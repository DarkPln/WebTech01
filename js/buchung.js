//Tim

// Sendet eine neue Buchung an den Server und gibt die Antwort zurück
async function createBooking(carId, carName, carPrice) {
    const antwort = await fetch(BASE_URL + '/api/bookings/create', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ carId, carName, carPrice })
    });
    return antwort.json();
}

// Markiert eine Buchung als storniert
async function cancelBooking(buchungsId) {
    const antwort = await fetch(BASE_URL + '/api/bookings/cancel', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id: buchungsId })
    });
    return antwort.json();
}

// Fragt den Nutzer nach Bestätigung und storniert dann die Buchung
async function handleCancelBooking(buchungsId) {
    if (!confirm('Buchung wirklich stornieren?')) return;
    const ergebnis = await cancelBooking(buchungsId);
    if (ergebnis.success) await renderBookingsPage();
}

// Gemeinsamer Click-Handler für alle Buchen-Buttons
async function handleBuchungsKlick(carId, carName, carPrice) {
    if (!confirm('Möchten Sie "' + carName + '" jetzt buchen?')) return;
    const ergebnis = await createBooking(carId, carName, carPrice);
    if (ergebnis.success) {
        window.location.href = BASE_URL + '/bookings';
    } else {
        alert(ergebnis.message || 'Buchung fehlgeschlagen.');
    }
}

// Prüft Auth-Status und gibt eine Hinweismeldung zurück, falls Buchen nicht erlaubt ist
function getBuchungsHinweis() {
    if (!authState.loggedIn) return 'Bitte einloggen, um dieses Fahrzeug zu buchen.';
    if (authState.isLocked)  return 'Ihr Konto ist vom Administrator gesperrt.';
    return null;
}

// Lukas

// Buchen-Button auf der Fahrzeugdetailseite (ID "buchungsBtn")
function initBookingButton() {
    var btn = document.getElementById('buchungsBtn');
    if (!btn) return;

    var hinweis = document.getElementById('buchungsNote');
    var fehler  = getBuchungsHinweis();

    if (fehler) {
        btn.disabled = true;
        if (hinweis) { hinweis.textContent = fehler; hinweis.style.display = 'block'; }
        return;
    }

    btn.addEventListener('click', function() {
        handleBuchungsKlick(btn.dataset.carId, btn.dataset.carName, btn.dataset.carPrice);
    });
}

// Buchen-Buttons in der Merkliste (Klasse "buchungsBtn", mehrere möglich) – Lukas
function initBookingButtons() {
    var buttons = document.querySelectorAll('.buchungsBtn');
    if (!buttons.length) return;

    var fehler = getBuchungsHinweis();

    buttons.forEach(function(btn) {
        if (fehler) {
            btn.disabled = true;
            btn.title    = fehler;
            return;
        }
        btn.addEventListener('click', function() {
            handleBuchungsKlick(btn.dataset.carId, btn.dataset.carName, btn.dataset.carPrice);
        });
    });
}

// Buchungsseite initialisieren: Weiterleitung wenn nicht eingeloggt, sonst Buchungen anzeigen
// Nur auf /bookings aktiv, nicht im User-Dashboard (dort lädt der Tab-Klick die Buchungen)
async function initBookingsPage() {
    var container = document.getElementById('buchungenContainer');
    if (!container) return;
    if (!document.getElementById('buchungenUsername')) return;

    if (!authState.loggedIn) {
        window.location.href = BASE_URL + '/auth/login';
        return;
    }

    var nutzernameAnzeige = document.getElementById('buchungenUsername');
    if (nutzernameAnzeige) nutzernameAnzeige.textContent = authState.username;

    await renderBookingsPage();
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
async function renderBookingsPage() {
    var container = document.getElementById('buchungenContainer');
    if (!container) return;
    const antwort       = await fetch(BASE_URL + '/api/bookings/list-html');
    container.innerHTML = await antwort.text();
}
