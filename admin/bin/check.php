<?php
//Check if the system is set up
if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/config.php")) {
	header("Location: /setup.php");
	exit;
}
?>