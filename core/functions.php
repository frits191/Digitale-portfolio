<?php

class functions {
    function executeQuery($SQLstring) {
		global $DBconnect;

        $QueryResult = mysqli_query($DBconnect, $SQLstring);
        if ($QueryResult === false) {
            echo "Error excecuting query.<br>" . mysqli_errno($DBconnect) . ": " . mysqli_error($DBconnect);
            return;
        }
        return $QueryResult;
    }

	function login() {
		//email en password komen vanuit het ingevulde formulier
		$email = htmlspecialchars($_POST["e-mail"]);
		$password = htmlspecialchars($_POST["password"]);

		if (!empty($email) && !empty($password)) {
			//check of de velden zijn ingevoerd
			$SQLString = "SELECT * FROM user WHERE `e-mail` = '". $email . "'";
			$QueryResult = $this->executeQuery($SQLString);
			$row = mysqli_fetch_assoc($QueryResult);

			//controleer of het wachtwoord end e-mail hetzelfde zijn
			if ($email === $row["e-mail"]){
				if (password_verify($password, $row["password"])) {
					$_SESSION['loggedIn'] = true;
					$_SESSION["e-mail"] = $email;
					$_SESSION['role'] = $row["role"];
					$_SESSION['name'] = $row["firstName"] . " " . $row["lastName"];
					$_SESSION['id'] = $row["id"];

					if ($_SESSION['role'] == "student") {
						$SQLString = "SELECT id FROM portfolio WHERE owner_id = '" . $_SESSION["id"] . "'";
						$QueryResult = $this->executeQuery($SQLString);
						$row = mysqli_fetch_assoc($QueryResult);		
						$_SESSION['portfolio_id'] = $row["id"];
					}

					header ('Location: backend.php?p=home');
					exit();
				} else {
					echo "<br>Fout wachtwoord/email combinatie";
				}
			} else {
				echo "<br>Fout wachtwoord/email combinatie";
			}
		} else {
			echo "<br>One or more fields are empty, please try again.";
		}
	}

	function register() {
		$email = htmlspecialchars($_POST["email"]);
		$email = str_replace("'", "&#39;", $email);

		$password = htmlspecialchars($_POST["password"]);
		$password = str_replace("'", "&#39;", $password);
				
		$fname = htmlspecialchars($_POST["fname"]);
		$fname = str_replace("'", "&#39;", $fname);

		$lname = htmlspecialchars($_POST["lname"]);
		$lname = str_replace("'", "&#39;", $lname);

		$phone = htmlspecialchars($_POST["phone"]);	
		$phone = str_replace("'", "&#39;", $phone);

		$role = htmlspecialchars($_POST["role"]);
		$role = str_replace("'", "&#39;", $role);

		if (empty($phone)) {
			$phone = "";
		} 

		$SQLString = "SELECT `e-mail` FROM user";
		$QueryResult = $this->executeQuery($SQLString);
		$row = mysqli_fetch_all($QueryResult);

		foreach ($row as $index => $value) {
			if ($email == $value[0]) {
				echo "An account with this e-mail adress already exists!";
				return;
			}
		}

		if (!empty($email) && !empty($password) && !empty($fname) && !empty($lname) && !empty($role)) {
			$hash = password_hash($password, PASSWORD_DEFAULT);
			$SQLString = 'INSERT INTO user (`e-mail`, `password`, `role`, `firstName`, `lastName`, `phone`) VALUES
				("' . $email . '", "' . $hash . '", "' . $role . '", "' . $fname . '", "' . $lname . '", "' . $phone . '")';
			$this->executeQuery($SQLString);			

			if ($role == "student") {
				$SQLString = "SELECT id FROM user WHERE `e-mail` = '" . $email . "'";
				$QueryResult = $this->executeQuery($SQLString);
				$row = mysqli_fetch_assoc($QueryResult);
				$id = $row["id"];

				$SQLString = 'INSERT INTO portfolio (`title`, `owner_id`) VALUES ("Portfolio ' . $fname . '", "' . $id . '")';
				$this->executeQuery($SQLString);

				$SQLString = "SELECT id FROM portfolio WHERE owner_id = " . $id;
				$QueryResult = $this->executeQuery($SQLString);
				$row = mysqli_fetch_assoc($QueryResult);

				$folderPath = "front-end/res/portfolios/" . $row["id"] . "/";
				mkdir($folderPath);
			}
		} 
	}

