<?php

// Inicjalizacja i konfiguracja

include('../cfg.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
if (!$conn) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

session_start();

// Funkcja: Formularz logowania

function FormularzLogowania() 
{
    // Generowanie formularza logowania
    $wynik = "
    <div class='logowanie'>
        <h1 class='heading'>Panel CMS:</h1>
        <form method='post' name='LoginForm' enctype='multipart/form-data' action='" . htmlspecialchars($_SERVER['REQUEST_URI']) . "' >
            <table class='logowanie'>
                <tr>
                    <td class='log4_t'>[login]</td>
                    <td><input type='text' name='login' class='logowanie' /></td>
                </tr>
                <tr>
                    <td class='log4_t'>[hasło]</td>
                    <td><input type='password' name='login_pass' class='logowanie' /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type='submit' name='xl_submit' class='logowanie' value='zaloguj' /></td>
                </tr>
            </table>
        </form>
    </div>";
    return $wynik;
}


// Obsługa logowania

if (isset($_POST['xl_submit'])) {
    // Bezpieczne pobranie danych
    $email = htmlspecialchars(trim($_POST['login'] ?? ''));
    $password = htmlspecialchars(trim($_POST['login_pass'] ?? ''));

    // Walidacja danych
    if ($email === $login && $password === $pass) {
        $_SESSION['logged_in'] = true;
    } else {
        echo "<p style='color: red;'>Błędny login lub hasło!</p>";
        echo FormularzLogowania();
        exit;
    }
}

// Wyświetlenie formularza logowania, jeśli użytkownik nie jest zalogowany
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo FormularzLogowania();
    exit;
}

// Wyświetlenie panelu administracyjnego

echo "<h1>Panel administracyjny</h1>";

// Obsługa akcji: edytuj, dodaj, usuń
if (isset($_GET['akcja'])) {
    $akcja = htmlspecialchars($_GET['akcja']);
    if ($akcja == 'edytuj' && isset($_GET['id'])) {
        $id = (int)$_GET['id']; // Bezpieczna konwersja na integer
        EdytujPodstrone($id); // Wyświetlenie formularza edycji
        
    } elseif ($akcja == 'dodaj') {
        DodajNowaPodstrone(); // Wyświetlenie formularza dodawania
        
    } elseif ($akcja == 'usun' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        UsunPodstrone($id); // Usunięcie podstrony
        
    } else {
        // Nieznana akcja - wyświetl tabelę podstron
        ListaPodstron();
    }
} else {
    // Brak akcji - wyświetl tabelę podstron
    ListaPodstron();
}

// Funkcja: Lista podstron
function ListaPodstron()
{
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    // Zapytanie SQL z limitem
    $query = "SELECT id, page_title FROM page_list ORDER BY id ASC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>
                <tr>
                    <th>ID</th>
                    <th>Tytuł</th>
                    <th>Akcje</th>
                </tr>";

        // Iteracja po wynikach zapytania
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . htmlspecialchars($row['page_title']) . "</td>
                    <td>
                        <a href='admin.php?akcja=usun&id=" . $row['id'] . "' onclick='return confirm(\"Czy na pewno chcesz usunąć?\")'>Usuń</a> | 
                        <a href='admin.php?akcja=edytuj&id=" . $row['id'] . "'>Edytuj</a>
                    </td>
                  </tr>";
        }
        echo "<tr>
                <td colspan='3' style='text-align: center;'>
                    <a href='admin.php?akcja=dodaj'>Dodaj nową podstronę</a>
                </td>
              </tr>
              </table>";
    } else {
        echo "Brak podstron do wyświetlenia.";
    }

    mysqli_close($conn);
}

