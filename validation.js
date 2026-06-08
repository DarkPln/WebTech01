// Formularvalidierung für Auto24
// Autor: tim
// ===== GEMEINSAME VALIDIERUNGSFUNKTIONEN =====

// Gibt ein Array mit Fehlermeldungen zurück, leeres Array bedeutet "gültig".
function validateUsername(value) {
    const errors = [];
    if (value.length < 5) errors.push('Mindestens 5 Zeichen erforderlich');
    if (value === value.toUpperCase()) errors.push('Mindestens ein Kleinbuchstabe erforderlich');
    if (value === value.toLowerCase()) errors.push('Mindestens ein Großbuchstabe erforderlich');
    return errors;
}

// Selbes Prinzip wie validateUsername – nur Längenprüfung, leicht erweiterbar.
function validatePassword(value) {
    const errors = [];
    if (value.length < 10) errors.push('Mindestens 10 Zeichen erforderlich');
    return errors;
}

// Strikter Vergleich (===) prüft Wert UND Typ – verhindert ungewollte Typenumwandlung.
function validatePasswordMatch(password, passwordRepeat) {
    if (password !== passwordRepeat) return ['Passwörter stimmen nicht überein'];
    return [];
}

// Nimmt ein Eingabefeld, eine Validator-Funktion und optional ein Zusatzargument (z.B. das
// erste Passwort beim Match-Check). Setzt CSS-Klassen und zeigt Fehlertexte an.
function validateField(field, validator, extraArg) {
    // extraArg !== undefined prüft ob ein Zusatzargument übergeben wurde.
    // Ternärer Operator (? :) wählt dann die passende Variante aus.
    const errors = extraArg !== undefined ? validator(field.value, extraArg)  : validator(field.value);           // z.B. validateUsername(wert) // z.B. validatePasswordMatch(wert, erstesPasswort) 
    //andere schreibweise für if/else (wenn extraarg nicht null, dann passwort mtach sonst Username validierung)
    // Sucht den zugehörigen Fehler-<span> anhand der Feld-ID.
    // Feld-ID "passwort" -> Span-ID "passwort-error".
    const errorSpan = document.getElementById(field.id + '-error');

    // Beide Klassen zuerst entfernen, damit nie beide gleichzeitig gesetzt sind.
    field.classList.remove('valid', 'invalid');

    // Fall 1: Es gibt Fehler → Feld rot markieren und ersten Fehler anzeigen.
    if (errors.length > 0) {
        field.classList.add('invalid');
        if (errorSpan) errorSpan.textContent = errors[0]; //error span, fehlertext anzeigen
        return false;
    }

    // Fall 2: Keine Fehler und Feld hat Inhalt → Feld grün markieren.
    if (field.value.length > 0) {
        field.classList.add('valid');
        if (errorSpan) errorSpan.textContent = ''; //kein fehler anzeigen
        return true;
    }

    // Fall 3: Feld ist leer → keine Markierung, kein Fehlertext.
    if (errorSpan) errorSpan.textContent = '';
    return false;
}

// ===== NUTZERVERWALTUNG (localStorage) ===== TIm

// localStorage ist ein persistenter Schlüssel-Wert-Speicher im Browser
// Daten bleiben nach dem Schließen des Tabs erhalten
// || [] Fallback liefert ein leeres Array, wenn der Schlüssel noch nicht existiert, nueer Nutzer
function getUsers() {
    return JSON.parse(localStorage.getItem('auto24_users') || '[]'); //verwandelt den String zurück in ein JS-Array, damit wir damit arbeiten können
}

// JSON.stringify() serialisiert das JS-Array in einen String, der im localStorage abgelegt wird.
function saveUsers(users) {
    localStorage.setItem('auto24_users', JSON.stringify(users)); //speichert das Array als String im localStorage unter dem Schlüssel 'auto24_users'
}

// Array.find() gibt das erste Element zurück, für das die Callback-Funktion true ergibt.
// Arrow-Function als Callback: u => u.username === username
// || null stellt sicher, dass der Rückgabewert explizit null ist (nicht undefined).
function findUser(username) {
    return getUsers().find(u => u.username === username) || null;
}

// ===== REGISTRIERUNGS-FORMULAR =====

