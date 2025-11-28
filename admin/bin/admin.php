<?php
/*
* This File is created by Marc Goering (https://marc-goering.de)
* This is the Login Checker for this Guestbook.
*/

//Define loginManager class
class loginManager {
	//Define variables
    private $user;
	private $admin;
	
	//Define login check function
    public function checkLogin() {
		//Check cookies
        if (!isset($_COOKIE["user_id"]) || !isset($_COOKIE["session_id"]) || !isset($_COOKIE["device_id"])) {
            return false;
        }
		
		//Start database connection
        require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
		//Select all userss
        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
		
		//While for every user
        while ($row = $result->fetch_assoc()) {
			//Get all sessions
            $file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $row["username"] . "/sessions.json");
            if (filesize($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $row["username"] . "/sessions.json") === 0) {
                continue;
            } else {
                $file = json_decode($file, true);
				$this->admin = $row["admin"];
            }
			
			//While for every session
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
	
	//Define createNewSession function
    public function createNewSession() {
		//Create new session id
        $session_id = hash("sha256", rand(0, 99999999999));
		
		//Get session file
        $file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $this->user . "/sessions.json");
        $file = json_decode($file, true);
		
		//Prepare Array
        $json = [];
        $json["user_id"] = $_COOKIE["user_id"];
        $json["session_id"] = $session_id;
        $json["time"] = time() + 1800;

        $file[$_COOKIE["device_id"]] = $json;
		
		//Insert
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/admin/users/" . $this->user . "/sessions.json", json_encode($file));

		//Update Cookies
        if (setcookie("user_id", $_COOKIE["user_id"], time() + 1800, "/") &&
            setcookie("session_id", $session_id, time() + 1800, "/") &&
            setcookie("device_id", $_COOKIE["device_id"], time() + 1800, "/"))
		{
			return true;
        } else {
			return false;
        }
    }

	//Define function getUserName
    public function getUserName() {
        return $this->user;
    }
	
	//Define function get Admin
	public function getAdmin() {
		if ($this->admin == 1) {
			return true;
		}else{
			return false;
		}
	}
}
?>