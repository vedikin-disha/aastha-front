<?php
include('config/constant.php');
session_start();
// remove all sessions
session_unset();
session_destroy();
header("Location: " . BASE_URL . "login");
exit();
?>
