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
		//Logout
		session_unset();
		session_destroy();

		echo "U bent uitgelogd.";
	}

	function info() {

	}

	function cijfers() {

	}

	function projecten() {
		global $functions;

		if (isset($_POST["submitFolder"])) {	
			$functions->createFolder();
		}

		
		echo "<div class='files'>";

		$functions->getFolders();
		
		?>
		 <!-- Folder template
		        
			<div class='clearfix'></div>
            <div class='fileblock'>
                <div class='file'>
                    <button type='button' class='btn-link'>
                        <span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span>
                    </button>
                </div>
                <div class='filemenu'>
                    <div class='btn-group'>
                        <button type='button' class='btn btn-link'>
                            <span class='glyphicon glyphicon-open' aria-hidden='true' title='Upload'></span>
                        </button>
                        <button type='button' class='btn btn-link'>
                            <span class='glyphicon glyphicon-pencil' aria-hidden='true' title='Edit'></span>
                        </button>
                        <button type='button' class='btn btn-link'>
                            <span class='glyphicon glyphicon-trash' aria-hidden='true' title='Delete'></span>
                        </button>
                    </div>
                </div>
		-->

		<!-- File template
       
			<div class='clearfix'></div>
            <div class='fileblock'>
                <div class='file'>
                    <button type='button' class='btn-link'>
                        <span class='glyphicon glyphicon-file' aria-hidden='true'></span>
                    </button>
                </div>
                <div class='filemenu'>
                    <div class='btn-group'>
                        <button type='button' class='btn btn-link'>
                            <span class='glyphicon glyphicon-open' aria-hidden='true' title='Upload'></span>
                        </button>
                        <button type='button' class='btn btn-link'>
                            <span class='glyphicon glyphicon-pencil' aria-hidden='true' title='Edit'></span>
                        </button>
                        <button type='button' class='btn btn-link'>
                            <span class='glyphicon glyphicon-trash' aria-hidden='true' title='Delete'></span>
                        </button>
                    </div>
                </div>
		-->

			</div>

			<!-- File uploading -->
			
			<div class="modal fade" id="AddFolder" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Maak een nieuw map aan.</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                <form action='backend.php?p=projecten' method='POST'>
                                <input type='text' name='folderName' placeholder='Title' required><br><br>
								<input type='text' name='folderDesc' placeholder='Description'>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <input type='submit' value='Verzend' name='submitFolder'></form>
                        </div>
                    </div>
                </div>
            </div>
			<?php
			echo "</div>";
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