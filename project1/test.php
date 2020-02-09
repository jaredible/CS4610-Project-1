<?php
define("DB_SERVER", "192.168.64.3");
define("DB_USERNAME", "admin");
define("DB_PASSWORD", "");
define("DB_NAME", "university");
define("ADMIN_PASSWORD_HASH", "");

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM course";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $column_names[] = $row[0];
    }
}
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Test</title>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <?php for ($i = 0; $i < count($column_names); $i++) { ?>
                    <td><?php echo $column_names[$i] ?></td>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < 5; $i++) {
                ?>
                <tr>
                    <td>Data1</td>
                    <td>Data2</td>
                    <td>Data3</td>
                    <td>Data4</td>
                    <td>Data5</td>
                    <td></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Column1</th>
                    <th>Column2</th>
                    <th>Column3</th>
                    <th>Column4</th>
                    <th>Column5</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </body>
</html>