<?php

echo "<div id='container'>";
	echo "<div id='content'>";
		require ('core/layout/headerbackend.php');

		if (isset($_SESSION["ID"])) {
			if (isset($SESSION["email"])) {
				$ID = $_SESSION["ID"];
				$email = $_SESSION["email"];
			} else {
				header ('Location: index.php?page=home');
				exit();
			}
		} else {
			header ('Location: index.php?page=home');
			exit();
		}



	echo "</div>";
echo "</div>";
require ('core/layout/footerbackend.php');

?>