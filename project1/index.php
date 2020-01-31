<?php
require_once "config/database.php";
// page
// table
// query
// fromDate
// toDate
if (isset($_SESSION["user_id"])) {
} else {
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>DBMS API</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.2/dist/semantic.min.css">
        <link rel="stylesheet" type="text/css" href="css/introjs.min.css">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <div class="ui stackable menu">
            <div class="item">
                <img src="img/logo.png">
            </div>
            <div class="right menu">
                <div class="item">
                    <div class="ui primary button">
                        Log In
                    </div>
                </div>
            </div>
        </div>
        <div class="main ui container">
            <div class="ui form">
                <div class="two fields">
                    <div class="field">
                        <label>Database</label>
                        <div class="ui search selection dropdown">
                            <i class="dropdown icon"></i>
                            <input class="search" type="text">
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
                        <div class="ui search selection dropdown">
                            <i class="dropdown icon"></i>
                            <input class="search" type="text">
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
            <div>
            </div>
            <div class="ui divider"></div>
            <div class="ui segment">
                <div class="ui top attached label" style="dislay: block;">
                    Table Editor
                </div>
                <div>
                    <div class="ui primary button">
                        <i class="plus icon"></i> Create Record
                    </div>
                    <div class="ui negative button">
                        <i class="trash icon"></i> Delete Selected
                    </div>
                    <div class="ui floating labeled icon dropdown grey button">
                        <i class="dropdown icon"></i>
                        <span class="text">Export</span>
                        <div class="left menu">
                            <div class="item">Test1</div>
                            <div class="item">Test2</div>
                        </div>
                    </div>
                    <div class="ui grey button">
                        <i class="edit icon"></i> Edit
                    </div>
                </div>
                <table class="ui compact celled padded table">
                    <thead class="full-width">
                        <tr>
                            <th></th>
                            <th>
                                Column1
                                <i class="fitted sort icon"></i>
                            </th>
                            <th>
                                Column2
                                <i class="fitted sort up icon"></i>
                            </th>
                            <th>
                                Column3
                                <i class="fitted sort down icon"></i>
                            </th>
                            <th>
                                Column4
                                <i class="fitted sort icon"></i>
                            </th>
                            <th>
                                Column5
                                <i class="fitted sort icon"></i>
                            </th>
                            <th>Action</th>
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
                            <td>Data1</td>
                            <td>Data2</td>
                            <td>Data3</td>
                            <td>Data4</td>
                            <td>Data5</td>
                            <td>
                                <div class="ui primary mini button">
                                    <i class="save icon"></i> Save
                                </div>
                                <div class="ui grey mini button">
                                    <i class="edit icon"></i> Edit
                                </div>
                                <div class="ui negative mini button">
                                    <i class="x icon"></i> Delete
                                </div>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr class="active">
                            <td class="collapsing"></td>
                            <td>
                                <div class="ui fluid input">
                                    <input type="text" placeholder="Data1">
                                </div>
                            </td>
                            <td>
                                <div class="ui fluid input">
                                    <input type="text" placeholder="Data2">
                                </div>
                            </td>
                            <td>
                                <div class="ui fluid input">
                                    <input type="text" placeholder="Data3">
                                </div>
                            </td>
                            <td>
                                <div class="ui fluid input">
                                    <input type="text" placeholder="Data4">
                                </div>
                            </td>
                            <td>
                                <div class="ui fluid input">
                                    <input type="text" placeholder="Data5">
                                </div>
                            </td>
                            <td>
                                <div class="ui primary mini button">
                                    <i class="plus icon"></i> Add
                                </div>
                                <div class="ui grey mini button">
                                    <i class="x icon"></i> Cancel
                                </div>
                            </td>
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
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="ui stackable grid">
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
                                    <i class="angle double left icon"></i>
                                </a>
                                <a class="icon item disabled">
                                    <i class="left chevron icon"></i>
                                </a>
                                <a class="active item disabled">1</a>
                                <a class="item">2</a>
                                <a class="item">3</a>
                                <a class="item">4</a>
                                <a class="item">5</a>
                                <a class="icon item">
                                    <i class="right chevron icon"></i>
                                </a>
                                <a class="icon item">
                                    <i class="angle double right icon"></i>
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
        <script src="js/main.js"></script>
    </body>
</html>