<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === TRUE) {
    header('Location: index.php');
}
?>