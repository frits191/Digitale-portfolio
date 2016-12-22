<?php
$db_name = "Digitaal_Portfolio";
$DBconnect = mysqli_connect("192.168.178.25", "INF1H", "stenden1", 'Digitaal_Portfolio');
//connect to the database
if ($DBconnect === FALSE) {
	echo "<p>Unable to connect to the database server.</p>"
	. "<p>Error code " . mysqli_errno() . ": "
	. mysqli_error() . "</p>";
} else {
	//select the database
	$db = mysqli_select_db($DBconnect, $db_name);
	if ($db === FALSE) {
		echo "<p>Unable to connect to the database server.</p>"
		. "<p>Error code " . mysqli_errno() . ": "
		. mysqli_error() . "</p>";
		mysqli_close($DBconnect);
		$return;
	}
}
?>
