<?php
require "config.php";
$room=$_GET["room"]??"Chill Zone";
$messages=load_messages();
$roomMessages=$messages[$room];
foreach ($roomMessages as &$msg)
	if (isset($msg["text"]))
		$msg["text"] = decryptMessage($msg["text"]);
$lastMessages=array_slice($roomMessages, -27);
header("Content-Type: application/json");
echo json_encode($lastMessages);
