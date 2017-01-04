<?php
$db_name = "digitaal_portfolio";
$DBconnect = mysqli_connect("localhost", "INF1H", "stenden1");
//connect to the database
if ($DBconnect === false) {
	echo "<p>Unable to connect to the database server.</p>"
	. "<p>Error code " . mysqli_connect_errno() . ": "
	. mysqli_connect_error() . "</p>";
} else {
	//select the database
	$db = mysqli_select_db($DBconnect, $db_name);
	if ($db === false) {
		echo "<p>Unable to connect to the database server.</p>"
		. "<p>Error code " . mysqli_errno() . ": "
		. mysqli_error() . "</p>";
		return;
	}
}
?>
