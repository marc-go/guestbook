<?php
require "../admin/bin/db.php";

if (isset($_GET["id"])) {
	$id = intval($_GET["id"]);
	
	$sql = "SELECT * FROM entrys WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if ($result->num_rows == 0) {
		$stmt->close();
		
		die('{"status":500, "error":"Dieser Eintrag exestiert nicht."}');
	}else{
		$row = $result->fetch_assoc();
		$mail = $row["mail"];
		
		$code = hash("sha256", rand(111111, 999999));
		
		$sql = "UPDATE entrys SET mail_code = ? WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ii", $code, $id);
		
		$html = '
		<!DOCTYPE html>
		<html lang="de">
			<head>
    			<meta charset="UTF-8">
    			<meta name="viewport" content="width=device-width, initial-scale=1.0">
    			<title>Löschen angefordert</title>
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
            			<h1>Loeschen angefordert</h1>
            			<p>Das loeschen von deinem Eintrag auf ' . $_SERVER["HTTP_HOST"] . ' wurde angefordert.
            			Wenn du dass nicht warst, kannst du diese Email ignorieren. Wenn doch, musst du diesen
            			Button zum genehmigen drücken.</p>
            			<a href="https://' . $_SERVER["SERVER_NAME"] . '/remove.php?id=' . $row["id"] . '&code=' . $code . '">
							<button>Loeschen</button>
						</a>
        			</div>
    			</div>
			</body>
		</html>
		';
		
		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$header .= 'From: Gaestebuch <gaestebuch@' . $_SERVER["SERVER_NAME"] . ">\r\n";
		
		if ($stmt->execute()) {
			if (mail($mail, "Loeschen angefordert", $html, $header)) {
				die('{"status":200}');
			}else{
				die('{"status":500, "error":"Fehler beim senden der Mail"}');
			}
		}else{
			die('{"status":500, "error":"Fehler beim einsetzen des Codes."}');
		}
	}
}else{
	die('{"status":500, "error":"Keine id angegeben."}');
}
?>