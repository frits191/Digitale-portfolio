<?php

session_start();
require('core/connect.php');
require('core/functions.php');
require('core/backendPages.php');

$functions = new functions;
$pages = new Pages;

$p = htmlspecialchars($_GET["p"]);

//Checks if the page is not empty and if the method exists
if (empty($p)) {
	header('Location: backend.php?p=home');
	exit();
}

if (method_exists($pages, $p) == false) {
	header('Location: backend.php?p=home');
	exit();
}

//Checks if the user is logged in
if ($p !== "login" && $p !== "logout") {
	if (isset($_SESSION["loggedIn"])) {
		if ($_SESSION["loggedIn"] == false) {
			header('Location: backend.php?p=login');
			exit();
		}
	} else {
		$_SESSION["loggedIn"] = false;
		header('Location: backend.php?p=login');
		exit();
	}
}

//When a user is already logged in they are not allowed to go to the login page
if ($p == "login") {
	if ($_SESSION["loggedIn"] == true) {
		header('Location: backend.php?p=home');
		exit();
	}
}

if (isset($_SESSION["role"])) {
	$role = $_SESSION["role"];
}

//Changes the viewed portfolio when a user is Docent, SLB or Admin
if (isset($_POST["PortfolioID"])) {
	$selected = htmlspecialchars($_POST["PortfolioID"]);
	$_SESSION["portfolio_id"] = $selected;
}

