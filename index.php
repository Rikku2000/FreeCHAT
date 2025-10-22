<?php
	require "core/config.php";
	if(!isset($_SESSION["user"])) header("Location: login.php");
	$user=$_SESSION["user"];
	$room=$_GET["room"]??"Chill Zone";
	$rooms=load_rooms();
	if(!isset($rooms[$room])) die($lang["message"]["room_not_exist"]);
	$messages=load_messages()[$room];
	$users=load_users();
	$bans=load_bans();
	if (isset($bans[$user]))
		$messages=$lang["message"]["banned"];

	include "core/header.php";

	echo '
		<div class="w3-theme-white">
			<header class="w3-container w3-text-theme">
				<h4><strong><i class="fas fa-house"></i> '. htmlspecialchars($room) .'</strong> <span class="w3-hide-small">- '. htmlspecialchars($rooms[$room]['description']) .'</span> '. $message .'</h4>
			</header>
			<div class="w3-row-padding w3-margin-bottom">
				<div class="w3-hide-small" id="privatechat"></div>
				<div class="w3-table-scroll w3-border w3-border-theme-light w3-threequarter">
					<div class="w3-table-scroll w3-chatfield" id="chatBox" style="overflow-y: scroll; padding: 1px;"></div>
				</div>
				<div class="w3-table-scroll w3-border w3-border-theme-light w3-quarter">
					<div class="w3-table-scroll w3-border w3-border-theme-light w3-hide-small w3-chatfield" id="onlineUsers" style="overflow-y: scroll; padding: 1px;"></div>
				</div>
				<div class="w3-hide-small w3-tiny" id="typingIndicator" style="float: left; font-style: italic; color: #aaa; width: 100%;"></div>
			</div>
			<div class="w3-bottom w3-theme-white w3-chatcontainer">
				<form id="chatForm">
					<audio id="joinSound" src="assets/online.wav"></audio>
					<audio id="leaveSound" src="assets/offline.wav"></audio>
					<!-- <audio id="msgSound" src="assets/message.wav"></audio> -->
	';

	if (!isset($bans[$user])) {
		echo '
					<input type="file" id="imageUpload" accept="image/*" style="display:none;">
					<button class="w3-button w3-theme-white w3-hover-theme w3-left" type="button" onclick="document.getElementById(\'imageUpload\').click()" style="width: 10%;">'. $ImageIcon .' <span class="w3-hide-small"> '. $lang["button"]["photo"] .'</span></button>
					<input class="w3-input-theme w3-left" type="text" id="msg" placeholder="'. $lang["chat"]["input"] .'" minlength="2" style="width: 30%;" required>
					<div class="w3-button w3-theme-white w3-hover-theme w3-left" id="emoji-button" style="width: 10%;">'. $emojiIcon .' <span class="w3-hide-small"> '. $lang["button"]["emoji"] .'</span></div>
					<div id="emoji-picker" class="w3-border w3-border-theme-light w3-theme-white w3-center w3-table-scroll w3-hide" style="position: absolute; bottom: 40px; right: 10px; width: 300px; max-height: 200px; overflow-y: auto;"></div>
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
			</div>
	';
?>

			<div class="w3-hide-small w3-invite" id="inviteModal">
				<div class="w3-border w3-border-theme-light w3-theme-white">
					<header class="w3-container w3-theme">
						<h4><?=$lang["invite"]["title"]?></h4>
					</header>
					<p class="w3-center" id="inviteText"></p>
					<div class="modal-actions">
						<button class="w3-button w3-theme-white w3-hover-theme w3-half" id="acceptInvite"><?=$lang["button"]["accept"]?></button>
						<button class="w3-button w3-theme-white w3-hover-theme w3-half" id="declineInvite"><?=$lang["button"]["decline"]?></button>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				const imageInput = document.getElementById("imageUpload");
				const chatBox=document.getElementById("chatBox");
				const form=document.getElementById("chatForm");

				let lastCount = 0;
				let lastPrivatCount = 0;
				let typingTimeout;

				form.addEventListener("submit",e=>{
					e.preventDefault();
					const text=document.getElementById("msg").value;
					const color=document.getElementById("color").value;
					const style=document.getElementById("style").value;
					fetch("core/post.php",{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body:"room=<?=urlencode($room)?>&text="+encodeURIComponent(text)+"&color="+encodeURIComponent(color)+"&style="+encodeURIComponent(style)})
					.then(r=>r.text()).then(r=>{ document.getElementById("msg").value=""; document.getElementById("msg").focus(); fetchMessages(); });
				});

				function fetchMessages(){
					fetch("core/fetch.php?room=<?=urlencode($room)?>").then(r=>r.json()).then(data=>{
						chatBox.innerHTML="";
						data.forEach(m=>{
							let div=document.createElement("div");
							if (m.user == "<?=$user?>" || m.name == "<?=CHATBOT?>" || m.invite == "no")
								div.innerHTML ="<div class='w3-msg-me'><div class='w3-msg-me-bubble'><div class='meta'><i class='fas " + m.icon.toLowerCase() + "'></i> <strong>" + m.name + "</strong> <span class='w3-right w3-tiny'>" + m.time + "</span></div><div style='font-style:" + m.style + "; color:" + m.color + ";'>" + m.text + "</div></div></div>";
							else
								div.innerHTML ="<div class='w3-msg'><div class='w3-msg-bubble'><div class='meta'><a class='w3-hover-text-theme' href='private.php?user="+ m.user +"' style='text-decoration: none;'><i class='fas " + m.icon.toLowerCase() + "'></i> <strong>" + m.name + "</strong></a> <span class='w3-right w3-tiny'>" + m.time + "</span></div><div style='font-style:" + m.style + "; color:" + m.color + ";'>" + m.text + "</div></div></div>";
							chatBox.appendChild(div);
						});

						if (data.length > lastCount) {
							let last = data[data.length - 1];
							if (last.name === "<?=CHATBOT?>") {
								if (last.text.includes("<?=$lang["message"]["joined"]?>")) document.getElementById("joinSound").play();
								if (last.text.includes("<?=$lang["message"]["left"]?>")) document.getElementById("leaveSound").play();
							}
						}
						lastCount = data.length;

						chatBox.scrollTop=chatBox.scrollHeight;
					});
				}

				window.addEventListener("beforeunload", ()=>{
					navigator.sendBeacon("core/heartbeat.php?logout=1&room=<?=urlencode($room)?>");
				});

				function heartbeat(){
					fetch("core/heartbeat.php?room=<?=urlencode($room)?>");
				}

				function loadOnlineUsers(){
					fetch("core/online.php?room=<?=urlencode($room)?>").then(r=>r.json()).then(data=>{
						let list = "";
						data.forEach(u=>{
							if (u.user == "<?=$user?>" || u.invite == "no")
								list += "<div style='padding: 2px;'><span class='w3-hide-small w3-hover-text-theme'><i class='fas fa-comments'></i></span> <span class='w3-hover-text-theme'><i class='fas "+ u.icon.toLowerCase() +"'></i> <strong>"+ u.name +"</strong></span></div>";
							else
								list += "<div style='padding: 2px;'><span class='w3-hide-small w3-hover-text-theme' onclick='openPrivateChat(\""+ u.user +"\",\""+ u.name +"\")' style='cursor: pointer'><i class='fas fa-comments'></i></span> <a class='w3-hover-text-theme' href='private.php?user="+ u.user +"' style='text-decoration: none;'><i class='fas "+ u.icon.toLowerCase() +"'></i> <strong>"+ u.name +"</strong></a></div>";
						});
						document.getElementById("onlineUsers").innerHTML=list;
					});
				}

				document.getElementById("msg").addEventListener("input", ()=>{
					sendTyping(1);
					clearTimeout(typingTimeout);
					typingTimeout = setTimeout(()=>sendTyping(0), 2000);
				});

				function sendTyping(state){
					fetch("core/post_typing.php", {
						method:"POST",
						body:"target=room:<?=urlencode($room)?>&typing="+state,
						headers:{"Content-Type":"application/x-www-form-urlencoded"}
					});
				}

				function fetchTyping(){
					fetch("core/fetch_typing.php?target=room:<?=urlencode($room)?>").then(r=>r.json()).then(users=>{
						let div = document.getElementById("typingIndicator");
						if(users.length > 0){
							div.innerText = users.join(", ") +" <?php echo $lang["chat"]["typing"]; ?>";
						} else {
							div.innerText = "";
						}
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

				function closePrivateChat(username) {
					const chat = document.getElementById("chat-" + username);
					if (chat) chat.remove();
				}

				function sendPrivateTyping(username){
					fetch("core/post_private_typing.php", {
						method:"POST",
						body:"to="+ encodeURIComponent(username),
						headers:{"Content-Type":"application/x-www-form-urlencoded"}
					});
				}

				function fetchPrivateTyping(username) {
					fetch("core/fetch_private_typing.php?user="+ encodeURIComponent(username)).then(r=>r.json()).then(typingData=>{
						const typingBox = document.getElementById("typing-" + username);
						typingBox.style.display = typingData.typing ? "block" : "none";
					});
				}

				async function checkInviteStatus(username) {
					const res = await fetch("core/check_invite_status.php?user=" + encodeURIComponent(username));
					const data = await res.json();

					if (data.status === "accepted") {
						// alert(username + " accepted your private chat request.");
					} else if (data.status === "declined") {
						// alert(username + " declined your private chat request.");
						closePrivateChat(username);
					} else {
						// setTimeout(() => checkInviteStatus(username), 5000);
					}
				}

				function openPrivateChat(username, name) {
					if (document.getElementById("chat-" + username)) return;

					const container = document.getElementById("privatechat");
					const chatBox = document.createElement("div");
					chatBox.className = "w3-private-chat w3-theme-white";
					chatBox.id = "chat-" + username;

					chatBox.innerHTML = `
						<header class="w3-private-header w3-container w3-border w3-border-theme w3-text-theme">
							<h4><strong><i class="fas fa-comments"></i> ${name}</strong> <span href="#" class="w3-text-black w3-hover-white w3-hover-text-theme w3-right" onclick="closePrivateChat('${username}')" style="cursor: pointer"><b>X</b></span></h4>
						</header>
						<div class="w3-private-messages w3-border w3-border-theme w3-theme-white" id="msgs-${username}"></div>
						<div class="w3-tiny w3-border w3-border-theme w3-theme-white" id="typing-${username}" style="display:none; float: left; font-style: italic; color: #aaa; width: 100%;">${name} <?php echo $lang["chat"]["typing"]; ?></div>
						<div class="w3-private-input w3-border w3-border-theme">
							<input class="w3-input-theme w3-left" type="text" id="input-${username}" oninput="sendPrivateTyping('${username}')" placeholder="<?php echo $lang["chat"]["input"]; ?>" minlength="2" style="width: 84%;" required>
							<button class="w3-button w3-theme-white w3-hover-theme w3-left" onclick="sendPrivateMessage('${username}')" style="width: 16%;"><i class="fas fa-paper-plane"></i></button>
						</div>
					`;

					container.appendChild(chatBox);
					makeDraggable(chatBox);

					fetch("core/private_invite.php", {
						method: "POST",
						headers: { "Content-Type": "application/x-www-form-urlencoded" },
						body: "to="+ encodeURIComponent(username) +"&name="+ encodeURIComponent(name)
					}).then(() => checkInviteStatus(username));

					setInterval(loadPrivateMessages, 500, username);
					setInterval(fetchPrivateTyping, 500, username);
				}

				function loadPrivateMessages(username) {
					fetch("core/fetch_private.php?other="+ encodeURIComponent(username)).then(r=>r.json()).then(data=>{
						const msgBox = document.getElementById("msgs-" + username);
						msgBox.innerHTML = "";
						data.forEach(m => {
							const div = document.createElement("div");
							div.innerHTML ="<div class='w3-msg'><div class='w3-msg-bubble'><div class='meta'><strong>" + m.from + "</strong></a> <span class='w3-right w3-tiny'>" + m.time + "</span></div><div style='font-style:" + m.style + "; color:" + m.color + ";'>" + m.text + "</div></div></div>";
							msgBox.appendChild(div);
						});

						/* if (data.length > lastPrivatCount) {
							let last = data[data.length - 1];
							if (last.from == username)
								document.getElementById("msgSound").play();

						}
						lastPrivatCount = data.length; */

						msgBox.scrollTop = msgBox.scrollHeight;
					});
				}

				function sendPrivateMessage(username) {
					const input = document.getElementById("input-" + username);
					const text = input.value.trim();
					if (!text) return;
					fetch("core/post_private.php", {
						method: "POST",
						headers: {"Content-Type": "application/x-www-form-urlencoded"},
						body: "other=" + encodeURIComponent(username) + "&msg=" + encodeURIComponent(text)
					});
					input.value = "";
				}

				function makeDraggable(el) {
					const header = el.querySelector(".w3-private-header");
					let offsetX = 0, offsetY = 0, isDown = false;

					header.addEventListener("mousedown", (e) => {
						isDown = true;
						offsetX = el.offsetLeft - e.clientX;
						offsetY = el.offsetTop - e.clientY;
					});

					document.addEventListener("mouseup", () => isDown = false);
					document.addEventListener("mousemove", (e) => {
						if (!isDown) return;
						el.style.left = (e.clientX + offsetX) + "px";
						el.style.top = (e.clientY + offsetY) + "px";
						el.style.right = "auto";
					});
				}

				function check_invites(){
					fetch("core/check_invites.php").then(r=>r.json()).then(data=>{

						if (data.length > lastPrivatCount) {
							const invite = data[0];
							currentInviteFrom = invite.from;
							currentInviteName = invite.name;

							document.getElementById("inviteText").innerHTML = "<b>"+ invite.name +"</b></br><?=$lang["invite"]["text"]?>";
							document.getElementById("inviteModal").style.display = "flex";
						}
						lastPrivatCount = data.length - 1;
					});
				}

				document.getElementById("acceptInvite").addEventListener("click", () => {
					if (!currentInviteFrom) return;
					document.getElementById("inviteModal").style.display = "none";
					openPrivateChat(currentInviteFrom, currentInviteName);
					fetch("core/accept_invite.php", {
						method: "POST",
						headers: { "Content-Type": "application/x-www-form-urlencoded" },
						body: "from=" + encodeURIComponent(currentInviteFrom)
					});
					currentInviteFrom = null;
				});

				document.getElementById("declineInvite").addEventListener("click", () => {
					if (!currentInviteFrom) return;
					document.getElementById("inviteModal").style.display = "none";
					fetch("core/decline_invite.php", {
						method: "POST",
						headers: { "Content-Type": "application/x-www-form-urlencoded" },
						body: "from=" + encodeURIComponent(currentInviteFrom)
					});
					currentInviteFrom = null;
				});

				setInterval(fetchTyping, <?=PRIVATEINTERVAL?>);
				setInterval(loadOnlineUsers, <?=ONLINEINTERVAL?>);
				setInterval(check_invites, <?=INVITEINTERVAL?>);
				setInterval(fetchMessages, <?=MESSAGEINTERVAL?>);
				setInterval(heartbeat, <?=HEARTBEATINTERVAL?>);
			</script>

<?php
	echo '
		</div>
	';

	include "core/footer.php";
?>