// Initialisierungsfunktion: prüft zuerst, ob das Formular auf dieser Seite existiert.
// Das ermöglicht es, dieses Skript auf allen Seiten einzubinden, ohne Fehler.
// Alle DOM-Referenzen werden einmalig abgefragt (Performance) und in Konstanten gespeichert.
function initRegistrationForm() {
    const form = document.getElementById('registrationForm');
    if (!form) return;

    const benutzername = document.getElementById('benutzername');
    const passwort = document.getElementById('passwort');
    const passwortWiederholen = document.getElementById('passwort_wiederholen');
    const submitBtn = document.getElementById('submitBtn');
    const errorMessage = document.getElementById('reg-error');

    // Variablen (benutzername, passwort, etc.)
    // && verknüpft alle Bedingungen: nur wenn alle true sind, ist isValid = true.
    // submitBtn.disabled = !isValid deaktiviert/aktiviert den Button direkt.
    function checkFormValidity() {
        submitBtn.disabled =
            validateUsername(benutzername.value).length > 0 ||
            validatePassword(passwort.value).length > 0 ||
            validatePasswordMatch(passwort.value, passwortWiederholen.value).length > 0;
    }

    // 'input'-Event bei jeder Tasteneingabe, ermöglicht Live-Validierung.
    benutzername.addEventListener('input', () => {
        validateField(benutzername, validateUsername);
        if (errorMessage) errorMessage.style.display = 'none';
        checkFormValidity();
    });

    // Passwort-Wiederholung wird nur erneut geprüft, wenn sie bereits einen Wert hat –
    // verhindert eine Fehlermeldung bevor der Nutzer das Feld überhaupt berührt hat.
    passwort.addEventListener('input', () => {
        validateField(passwort, validatePassword);
        if (passwortWiederholen.value.length > 0)
            validateField(passwortWiederholen, validatePasswordMatch, passwort.value);
        checkFormValidity();
    });

    passwortWiederholen.addEventListener('input', () => {
        validateField(passwortWiederholen, validatePasswordMatch, passwort.value);
        checkFormValidity();
    });

    // e.preventDefault() verhindert den nativen Browser-Submit (der die Seite neu laden würde).
    // Stattdessen speichern wir den Nutzer manuell in localStorage.
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (submitBtn.disabled) return;

        const username = benutzername.value.trim();

        if (findUser(username)) {
            if (errorMessage) {
                errorMessage.textContent = 'Dieser Benutzername ist bereits vergeben.';
                errorMessage.style.display = 'block';
            }
            return;
        }

        const users = getUsers();
        users.push({ username, password: passwort.value });
        saveUsers(users);
        window.location.href = 'login.php?registered=1';
    });

    submitBtn.disabled = true;
}

// ===== LOGIN-FORMULAR =====

function initLoginForm() {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return;

    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const loginBtn = document.getElementById('loginBtn');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');

    // Kurzschreibweise: loginBtn.disabled ist direkt das Ergebnis des booleschen Ausdrucks.
    function checkFormValidity() {
        loginBtn.disabled = username.value.trim().length === 0 || password.value.length === 0;
    }

    username.addEventListener('input', () => {
        if (errorMessage) errorMessage.style.display = 'none';
        checkFormValidity();
    });
    password.addEventListener('input', () => {
        if (errorMessage) errorMessage.style.display = 'none';
        checkFormValidity();
    });

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('registered') === '1' && successMessage) {
        successMessage.textContent = 'Registrierung erfolgreich! Bitte jetzt einloggen.';
        successMessage.style.display = 'block';
    }

    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (loginBtn.disabled) return;

        const uname = username.value.trim();
        const pwd = password.value;
        const user = findUser(uname);
        // Hardcoded Demo-Zugangsdaten als Fallback für Tests ohne vorherige Registrierung.
        const isDemo = uname === 'TestUser' && pwd === 'TestPass123';
        //? ob user null ist
        const isAdmin = uname === ADMIN_CREDENTIALS.username && pwd === ADMIN_CREDENTIALS.password;
        if (isAdmin) {
            localStorage.setItem('auto24_adminLoggedIn', 'true');
            window.location.href = 'admin.php';
        } else if (user?.password === pwd || isDemo) {
            localStorage.setItem('loggedIn', 'true');
            localStorage.setItem('loggedInUser', uname);
            window.location.href = 'user.php';
        } else {
            if (errorMessage) {
                errorMessage.textContent = 'Falscher Benutzername oder Passwort.';
                errorMessage.style.display = 'block';
            }
        }
    });

    loginBtn.disabled = true;
}

// ===== NUTZERBEREICH =====

