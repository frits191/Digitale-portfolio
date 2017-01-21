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
		$password = htmlspecialchars($_POST["password"]);				
		$fname = htmlspecialchars($_POST["fname"]);
		$lname = htmlspecialchars($_POST["lname"]);
		$phone = htmlspecialchars($_POST["phone"]);
		$role = htmlspecialchars($_POST["role"]);

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
			$SQLString = "INSERT INTO user (`e-mail`, `password`, `role`, `firstName`, `lastName`, `phone`) VALUES
				('" . $email . "', '" . $hash . "', '" . $role . "', '" . $fname . "', '" . $lname . "', '" . $phone . "')";
			$this->executeQuery($SQLString);

			$SQLString = "SELECT id FROM user WHERE `e-mail` = '" . $email . "'";
			$QueryResult = $this->executeQuery($SQLString);
			$row = mysqli_fetch_assoc($QueryResult);
			$id = $row["id"];

			if ($role == "student") {
				$SQLString = "INSERT INTO portfolio (`title`, `owner_id`) VALUES ('Portfolio " . $fname . "', '" . $id . "')";
				$this->executeQuery($SQLString);
				$folderPath = "portfolio/" . $id . "/";
				mkdir($folderPath);
			}
			echo "The account was succesfully created";
		} else {
			echo "Please fill out all forms.";
		}
	}

	function createFolder() {
		global $DBconnect;
		if (!empty($_POST["folderName"])) {
			$title = htmlspecialchars($_POST["folderName"]);
			if (empty($_POST["folderDesc"])) {
				$description = "";
			} else {
				$description = htmlspecialchars($_POST["folderDesc"]);
			}
			$SQLString = "INSERT INTO project (`title`, `description`, `portfolio_id`) VALUES ('" . $title . "', '" . $description . "', '" . $_SESSION['portfolio_id'] . "')";
			$QueryResult = $this->executeQuery($SQLString);
			$last_id = mysqli_insert_id($DBconnect);
			$folderPath = "front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $last_id;
			mkdir($folderPath);
		}
	}

	function uploadFile() {
		global $project;

		$title = htmlspecialchars($_POST["fileTitle"]);

		if (isset($_POST["fileDesc"])) {
			$description = htmlspecialchars($_POST["fileDesc"]);
		} else {
			$description = "";
		}

		if (empty($title)) {
			header('Location: backend.php?p=projecten&project=' . $project);
			exit();
		}

        $target_dir = "front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $project . "/";

        if(getimagesize($_FILES["file"]["tmp_name"]) !== false) {
            if (isset($_FILES["file"])) {
                $target_file = $target_dir . basename($_FILES["file"]["name"]);
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                if ($this->imagecheck($imageFileType , $target_file, "file") === true) {
                    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
                    rename($target_file, $target_dir . $title . "." . $imageFileType);

					$SQLString = "INSERT INTO file (`title`, `type`, `description`, `project_id`) VALUES ('" . $title . "', '." . $imageFileType . "', '" . $description . "', '" . $project . "')";
					$this->executeQuery($SQLString);
                } 
            }
        }
	}

	function imagecheck($imageFileType, $target_file, $ImageName) {
        //check if the file does not exceed the max size
		if ($_FILES[$ImageName]["size"] > 5000000) {
			echo "Sorry, your file is too large.<br>";
            return false;
        }
        //Check if the image is a jpg, png, jpeg or gif
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            return false;
        } else {
            return true;
        }
    }

	function getFolders() {
		$SQLString = "SELECT * FROM project WHERE portfolio_id = '" . $_SESSION["portfolio_id"] . "' ORDER BY id DESC";
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
                            echo "<span class='glyphicon glyphicon-pencil' aria-hidden='true' title='Edit' data-toggle='modal' data-target='#EditFolder'></span>";
                        echo "</button>";
                        echo "<button type='button' class='btn btn-link'>";
                            echo "<span class='glyphicon glyphicon-trash' aria-hidden='true' title='Delete' data-toggle='modal' data-target='#DeleteFolder'></span>";
                        echo "</button>";
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
                echo "<div class='file' onclick='location.href = \"front-end\res\portfolio" . $_SESSION["portfolio_id"] . "\\" . $project . "\";'>";
                    echo "<button type='button' class='btn-link'>";
                        echo "<span class='glyphicon glyphicon-file' aria-hidden='true'></span>";
                    echo "</button>";
                echo "</div>";
                echo "<div class='filemenu'>";
					echo "<div class='FolderTitle'>" . $row[$i][1] . $row[$i][2] . "</div>";
                    echo "<div class='btn-group'>";
						echo "<button type='button' class='btn btn-link'>";
							echo "<span class='glyphicon glyphicon-open' aria-hidden='true' title='Upload'></span>";
						echo "</button>";
                        echo "<button type='button' class='btn btn-link'>";
                            echo "<span class='glyphicon glyphicon-pencil' aria-hidden='true' title='Edit'></span>";
                        echo "</button>";
                        echo "<button type='button' class='btn btn-link'>";
                            echo "<span class='glyphicon glyphicon-trash' aria-hidden='true' title='Delete'></span>";
                        echo "</button>";
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
}
?>