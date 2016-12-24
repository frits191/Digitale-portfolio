<!DOCTYPE html>
<html>
    <head>
        <title>Digitaal Portfolio</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/simplex/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/header.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
<?php
        echo "<body>";
        echo "<div id='header'>";
            echo "<div id='mainblock'>";  
                echo "<img id='logo' src='core/images/header-logo.png' alt='logo'/>";          
            echo "</div>";
            echo "<div id='userblock'>";
               echo " <p>";
                    echo "U bent ingelogd als:<br/>";
                    echo "(Sample text)<br/>";
                    echo "<br/>";
                    echo "<a id='userlink' href=''>Uitloggen</a>";
                echo "</p>";
            echo "</div>";
            echo "<div id='downblock'>";
                echo "<div class='item-add'>";
                     echo "<img src='core/images/header-maptoevoegen.png' alt='map'/>" ;
                echo "</div>";
                echo "<div class='item-add'>";
                    echo "<img src='core/images/header-itemtoevoegen.png' alt='item'/>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</body>";
?>