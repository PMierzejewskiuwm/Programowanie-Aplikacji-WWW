<?php

    // Inicjalizacja zmiennych potrzebnych do połączenia z bazą danych

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';
    $login = "admin";                    //ustawienie loginu którym będziemy się logować
    $pass = "p.mierz169338#";           // ustawienie hasła którym będziemy się logować

    // Połączenie z bazą danych za pomocą zmiennych

    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$link) {
        die('<b>Przerwane połączenie: </b>' . mysqli_connect_error());
    } 


?>