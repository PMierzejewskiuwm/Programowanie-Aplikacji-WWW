
// Deklaracja zmiennych globalnych

var computed = false; // Czy wynik został obliczony
var decimal = 0;      // Flaga określająca, czy wprowadzono kropkę dziesiętną


// Funkcja: Konwersja jednostek

// Przekształca wartość na podstawie wyboru jednostek wejściowych i wyjściowych
function convert(entryform, from, to) {
    let convertFrom = from.selectedIndex; // Indeks wybranej jednostki wejściowej
    let convertTo = to.selectedIndex;     // Indeks wybranej jednostki wyjściowej

    // Obliczenie przeliczonej wartości
    entryform.display.value = 
        (entryform.input.value * from[convertFrom].value / to[convertTo].value);
}


// Funkcja: Dodawanie znaków do pola wejściowego

// Dodaje znak (cyfrę lub kropkę dziesiętną) do pola wejściowego.
// Jeśli dodawana jest kropka, upewnia się, że można ją wstawić tylko raz.
function addChar(input, character) {
    // Sprawdza, czy można dodać kropkę dziesiętną lub dowolny inny znak
    if ((character === '.' && decimal === 0) || character !== '.') {
        // Aktualizuje wartość pola wejściowego
        input.value = (input.value === "" || input.value === "0") ? character : input.value + character;

        // Przeprowadza automatyczną konwersję po dodaniu znaku
        convert(input.form, input.form.measure1, input.form.measure2);
        computed = true;

        // Ustawia flagę kropki dziesiętnej
        if (character === '.') {
            decimal = 1;
        }
    }
}


// Funkcja: Otwórz nowe okno

// Otwiera nowe okno przeglądarki z określonymi ustawieniami
function openVothcom() {
    window.open("", "Display window", "toolbar=no,directories=no,menubar=no");
}


// Funkcja: Czyszczenie formularza

// Resetuje wartości pola wejściowego i pola wyświetlania, usuwa flagę kropki dziesiętnej
function clear(form) {
    form.input.value = 0;    // Resetuje wartość wejściową
    form.display.value = 0;  // Resetuje wartość wyświetlaną
    decimal = 0;             // Usuwa flagę kropki dziesiętnej
}


// Funkcja: Zmiana koloru tła

// Ustawia kolor tła strony na podstawie podanego kodu koloru
function changeBackground(hexNumber) {
    document.bgColor = hexNumber;
}


// Funkcja: Przełączanie selektora kolorów

// Pokazuje lub ukrywa selektor kolorów na stronie
function toggleColorPicker() {
    var colorPicker = document.getElementById("colorPicker");

    // Sprawdza aktualny stan wyświetlania i przełącza go
    colorPicker.style.display = (colorPicker.style.display === "none") ? "block" : "none";
}
