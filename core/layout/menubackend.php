<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, intial-scale=1">
        <link rel="stylesheet" type="text/css" href="menu.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php
        echo "<div id='container'>";
            echo "<div id='menu'>";
                echo "<div id='searchbar'>";
                    echo "<form id='search' method='POST' action='#'>";
                        echo "<input type='text' class='searchinput' name='search' size='10' maxlength='120' placeholder='Search'><input type='submit' value='>' class='searchbutton'>";
                    echo "</form>";
                echo "</div>";
                echo "<div id='content'>";
                    echo "<table class='table table-hover'>";
                        echo "<tr><th>Menu</th></tr>";
                        echo "<tr><td><a href='cijfers.php'>Cijfers</a></td></tr>";
                        echo "<tr><td><a href='projecten.php'>Projecten</a></td></tr>";
                        echo "<tr><td><a href='stages.php'>Stages</a></td></tr>";
                        echo "<tr><td><a href='portfolio.php'>Openbaar portfolio</a></td></tr>";
                        echo "<tr><td><a href='opmerkingen.php'>Opmerkingen</a></td></tr>";
                    echo "</table>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
        ?>
    </body>
</html>