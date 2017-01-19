<div class='container-fluid'>
	<div id='header'>
		<div class='row'>
			<div class='col-lg-9' id='banner'>
				<div id='mainblock'>
					<a href='backend.php?p=home' title='Logo stenden'>
						<img id='logo' src='../Digitale-portfolio/core/images/header-logo.png' alt='logo' />
					</a>
				</div>
			</div>
			<div class='col-lg-3' id='userLogged'>
				<div id='userblock'>
					<p>
						<?php
						if (isset($_SESSION["loggedIn"])) {
							if ($_SESSION["loggedIn"] == true) {
								echo "U bent ingelogd als: <br />";
								echo $_SESSION["name"] . " ("  . $_SESSION["role"] . ")";
								echo "<br /><br />";
								echo "<a id='userlink' href='backend.php?p=logout'>Uitloggen</a>";
							} else {
								echo "U bent niet ingelogd.<br>";	
							}
						} else {
							echo "U bent niet ingelogd.<br>";
						}
						?>
					</p>
				</div>
			</div>
		</div>
		<div class='row'>
			<div id='downblock' class='col-lg-12'> 
			<?php
			if ($p == "projecten") {
				echo "<div class='item-add'>";
					echo "<div class='btn-group'>";
						echo "<button type='button' class='btn btn-default' aria-label='Left Align'>";
							echo "<span class='glyphicon glyphicon-folder-open' aria-hidden='true' title='Add a new folder' data-toggle='modal' data-target='#AddFolder'></span>";
						echo "</button>";
						echo "<button type='button' class='btn btn-default' aria-label='Left Align'>";
							echo "<span class='glyphicon glyphicon-file' aria-hidden='true' title='Add a new file' data-toggle='modal' data-target='#AddFile'></span>";
						echo "</button>";						
					echo "</div>";
				echo "</div>";
			}
			?>
			</div>
		</div>
	</div>
</div>