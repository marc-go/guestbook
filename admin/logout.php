<?php
setcookie("user_id", "", time(), "/");
setcookie("session_id", "", time(), "/");
setcookie("device_id", "", time(), "/");

header("Location: login.php");
exit;
?>