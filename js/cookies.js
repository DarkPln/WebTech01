// Cookies hilfsfkten
// Lukas

// Setzt  Cookie mit name, wert und ablaufzeit in Tagen
function setCookie(name, value, days) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = name + '=' + encodeURIComponent(value) +
        '; expires=' + expires + '; path=/; SameSite=Lax';
}

// Liest den Wert eines Cookies anhand seines Namens (null wenn nicht vorhanden)
function getCookie(name) {
    return document.cookie.split('; ').reduce(function(result, pair) {
        var parts = pair.split('=');
        return parts[0] === name ? decodeURIComponent(parts[1]) : result;
    }, null);
}

// Prüft ob Cookies im Browser aktiviert sind
function areCookiesEnabled() {
    // navigator.cookieEnabled ist unzuverlässig: moderne Browser liefern oft
    // weiterhin "true", selbst wenn Cookies (z.B. über die Datenschutz-
    // Einstellungen) tatsächlich blockiert sind. Daher zusätzlich aktiv
    // testen, ob ein Cookie wirklich gespeichert werden kann.
    if (!navigator.cookieEnabled) return false;

    document.cookie = 'auto24_cookietest=1; path=/; SameSite=Lax';
    var works = document.cookie.indexOf('auto24_cookietest=') !== -1;
    if (works) {
        document.cookie = 'auto24_cookietest=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
    }
    return works;
}

// cookie Banner 

// Zeigt Cookie-Deaktiviert-Warnung oder Consent-Banner je nach Browserzustand
function initCookieBanner() {
    var disabledBanner = document.getElementById('cookieDisabledBanner');
    var consentBanner  = document.getElementById('cookieConsentBanner');

    if (!areCookiesEnabled()) {
        if (disabledBanner) disabledBanner.style.display = 'block';
        if (consentBanner)  consentBanner.style.display  = 'none';
        return;
    }

    if (!consentBanner) return;
    if (!getCookie('auto24_consent')) {
        consentBanner.style.display = 'flex';
    }
}

function acceptCookies() {
    setCookie('auto24_consent', 'accepted', 365);
    var banner = document.getElementById('cookieConsentBanner');
    if (banner) banner.style.display = 'none';
    // Light/Dark-Modus-Einstellung jetzt als Cookie sichern
    var isLight = document.body.classList.contains('light-mode');
    setCookie('auto24_lightMode', isLight ? '1' : '0', 365);
}

function declineCookies() {
    setCookie('auto24_consent', 'declined', 30);
    var banner = document.getElementById('cookieConsentBanner');
    if (banner) banner.style.display = 'none';
}
