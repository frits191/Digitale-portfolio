<?php
$dsn = "mysql:dbname=digitaal_portfolio;host=localhost;port=3306;charset=UTF8";
$username="root";
$password="";

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo ('Could not connect to the database:<br/>' . $e);
}
?>