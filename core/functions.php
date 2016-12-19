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
}

?>