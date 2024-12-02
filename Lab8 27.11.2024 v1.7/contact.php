<?php

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


function przypomnijHaslo($email, $odbiorca) {
    // Przykładowe nowe hasło (może być generowane dynamicznie)
    $noweHaslo = '12345'; // W praktyce należy je dynamicznie generować i przechowywać w bazie danych

    // Treść wiadomości
    $_POST['temat'] = "Przypomnienie hasła do panelu admina";
    $_POST['tresc'] = "Twoje nowe hasło do panelu admina to: " . $noweHaslo;
    $_POST['email'] = $email; // Nadawca (np. użytkownik proszący o hasło)

    // Wywołanie metody wyslijMailKontakt() do wysłania wiadomości
    wyslijMailKontakt($odbiorca);

    echo 'Nowe hasło zostało wysłane na adres e-mail panelu admina.';
}

// -----------------------------------------------------------------
// wysylanie danych poprzez formularz kontaktowy
// -----------------------------------------------------------------
// przesyla dane z formularza kontaktowego
function wyslijMailaKontakt($odbiorca)
{
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email']))
    {
        echo [nie_wypelniles_pola];
        echo pokazKontakt(); // ponowne wywolanie formularza
    }
    else
    {
        $mail['subject'] = $_POST['temat'];
        $mail['body'] = $_POST['tresc'];
        $mail['sender'] = $_POST['email'];
        $mail['recipient'] = $odbiorca; // czyli my jestesmy odbiorca, jezeli tworzymy formularz kontaktowy

        $header = "From: Formularz kontaktowy <". $mail['sender'] .">.\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: ";
        $header .= "8bit\nX-Mailer: Rpawnik mail 1.2\n";
        $header .= "X-Priority: 3\n";
        $header .= "Return-Path: <". $mail['sender'] .">.\n";

        mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

        echo ['wiadomosc_wyslana'];
    }
}
?>