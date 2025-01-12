<?php
    // Ustawienie poziomu raportowania błędów
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

     // Wczytanie plików konfiguracyjnych i pomocniczych
    include('cfg.php');
    include ('showpage.php'); 
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Patryk Mierzejewski" />
    <title>Największe budynki świata</title>
    <!-- Skrypty JavaScript -->
    <script src="js/timedate.js" type="text/javascript"></script>
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Wbudowane style -->
    <style>
        p {
        text-align: center;
        text-indent: 20px;   
        }
        .link-table {
        width: 70%; 
        margin: 0 auto; 
        border-collapse: collapse;
        background-color:rgb(219, 255, 252);
        }

        .link-table, .link-table th, .link-table td {
        border: 1px solid #ddd;
        }

        .link-table th, .link-table td {
        padding: 8px;
        text-align: center; 
        }

        .link-table th {
        background-color: #4CAF50;
        color: white;
        font-weight: bold;
        }

        .link-table td a {
        display: block;
        padding: 10px;
        color: #333;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
        }

        .myDiv {
        border: 2px solid #999;
        border-radius: 10px;
        padding: 10px;
        background-color: #f8f8f8;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        }
        
        .myDiv2 {
        font-size: 1.2em;
        color: #333;
        font-weight: bold;
        }
        .myDiv3 {
        font-size: 2em; 
        color: #4CAF50; 
        font-weight: 600; 
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); 
        background-color: rgba(255, 255, 255, 0.8); 
        padding: 10px 20px;
        border-radius: 10px; 
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); 
        text-align: center; 
        width: fit-content; 
        margin: 20px auto;
}
        body {
         margin: 0;
        padding: 0;
        height: 300vh; 
            
        }
        #colorPicker {
        display: none;
        }
        .centered {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        }
    </style>
</head>
<body onload="startClock()">
    <header>
        <h1><p>Największe budynki na świecie</p></h1>
    </header>
    <p class="myDiv2">Witamy na stronie poświęconej najwyższym budynkom na świecie! <br>
    Dowiedz się o imponujących konstrukcjach, które wyznaczają granice możliwości w inżynierii i architekturze.</p>

    <div class="centered">
    <b><i>Zmień kolor strony:</i></b>
<button onclick="toggleColorPicker()">Wybierz kolor strony</button>
<select id="colorPicker" onchange="changeBackground(this.value)">
        <option value="#FFFFFF">Biały</option>
        <option value="#FF0000">Czerwony</option>
        <option value="#00FF00">Zielony</option>
        <option value="#0000FF">Niebieski</option>
        <option value="#FFFF00">Żółty</option>
        <option value="#FFA500">Pomarańczowy</option>
        <option value="#9933FF">Fioletowy</option>
</select>
      </div>

    <div class="link-table">
    <table cellspacing="10">
        <tr style="background-color: brown;">
            <td><a href="index.php?idp=glowna">Strona Główna</a></td>
        </tr>
        <tr style="background-color: aquamarine;">
            <td><a href="index.php?idp=podstrona1">Zjednoczone Emiraty Arabskie</a></td>
            <td><a href="index.php?idp=podstrona2">Chiny</a></td>
            <td><a href="index.php?idp=podstrona3">Malezja</a></td>
            <td><a href="index.php?idp=podstrona4">Stany Zjednoczone</a></td>
            <td><a href="index.php?idp=podstrona5">Singapur</a></td>
            <td><a href="index.php?idp=podstrona6">Experimental</a></td>
            <td><a href="index.php?idp=filmy">Filmy</a></td>
        </tr>
        <tr style="background-color: crimson;">
            <td><a href="contact.php">Kontakt</a></td>
        </tr>
    </table>
</div>

<div class="myDiv3">
        
        Data: M / D / Y<div id="data"></div>
        Godzina: <div id="zegarek"></div>
        <button onclick="toggleClock()">Ukryj/Pokaż Godzinę</button>
</div>

<br>
<div class="myDiv">
    <h3>Ciekawostki o najwyższych budynkach:</h3>
    <ul>
        <li>Wieża Burj Khalifa w Dubaju jest najwyższym budynkiem na świecie.</li>
        <li>Najwyższy budynek w Stanach Zjednoczonych to One World Trade Center w Nowym Jorku.</li>
        <li>Wielki mur w Chinach jest jednym z najstarszych monumentalnych "budynków" świata.</li>
    </ul>
</div>
    
