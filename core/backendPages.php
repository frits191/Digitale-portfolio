<?php

class Pages
{
	function home() {
		//HOME
		echo "<div class='col-lg-10'>";
			//Navigation at the bottom, kept here for future reference

			/* echo "<nav aria-label='Page navigation'>";
				echo "<ul class='pagination'>";
					echo "<li>";
						echo "<a href='#' title='Previous' aria-label='Previous'>";
							echo "<span aria-hidden='true'>&laquo;</span>";
						echo "</a>";
					echo "</li>";
					echo "<li>";
						echo "<a href='#'>1</a>";
					echo "</li>";
					echo "<li>";
						echo "<a href='#'>2</a>";
					echo "</li>";
					echo "<li>";
						echo "<a href='#'>3</a>";
					echo "</li>";
					echo "<li>";
						echo "<a href='#' title='Next' aria-label='Next'>";
							echo "<span aria-hidden='true'>&raquo;</span>";
						echo "</a>";
					echo "</li>";
				echo "</ul>";
			echo "</nav>"; */
			echo "Welcome " . $_SESSION["name"] . ".<br>";
			echo "Kies links in het menu een optie om verder te gaan.";
		echo "</div>";
	}

	function login() {
		//LOGIN
		global $functions;

		echo "<form method='post' action='#'>";
			echo "Login<br><br>";
			echo "E-mail: <input type='e-mail' name='e-mail' required><br><br>";
			echo "Password: <input type='password' name='password' required><br><br>";
			echo "<input type='submit' name='submitLogin'><br><br>";
		echo "</form>";
		if (isset($_POST["submitLogin"])) {
			$functions->login();
		}
	}

	function logout() {
		//LOGOUT
		//$_SESSION['loggedIn'] = false;
		//$_SESSION["e-mail"] = "";
		//$_SESSION['role'] = "";
		session_unset();
		session_destroy();
		
		echo "U bent uitgelogd.";
	}

	function cijfers() {

	}

	function projecten() {

	}

	function stages() {

	}

	function portfolio() {

	}

	function opmerkingen() {

	}

	function gebruikers() {
		global $functions;

		echo "Voeg een gebruiker toe: <br><br>";
		echo "<form action='#' method='post'>";
			echo "<input type='email' name='email' placeholder='E-mail' required><br><br>";
			echo "<input type='password' name='password' placeholder='Password' required><br><br>";
			echo "<input type='text' name='fname' placeholder='First name' required><br><br>";
			echo "<input type='text' name='lname' placeholder='Last name' required><br><br>";
			echo "<input type='number' name='phone' maxlength='16' placeholder='Phone Number'><br><br>";
			echo "Role<br><select name='role'><br><br>";
				echo "<option value='student'>Student</option>";
				echo "<option value='docent'>Docent</option>";
				echo "<option value='SLB'>SLB'er</option>";
				echo "<option value='admin'>Administrator</option>";
			echo "</select><br><br>";
			echo "<input type='submit' name='submitRegister'>";
		echo "</form>";

		if (isset($_POST["submitRegister"])) {
			echo "<br>";
			$functions->register();
		}
	}
}