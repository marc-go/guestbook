<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ruleManager {
	private $last_error;
	
	public function getRule($name) {
		require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
		
		$sql = "SELECT * FROM rules WHERE name = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$value = $row["value"];
		
		$stmt->close();
		$conn->close();
		
		return intval($value);
	}
	
	public function changeRule($name, $value) {
		require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
		$sql = "UPDATE rules SET value = ? WHERE name = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("is", $value, $name);
		
		if (!$stmt->execute()) {
			$this->last_error = "Es gab einen Fehler beim ändern der Regel.";
			return false;
		}else{
			return true;
		}
	}
	
	public function getLastError() {
		return $this->last_error;
	}
}
?>