<?php
session_start();
require_once 'includes/validation/no_auth.php';

echo password_hash("password", PASSWORD_BCRYPT);
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>login</title>
    </head>
    <body>
        login
    </body>
</html>