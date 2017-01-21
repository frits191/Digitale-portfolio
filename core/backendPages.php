<?php

class Pages
{
	function home() {
		//HOME
		echo "<div class='col-lg-10'>";
			echo "Welkom " . $_SESSION["name"] . ".<br>";
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
		header('refresh:2;url=backend.php?p=home');
	}

	function info() {

	}

	function cijfers() {

	}

	function projecten() {
		global $functions;

		if (isset($_GET["project"])) {
			$project = htmlspecialchars($_GET["project"]);
			if (is_int($project !== true)) {
				header('Location: backend.php?p=home');
				exit();
			} 
			$SQLString = "SELECT portfolio_id FROM project WHERE id = " . $project;
			$QueryResult = $functions->executeQuery($SQLString);
			$row = mysqli_fetch_all($QueryResult);
			if (mysqli_num_rows($QueryResult) > 0) {
				if ($row[0][0] != $_SESSION["portfolio_id"]) {
					header('Location: backend.php?p=home');
					exit();
				}
			} else {
				header('Location: backend.php?p=home');
				exit();
			}
		}

		if (isset($_POST["submitFolder"])) {	
			$functions->createFolder();
		}

		if (isset($_POST["submitFile"])) {
			$functions->uploadFile();
		}	

		//TODO: breadcrumbs
		echo "Maurice portfolio->projecten->SLB Folder<br>";

		if (isset($_GET["project"])) {
			echo "<a href='backend.php?p=projecten'><-- back</a>";
		}

		echo "<div class='files'>";
			if (isset($_GET["project"])) {
				$functions->getfiles();
			} else {
				$functions->getFolders();
			}			
		echo "</div>";

		//Folder creation modal
		echo '<div class="modal fade" id="AddFolder" role="dialog">';
            echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                        echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                        echo '<h4 class="modal-title">Maak een nieuw map aan.</h4>';
                    echo'</div>';
                    echo '<div class="modal-body">';
                        echo '<p>';
                            echo "<form action='backend.php?p=projecten' method='POST'>";
								echo "<input type='text' name='folderName' placeholder='Title' required><br><br>";
								echo "<input type='text' name='folderDesc' placeholder='Description'>";
                        echo "</p>";
                    echo "</div>";
                    echo '<div class="modal-footer">';
                        echo "<input type='submit' value='Verzend' name='submitFolder'></form>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";

		//File uploading modal
		echo '<div class="modal fade" id="AddFile" role="dialog">';
            echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                        echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                        echo '<h4 class="modal-title">Voeg een bestand toe.</h4>';
                    echo'</div>';
                    echo '<div class="modal-body">';
                        echo '<p>';
                            echo "<form action='backend.php?p=projecten&project=" . $project . "' method='POST' enctype='multipart/form-data'>";
								echo "<input type='file' name='file' required><br>";
								echo "<input type='text' name='fileTitle' placeholder='Name' required><br><br>";
								echo "<input type='text' name='fileDesc' placeholder='Description'>";
                        echo "</p>";
                    echo "</div>";
                    echo '<div class="modal-footer">';
                        echo "<input type='submit' value='Verzend' name='submitFile'></form>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";

		//Edit Folder modal
		echo '<div class="modal fade" id="EditFolder" role="dialog">';
            echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                        echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                        echo '<h4 class="modal-title">Edit</h4>';
                    echo'</div>';
                    echo '<div class="modal-body">';
                        echo '<p>';
                            echo "<form action='backend.php?p=projecten' method='POST'>";
								echo "<input type='text' name='fileTitle' placeholder='Name' required><br><br>";
								echo "<input type='text' name='fileDesc' placeholder='Description'>";
                        echo "</p>";
                    echo "</div>";
                    echo '<div class="modal-footer">';
                        echo "<input type='submit' value='Verzend' name='submitFile'></form>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";

		//Delete Folder modal
		echo '<div class="modal fade" id="DeleteFolder" role="dialog">';
            echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                        echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                        echo '<h4 class="modal-title">Voeg een bestand toe.</h4>';
                    echo'</div>';
                    echo '<div class="modal-body">';
                        echo '<p>';
                            echo "<form action='backend.php?p=projecten' method='POST'>";
								echo "<input type='text' name='fileTitle' placeholder='Name' required><br><br>";
								echo "<input type='text' name='fileDesc' placeholder='Description'>";
                        echo "</p>";
                    echo "</div>";
                    echo '<div class="modal-footer">';
                        echo "<input type='submit' value='Verzend' name='submitFile'></form>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
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

		echo "<table class='table table-hover'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>ID</th>";
					echo "<th>E-mail</th>";
					echo "<th>Role</th>";
					echo "<th>First Name</th>";
					echo "<th>Last Name</th>";
					echo "<th>Phone number</th>";
					echo "<th>Following</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
				$SQLString = "SELECT * FROM user ORDER BY id ASC";
				$QueryResult = $functions->executeQuery($SQLString);
				$row = mysqli_fetch_all($QueryResult);

				foreach ($row as $index) {
					echo "<tr>";
						echo "<td>" . $index[0] . "</td>";
						echo "<td>" . $index[1] . "</td>";
						echo "<td>" . $index[3] . "</td>";
						echo "<td>" . $index[4] . "</td>";
						echo "<td>" . $index[5] . "</td>";
						echo "<td>" . $index[6] . "</td>";
						echo "<td>" . $index[7] . "</td>";	
					echo "</tr>";
				}
			echo "</tbody>";
		echo "</table>";

		echo "<button type='button' class='btn btn-default' id='addUserButton'>Voeg gebruiker toe.</button>";
			echo "<div id='addUser'>";
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
			echo "</div>";

		if (isset($_POST["submitRegister"])) {
			echo "<br>";
			$functions->register();
		}
	}
}