	function createFolder() {
		global $DBconnect;

		if (!empty($_POST["folderName"])) {
			$title = htmlspecialchars($_POST["folderName"]);
			$title = str_replace("'", "&#39;", $title);
			if (empty($_POST["folderDesc"])) {
				$desc = "";
			} else {
				$desc = htmlspecialchars($_POST["folderDesc"]);
				$desc = str_replace("'", "&#39;", $desc);
			}
			$SQLString = 'INSERT INTO project (`title`, `description`, `portfolio_id`) VALUES ("' . $title . '", "' . $desc . '", "' . $_SESSION["portfolio_id"] . '")';
			$this->executeQuery($SQLString);
			$last_id = mysqli_insert_id($DBconnect);

			$SQLString = 'INSERT INTO rating (`project_id`) VALUES ("' . $last_id . '")';
			$this->executeQuery($SQLString);

			$folderPath = "front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $last_id;
			mkdir($folderPath);
		}
	}

	function uploadFile() {
		global $project;

        $target_dir = "front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $project . "/";
		$dir = "server/php/files/";
		$scandir = scandir($dir);		

		$key1 = array_search("thumbnail", $scandir);
		$key2 = array_search(".gitignore", $scandir);
		$key3 = array_search(".htaccess", $scandir);
		unset($scandir[0], $scandir[1], $scandir[$key1], $scandir[$key2], $scandir[$key3]);
		$scandir = array_values($scandir);

		for ($i = 0; $i < count($scandir); $i++) {
			$key = array_search($scandir[$i], $scandir);
			$target_file = $dir . $scandir[$key];
			$FileArray = explode(".", $scandir[$i]);
			$title = $FileArray[0];
			$imageFileType = "." . $FileArray[1];

			rename($target_file, $target_dir . $title . $imageFileType);
			array_map('unlink', glob("$dir/thumbnail/*.*"));

			$SQLString = 'INSERT INTO file (`title`, `type`, `description`, `project_id`) VALUES ("' . $title . '", "' . $imageFileType . '", "", "' . $project . '")';
			$this->executeQuery($SQLString);
		}	
	}

	function editFolder() {
		$title = htmlspecialchars($_POST["folderName"]);
		$title = str_replace("'", "&#39;", $title);

		$desc = htmlspecialchars($_POST["folderDesc"]);
		$desc = str_replace("'", "&#39;", $desc);

		$id = htmlspecialchars($_POST["folderID"]);
		$id = str_replace("'", "&#39;", $id);

		if (empty($desc) == true) {
			$desc = "";
		}

		if (!empty($title) && !empty($id)) {
			$SQLString = 'UPDATE project SET title = "' . $title . '", description = "' . $desc . '" WHERE id = ' . $id . ' AND portfolio_id = ' . $_SESSION["portfolio_id"];
			$this->executeQuery($SQLString);
		}
	}

	function deleteFolder() {
		$id = htmlspecialchars($_POST["folderToDelete"]);
		$id = str_replace("'", "&#39;", $id);

		if (!empty($id)) {
			$SQLString = "SELECT id FROM project WHERE id = " . $id . " AND portfolio_id = " . $_SESSION["portfolio_id"];
			$QueryResult = $this->executeQuery($SQLString);

			if (mysqli_num_rows($QueryResult) > 0) {
				$SQLString = "DELETE FROM file WHERE project_id = " . $id;
				$QueryResult = $this->executeQuery($SQLString);
				$SQLString = "DELETE FROM rating WHERE project_id = " . $id;
				$QueryResult = $this->executeQuery($SQLString);
				$SQLString = "DELETE FROM project WHERE id = " . $id;
				$QueryResult = $this->executeQuery($SQLString);			

				$dirname = "front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $id;
				array_map('unlink', glob("$dirname/*.*"));
				rmdir($dirname);
			}
		}
	}