// Funkcja: Dodaj nową podstronę
function DodajNowaPodstrone()
{
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = mysqli_real_escape_string($conn, $_POST['page_title']);
        $content = mysqli_real_escape_string($conn, $_POST['page_content']);
        $status = isset($_POST['status']) ? 1 : 0;
        $alias = mysqli_real_escape_string($conn, $_POST['alias']);

        $query = "INSERT INTO page_list (page_title, page_content, status, alias) VALUES ('$title', '$content', $status, '$alias')";

        if (mysqli_query($conn, $query)) {
            header("Location: admin.php"); // Przekierowanie, aby uniknąć powtórnego przesyłania formularza
            exit;
        } else {
            echo "<script>alert('Wystąpił błąd podczas dodawania podstrony: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        // Formularz dodawania
        echo "
        <h2>Dodaj nową podstronę</h2>
        <form method='post' action=''>
            <table>
                <tr><td>Tytuł: </td><td><input type='text' name='page_title' required /></td></tr>
                <tr><td>Treść: </td><td><textarea name='page_content' required></textarea></td></tr>
                <tr><td>Alias: </td><td><input type='text' name='alias' /></td></tr>
                <tr><td>Aktywna: </td><td><input type='checkbox' name='status' /> Tak</td></tr>
                <tr><td>&nbsp;</td><td><input type='submit' value='Dodaj podstronę' /></td></tr>
            </table>
        </form>";
    }

    mysqli_close($conn);
}

// Funkcja: Usun podstronę
function UsunPodstrone($id)
{
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    $id = (int)$id;
    $query = "DELETE FROM page_list WHERE id = $id LIMIT 1";

    if (mysqli_query($conn, $query)) 
    {
        echo "<script>alert('Nowa podstrona została dodana!');</script>";
        header("Location: admin.php"); // Przekierowanie, aby uniknąć powtórnego przesyłania formularza
    }
    else
    {
        echo "<script>alert('Wystąpił błąd podczas usuwania podstrony: " . mysqli_error($conn) . "');</script>";
    }

    mysqli_close($conn);
}


// Funkcja: Edytuj podstronę

function EdytujPodstrone($id)
{
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    // Bezpieczna konwersja ID na integer
    $id = (int)$id;

    // Obsługa formularza edycji po przesłaniu
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Bezpieczne pobranie danych wejściowych
        $title = mysqli_real_escape_string($conn, $_POST['page_title']);
        $content = mysqli_real_escape_string($conn, $_POST['page_content']);
        $status = isset($_POST['is_active']) ? 1 : 0;

        // Aktualizacja danych w bazie
        $update_query = "UPDATE page_list SET page_title = '$title', page_content = '$content', status = $status WHERE id = $id";
        if (mysqli_query($conn, $update_query)) {
            echo "<p style='color: green;'>Podstrona została zaktualizowana.</p>";
            header("Location: admin.php"); // Przekierowanie, aby uniknąć powtórnego przesyłania formularza
        } else {
            echo "<p style='color: red;'>Wystąpił błąd podczas aktualizacji: " . mysqli_error($conn) . "</p>";
        }
        mysqli_close($conn);
        return;
    }

    // Pobieranie danych do edycji
    $query = "SELECT page_title, page_content, status FROM page_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Przygotowanie danych do wyświetlenia w formularzu
        $title = htmlspecialchars($row['page_title']);
        $content = htmlspecialchars($row['page_content']);
        $is_active = $row['status'] == 1 ? 'checked' : '';

        // Formularz edycji podstrony
        echo "
        <h2>Edytuj podstronę</h2>
        <form method='post' action=''>
            <table>
                <tr>
                    <td>Tytuł: </td>
                    <td><input type='text' name='page_title' value='$title' required /></td>
                </tr>
                <tr>
                    <td>Treść: </td>
                    <td><textarea name='page_content' required>$content</textarea></td>
                </tr>
                <tr>
                    <td>Aktywna: </td>
                    <td><input type='checkbox' name='is_active' $is_active /> Tak</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type='submit' name='submit' value='Zapisz zmiany' /></td>
                </tr>
            </table>
        </form>
        ";
    } else {
        echo "<p>Nie znaleziono podstrony o podanym ID.</p>";
    }
    echo ListaPodstron();

    mysqli_close($conn);
}

