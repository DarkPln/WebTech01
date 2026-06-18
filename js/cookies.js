// Cookies (hilfsfkten)
// Lukas

// Setzt  cookie mit name, wert und ablaufzeit 
function setCookie(name, value, days) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = name + '=' + encodeURIComponent(value) +
        '; expires=' + expires + '; path=/; SameSite=Lax';
}

// sucht Cookie anhand seines namens und returned wert 
function getCookie(name) {
    return document.cookie.split('; ').reduce(function(result, pair) {
        var parts = pair.split('=');
        return parts[0] === name ? decodeURIComponent(parts[1]) : result;
    }, null);
}

// prüft ob cookies im browser aktiviert sind 

function areCookiesEnabled() {
    // navigator.cookieEnabled ist unzuverlässig, bzw hat nicht immer funktioniert 
    if (!navigator.cookieEnabled) return false;


    // also test cookie setzen und schauen obs geht -> t/f 
    document.cookie = 'auto24_cookietest=1; path=/; SameSite=Lax';
    var works = document.cookie.indexOf('auto24_cookietest=') !== -1;
    if (works) {
        document.cookie = 'auto24_cookietest=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
    }
    return works;
}

// cookie Banner 

// Zeigt cookie-deaktiviert-Warnung oder consentbanner je nach browserzustand
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


// cookie akzeptiert; banner weg... cookie setzen 
function acceptCookies() {
    setCookie('auto24_consent', 'accepted', 365);
    var banner = document.getElementById('cookieConsentBanner');
    if (banner) banner.style.display = 'none';
    // Light/Dark-Modus-Einstellung jetzt als Cookie sichern
    var isLight = document.body.classList.contains('light-mode');

// Cookie für light/darkmode 
    setCookie('auto24_lightMode', isLight ? '1' : '0', 365);
}


// abgelehnte cookies 

function declineCookies() {
    setCookie('auto24_consent', 'declined', 30);
    var banner = document.getElementById('cookieConsentBanner');
    if (banner) banner.style.display = 'none';
}
