<?php
session_start();
require('core/connect.php');
require('core/functions.php');

$functions = new functions;

if (isset($_SESSION["loggedIn"])) {
	if ($_SESSION["loggedIn"] == false) {
		//Should be changed to underlying login screen and not an external page
		header('Location: core/login.php');
		exit();
	}
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
		echo "<div id='wrapper'>";
			require ('core/layout/headerbackend.php');
			//temp page for now, will become require to specific get page
			?>
			<div id='body' class='container-fluid'>
            <div class='row'>
                <div class='col-lg-2'>
                    <div id='searchbar'>
                        <form id='search' method='POST' action='#'>
                            <input type='text' class='searchinput' name='search' size='10' maxlength='120' placeholder='Search'><input type='submit' value='>' class='searchbutton' title='Search'>
                        </form>
                    </div>
                    <div id='content'>
                        <table class='table table-hover'>
                            <tr><th>Menu</th></tr>
                            <tr><td><a href='cijfers.php'>Cijfers</a></td></tr>
                            <tr><td><a href='projecten.php'>Projecten</a></td></tr>
                            <tr><td><a href='stages.php'>Stages</a></td></tr>
                            <tr><td><a href='portfolio.php'>Openbaar portfolio</a></td></tr>
                            <tr><td><a href='opmerkingen.php'>Opmerkingen</a></td></tr>
                        </table>
                    </div>
                </div>
                <div class='col-lg-10'>
                    <nav aria-label='Page navigation'>
                        <ul class='pagination'>
                            <li>
                                <a href='#' title='Previous' aria-label='Previous'>
                                    <span aria-hidden='true'>&laquo;</span>
                                </a>
                            </li>
                            <li><a href='#'>1</a></li>
                            <li><a href='#'>2</a></li>
                            <li><a href='#'>3</a></li>
                            <li>
                                <a href='#' title='Next' aria-label='Next'>
                                    <span aria-hidden='true'>&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
			<?php
			require ('core/layout/footerbackend.php');
		echo "</div>";
	echo "</html>";

?>