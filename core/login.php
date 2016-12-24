<?php
//Simple login system, has to be expanded on obviously

session_start();

require('connect.php');
require('functions.php');

$functions = new functions;

echo "<form method='post' action='#'>";
	echo "Login<br><br>";
	echo "E-mail: <input type='e-mail' name='e-mail' required><br><br>";
	echo "Password: : <input type='password' name='password' required><br><br>";
	echo "<input type='submit' name='submitLogin'><br><br>";
echo "</form>";

if (isset($_SESSION["loggedIn"])) {
	if ($_SESSION["loggedIn"] == true) {
		header ('Location: ../backend.php');
	}
}

if (isset($_POST["submitLogin"])) {
	$functions->login();
}
?>