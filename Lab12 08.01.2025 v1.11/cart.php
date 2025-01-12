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
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #f9f9f9; /* Kolor tła tabeli */
            border-radius: 10px; /* Zaokrąglenie rogów */
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Delikatny cień */
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            background-color: #4CAF50; /* Zielony kolor nagłówków */
            color: white;
            padding: 10px;
        }

        td {
            background-color: #ffffff; /* Tło dla komórek */
            padding: 10px;
            text-align: center;
        }

        tr:nth-child(even) td {
            background-color: #e0f7fa; /* Jasnoniebieskie tło dla parzystych wierszy */
        }

        tr:nth-child(odd) td {
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
        .return-button {
            display: inline-block;
            padding: 12px 24px;
            margin: 20px auto;
            background-color: #007BFF; /* Niech to będzie główny kolor */
            color: #fff; /* Tekst w białym kolorze */
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 8px; /* Zaokrąglone rogi */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Lekki cień */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Animacje hover */
        }

        .return-button:hover {
            background-color: #0056b3; /* Ciemniejszy kolor na hover */
            transform: translateY(-2px); /* Lekki efekt "podnoszenia" przy najechaniu */
        }

        .return-button:active {
            background-color: #004494; /* Jeszcze ciemniejszy kolor przy kliknięciu */
            transform: translateY(0); /* Efekt wciśnięcia */
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

echo "<table>
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

<button onclick="window.location.href='index.php';" class="return-button">Powrót do strony głównej</button>

</body>
</html>