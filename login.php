<?php
	require "core/config.php";
	$message="";
	if($_SERVER["REQUEST_METHOD"]==="POST"){
		$users=load_users();
		$user=$_POST["username"];
		$pass=$_POST["password"];
		$bans=load_bans();

		if (isset($bans[$user]))
			$message=$lang["message"]["banned"];
		else {
			if(isset($users[$user]) && ($pass == decryptMessage ($users[$user]["password"]))){
				$_SESSION["user"]=$user;
				$_SESSION["lang"]=$users[$user]["language"];
				header("Location: rooms.php");
				exit;
			}else $message=$lang["message"]["login"];
		}
	}

	include "core/header.php";

	$online=load_online();
	$online=count($online);

	echo '
		<form method="post" enctype="multipart/form-data">
			<div class="w3-theme-white">
				<header class="w3-container w3-text-theme">
					<h4><strong><i class="fas fa-sign-in"></i> '. $lang["login"]["title"] .'</strong> '. $message .'</h4>
				</header>
				<div class="w3-half">
					<div class="w3-row-padding w3-margin-bottom">
						<div class="w3-lable">'. $lang["lable"]["username"] .'</div>
						<input class="w3-input" name="username" type="text" placeholder="'. $lang["login"]["username"] .'" style="width: 100%" required>
					</div>
				</div>
				<div class="w3-half">
					<div class="w3-row-padding w3-margin-bottom">
						<div class="w3-lable">'. $lang["lable"]["password"] .'</div>
						<input class="w3-input" name="password" type="text" placeholder="'. $lang["login"]["password"] .'" style="width: 100%" required>
					</div>
				</div>
				<div class="w3-row-padding w3-margin-bottom">
					<button class="w3-button w3-theme w3-hover-theme" type="submit" name="submit"><i class="fas fa-sign-in"></i> <span class="w3-hide-small">'. $lang["button"]["login"] .'</span></button>
				</div>
			</div>
		</form>
	';

	include "core/template.php";

	echo '
		<div class="w3-bottom w3-theme-white w3-box">
			<div class="w3-container w3-center">
				- <a class="w3-link w3-hover-text-theme" href="imprint.php">'. $lang["page"]["imprint"] .'</a> | <a class="w3-link w3-hover-text-theme" href="privacy.php">'. $lang["page"]["privacy"] .'</a> -
			</div>
		</div>
	';

	include "core/footer.php";
?>
