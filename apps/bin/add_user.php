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
		require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/rules.php";
		$rules = new ruleManager();
		if (!$rules->getRule("new_user_mail_admin")) {
			die('{"status":200}');
		}
		
		$sql = "SELECT * FROM users";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();
		
		while ($row = $result->fetch_assoc()) {
			$to = $row["mail"];
			$subject = "Neuer Benutzer";
			$html = '
			<!DOCTYPE html>
			<html lang="de">
				<head>
    				<meta charset="UTF-8">
    				<meta name="viewport" content="width=device-width, initial-scale=1.0">
    				<title>Neuer Benutzer</title>
					<style>
						body {
							font-family: Arial, sans-serif;
							color: #000000;
							background-color: #ffffff;
							padding: 20px;
						}
					</style>
				</head>
				<body>
    				<div class="page-content">
        				<div class="content">
            				<h1>Neuer Benutzer</h1>
            				<p>Es gibt einen neuen Benutzer bei deinen Gaestebuch.</p>
        				</div>
    				</div>
				</body>
			</html>
			';
			
			$header = 'From: gaestebuch@' . $_SERVER["SERVER_NAME"] . "\r\n" .
			'Content-Type: text/html' . "\r\n" .
    		'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $html, $header);
			
			die('{"status":200}');
		}
	}
}else{
	die('{"status":500, "error":"Die erforderlichen Parameter wurden nicht übergeben"}');
}
?>
