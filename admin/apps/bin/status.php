<?php
require "../../bin/admin.php";

$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Du bist nicht angemeldet. Bitte lade die Seite neu."}');
}

if (isset($_GET["id"]) && isset($_GET["mode"])) {
	$id = intval($_GET["id"]);
	
	if ($_GET["mode"] == "allow") {
		$mode = 0;
	}elseif($_GET["mode"] == "block") {
		$mode = 2;
	}else{
		die('{"status":500, "error":"Unbekannter Modus"}');
	}
	
	require "../../bin/db.php";
	
	$sql = "UPDATE entrys SET status = ? WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ii", $mode, $id);
	
	if (!$stmt->execute()) {
		die('{"status":500, "error":"Fehler beim ausführen der Abfrage."}');
	}else{
		die('{"status":200}');
	}
}
?>