// Auth-Guard: Diese Prüfung blockiert den gesamten Nutzerbereich für nicht eingeloggte Besucher.
// localStorage.getItem() gibt null zurück, wenn der Schlüssel nicht existiert – daher !== 'true'.
// window.location.href leitet sofort weiter; return beendet die Funktion danach.
function initUserForm() {
    if (!document.getElementById('userForm')) return;

    if (localStorage.getItem('loggedIn') !== 'true') {
        window.location.href = 'login.html';
        return;
    }

    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    const saveBtn = document.getElementById('saveBtn');
    const displayName = document.getElementById('display-username');
    const saveSuccess = document.getElementById('saveSuccess');

    // || '' verhindert null als Wert – falls kein Nutzer gespeichert ist, wird ein leerer String verwendet.
    const loggedInUser = localStorage.getItem('loggedInUser') || '';

    // Vorausfüllen des Formularfelds mit dem aktuell eingeloggten Benutzernamen.
    // displayName.textContent schreibt reinen Text in den <span> – sicherer als innerHTML (kein XSS-Risiko).
    if (usernameInput) usernameInput.value = loggedInUser;
    if (displayName) displayName.textContent = loggedInUser;

    function checkFormValidity() {
        saveBtn.disabled =
            validateUsername(usernameInput.value).length > 0 ||
            validatePassword(passwordInput.value).length > 0 ||
            validatePasswordMatch(passwordInput.value, passwordConfirm.value).length > 0;
    }

    usernameInput.addEventListener('input', () => {
        validateField(usernameInput, validateUsername);
        checkFormValidity();
    });
    passwordInput.addEventListener('input', () => {
        validateField(passwordInput, validatePassword);
        if (passwordConfirm.value.length > 0)
            validateField(passwordConfirm, validatePasswordMatch, passwordInput.value);
        checkFormValidity();
    });
    passwordConfirm.addEventListener('input', () => {
        validateField(passwordConfirm, validatePasswordMatch, passwordInput.value);
        checkFormValidity();
    });

    document.getElementById('userForm').addEventListener('submit', (e) => {
        e.preventDefault();
        if (saveBtn.disabled) return;

        const newUsername = usernameInput.value.trim();
        const newPassword = passwordInput.value;

        // Array.findIndex() sucht nach dem alten Benutzernamen im Array und gibt dessen Index zurück.
        // -1 bedeutet "nicht gefunden", nur dann updaten, wenn der Nutzer existiert.
        // users[idx] = {...} überschreibt den Eintrag im Array; saveUsers() persistiert das Ergebnis.
        const users = getUsers();
        const idx = users.findIndex(u => u.username === loggedInUser);
        if (idx !== -1) {
            users[idx] = { username: newUsername, password: newPassword };
            saveUsers(users);
        }

        localStorage.setItem('loggedInUser', newUsername);
        if (displayName) displayName.textContent = newUsername;

        // setTimeout(callback, 3000) führt die Funktion nach 3000ms (3s) asynchron aus –
        // der restliche Code läuft sofort weiter, die Ausblendung erfolgt zeitverzögert.
        if (saveSuccess) {
            saveSuccess.textContent = 'Änderungen erfolgreich gespeichert!';
            saveSuccess.style.display = 'block';
            setTimeout(() => saveSuccess.style.display = 'none', 3000);
        }
    });

    saveBtn.disabled = true;
    renderUserInserate();
}

function renderUserInserate() {
    var container = document.getElementById('userInserate');
    if (!container) return;
    var user = localStorage.getItem('loggedInUser') || '';
    var inserate = getInserate().filter(function(i) { return i.userId === user; });
    if (inserate.length === 0) {
        container.innerHTML = '<p style="color:#888; font-size:14px;">Sie haben noch keine Inserate eingereicht.</p>';
        return;
    }
    inserate.sort(function(a, b) { return new Date(b.createdAt) - new Date(a.createdAt); });
    container.innerHTML = inserate.map(function(ins) {
        var date = new Date(ins.createdAt).toLocaleDateString('de-DE');
        var label = INSERAT_STATUS_LABELS[ins.status] || ins.status;
        var statusClass = ins.status === 'genehmigt' ? 'status-fertig'
                        : ins.status === 'abgelehnt'  ? 'status-abgelehnt'
                        : 'status-in_bearbeitung';
        return '<div class="buchung-card">' +
            '<div class="buchung-header">' +
                '<span class="buchung-car">' + escapeHtml(ins.make) + ' ' + escapeHtml(ins.model) + ' (' + escapeHtml(ins.year) + ')</span>' +
                '<span class="buchung-status ' + statusClass + '">' + escapeHtml(label) + '</span>' +
            '</div>' +
            '<div class="buchung-meta">Preis: ' + escapeHtml(String(ins.price)) + ' € &nbsp;|&nbsp; Eingereicht: ' + date + '</div>' +
            (ins.status === 'abgelehnt' ? '<div class="buchung-reason">Vom Administrator abgelehnt</div>' : '') +
        '</div>';
    }).join('');
}

// ===== LOGOUT =====

// Erkennt die Logout-Seite am Element mit id="logoutPage" (nur in logout.html vorhanden).
// localStorage.removeItem() löscht gezielt einzelne Einträge – dadurch ist der Nutzer abgemeldet.
// Der Auth-Guard in initUserForm() leitet bei erneutem Besuch von user.html automatisch weiter.
function initLogout() {
    if (!document.getElementById('logoutPage')) return;
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('loggedInUser');
}

