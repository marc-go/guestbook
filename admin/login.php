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
        $error = "That were false.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f4f4f4;
        }
        .login-container {
            background: white;
            padding: 20px;
            width: 300px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <span><?php if (isset($error)) { echo $error; } ?></span><br>
        <form>
	    <?php
            if (isset($_GET["from"])) {
                echo '<input type="hidden" name="from" value="' . $_GET["from"] . '">';
            }
            ?>
            <input type="text" placeholder="Benutzername" required>
            <input type="password" placeholder="Passwort" required>
            <button type="submit" class="login-btn">Anmelden</button>
        </form>
    </div>
</body>
</html>
