<?php
session_start(); 


if (isset($_GET['imie'])) {
    echo "Witaj, " . htmlspecialchars($_GET['imie']) . "!<br />";
} else {
    echo "Wpisz swoje imię w URL np. ?imie=Patryk<br />";
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nazwisko'])) {
    $nazwisko = htmlspecialchars($_POST['nazwisko']);
    echo "Nazwisko z POST: $nazwisko <br />";
}


if (!isset($_SESSION['odwiedziny'])) {
    $_SESSION['odwiedziny'] = 1;
    echo "Witaj pierwszy raz na stronie!<br />";
} else {
    $_SESSION['odwiedziny']++;
    echo "Liczba odwiedzin tej strony: " . $_SESSION['odwiedziny'] . "<br />";
}
?>


<?php
    $nr_indeksu = '169338' . '<br />';
    $nrGrupy = 'ISI3';
    echo 'Patryk Mierzejewski ' . $nr_indeksu . ' grupa ' . $nrGrupy . ' <br /><br />';

    echo 'Zastosowanie metody include() <br />';
    include 'plik1.php';
    echo "zmienne: $zmienna1 $zmienna2" . '<br /><br />';

    echo 'Zastosowanie metody require_once() <br />';
    $zmienna = require_once('plik2.php');
    echo $zmienna . '<br />';
    $zmienna = require_once('plik2.php');
    echo $zmienna . '<br /><br />';

    echo 'Zastosowanie if, else, else if, switch <br />';

    $a = 17;
    $b = 231;
    $c = 17;
    $d = 1;
    if ($a < $b) {
        echo "a mniejsze od b" . '<br />';
    }

    if ($c > $a) {
        echo "c wieksze od a" . '<br />';
    } else if ($a > $c) {
        echo "a wieksze od c" . '<br />';
    } else {
        echo "a rowne c" . '<br />';
    }

    switch ($d) {
        case 0:
            echo "d jest rowne 0" . '<br /><br />';
            break;
        case 1:
            echo "d jest rowne 1" . '<br /><br />';
            break;
        case 2:
            echo "d jest rowne 2" . '<br /><br />';
            break;
    }
    echo 'Zastosowanie while, for <br />';
    $i = 0;
    while ($i <= 15) {
        echo "i równe $i " . '<br />';
        $i++;
    }
    echo '<br />';

    for ($j = 15; $j >= 0; $j--) {
        echo "j równe $j " . '<br />';
    }
    echo '<br />';
?>

    
    <form method="POST" action="">
        <label for="nazwisko">Wpisz swoje nazwisko:</label>
        <input type="text" name="nazwisko" id="nazwisko">
        <input type="submit" value="Wyślij">
    </form>


