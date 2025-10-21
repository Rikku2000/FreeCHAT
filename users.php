<?php
	require "core/config.php";
	if(!isset($_SESSION["user"])) header("Location: login.php");
	$user=$_SESSION["user"];
	$users=load_users();

	include "core/header.php";

	echo '
		<div class="w3-theme-white">
			<header class="w3-container w3-text-theme">
				<h4><strong><i class="fas fa-users"></i> '. $lang["users"]["title"] .'</strong></h4>
			</header>
			<div class="w3-row-padding w3-margin-bottom">
				<table class="w3-table w3-striped w3-theme-white">
					<tr class="w3-theme w3-border w3-border-theme-light">
						<td><b>'. $lang["users"]["user"] .'</b></td>
					</tr>
					<tbody id="membersList">
					</tbody>
				</table>
			</div>
			<div class="w3-row-padding w3-margin-bottom">
				<input type="text" id="memberSearch" class="w3-input-theme w3-left" placeholder="'. $lang["users"]["search"] .'">
				<div class="w3-row-padding w3-margin-bottom w3-left" id="pagination"></div>
			</div>
		</div>
	';
?>

		<script>
			const members = <?php echo json_encode($users); ?>;
			const membersList = document.getElementById("membersList");
			const searchBox = document.getElementById("memberSearch");
			const pagination = document.getElementById("pagination");

			let currentPage = 1;
			const perPage = 15;

			function renderMembers(filter = "", page = 1) {
				let table_theme = 'w3-theme-light';

				membersList.innerHTML = "";
				pagination.innerHTML = "";

				const filtered = Object.keys(members).filter(user =>
					user.toLowerCase().includes(filter.toLowerCase())
				);

				const totalPages = Math.ceil(filtered.length / perPage);
				if (page > totalPages) page = totalPages;
				if (page < 1) page = 1;

				const start = (page - 1) * perPage;
				const end = start + perPage;
				const pageMembers = filtered.slice(start, end);

				pageMembers.forEach(user => {
					const tr = document.createElement("tr");
					tr.className = "w3-theme-white";
					if (table_theme == "w3-theme-light")
						table_theme = "w3-theme-white";
					else
						table_theme = "w3-theme-light";
					if (members[user].invite == "no")
						tr.innerHTML = '<td class="w3-border '+ table_theme +' w3-border-theme-light w3-hover-text-theme" style="vertical-align: middle;"><span class="w3-link"><b><i class="fas '+ members[user].gender +'"></i> '+ members[user].name +'</b></span></td>';
					else
						tr.innerHTML = '<td class="w3-border '+ table_theme +' w3-border-theme-light w3-hover-text-theme" style="vertical-align: middle;"><a class="w3-link" href="private.php?user='+ user +'"><b><i class="fas '+ members[user].gender +'"></i> '+ members[user].name +'</b></a></td>';
					membersList.appendChild(tr);
				});

				for (let i = 1; i <= totalPages; i++) {
					const btn = document.createElement("button");
					btn.textContent = i;
					btn.className = "w3-button w3-theme-white w3-hover-theme";
					if (i === page) btn.classList.add("active");
					btn.addEventListener("click", () => {
						currentPage = i;
						renderMembers(searchBox.value, currentPage);
					});
					pagination.appendChild(btn);
				}
			}

			searchBox.addEventListener("input", () => { currentPage = 1; renderMembers(searchBox.value, currentPage); });
			renderMembers("", currentPage);
		</script>

<?php
	include "core/footer.php";
?>