// Obsługa akcji: edytuj, dodaj, usuń
if (isset($_GET['akcja'])) {
    $akcja = htmlspecialchars($_GET['akcja']);
    if ($akcja == 'edytuj1' && isset($_GET['id'])) {
        $id = (int)$_GET['id']; // Bezpieczna konwersja na integer
        EdytujKategorie($id); // Wyświetlenie formularza edycji kategorii
        
    } elseif ($akcja == 'dodaj1') {
        DodajKategorie(); // Wyświetlenie formularza dodawania nowej kategorii
        
    } elseif ($akcja == 'usun1' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        UsunKategorie($id); // Usunięcie kategorii
        
    } else {
        // Nieznana akcja - wyświetl tabelę kategorii oraz listę kategorii rekurencyjnie
        ZarzadzajKategoriami();
        PokazKategorie();
    }
} else {
    // Brak akcji - wyświetl tabelę kategorii oraz listę kategorii rekurencyjnie
    ZarzadzajKategoriami();
    PokazKategorie();
}

// Funkcja: Zarządzanie kategoriami
function ZarzadzajKategoriami()
{
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    // Zapytanie SQL do pobrania kategorii
    $query = "SELECT id, nazwa, matka FROM sklep ORDER BY nazwa ASC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Tworzenie tabeli do wyświetlenia kategorii
        echo "<h2>Zarządzaj kategoriami</h2>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>
                <tr>
                    <th>ID</th>
                    <th>Nazwa kategorii</th>
                    <th>Akcje</th>
                </tr>";

        // Iteracja po wynikach zapytania i wyświetlanie kategorii
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . htmlspecialchars($row['nazwa']) . "</td>
                    <td>
                        <a href='admin.php?akcja=edytuj1&id=" . $row['id'] . "'>Edytuj</a> | 
                        <a href='admin.php?akcja=usun1&id=" . $row['id'] . "' onclick='return confirm(\"Czy na pewno chcesz usunąć tę kategorię?\")'>Usuń</a>
                    </td>
                  </tr>";
        }

        // Link do dodawania nowej kategorii
        echo "<tr>
                <td colspan='3' style='text-align: center;'>
                    <a href='admin.php?akcja=dodaj1'>Dodaj nową kategorię</a>
                </td>
              </tr>
              </table>";
    } else {
        // Jeśli brak kategorii
        echo "Brak kategorii do wyświetlenia.";
    }

    mysqli_close($conn);
}

// Funkcja: Usuń kategorie
function UsunKategorie($id)
{
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    $query = "DELETE FROM sklep WHERE id = $id OR matka = $id LIMIT 1";
    if (mysqli_query($conn, $query)) {
        header("Location: admin.php"); // Przekierowanie, aby uniknąć powtórnego przesyłania formularza
        echo "<p style='color: green;'>Kategoria została usunięta.</p>";
    } else {
        echo "<p style='color: red;'>Wystąpił błąd podczas usuwania kategorii: " . mysqli_error($conn) . "</p>";
    }

    mysqli_close($conn);
}

// Funkcja: Dodaj kategorię
function DodajKategorie()
{
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
        $parent_id = (int)($_POST['parent_id'] ?? 0);

        // Zapytanie do dodania kategorii
        $query = "INSERT INTO sklep (nazwa, matka) VALUES ('$category_name', $parent_id)";
        if (mysqli_query($conn, $query)) {
            header("Location: admin.php"); // Przekierowanie, aby uniknąć powtórnego przesyłania formularza
            echo "<p style='color: green;'>Kategoria została dodana.</p>";
        } else {
            echo "<p style='color: red;'>Wystąpił błąd podczas dodawania kategorii: " . mysqli_error($conn) . "</p>";
        }

    } else {
        echo "
        <h2>Dodaj kategorię</h2>
        <form method='post'>
            <table>
                <tr>
                    <td>Nazwa kategorii:</td>
                    <td><input type='text' name='category_name' required /></td>
                </tr>
                <tr>
                    <td>Kategoria nadrzędna:</td>
                    <td>
                        <select name='parent_id'>
                            <option value='0'>Brak</option>";

        // Pobranie kategorii z poziomami
        $result = mysqli_query($conn, "SELECT id, nazwa, matka FROM sklep WHERE matka = 0");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nazwa']) . "</option>";
            // Wywołanie rekurencyjne, by pokazać kategorie podkategorii
            PokazKategorieRekurencyjnieOpcje($row['id'], 1);
        }

        echo "
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type='submit' value='Dodaj kategorię' /></td>
                </tr>
            </table>
        </form>";
    }

    mysqli_close($conn);
}

