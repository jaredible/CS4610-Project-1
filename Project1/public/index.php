<?php

// CREATE: INSERT INTO table_name (column1, ...) VALUES (value1, ...);
// UPDATE: UPDATE table_name SET column1 = value1, ... WHERE column1 = value1, ...;
// DELETE: DELETE FROM table_name WHERE column1 = value1, ...;

session_start();

// Load configurations
require_once('../config/database.php');

// Attempt to connect to the database, and if not, then display an error message
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die('Could not connect: ' . mysqli_connect_error());
}

$param_username = filter_input(INPUT_POST, 'username');
$param_password = filter_input(INPUT_POST, 'password');

if (isset($param_username) && isset($param_password)) {
    if ($param_username === DB_USER && $param_password === DB_PASS) {
        $_SESSION['logged_in'] = true;
    }
}

$param_table = filter_input(INPUT_GET, 'table');

// We need to populate these variables
$current_table_name = '';
$tables_names = array();
$column_names = array();
$data = array(); // Will be a multidimensional array

// Get all table names in alphabetical order
$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'universitydb' ORDER BY TABLE_NAME ASC";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    $table_names[] = $row[0];
}

// Set the current table name before we get column names and data
$current_table_name = $param_table;
if (!isset($current_table_name)) {
    $current_table_name = $table_names[0];
}

// Get current table's column names
$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'universitydb' AND TABLE_NAME = '$current_table_name'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    $column_names[] = $row[0];
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo 'GET';
        break;
    case 'POST':
        echo var_dump($_GET);
        echo var_dump($_POST);
        echo 'POST';
        break;
}

// Get current table's data
$query = "SELECT * FROM $current_table_name";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $i = 0;
    foreach ($row as $col) {
        $data[$i++][] = $col;
    }
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
        <button class="ui button"><?php echo 'Log ' . (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ? 'Out' : 'In') ?></button>

        <div class="ui inverted left vertical sidebar menu sidebar">
            <?php foreach ($table_names as $table_name): ?>
                <a class="item <?php if ($table_name === $current_table_name) echo 'active' ?>" href="?table=<?php echo $table_name ?>"><?php echo ucwords(str_replace("_", " ", $table_name)) ?></a>
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
                                <form action="" method="">
                                    <table class="ui center aligned table">
                                        <tbody>
                                            <tr>
                                                <?php foreach ($column_names as $column_name): ?>
                                                    <td>
                                                        <div class="ui fluid input">
                                                            <input type="text" placeholder="<?php echo ucwords(str_replace("_", " ", $column_name)) ?>">
                                                        </div>
                                                    </td>
                                                <?php endforeach ?>
                                                <td>
                                                    <div class="ui basic buttons">
                                                        <button class="ui button">New Row</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="sixteen wide column">
                                <table class="ui center aligned table">
                                    <thead class="full-width">
                                        <tr>
                                            <?php foreach ($column_names as $column_name): ?>
                                                <th><?php echo ucwords(str_replace("_", " ", $column_name)) ?></th>
                                            <?php endforeach ?>
                                            <th class="collapsing">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($data[0]); $i++): ?>
                                            <tr>
                                                <?php for ($j = 0; $j < count($column_names); $j++): ?>
                                                    <td><?php echo $data[$j][$i] ?></td>
                                                <?php endfor ?>
                                                <td>
                                                    <div class="ui basic icon buttons">
                                                        <button class="ui action button" onclick="edit(<?php echo $data[0][$i] ?>)"><i class="edit icon"></i></button>
                                                        <button class="ui action button" onclick="remove(<?php echo $data[0][$i] ?>)"><i class="trash icon"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endfor ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <?php for ($i = 0; $i < count($column_names); $i++): ?>
                                                <td>
                                                    <div class="ui basic icon buttons">
                                                        <button class="ui sort button"><i class="fitted up arrow icon"></i></button>
                                                        <button class="ui sort button"><i class="fitted down arrow icon"></i></button>
                                                    </div>
                                                </td>
                                            <?php endfor ?>
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

        <form method="post" style="display: hidden;">
            <input type="text" name="test" value="testing">
            <input type="submit" value="Test">
        </form>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<?php mysqli_close($conn) ?>