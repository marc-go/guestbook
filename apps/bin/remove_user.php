<?php
require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Du bist nicht angemeldet. Bitte lade die Seite neu."}');
}

if (isset($_GET["id"])) {
	$id = intval($_GET["id"]);
	
	require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
	$sql = "SELECT * FROM users WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$name = $row["username"];
	
	if (!unlink($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $name . "/sessions.json")) {
		die('{"status":500, "error":"Es gab einen Fehler beim löschen einer Datei."}');
	}
	
	if (!rmdir($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $name)) {
		die('{"status":500, "error":"Es gab einen Fehler beim löschen eines Ordners."}');
	}
	
	$stmt->close();
	
	$sql = "DELETE FROM users WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	
	if (!$stmt->execute()) {
		die('{"status":500, "error":"Es gab einen Fehler beim löschen."}');
	}else{
		die('{"status":200}');
	}
}else{
	die('{"status":500, "error":"Die erforderlichen Parameter wurden nicht übergeben."}');
}
?>