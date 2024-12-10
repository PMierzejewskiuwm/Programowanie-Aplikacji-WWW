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
ListaPodstron();


// Obsługa akcji: edytuj, dodaj, usuń

if (isset($_GET['akcja'])) {
    $akcja = htmlspecialchars($_GET['akcja']);
    if ($akcja == 'edytuj' && isset($_GET['id'])) {
        $id = (int)$_GET['id']; // Bezpieczna konwersja na integer
        EdytujPodstrone($id);
    } elseif ($akcja == 'dodaj') {
        DodajNowaPodstrone();
    } elseif ($akcja == 'usun' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        UsunPodstrone($id);
    }
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
    $query = "SELECT id, page_title FROM page_list ORDER BY id ASC LIMIT 10";
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
        // Bezpieczne pobranie i przetworzenie danych
        $title = mysqli_real_escape_string($conn, $_POST['page_title']);
        $content = mysqli_real_escape_string($conn, $_POST['page_content']);
        $status = isset($_POST['status']) ? 1 : 0;

        // Wstawianie danych
        $insert_query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $status)";
        if (mysqli_query($conn, $insert_query)) {
            echo "<p style='color: green;'>Nowa podstrona została dodana.</p>";
        } else {
            echo "<p style='color: red;'>Wystąpił błąd podczas dodawania podstrony.</p>";
        }
        ListaPodstron();
    } else {
        // Formularz dodawania
        echo "
        <h2>Dodaj nową podstronę</h2>
        <form method='post' action=''>
            <table>
                <tr><td>Tytuł: </td><td><input type='text' name='page_title' required /></td></tr>
                <tr><td>Treść: </td><td><textarea name='page_content' required></textarea></td></tr>
                <tr><td>Aktywna: </td><td><input type='checkbox' name='status' /> Tak</td></tr>
                <tr><td>&nbsp;</td><td><input type='submit' value='Dodaj podstronę' /></td></tr>
            </table>
        </form>";
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
        } else {
            echo "<p style='color: red;'>Wystąpił błąd podczas aktualizacji: " . mysqli_error($conn) . "</p>";
        }
        ListaPodstron();
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

    mysqli_close($conn);
}
?>
