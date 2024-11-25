<?php
function PokazPodstrone($id) {
    
    include('cfg.php');
    
    //czyscimy $id, aby przez GET ktoś nie próbował wykonać ataku SQL INJECTION
    $id_clear = htmlspecialchars($id);

    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);

    //wywoływanie strony z bazy
    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }

    return $web;
}
?>