// Funkcja: Rekurencyjne wyświetlanie kategorii w formularzu
function PokazKategorieRekurencyjnieOpcje($matka = 0, $poziom = 1)
{
    global $conn;
    $matka = (int)$matka;

    // Pobieranie kategorii dla danego poziomu
    $query = "SELECT id, nazwa FROM sklep WHERE matka = $matka ORDER BY nazwa ASC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Wyświetlanie kategorii jako opcji
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['id'] . "'>" . str_repeat("&nbsp;&nbsp;", $poziom) . htmlspecialchars($row['nazwa']) . "</option>";

            // Wywołanie rekurencyjne dla podkategorii
            PokazKategorieRekurencyjnieOpcje($row['id'], $poziom + 1);
        }
    }
}

// Funkcja: Edytuj kategorię
function EdytujKategorie($id)
{
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
        $parent_id = (int)($_POST['parent_id'] ?? 0);

        $query = "UPDATE sklep SET nazwa = '$category_name', matka = $parent_id WHERE id = $id LIMIT 1";
        if (mysqli_query($conn, $query)) {
            header("Location: admin.php");
            echo "<p style='color: green;'>Kategoria została zaktualizowana.</p>";
        } else {
            echo "<p style='color: red;'>Wystąpił błąd podczas aktualizacji kategorii: " . mysqli_error($conn) . "</p>";
        }

    } else {
        $query = "SELECT nazwa, matka FROM sklep WHERE id = $id LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $category_name = htmlspecialchars($row['nazwa']);
            $parent_id = (int)$row['matka'];

            echo "
            <h2>Edytuj kategorię</h2>
            <form method='post'>
                <table>
                    <tr>
                        <td>Nazwa kategorii:</td>
                        <td><input type='text' name='category_name' value='$category_name' required /></td>
                    </tr>
                    <tr>
                        <td>Kategoria nadrzędna:</td>
                        <td>
                            <select name='parent_id'>
                                <option value='0'>Brak</option>";

            // Wyświetlanie opcji kategorii nadrzędnych, z uwzględnieniem wybranego
            $result2 = mysqli_query($conn, "SELECT id, nazwa FROM sklep WHERE matka = 0 AND id != $id");
            while ($row2 = mysqli_fetch_assoc($result2)) {
                $selected = $row2['id'] == $parent_id ? 'selected' : '';
                echo "<option value='" . $row2['id'] . "' $selected>" . htmlspecialchars($row2['nazwa']) . "</option>";
                PokazKategorieRekurencyjnieOpcje($row2['id'], 1);
            }

            echo "
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type='submit' value='Zapisz zmiany' /></td>
                    </tr>
                </table>
            </form>";
        } else {
            echo "<p>Nie znaleziono kategorii o podanym ID.</p>";
        }
    }

    mysqli_close($conn);
}

// Funkcja: Wyświetlanie kategorii
function PokazKategorie()
{
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    echo "<h2>Lista kategorii</h2>";
    PokazKategorieRekurencyjnie(0);

    mysqli_close($conn);
}

function PokazKategorieRekurencyjnie($matka = 0, $poziom = 0)
{
    global $conn;
    $matka = (int)$matka;

    // Pobieranie kategorii dla danego poziomu
    $query = "SELECT id, nazwa FROM sklep WHERE matka = $matka ORDER BY nazwa ASC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Wyświetlanie kategorii
        while ($row = mysqli_fetch_assoc($result)) {
            // Wcięcie zależne od poziomu zagnieżdżenia
            echo str_repeat("&nbsp;&nbsp;&nbsp;", $poziom) . htmlspecialchars($row['nazwa']) . "<br>";

            // Wywołanie rekurencyjne dla podkategorii
            PokazKategorieRekurencyjnie($row['id'], $poziom + 1);
        }
    }
}

