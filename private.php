<?php
	require "core/config.php";
	if(!isset($_SESSION["user"])) { header("Location: login.php"); exit; }
	$user=$_SESSION["user"];
	$other=$_GET["user"];
	$users=load_users();
	if(!$other || $user === $other) { die($lang["message"]["invalid_user"]); }
	$key = implode("|", array_sort([$user, $other]));
	function array_sort($arr){
		sort($arr, SORT_STRING);
		return $arr;
	}

	include "core/header.php";

	echo '
		<div class="w3-theme-white">
			<header class="w3-container w3-text-theme">
				<h4><strong><i class="fas fa-user"></i> '. $lang["private"]["title"] .'</strong> <span class="w3-hide-small">- '. htmlspecialchars($users[$other]["name"]) .'</span></h4>
			</header>
			<div class="w3-row-padding w3-margin-bottom">
				<div class="w3-table-scroll w3-border w3-border-theme-light">
					<div class="w3-table-scroll w3-chatfield" id="chat" style="overflow-y: scroll; padding: 1px;"></div>
				</div>
				<div class="w3-hide-small w3-tiny" id="typingIndicator" style="float: left; font-style: italic; color: #aaa; width: 100%;"></div>
			</div>
			<div class="w3-bottom w3-theme-white w3-chatcontainer">
				<div class="emoji-container">
					<div class="w3-border w3-border-theme-light emoji-popup" id="emoji-popup">
						<div class="w3-theme tabs">
		';

		foreach ($emojiCategories as $category => $emojis) echo '<div class="tab">'. $category .'</div>';

		echo '
					</div>
		';

		foreach ($emojiCategories as $category => $emojis) {
			echo '<div class="w3-theme-white emoji-grid">';
			foreach ($emojis as $emoji) echo '<div class="emoji">'. $emoji .'</div>';
			echo '</div>';
		}

		echo '
					</div>
				</div>
				<form onsubmit="sendPrivate(event)">
					<audio id="msgSound" src="assets/message.wav"></audio>
	';

	if (!isset($bans[$user])) {
		echo '
					<input type="file" id="imageUpload" accept="image/*" style="display:none;">
					<button class="w3-button w3-theme-white w3-hover-theme w3-left" type="button" onclick="document.getElementById(\'imageUpload\').click()" style="width: 10%;">'. $ImageIcon .'</button>
					<input class="w3-input-theme w3-left" type="text" id="msg" placeholder="Type your message" minlength="2" style="width: 30%;" required>
					<a class="w3-button w3-theme-white w3-hover-theme w3-left" id="emoji-button" style="width: 10%;">'. $emojiIcon .'</a>
					<select class="w3-select-theme w3-left" id="color" style="width: 19%;">
		';

		if ($users[$user]["theme"] == "dark")
			echo '<option value="#ffffff">Default</option>';
		else
			echo '<option value="#000000">Default</option>';

		echo '
						<option value="#9E0F22" style="color: #9E0F22;">Dark Red</option>
						<option value="#E44235" style="color: #E44235;">Light Red</option>
						<option value="#111C4E" style="color: #111C4E;">Dark Blue</option>
						<option value="#1083C4" style="color: #1083C4;">Light Blue</option>
						<option value="#062721" style="color: #062721;">Dark Green</option>
						<option value="#3A984A" style="color: #3A984A;">Light Green</option>
						<option value="#C08856" style="color: #C08856;">Dark Yellow</option>
						<option value="#F4B72B" style="color: #F4B72B;">Light Yellow</option>
						<option value="#11151F" style="color: #11151F;">Dark Gray</option>
						<option value="#A8ACBA" style="color: #A8ACBA;">Light Gray</option>
					</select>
					<select class="w3-select-theme w3-left" id="style" style="width: 19%;">
						<option value="normal" style="font-style: normal;">Normal</option>
						<option value="italic" style="font-style: italic;">Italic</option>
					</select>
					<button class="w3-button w3-theme-white w3-hover-theme w3-left" type="submit" style="width: 12%;">&nbsp;<i class="fas fa-paper-plane"></i><span class="w3-hide-small"> '. $lang["button"]["send"] .'</span>&nbsp;</button>
		';
	}

	echo '
				</form>
			</div>
	';
?>

			<script type="text/javascript" src="assets/emoji.js"></script>
			<script>
				const imageInput = document.getElementById("imageUpload");

				let lastCount = 0;
				let typingTimeout;

				function fetchPrivate(){
					fetch("core/fetch_private.php?other=<?=urlencode($other)?>").then(r=>r.json()).then(data=>{
						let chat = document.getElementById("chat");
						chat.innerHTML = "";
						data.forEach(m=>{
							let div = document.createElement("div");
							div.className = m.from === "<?=$user?>" ? "msg me" : "msg";
							div.innerHTML=`<div style="padding: 2px;"><strong>${m.from}</strong>: <span class="w3-right w3-tiny">${m.time}</span><span style="font-style: ${m.style}; color: ${m.color};">${m.text}</span></div>`;
							chat.appendChild(div);
						});

						if (data.length > lastCount) {
							let last = data[data.length - 1];
							if (last.from !== "<?=$user?>")
								document.getElementById("msgSound").play();
						}
						lastCount = data.length;

						chat.scrollTop = chat.scrollHeight;
					});
				}

				function sendPrivate(e){
					e.preventDefault();
					let msg = document.getElementById("msg").value;
					const color=document.getElementById("color").value;
					const style=document.getElementById("style").value;
					fetch("core/post_private.php", {
						method:"POST",body:"other=<?=urlencode($other)?>&msg="+encodeURIComponent(msg)+"&color="+encodeURIComponent(color)+"&style="+encodeURIComponent(style),headers:{"Content-Type":"application/x-www-form-urlencoded"}
					}).then(()=>{ document.getElementById("msg").value=""; document.getElementById("msg").focus(); fetchPrivate(); });
				}

				document.getElementById("msg").addEventListener("input", ()=>{
					sendTyping(1);
					clearTimeout(typingTimeout);
					typingTimeout = setTimeout(()=>sendTyping(0), 2000);
				});

				function sendTyping(state){
					fetch("core/post_typing.php", {
						method:"POST",
						body:"target=private:<?=urlencode($key)?>&typing="+state,
						headers:{"Content-Type":"application/x-www-form-urlencoded"}
					});
				}

				function fetchTyping(){
					fetch("core/fetch_typing.php?target=private:<?=urlencode($key)?>").then(r=>r.json()).then(users=>{
						let div = document.getElementById("typingIndicator");
						if(users.length > 0){
							div.innerText = users.join(", ") +" <?php echo $lang["private"]["typing"]; ?>";
						} else {
							div.innerText = "";
						}
					});
				}

				imageInput.addEventListener("change", () => {
					if(imageInput.files.length > 0){
						let formData = new FormData();
						formData.append("image", imageInput.files[0]);
						fetch("core/upload.php", { method: "POST", body: formData }).then(r => r.text()).then(tag => {
							if(tag.startsWith("[img]"))
								msg.value += tag;
							else
								alert(tag);
						});
					}
				});

				setInterval(fetchTyping, 500);
				setInterval(fetchPrivate, 500);
			</script>


<?php
	echo '
		</div>
	';

	include "core/footer.php";
?>
