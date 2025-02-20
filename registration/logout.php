<?php
session_start();

$_SESSION = [];
session_destroy();

// направляем на страницу входа
// header("Location: login.php");
header("Location: ../index.php");
exit();
?>