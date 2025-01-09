<?php
//Insert Database Informations here!
$db = [
	"server" => "localhost",
	"user" => "",
	"password" => "",
	"name" => ""
];

$conn = new mysqli($db["server"], $db["user"], $db["password"], $db["name"]);
?>