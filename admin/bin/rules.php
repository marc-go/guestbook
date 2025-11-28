<?php
//Define class ruleManager
class ruleManager {
	//Define variables
	private $last_error;
	
	//Define function getRule
	public function getRule($name) {
		//Start database connection
		require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
		
		//Select rule
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
	
	//Define function changeRule
	public function changeRule($name, $value) {
		//Start database connection
		require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
		//Run UPDATE command
		$sql = "UPDATE rules SET value = ? WHERE name = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("is", $value, $name);
		
		if (!$stmt->execute()) {
			$this->last_error = "There was an error to update the Rule.";
			return false;
		}else{
			return true;
		}
	}
	
	//Define function getLastError
	public function getLastError() {
		return $this->last_error;
	}
}
?>