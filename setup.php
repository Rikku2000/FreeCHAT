<?php
if(!is_dir('./data')) mkdir('./data', 0777, true);
if(!is_dir('./data/upload')) mkdir('./data/upload', 0777, true);
$defaultUsers = [
	"Admin"=>["password"=>"R7BRW7QuPoKhly2TrToHKFZITWFNU2Y5U1Vwa3hLSVpQUmRub2c9PQ==","name"=>"Admin","gender"=>"fa-mars","theme"=>"light","language"=>"en_us","invite"=>"no","is_admin"=>true],
	"Guest"=>["password"=>"R7BRW7QuPoKhly2TrToHKFZITWFNU2Y5U1Vwa3hLSVpQUmRub2c9PQ==","name"=>"Guest","gender"=>"fa-mars","theme"=>"light","language"=>"en_us","invite"=>"no","is_admin"=>false]
];
file_put_contents('./data/users.json',json_encode($defaultUsers));
$defaultRooms = [
	"Chill Zone"=>["description"=>"Relaxed conversations, anywhere, anytime."]
];
file_put_contents('./data/rooms.json',json_encode($defaultRooms));
header("location: login.php");
?>
