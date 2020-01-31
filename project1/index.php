<?php
require_once "config/database.php";

if (isset($_SESSION["isAdmin"])) {
} else {
}

$password = "password";
$hash = password_hash($password, PASSWORD_BCRYPT, [
    "cost" => 12
]);

//echo $hash;
if (password_verify($password, $hash)) {
    //echo "Yes";
} else {
    //echo "No";
}
?>
<!DOCTYPE html>
<html>
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
            <div class="item">
                <img src="img/logo.png">
            </div>
            <div class="right menu">
                <div class="item">
                    <div class="ui teal button">
                        <i class="shield icon"></i> View as Administrator
                    </div>
                </div>
            </div>
        </div>
        <div class="main ui container">
            <div class="ui form">
                <div class="two fields">
                    <div class="field">
                        <label>Database</label>
                        <div class="ui selection dropdown">
                            <input type="hidden" name="database" value="<?php if (isset($_GET['database'])) { echo $_GET['database']; } ?>" onchange="onChangeDatabase()">
                            <i class="dropdown icon"></i>
                            <div class="default text">Select one...</div>
                            <div class="menu">
                                <div class="item" data-value="test1">Test1</div>
                                <div class="item" data-value="test2">Test2</div>
                                <div class="item" data-value="test3">Test3</div>
                            </div>
                        </div>
                    </div>
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
            <div class="ui divider"></div>
            <div class="ui stackable grid">
                <div class="row">
                    <div class="eight wide column">
                        <div>
                            <label>
                                Show
                                <div class="ui selection dropdown" style="min-width: 0;">
                                    <input type="hidden" name="entries" value="10">
                                    <i class="dropdown icon"></i>
                                    <div class="text">10</div>
                                    <div class="menu">
                                        <div class="item active selected" data-value="10">10</div>
                                        <div class="item" data-value="25">25</div>
                                        <div class="item" data-value="50">50</div>
                                    </div>
                                </div>
                                entries
                            </label>
                        </div>
                    </div>
                    <div class="right aligned eight wide column">
                        <div class="ui basic buttons">
                            <button class="ui button">New</button>
                            <button class="ui button">Edit</button>
                            <button class="ui button">Delete</button>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding: 0;">
                    <div class="sixteen wide column">
                        <table class="ui sortable celled table">
                            <thead class="full-width">
                                <tr>
                                    <th class="no-sort"></th>
                                    <th>Column1</th>
                                    <th>Column2</th>
                                    <th>Column3</th>
                                    <th>Column4</th>
                                    <th>Column5</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < 5; $i++) {
                                ?>
                                <tr>
                                    <td class="collapsing">
                                        <div class="ui fitted slider checkbox">
                                            <input type="checkbox"> <label></label>
                                        </div>
                                    </td>
                                    <td>Data<?php echo $i + 1 ?></td>
                                    <td>Data<?php echo $i + 2 ?></td>
                                    <td>Data<?php echo $i + 3 ?></td>
                                    <td>Data<?php echo $i + 4 ?></td>
                                    <td>Data<?php echo $i + 5 ?></td>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr style="display: none;">
                                    <td class="collapsing">
                                        <div class="ui fitted slider checkbox">
                                            <input type="checkbox"> <label></label>
                                        </div>
                                    </td>
                                    <td>Data0</td>
                                    <td>Data1</td>
                                    <td>Data2</td>
                                    <td>Data3</td>
                                    <td>Data4</td>
                                </tr>
                            </tbody>
                            <tfoot class="full-width">
                                <tr>
                                    <th></th>
                                    <th>Column1</th>
                                    <th>Column2</th>
                                    <th>Column3</th>
                                    <th>Column4</th>
                                    <th>Column5</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="seven wide column">
                        <div>
                            Showing 1 to 10 of 57 entries
                        </div>
                    </div>
                    <div class="right aligned nine wide column">
                        <div>
                            <div class="ui pagination menu">
                                <a class="icon item disabled">
                                    <i class="left chevron icon"></i>
                                </a>
                                <a class="active item disabled">1</a>
                                <a class="item">2</a>
                                <a class="item">3</a>
                                <a class="icon item">
                                    <i class="right chevron icon"></i>
                                </a>
                            </div>
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