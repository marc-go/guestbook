<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

define("PATH", "");

require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/admin.php";

$session = new loginManager();
if ($session->checkLogin()) {
	if (isset($_GET["action"])) {
		if ($_GET["action"] == "logout") {
			setcookie("device_id", "", time() - 1800, "/");
			setcookie("user_id", "", time() - 1800, "/");
			setcookie("session_id", "", time() - 1800, "/");
		}
	}
    header("Location: apps");
    exit;
}

if (isset($_POST["user"]) && isset($_POST["pw"])) {
    $user = $_POST["user"];
    $pw = hash("sha256", $_POST["pw"]);

    require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $pw);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows <= 0) {
        $error = "Der Benutzername bzw. das Passwort sind falsch.";
    } else {
        $file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $user . "/sessions.json");
        if (filesize($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $user . "/sessions.json") !== 0) {
            $file = json_decode($file, true);
        } else {
            $file = array();
        }

        $user_id = hash("sha256", rand(0, 99999999999));
        $session_id = hash("sha256", rand(0, 99999999999));
        do {
            $device_id = rand(0, 999);
        } 
		while (array_key_exists($device_id, $file));

        $json = [];
        $json["user_id"] = $user_id;
        $json["session_id"] = $session_id;
        $json["time"] = time() + 1800;

        $file[$device_id] = $json;
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $user . "/sessions.json", json_encode($file));

        if (setcookie("user_id", $user_id, time() + 1800, "/") &&
            setcookie("session_id", $session_id, time() + 1800, "/") &&
            setcookie("device_id", $device_id, time() + 1800, "/")) {
        } else {
            echo "Fehler beim Setzen der Cookies.";
        }

        if (isset($_POST["from"])) {
            header("Location: " . $_POST["from"]);
            exit;
        } else {
            header("Location: apps");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="UTF-8">
	<title>Login beim Gästebuch</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div id="login">
        <h2>Login beim Gästebuch</h2>
        <form method="post" action="#">
            <?php
            if (isset($_GET["from"])) {
                echo '<input type="hidden" name="from" value="' . $_GET["from"] . '">';
            }
            ?>
            <input id="input" placeholder="Benutzername" type="text" name="user" required><br>
            <input id="input" placeholder="Passwort" type="password" name="pw" required><br>
            <span id="error"><?php if (isset($error)) { echo $error; } ?></span><br>
            <input id="button" type="submit" value="Anmelden">
        </form>
    </div>
</body>
</html>