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
			$SQLString = "SELECT * FROM user WHERE `e-mail` = '$email' AND password = '$password'";
			$QueryResult = $this->executeQuery($SQLString);
			$row = mysqli_fetch_assoc($QueryResult);

			//controleer of het wachtwoord end e-mail hetzelfde zijn
			if ($email === $row["e-mail"] && $password === $row["password"]){
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
			$phone = "NULL";
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
			$SQLString = "INSERT INTO user (`e-mail`, `password`, `role`, `firstName`, `lastName`, `phone`) VALUES
				('" . $email . "', '" . $password . "', '" . $role . "', '" . $fname . "', '" . $lname . "', '" . $phone . "')";
			$this->executeQuery($SQLString);

			$SQLString = "SELECT id FROM user WHERE `e-mail` = '" . $email . "'";
			$QueryResult = $this->executeQuery($SQLString);
			$row = mysqli_fetch_assoc($QueryResult);
			$id = $row["id"];

			if ($role = "student") {
				$SQLString = "INSERT INTO portfolio (`title`, `owner_id`) VALUES ('Portfolio " . $fname . "', '" . $id . "')";
				$this->executeQuery($SQLString);
			}
			echo "The account was succesfully created";
		} else {
			echo "Please fill out all forms.";
		}
	}

	function createFolder() {
		if (!empty($_POST["folderName"])) {
			$title = htmlspecialchars($_POST["folderName"]);
			if (empty($_POST["folderDesc"])) {
				$description = "";
			} else {
				$description = htmlspecialchars($_POST["folderName"]);
			}
			$SQLString = "INSERT INTO project (`title`, `description`, `portfolio_id`) VALUES ('" . $title . "', '" . $description . "', '" . $_SESSION['portfolio_id'] . "')";
			$QueryResult = $this->executeQuery($SQLString);
		}
	}

	function getFolders() {
		$SQLString = "SELECT * FROM project WHERE portfolio_id = '" . $_SESSION["portfolio_id"] . "' ORDER BY id DESC";
		$QueryResult = $this->executeQuery($SQLString);
		$row = mysqli_fetch_all($QueryResult);

		for ($i = count($row) - 1;$i >= 0;$i--) {
			//echo "<div class='clearfix'></div>";
            echo "<div class='fileblock'>";
                echo "<div class='file' onclick='location.href = \"backend.php?p=portfolio&project=" . $row[$i][0] . "\";'>";
                    echo "<button type='button' class='btn-link'>";
                        echo "<span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span>";
                    echo "</button>";
                echo "</div>";
                echo "<div class='filemenu'>";
					echo "<div class='FolderTitle'>" . $row[$i][1] . "</div>";
                    echo "<div class='btn-group'>";
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
	}
}

?>