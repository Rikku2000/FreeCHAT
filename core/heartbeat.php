<?php
require "config.php";
$user=$_SESSION["user"]??"";
$room=$_GET["room"]??"general";
if(!$user) exit;
$users=load_users();
$online=load_online();
$messages=load_messages();
if(isset($_GET["logout"])){
    if(isset($online[$user])){
        unset($online[$user]);
        save_online($online);
		$messages[$room][] = [
			"name"=>CHATBOT,
			"text"=>encryptMessage("<small>". $users[$user]['name'] ." ". $lang["message"]["left"] ."</small>"),
			"color"=>"#E44235",
			"style"=>"italic",
			"icon"=>"fa-genderless",
			"time"=>date("H:i:s")
		];
		save_messages($messages);
    }
    exit;
}
$alreadyInRoom = isset($online[$user]) && $online[$user] === $room;
$online[$user]=$room;
save_online($online);
if(!$alreadyInRoom){
    $messages[$room][] = [
		"name"=>CHATBOT,
        "text"=>encryptMessage("<small>". $users[$user]['name'] ." ". $lang["message"]["joined"] ."</small>"),
        "color"=>"#3A984A",
        "style"=>"italic",
        "icon"=>"fa-genderless",
        "time"=>date("H:i:s")
    ];
	save_messages($messages);
}
