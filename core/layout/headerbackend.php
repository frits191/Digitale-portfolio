<?php
echo "<div class='container-fluid'>";
	echo "<div id='header'>";
		echo "<div class='row'>";
			echo "<div class='col-lg-9' id='banner'>";
				echo "<div id='mainblock'>";
					echo "<a href='backend.php?p=home' title='Logo stenden'>";
						echo "<img id='logo' src='../Digitale-portfolio/core/images/header-logo.png' alt='logo' />";
					echo "</a>";
				echo "</div>";
			echo "</div>";
			echo "<div class='col-lg-3' id='userLogged'>";
				echo "<div id='userblock'>";
					echo "<p>";

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

					echo "</p>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "<div class='row'>";
			echo "<div id='downblock' class='col-lg-12'>";
 
				if (isset($_GET["project"])) {
					$project = htmlspecialchars($_GET["project"]);
					if (is_int($project !== true)) {
						header('Location: backend.php?p=home');
						exit();
					}

					echo "<div class='item-add'>";
						echo "<div class='btn-group'>";
							echo "<button type='button' class='btn btn-default' aria-label='Left Align'>";
								echo "<span class='glyphicon glyphicon-file' aria-hidden='true' title='Add a new file' data-toggle='modal' data-target='#AddFile'></span>";
							echo "</button>";					
						echo "</div>";
					echo "</div>";
				} elseif ($p == "projecten") {
					echo "<div class='item-add'>";
						echo "<div class='btn-group'>";
							echo "<button type='button' class='btn btn-default' aria-label='Left Align'>";
								echo "<span class='glyphicon glyphicon-folder-open' aria-hidden='true' title='Add a new folder' data-toggle='modal' data-target='#AddFolder'></span>";
							echo "</button>";					
						echo "</div>";
					echo "</div>";
				}
			echo "</div>";
		echo "</div>";
	echo "</div>";
echo "</div>";
?>