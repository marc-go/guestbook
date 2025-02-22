<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$_POST = json_decode(file_get_contents("php://input"), true);

require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Du bist nicht angemeldet. Bitte lade die Seite neu."}');
}

$rules["allow_entrys"] = "";
$rules["new_user_mail_admin"] = "";
$rules["new_entry_mail_user"] = "";
$rules["new_entry_mail_admin"] = "";
$rules["spamblock"] = "";

require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/rules.php";
$rule = new ruleManager();

foreach ($rules as $name => $value) {
	$value = $_POST[$name];
	
	if ($value) {
		$value = 1;
	}else{
		$value = 0;
	}
		
	if (!$rule->changeRule($name, $value)) {
		die('{"status":500, "error":"' . $rule->getLastError() . '"}');
	}
}
die('{"status":200}');
?>
