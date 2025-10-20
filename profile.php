<?php
	require "core/config.php";
	if(!isset($_SESSION["user"])) header("Location: login.php");
	$user=$_SESSION["user"];
	$users=load_users();
	$message="";

	include "core/header.php";

	if($_SERVER["REQUEST_METHOD"]==="POST"){
		$name=$_POST["name"];
		$users[$user]["name"]=$name;
		$gen=$_POST["gender"];
		$users[$user]["gender"]=$gen;
		$theme=$_POST["theme"];
		$users[$user]["theme"]=$theme;
		$language=$_POST["language"];
		$users[$user]["language"]=$language;
		$invite=$_POST["invite"];
		$users[$user]["invite"]=$invite;
		save_users($users);
		$_SESSION['lang']=$language;
		$message=$lang["message"]["profile"];
	}

	if ($users[$user]["gender"] == "fa-venus-mars")
		$gender_select1="selected";
	else if ($users[$user]["gender"] == "fa-venus-double")
		$gender_select2="selected";
	else if ($users[$user]["gender"] == "fa-venus")
		$gender_select3="selected";
	else if ($users[$user]["gender"] == "fa-transgender")
		$gender_select4="selected";
	else if ($users[$user]["gender"] == "fa-non-binary")
		$gender_select5="selected";
	else if ($users[$user]["gender"] == "fa-neuter")
		$gender_select6="selected";
	else if ($users[$user]["gender"] == "fa-mercury")
		$gender_select7="selected";
	else if ($users[$user]["gender"] == "fa-mars-stroke-up")
		$gender_select8="selected";
	else if ($users[$user]["gender"] == "fa-mars-stroke-right")
		$gender_select9="selected";
	else if ($users[$user]["gender"] == "fa-mars-double")
		$gender_select10="selected";
	else if ($users[$user]["gender"] == "fa-mars-and-venus")
		$gender_select11="selected";
	else if ($users[$user]["gender"] == "fa-mars")
		$gender_select12="selected";
	else if ($users[$user]["gender"] == "fa-genderless")
		$gender_select13="selected";

	if ($users[$user]["language"] == "de_de")
		$lang_select1="selected";
	else if ($users[$user]["language"] == "en_us")
		$lang_select2="selected";
	else if ($users[$user]["language"] == "zh_cn")
		$lang_select3="selected";

	if ($users[$user]["theme"] == "dark")
		$lang_theme1="selected";
	else if ($users[$user]["theme"] == "light")
		$lang_theme2="selected";

	if ($users[$user]["invite"] == "yes")
		$lang_invite1="selected";
	else if ($users[$user]["invite"] == "no")
		$lang_invite2="selected";

	echo '
		<form method="post" enctype="multipart/form-data">
			<div class="w3-theme-white">
				<header class="w3-container w3-text-theme">
					<h4><strong><i class="fas fa-user"></i> '. $lang["profile"]["title"] .'</strong> '. $message .'</h4>
				</header>
				<div class="w3-half">
					<div class="w3-row-padding w3-margin-bottom">
						<div class="w3-lable">'. $lang["lable"]["name"] .'</div>
						<input class="w3-input" name="name" type="text" placeholder="Fullname" style="width: 100%" value="'. $users[$user]["name"] .'" minlength="8" maxlength="16" required>
					</div>
					<div class="w3-row-padding w3-margin-bottom">
						<div class="w3-lable">'. $lang["lable"]["gender"] .'</div>
						<select class="w3-select" name="gender">
							<option value="fa-venus-mars" '. $gender_select1 .'>Hetero</option>
							<option value="fa-venus-double" '. $gender_select2 .'>Lesbian</option>
							<option value="fa-venus" '. $gender_select3 .'>Female</option>
							<option value="fa-transgender" '. $gender_select4 .'>Transgender</option>
							<option value="fa-non-binary" '. $gender_select5 .'>Genderqueer</option>
							<option value="fa-neuter" '. $gender_select6 .'>Neutrois</option>
							<option value="fa-mercury" '. $gender_select7 .'>Hermaphrodit</option>
							<option value="fa-mars-stroke-up" '. $gender_select8 .'>Androgyne</option>
							<option value="fa-mars-stroke-right" '. $gender_select9 .'>Othergender</option>
							<option value="fa-mars-double" '. $gender_select10 .'>Gay</option>
							<option value="fa-mars-and-venus" '. $gender_select11 .'>Bigender</option>
							<option value="fa-mars" '. $gender_select12 .'>Male</option>
							<option value="fa-genderless" '. $gender_select13 .'>Genderless</option>
						</select>
					</div>
				</div>
				<div class="w3-half">
					<div class="w3-row-padding w3-margin-bottom">
						<div class="w3-lable">'. $lang["lable"]["language"] .'</div>
						<select class="w3-select" name="language">
							<option value="de_de" '. $lang_select1 .'>'. $lang["lang"]["de_de"] .'</option>
							<option value="en_us" '. $lang_select2 .'>'. $lang["lang"]["en_us"] .'</option>
							<option value="zh_cn" '. $lang_select3 .'>'. $lang["lang"]["zh_cn"] .'</option>
						</select>
					</div>
					<div class="w3-row-padding w3-margin-bottom">
						<div class="w3-lable">'. $lang["lable"]["theme"] .'</div>
						<select class="w3-select" name="theme">
							<option value="dark" '. $lang_theme1 .'>'. $lang["theme"]["dark"] .'</option>
							<option value="light" '. $lang_theme2 .'>'. $lang["theme"]["light"] .'</option>
						</select>
					</div>
				</div>
				<div class="w3-half">
					<div class="w3-row-padding w3-margin-bottom">
						<div class="w3-lable">'. $lang["lable"]["invite"] .'</div>
						<select class="w3-select" name="invite">
							<option value="yes" '. $lang_invite1 .'>'. $lang["invite"]["enable"] .'</option>
							<option value="no" '. $lang_invite2 .'>'. $lang["invite"]["disable"] .'</option>
						</select>
					</div>
				</div>
				<div class="w3-row-padding w3-margin-bottom">
					<button class="w3-button w3-theme w3-hover-theme" type="submit"><i class="fas fa-save"></i> <span class="w3-hide-small">'. $lang["button"]["save"] .'</span></button>
				</div>
			</div>
		</form>
	';

	include "core/footer.php";
?>
