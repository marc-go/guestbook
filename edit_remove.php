<?php
require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/rules.php";
$rules = new ruleManager();
if ($rules->getRule("delete") == 0) {
	die("Premission denied: This is not allowed.");
}
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="UTF-8">
		<title>Edit or Delete Entry</title>
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
			
			textarea {
				border: 0.5px solid grey;
				border-radius: 20px;
				width: 25%;
				height: 300px;
				outline: none;
			}
		</style>
	</head>
	<body>
		<?php
		ini_set("display_errors", 1);
		ini_set("display_startup_errors", 1);
		error_reporting(E_ALL);
		
		if (isset($_GET["id"]) && isset($_GET["code"])) {
			$id = intval($_GET["id"]);
			$code = intval($_GET["code"]);
			
			//Start database connection
			require "admin/bin/db.php";
			//Check Auth
			$sql = "SELECT * FROM entrys WHERE id = ? AND mail_code = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("ii", $id, $code);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			
			if (!isset($row["text"])) {
				echo '<h1>Error</h1>';
				echo '<p>The Code or the id are false.</p>';
				echo '<a href="/"><button>Home</button></a>';
				die('</body></html>');
			}else{
				if (isset($_POST["rm"])) {
					$sql = "DELETE FROM entrys WHERE id = ?";
					$stmt = $conn->prepare($sql);
					$stmt->bind_param("i", $id);
					
					if (!$stmt->execute()) {
						echo '<h1>Error</h1>';
						echo '<p>Error to delete.</p>';
						die('</body></html>');
					}else{
						echo '<h1>Success</h1>';
						echo '<p>Your entry was successfull deletet.</p>';
						echo '<a href="/"><button>Home</button></a>';
						die('</body></html>');
					}
				}elseif (isset($_POST["text"])) {
					$text = htmlspecialchars($_POST["text"]);
					
					$sql = "UPDATE entrys SET text = ? WHERE id = ?";
					$stmt = $conn->prepare($sql);
					$stmt->bind_param("si", $text, $id);
									  
					if ($stmt->execute()) {
						echo '<h1>Successful</h1>';
						echo '<p>Your entry was successful edited.</p>';
						echo '<a href="/"><button>Home</button></a>';
						die('</body></html>');
					}else{
						echo '<h1>Error</h1>';
						echo '<p>There was an error.</p>';
						echo '<a href="/"><button>Home</button></a>';
						die('</body></html>');
					}
				}
				
				echo '<h1>Edit Entry</h1>';
				echo '<form action="#" method="post">';
				echo '<textarea name="text">' . $row["text"] . '</textarea><br>';
				echo '<button type="submit">Save</button>';
				echo '</form>';
				
				echo '<h1>Delete</h1>';
				echo '<form action="#" method="post">';
				echo '<input type="hidden" name="rm" value="1">';
				echo '<button type="submit">Confirm delete</button>';
				echo '</form>';
			}
		}
		?>
	</body>
</html>