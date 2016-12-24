<?php

class functions {
    function executeQuery($SQLstring) {
        $QueryResult = mysqli_query($DBconnect, $SQLString);
        if ($QueryResult === false) {
            echo "Error excecuting query.<br>" . mysqli_errno($DBconnect) . ": " . mysqli_error($DBconnect);
            mysqli_close($DBconnect);
            return;
        }
        mysqli_close($DBconnect);
        return $QueryResult;
    }

	function login() {
		//email en password komen vanuit het ingevulde formulier
		$Email = htmlspecialchars($_POST["E-mail"]);
		$Password = htmlspecialchars($_POST["Password"]);

        //aantal rijen checken
        $SQLString = "SELECT * FROM user WHERE e-mail='" . $Email . "' AND password = '" . $Password . "'";
		$QueryResult = $this->executeQuery($SQLString);
        $count = mysqli_num_rows($QueryResult);

        //als er 1 rij is kloppen de email en wachtwoord
        if ($count === 1){
            $_SESSION['loggedIn'] = true;
        } else {
            return false;
        }

	}

    function checkLogin() {
		//met deze functie check je of de gebruiker is ingelogd (zet hem bovenaan de code)
        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == TRUE) {
            return true;
        } else {
            echo "je bent niet ingelogd";
			return false;
        }
    }
}

?>