// ===== INITIALISIERUNG =====

// DOMContentLoaded feuert, sobald das HTML vollständig geparst wurde (bevor Bilder/CSS fertig laden).
// Damit ist sichergestellt, dass alle getElementById()-Aufrufe die Elemente bereits finden.
// Jede init-Funktion prüft selbst, ob ihr Zielelement existiert – dadurch kann dieses eine
// Skript auf allen Seiten eingebunden werden, ohne seitenspezifische Fehler zu verursachen.
document.addEventListener('DOMContentLoaded', () => {
    initLogout();
    initNavAuthLink();
    initRegistrationForm();
    initLoginForm();
    initUserForm();
    initVehicleForm();
    initBookingsPage();
    initBookingButton();
    initBookingButtons();
    initAdminPage();
});

function initNavAuthLink() {
    var link = document.getElementById('navAuthLink');
    if (!link) return;
    if (localStorage.getItem('loggedIn') === 'true') {
        var username = localStorage.getItem('loggedInUser') || 'Konto';
        link.textContent = username;
        link.href = 'user.php';
    } else {
        link.textContent = 'Login';
        link.href = 'login.php';
    }
}

/*Light Mode Toggle: Niclas */
function toggleMode() {
    const isLight = document.body.classList.toggle("light-mode");
    localStorage.setItem('auto24_lightMode', isLight ? '1' : '0');
    document.querySelectorAll('.mode-btn').forEach(btn => {
        btn.textContent = isLight ? 'Dark' : 'Light';
    });
}

(function applyStoredMode() {
    if (localStorage.getItem('auto24_lightMode') === '1') {
        document.body.classList.add('light-mode');
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.mode-btn').forEach(btn => { btn.textContent = 'Dark'; });
        });
    }
})();
/*Layout-Umschaltung: Niclas */
function setVerticalLayout() {
    const layout = document.getElementById("carLayout");

    if (!layout) return;

    layout.classList.remove("horizontal-layout");
    layout.classList.add("vertical-layout");
}

function setHorizontalLayout() {
    const layout = document.getElementById("carLayout");

    if (!layout) return;

    layout.classList.remove("vertical-layout");
    layout.classList.add("horizontal-layout");
}

/*Preisberechnung mit Steuern */
function getTotalPrice(priceWOTax) {
    const taxRate = 0.19;
    return priceWOTax * (1 + taxRate);
}
function calculatePrice() {
    const input = document.getElementById("priceInput");
    const value = Number(input.value);

    if (value <= 0) {
        alert("Bitte gültigen Preis eingeben");
        return;
    }

    const total = getTotalPrice(value);

    document.getElementById("priceWithoutTax").textContent =
        "Preis ohne Steuer: " + value.toFixed(2) + " €";

    document.getElementById("priceWithTax").textContent =
        "Preis mit 19% Steuer: " + total.toFixed(2) + " €";
}

/* Finanzierungshilfe */
function calculateFinancing() {
    const input = document.getElementById("financingInput");
    const value = Number(input.value);

    const loanTermInput = document.getElementById("loanTermInput");
    const loanTerm = Number(loanTermInput.value);

    if (value <= 0) {
        alert("Bitte gültigen Finanzierungsbetrag eingeben");
        return;
    }

    if (loanTerm < 12 || loanTerm > 48) {
        alert("Bitte gültige Laufzeit eingeben (12-48 Monate)");
        return;
    }

    const interest = value * 0.05;
    const totalAmount = value + interest;
    const monthlyRate = totalAmount / loanTerm;

    document.getElementById("financingResult").textContent =
        "Monatliche Rate: " + monthlyRate.toFixed(2) + " €";

    document.getElementById("financingResult2").textContent =
        "Gesamtbetrag: " + totalAmount.toFixed(2) + " €";
}

/*Passwort Generator: Niclas */
function generatePassword() {
    const input = document.getElementById("pwInput");

    const length = 12;
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
    let password = "";
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
    }

    input.value = password;
    const output = document.getElementById('generatedPassword');
    if (output) output.textContent = "Generiertes Passwort: " + password;
}


// ===== INSERATE (Tim) =====

function getInserate() {
    return JSON.parse(localStorage.getItem('auto24_inserate') || '[]');
}

function saveInserate(inserate) {
    localStorage.setItem('auto24_inserate', JSON.stringify(inserate));
}

