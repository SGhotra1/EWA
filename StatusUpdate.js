var jstring = '{"pizzaname":"Salami","status":"Im Ofen","bestellstatus":"Unterwegs"}';
var request = new XMLHttpRequest();



function updateBestellungStatus(jsonParse) {
    "use strict";
    var bstatus = document.getElementsByClassName("bstatus"),
        i;
    bstatus[0].textContent = jsonParse[0].bestellstatus;
}

function process(jsonString) {
    "use strict";
    var jsonParse = JSON.parse(jsonString);
    if (jsonString.length > 2) { // Wegen []
        updateBestellungStatus(jsonParse);
    }
}

function processData() {
    if (request.readyState == 4) { // Uebertragung = DONE
        if (request.status == 200) {   // HTTP-Status = OK
            if (request.responseText != null) {
                process(request.responseText);// Daten verarbeiten
            } else {
                console.error("Dokument ist leer");
            }
        } else {
            console.error("Uebertragung fehlgeschlagen");
        }
    } else;    // Uebertragung laeuft noch
}

function requestData() { // fordert die Daten asynchron an
    request.open("GET", "KundenStatus.php"); // URL für HTTP-GET
    request.onreadystatechange = processData; //Callback-Handler zuordnen
    request.send(null); // Request abschicken
}


//function onload() {
//    "use strict";
//    process(jstring);
//}


window.onload = function () {
    window.setInterval(requestData, 2000);
    
}

//window.onload = function onloadtest() {
//    window.setInterval(requestData,2000);
//}