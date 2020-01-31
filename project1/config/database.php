<?php
session_start();

//$_SESSION["user_id"] = 69;

define("DB_SERVER", "192.168.64.3");
define("DB_USERNAME", "admin");
define("DB_PASSWORD", "");
define("ADMIN_PASSWORD_HASH", "");

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>