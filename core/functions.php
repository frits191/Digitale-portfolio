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
				('$email', '$password', '$role', '$fname', '$lname', '$phone')";
			$this->executeQuery($SQLString);
			echo "The account was succesfully created";
		} else {
			echo "Please fill out all forms.";
		}
	}
}

?>