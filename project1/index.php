<?php
session_start();

//$_SESSION["user_id"] = 69;

define("DB_SERVER", "192.168.64.3");
define("DB_USERNAME", "admin");
define("DB_PASSWORD", "");
define("DB_NAME", "university");
define("ADMIN_PASSWORD_HASH", "");

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

//if (isset($_SESSION["isAdmin"])) {
//} else {
//}

//$password = "password";
//$hash = password_hash($password, PASSWORD_BCRYPT, [
//    "cost" => 12
//]);

//echo $hash;
//if (password_verify($password, $hash)) {
    //echo "Yes";
//} else {
    //echo "No";
//}

$sql = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'university'";
$result = mysqli_query($conn, $sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $table_names[] = $row[0];
    }
}

$sql = "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'university' AND TABLE_NAME = 'course'";
$result = mysqli_query($conn, $sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        echo $row[0] . "<br>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>RDBMSI</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.css">
        <link rel="stylesheet" type="text/css" href="css/introjs.min.css">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <div class="ui secondary stackable menu">
        </div>
        <div class="main ui container">
            <div class="ui segment">
                <div class="ui form">
                    <div class="field">
                        <label>Table</label>
                        <div class="ui selection dropdown">
                            <input type="hidden" name="table" value="<?php if (isset($_GET['table'])) { echo $_GET['table']; } ?>" onchange="onChangeTable()">
                            <i class="dropdown icon"></i>
                            <div class="default text">Select one...</div>
                            <div class="menu">
                                <div class="item" data-value="test1">Test1</div>
                                <div class="item" data-value="test2">Test2</div>
                                <div class="item" data-value="test3">Test3</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui segment">
                <div class="ui stackable grid">
                    <div class="row">
                        <div class="eight wide column">
                            <div class="ui basic buttons">
                                <button class="ui button">New</button>
                            </div>
                        </div>
                        <div class="right aligned eight wide column">
                            Sort
                            <div class="ui selection dropdown" style="min-width: 0;">
                                <input type="hidden" name="column" value="1">
                                <i class="dropdown icon"></i>
                                <div class="text">Column1</div>
                                <div class="menu">
                                    <div class="item" data-value="1">Column1</div>
                                    <div class="item" data-value="2">Column2</div>
                                    <div class="item" data-value="3">Column3</div>
                                    <div class="item" data-value="4">Column4</div>
                                    <div class="item" data-value="5">Column5</div>
                                </div>
                            </div>
                            by
                            <div class="ui selection dropdown" style="min-width: 0;">
                                <input type="hidden" name="column" value="asc">
                                <i class="dropdown icon"></i>
                                <div class="text">Ascending</div>
                                <div class="menu">
                                    <div class="item" data-value="asc">Ascending</div>
                                    <div class="item" data-value="desc">Descending</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="sixteen wide column">
                            <table class="ui single line table center aligned">
                                <thead class="full-width">
                                    <tr>
                                        <th>Column1</th>
                                        <th>Column2</th>
                                        <th>Column3</th>
                                        <th>Column4</th>
                                        <th>Column5</th>
                                        <th class="collapsing">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < 5; $i++) {
                                    ?>
                                    <tr>
                                        <?php
                                        for ($j = 0; $j < 5; $j++) {
                                        ?>
                                        <td>
                                            Data<?php echo $i + 2 ?>
                                        </td>
                                        <?php
                                        }
                                        ?>
                                        <td>
                                            <div class="ui basic buttons">
                                                <button class="ui button disabled">Edit</button>
                                                <button class="ui button disabled">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Column1</td>
                                        <td>Column2</td>
                                        <td>Column3</td>
                                        <td>Column4</td>
                                        <td>Column5</td>
                                        <td>Action</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.js"></script>
        <script src="js/intro.min.js"></script>
        <script src="js/tablesort.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<?php
mysqli_close($conn);
?>