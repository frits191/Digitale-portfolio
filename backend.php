<?php

echo "<div id='container'>";
	echo "<div id='content'>";
		require ('core/layout/headerbackend.php');

		if (isset($_SESSION["id"])) {
			if (isset($SESSION["e-mail"])) {
				$ID = $_SESSION["id"];
				$email = $_SESSION["e-mail"];
			} else {
				//header ('Location: index.php?page=home');
				//exit();
			}
		} else {
			//header ('Location: index.php?page=home');
			//exit();
		}
		


	echo "</div>";
echo "</div>";
require ('core/layout/footerbackend.php');

?>