//Tim

// Sendet eine neue Buchung an den Server und gibt die Antwort zurück
function createBooking(carId, carName, carPrice) {
    return fetch(BASE_URL + '/api/bookings/create', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ carId, carName, carPrice })
    }).then(antwort => antwort.json());
}

// Markiert eine Buchung als storniert
function cancelBooking(buchungsId) {
    return fetch(BASE_URL + '/api/bookings/cancel', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id: buchungsId })
    }).then(antwort => antwort.json());
}

// Fragt den Nutzer nach Bestätigung und storniert dann die Buchung
function handleCancelBooking(buchungsId) {
    if (!confirm('Buchung wirklich stornieren?')) return;
    cancelBooking(buchungsId).then(ergebnis => {
        if (ergebnis.success) return renderBookingsPage();
    });
}

// Gemeinsamer Click-Handler für alle Buchen-Buttons
function handleBuchungsKlick(carId, carName, carPrice) {
    if (!confirm('Möchten Sie "' + carName + '" jetzt buchen?')) return;

    // TIMER-IDEE: Hier könnte ein Reservierungs-Countdown starten (z.B. 10 Minuten).
    // Solange der Timer läuft, gilt das Fahrzeug als "reserviert" und andere User
    // sehen es als nicht mehr verfügbar. Bei Ablauf: Buchung abbrechen + User benachrichtigen.
    //
    // let seconds = 600;
    // const timerId = setInterval(() => {
    //     seconds--;
    //     document.getElementById('buchungsTimer').textContent =
    //         `${Math.floor(seconds / 60)}:${String(seconds % 60).padStart(2, '0')}`;
    //     if (seconds <= 0) {
    //         clearInterval(timerId);
    //         alert('Zeit abgelaufen – Reservierung wurde aufgehoben.');
    //         window.location.reload();
    //     }
    // }, 1000);

    createBooking(carId, carName, carPrice).then(ergebnis => {
        if (ergebnis.success) {
            window.location.href = BASE_URL + '/bookings';
        } else {
            alert(ergebnis.message || 'Buchung fehlgeschlagen.');
        }
    });
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

// Buchen-Buttons in der Merkliste (Klasse "buchungsBtn", mehrere möglich) - Lukas
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
function initBookingsPage() {
    var container = document.getElementById('buchungenContainer');
    if (!container) return Promise.resolve();
    if (!document.getElementById('buchungenUsername')) return Promise.resolve();

    if (!authState.loggedIn) {
        window.location.href = BASE_URL + '/auth/login';
        return Promise.resolve();
    }

    var nutzernameAnzeige = document.getElementById('buchungenUsername');
    if (nutzernameAnzeige) nutzernameAnzeige.textContent = authState.username;

    return renderBookingsPage();
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
function renderBookingsPage() {
    var container = document.getElementById('buchungenContainer');
    if (!container) return Promise.resolve();
    return fetch(BASE_URL + '/api/bookings/list-html')
        .then(antwort => antwort.text())
        .then(text => { container.innerHTML = text; });
}