echo "<!DOCTYPE html>";
echo "<html>";
	echo "<head>";
		echo "<title>Digitaal Portfolio</title>";
		echo '<meta charset="utf-8" />';
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">';

		//JS
		echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
		//echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>';
		echo '<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" type="text/javascript">></script>';
		echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>';
		echo '<script src="js/scripts.js"></script>';

		//CSS
		echo '<link rel="stylesheet" type="text/css" href="../Digitale-portfolio/css/backend.css">';
		echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
		echo '<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">';
		echo '<link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">';
		echo '<link rel="stylesheet" href="css/jquery.fileupload.css">';
		echo '<link rel="stylesheet" href="css/jquery.fileupload-ui.css">';

		//Hide Angular JS elements before initializing
		echo '<style>';
			echo '.ng-cloak { display: none; }';
		echo '</style>';

	echo "</head>";
	echo "<body>";
	echo "<div id='wrapper'>";

		require ('core/layout/headerbackend.php');

		//Menu
		echo "<div id='body' class='container-fluid clearfix'>";
			echo "<div class='row mainContent clearfix '>";

				 echo "<div class='col-lg-2 bootstrap-vertical-nav'>";
				 	echo "<div class='navbar navbar-light'>";
					echo "<button class='navbar-toggler hidden-md-up' type='button' data-toggle='collapse' data-target='#exCollapsingNavbar2'>&#9776;</button>";
						echo "<div class='collapse navbar-toggleable-sm' id='exCollapsingNavbar2'>";
							echo "<div id='searchbar'>";
								echo "<form class='form-inline' id='search' method='POST' action='#'>";
									echo "<input type='text' class='searchinput' name='search' size='10' maxlength='120' placeholder='Search'><input type='submit' value='>' class='searchbutton' title='Search'>";
								echo "</form>";
							echo "</div>";

					if (isset($role)) {
						if ($role !== "student") {
							echo "<div class='portfolioSelect'>";
								echo "<label for='selPort'>Bekijk de portfolio van:</label>";
								echo "<form action='backend.php?p=home' method='post' >";
									echo '<div class="form-group">';
										echo "<select name='PortfolioID' class='form-control' id='selPort' onchange='this.form.submit()'>";
											//Select portfolio based on user role
											$UserID = $_SESSION["id"];
											if ($role == "docent" || $role == "SLB") {
												$SQLString = "SELECT following FROM user WHERE id = " . $UserID;
												$QueryResult = $functions->executeQuery($SQLString);
												$row = mysqli_fetch_assoc($QueryResult);

												$following = explode(',', $row["following"]);
												$sqlFollow = '';
												for ($x = count($following); $x > 0; $x--) {
													if ($x == 1) {
														$sqlFollow .= "id = " . $following[$x - 1];
													} else {
														$sqlFollow .= "id = " . $following[$x - 1] . " OR ";
													}
												}

												$SQLString = "SELECT title, id FROM portfolio WHERE " . $sqlFollow;
												$QueryResult = $functions->executeQuery($SQLString);
												$row = mysqli_fetch_all($QueryResult);

												if (empty($_SESSION["portfolio_id"])) {
													$_SESSION["portfolio_id"] = $row[0][1];
												}

												for ($i = count($row); $i > 0; $i--) {
													echo "<option value='" . $row[$i - 1][1] . "' " . ($_SESSION["portfolio_id"] == $row[$i - 1][1] ? 'selected=\'selected\'' : '') . ">" . $row[$i - 1][0] . "</option>";
												}
											} elseif ($role == "admin") {
												$SQLString = "SELECT title, id FROM portfolio";
												$QueryResult = $functions->executeQuery($SQLString);
												$row = mysqli_fetch_all($QueryResult);

												if (empty($_SESSION["portfolio_id"])) {
													$_SESSION["portfolio_id"] = $row[0][1];
												}

												for ($i = count($row); $i > 0; $i--) {
													echo "<option value='" . $row[$i - 1][1] . "' " . ($_SESSION["portfolio_id"] == $row[$i - 1][1] ? 'selected=\'selected\'' : '') . ">" . $row[$i - 1][0] . "</option>";
												}
											}
										echo "</select>";
									echo "</div>";
								echo "</form>";
							echo "</div>";
						}
					}

					echo "<p>Menu</p>";
					echo "<ul class='navbar navbar-nav'>";
						echo "<a href='backend.php?p=info'><li class='nav-item' id='NavItem'>Persoonlijke gegevens</li></a>";
						echo "<a href='backend.php?p=cijfers'><li class='nav-item' id='NavItem'>Cijfers</li></a>";
						echo "<a href='backend.php?p=projecten'><li class='nav-item' id='NavItem'>Projecten</li></a>";
						echo "<a href='backend.php?p=stages'><li class='nav-item' id='NavItem'>Stages</li></a>";
						echo "<a href='backend.php?p=portfolio'><li class='nav-item' id='NavItem'>Openbaar portfolio</li></a>";
						echo "<a href='backend.php?p=opmerkingen'><li class='nav-item' id='NavItem'>Opmerkingen</li></a>";
						if (isset($_SESSION["role"])) {
					 		if ($role == "admin") {
					 			echo "<a href='backend.php?p=gebruikers'><li class='nav-item' id='NavItem'>Gebruikers beheren</li></a>";
 							}
						}
					echo "</ul>";
				echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "<div class='col-lg-10'>";
			
				//Checks which page is called and directs traffic to the appropriate page
				if ($p == "home") {
					$pages->home();
				}
				elseif ($p == "login") {
					$pages->login();
				}
				elseif ($p == "logout") {
					$pages->logout();
				}
				elseif ($p == "info") {
					$pages->info();
				}
				elseif ($p == "cijfers") {
					$pages->cijfers();
				}
				elseif ($p == "projecten") {
					$pages->projecten();
				}
				elseif ($p == "stages") {
					$pages->stages();
				}
				elseif ($p == "portfolio") {
					$pages->portfolio();
				}
				elseif ($p == "opmerkingen") {
					$pages->opmerkingen();
				}
				elseif ($p == "gebruikers") {
					if ($role == "admin") {
						$pages->gebruikers();
					} else {
						header ('Location: backend.php?p=home');
						exit();
					}
				}
			echo "</div>";
		echo "</div>";

			echo '<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">';
				echo '<div class="slides"></div>';
				echo '<h3 class="title"></h3>';
				echo '<a class="prev">�</a>';
				echo '<a class="next">�</a>';
				echo '<a class="close">�</a>';
				echo '<a class="play-pause"></a>';
				echo '<ol class="indicator"></ol>';
			echo '</div>';

			//File uploading scripts
			echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>';
			echo '<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>';
			echo '<script src="js/vendor/jquery.ui.widget.js"></script>';
			echo '<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>';
			echo '<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>';
			//echo '<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>';
			echo '<script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>';
			echo '<script src="js/jquery.iframe-transport.js"></script>';
			echo '<script src="js/jquery.fileupload.js"></script>';
			echo '<script src="js/jquery.fileupload-process.js"></script>';
			echo '<script src="js/jquery.fileupload-image.js"></script>';
			echo '<script src="js/jquery.fileupload-audio.js"></script>';
			echo '<script src="js/jquery.fileupload-video.js"></script>';
			echo '<script src="js/jquery.fileupload-validate.js"></script>';
			echo '<script src="js/jquery.fileupload-angular.js"></script>';
			echo '<script src="js/app.js"></script>';

		require ('core/layout/footerbackend.php');

	echo "</div>";
	echo "</body>";
echo "</html>";

?>
