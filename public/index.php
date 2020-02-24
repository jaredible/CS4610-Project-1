<!-- Supports PHP 5.6, although support for it has been discontinued since January 10, 2019. Developed using PHP 5.6.40. -->
<?php
// Creates a session or resumes the current one based on a session identifier passed via a GET or POST request, or passed via a cookie.
session_start();

// Load database configurations to be used in this script.
require_once('../config/database.php');

// This is here so that we know that there can be errors.
$error = '';

// Handle login/logout
if (isset($_POST['log-action'])) {
    switch ($_POST['log-action']) {
        case 'login':
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($username === $db_user && $password === $db_pass) {
                $_SESSION['logged-in'] = true;

                if (isset($_POST['remember'])) {
                    if ($_POST['remember'] === 'on') {
                        setcookie('selector', $username, time() + 60 * 60 * 24);
                        setcookie('validator', $password, time() + 60 * 60 * 24);
                    }
                }
            } else {
                $error = 'Incorrect username or password!';
            }

            break;
        case 'logout':
            if ($_SESSION['logged-in']) {
                $_SESSION['logged-in'] = false;
                setcookie('selector', '', time() - 60 * 60);
                setcookie('validator', '', time() - 60 * 60);
            }

            break;
    }
} else {
    if(isset($_COOKIE['selector']) && isset($_COOKIE['validator'])) {
        if ($_COOKIE['selector'] === $db_user && $_COOKIE['validator'] === $db_pass) {
            $_SESSION['logged-in'] = true;
        }
    }
}

// Attempt to connect to the database, and if not, then display an error message
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die('Could not connect: ' . mysqli_connect_error());
}

$param_table = filter_input(INPUT_GET, 'table');
$param_order = filter_input(INPUT_GET, 'orderby');

// We need to populate these variables
$current_table = '';
$tables = array();
$fields = array();
$data = array(); // 2D array

// Get all table names in alphabetical order
$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$db_name' ORDER BY TABLE_NAME ASC";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    $tables[] = $row[0];
}

// Set the current table name before we get column names and data
$current_table = $param_table;
if (!isset($current_table)) {
    $current_table = $tables[0];
}

// Get current table's column names
$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$current_table'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    $fields[] = $row[0];
}

// Before getting the current table's data, check if created, updated, or deleted
if (isset($_POST['table-action'])) {
    switch ($_POST['table-action']) {
        case 'create':
            $sql_fields = implode(', ', $fields);
            $sql_values = implode(', ', array_fill(0, count($fields), '?'));
            $sql = "INSERT INTO $current_table ($sql_fields) VALUES ($sql_values)";

            $stmt = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($stmt, $sql)) {
                $sql_values_array = array();
                foreach ($fields as $field => $field_value) {
                    $sql_values_array[] = $_POST[$field_value];
                }

                $types = str_repeat("s", count($fields));
                $params = $sql_values_array;

                $bind_names[] = $types;
                for ($i = 0; $i < count($params); $i++) {
                    $bind_name = 'bind' . $i;
                    $$bind_name = $params[$i];
                    $bind_names[] = &$$bind_name;
                }

                call_user_func_array(array($stmt, 'bind_param'), $bind_names);

                if (!mysqli_stmt_execute($stmt)) {
                    $error = mysqli_error($conn);
                }
            }
            mysqli_stmt_close($stmt);
            break;
        case 'update':
            $sql_set = implode(', ', array_map(function ($field) { return $field . ' = ?'; }, $fields));
            $sql_where = implode(' AND ', array_map(function ($field) { return $field . ' = ?'; }, $fields));
            $sql = "UPDATE $current_table SET $sql_set WHERE $sql_where";

            $stmt = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($stmt, $sql)) {
                $sql_set_array = array();
                $sql_where_array = array();
                foreach ($fields as $field => $field_value) {
                    $sql_set_array[] = $_POST['new_' . $field_value];
                    $sql_where_array[] = $_POST['old_' . $field_value];
                }

                $types = str_repeat("s", count($fields) * 2);
                $params = array_merge($sql_set_array, $sql_where_array);

                $bind_names[] = $types;
                for ($i = 0; $i < count($params); $i++) {
                    $bind_name = 'bind' . $i;
                    $$bind_name = $params[$i];
                    $bind_names[] = &$$bind_name;
                }

                call_user_func_array(array($stmt, 'bind_param'), $bind_names);

                if (!mysqli_stmt_execute($stmt)) {
                    $error = mysqli_error($conn);
                }
            }
            mysqli_stmt_close($stmt);
            break;
        case 'delete':
            $sql_delete = implode(' AND ', array_map(function ($field) { return $field . ' = ?'; }, $fields));
            $sql = "DELETE FROM $current_table WHERE $sql_delete";

            $stmt = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($stmt, $sql)) {
                $sql_where_array = array();
                foreach ($fields as $field => $field_value) {
                    $sql_where_array[] = $_POST[$field_value];
                }

                $types = str_repeat("s", count($fields));
                $params = $sql_where_array;

                $bind_names[] = $types;
                for ($i = 0; $i < count($params); $i++) {
                    $bind_name = 'bind' . $i;
                    $$bind_name = $params[$i];
                    $bind_names[] = &$$bind_name;
                }

                call_user_func_array(array($stmt, 'bind_param'), $bind_names);

                if (!mysqli_stmt_execute($stmt)) {
                    $error = mysqli_error($conn);
                }
            }
            mysqli_stmt_close($stmt);
            break;
    }
}

