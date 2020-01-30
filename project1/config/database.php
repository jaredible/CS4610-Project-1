<?php
define("DB_SERVER", "192.168.64.3");
define("DB_USERNAME", "admin");
define("DB_PASSWORD", "");
define("DB_NAME", "university");

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>