	function editFile() {
		global $project;

		$title = htmlspecialchars($_POST["fileName"]);
		$title = str_replace("'", "&#39;", $title);

		$desc = htmlspecialchars($_POST["fileDesc"]);
		$desc = str_replace("'", "&#39;", $desc);

		$id = htmlspecialchars($_POST["fileID"]);
		$id = str_replace("'", "&#39;", $id);

		$projectID = htmlspecialchars($_POST["projectID"]);
		$projectID = str_replace("'", "&#39;", $projectID);

		if (empty($desc) == true) {
			$desc = "";
		}

		if (!empty($title) && !empty($id) && !empty($projectID)) {
			$SQLString = "SELECT title, type FROM file WHERE id = " . $id;
			$QueryResult = $this->executeQuery($SQLString);
			$row = mysqli_fetch_assoc($QueryResult);
			$type = $row["type"];
			$oldDir = "front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $project . "/" . $row["title"] . $type;

			$SQLString = 'UPDATE file SET title = "' . $title . '", description = "' . $desc . '" WHERE id = ' . $id . ' AND project_id = ' . $projectID;
			$this->executeQuery($SQLString);

			$newDir = "front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $project . "/" . $title . $type;	
			rename($oldDir, $newDir);
		}
	}

	function deleteFile() {
		$id = htmlspecialchars($_POST["fileToDelete"]);
		$id = str_replace("'", "&#39;", $id);

		$projectID = htmlspecialchars($_POST["projectID"]);
		$projectID = str_replace("'", "&#39;", $projectID);

		if (!empty($id) && !empty($projectID)) {
			$SQLString = "SELECT id, title, type FROM file WHERE id = " . $id . " AND project_id = " . $projectID;
			$QueryResult = $this->executeQuery($SQLString);
			$row = mysqli_fetch_assoc($QueryResult);

			if (mysqli_num_rows($QueryResult) > 0) {
				$title = $row["title"];
				$type = $row["type"];

				$SQLString = "DELETE FROM file WHERE id = " . $id;
				$QueryResult = $this->executeQuery($SQLString);		

				$dirname = "front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $projectID . "/" . $title . $type;
				unlink($dirname);
			}
		}
	}