// Get current table's data
$query = "SELECT * FROM $current_table" . (isset($param_order) ? " ORDER BY $param_order" : '');
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $data[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>University Portal</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.9.3/introjs.min.css">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>

        <div class="context">

            <div class="ui top attached segment" style="padding: 0; border: 0; margin-top: 0;">
            
                <!-- -->
                <div class="ui top attached menu">
                    <a id="sidebar-menu-item" class="item"><i class="sidebar icon"></i>Tables</a>
                    <div class="right menu">
                        <a id="login-menu-item" class="item" onclick="log<?php echo isset($_SESSION['logged-in']) && $_SESSION['logged-in'] ? 'out' : 'in' ?>()"><i class="sign <?php echo isset($_SESSION['logged-in']) && $_SESSION['logged-in'] ? 'out' : 'in' ?> icon"></i><?php echo 'Log ' . (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] ? 'Out' : 'In') ?></a>
                        <a id="help-menu-item" class="item" onclick="show_help()"><i class="help icon"></i>Help</a>
                    </div>
                </div>

                <!-- -->
                <div class="ui bottom attached segment pushable" style="margin-bottom: 0;">
                    <div class="ui inverted labeled icon left inline vertical sidebar menu">
                        <?php foreach ($tables as $table): ?>
                            <a class="item <?php if ($table === $current_table) echo 'active' ?>" href="?table=<?php echo $table ?>"><i class="table icon"></i><?php echo ucwords(str_replace("_", " ", $table)) ?></a>
                        <?php endforeach ?>
                    </div>

                    <!-- -->
                    <div class="pusher">
                        <div class="ui container">
                            <h1 class="ui header" style="margin-top: 1.5rem; margin-bottom: 1.5rem; text-align: center !important;"><?php echo ucwords(str_replace("_", " ", $current_table)) ?></h1>
                            <div class="ui segment" style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
                                <div class="ui stackable grid">
                                    <div class="row" style="padding-bottom: 0;">
                                        <div class="sixteen wide column">
                                            <div class="ui basic right floated buttons">
                                                <button id="data-table-new-row-button" class="ui button" onclick="data_table.create_enter()"><i class="plus icon"></i>New Row</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="sixteen wide column">
                                            <table id="data-table" class="ui center aligned table" data-table="<?php echo $current_table ?>">
                                                <thead class="full-width">
                                                    <tr>
                                                        <?php foreach ($fields as $field_name): ?>
                                                            <th data-field="<?php echo $field_name ?>"><?php echo ucwords(str_replace("_", " ", $field_name)) ?></th>
                                                        <?php endforeach ?>
                                                        <th class="collapsing">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr style="display: none !important;">
                                                        <?php foreach ($fields as $field_name): ?>
                                                            <td class="input">
                                                                <div class="ui fluid input">
                                                                    <input type="text" name="<?php echo $field_name ?>" placeholder="<?php echo ucwords(str_replace("_", " ", $field_name)) ?>">
                                                                </div>
                                                            </td>
                                                        <?php endforeach ?>
                                                        <td>
                                                            <div class="ui basic icon buttons">
                                                                <button class="ui button data-table-create-leave-button" onclick="data_table.create_leave()"><i class="cancel icon"></i></button>
                                                                <button class="ui button data-table-create-submit-button" onclick="data_table.create_submit()"><i class="save icon"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php foreach ($data as $row => $row_value): ?>
                                                        <tr data-row="<?php echo md5(implode(',', $row_value)) ?>">
                                                            <?php foreach ($row_value as $col => $col_value): ?>
                                                                <td class="data" data-field="<?php echo $col ?>" data-value="<?php echo $col_value ?>">
                                                                    <data><?php echo $col_value ?></data>
                                                                </td>
                                                            <?php endforeach ?>
                                                            <td>
                                                                <div class="ui basic icon buttons">
                                                                    <button class="ui button data-table-update-leave-button" onclick="data_table.update_leave()" style="display: none !important;"><i class="cancel icon"></i></button>
                                                                    <button class="ui button data-table-update-submit-button" onclick="data_table.update_submit()" style="display: none !important;"><i class="save icon"></i></button>
                                                                    <button class="ui button data-table-update-enter-button" onclick="data_table.update_enter('<?php echo md5(implode(',', $row_value)) ?>')" style=""><i class="edit icon"></i></button>
                                                                    <button class="ui button data-table-delete-submit-button" onclick="data_table.delete_submit('<?php echo md5(implode(',', $row_value)) ?>')" style=""><i class="trash icon"></i></button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <?php foreach ($fields as $field_name): ?>
                                                            <td>
                                                                <div class="ui basic icon buttons">
                                                                    <button class="ui button data-table-order-by-asc-button" onclick="data_table.order_by('<?php echo $field_name ?>', 'asc')"><i class="fitted up arrow icon"></i></button>
                                                                    <button class="ui button data-table-order-by-desc-button" onclick="data_table.order_by('<?php echo $field_name ?>', 'desc')"><i class="fitted down arrow icon"></i></button>
                                                                </div>
                                                            </td>
                                                        <?php endforeach ?>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 1.5rem; padding-bottom: 0;">
                                        <div class="sixteen wide column">
                                            <div class="ui basic right floated buttons">
                                                <?php if(isset($_SESSION['logged-in']) && $_SESSION['logged-in']): ?>
                                                    <button id="data-table-new-column-button" class="ui button" onclick="structure_table.create_enter()"><i class="plus icon"></i>New Column</button>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="sixteen wide column">
                                            <table id="structure-table" class="ui center aligned table" data-table="<?php echo $current_table ?>">
                                                <thead class="full-width">
                                                    <tr>
                                                        <?php for ($i = 0; $i < 6; $i++): ?>
                                                            <th><?php echo "Column $i" ?></th>
                                                        <?php endfor ?>
                                                        <th class="collapsing">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php for ($i = 0; $i < 6; $i++): ?>
                                                        <tr>
                                                            <?php for ($j = 0; $j < 6; $j++): ?>
                                                                <td><?php echo "Cell $i, $j" ?></td>
                                                            <?php endfor ?>
                                                            <td>
                                                                <div class="ui basic icon buttons">
                                                                    <button class="ui button"><i class="edit icon"></i></button>
                                                                    <button class="ui button"><i class="trash icon"></i></button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endfor ?>
                                                </tbody>
                                                <tfoot></tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- -->
                        <footer class="ui center aligned footer segment">Made with <i class="blue fitted heart icon"></i> by <a href="https://jaredible.net" target="_blank">Jaredible</a></footer>

                    </div>

                </div>

            </div>

        </div>

        <!-- -->
        <div class="ui mini modal">

            <!-- -->
            <div class="header">Login</div>

            <!-- -->
            <div class="content">

                <!-- -->
                <form class="ui form" method="post">

                    <!-- -->
                    <input type="hidden" name="log-action" value="login">
                    
                    <!-- -->
                    <div class="field">
                        
                        <!-- -->
                        <input type="text" name="username", placeholder="Username">

                    </div>

                    <!-- -->
                    <div class="field">
                        <!-- -->
                        <input type="password" name="password", placeholder="Password">

                    </div>

                    <!-- -->
                    <div class="field">

                        <!-- -->
                        <div class="ui checkbox">

                            <!-- -->
                            <input id="remember" class="hidden" type="checkbox" name="remember" tabindex="0">

                            <!-- -->
                            <label for="remember">Remember me</label>

                        </div>

                    </div>

                    <!-- -->
                    <div class="ui basic buttons">
                        <!-- -->
                        <button class="ui button" type="submit" name="submit-login">Submit</button>

                    </div>

                </form>

            </div>

        </div>

        <!-- -->
        <form id="action-form" method="post" style="display: none !important;"></form>

        <!-- -->
        <data id="error" value="<?php echo $error ?>" style="display: none !important;"></data>

        <!-- -->
        <data id="logged-in" value="<?php echo isset($_SESSION['logged-in']) && $_SESSION['logged-in'] ? 'true' : 'false' ?>" style="display: none !important;"><?php echo $error ?></data>

        <!--
            Get JavaScript from a CDN so that the browser can download
            them instead of the server and get the minified versions so
            that the file size is the smallest possible.
        -->
        <!-- Load into this page JQuery's minified JavaScript file from a CDN. -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
        <!-- Load into this page Fomantic-UI's minified JavaScript file from a CDN. -->
        <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.js"></script>
        <!-- Load into this page Intro.js's minified JavaScript file from a CDN. -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.9.3/intro.min.js"></script>
        <!-- Load into this page the main JavaScript file from this domain. -->
        <script src="js/main.js"></script>
    </body>
</html>
<?php mysqli_close($conn) ?>