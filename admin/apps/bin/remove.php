<?php
require "../../bin/admin.php";

$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Du bist nicht angemeldet. Bitte lade die Seite neu."}');
}

if (isset($_GET["id"])) {
	$id = intval($_GET["id"]);
	
	require "../../bin/db.php";
	
	$sql = "DELETE FROM entrys WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	
	if ($stmt->execute()) {
		die('{"status":200}');
	}else{
		die('{"status":500, "error":"Fehler beim Löschen."}');
	}
}else{
	die('{"status":500, "error":"Keine id angegeben."}');
}
?>