<?php

 // Obsługa zmiennych `idp` dla wyświetlania odpowiednich podstron

    if ($_GET['idp'] == '') {
        
        echo PokazPodstrone(1); // Wyświetlenie treści podstrony

    } elseif ($_GET['idp'] == 'glowna') {
      
      echo PokazPodstrone(1);
    } 
    elseif ($_GET['idp'] == 'filmy') {
   
        echo PokazPodstrone(8);
    }
    elseif ($_GET['idp'] == 'podstrona1') {
        
        echo PokazPodstrone(2);

    } elseif ($_GET['idp'] == 'podstrona2') {
        
        echo PokazPodstrone(3);

    } elseif ($_GET['idp'] == 'podstrona3') {
        
        echo PokazPodstrone(4);

    } elseif ($_GET['idp'] == 'podstrona4') {
        
        echo PokazPodstrone(5);

    } elseif ($_GET['idp'] == 'podstrona5') {
        
        echo PokazPodstrone(6);

    } elseif ($_GET['idp'] == 'podstrona6') {
        
        echo PokazPodstrone(7); //experimental 7

    } elseif ($_GET['idp'] == 'kontakt') {
      
      echo PokazPodstrone(9);

    } else {
        
        echo PokazPodstrone(1);
    }
?>

</div>
</body>
</html>

<?php
session_start(); // Uruchomienie sesji
include('cfg.php');

// Połączenie z bazą danych
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
if (!$conn) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

if (isset($_SESSION['popup_message'])) {
    echo "<script>document.addEventListener('DOMContentLoaded', function() {
        showPopup('" . addslashes($_SESSION['popup_message']) . "');
    });</script>";
    unset($_SESSION['popup_message']); // Usuń wiadomość po jej wyświetleniu
}

// Funkcje zarządzania koszykiem
function addToCart($id_prod, $ilosc, $cena_netto, $vat) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $cena_brutto = $cena_netto * (1 + $vat / 100);

    if (isset($_SESSION['cart'][$id_prod])) {
        $_SESSION['cart'][$id_prod]['ilosc'] += $ilosc;
    } else {
        $_SESSION['cart'][$id_prod] = [
            'id_prod' => $id_prod,
            'ilosc' => $ilosc,
            'cena_netto' => $cena_netto,
            'cena_brutto' => $cena_brutto
        ];
    }
}

function updateCartQuantity($id_prod, $new_quantity) {
    global $conn;

    if (isset($_SESSION['cart'][$id_prod])) {
        // Pobierz dostępność z bazy danych
        $query = "SELECT ilosc_dostepnych_sztuk_w_magazynie FROM produkty WHERE id = $id_prod";
        $result = mysqli_query($conn, $query);
        $produkt = mysqli_fetch_assoc($result);

        if ($produkt && $new_quantity <= $produkt['ilosc_dostepnych_sztuk_w_magazynie']) {
            $_SESSION['cart'][$id_prod]['ilosc'] = $new_quantity;
            $_SESSION['popup_message'] = 'Ilość została pomyślnie zaktualizowana.';
        } else {
            $_SESSION['popup_message'] = "Nie można ustawić ilości. Tylko {$produkt['ilosc_dostepnych_sztuk_w_magazynie']} sztuk jest dostępnych.";
        }
    }
    header('Location: cart.php');
    exit();
}

function removeFromCart($id_prod) {
    if (isset($_SESSION['cart'][$id_prod])) {
        unset($_SESSION['cart'][$id_prod]);
    }
}

function showCart() {
    global $conn, $feedback;

    if (empty($_SESSION['cart'])) {
        echo "<p>Koszyk jest pusty.</p>";
        return;
    }

    // Wyświetl ewentualną wiadomość o błędzie lub powodzeniu
    echo $feedback;

    echo "<h2>Koszyk</h2>";
    echo "<table border='1'>
            <tr>
                <th>ID Produktu</th>
                <th>Ilość</th>
                <th>Cena netto</th>
                <th>Cena brutto</th>
                <th>Wartość brutto</th>
                <th>Zmiana ilości</th>
                <th>Akcje</th>
            </tr>";

    $suma = 0;
    foreach ($_SESSION['cart'] as $produkt) {
        $wartosc_brutto = $produkt['ilosc'] * $produkt['cena_brutto'];
        $suma += $wartosc_brutto;

        echo "<tr>
                <td>{$produkt['id_prod']}</td>
                <td>{$produkt['ilosc']}</td>
                <td>" . number_format($produkt['cena_netto'], 2) . " zł</td>
                <td>" . number_format($produkt['cena_brutto'], 2) . " zł</td>
                <td>" . number_format($wartosc_brutto, 2) . " zł</td>
                <td>
                    <form action='cart.php' method='post' style='display: inline;'>
                        <input type='hidden' name='action' value='update'>
                        <input type='hidden' name='id_prod' value='{$produkt['id_prod']}'>
                        <input type='number' name='new_quantity' value='{$produkt['ilosc']}' min='1'>
                        <button type='submit'>Zmień</button>
                    </form>
                </td>
                <td><a href='cart.php?action=remove&id={$produkt['id_prod']}'>Usuń</a></td>
              </tr>";
    }

    echo "</table>";
    echo "<h3>Łączna wartość koszyka: " . number_format($suma, 2) . " zł</h3>";

}

