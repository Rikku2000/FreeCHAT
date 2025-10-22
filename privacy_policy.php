<?php
	require "core/config.php";
	include "core/header.php";

	echo '
		<img class="w3-image w3-hide-small" src="assets/winx-os_tan.png" style="bottom: 32; right: 0; position: absolute; opacity: 0.2; z-index: 10;">
		<div class="w3-theme-white">
			<header class="w3-container w3-text-theme">
				<h4><strong><i class="fas fa-user"></i> '. $lang["page"]["privacy_policy"] .'</strong></h4>
			</header>
			<div class="w3-row-padding w3-margin-bottom">
				<div class="w3-container">
					'. $lang["privacy_policy"]["text"] .'
				</div>
			</div>
		</div>
		<div class="w3-bottom w3-theme-white w3-box">
			<div class="w3-container w3-center">
				- <a class="w3-link w3-hover-text-theme" href="imprint.php">'. $lang["page"]["imprint"] .'</a> | <a class="w3-link w3-hover-text-theme" href="privacy_policy.php">'. $lang["page"]["privacy_policy"] .'</a> | <a class="w3-link w3-hover-text-theme" href="terms_of_use.php">'. $lang["page"]["terms_of_use"] .'</a> -
			</div>
		</div>
	';

	include "core/footer.php";
?>
