<?php

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';
    $login = "admin";
    $pass = "p.mierz169338#";

    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$link) {
        die('<b>Przerwane połączenie: </b>' . mysqli_connect_error());
    } 


?>