var warenkorb = [];
var gesamtPreis = 0.0;

function addierenPreise(element) {
    "use strict";
    gesamtPreis += parseFloat(element.preis);
}

function updateGesamtPreis() {
    "use strict";
    gesamtPreis = 0.0;
    warenkorb.forEach(addierenPreise);
    var summeSite = document.getElementById("gesamtPreis");
    summeSite.innerText = "Gesamtpreis: " + gesamtPreis.toFixed(2) + "€";
}

/*function getGesamtPreis() {
    "use strict";
    warenkorb.forEach(addierenPreise);
    return gesamtPreis.toFixed(2);
}*/
function hinzufuegenWarenkorb(pizza) {
    "use strict";
    var pJSON = JSON.parse(pizza),
        wkSite = document.getElementById("warenkorb"),
        newOption = document.createElement("option");
    warenkorb.push(pJSON);
    newOption.nodeValue = pJSON.pizzaname;
    newOption.innerText = pJSON.pizzaname;
    wkSite.append(newOption);
    
    updateGesamtPreis();
}

function checkBestellung() {
    "use strict";
    var bestellbutton = document.getElementById("bestellen"),
        wkSite = document.getElementById("warenkorb"),
        adresse = document.getElementById("adresse"),
        plz = document.getElementById("plz"),
        name = document.getElementById("name");
    if (wkSite.options.length <= 0 || adresse.value.length <= 0 || plz.value.length <= 4 || name.value.length <= 0) {
        bestellbutton.disabled = true;
    } else {
        bestellbutton.disabled = false;
    }
}

document.addEventListener("click", function () {
    "use strict";
    checkBestellung();
});

function entfernenWarenkorb() {
    "use strict";
    var wkSite = document.getElementById("warenkorb"),
        i;
    for (i = wkSite.options.length - 1; i >= 0; i -= 1) {
        if (wkSite.options[i].selected) {
            wkSite.remove(i);
            warenkorb.splice(i, 1);
        }
    }
    updateGesamtPreis();
    checkBestellung();
}

function leereWarenkorb() {
    "use strict";
    var wkSite = document.getElementById("warenkorb"),
        i;
    for (i = wkSite.options.length - 1; i >= 0; i -= 1) {
        wkSite.remove(i);
        warenkorb.splice(i, 1);
    }
    updateGesamtPreis();
    checkBestellung();
}

function bestellungSelectAll() {
    "use strict";
    var wkSite = document.getElementById("warenkorb"),
        i;
    for (i = 0; i < wkSite.options.length; i += 1) {
        wkSite.options[i].selected = true;
    }
}