<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

if (isset($_POST["name"]) && isset($_POST["text"]) && isset($_POST["mail"])) {
	$name = htmlspecialchars($_POST["name"]);
	
	$mail = [
		"value" => htmlspecialchars($_POST["mail"]),
		"muster" => "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/"
	];
	
	if (!preg_match($mail["muster"], $mail["value"])) {
		header("Location: /index.php?error=Email%20Adresse%20nicht%20gueltig.");
		exit;
	}
	
	$text = htmlspecialchars($_POST["text"]);
	
	
		$date = date("d-m-Y");
		$mail = $mail["value"];
		
		require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
		require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/rules.php";
		
		$rules = new ruleManager();
		$int = $rules->getRule("allow_entrys");
		
		$sql = "INSERT INTO entrys (name, mail, date, text, status) VALUES(?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssssi", $name, $mail, $date, $text, $int);
		
		if ($stmt->execute()) {
			$rule = $rules->getRule("new_entry_mail_admin");
			
			if ($rule = 1) {
				$html = '
				<!DOCTYPE html>
				<html lang="de">
					<head>
    					<meta charset="UTF-8">
    					<meta name="viewport" content="width=device-width, initial-scale=1.0">
    					<title>Neuer Eintrag</title>
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
            					<h1>Neuer Eintrag</h1>
            					<p>Es gibt einen neuen Eintrag in deinen Gaestebuch.</p>
            					<a href="https://' . $_SERVER["SERVER_NAME"] . '/admin/">
									<button>Zum Admin Panal</button>
								</a>
        					</div>
    					</div>
					</body>
				</html>
				';
				
				$sql = "SELECT * FROM users";
				$stmt = $conn->prepare($sql);
				$stmt->execute();
				$result = $stmt->get_result();
				
				while($row = $result->fetch_assoc()) {
					$to = $row["mail"];
					$subject = "Eintrag hinzugefügt";
					$header = 'From: gaestebuch@' . $_SERVER["SERVER_NAME"] . "\r\n" .
					'Content-Type: text/html' . "\r\n" .
    				'X-Mailer: PHP/' . phpversion();
					
					mail($to, $subject, $html, $header);
				}
			
			$rule = $rules->getRule("new_entry_mail_user");
			if ($rule = 1) {
				$html = '
				<!DOCTYPE html>
				<html lang="de">
					<head>
    					<meta charset="UTF-8">
    					<meta name="viewport" content="width=device-width, initial-scale=1.0">
    					<title>Neuer Eintrag</title>
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
            					<h1>Neuer Eintrag</h1>
            					<p>Dein neuer Eintrag war erfolgreich.</p>
            					<a href="https://' . $_SERVER["SERVER_NAME"] . '">
									<button>Zum Gaestebuch</button>
								</a>
        					</div>
    					</div>
					</body>
				</html>
				';
				
				$to = $_POST["mail"];
				$subject = "Neuer Eintrag";
				$header = 'From: gaestebuch@' . $_SERVER["SERVER_NAME"] . "\r\n" .
				'Content-Type: text/html' . "\r\n" .
    			'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $html, $header);
			}
				
			if ($int = 1) {
				header("Location: /index.php");
				exit;
			}else{
				$stmt->close();
				$conn->close();
			
				header("Location: /index.php?error=Fehler%20beim%20eintragen.");
				exit;
			}
		}
	}
?>