function DodajProdukt() {
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
        $opis = mysqli_real_escape_string($conn, $_POST['opis']);
        $cena_netto = (float)$_POST['cena_netto'];
        $vat = (float)$_POST['vat'];
        $ilosc = (int)$_POST['ilosc'];
        $data_wygasniecia = mysqli_real_escape_string($conn, $_POST['data_wygasniecia']);
        $gabaryt = mysqli_real_escape_string($conn, $_POST['gabaryt']);
        $link_zdjecie = mysqli_real_escape_string($conn, $_POST['link_zdjecie']);
        $kategoria = mysqli_real_escape_string($conn, $_POST['kategoria']);
        $status = $ilosc > 0 ? "dostępny" : "niedostępny";

        $query = "INSERT INTO produkty (tytul, opis, cena_netto, podatek_vat, ilosc_dostepnych_sztuk_w_magazynie, data_wygaśnięcia, gabaryt_produktu, link_zdjecie, kategoria, status_dostepnosci)
                  VALUES ('$tytul', '$opis', $cena_netto, $vat, $ilosc, '$data_wygasniecia', '$gabaryt', '$link_zdjecie', '$kategoria', '$status')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Produkt został dodany!');</script>";
            PokazProdukty(); // Wyświetlenie listy produktów
        } else {
            echo "<p style='color: red;'>Wystąpił błąd: " . mysqli_error($conn) . "</p>";
        }
    } else {
        // Formularz do dodania produktu
        echo "
        <h2>Dodaj Produkt</h2>
        <form method='post'>
            <label>Tytuł:</label><input type='text' name='tytul' required><br>
            <label>Opis:</label><textarea name='opis' required></textarea><br>
            <label>Cena netto:</label><input type='number' step='0.01' name='cena_netto' required><br>
            <label>VAT (%):</label><input type='number' step='0.01' name='vat' value='23.00'><br>
            <label>Ilość:</label><input type='number' name='ilosc' required><br>
            <label>Data wygaśnięcia:</label><input type='date' name='data_wygasniecia'><br>
            <label>Gabaryt:</label><input type='text' name='gabaryt'><br>
            <label>Link do zdjęcia:</label><input type='text' name='link_zdjecie'><br>
            <label>Kategoria:</label><input type='text' name='kategoria' required><br>
            <button type='submit'>Dodaj Produkt</button>
        </form>";
    }
    mysqli_close($conn);
}

function PokazProdukty() {
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM produkty";
    $result = mysqli_query($conn, $query);

    echo "<h2>Lista Produktów</h2>";
    echo "<a href='admin.php?akcja=dodajProdukt' style='display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;'>Dodaj Nowy Produkt</a>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Tytuł</th>
                <th>Opis</th>
                <th>Cena brutto</th>
                <th>Ilość</th>
                <th>Status</th>
                <th>Kategoria</th>
                <th>Zdjęcie</th>
                <th>Akcje</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $cena_brutto = $row['cena_netto'] * (1 + $row['podatek_vat'] / 100);
        $zdjecie = $row['link_zdjecie'] ? "<img src='" . $row['link_zdjecie'] . "' width='50'>" : "Brak zdjęcia";
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['tytul']}</td>
                <td>{$row['opis']}</td>
                <td>" . number_format($cena_brutto, 2) . " zł</td>
                <td>{$row['ilosc_dostepnych_sztuk_w_magazynie']}</td>
                <td>{$row['status_dostepnosci']}</td>
                <td>{$row['kategoria']}</td>
                <td>$zdjecie</td>
                <td>
                    <a href='admin.php?akcja=edytujProdukt&id={$row['id']}'>Edytuj</a> |
                    <a href='admin.php?akcja=usunProdukt&id={$row['id']}' onclick='return confirm(\"Czy na pewno?\")'>Usuń</a>
                </td>
              </tr>";
    }
    echo "</table>";
    mysqli_close($conn);
}