function initVehicleForm() {
    var form = document.getElementById('vehicleForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var requiredFields = form.querySelectorAll('[required]');
        var valid = true;
        requiredFields.forEach(function(f) {
            if (!f.value.trim()) { f.classList.add('invalid'); valid = false; }
            else f.classList.remove('invalid');
        });
        if (!valid) return;

        var inserat = {
            id: 'i_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5),
            userId: localStorage.getItem('loggedInUser') || 'Gast',
            make:      document.getElementById('sell-make').value.trim(),
            model:     document.getElementById('sell-model').value.trim(),
            year:      document.getElementById('sell-year').value,
            km:        document.getElementById('sell-km').value,
            fuel:      document.getElementById('sell-fuel').value,
            gearbox:   document.getElementById('sell-gearbox').value,
            power:     document.getElementById('sell-power').value,
            type:      document.getElementById('sell-type').value,
            condition: document.getElementById('sell-condition').value,
            price:     document.getElementById('sell-price').value,
            desc:      document.getElementById('sell-desc').value.trim(),
            name:      document.getElementById('sell-name').value.trim(),
            email:     document.getElementById('sell-email').value.trim(),
            phone:     document.getElementById('sell-phone').value.trim(),
            status:    'eingereicht',
            createdAt: new Date().toISOString()
        };

        var inserate = getInserate();
        inserate.push(inserat);
        saveInserate(inserate);

        form.reset();
        form.style.display = 'none';
        var success = document.getElementById('vehicleSuccess');
        if (success) {
            success.textContent = 'Ihr Inserat wurde erfolgreich eingereicht und wird innerhalb von 24 Stunden geprüft.';
            success.style.display = 'block';
        }
    });
}

// ===== BUCHUNGEN (Tim) =====

function getBookings() {
    return JSON.parse(localStorage.getItem('auto24_bookings') || '[]');
}

function saveBookings(bookings) {
    localStorage.setItem('auto24_bookings', JSON.stringify(bookings));
}

function getUserBookings(username) {
    return getBookings().filter(function(b) { return b.userId === username; });
}

function createBooking(carId, carName, carPrice) {
    var username = localStorage.getItem('loggedInUser');
    if (!username) return null;
    var booking = {
        id: 'b_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5),
        userId: username,
        carId: String(carId),
        carName: String(carName),
        carPrice: Number(carPrice),
        status: 'bestellt',
        reason: '',
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
    };
    var bookings = getBookings();
    bookings.push(booking);
    saveBookings(bookings);
    return booking;
}

function cancelBooking(bookingId) {
    var username = localStorage.getItem('loggedInUser');
    if (!username) return false;
    var bookings = getBookings();
    var idx = bookings.findIndex(function(b) { return b.id === bookingId && b.userId === username; });
    if (idx === -1 || bookings[idx].status !== 'bestellt') return false;
    bookings[idx].status = 'storniert';
    bookings[idx].updatedAt = new Date().toISOString();
    saveBookings(bookings);
    return true;
}

function isUserLocked(username) {
    var user = findUser(username);
    return user ? !!user.locked : false;
}
//schutz vor Cross-Site-Scripting (XSS) Angriffen, indem potenziell gefährliche Zeichen in HTML-Entities umgewandelt werden
function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

var BUCHUNG_STATUS_LABELS = {
    bestellt: 'Bestellt',
    in_bearbeitung: 'In Bearbeitung',
    versandt: 'Versandt, aber nicht erhalten',
    fertig: 'Fertig',
    storniert: 'Storniert',
    abgelehnt: 'Abgelehnt'
};

function initBookingsPage() {
    var container = document.getElementById('buchungenContainer');
    if (!container) return;

    if (localStorage.getItem('loggedIn') !== 'true') {
        window.location.href = 'login.php';
        return;
    }

    var username = localStorage.getItem('loggedInUser') || '';
    var displayEl = document.getElementById('buchungenUsername');
    if (displayEl) displayEl.textContent = username;

    renderBookingsPage();
}

function renderBookingsPage() {
    var container = document.getElementById('buchungenContainer');
    if (!container) return;

    var username = localStorage.getItem('loggedInUser') || '';
    var bookings = getUserBookings(username);

    if (bookings.length === 0) {
        container.innerHTML = '<p class="buchungen-empty">Sie haben noch keine Buchungen.<br><a href="gebrauchtwagenList.php" class="home-btn-primary" style="display:inline-block;margin-top:20px;">Fahrzeuge ansehen</a></p>';
        return;
    }

    bookings.sort(function(a, b) { return new Date(b.createdAt) - new Date(a.createdAt); });

    container.innerHTML = bookings.map(function(b) {
        var date = new Date(b.createdAt).toLocaleDateString('de-DE');
        var canCancel = b.status === 'bestellt';
        var label = BUCHUNG_STATUS_LABELS[b.status] || b.status;
        var html = '<div class="buchung-card">' +
            '<div class="buchung-header">' +
                '<div class="buchung-car">' + escapeHtml(b.carName) + '</div>' +
                '<span class="buchung-status status-' + escapeHtml(b.status) + '">' + escapeHtml(label) + '</span>' +
            '</div>' +
            '<div class="buchung-meta">' +
                '<span>Preis: <strong>' + Number(b.carPrice).toLocaleString('de-DE') + ' €</strong></span>' +
                '<span>Bestellt am: ' + date + '</span>' +
            '</div>';
        if (b.status === 'abgelehnt' && b.reason) {
            html += '<div class="buchung-reason">Ablehnungsgrund: ' + escapeHtml(b.reason) + '</div>';
        }
        if (canCancel) {
            html += '<button class="buchung-cancel-btn" onclick="handleCancelBooking(\'' + escapeHtml(b.id) + '\')">Buchung stornieren</button>';
        }
        html += '</div>';
        return html;
    }).join('');
}

