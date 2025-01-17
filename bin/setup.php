<?php
ini_set("display_errors", 1);
ini_set("display_startup_error", 1);
error_reporting(E_ALL);

function error($error) {
	$html = '
	<!DOCTYPE html>
	<html lang="de">
	<head>
    	<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<title>Gästebuch // Setup</title>
    	<link rel="stylesheet" type="text/css" href="/css/setup.css">
	</head>
	<body>
    	<div class="page-content">
        	<div class="header">
            	<h1>Fehler!</h1>
            	<p>' . $error . '</p>
				<a href="/setup.php">
					<button>Zurück</button>
				</a>
        	</div>
    	</div>
	</body>
	</html>
	';
	
	die($html);
}


$names = [
	"db-host",
	"db-user",
	"db-pw",
	"db-name",
	"ad-user",
	"ad-mail",
	"ad-pw",
	"ad-pw2"
];

foreach ($names as $name) {
	if (!isset($_POST[$name])) {
		error('Der Parameter "' . $name . '" wurde nicht übergeben.');
	}
}

$ad_user = $_POST["ad-user"];
$ad_mail = $_POST["ad-mail"];
$ad_pw = $_POST["ad-pw"];
$ad_pw2 = $_POST["ad-pw2"];
$db_host = $_POST["db-host"];
$db_user = $_POST["db-user"];
$db_pw = $_POST["db-pw"];
$db_name = $_POST["db-name"];


$mail_muster = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
if (!preg_match($mail_muster, $ad_mail)) {
	error("Die Email Adresse ist nicht gültig.");
}

$user_muster = '/^[a-z]+$/';
if (!preg_match($user_muster, $ad_user)) {
	error("Der Benutzername ist nicht gültig.");
}

if ($ad_pw !== $ad_pw2) {
	error("Die Passwörter stimmen nicht überein.");
}

$ad_pw = hash("sha256", $ad_pw);


try {
	$conn = new mysqli($db_host, $db_user, $db_pw);
	
	if ($conn->connect_error) {
		throw new Exception("Die Zugangsdaten sind falsch.");
	}
} catch (Exception $error) {
	error("Die Zugangsdaten sind falsch oder der Zugriff wurde verweigert.");
}

$sql = "SHOW DATABASES";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$db = false;

while($row = $result->fetch_assoc()) {
	if ($row["Database"] == $db_name) {
		$db = true;
	}
}

if (!$db) {
	try {
		$sql = "CREATE DATABASE " . $db_name;
	
		if (!$conn->query($sql)) {
			throw new Exception("Fehler beim erstellen der Datenbank.");
		}
	} catch (Exception $error) {
		error("Fehler beim erstellen der Datenbank. Bitte erstelle die Datenbank manuell.");
	}
}
	
$conn->select_db($db_name);

$sql = "CREATE TABLE IF NOT EXISTS users (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(100) UNIQUE,
	mail VARCHAR(100),
	password VARCHAR(100),
	admin TINYINT(11)
)";
	
if (!$conn->query($sql)) {
	error("Fehler beim erstellen der Datenbank Tabelle.");
}

$sql = "CREATE TABLE IF NOT EXISTS entrys (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) UNIQUE,
	mail VARCHAR(100),
	date VARCHAR(100),
	text VARCHAR(200),
	mail_code VARCHAR(100),
	status TINYINT(11)
)";
if (!$conn->query($sql)) {
	error("Fehler beim erstellen der Datenbank Tabelle.");
}

$sql = "CREATE TABLE IF NOT EXISTS rules (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) UNIQUE,
	value TINYINT(11)
)";
if (!$conn->query($sql)) {
	error("Fehler beim erstellen der Datenbank Tabelle.");
}

$rules = [
	"allow_entrys" => 1,
	"new_entry_mail_admin" => 1,
	"new_entry_mail_user" => 1,
	"new_user_mail_admin" => 1
];

foreach($rules as $rule => $value) {
	$sql = "SELECT name FROM rules WHERE name = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $rule);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows == 1) {
		continue;
	}
	
	$sql = "INSERT INTO rules (name, value) VALUES (?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("si", $rule, $value);
	
	if (!$stmt->execute()) {
		error("Fehler beim ändern einer Einstellung.");
	}
	
	@mkdir($_SERVER["DOCUMENT_ROOT"] . "/admin/users/", 0777);
	
	@mkdir($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $ad_user, 0777);
	
	if (!file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $ad_user . "/sessions.json", "{'array':true}")) {
		error("Fehler beim erstellen einer Datei.");
	}
}

$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
	$sql = "INSERT INTO users (username, mail, password, admin) VALUES (?, ?, ?, 1)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("sss", $ad_user, $ad_mail, $ad_pw);

	if (!$stmt->execute()) {
		error("Fehler beim erstellen des Benutzers.");
	}
}

$php = '
<?php
$db_host = "' . $db_host . '";
$db_user = "' . $db_user . '";
$db_pw = "' . $db_pw . '";
$db_name = "' . $db_name . '";
?>
';

if (!file_put_contents("../config.php", $php)) {
	error("Fehler beim speichern der Datei.");
}

$html = '
<!DOCTYPE html>
<html lang="de">
<head> 
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gästebuch // Setup</title>
   	<link rel="stylesheet" type="text/css" href="/css/setup.css">
</head>
<body>
    <div class="page-content">
       	<div class="header">
            <h1>Erfolgreich!</h1>
            <p>Dein Gästebuch wurde erfolgreich eingerichtet.</p>
			<p>Admin Panel: /admin<p>
			<a href="/">
				<button>Zum Gästebuch</button>
			</a>
        </div>
    </div>
</body>
</html>
';

die($html);
?>
