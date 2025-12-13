<?php
    // přihlašovací údaje pro MySQL
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "3dgallery";

    // vytvoření nového mysqli objektu (připojení)
    $mysqli = new mysqli($host, $user, $pass, $db);

    // pokud došlo k chybě připojení, skript skončí s chybovou hláškou
    if ($mysqli->connect_errno) {
        die("Chyba připojení: " . $mysqli->connect_error);
    }
?>