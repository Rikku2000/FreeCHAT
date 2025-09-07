<?php
	require "core/config.php";
	include "core/header.php";

	echo '
		<img class="w3-image w3-hide-small" src="assets/winx-os_tan.png" style="bottom: 0; right: 0; position: absolute; opacity: 0.2; z-index: 10;">
		<div class="w3-theme-white">
			<div class="w3-half">
				<div class="w3-row-padding w3-margin-bottom">
					<p><h4 class="w3-text-theme"><strong>'. $lang["page"]["imprint"] .'</strong></h4></p>
					<p>
						
					</p>
				</div>
			</div>
			<div class="w3-half">
				<div class="w3-row-padding w3-margin-bottom">

				</div>
			</div>
		</div>
		<div class="w3-bottom w3-theme-white w3-box">
			<div class="w3-container w3-center">
				- <a class="w3-link w3-hover-text-theme" href="imprint.php">'. $lang["page"]["imprint"] .'</a> | <a class="w3-link w3-hover-text-theme" href="privacy.php">'. $lang["page"]["privacy"] .'</a> -
			</div>
		</div>
	';

	include "core/footer.php";
?>