	function getFolders() {
		$SQLString = "SELECT id, title, description FROM project WHERE portfolio_id = '" . $_SESSION["portfolio_id"] . "' ORDER BY id DESC";
		$QueryResult = $this->executeQuery($SQLString);
		$row = mysqli_fetch_all($QueryResult);

		for ($i = count($row) - 1;$i >= 0;$i--) {
            echo "<div class='fileblock'>";
                echo "<div class='file' onclick='location.href = \"backend.php?p=projecten&project=" . $row[$i][0] . "\";'>";
                    echo "<button type='button' class='btn-link'>";
                        echo "<span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span>";
                    echo "</button>";
                echo "</div>";
                echo "<div class='filemenu'>";
					echo "<div class='FolderTitle'>" . $row[$i][1] . "</div>";
                    echo "<div class='btn-group'>";
                        echo "<button type='button' class='btn btn-link'>";
                            echo "<span class='glyphicon glyphicon-pencil' aria-hidden='true' title='Edit' data-toggle='modal' data-target='#EditFolder" . $row[$i][0] . "'></span>";
                        echo "</button>";
                        echo "<button type='button' class='btn btn-link'>";
                            echo "<span class='glyphicon glyphicon-trash' aria-hidden='true' title='Delete' data-toggle='modal' data-target='#DeleteFolder" . $row[$i][0] . "'></span>";
                        echo "</button>";
                    echo "</div>";
                echo "</div>";	
			echo "</div>";

			//Edit Folder modal
			echo '<div class="modal fade" id="EditFolder' . $row[$i][0] . '" role="dialog">';
				echo '<div class="modal-dialog">';
					echo '<div class="modal-content">';
						echo '<div class="modal-header">';
							echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
							echo '<h4 class="modal-title">Bewerk deze map.</h4>';
						echo '</div>';
						echo '<div class="modal-body">';
							echo '<p>';
								echo "<form action='backend.php?p=projecten' method='POST'>";
									echo "<input type='hidden' name='folderID' value='" . $row[$i][0] . "'>";
									echo "<input type='text' name='folderName' value='" . $row[$i][1] . "' placeholder='Name' required><br><br>";
									echo "<input type='text' name='folderDesc' value='" . $row[$i][2] . "' placeholder='Description'>";
							echo "</p>";
						echo "</div>";
						echo '<div class="modal-footer">';
							echo "<input type='submit' value='Bewerk' name='folderEdit'></form>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";

			//Delete Folder modal
			echo '<div class="modal fade" id="DeleteFolder' . $row[$i][0] . '" role="dialog">';
				echo '<div class="modal-dialog">';
					echo '<div class="modal-content">';
						echo '<div class="modal-header">';
							echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
							echo '<h4 class="modal-title">Weet u zeker dat je de map: \'' . $row[$i][1] . '\' wilt verwijderen?<br>Hou er rekening mee dat alle onderliggende bestanden ook worden verwijdert.</h4>';
						echo'</div>';
						echo '<div class="modal-body">';
							echo '<p>';
								echo "<form action='backend.php?p=projecten' method='POST'>";
									echo "<input type='hidden' name='folderToDelete' value='" . $row[$i][0] . "'><br><br>";
									echo "<input type='submit' value='Verwijder' name='folderDelete'></form>";
							echo "</p>";
						echo "</div>";
						echo '<div class="modal-footer">';						
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
		echo "<nav class='Pnav' aria-label='Page navigation'>";
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
					echo "<a href='#' title='Next' aria-label='Next'>";
						echo "<span aria-hidden='true'>&raquo;</span>";
					echo "</a>";
				echo "</li>";
			echo "</ul>";
		echo "</nav>"; 
	}

	function getFiles() {
		global $project;

		$SQLString = "SELECT * FROM file WHERE project_id = " . $project;
		$QueryResult = $this->executeQuery($SQLString);
		$row = mysqli_fetch_all($QueryResult);

		for ($i = count($row) - 1;$i >= 0;$i--) {
            echo "<div class='fileblock'>";
                echo "<div class='file' onclick='window.open(\"front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $project . "/" . $row[$i][1] . $row[$i][2] . "\")'>";
                    echo "<button type='button' class='btn-link'>";
                        echo "<span class='glyphicon glyphicon-file' aria-hidden='true'></span>";
                    echo "</button>";
                echo "</div>";
                echo "<div class='filemenu'>";
					echo "<div class='FolderTitle'>" . $row[$i][1] . $row[$i][2] . "</div>";
                    echo "<div class='btn-group'>";
						echo "<button type='button' class='btn btn-link'>";
							echo "<span class='glyphicon glyphicon-open' aria-hidden='true' title='Download' onclick='window.open(\"front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $project . "/" . $row[$i][1] . $row[$i][2] . "\")'></span>";
						echo "</button>";
                        echo "<button type='button' class='btn btn-link'>";
                            echo "<span class='glyphicon glyphicon-pencil' aria-hidden='true' title='Edit' data-toggle='modal' data-target='#EditFile" . $row[$i][0] . "'></span>";
                        echo "</button>";
                        echo "<button type='button' class='btn btn-link'>";
                            echo "<span class='glyphicon glyphicon-trash' aria-hidden='true' title='Delete' data-toggle='modal' data-target='#DeleteFile" . $row[$i][0] . "'></span>";
                        echo "</button>";
                    echo "</div>";
                echo "</div>";	
			echo "</div>";

			//Edit File modal
			echo '<div class="modal fade" id="EditFile' . $row[$i][0] . '" role="dialog">';
				echo '<div class="modal-dialog">';
					echo '<div class="modal-content">';
						echo '<div class="modal-header">';
							echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
							echo '<h4 class="modal-title">Bewerk dit bestand.</h4>';
						echo'</div>';
						echo '<div class="modal-body">';
							echo '<p>';
								echo "<form action='backend.php?p=projecten&project=" . $project . "' method='POST'>";
									echo "<input type='hidden' name='fileID' value='" . $row[$i][0] . "'>";
									echo "<input type='hidden' name='projectID' value='" . $project . "'>";
									echo "<input type='text' name='fileName' value='" . $row[$i][1] . "' placeholder='Name' required><br><br>";
									echo "<input type='text' name='fileDesc' value='" . $row[$i][3] . "' placeholder='Description'>";
							echo "</p>";
						echo "</div>";
						echo '<div class="modal-footer">';
							echo "<input type='submit' value='Bewerk' name='fileEdit'></form>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";

			//Delete File modal
			echo '<div class="modal fade" id="DeleteFile' . $row[$i][0] . '" role="dialog">';
				echo '<div class="modal-dialog">';
					echo '<div class="modal-content">';
						echo '<div class="modal-header">';
							echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
							echo '<h4 class="modal-title">Weet u zeker dat je het bestand: \'' . $row[$i][1] . '\' wilt verwijderen?</h4>';
						echo'</div>';
						echo '<div class="modal-body">';
							echo '<p>';
								echo "<form action='backend.php?p=projecten&project=" . $project . "' method='POST'>";
									echo "<input type='hidden' name='fileToDelete' value='" . $row[$i][0] . "'><br><br>";
									echo "<input type='hidden' name='projectID' value='" . $project . "'>";
									echo "<input type='submit' value='Verwijder' name='fileDelete'></form>";
							echo "</p>";
						echo "</div>";
						echo '<div class="modal-footer">';						
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
		echo "<nav class='Pnav' aria-label='Page navigation'>";
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
					echo "<a href='#' title='Next' aria-label='Next'>";
						echo "<span aria-hidden='true'>&raquo;</span>";
					echo "</a>";
				echo "</li>";
			echo "</ul>";
		echo "</nav>";
	}

	function breadcrumbs() {
		//"Portfolio Maurice->projecten->SLB Folder<br>";
		$SQLString = "SELECT title FROM portfolio WHERE id = " . $_SESSION["portfolio_id"];
		$QueryResult = $this->executeQuery($SQLString);
		$row = mysqli_fetch_assoc($QueryResult);
		$portfolio = $row["title"];

		if (isset($_GET["project"])) {
			$projectID = htmlspecialchars($_GET["project"]);
			$projectID = str_replace("'", "&#39;", $projectID);

			if (is_int($projectID !== true)) {
				header('Location: backend.php?p=home');
				exit();
			}
			$SQLString = "SELECT title FROM project WHERE id = " . $projectID . " AND portfolio_id = " . $_SESSION["portfolio_id"];
			$QueryResult = $this->executeQuery($SQLString);
			if (mysqli_num_rows($QueryResult) == 0) {
				header('Location: backend.php?p=home');
				exit();
			}
			$row = mysqli_fetch_assoc($QueryResult);
			echo $portfolio . "-><a href='backend.php?p=projecten'>projecten</a>->" . $row["title"] . "<br>";
		} else {
			echo $portfolio . "->projecten<br>";
		}
	}
}
?>