function handleCancelBooking(bookingId) {
    if (!confirm('Buchung wirklich stornieren?')) return;
    if (cancelBooking(bookingId)) {
        renderBookingsPage();
    }
}

function initBookingButton() {
    var btn = document.getElementById('buchungsBtn');
    if (!btn) return;

    var carId = btn.dataset.carId;
    var carName = btn.dataset.carName;
    var carPrice = btn.dataset.carPrice;
    var note = document.getElementById('buchungsNote');

    if (localStorage.getItem('loggedIn') !== 'true') {
        btn.disabled = true;
        btn.title = 'Bitte einloggen, um zu buchen.';
        if (note) { note.textContent = 'Bitte einloggen, um dieses Fahrzeug zu buchen.'; note.style.display = 'block'; }
        return;
    }

    var username = localStorage.getItem('loggedInUser') || '';
    if (isUserLocked(username)) {
        btn.disabled = true;
        btn.title = 'Ihr Konto ist vom Administrator gesperrt.';
        if (note) { note.textContent = 'Ihr Konto ist vom Administrator gesperrt.'; note.style.display = 'block'; }
        return;
    }

    btn.addEventListener('click', function() {
        if (!confirm('Möchten Sie "' + carName + '" jetzt buchen?')) return;
        createBooking(carId, carName, carPrice);
        window.location.href = 'buchungen.php';
    });
}

// hier für mehrere Buttons in der merkliste also nicht nur eine ID sondern class! quasi identische Funktion -Lukas  
function initBookingButtons() {
    var btns = document.querySelectorAll('.buchungsBtn');
    if (!btns.length) return;

    var loggedIn = localStorage.getItem('loggedIn') === 'true';
    var username = localStorage.getItem('loggedInUser') || '';
    var locked = loggedIn && isUserLocked(username);

    btns.forEach(function(btn) {
        var carId    = btn.dataset.carId;
        var carName  = btn.dataset.carName;
        var carPrice = btn.dataset.carPrice;

        if (!loggedIn) {
            btn.disabled = true;
            btn.title = 'Bitte einloggen, um zu buchen.';
            return;
        }

        if (locked) {
            btn.disabled = true;
            btn.title = 'Ihr Konto ist vom Administrator gesperrt.';
            return;
        }

        btn.addEventListener('click', function() {
            if (!confirm('Möchten Sie "' + carName + '" jetzt buchen?')) return;
            createBooking(carId, carName, carPrice);
            window.location.href = 'buchungen.php';
        });
    });
}

// ===== ADMIN (Tim) =====

var ADMIN_CREDENTIALS = { username: 'admin', password: 'Admin1234' };

function isAdminLoggedIn() {
    return localStorage.getItem('auto24_adminLoggedIn') === 'true';
}

function adminUpdateBookingStatus(bookingId, newStatus, reason) {
    var bookings = getBookings();
    var idx = bookings.findIndex(function(b) { return b.id === bookingId; });
    if (idx === -1) return false;
    bookings[idx].status = newStatus;
    bookings[idx].reason = reason || '';
    bookings[idx].updatedAt = new Date().toISOString();
    saveBookings(bookings);
    return true;
}

function adminToggleUserLock(username) {
    var users = getUsers();
    var idx = users.findIndex(function(u) { return u.username === username; });
    if (idx === -1) return;
    users[idx].locked = !users[idx].locked;
    saveUsers(users);
}

