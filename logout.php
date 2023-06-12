<?php
session_start();
if (isset($_SESSION['profile_name'])) {
    unset($_SESSION['profile_name']);
    session_unset();
    session_destroy();
    header('Location: index.php');
}
header('Location: index.php');
session_destroy();
