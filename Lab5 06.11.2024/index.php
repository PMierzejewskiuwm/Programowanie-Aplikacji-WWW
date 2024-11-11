<?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Patryk Mierzejewski" />
    <title>Największe budynki świata</title>
    <script src="js/timedate.js" type="text/javascript"></script>
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        p {
            text-align: center;
            text-indent: 20px;   
        }
        table,tr,td {
            border: 1px solid black;
        }
        .myDiv {
            border: 5px outset rgb(145, 255, 0);
            background-color: lightblue;
            text-align: center;
            display: flex;
            justify-content: center;  
            align-items: center;      
            height: 20vh;
        }
        .myDiv2 {
            text-align: left;
            font-size: x-large;
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
<body onload="startclock()">
    <header>
        <h1><p>Strona poświęcona największym budynkom świata</p></h1>
    </header>
    <div class="centered">
    <b><i>Zmień kolor strony:</i></b>
<button onclick="toggleColorPicker()">Wybierz kolor strony</button>
<select id="colorPicker" onchange="changeBackground(this.value)">
        <option value="">-- Wybierz kolor --</option>
        <option value="#FFFFFF">Biały</option>
        <option value="#FF0000">Czerwony</option>
        <option value="#00FF00">Zielony</option>
        <option value="#0000FF">Niebieski</option>
        <option value="#FFFF00">Żółty</option>
        <option value="#FFA500">Pomarańczowy</option>
        <option value="#9933FF">Fioletowy</option>
</select>
      </div>

    <div class="myDiv">
        <table cellspacing="10">
            <tr style="background-color: brown;">
            <td><a href="index.php?idp=glowna">Strona Glowna</a></td>
            </tr>
            <tr style="background-color: aquamarine;">
            <td><a href="index.php?idp=podstrona1">Zjednoczone Emiraty Arabskie</a></td>
            <td><a href="index.php?idp=podstrona2">Chiny</a></td>
            <td><a href="index.php?idp=podstrona3">Malezja</a></td>
            <td><a href="index.php?idp=podstrona4">Stany Zjednoczone</a></td>
            <td><a href="index.php?idp=podstrona5">Singapur</a></td>
            <td><a href="index.php?idp=podstrona6">experimental</a></td>
            <td><a href="index.php?idp=filmy">Filmy</a></td>
            </tr>
            <tr style="background-color: crimson;">
                <td><a href="index.php?idp=kontakt">Kontakt</a></td>
            </tr>
        </table>
    </div>

    
    
    <?php
    if ($_GET['idp'] == '') {
        $strona = 'html/glowna.html';
    } elseif ($_GET['idp'] == 'glowna') {
      $strona = 'html/glowna.html';
  } 
  elseif ($_GET['idp'] == 'filmy') {
   $strona = 'html/filmy.html';
}
    elseif ($_GET['idp'] == 'podstrona1') {
        $strona = 'html/pierwsza.html';
    } elseif ($_GET['idp'] == 'podstrona2') {
        $strona = 'html/druga.html';
    } elseif ($_GET['idp'] == 'podstrona3') {
        $strona = 'html/trzecia.html';
    } elseif ($_GET['idp'] == 'podstrona4') {
        $strona = 'html/czwarta.html';
    } elseif ($_GET['idp'] == 'podstrona5') {
        $strona = 'html/piata.html';
    } elseif ($_GET['idp'] == 'podstrona6') {
        $strona = 'html/szosta.html';
    } elseif ($_GET['idp'] == 'kontakt') {
      $strona = 'html/kontakt.html';
    } else {
        $strona = 'html/glowna.html'; 
    }
?>
<div class="content">
    <?php
        if (file_exists($strona)) {
            include($strona);
        } else {
            echo "<p>Przepraszamy, nie znaleziono strony.</p>";
        }
    ?>
</div>
</body>
</html>
