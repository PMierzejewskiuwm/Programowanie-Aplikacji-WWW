var obliczone = false;
var liczba = 0;
function convert (entryform, from, to)
{
    convertfrom = from.selectedIndex;
    convertto = to.selectedIndex;
    entryform.display.value = (entryform.input.value * from[convertfrom].value / to[convertto].value);
}

function addChar (input, character)
{
    if((character == '.' && decimal == 0) || character != '.')
    {
        (input.value == "" || input.value == "0") ? input.value = character : input.value += character
        convert(input.form, input.form.measure1, input.form.measure2)
        obliczone = true;
        if(character == '.')
        {
            liczba = 1;
        }
    }
}

function openVothcom()
{
    window.open("","Display window","toolbar=no,directories=no,menubar=no");
}

function clear (form)
{
    form.input.value = 0;
    form.display.value = 0;
    decimal = 0;
}

function zamianaTla(number)
{
    document.bgColor = number;
}
