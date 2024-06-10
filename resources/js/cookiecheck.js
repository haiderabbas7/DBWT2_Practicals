window.onload = function() {
    // Überprüfen, ob das Cookie bereits gesetzt ist
    if (!getCookie("cookieConsent")) {
        // Wenn das Cookie nicht gesetzt ist, Dialogfenster anzeigen
        let consent = confirm("Diese Website verwendet Cookies. Sind Sie damit einverstanden?");

        // Wenn der Benutzer auf "Akzeptieren" klickt, Cookie setzen
        if (consent === true) {
            setCookie("cookieConsent", "true", 365);
        }
    }
}

// Funktion zum Setzen eines Cookies
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
// Funktion zum Abrufen eines Cookies
function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i=0;i < ca.length;i++) {
        let c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