function renderOrderList(containerId, bookings) {
    var container = document.getElementById(containerId);
    if (!container) return;
    if (bookings.length === 0) {
        container.innerHTML = '<p class="admin-empty">Keine Aufträge.</p>';
        return;
    }
    container.innerHTML = bookings.map(function(b) {
        var date = new Date(b.createdAt).toLocaleDateString('de-DE');
        var label = BUCHUNG_STATUS_LABELS[b.status] || b.status;
        var actions = '';
        if (b.status === 'bestellt') {
            actions += '<button onclick="adminSetStatus(\'' + b.id + '\', \'in_bearbeitung\')">In Bearbeitung</button>';
            actions += '<button class="btn-reject" onclick="adminRejectOrder(\'' + b.id + '\')">Ablehnen</button>';
        } else if (b.status === 'in_bearbeitung') {
            actions += '<button onclick="adminSetStatus(\'' + b.id + '\', \'versandt\')">Als versandt markieren</button>';
            actions += '<button onclick="adminSetStatus(\'' + b.id + '\', \'fertig\')">Fertigstellen</button>';
            actions += '<button class="btn-reject" onclick="adminRejectOrder(\'' + b.id + '\')">Ablehnen</button>';
        } else if (b.status === 'versandt') {
            actions += '<button onclick="adminSetStatus(\'' + b.id + '\', \'fertig\')">Als erhalten markieren</button>';
        }
        return '<div class="admin-order-card">' +
            '<div class="admin-order-header">' +
                '<div class="admin-order-car">' + escapeHtml(b.carName) + '</div>' +
                '<span class="buchung-status status-' + escapeHtml(b.status) + '">' + escapeHtml(label) + '</span>' +
            '</div>' +
            '<div class="admin-order-meta">' +
                '<span>Nutzer: <strong>' + escapeHtml(b.userId) + '</strong></span>' +
                '<span>Preis: <strong>' + Number(b.carPrice).toLocaleString('de-DE') + ' €</strong></span>' +
                '<span>Datum: ' + date + '</span>' +
            '</div>' +
            (b.reason ? '<div class="buchung-reason">Grund: ' + escapeHtml(b.reason) + '</div>' : '') +
            (actions ? '<div class="admin-order-actions">' + actions + '</div>' : '') +
        '</div>';
    }).join('');
}

function renderAdminOrders() {
    var bookings = getBookings();
    bookings.sort(function(a, b) { return new Date(b.createdAt) - new Date(a.createdAt); });
    renderOrderList('adminOrdersNew', bookings.filter(function(b) { return b.status === 'bestellt'; }));
    renderOrderList('adminOrdersProcessing', bookings.filter(function(b) { return b.status === 'in_bearbeitung' || b.status === 'versandt'; }));
    renderOrderList('adminOrdersRejected', bookings.filter(function(b) { return b.status === 'abgelehnt' || b.status === 'storniert'; }));
    renderOrderList('adminOrdersCompleted', bookings.filter(function(b) { return b.status === 'fertig'; }));
}

var INSERAT_STATUS_LABELS = {
    eingereicht: 'Eingereicht',
    genehmigt:   'Genehmigt',
    abgelehnt:   'Abgelehnt'
};

function renderAdminInserate() {
    var container = document.getElementById('adminInserate');
    if (!container) return;
    var inserate = getInserate();
    if (inserate.length === 0) {
        container.innerHTML = '<p class="admin-empty">Keine eingereichten Inserate.</p>';
        return;
    }
    inserate.sort(function(a, b) { return new Date(b.createdAt) - new Date(a.createdAt); });
    container.innerHTML = inserate.map(function(ins) {
        var date = new Date(ins.createdAt).toLocaleDateString('de-DE');
        var label = INSERAT_STATUS_LABELS[ins.status] || ins.status;
        var statusClass = ins.status === 'genehmigt' ? 'status-fertig' : ins.status === 'abgelehnt' ? 'status-abgelehnt' : 'status-in_bearbeitung';
        var actions = '';
        if (ins.status === 'eingereicht') {
            actions = '<button onclick="adminApproveInserat(\'' + ins.id + '\')">Genehmigen</button>' +
                      '<button class="btn-reject" onclick="adminRejectInserat(\'' + ins.id + '\')">Ablehnen</button>';
        }
        return '<div class="admin-order-card">' +
            '<div class="admin-order-header">' +
                '<div class="admin-order-car">' + escapeHtml(ins.make) + ' ' + escapeHtml(ins.model) + ' (' + escapeHtml(ins.year) + ')</div>' +
                '<span class="buchung-status ' + statusClass + '">' + escapeHtml(label) + '</span>' +
            '</div>' +
            '<div class="admin-order-meta">' +
                '<span>Von: <strong>' + escapeHtml(ins.name) + '</strong></span>' +
                '<span>Nutzer: <strong>' + escapeHtml(ins.userId) + '</strong></span>' +
                '<span>Preis: <strong>' + Number(ins.price).toLocaleString('de-DE') + ' €</strong></span>' +
                '<span>Datum: ' + date + '</span>' +
            '</div>' +
            '<div class="admin-order-meta" style="margin-top:-8px;">' +
                '<span>' + escapeHtml(ins.km) + ' km</span>' +
                '<span>' + escapeHtml(ins.fuel) + '</span>' +
                '<span>' + escapeHtml(ins.type) + '</span>' +
                '<span>' + escapeHtml(ins.condition) + '</span>' +
            '</div>' +
            (ins.desc ? '<div style="font-size:13px;color:#bdbdbd;margin-bottom:8px;text-align:left;">' + escapeHtml(ins.desc) + '</div>' : '') +
            (actions ? '<div class="admin-order-actions">' + actions + '</div>' : '') +
        '</div>';
    }).join('');
}

