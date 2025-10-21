<?php $users=load_users(); ?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en" id="top" dir="ltr">
	<head>
		<title><?=TITLE?></title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="index">
		<meta name="robots" content="follow">
		<meta name="language" content="English">
		<meta name="description" content="<?=DESCRIPTION?>">
		<meta name="keywords" content="<?=KEYWORDS?>">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="cache-control" content="max-age=0">
		<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
		<meta http-equiv="expires" content="0">
		<meta http-equiv="pragma" content="no-cache">
		<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
		<link rel="stylesheet" href="assets/w3.css">

<?php
	if ($users[$user]["theme"] == "dark")
		echo '<link rel="stylesheet" href="assets/w3-theme-owner-dark.css">';
	else
		echo '<link rel="stylesheet" href="assets/w3-theme-owner.css">';
?>

		<link rel="stylesheet" href="assets/font-awesome.all.css">
		<link rel="stylesheet" href="assets/raleway.css">
		<script type="text/javascript">
			function sidebar_handle() {
				var sidebar = document.getElementById("sidebar");
				if (sidebar.style.display === 'block')
					sidebar.style.display = 'none';
				else
					sidebar.style.display = 'block';
			}
		</script>
	</head>
	<body class="w3-theme-white">
		<div class="w3-bar w3-top w3-theme w3-large" style="z-index: 4;">
			<button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-gray w3-right" onclick="sidebar_handle();"><strong><i class="fas fa-bars"></i> <?php echo $lang["page"]["menu"]; ?></strong></button>
			<span class="w3-bar-item w3-button w3-hide-small w3-hover-none w3-hover-text-light-gray w3-right"><?php echo $lang["page"]["welcome"]; ?> <strong><?php echo $users[$user]["name"]; ?></strong></span>
			<a class="w3-bar-item w3-button w3-hover-none w3-hover-text-light-gray w3-left" href="index.php"><strong><i class="fas fa-message"></i> <?=TITLE?></strong> <span class="w3-hide-small"><?=DESCRIPTION?></span></a>
		</div>

<?php
	echo '
		<nav class="w3-sidebar w3-collapse w3-theme-white w3-table-scroll" style="z-index: 3; width: 300px;" id="sidebar">
	';

	if (($users[$user]["is_admin"] != false) && (isset($_SESSION["user"]))) {
		echo '
			<div class="w3-container w3-text-theme">
				<h5><strong><i class="fas fa-bars"></i> '. $lang["page"]["admin"] .'</strong></h5>
			</div>
			<div class="w3-bar-block">
				<a href="admin_rooms.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-house"></i> '. $lang["page"]["rooms"] .'</a>
				<a href="admin_users.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-users"></i> '. $lang["page"]["users"] .'</a>
			</div>
			<div class="w3-container w3-text-theme">
				<h5><strong><i class="fas fa-bars"></i> '. $lang["page"]["menu"] .'</strong></h5>
			</div>
			<div class="w3-bar-block">
				<a href="rooms.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-house"></i> '. $lang["page"]["rooms"] .'</a>
				<a href="users.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-users"></i> '. $lang["page"]["users"] .'</a>
				<a href="profile.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-user"></i> '. $lang["page"]["profile"] .'</a>
				<a href="logout.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-sign-out"></i> '. $lang["page"]["logout"] .'</a>
			</div>
		';
	} else {
		echo '
			<div class="w3-container w3-text-theme">
				<h5><strong><i class="fas fa-bars"></i> '. $lang["page"]["menu"] .'</strong></h5>
			</div>
			<div class="w3-bar-block">
		';

		if (strrpos ($_SERVER["REQUEST_URI"], "register.php") != false) {
			echo '
				<a href="login.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-sign-in"></i> '. $lang["page"]["login"] .'</a>
			';
		} else if (strrpos ($_SERVER["REQUEST_URI"], "login.php") != false) {
			echo '
				<a href="register.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-signature"></i> '. $lang["page"]["register"] .'</a>
			';
		} else if ((strrpos ($_SERVER["REQUEST_URI"], "imprint.php") != false) or (strrpos ($_SERVER["REQUEST_URI"], "privacy.php") != false)) {
			echo '
				<a href="login.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-sign-in"></i> '. $lang["page"]["login"] .'</a>
				<a href="register.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-signature"></i> '. $lang["page"]["register"] .'</a>
			';
		} else {
			if(isset($_SESSION["user"])) {
				if (strrpos ($_SERVER["REQUEST_URI"], "index.php") != false) {
					echo '
						<a href="rooms.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-house"></i> '. $lang["page"]["rooms"] .'</a>
						<a href="users.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-users"></i> '. $lang["page"]["users"] .'</a>
						<a href="profile.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-user"></i> '. $lang["page"]["profile"] .'</a>
					';
				} else if (strrpos ($_SERVER["REQUEST_URI"], "private.php") != false) {
					echo '
						<a href="rooms.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-house"></i> '. $lang["page"]["rooms"] .'</a>
						<a href="users.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-users"></i> '. $lang["page"]["users"] .'</a>
						<a href="profile.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-user"></i> '. $lang["page"]["profile"] .'</a>
					';
				} else if (strrpos ($_SERVER["REQUEST_URI"], "rooms.php") != false) {
					echo '
						<a href="users.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-users"></i> '. $lang["page"]["users"] .'</a>
						<a href="profile.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-user"></i> '. $lang["page"]["profile"] .'</a>
					';
				} else if (strrpos ($_SERVER["REQUEST_URI"], "profile.php") != false) {
					echo '
						<a href="rooms.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-house"></i> '. $lang["page"]["rooms"] .'</a>
						<a href="users.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-users"></i> '. $lang["page"]["users"] .'</a>
					';
				} else if (strrpos ($_SERVER["REQUEST_URI"], "users.php") != false) {
					echo '
						<a href="rooms.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-house"></i> '. $lang["page"]["rooms"] .'</a>
						<a href="profile.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-user"></i> '. $lang["page"]["profile"] .'</a>
					';
				}

				echo '
					<a href="logout.php" class="w3-bar-item w3-button w3-padding w3-hover-white w3-hover-text-theme"><i class="fas fa-sign-out"></i> '. $lang["page"]["logout"] .'</a>
				';
			}
		}

		echo '
			</div>
		';
	}

		if(isset($_SESSION["user"])) {
			echo '
				<div class="w3-container w3-text-theme">
					<h5><strong><i class="fas fa-bars"></i> '. $lang["page"]["activity"] .'</strong></h5>
				</div>
				<div class="w3-bar-block w3-padding" id="activity">
				</div>
			';
?>

			<script type="text/javascript">
				function fetchActivity(){
					fetch("core/activity.php").then(r=>r.json()).then(data=>{
						let list = "";
						data.forEach(o=>{
							list += "<a class='w3-border-theme-light w3-hover-text-theme w3-link' href='index.php?room="+ o.activity +"'><b>"+ o.activity +"</b><span class='w3-right'>"+ o.count +" <i class='fas fa-users'></i></span></a></br>";
						});
						document.getElementById("activity").innerHTML=list;
					});
				}

				setInterval(fetchActivity, 500);
			</script>

<?php
		}

	echo '
		</nav>
	';
?>

		<div class="w3-main" style="height: 90vh; margin-left: 300px; margin-top: 42px;">
