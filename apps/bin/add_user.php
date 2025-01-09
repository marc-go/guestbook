<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Du bist nicht angemeldet. Bitte lade die Seite neu."}');
}

$_POST = @json_decode(file_get_contents("php://input"), true);

if (isset($_POST["name"]) && isset($_POST["mail"]) && isset($_POST["pw"]) && isset($_POST["pw2"])) {
	$name = $_POST["name"];
	$mail = $_POST["mail"];
	$pw = hash("sha256", $_POST["pw"]);
	$pw2 = hash("sha256", $_POST["pw2"]);
	
	if ($pw !== $pw2) {
		die('{"status":500, "error":"Die Passwörter stimmen nicht überein."}');
	}
	
	if (!mkdir($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $name, 0777)) {
		die('{"status":500, "error":"Es gab einen Fehler beim erstellen eines Ordners."}');
	}
	
	if (!file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $name . "/sessions.json", '{"array":true}')) {
		die('{"status":500, "error":"Es gab einen Fehler beim erstellen der notwendigen Dateien."}');
	}
	
	require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
	$sql = "INSERT INTO users (username, mail, password) VALUES (?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("sss", $name, $mail, $pw2);
	
	if (!$stmt->execute()) {
		die('{"status":500, "error":"Es gab einen Fehler beim speichern der Daten."}');
	}else{
		die('{"status":200}');
	}
}else{
	die('{"status":500, "error":"Die erforderlichen Parameter wurden nicht übergeben"}');
}
?>