<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "3dgallery";

    $mysqli = new mysqli($host, $user, $pass, $db);

    if ($mysqli->connect_errno) {
        die("Chyba připojení: " . $mysqli->connect_error);
    }
?>