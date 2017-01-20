<?php
    session_start();

	require('core/functions.php');
	require('core/connect.php');

	$functions = new functions;

    if(isset($_GET['page']))
    {
		$page = $_GET['page'];
    } else {
		$page = "home";
    }

	echo "<!DOCTYPE html>";
	echo "<html>";
		echo "<head>";
			echo "<title>Digitaal Portfolio</title>";
			echo '<meta charset="utf-8" />';
			echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
	        echo '<link rel="stylesheet" type="text/css" href="menu.css">';
			echo '<link rel="stylesheet" type="text/css" href="css/header.css">';
			echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/simplex/bootstrap.min.css">';
			echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>';
			echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>';
		echo "</head>";
	echo "<div id='container'>";
		echo "<div id='content'>";
			if (file_exists('core/pages/$page.php'))
			{
				require('core/pages/$page.php');
			} else {
				echo "<img class='img-responsive center-block' src='img/404.png' alt='page not found'>";
			}
		echo "</div>";
	echo "</div>";
?>