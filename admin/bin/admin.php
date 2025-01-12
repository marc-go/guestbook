<?php
/*
* This File is created by Marc Goering (https://marc-goering.de)
* This is the Login Checker for this Gaestebuch.
*/
class loginManager {
    private $user;
	private $admin;

    public function checkLogin() {
        if (!isset($_COOKIE["user_id"]) || !isset($_COOKIE["session_id"]) || !isset($_COOKIE["device_id"])) {
            return false;
        }
        require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $row["username"] . "/sessions.json");
            if (filesize($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $row["username"] . "/sessions.json") === 0) {
                continue;
            } else {
                $file = json_decode($file, true);
            }

            foreach ($file as $device_id => $json) {
				if ($device_id == "array") {
					continue;
				}
				
                if ($json["user_id"] === $_COOKIE["user_id"] && $json["session_id"] === $_COOKIE["session_id"]) {
                    $this->user = $row["username"];
                    return true;
                }
            }
        }
        return false;
    }

    public function createNewSession() {
        $session_id = hash("sha256", rand(0, 99999999999));

        $file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $this->user . "/sessions.json");
        $file = json_decode($file, true);

        $json = [];
        $json["user_id"] = $_COOKIE["user_id"];
        $json["session_id"] = $session_id;
        $json["time"] = time() + 1800;

        $file[$_COOKIE["device_id"]] = $json;

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $this->user . "/sessions.json", json_encode($file));

        if (setcookie("user_id", $_COOKIE["user_id"], time() + 1800, "/") &&
            setcookie("session_id", $session_id, time() + 1800, "/") &&
            setcookie("device_id", $_COOKIE["device_id"], time() + 1800, "/"))
		{
			return true;
        } else {
			return false;
        }
    }

    public function getUserName() {
        return $this->user;
    }
	
	public function getAdmin() {
		if ($this->admin == 1) {
			return true;
		}else{
			return false;
		}
	}
}
?>