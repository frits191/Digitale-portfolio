<?php

session_start();
require('core/connect.php');
require('core/functions.php');
require('core/backendPages.php');

$functions = new functions;
$pages = new Pages;

$p = $_GET["p"];

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

echo "<!DOCTYPE html>";
	echo "<html>";
		echo "<head>";
			echo "<title>Digitaal Portfolio</title>";
			echo '<meta charset="utf-8" />';
			echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
			echo '<link rel="stylesheet" type="text/css" href="../Digitale-portfolio/css/backend.css">';
			echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/simplex/bootstrap.min.css">';
			echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>';
			echo '<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>';
		echo "</head>";
		echo "<body>";
		echo "<div id='wrapper'>";

			require ('core/layout/headerbackend.php');

			//Menu
			echo "<div id='body' class='container-fluid'>";
				echo "<div class='row'>";
					echo "<div class='col-lg-2'>";
						echo "<div id='searchbar'>";
							echo "<form id='search' method='POST' action='#'>";
								echo "<input type='text' class='searchinput' name='search' size='10' maxlength='120' placeholder='Search'><input type='submit' value='>' class='searchbutton' title='Search'>";
							echo "</form>";
						echo "</div>";
						echo "<div id='content'>";
							echo "<table class='table table-hover'>";
								echo "<tr><th>Menu</th></tr>";
								echo "<tr><td><a href='backend.php?p=cijfers'>Cijfers</a></td></tr>";
								echo "<tr><td><a href='backend.php?p=projecten'>Projecten</a></td></tr>";
								echo "<tr><td><a href='backend.php?p=stages'>Stages</a></td></tr>";
								echo "<tr><td><a href='backend.php?p=portfolio'>Openbaar portfolio</a></td></tr>";
								echo "<tr><td><a href='backend.php?p=opmerkingen'>Opmerkingen</a></td></tr>";
								if (isset($_SESSION["role"])) {
									if ($role == "admin") {
										echo "<tr><td><a href='backend.php?p=gebruikers'>Gebruikers beheren</a></td></tr>";
									}
								}
							echo "</table>";
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

			require ('core/layout/footerbackend.php');

		echo "</div>";
		echo "</body>";
	echo "</html>";

?>