function adminApproveInserat(id) {
    var inserate = getInserate();
    var idx = inserate.findIndex(function(i) { return i.id === id; });
    if (idx !== -1) { inserate[idx].status = 'genehmigt'; saveInserate(inserate); }
    renderAdminInserate();
}

function adminRejectInserat(id) {
    var inserate = getInserate();
    var idx = inserate.findIndex(function(i) { return i.id === id; });
    if (idx !== -1) { inserate[idx].status = 'abgelehnt'; saveInserate(inserate); }
    renderAdminInserate();
}

function renderAdminUsers() {
    var container = document.getElementById('adminUsersList');
    if (!container) return;
    var users = getUsers();
    if (users.length === 0) {
        container.innerHTML = '<p class="admin-empty">Keine registrierten Nutzer.</p>';
        return;
    }
    container.innerHTML = users.map(function(u) {
        return '<div class="admin-user-row">' +
            '<span class="admin-user-name">' + escapeHtml(u.username) + '</span>' +
            '<span class="admin-user-status ' + (u.locked ? 'user-locked' : 'user-active') + '">' + (u.locked ? 'Gesperrt' : 'Aktiv') + '</span>' +
            '<button class="' + (u.locked ? 'btn-unlock' : 'btn-lock') + '" onclick="adminToggleLock(\'' + escapeHtml(u.username) + '\')">' + (u.locked ? 'Entsperren' : 'Sperren') + '</button>' +
        '</div>';
    }).join('');
}

function adminSetStatus(bookingId, status) {
    adminUpdateBookingStatus(bookingId, status, '');
    renderAdminOrders();
}

function adminRejectOrder(bookingId) {
    var reason = prompt('Bitte geben Sie einen Ablehnungsgrund an (z.B. nicht verfügbare Items):');
    if (reason === null) return;
    adminUpdateBookingStatus(bookingId, 'abgelehnt', reason || 'Kein Grund angegeben');
    renderAdminOrders();
}

function adminToggleLock(username) {
    adminToggleUserLock(username);
    renderAdminUsers();
}

function adminLogout() {
    localStorage.removeItem('auto24_adminLoggedIn');
    window.location.reload();
}

function initAdminPage() {
    var loginSection = document.getElementById('adminLoginSection');
    var dashboard = document.getElementById('adminDashboard');
    if (!loginSection && !dashboard) return;

    if (isAdminLoggedIn()) {
        if (loginSection) loginSection.style.display = 'none';
        if (dashboard) dashboard.style.display = 'block';
        renderAdminOrders();
        renderAdminUsers();
        renderAdminInserate();

        document.querySelectorAll('.admin-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.admin-tab').forEach(function(t) { t.classList.remove('active'); });
                document.querySelectorAll('.admin-tab-content').forEach(function(c) { c.classList.remove('active'); });
                tab.classList.add('active');
                var target = document.getElementById(tab.dataset.target);
                if (target) target.classList.add('active');
            });
        });
    } else {
        if (loginSection) loginSection.style.display = 'flex';
        if (dashboard) dashboard.style.display = 'none';

        var form = document.getElementById('adminLoginForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var u = document.getElementById('adminUsername').value.trim();
                var p = document.getElementById('adminPassword').value;
                var err = document.getElementById('adminLoginError');
                if (u === ADMIN_CREDENTIALS.username && p === ADMIN_CREDENTIALS.password) {
                    localStorage.setItem('auto24_adminLoggedIn', 'true');
                    window.location.reload();
                } else {
                    if (err) { err.textContent = 'Falscher Benutzername oder Passwort.'; err.style.display = 'block'; }
                }
            });
        }
    }
}
function filterByBudget() {

    const budget = Number(
        document.getElementById("budgetInput").value
    );

    if (budget <= 0) {
        alert("Bitte ein gültiges Budget eingeben.");
        return;
    }

    const cars = document.querySelectorAll(".car-card");

    cars.forEach(car => {

        const price = Number(
            car.dataset.price
        );

        if (price <= budget) {
            car.style.display = "";
        } else {
            car.style.display = "none";
        }
    });
}
function resetBudgetFilter() {

    const cars = document.querySelectorAll(".car-card");

    cars.forEach(car => {
        car.style.display = "";
    });

    document.getElementById("budgetInput").value = "";
}