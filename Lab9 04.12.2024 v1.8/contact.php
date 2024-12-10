<?php


// Funkcja: Wyświetlanie formularza kontaktowego

function pokazKontakt() {
    echo '
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formularz Kontaktowy</title>
        <link rel="stylesheet" href="css/style2.css">
    </head>
    <body>
        <form class="contact-form" action="" method="POST">
            <h1>Formularz Kontaktowy</h1>
            
            <label for="temat">Temat:</label>
            <input type="text" id="temat" name="temat" required>

            <label for="tresc">Treść wiadomości:</label>
            <textarea id="tresc" name="tresc" rows="5" required></textarea>

            <label for="email">Twój email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Wyślij</button>
        </form>
    </body>
    </html>';
}


// Funkcja: Przypomnienie hasła

// Wysyła nowe hasło użytkownikowi na podany adres email
function przypomnijHaslo($email, $odbiorca) {
    // Generowanie przykładowego nowego hasła
    $noweHaslo = 'kj2g45h9g'; // Przykładowe wygenerowane nowe hasło

    // Ustawienie danych do wysyłki
    $_POST['temat'] = "Przypomnienie hasła do panelu admina";
    $_POST['tresc'] = "Twoje nowe hasło do panelu admina to: " . $noweHaslo;
    $_POST['email'] = $email; // Email nadawcy

    // Wysłanie wiadomości za pomocą funkcji `wyslijMailaKontakt`
    wyslijMailaKontakt($odbiorca);

    // Informacja dla użytkownika
    echo 'Nowe hasło zostało wysłane na adres e-mail panelu admina.';
}


// Funkcja: Wysyłanie maila z formularza kontaktowego

// Wysyła dane wpisane w formularzu kontaktowym na podany adres odbiorcy
function wyslijMailaKontakt($odbiorca) {
    // Sprawdzenie, czy wszystkie pola są wypełnione
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '<p>Nie wypełniłeś wszystkich wymaganych pól!</p>';
        echo pokazKontakt(); // Ponowne wyświetlenie formularza
        return;
    }

    // Przygotowanie danych maila
    $mail['subject'] = htmlspecialchars($_POST['temat']); // Zabezpieczenie danych
    $mail['body'] = htmlspecialchars($_POST['tresc']);
    $mail['sender'] = htmlspecialchars($_POST['email']);
    $mail['recipient'] = $odbiorca;

    // Przygotowanie nagłówków wiadomości
    $header = "From: Formularz Kontaktowy <" . $mail['sender'] . ">\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: text/plain; charset=utf-8\r\n";
    $header .= "Content-Transfer-Encoding: 8bit\r\n";
    $header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $header .= "Return-Path: <" . $mail['sender'] . ">\r\n";

    // Wysyłka maila
    if (mail($mail['recipient'], $mail['subject'], $mail['body'], $header)) {
        echo '<p>Wiadomość została wysłana pomyślnie!</p>';
    } else {
        echo '<p>Wystąpił błąd podczas wysyłania wiadomości. Spróbuj ponownie później.</p>';
    }
}

// Główna logika strony

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obsługa akcji zależnych od wartości $_POST['akcja']
    if (isset($_POST['akcja']) && $_POST['akcja'] === 'wyslijKontakt') {
        wyslijMailKontakt("odbiorca@example.com"); 
    } elseif (isset($_POST['akcja']) && $_POST['akcja'] === 'przypomnijHaslo') {
        przypomnijHaslo($_POST['email'], "admin@example.com");
    }
} else {
    
   // Wyświetlenie formularzy dla GET
    pokazKontakt();

    echo '
    <link rel="stylesheet" href="css/password_reset.css"> <!-- Odwołanie do innego pliku CSS -->
    <form action="" method="POST" class="password-reset-form">
    <h1>Przypomnienie hasła</h1>
        <input type="hidden" name="akcja" value="przypomnijHaslo">
        <label for="email">Podaj swój email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Przypomnij hasło</button>
    </form>';
}

?>
