<?php
function PokazPodstrone($id) {
    
    include('cfg.php'); // Wczytanie konfiguracji bazy danych
    
    // Czyscimy $id, aby przez GET ktoś nie próbował wykonać ataku SQL INJECTION
    $id_clear = htmlspecialchars($id);

    // Przygotowanie zapytania SQL z limitem
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);

    // Sprawdzenie, czy zawartość strony została znaleziona
    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }

    return $web;  // Zwrócenie zawartości strony
}
?>