function EdytujProdukt($id) {
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
        $opis = mysqli_real_escape_string($conn, $_POST['opis']);
        $cena_netto = (float)$_POST['cena_netto'];
        $vat = (float)$_POST['vat'];
        $ilosc = (int)$_POST['ilosc'];
        $data_wygasniecia = mysqli_real_escape_string($conn, $_POST['data_wygasniecia']);
        $gabaryt = mysqli_real_escape_string($conn, $_POST['gabaryt']);
        $link_zdjecie = mysqli_real_escape_string($conn, $_POST['link_zdjecie']);
        $kategoria = mysqli_real_escape_string($conn, $_POST['kategoria']);
        $status = $ilosc > 0 ? "dostępny" : "niedostępny";

        $query = "UPDATE produkty SET tytul = '$tytul', opis = '$opis', cena_netto = $cena_netto, podatek_vat = $vat,
                  ilosc_dostepnych_sztuk_w_magazynie = $ilosc, data_wygaśnięcia = '$data_wygasniecia', gabaryt_produktu = '$gabaryt',
                  link_zdjecie = '$link_zdjecie', kategoria = '$kategoria', 
                  status_dostepnosci = '$status' WHERE id = $id LIMIT 1";

        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Produkt został zaktualizowany!');</script>";
            PokazProdukty(); // Wyświetlenie listy produktów
        } else {
            echo "<p style='color: red;'>Wystąpił błąd: " . mysqli_error($conn) . "</p>";
        }
    } else {
        $query = "SELECT * FROM produkty WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            $tytul = htmlspecialchars($row['tytul']);
            $opis = htmlspecialchars($row['opis']);
            $cena_netto = $row['cena_netto'];
            $vat = $row['podatek_vat'];
            $ilosc = $row['ilosc_dostepnych_sztuk_w_magazynie'];
            $data_wygasniecia = $row['data_wygaśnięcia'];
            $gabaryt = htmlspecialchars($row['gabaryt_produktu']);
            $link_zdjecie = htmlspecialchars($row['link_zdjecie']);
            $kategoria = htmlspecialchars($row['kategoria']);

            echo "
            <h2>Edytuj Produkt</h2>
            <form method='post'>
                <label>Tytuł:</label><input type='text' name='tytul' value='$tytul' required><br>
                <label>Opis:</label><textarea name='opis' required>$opis</textarea><br>
                <label>Cena netto:</label><input type='number' step='0.01' name='cena_netto' value='$cena_netto' required><br>
                <label>VAT (%):</label><input type='number' step='0.01' name='vat' value='$vat'><br>
                <label>Ilość:</label><input type='number' name='ilosc' value='$ilosc' required><br>
                <label>Data wygaśnięcia:</label><input type='date' name='data_wygasniecia' value='$data_wygasniecia'><br>
                <label>Gabaryt:</label><input type='text' name='gabaryt' value='$gabaryt'><br>
                <label>Link do zdjęcia:</label><input type='text' name='link_zdjecie' value='$link_zdjecie'><br>
                <label>Kategoria:</label><input type='text' name='kategoria' value='$kategoria'><br>
                <button type='submit'>Zapisz zmiany</button>
            </form>";
        }
    }
    mysqli_close($conn);
}

function UsunProdukt($id) {
    global $dbhost, $dbuser, $dbpass, $baza;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    $query = "DELETE FROM produkty WHERE id = $id LIMIT 1";
    if (mysqli_query($conn, $query)) {
        echo "<p style='color: green;'>Produkt został usunięty.</p>";
    } else {
        echo "<p style='color: red;'>Wystąpił błąd: " . mysqli_error($conn) . "</p>";
    }

    mysqli_close($conn);
}

function ZarzadzajProduktami() {
    $akcja = isset($_GET['akcja']) ? $_GET['akcja'] : 'pokazProdukty';
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    switch ($akcja) {
        case 'dodajProdukt':
            DodajProdukt();
            break;
        case 'edytujProdukt':
            if ($id > 0) {
                EdytujProdukt($id);
            } else {
                echo "<p style='color: red;'>Nie podano ID produktu do edycji.</p>";
            }
            break;
        case 'usunProdukt':
            if ($id > 0) {
                UsunProdukt($id);
            } else {
                echo "<p style='color: red;'>Nie podano ID produktu do usunięcia.</p>";
            }
            PokazProdukty(); // Po usunięciu wyświetlamy listę produktów
            break;
        default:
            PokazProdukty(); // Domyślnie pokazujemy listę produktów
            break;
    }
}

ZarzadzajProduktami();

?>


