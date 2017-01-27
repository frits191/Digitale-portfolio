<?php

$_SESSION["message"]["Succes!"] = $_SESSION["name"] . ". U bent nu uitgelogd";
$_SESSION['loggedIn'] = false;
unset($_SESSION["e-mail"]);
unset($_SESSION['role']);
unset($_SESSION['name']);
unset($_SESSION['id']);

redirect("?page=home");