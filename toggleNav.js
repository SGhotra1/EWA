var nav,
    key;

function toggleNavbar() {
    "use strict";
    nav = document.getElementById("verlinkung");
    if (nav.style.display == "none") {
        nav.style.display = "inherit";
    } else {
        nav.style.display = "none";
    }
}


window.addEventListener("keyup", function (key) {
    "use strict";
    key = key.keyCode || key.charCode;
    if (key == 36) {
        toggleNavbar();
    }
});