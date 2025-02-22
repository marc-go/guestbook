<?php
//Get config file
require $_SERVER["DOCUMENT_ROOT"] . "/config.php";

//Start database connection
$conn = new mysqli($db_host, $db_user, $db_pw, $db_name);
?>