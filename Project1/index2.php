<?php

if(isset($_POST['create'])) {
    echo 'Test1: ' . $_POST['test1'];
    echo 'Test2: ' . $_POST['test2'];
    echo 'Test3: ' . $_POST['test3'];
    echo 'Test4: ' . $_POST['test4'];
    echo 'Test5: ' . $_POST['test5'];
}

require_once "config.php";
$conn = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
if (!$conn) {
  die('Could not connect: ' . mysqli_connect_error());
}

// Get tables
$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$dbname'";
$result = mysqli_query($conn, $query);
while ($record = mysqli_fetch_array($result)) {
    $table_names[] = $record[0];
}

// Get current table name or set the default
$current_table_name = filter_input(INPUT_GET, 'table');
if (!isset($current_table_name)) {
    $current_table_name = $table_names[0];
}

// Get current table's column names
$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = '$current_table_name'";
$result = mysqli_query($conn, $query);
while ($record = mysqli_fetch_array($result)) {
    $column_names[] = $record[0];
}

// Get current table's data
$query = "SELECT * FROM $current_table_name";
$result = mysqli_query($conn, $query);
while ($record = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $i = 0;
    foreach ($record as $record_value) {
        $data[$i++][] = $record_value;
    }
}
?>

<!DOCYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no">
        <title>University Portal</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link href="css/styles.css" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">University Portal</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php foreach ($table_names as $table_name) { ?>
                    <li class="nav-item <?php if ($table_name === $current_table_name) echo 'active'; ?>">
                        <a class="nav-link" href="?table=<?php echo "$table_name" ?>"><?php echo ucwords(str_replace("_", " ", $table_name)) ?></a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <main class="col-md-12">
                        <div class="bd">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <?php for ($i = 0; $i < count($column_names); $i++) { ?>
                                                <th scope="col"><?php echo ucwords(str_replace("_", " ", $column_names[$i])) ?></th>
                                            <?php } ?>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($data); $i++) { ?>
                                        <tr>
                                            <?php for ($j = 0; $j < count($column_names); $j++) { ?>
                                                <td><?php echo $data[$j][$i] ?></td>
                                            <?php } ?>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <div>
                                                        <button class="btn" data-value="<?php echo $data[0][$i] ?>"><i class="fa fa-edit"></i></button>
                                                        <button class="btn" data-value="<?php echo $data[0][$i] ?>"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <?php for ($i = 0; $i < count($column_names); $i++) { ?>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <div>
                                                            <button class="btn"><i class="fa fa-arrow-up"></i></button>
                                                            <button class="btn"><i class="fa fa-arrow-down"></i></button>
                                                        </div>
                                                    </div>
                                                </td>
                                            <?php } ?>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <form class="bd" action="?table=<?php echo $current_table_name ?>" method="post">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td><input class="form-control" type="text" name="test1" placeholder="Testing1"></td>
                                            <td><input class="form-control" type="text" name="test2" placeholder="Testing2"></td>
                                            <td><input class="form-control" type="text" name="test3" placeholder="Testing3"></td>
                                            <td><input class="form-control" type="text" name="test4" placeholder="Testing4"></td>
                                            <td><input class="form-control" type="text" name="test5" placeholder="Testing5"></td>
                                            <td><input class="form-control" type="submit" name="create" value="New Row"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </main>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<?php mysqli_close($conn); ?>