<?php 

class functions {
    function connect() {
        $db_name = "digitaalportfolio";
        $DBconnect = mysqli_connect("localhost", "INF1H", "stenden");
        //connect to the database
        if ($DBconnect === FALSE) {
            echo "<p>Unable to connect to the database server.</p>"
            . "<p>Error code " . mysqli_errno() . ": "
            . mysqli_error() . "</p>";
        } else {
            //select the database
            $db = mysqli_select_db($DBconnect, $db_name);
            if ($db === FALSE) {
                echo "<p>Unable to connect to the database server.</p>"
                . "<p>Error code " . mysqli_errno() . ": "
                . mysqli_error() . "</p>";
                mysqli_close($DBconnect);
                $return;
            }
        }
        return $DBconnect;
    }
    
    function executeQuery($SQLstring) {
        $DBconnect = $this->connect();
        $QueryResult = mysqli_query($DBconnect, $SQLString);
        if ($QueryResult === false) {
            echo "Error excecuting query.<br>" . mysqli_errno($DBconnect) . ": " . mysqli_error($DBconnect);
            mysqli_close($DBconnect);
            return;
        }
        mysqli_close($DBconnect);
        return $QueryResult;
    }
}

?>