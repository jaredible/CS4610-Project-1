<?php

// CREATE: INSERT INTO table_name (column1, ...) VALUES (value1, ...);
// UPDATE: UPDATE table_name SET column1 = value1, ... WHERE column1 = value1, ...;
// DELETE: DELETE FROM table_name WHERE column1 = value1, ...;

session_start();

// Load configurations
require_once('../config/config.php');

// Attempt to connect to the database, and if not, then display an error message
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die('Could not connect: ' . mysqli_connect_error());
}

$param_username = filter_input(INPUT_POST, 'username');
$param_password = filter_input(INPUT_POST, 'password');

if (isset($param_username) && isset($param_password)) {
    if ($param_username === $db_user && $param_password === $db_pass) {
        $_SESSION['logged_in'] = true;
    }
}

$param_table = filter_input(INPUT_GET, 'table');

// We need to populate these variables
$current_table = '';
$tables = array();
$fields = array();
$data = array(); // Will be a multidimensional array

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

if (isset($_POST['submit'])) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                foreach ($fields as $field_name) {
                }

                //$sql = "INSERT INTO $current_table (" . implode(', ', $fields) . ') VALUES (' . ;
                //echo $sql;
                break;
            case 'update':
                break;
            case 'delete':
                break;
        }
    }
}

// Get current table's data
$query = "SELECT * FROM $current_table";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $data[] = $row;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>University Portal</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.css">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <div class="ui inverted left vertical sidebar menu sidebar">
            <?php foreach ($tables as $table): ?>
                <a class="item <?php if ($table === $current_table) echo 'active' ?>" href="?table=<?php echo $table ?>"><?php echo ucwords(str_replace("_", " ", $table)) ?></a>
            <?php endforeach ?>
        </div>
        <div class="dimmed pusher">
            <div class="ui container">
                <div class="ui segment">
                    <div class="ui stackable grid">
                        <div class="row">
                            <div class="sixteen wide column">
                                <div class="ui basic buttons">
                                    <button class="ui button" onclick="push()">Test</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="sixteen wide column">
                                <table class="ui center aligned table" data-table="<?php echo $current_table ?>">
                                    <thead class="full-width">
                                        <tr>
                                            <?php foreach ($fields as $field_name): ?>
                                                <th data-field="<?php echo $field_name ?>"><?php echo $field_name ?></th>
                                            <?php endforeach ?>
                                            <th class="collapsing">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data as $row): ?>
                                            <tr>
                                                <?php foreach ($row as $col => $col_value): ?>
                                                    <td data-value="<?php $col_value ?>"><?php echo $col_value ?></td>
                                                <?php endforeach ?>
                                                <td>
                                                    <div class="ui basic icon buttons">
                                                        <button class="ui action button" onclick="edit('<?php echo 'table=' . $current_table . '&' . http_build_query($row) ?>')"><i class="edit icon"></i></button>
                                                        <button class="ui action button"><i class="trash icon"></i></button>
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
                                                        <button class="ui sort button"><i class="fitted up arrow icon"></i></button>
                                                        <button class="ui sort button"><i class="fitted down arrow icon"></i></button>
                                                    </div>
                                                </td>
                                            <?php endforeach ?>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Check if the form has been submitted -->
        <?php if (isset($_POST['submit'])): ?>

            <!-- Check if an action exists -->
            <?php if (isset ($_POST['action'])): ?>
                
                <!-- Check if is a create action -->
                <?php if ($_POST['action'] === 'create'): ?>

                    <!-- Provide a form for creating -->
                    <form method="post">
                        <?php foreach ($fields as $field_name) ?>
                            <input type="text" name="<?php echo $field_name ?>" placeholder="<?php echo ucwords(str_replace("_", " ", $field_name)) ?>">
                        <?php ?>
                        <input type="submit" name="submit" value="Enter">
                    </form>

                <?php endif ?>

                <!-- Check if is an update action -->
                <?php if ($_POST['action'] === 'update'): ?>

                    <!-- Provide a form for updating -->
                    <form method="post">
                    </form>

                <?php endif ?>

                <!-- Check if is a delete action -->
                <?php if ($_POST['action'] === 'delete'): ?>

                    <!-- Provide a form for deleting -->
                    <form method="post">
                    </form>

                <?php endif ?>

            <?php endif ?>

        <?php endif ?>

        <form id="createForm" method="post" style="display: hidden;">
            <?php foreach ($fields as $field_name): ?>
                <input type="text" name="<?php echo $field_name ?>" placeholder="<?php echo ucwords(str_replace("_", " ", $field_name)) ?>">
            <?php endforeach ?>
            <input type="submit" name="submit" value="Enter">
        </form>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<?php mysqli_close($conn) ?>