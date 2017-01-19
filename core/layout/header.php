
<?php
    echo "<body>";
    echo "<div id='header'>";
        echo "<div id='mainblock'>";
			
			
            echo "<img id='logo' src='../images/header-logo.png' alt='logo'/>";
			   
        echo "</div>";
        echo "<div id='userblock'>";
            echo " <p>";
                echo "U bent ingelogd als:<br/>";
                echo "(Sample text)<br/>";
                echo "<br/>             "       ;
                echo "<a id='userlink' href=''>Uitloggen</a>";
            echo "</p>";
        echo "</div>";
        echo "<div id='downblock'>";
            echo "<div class='item-add'>";
                    echo "<img src='../images/header-maptoevoegen.png' alt='map'/>" ;
            echo "</div>";
            echo "<div class='item-add'>";
                echo "<img src='../images/header-itemtoevoegen.png' alt='item'/>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "</body>";
?>
