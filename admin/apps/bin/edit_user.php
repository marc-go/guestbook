<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Du bist nicht angemeldet. Bitte lade die Seite neu."}');
}

require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";

$_POST = @json_decode(file_get_contents("php://input"), true);

if (isset($_POST["pw"]) && isset($_POST["pw2"]) && isset($_POST["id"])) {
	$id = intval($_POST["id"]);
	$pw = hash("sha256", $_POST["pw"]);
	$pw2 = hash("sha256", $_POST["pw2"]);
	
	if (!$pw === $pw2) {
		die('{"status":500, "error":"Die Passwörter stimmen nicht überein."}');
	}
	
	require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
	
	$sql = "UPDATE users SET password = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("si", $pw2, $id);
	
	if (!$stmt->execute()) {
		die('{"status":500, "error":"Es ist ein Fehler aufgetreten"}');
	}else{
		die('{"status":200}');
	}
}else{
	die('{"status":500, "error":"Die Parameter wurden nicht übergeben."}');
}
?>