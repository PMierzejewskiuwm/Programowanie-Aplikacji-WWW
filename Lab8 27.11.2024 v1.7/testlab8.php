<?php

require_once 'contact.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['akcja']) && $_POST['akcja'] === 'wyslijKontakt') {
        wyslijMailKontakt("odbiorca@example.com"); 
    } elseif (isset($_POST['akcja']) && $_POST['akcja'] === 'przypomnijHaslo') {
        przypomnijHaslo($_POST['email'], "admin@example.com");
    }
} else {
    
   
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