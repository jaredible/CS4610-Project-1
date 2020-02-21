
<?php

// CREATE: INSERT INTO table_name (column1, ...) VALUES (value1, ...);
// UPDATE: UPDATE table_name SET column1 = value1, ... WHERE column1 = value1, ...;
// DELETE: DELETE FROM table_name WHERE column1 = value1, ...;

session_start();

// Load configurations
require_once('../config/database.php');

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
$error = '';

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
            $sql = "INSERT INTO $current_table (" . implode(', ', $fields) . ') VALUES (?, ?, ?, ?)';
            echo $sql;
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", ...array("69", "69", "69", "69"));
                $course_number = $_POST['course_number'];
                $course_name = $_POST['course_name'];
                $credit_hours = $_POST['credit_hours'];
                $department = $_POST['department'];
                if (!mysqli_stmt_execute($stmt)) {
                    $error = mysqli_error($conn);
                }
            }
            mysqli_stmt_close($stmt);
            break;
        case 'update':
            $sql = 'UPDATE course SET course_number = ?, course_name = ?, credit_hours = ?, department = ? WHERE course_number = ? AND course_name = ? AND credit_hours = ? AND department = ?';
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssssssss", $new_course_number, $new_course_name, $new_credit_hours, $new_department, $old_course_number, $old_course_name, $old_credit_hours, $old_department);
                $new_course_number = $_POST['new_course_number'];
                $new_course_name = $_POST['new_course_name'];
                $new_credit_hours = $_POST['new_credit_hours'];
                $new_department = $_POST['new_department'];
                $old_course_number = $_POST['old_course_number'];
                $old_course_name = $_POST['old_course_name'];
                $old_credit_hours = $_POST['old_credit_hours'];
                $old_department = $_POST['old_department'];
                if (!mysqli_stmt_execute($stmt)) {
                    $error = mysqli_error($conn);
                }
            }
            mysqli_stmt_close($stmt);
            break;
        case 'delete':
            $sql = 'DELETE FROM course WHERE course_number = ? AND course_name = ? AND credit_hours = ? AND department = ?';
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", $course_number, $course_name, $credit_hours, $department);
                $course_number = $_POST['course_number'];
                $course_name = $_POST['course_name'];
                $credit_hours = $_POST['credit_hours'];
                $department = $_POST['department'];
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
                <div class="ui top attached menu">
                    <a class="item"><i class="sidebar icon"></i>Tables</a>
                    <a class="item" href="index.php"><i class="home icon"></i>Home</a>
                    <div class="right menu">
                        <a class="item" href="login.php"><i class="sign <?php echo 0 === 1 ? 'out' : 'in' ?> icon"></i><?php echo 'Log ' . (0 === 1 ? 'Out' : 'In') ?></a>
                        <a class="item" onclick="introJs().start()"><i class="help icon"></i>Help</a>
                    </div>
                </div>
                <div class="ui bottom attached segment pushable" style="margin-bottom: 0;">
                    <div class="ui inverted labeled icon left inline vertical sidebar menu">
                        <?php foreach ($tables as $table): ?>
                            <a class="item <?php if ($table === $current_table) echo 'active' ?>" href="?table=<?php echo $table ?>"><i class="table icon"></i><?php echo ucwords(str_replace("_", " ", $table)) ?></a>
                        <?php endforeach ?>
                    </div>
                    <div class="pusher">
                        <div class="ui container">
                            <h1 class="ui header" data-step="1" data-intro="Testing1!" style="margin-top: 1.5rem; margin-bottom: 1.5rem; text-align: center !important;"><?php echo ucwords(str_replace("_", " ", $current_table)) ?></h1>
                            <div class="ui segment" style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
                                <div class="ui stackable grid">
                                    <div class="row" style="padding-bottom: 0;">
                                        <div class="sixteen wide column">
                                            <h3 class="ui left floated header" data-step="2" data-intro="Testing2!" style="position: absolute; bottom: 0; margin-bottom: 0;">Table data</h3>
                                            <div class="ui basic right floated buttons">
                                                <button class="ui button" onclick="data_table.create_enter()"><i class="plus icon"></i>New Row</button>
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
                                    <div class="row" style="padding-bottom: 0;">
                                        <div class="sixteen wide column">
                                            <h3 class="ui left floated header" data-step="3" data-intro="Testing3!" style="position: absolute; bottom: 0; margin-bottom: 0;">Table structure</h3>
                                            <div class="ui basic right floated buttons">
                                                <button class="ui button" onclick="structure_table.create_enter()"><i class="plus icon"></i>New Column</button>
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
                        <footer class="ui center aligned black footer segment">Made with <i class="red fitted heart icon"></i> by <a href="https://jaredible.net" target="_blank">Jaredible</a></footer>
                    </div>
                </div>
            </div>
        </div>

        <form id="action-form" method="post" style="display: none !important;"></form>

        <data id="error" value="<?php echo $error ?>" style="display: none !important;"><?php echo $error ?></data>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.9.3/intro.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<?php mysqli_close($conn) ?>
