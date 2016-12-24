<?php
session_start();
require('core/connect.php');
require('core/functions.php');

$functions = new functions;

if ($_SESSION["loggedIn"] == false) {
	header('Location: core/login.php');
}

echo "<div id='container'>";
	echo "<div id='content'>";
		require ('core/layout/headerbackend.php');
	echo "</div>";
echo "</div>";
require ('core/layout/footerbackend.php');

?>