
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
$param_order = filter_input(INPUT_GET, 'order');

// We need to populate these variables
$current_table = '';
$tables = array();
$fields = array();
$data = array(); // 2D array
$error = array('type' => '', 'message' => '');

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
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'create':
            $sql = 'INSERT INTO course (course_number, course_name, credit_hours, department) VALUES (?, ?, ?, ?)';
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", $course_number, $course_name, $credit_hours, $department);
                $course_number = $_POST['course_number'];
                $course_name = $_POST['course_name'];
                $credit_hours = $_POST['credit_hours'];
                $department = $_POST['department'];
                if (mysqli_stmt_execute($stmt)) {
                    header('Location: index.php');
                    exit();
                } else {
                    $error['type'] = 'sql create';
                    $error['message'] = mysqli_error($conn);
                }
            }
            mysqli_stmt_close($stmt);
            break;
        case 'update':
            echo 'update<br>';
            echo '<pre>' . var_dump($_POST) . '</pre>';
            break;
        case 'delete':
            $sql = 'DELETE FROM course WHERE course_number = ? AND course_name = ? AND credit_hours = ? AND department = ?';
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", $course_number, $course_name, $credit_hours, $department);
                $course_number = $_POST['course_number'];
                $course_name = $_POST['course_name'];
                $credit_hours = $_POST['credit_hours'];
                $department = $_POST['department'];
                if (mysqli_stmt_execute($stmt)) {
                    header('Location: index.php');
                    exit();
                } else {
                    $error['type'] = 'sql delete';
                    $error['message'] = mysqli_error($conn);
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

// TODO: testing
//$error['type'] = 'sql';
//$error['message'] = 'Something went wrong when creating!';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>University Portal</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.css">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <div class="context">
            <data id="error" value="<?php echo $error['type'] ?>" hidden><?php echo $error['message'] ?></data>
            <div class="ui top attached segment" style="padding: 0; border: 0; margin-top: 0;">
                <div class="ui top attached menu">
                    <a class="item"><i class="sidebar icon"></i>Tables</a>
                    <a class="item" href="index.php"><i class="home icon"></i>Home</a>
                    <div class="right menu">
                        <a class="item" href="login.php"><i class="sign <?php echo 0 === 1 ? 'out' : 'in' ?> icon"></i><?php echo 'Log ' . (0 === 1 ? 'Out' : 'In') ?></a>
                        <!--<a class="item"><i class="help icon"></i>Help</a>-->
                    </div>
                </div>
                <div class="ui bottom attached segment pushable">
                    <div class="ui inverted labeled icon left inline vertical sidebar menu">
                        <?php foreach ($tables as $table): ?>
                            <a class="item <?php if ($table === $current_table) echo 'active' ?>" href="?table=<?php echo $table ?>"><i class="table icon"></i><?php echo ucwords(str_replace("_", " ", $table)) ?></a>
                        <?php endforeach ?>
                    </div>
                    <div class="pusher">
                        <div class="ui container">
                            <div class="ui segment" style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
                                <div class="ui stackable grid">
                                    <div class="row">
                                        <div class="sixteen wide column">
                                            <h2 class="ui dividing header"><?php echo ucwords(str_replace("_", " ", $current_table)) ?></h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="sixteen wide column">
                                            <div class="ui basic right floated buttons">
                                                <button class="ui button" onclick="open_create()"><i class="plus icon"></i>New Row</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="sixteen wide column">
                                            <table class="ui center aligned table" data-table="<?php echo $current_table ?>">
                                                <thead class="full-width">
                                                    <tr>
                                                        <?php foreach ($fields as $field_name): ?>
                                                            <th data-field="<?php echo $field_name ?>"><?php echo ucwords(str_replace("_", " ", $field_name)) ?></th>
                                                        <?php endforeach ?>
                                                        <th class="collapsing">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr style="display: none;">
                                                        <?php foreach ($fields as $field_name): ?>
                                                            <td class="input">
                                                                <div class="ui fluid input">
                                                                    <input type="text" name="<?php echo $field_name ?>" placeholder="<?php echo ucwords(str_replace("_", " ", $field_name)) ?>">
                                                                </div>
                                                            </td>
                                                        <?php endforeach ?>
                                                        <td>
                                                            <div class="ui basic icon buttons">
                                                                <button class="ui button" onclick="cancel_create()"><i class="cancel icon"></i></button>
                                                                <button class="ui button" onclick="create()"><i class="save icon"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php foreach ($data as $row => $row_value): ?>
                                                        <tr data-row="<?php echo $row ?>">
                                                            <?php foreach ($row_value as $col => $col_value): ?>
                                                                <td class="data" data-field="<?php echo $col ?>" data-value="<?php echo $col_value ?>"><?php echo $col_value ?></td>
                                                            <?php endforeach ?>
                                                            <td>
                                                                <div class="ui basic icon buttons">
                                                                    <!--<button class="ui button" onclick="cancel_edit()"><i class="cancel icon"></i></button>
                                                                    <button class="ui button" onclick="send_edit()"><i class="save icon"></i></button>-->
                                                                    <button class="ui button" onclick="open_edit('<?php echo $row ?>')"><i class="edit icon"></i></button>
                                                                    <button class="ui button" onclick="send_remove('<?php echo $row ?>')"><i class="trash icon"></i></button>
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
                                                                    <button class="ui button" onclick="order('<?php echo $field_name ?>', 'asc')"><i class="fitted up arrow icon"></i></button>
                                                                    <button class="ui button" onclick="order('<?php echo $field_name ?>', 'desc')"><i class="fitted down arrow icon"></i></button>
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
                </div>
            </div>
        </div>

        <form id="createForm" method="post" style="display: none;">
            <input type="hidden" name="action" value="create">
            <?php foreach ($fields as $field_name): ?>
                <input class="data" type="text" name="<?php echo $field_name ?>" placeholder="<?php echo ucwords(str_replace("_", " ", $field_name)) ?>">
            <?php endforeach ?>
        </form>

        <form id="updateForm" method="post" style="display: none;">
            <input type="hidden" name="action" value="update">
            <?php foreach ($fields as $field_name): ?>
                <input type="hidden" name="<?php echo 'old_' . $field_name ?>" value="">
                <input type="text" name="<?php echo 'new_' . $field_name ?>" placeholder="<?php echo ucwords(str_replace("_", " ", $field_name)) ?>">
            <?php endforeach ?>
            <input type="submit" name="submit" value="Update">
        </form>

        <form id="deleteForm" method="post" style="display: none;">
            <input type="hidden" name="action" value="delete">
            <?php foreach ($fields as $field_name): ?>
                <input class="data" type="hidden" name="<?php echo $field_name ?>" value="">
            <?php endforeach ?>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<?php mysqli_close($conn) ?>
