<?php
    session_start();
	require('core/connect.php');
    if(isset($_GET['page']))
    {
		$page = $_GET['page'];
    } else {
		$page = "home";
    }

	//Check if logged in needs to be here

	if ($page !== "backend") {
		require ('core/layout/header.php');

		echo "<div id='container'>";
		echo "<div id='content'>";

		if (file_exists("core/pages/$page.php"))
		{
			require ("core/pages/$page.php");
		} else {
			echo "<img class='img-responsive center-block' src='img/404.png' alt='page not found'>";
		}

		echo "</div></div>";
		require ('core/layout/footer.php');
	} else {
		require ('core/layout/headerbackend.php');

		echo "<div id='container'>";
			echo "<div id='content'>";

				if (file_exists("core/pages/$page.php"))
				{
					require ("core/pages/$page.php");
				} 
			echo "</div>";
		echo "</div>";
		require ('core/layout/footerbackend.php');
	}
?>