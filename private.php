<?php
	require "core/config.php";
	if(!isset($_SESSION["user"])) { header("Location: login.php"); exit; }
	$user=$_SESSION["user"];
	$other=$_GET["user"];
	$users=load_users();
	if(!$other || $user === $other) { die($lang["message"]["invalid_user"]); }
	if ($users[$other]["invite"] == "no") { header("Location: login.php"); exit; }
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
			<div class="w3-container">
				<div class="w3-table-scroll">
					<div class="w3-table-scroll w3-chatfield" id="chat" style="overflow-y: scroll; padding: 1px;"></div>
				</div>
			</div>
			<div id="emoji-picker" class="w3-border w3-border-theme-light w3-theme-white w3-center w3-table-scroll w3-hide" style="position: absolute; bottom: 60px; right: 10px; width: 300px; max-height: 200px; overflow-y: auto;  z-index: 10;"></div>
			<div class="w3-bottom w3-theme-white w3-chatcontainer">
				<form onsubmit="sendPrivate(event)">
					<audio id="msgSound" src="assets/message.wav"></audio>
	';

	if (!isset($bans[$user])) {
		echo '
					<input type="file" id="imageUpload" accept="image/*" style="display:none;">
					<button class="w3-button w3-theme-white w3-hover-theme w3-left" type="button" onclick="document.getElementById(\'imageUpload\').click()" style="width: 10%;">'. $ImageIcon .' <span class="w3-hide-small"> '. $lang["button"]["photo"] .'</span></button>
					<input class="w3-input-theme w3-left" type="text" id="msg" placeholder="'. $lang["private"]["input"] .'" minlength="2" style="width: 30%;" required>
					<div class="w3-button w3-theme-white w3-hover-theme w3-left" id="emoji-button" style="width: 10%;">'. $emojiIcon .' <span class="w3-hide-small"> '. $lang["button"]["emoji"] .'</span></div>
					<select class="w3-select-theme w3-left" id="color" style="width: 19%;">
		';

		if ($users[$user]["theme"] == "dark")
			echo '<option value="#ffffff">Default</option>';
		else
			echo '<option value="#000000">Default</option>';

		echo '
						<option value="#E44235" style="color: #E44235;">Red</option>
						<option value="#1083C4" style="color: #1083C4;">Blue</option>
						<option value="#3A984A" style="color: #3A984A;">Green</option>
						<option value="#F4B72B" style="color: #F4B72B;">Yellow</option>
						<option value="#A8ACBA" style="color: #A8ACBA;">Gray</option>
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
				<div class="w3-hide-small w3-tiny" id="typingIndicator" style="float: left; font-style: italic; color: #aaa; bottom: 6px; width: 100%; position: absolute;"></div>
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
							div.innerHTML=`<div class='w3-msg'><div class='w3-msg-bubble'><div class='meta'><strong>${m.from}</strong> <span class='w3-right w3-tiny'>${m.time}</span></div><div style='font-style:" + m.style + "; color:" + m.color + ";'>${m.text}</div></div></div>`;
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
						if(!div){
							const container = document.querySelector(".w3-container") || document.body;
							div = document.createElement("div");
							div.id = "typingIndicator";
							div.className = "w3-tiny";
							container.appendChild(div);
						}
						if(Array.isArray(users) && users.length > 0){
							div.innerText = users.join(", ") + " <?php echo $lang["chat"]["typing"]; ?>";
							div.style.display = "block";
							div.style.fontStyle = "italic";
							div.style.color = "#aaa";
						} else {
							div.innerText = "";
							div.style.display = "none";
						}
					}).catch(()=>{
						const div = document.getElementById("typingIndicator");
						if(div){ div.style.display = "none"; }
					});
				}

				async function loademoji() {
					const res = await fetch("assets/emoji.json");
					const data = await res.json();
					const picker = document.getElementById("emoji-picker");
					picker.innerHTML = "";

					for (const category in data) {
						const title = document.createElement("div");
						title.textContent = category;
						title.style.width = "100%";
						title.style.fontWeight = "bold";
						title.className = "w3-border w3-border-theme-light w3-theme";
						picker.appendChild(title);

						for (const [name, path] of Object.entries(data[category])) {
							const img = document.createElement("img");
							img.src = path;
							img.alt = name;
							img.style.padding = "2px";
							img.addEventListener("click", () => {
								const input = document.getElementById("msg");
								input.value += `:[${name}]:`;
							});
							picker.appendChild(img);
						}
					}
				}

				document.getElementById("emoji-button").addEventListener("click", () => {
					const picker = document.getElementById("emoji-picker");
					picker.classList.toggle("w3-hide");
					if (!picker.classList.contains("w3-hide")) loademoji();
				});

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

				setInterval(fetchTyping, <?=TYPINGINTERVAL?>);
				setInterval(fetchPrivate, <?=PRIVATEINTERVAL?>);
			</script>


<?php
	echo '
		</div>
	';

	include "core/footer.php";
?>
