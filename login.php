<?php
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

if ($username === 'matron' && $password === '123') {
    $_SESSION['matron'] = true;
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: login.html?error=1");
    exit();
}
?>
