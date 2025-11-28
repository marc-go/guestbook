<?php
require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"You are not Login."}');
}

$json = json_decode(file_get_contents("settings.json"), true);
$POST = json_decode(file_get_contents("php://input"), true);

require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/rules.php";
$rules = new ruleManager();

foreach($json as $rule_n => $rule_k) {
	$rule_v = $POST[$rule_n];
	
	if (!$rules->changeRule($rule_n, $rule_v)) {
		die('{"status":500, "error":"' . $rules->getLastError() . '"}');
	}
}

die('{"status":200}');
?>