<?php
require "config.php";
$room=$_GET["room"]??"Chill Zone";
$messages=load_messages();
$roomMessages=$messages[$room];
$users=load_users();
foreach ($roomMessages as &$msg) {
	if (isset($msg["text"])) {
		$msg["text"] = decryptMessage($msg["text"]);
		if (($users[$_SESSION["user"]]["theme"] == "dark") && ($msg["color"] == "#000000"))
			$msg["color"] = str_replace ($msg["color"], "#ffffff", "#000000");
		else if (($users[$_SESSION["user"]]["theme"] == "light") && ($msg["color"] == "#ffffff"))
			$msg["color"] = str_replace ($msg["color"], "#000000", "#ffffff");
	}
}
$lastMessages=array_slice($roomMessages, -27);
header("Content-Type: application/json");
echo json_encode($lastMessages);
