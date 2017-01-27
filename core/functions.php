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

				$SQLString = 'INSERT INTO persoonlijkeinfo (`user_id`) VALUES ("' . $id . '")';
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
		$SQLString = "SELECT id, title, description, visible FROM project WHERE portfolio_id = '" . $_SESSION["portfolio_id"] . "' ORDER BY id DESC";
		$QueryResult = $this->executeQuery($SQLString);
		$row = mysqli_fetch_all($QueryResult);

		for ($i = count($row) - 1;$i >= 0;$i--) {
			echo "<form action='backend.php?p=projecten' method='post'>";
				echo "<div class='fileblock'>";
					echo "<div class='file' title='". $row[$i][2] . "' onclick='location.href = \"backend.php?p=projecten&project=" . $row[$i][0] . "\";'>";
						echo "<button type='button' class='btn-link'>";
							echo "<span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span>";
						echo "</button>";
					echo "</div>";
					echo "<div class='filemenu'>";
						echo "<div class='FolderTitle'>" . $row[$i][1] . "</div>";
						echo "<div class='btn-group'>";	
							echo "<button type='submit' name='submitVisible' class='btn btn-link'>";									
								if ($row[$i][3] == true) {
									echo "<input type='hidden' name='visible' value='false'>";									
									echo "<span class='glyphicon glyphicon-eye-open' title='Openbaar' aria-hidden='true'></span>";
								} else {
									echo "<input type='hidden' name='visible' value='true'>";									
									echo "<span class='glyphicon glyphicon-eye-close' title='Niet openbaar' aria-hidden='true'></span>";									
								} 		
							echo "</button>";				             
							echo "<button type='button' class='btn btn-link'>";
								echo "<span class='glyphicon glyphicon-pencil' aria-hidden='true' title='Edit' data-toggle='modal' data-target='#EditFolder" . $row[$i][0] . "'></span>";
							echo "</button>";
							echo "<button type='button' class='btn btn-link'>";
								echo "<span class='glyphicon glyphicon-trash' aria-hidden='true' title='Delete' data-toggle='modal' data-target='#DeleteFolder" . $row[$i][0] . "'></span>";
							echo "</button>";
						echo "</div>";
					echo "</div>";	
				echo "</div>";
			echo "</form>"; 

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
									echo "<div class='form-group'>";
										echo "<label for='EditFolderName'>Bestands Naam:</label><br>";
										echo "<input type='text' class='form-control' id='EditFolderName' name='folderName' value='" . $row[$i][1] . "' placeholder='Name' required>";
									echo "</div>";
									echo "<div class='form-group'>";
										echo "<label for='EditFolderDesc'>Beschrijving:</label><br>";
										echo "<textarea name='folderDesc' class='form-control' id='EditFolderDesc' maxlength='500' placeholder='Description'>" . $row[$i][2] . "</textarea>";
									echo "</div>";
									echo "</div>";
									echo '<div class="modal-footer">';
									echo "<button type='submit' class='btn btn-default' name='folderEdit'>Bewerk</button></form>";
							echo "</p>";
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
							echo '<h4 class="modal-title">Weet u zeker dat u de map: \'' . $row[$i][1] . '\' wilt verwijderen?`</h4>';
						echo'</div>';
						echo '<div class="modal-body">';
							echo '<p>';
								echo '<h5><strong>Waarschuwing:</strong> alle onderliggende bestanden worden ook verwijderd.</h5>';
								echo "<form action='backend.php?p=projecten' method='POST'>";
									echo "<input type='hidden' name='folderToDelete' value='" . $row[$i][0] . "'><br><br>";
							echo "</p>";
						echo "</div>";
						echo '<div class="modal-footer">';
							echo "<button type='submit' class='btn btn-default' name='folderDelete'>Verwijder</button></form>";
							echo "</p>";						
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
	}

	function getFiles() {
		global $project;

		$SQLString = "SELECT * FROM file WHERE project_id = " . $project;
		$QueryResult = $this->executeQuery($SQLString);
		$row = mysqli_fetch_all($QueryResult);

		for ($i = count($row) - 1;$i >= 0;$i--) {
            echo "<div class='fileblock'>";
                echo "<div class='file' title='". $row[$i][3] . "' onclick='window.open(\"front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $project . "/" . $row[$i][1] . $row[$i][2] . "\")'>";
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
									echo "<div class='input-group'>";
										echo "<label for='EditFileName'>Bestands Naam:</label><br>";
										echo "<input type='text' class='form-control' id='EditFileName' name='fileName' value='" . $row[$i][1] . "' placeholder='Name' required><br><br>";
									echo "</div>";
									echo "<div class='input-group'>";
										echo "<label for='EditFileDesc'>Beschrijving:</label><br>";
										echo "<input type='text' class='form-control' id='EditFileDesc' name='fileDesc' value='" . $row[$i][3] . "' placeholder='Description'>";
									echo "</div>";
							echo "</p>";
						echo "</div>";
						echo '<div class="modal-footer">';
							echo "<button type='submit' class='btn btn-default' name='fileEdit'>Bewerken</button></form>";
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
							echo '<h4 class="modal-title">Weet u zeker dat u het bestand: \'' . $row[$i][1] . $row[$i][2] . '\' wilt verwijderen?</h4>';
						echo'</div>';
						echo '<div class="modal-body">';
							echo '<p>';
								echo "<form action='backend.php?p=projecten&project=" . $project . "' method='POST'>";
									echo "<input type='hidden' name='fileToDelete' value='" . $row[$i][0] . "'><br><br>";
									echo "<input type='hidden' name='projectID' value='" . $project . "'>";
						echo "</div>";
						echo '<div class="modal-footer">';	
								echo "<button type='submit' class='btn btn-default' name='fileDelete'>Verwijder</button></form>";
							echo "</p>";								
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
	}

	function edituser() {
		$email = htmlspecialchars($_POST["email"]);
		$fname = htmlspecialchars($_POST["fname"]);
		$lname = htmlspecialchars($_POST["lname"]);
		$phone = htmlspecialchars($_POST["phone"]);
		$role = htmlspecialchars($_POST["role"]);
		if (isset($_POST["following"])) {
			$followingArray = $_POST["following"];
		} else {
			$following = "";
		}
		$userID = htmlspecialchars($_POST["userID"]);

		if (!empty($email) && !empty($fname) && !empty($lname) && !empty($role) && !empty($userID)) {
			$SQLString = "SELECT role FROM user WHERE id = " . $userID;
			$QueryResult = $this->executeQuery($SQLString);
			$row = mysqli_fetch_all($QueryResult);

			if (isset($followingArray)) {
				$following = implode(",", $followingArray);
			}

			if ($row[0][0] == "student" && $role != "student") {
				//When a student becomes another role
				$SQLString = "SELECT id FROM portfolio WHERE owner_id = " . $userID;
				$QueryResult = $this->executeQuery($SQLString);
				$row = mysqli_fetch_all($QueryResult);
				$portfolioID = $row[0][0];				

				$SQLString = "SELECT id FROM project WHERE portfolio_id = " . $portfolioID;
				$QueryResult = $this->executeQuery($SQLString);
				$row = mysqli_fetch_all($QueryResult);

				if (mysqli_num_rows($QueryResult) > 0) {
					foreach ($row as $index => $value) {
						$SQLString = "DELETE FROM file WHERE project_id = " . $value[0];
						$QueryResult = $this->executeQuery($SQLString);
						$SQLString = "DELETE FROM rating WHERE project_id = " . $value[0];
						$QueryResult = $this->executeQuery($SQLString);
						$SQLString = "DELETE FROM project WHERE id = " . $value[0];
						$QueryResult = $this->executeQuery($SQLString);						

						$dirname = "front-end/res/portfolios/" . $portfolioID . "/" . $value[0];
						array_map('unlink', glob("$dirname/*.*"));
						rmdir($dirname);						
					}				
				}
				$portDir = "front-end/res/portfolios/" . $portfolioID;
				rmdir($portDir);

				$SQLString = "DELETE FROM persoonlijkeinfo WHERE user_id = " . $userID;
				$QueryResult = $this->executeQuery($SQLString);	

				$SQLString = "DELETE FROM portfolio WHERE id = " . $portfolioID;
				$QueryResult = $this->executeQuery($SQLString); 

			} elseif ($row[0][0] != "student" && $role == "student") {
				//When another role becomes a student
				$following = "";
				$SQLString = 'INSERT INTO portfolio (`title`, `owner_id`) VALUES ("Portfolio ' . $fname . '", "' . $userID . '")';
				$this->executeQuery($SQLString);

				$SQLString = "SELECT id FROM portfolio WHERE owner_id = " . $userID;
				$QueryResult = $this->executeQuery($SQLString);
				$row = mysqli_fetch_assoc($QueryResult);

				$SQLString = 'INSERT INTO persoonlijkeinfo (`user_id`) VALUES ("' . $userID . '")';
				$this->executeQuery($SQLString);

				$folderPath = "front-end/res/portfolios/" . $row["id"] . "/";
				mkdir($folderPath);
			}			

			$SQLString = "UPDATE user SET `e-mail` = '" . $email . "', `role` = '" . $role . "', `firstName` = '" . $fname . "', `lastName` = '" . $lname . "', `phone` = '" . $phone . "', `following` = '" . $following . "' WHERE id = " . $userID; 
			$QueryResult = $this->executeQuery($SQLString);
		}
	}

	function deleteUser() {
		$userID = htmlspecialchars($_POST["userID"]);

		$SQLString = "SELECT role FROM user WHERE id = " . $userID;
		$QueryResult = $this->executeQuery($SQLString);
		$row = mysqli_fetch_all($QueryResult);
		$role = $row[0][0];

		if ($role == "student") {
			$SQLString = "SELECT id FROM portfolio WHERE owner_id = " . $userID;
			$QueryResult = $this->executeQuery($SQLString);
			$row = mysqli_fetch_all($QueryResult);
			$portfolioID = $row[0][0];				

			$SQLString = "SELECT id FROM project WHERE portfolio_id = " . $portfolioID;
			$QueryResult = $this->executeQuery($SQLString);
			$row = mysqli_fetch_all($QueryResult);

			if (mysqli_num_rows($QueryResult) > 0) {
				foreach ($row as $index => $value) {
					$SQLString = "DELETE FROM file WHERE project_id = " . $value[0];
					$QueryResult = $this->executeQuery($SQLString);
					$SQLString = "DELETE FROM rating WHERE project_id = " . $value[0];
					$QueryResult = $this->executeQuery($SQLString);
					$SQLString = "DELETE FROM project WHERE id = " . $value[0];
					$QueryResult = $this->executeQuery($SQLString);			

					$dirname = "front-end/res/portfolios/" . $portfolioID . "/" . $value[0];;
					array_map('unlink', glob("$dirname/*.*"));
					rmdir($dirname);						
				}				
			}
			$portDir = "front-end/res/portfolios/" . $portfolioID;
			rmdir($portDir);

			$SQLString = "DELETE FROM persoonlijkeinfo WHERE user_id = " . $userID;
			$QueryResult = $this->executeQuery($SQLString);	

			$SQLString = "DELETE FROM portfolio WHERE id = " . $portfolioID;
			$QueryResult = $this->executeQuery($SQLString); 
		}

		$SQLString = "DELETE FROM user WHERE id = " . $userID;
		$QueryResult = $this->executeQuery($SQLString);
	}

	function EditCijfer() {
		$grade = htmlspecialchars($_POST["cijfer"]);
		$remark = htmlspecialchars($_POST["cijferOpmerking"]);
		$project_id = htmlspecialchars($_POST["projectID"]);
		$giver_id = $_SESSION['id'];

		if (!empty($grade) && !empty($project_id) && !empty($giver_id)) {
			$SQLString = "UPDATE rating SET grade = '" . $grade . "', remark = '" . $remark . "', giver_id = '" . $giver_id . "' WHERE project_id = '" . $project_id . "'";
			$this->executeQuery($SQLString);
		}
	}

	function submitInfo() {
		$title = htmlspecialchars($_POST["info_title"]);
		$color_bg = htmlspecialchars($_POST["info_color_bg"]);
		$color_font = htmlspecialchars($_POST["info_color_font"]);
		$layout = htmlspecialchars($_POST["info_layout"]);
		$study = htmlspecialchars($_POST["info_study"]);
		$interests = htmlspecialchars($_POST["info_interests"]);
		$experience = htmlspecialchars($_POST["info_experience"]);
		$hobby = htmlspecialchars($_POST["info_hobby"]);
		$description = htmlspecialchars($_POST["info_description"]);

		if (!empty($title) || !empty($color_bg) || !empty($color_font) || !empty($layout)) {
			$SQLString = "UPDATE portfolio SET title = '" . $title . "', layout = '" . $layout . "', bg_color = '" . $color_bg . "', font_color = '" . $color_font . "' WHERE id = " . $_SESSION["portfolio_id"];
			$this->executeQuery($SQLString);

			$SQLString = "SELECT owner_id FROM portfolio WHERE id = " . $_SESSION["portfolio_id"];
			$QueryResult = $this->executeQuery($SQLString);
			$row = mysqli_fetch_assoc($QueryResult);

			$SQLString = "UPDATE persoonlijkeinfo SET Opleiding = '" . $study . "', Interesses = '" . $interests . "', Werkervaring = '" . $experience . "', Hobbies = '" . $hobby . "', Info = '" . $description . "' WHERE user_id = " . $row["owner_id"];
			$this->executeQuery($SQLString);
		}
	}
}
function redirect($url)
{
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    echo $string;
}
?>