// Obsługa akcji (dodawanie/usuwanie/aktualizowanie ilości w koszyku)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'update') {
        $id_prod = (int)$_POST['id_prod'];
        $new_quantity = (int)$_POST['new_quantity'];
        updateCartQuantity($id_prod, $new_quantity);
    }
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id_prod = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id_prod) {
        // Pobierz dane produktu z bazy, aby zapewnić poprawność dodawania
        $query = "SELECT * FROM produkty WHERE id = $id_prod";
        $result = mysqli_query($conn, $query);
        $produkt = mysqli_fetch_assoc($result);

        if ($produkt) {
            $ilosc = 1; // Domyślna ilość to 1
            $cena_netto = $produkt['cena_netto'];
            $vat = $produkt['podatek_vat'];
        }

        if($produkt['ilosc_dostepnych_sztuk_w_magazynie'] == 0){
            $_SESSION['popup_message'] = 'Produkt nie jest dostępny, nie można dodać go do koszyka';
            header('Location: cart.php');
            exit();
        }

        if ($action == 'add') {
            addToCart($id_prod, $ilosc, $cena_netto, $vat);
            $_SESSION['popup_message'] = 'Produkt został dodany do koszyka.';
            header('Location: cart.php');
            exit();
        } elseif ($action == 'remove') {
            removeFromCart($id_prod);
            unset($_SESSION['cart'][$id_prod]);
            $_SESSION['popup_message'] = 'Produkt został usunięty z koszyka.';
            header('Location: cart.php');
            exit();
        }

        header('Location: cart.php'); // Odśwież stronę, aby uniknąć ponownego wykonania akcji
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszyk</title>
    <style>
        /* Style dla tabeli produktów */
        .produkty-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #f9f9f9; /* Kolor tła tabeli */
            border-radius: 10px; /* Zaokrąglenie rogów */
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Delikatny cień */
        }

        .produkty-table, .produkty-table th, .produkty-table td {
            border: 1px solid black;
        }

        .produkty-table th {
            background-color: #4CAF50; /* Zielony kolor nagłówków */
            color: white;
            padding: 10px;
        }

        .produkty-table td {
            background-color: #ffffff; /* Tło dla komórek */
            padding: 10px;
            text-align: center;
        }

        .produkty-table tr:nth-child(even) td {
            background-color: #e0f7fa; /* Jasnoniebieskie tło dla parzystych wierszy */
        }

        .produkty-table tr:nth-child(odd) td {
            background-color: #ffffff; /* Białe tło dla nieparzystych wierszy */
        }

        h1, h2 {
            text-align: center;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border: 1px solid black;
            padding: 20px;
            z-index: 1000;
            text-align: center;
        }

        .popup button {
            margin-top: 10px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
    <script>
    function showPopup(message) {
        const popup = document.querySelector('.popup');
        const overlay = document.querySelector('.overlay');
        popup.innerHTML = `<p>${message}</p><button onclick="closePopup()">Zamknij</button>`;
        popup.style.display = 'block';
        overlay.style.display = 'block';
    }

    function closePopup() {
        const popup = document.querySelector('.popup');
        const overlay = document.querySelector('.overlay');
        popup.style.display = 'none';
        overlay.style.display = 'none';
    }
    </script>
</head>
<body>

<div class="overlay" onclick="closePopup()"></div>
<div class="popup"></div>

<h1>Sklep internetowy - Lista produktów</h1>

<?php
// Wyświetlenie listy produktów
$query = "SELECT * FROM produkty";
$result = mysqli_query($conn, $query);

echo "<table class='produkty-table'>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Cena netto</th>
            <th>VAT</th>
            <th>Cena brutto</th>
            <th>Ilość</th>
            <th>Status dostępności</th>
            <th>Akcje</th>
        </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $nazwa = $row['tytul'];
    $cena_netto = $row['cena_netto'];
    $vat = $row['podatek_vat'];
    $ilosc = $row['ilosc_dostepnych_sztuk_w_magazynie'];
    $status_dostepnosci = $ilosc > 0 ? 'Dostępny' : 'Niedostępny';
    $cena_brutto = $cena_netto * (1 + $vat / 100);

    echo "<tr>
            <td>$id</td>
            <td>$nazwa</td>
            <td>" . number_format($cena_netto, 2) . " zł</td>
            <td>$vat%</td>
            <td>" . number_format($cena_brutto, 2) . " zł</td>
            <td>$ilosc</td>
            <td>$status_dostepnosci</td>
            <td><a href='cart.php?action=add&id=$id'>Dodaj do koszyka</a></td>
          </tr>";
}

echo "</table>";
?>

<hr>

<?php
// Wyświetlenie zawartości koszyka
showCart();
?>

</body>
</html>

