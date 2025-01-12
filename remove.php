<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="UTF-8">
		<title>Eintrag löschen</title>
		<style>
			body {
				padding: 10px;
				font-family: Arial, sans-serif;
				color: #000000;
				background-color: #ffffff;
			}
			
			button {
				height: 32px;
				width: 100px;
				background-color: #000000;
				color: #ffffff;
				border: none;
				border-radius: 30px;
				transition: all 0.3s ease-in-out;
			}
			
			button:hover {
				transform: scale(1.1);
			}
		</style>
	</head>
	<body>
		<?php
		if (isset($_GET["id"]) && isset($_GET["code"])) {
			$id = intval($_GET["id"]);
			$code = intval($_GET["code"]);
	
			require "admin/bin/db.php";
			$sql = "DELETE FROM entrys WHERE id = ? AND mail_code = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("ii", $id, $code);
			
			if ($stmt->execute()) {
				echo '<h1>Erfolgreich</h1>';
				echo '<p>Dein Eintrag wurde erfolgreich aus dem System gelöscht.</p>';
				echo '<a href="/"><button>Zur Startseite</button></a>';
			}else{
				echo '<h1>Fehler</h1>';
				echo '<p>Es ist einer der folgenden Fehler aufgetreten:</p>';
				echo '
				<ul>
					<li>Fehler beim löschen</li>
					<li>Der Code ist ungültig</li>
					<li>Der Eintrag wurde nicht gefunden.</li>
				</ul>
				';
			}
		}else{
			echo '<h1>Fehler</h1>';
			echo '<p>Der Code oder der Eintrag wurden nicht übermittelt</p>';
		}
		?>
	</body>
</html>