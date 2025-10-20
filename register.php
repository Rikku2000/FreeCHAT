<?php
	require "core/config.php";
	$message="";
	if($_SERVER["REQUEST_METHOD"]==="POST"){
		$users=load_users();
		$user=$_POST["username"];
		$pass=$_POST["password"];
		$name=$_POST["name"];
		$gen=$_POST["gender"];
		$language=$_POST["language"];
		if($user && $pass && !isset($users[$user])){
			$users[$user]=["password"=>password_hash($pass,PASSWORD_DEFAULT),"name"=>$name,"gender"=>$gen,"language"=>$language,"theme"=>"light","invite"=>"yes","is_admin"=>count($users)==0];
			save_users($users);
			$_SESSION["user"]=$user;
			$_SESSION['lang']=$language;
			header("Location: rooms.php");
			exit;
		}else $message=$lang["message"]["register"];
	}

	include "core/header.php";

	$online=load_online();
	$online = count ($online);

	echo '
		<form method="post" enctype="multipart/form-data">
			<div class="w3-theme-white">
				<header class="w3-container w3-text-theme">
					<h4><strong><i class="fas fa-signature"></i> '. $lang["register"]["title"] .'</strong> '. $message .'</h4>
				</header>
				<div class="w3-third">
					<div class="w3-row-padding w3-margin-bottom">
						<div class="w3-lable">'. $lang["lable"]["username"] .'</div>
						<input class="w3-input" name="username" type="text" placeholder="'. $lang["register"]["username"] .'" style="width: 100%" minlength="3" maxlength="16" required>
					</div>
				</div>
				<div class="w3-third">
					<div class="w3-row-padding w3-margin-bottom">
						<div class="w3-lable">'. $lang["lable"]["password"] .'</div>
						<input class="w3-input" name="password" type="text" placeholder="'. $lang["register"]["password"] .'" style="width: 100%" minlength="8" maxlength="16" required>
					</div>
				</div>
				<div class="w3-third">
					<div class="w3-row-padding w3-margin-bottom">
						<div class="w3-lable">'. $lang["lable"]["name"] .'</div>
						<input class="w3-input" name="name" type="text" placeholder="'. $lang["register"]["name"] .'" style="width: 100%" minlength="3" maxlength="16" required>
					</div>
				</div>
				<div class="w3-half">
					<div class="w3-lable">'. $lang["lable"]["gender"] .'</div>
					<div class="w3-row-padding w3-margin-bottom">
						<select class="w3-select" id="style" name="gender">
							<option value="fa-venus-mars">Hetero</option>
							<option value="fa-venus-double">Lesbian</option>
							<option value="fa-venus">Female</option>
							<option value="fa-transgender">Transgender</option>
							<option value="fa-non-binary">Genderqueer</option>
							<option value="fa-neuter">Neutrois</option>
							<option value="fa-mercury">Hermaphrodit</option>
							<option value="fa-mars-stroke-up">Androgyne</option>
							<option value="fa-mars-stroke-right">Othergender</option>
							<option value="fa-mars-double">Gay</option>
							<option value="fa-mars-and-venus">Bigender</option>
							<option value="fa-mars" selected>Male</option>
							<option value="fa-genderless">Genderless</option>
						</select>
					</div>
				</div>
				<div class="w3-half">
					<div class="w3-lable">'. $lang["lable"]["language"] .'</div>
					<div class="w3-row-padding w3-margin-bottom">
						<select class="w3-select" id="style" name="language">
							<option value="de_de">'. $lang["lang"]["de_de"] .'</option>
							<option value="en_us" selected>'. $lang["lang"]["en_us"] .'</option>
							<option value="zh_cn">'. $lang["lang"]["zh_cn"] .'</option>
						</select>
					</div>
				</div>
				<div class="w3-row-padding w3-margin-bottom">
					<button class="w3-button w3-theme w3-hover-theme" type="submit" name="submit"><i class="fas fa-signature"></i> <span class="w3-hide-small">'. $lang["button"]["register"] .'</span></button>
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
