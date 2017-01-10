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
				header ('Location: backend.php?p=home');
				exit();
			} else {
				echo "<br>One or more login details were incorrect, please try again.";
			}
		} else {
			echo "<br>One or more fields are empty, please try again.";
		}
	}

}

?>