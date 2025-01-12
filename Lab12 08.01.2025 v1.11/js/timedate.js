
// Funkcja: Pobieranie bieżącej daty

// Wyświetla bieżącą datę w formacie MM / DD / YY
function getTheDate() {
    // Tworzenie nowego obiektu Date
    var todays = new Date();

    // Formatowanie daty na MM / DD / YY
    var theDate = (todays.getMonth() + 1) + " / " + todays.getDate() + " / " + (todays.getFullYear() % 100);

    // Wyświetlenie daty w elemencie o id "data"
    document.getElementById("data").innerHTML = theDate;
}

// Zmienne globalne

var timerID = null;      // ID dla timera
var timerRunning = false; // Flaga określająca, czy zegar działa


// Funkcja: Zatrzymywanie zegara

// Zatrzymuje działanie zegara, jeśli jest włączony
function stopClock() {
    if (timerRunning) {
        clearTimeout(timerID); // Anulowanie działania timera
    }
    timerRunning = false;
}

// Funkcja: Uruchamianie zegara

// Uruchamia zegar, pokazuje datę i czas
function startClock() {
    stopClock();    // Zatrzymuje istniejący timer (jeśli działa)
    getTheDate();   // Pobiera i wyświetla aktualną datę
    showTime();     // Wyświetla bieżący czas i uruchamia timer
}

// Funkcja: Wyświetlanie bieżącego czasu

// Formatuje i wyświetla czas w formacie 12-godzinnym z AM/PM
function showTime() {
    var now = new Date();

    // Pobranie godzin, minut i sekund
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    // Formatowanie godzin (12-godzinny format)
    var timeValue = (hours > 12) ? hours - 12 : hours;
    timeValue = (timeValue === 0) ? 12 : timeValue; // Zastąp 0 godziną 12 w 12-godzinnym formacie
    timeValue += (minutes < 10 ? ":0" : ":") + minutes; // Dodanie minut z zerem wiodącym
    timeValue += (seconds < 10 ? ":0" : ":") + seconds; // Dodanie sekund z zerem wiodącym
    timeValue += (hours >= 12) ? " P.M." : " A.M."; // Dodanie AM/PM

    // Wyświetlenie czasu w elemencie o id "zegarek"
    document.getElementById("zegarek").innerHTML = timeValue;

    // Ustawienie timera na aktualizację co sekundę
    timerID = setTimeout(showTime, 1000);
    timerRunning = true;
}

// Funkcja: Przełączanie widoczności zegara

// Pokazuje lub ukrywa element zegara na stronie
function toggleClock() {
    var zegarek = document.getElementById("zegarek");

    // Przełączanie stanu wyświetlania elementu zegarka
    zegarek.style.display = (zegarek.style.display === "none") ? "block" : "none";
}
