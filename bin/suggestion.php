<?php
if (isset($_POST["feature"])) {
	$text = trim(htmlspecialchars($_POST["feature"]));
	
	require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/db.php";
	
	$url = "https://marc-goering.de/api/suggestions/guestbook.php";
	$data = http_build_query([
    	"text" => $text
	]);

	$options = [
    	"http" => [
        	"header" => "Content-Type: application/x-www-form-urlencoded\r\n",
        	"method" => "POST",
        	"content" => $data
    	]
	];

	$context = stream_context_create($options);
	$response = json_decode(file_get_contents($url, false, $context), true);
	
	if ($response["status"] == 200) {
		header("Location: /?error=You suggestion was successful saved!");
		exit;
	}else{
		header("Location: /?error=There was an error to make the suggestion.");
		exit;
	}
}
?>