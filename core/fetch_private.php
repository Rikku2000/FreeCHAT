<?php
require "config.php";
$me = $_SESSION["user"];
$other = $_GET["other"];
if(!$me || !$other) exit;
$key = [$me, $other];
sort($key, SORT_STRING);
$key = implode("|", $key);
$privates = load_private ();
$messages = $privates[$key];
$users=load_users();
foreach ($messages as &$msg) {
	if (isset($msg["text"])) {
		$msg["text"] = decryptMessage($msg["text"]);
		if ($users[$me]["theme"] == "dark") {
			if ($msg["color"] == "#000000")
				$msg["color"] = str_replace ($msg["color"], "#ffffff", "#000000");
			else
				$msg["color"] = str_replace ($msg["color"], "#000000", "#ffffff");
		}
	}
}
echo json_encode(array_slice($messages, -27));
