// Formularvalidierung für Auto24
// Autor: tim

// ===== GEMEINSAME VALIDIERUNGSFUNKTIONEN =====

function validateUsername(value) {
    const errors = [];
    if (value.length < 5) errors.push('Mindestens 5 Zeichen erforderlich');
    if (value === value.toUpperCase()) errors.push('Mindestens ein Kleinbuchstabe erforderlich');
    if (value === value.toLowerCase()) errors.push('Mindestens ein Großbuchstabe erforderlich');
    return errors;
}

function validatePassword(value) {
    const errors = [];
    if (value.length < 10) errors.push('Mindestens 10 Zeichen erforderlich');
    return errors;
}

function validatePasswordMatch(password, passwordRepeat) {
    if (password !== passwordRepeat) return ['Passwörter stimmen nicht überein'];
    return [];
}

// Nimmt ein Eingabefeld, eine Validator-Funktion und optional ein Zusatzargument (z.B. das
// erste Passwort beim Match-Check). Setzt CSS-Klassen und zeigt Fehlertexte an.
function validateField(field, validator, extraArg) {
    const errors = extraArg !== undefined ? validator(field.value, extraArg) : validator(field.value);
    const errorSpan = document.getElementById(field.id + '-error');
    field.classList.remove('valid', 'invalid');
    if (errors.length > 0) {
        field.classList.add('invalid');
        if (errorSpan) errorSpan.textContent = errors[0];
        return false;
    }
    if (field.value.length > 0) {
        field.classList.add('valid');
        if (errorSpan) errorSpan.textContent = '';
        return true;
    }
    if (errorSpan) errorSpan.textContent = '';
    return false;
}

// ===== AUTH-STATUS (PHP-Session-basiert) =====

// Wird beim Seitenstart einmal geladen; alle anderen Funktionen lesen dieses Objekt.
let authState = { loggedIn: false, username: '', isAdmin: false, isLocked: false };

async function loadAuthState() {
    try {
        const r = await fetch('api/auth/status.php');
        authState = await r.json();
    } catch (e) {
        authState = { loggedIn: false };
    }
}

// ===== REGISTRIERUNGS-FORMULAR =====

function initRegistrationForm() {
    const form = document.getElementById('registrationForm');
    if (!form) return;

    const benutzername      = document.getElementById('benutzername');
    const passwort          = document.getElementById('passwort');
    const passwortWiederholen = document.getElementById('passwort_wiederholen');
    const submitBtn         = document.getElementById('submitBtn');
    const errorMessage      = document.getElementById('reg-error');

    function checkFormValidity() {
        submitBtn.disabled =
            validateUsername(benutzername.value).length > 0 ||
            validatePassword(passwort.value).length > 0 ||
            validatePasswordMatch(passwort.value, passwortWiederholen.value).length > 0;
    }

    benutzername.addEventListener('input', () => {
        validateField(benutzername, validateUsername);
        if (errorMessage) errorMessage.style.display = 'none';
        checkFormValidity();
    });

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

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (submitBtn.disabled) return;

        const r    = await fetch('api/auth/register.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ username: benutzername.value.trim(), password: passwort.value }),
        });
        const data = await r.json();

        if (data.success) {
            window.location.href = 'login.php?registered=1';
        } else {
            if (errorMessage) {
                errorMessage.textContent = data.message || 'Registrierung fehlgeschlagen.';
                errorMessage.style.display = 'block';
            }
        }
    });

    submitBtn.disabled = true;
}

// ===== LOGIN-FORMULAR =====

function initLoginForm() {
    const loginForm      = document.getElementById('loginForm');
    if (!loginForm) return;

    const username       = document.getElementById('username');
    const password       = document.getElementById('password');
    const loginBtn       = document.getElementById('loginBtn');
    const errorMessage   = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');

    function checkFormValidity() {
        loginBtn.disabled = username.value.trim().length === 0 || password.value.length === 0;
    }

    username.addEventListener('input', () => { if (errorMessage) errorMessage.style.display = 'none'; checkFormValidity(); });
    password.addEventListener('input', () => { if (errorMessage) errorMessage.style.display = 'none'; checkFormValidity(); });


    // bei erfolgreicher registrierung: 
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('registered') === '1' && successMessage) {
        successMessage.textContent = 'Registrierung erfolgreich! Bitte jetzt einloggen.';
        successMessage.style.display = 'block';
    }

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (loginBtn.disabled) return;

        const r    = await fetch('api/auth/login.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ username: username.value.trim(), password: password.value }),
        });
        const data = await r.json();

        if (data.success) {
            window.location.href = data.isAdmin ? 'admin.php' : 'user.php';
        } else {
            if (errorMessage) {
                errorMessage.textContent = data.message || 'Falscher Benutzername oder Passwort.';
                errorMessage.style.display = 'block';
            }
        }
    });

    loginBtn.disabled = true;
}

// ===== NUTZERBEREICH =====

function initUserForm() {
    if (!document.getElementById('userForm')) return;

    if (!authState.loggedIn) {
        window.location.href = 'login.php';
        return;
    }

    const usernameInput   = document.getElementById('username');
    const passwordInput   = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    const saveBtn         = document.getElementById('saveBtn');
    const displayName     = document.getElementById('display-username');
    const saveSuccess     = document.getElementById('saveSuccess');

    if (usernameInput) usernameInput.value = authState.username;
    if (displayName)   displayName.textContent = authState.username;

    function checkFormValidity() {
        saveBtn.disabled =
            validateUsername(usernameInput.value).length > 0 ||
            validatePassword(passwordInput.value).length > 0 ||
            validatePasswordMatch(passwordInput.value, passwordConfirm.value).length > 0;
    }

    usernameInput.addEventListener('input', () => { validateField(usernameInput, validateUsername); checkFormValidity(); });
    passwordInput.addEventListener('input', () => {
        validateField(passwordInput, validatePassword);
        if (passwordConfirm.value.length > 0)
            validateField(passwordConfirm, validatePasswordMatch, passwordInput.value);
        checkFormValidity();
    });
    passwordConfirm.addEventListener('input', () => { validateField(passwordConfirm, validatePasswordMatch, passwordInput.value); checkFormValidity(); });

    document.getElementById('userForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        if (saveBtn.disabled) return;

        const r    = await fetch('api/auth/update.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ username: usernameInput.value.trim(), password: passwordInput.value }),
        });
        const data = await r.json();

        if (data.success) {
            authState.username = data.username;
            if (displayName) displayName.textContent = data.username;
            if (saveSuccess) {
                saveSuccess.textContent = 'Änderungen erfolgreich gespeichert!';
                saveSuccess.style.display = 'block';
                setTimeout(() => saveSuccess.style.display = 'none', 3000);
            }
        }
    });

    saveBtn.disabled = true;
    renderUserInserate();
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
async function renderUserInserate() {
    var container = document.getElementById('userInserate');
    if (!container) return;

    const antwort       = await fetch('api/listings/list_html.php');
    container.innerHTML = await antwort.text();
}

// ===== LOGOUT =====

async function initLogout() {
    if (!document.getElementById('logoutPage')) return;
    await fetch('api/auth/logout.php', { method: 'POST' });
    authState = { loggedIn: false, username: '', isAdmin: false, isLocked: false };
}

// ===== INITIALISIERUNG =====

document.addEventListener('DOMContentLoaded', async () => {
    showJsNoticeIfNeeded();
    applyUrlFilter();
    await initLogout();
    await loadAuthState();
    initNavAuthLink();
    initRegistrationForm();
    initLoginForm();
    initUserForm();
    initVehicleForm();
    await initBookingsPage();
    initBookingButton();
    initBookingButtons();
    await initAdminPage();
});

function showJsNoticeIfNeeded() {
    if (sessionStorage.getItem('jsNoticeDismissed')) return;

    var banner = document.createElement('div');
    banner.className = 'js-notice-banner';
    banner.innerHTML =
        '<span>Für alle Funktionen von Auto24 bitte JavaScript aktivieren.</span>' +
        '<button class="js-notice-close" onclick="this.parentElement.remove();sessionStorage.setItem(\'jsNoticeDismissed\',\'1\')">✕</button>';
    document.body.prepend(banner);
}

function initNavAuthLink() {
    var link = document.getElementById('navAuthLink');
    if (!link) return;
    if (authState.loggedIn) {
        link.textContent = authState.username || 'Konto';
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
function calculatePrice() {
    const input = document.getElementById("priceInput");
    const value = Number(input.value);
    if (value <= 0) { alert("Bitte gültigen Preis eingeben"); return; }
    const total = value * 1.19;
    document.getElementById("priceWithoutTax").textContent = "Preis ohne Steuer: " + value.toFixed(2) + " €";
    document.getElementById("priceWithTax").textContent    = "Preis mit 19% Steuer: " + total.toFixed(2) + " €";
}

/* Finanzierungshilfe */
function calculateFinancing() {
    const value    = Number(document.getElementById("financingInput").value);
    const loanTerm = Number(document.getElementById("loanTermInput").value);
    if (value <= 0)              { alert("Bitte gültigen Finanzierungsbetrag eingeben"); return; }
    if (loanTerm < 12 || loanTerm > 48) { alert("Bitte gültige Laufzeit eingeben (12-48 Monate)"); return; }
    const total   = value * 1.05;
    document.getElementById("financingResult").textContent  = "Monatliche Rate: " + (total / loanTerm).toFixed(2) + " €";
    document.getElementById("financingResult2").textContent = "Gesamtbetrag: " + total.toFixed(2) + " €";
}

/*Passwort Generator: Niclas */
function generatePassword() {
    const input   = document.getElementById("pwInput");
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
    let password  = "";
    for (let i = 0; i < 12; i++) {
        password += charset[Math.floor(Math.random() * charset.length)];
    }
    input.value = password;
    const output = document.getElementById('generatedPassword');
    if (output) output.textContent = "Generiertes Passwort: " + password;
}

// ===== INSERATE (Tim) =====

function initVehicleForm() {
    var form = document.getElementById('vehicleForm');
    if (!form) return;

    if (!authState.loggedIn) {
        window.location.href = 'login.php';
        return;
    }

    var uploadArea    = document.getElementById('uploadArea');
    var fileInput     = document.getElementById('sell-images');
    var previewGrid   = document.getElementById('imagePreviewGrid');
    var uploadCount   = document.getElementById('uploadCount');
    var selectedFiles = [];

    function updateCount() {
        if (!uploadCount) return;
        if (selectedFiles.length === 0) {
            uploadCount.style.display = 'none';
        } else {
            uploadCount.style.display = 'block';
            uploadCount.textContent = selectedFiles.length + ' von 10 Foto(s) ausgewählt';
        }
    }

    function renderPreviews() {
        if (!previewGrid) return;
        previewGrid.innerHTML = '';
        selectedFiles.forEach(function(file, i) {
            var item = document.createElement('div');
            item.className = 'image-preview-item';

            var img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;

            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-img';
            removeBtn.textContent = '×';
            removeBtn.addEventListener('click', function() {
                selectedFiles.splice(i, 1);
                renderPreviews();
                updateCount();
            });

            item.appendChild(img);
            item.appendChild(removeBtn);
            previewGrid.appendChild(item);
        });
        updateCount();
    }

    function addFiles(newFiles) {
        var allowed = ['image/jpeg', 'image/png', 'image/webp'];
        newFiles.forEach(function(file) {
            if (!allowed.includes(file.type)) return;
            if (file.size > 5 * 1024 * 1024) { alert(file.name + ' ist zu groß (max. 5 MB).'); return; }
            if (selectedFiles.length >= 10) { alert('Maximal 10 Fotos erlaubt.'); return; }
            selectedFiles.push(file);
        });
        renderPreviews();
    }

    if (uploadArea && fileInput) {
        uploadArea.addEventListener('click', function() { fileInput.click(); });

        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', function() {
            uploadArea.classList.remove('dragover');
        });
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            addFiles(Array.from(e.dataTransfer.files));
        });

        fileInput.addEventListener('change', function() {
            addFiles(Array.from(fileInput.files));
            fileInput.value = '';
        });
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        var valid = true;
        form.querySelectorAll('[required]').forEach(function(f) {
            if (!f.value.trim()) { f.classList.add('invalid'); valid = false; }
            else f.classList.remove('invalid');
        });
        if (!valid) return;

        var fd = new FormData();
        fd.append('make',      document.getElementById('sell-make').value.trim());
        fd.append('model',     document.getElementById('sell-model').value.trim());
        fd.append('year',      document.getElementById('sell-year').value);
        fd.append('km',        document.getElementById('sell-km').value);
        fd.append('fuel',      document.getElementById('sell-fuel').value);
        fd.append('gearbox',   document.getElementById('sell-gearbox').value);
        fd.append('power',     document.getElementById('sell-power').value);
        fd.append('antrieb',   document.getElementById('sell-antrieb').value);
        fd.append('type',      document.getElementById('sell-type').value);
        fd.append('condition', document.getElementById('sell-condition').value);
        fd.append('price',     document.getElementById('sell-price').value);
        fd.append('desc',      document.getElementById('sell-desc').value.trim());
        fd.append('name',      document.getElementById('sell-name').value.trim());
        fd.append('email',     document.getElementById('sell-email').value.trim());
        fd.append('phone',     document.getElementById('sell-phone').value.trim());
        selectedFiles.forEach(function(file) { fd.append('images[]', file); });

        const r    = await fetch('api/listings/create.php', { method: 'POST', body: fd });
        const data = await r.json();

        if (data.success) {
            form.reset();
            selectedFiles = [];
            renderPreviews();
            form.style.display = 'none';
            var success = document.getElementById('vehicleSuccess');
            if (success) {
                success.textContent = 'Ihr Inserat wurde erfolgreich eingereicht und wird innerhalb von 24 Stunden geprüft.';
                success.style.display = 'block';
            }
        }
    });
}

// ===== BUCHUNGEN (Tim) =====

// Sendet eine neue Buchung an den Server und gibt die Antwort zurück
async function createBooking(carId, carName, carPrice) {
    const antwort = await fetch('api/bookings/create.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ carId, carName, carPrice })
    });
    return antwort.json();
}

// Markiert eine Buchung als storniert
async function cancelBooking(buchungsId) {
    const antwort = await fetch('api/bookings/cancel.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id: buchungsId })
    });
    return antwort.json();
}

// Buchungsseite initialisieren: Weiterleitung wenn nicht eingeloggt, sonst Buchungen anzeigen
async function initBookingsPage() {
    var container = document.getElementById('buchungenContainer');
    if (!container) return;

    if (!authState.loggedIn) {
        window.location.href = 'login.php';
        return;
    }

    // Benutzernamen in der Seitenüberschrift anzeigen
    var nutzernameAnzeige = document.getElementById('buchungenUsername');
    if (nutzernameAnzeige) nutzernameAnzeige.textContent = authState.username;

    await renderBookingsPage();
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
async function renderBookingsPage() {
    var container = document.getElementById('buchungenContainer');
    if (!container) return;

    const antwort       = await fetch('api/bookings/list_html.php');
    container.innerHTML = await antwort.text();
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
        window.location.href = 'buchungen.php';
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

// ===== ADMIN (Tim) =====

// Admin-Seite initialisieren: Dashboard zeigen wenn eingeloggt, sonst Login-Formular
async function initAdminPage() {
    var loginBereich = document.getElementById('adminLoginSection');
    var dashboard    = document.getElementById('adminDashboard');
    if (!loginBereich && !dashboard) return;

    if (authState.isAdmin) {
        // Admin ist eingeloggt: Login verstecken, Dashboard anzeigen
        if (loginBereich) loginBereich.style.display = 'none';
        if (dashboard)    dashboard.style.display    = 'block';

        // Alle drei Bereiche mit Daten aus der Datenbank befüllen
        await renderAdminOrders();
        await renderAdminUsers();
        await renderAdminInserate();

        // Tab-Wechsel einrichten: aktiven Tab und Inhalt hervorheben
        document.querySelectorAll('.admin-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.admin-tab-content').forEach(c => c.classList.remove('active'));
                tab.classList.add('active');
                var zielBereich = document.getElementById(tab.dataset.target);
                if (zielBereich) zielBereich.classList.add('active');
            });
        });
    } else {
        // Admin ist nicht eingeloggt: Login anzeigen, Dashboard verstecken
        if (loginBereich) loginBereich.style.display = 'flex';
        if (dashboard)    dashboard.style.display    = 'none';

        var formular = document.getElementById('adminLoginForm');
        if (formular) {
            formular.addEventListener('submit', async function(e) {
                e.preventDefault();
                var benutzername  = document.getElementById('adminUsername').value.trim();
                var passwort      = document.getElementById('adminPassword').value;
                var fehlerAnzeige = document.getElementById('adminLoginError');

                const antwort  = await fetch('api/auth/login.php', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify({ username: benutzername, password: passwort })
                });
                const ergebnis = await antwort.json();

                if (ergebnis.success && ergebnis.isAdmin) {
                    // Seite neu laden damit die Session erkannt wird
                    window.location.reload();
                } else {
                    if (fehlerAnzeige) { fehlerAnzeige.textContent = 'Falscher Benutzername oder Passwort.'; fehlerAnzeige.style.display = 'block'; }
                }
            });
        }
    }
}

// Alle vier Auftrags-Tabs parallel vom Server laden und befüllen
async function renderAdminOrders() {
    const bereiche   = ['new', 'processing', 'rejected', 'completed'];
    const containerIds = ['adminOrdersNew', 'adminOrdersProcessing', 'adminOrdersRejected', 'adminOrdersCompleted'];

    // Promise.all schickt alle vier Anfragen gleichzeitig ab
    const antworten = await Promise.all(
        bereiche.map(b => fetch('api/admin/orders_html.php?bereich=' + b).then(r => r.text()))
    );

    bereiche.forEach((_, i) => {
        document.getElementById(containerIds[i]).innerHTML = antworten[i];
    });
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
async function renderAdminInserate() {
    var container = document.getElementById('adminInserate');
    if (!container) return;

    const antwort          = await fetch('api/admin/inserate_html.php');
    container.innerHTML    = await antwort.text();
}

async function adminSetInseratStatus(id, status) {
    await fetch('api/admin/listings.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id, status })
    });
    await renderAdminInserate();
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
async function renderAdminUsers() {
    var container = document.getElementById('adminUsersList');
    if (!container) return;

    const antwort       = await fetch('api/admin/users_html.php');
    container.innerHTML = await antwort.text();
}

async function adminSetStatus(bookingId, status, reason) {
    await fetch('api/admin/orders.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: bookingId, status, reason: reason || '' }) });
    await renderAdminOrders();
}

async function adminRejectOrder(bookingId) {
    var reason = prompt('Bitte geben Sie einen Ablehnungsgrund an (z.B. nicht verfügbare Items):');
    if (reason === null) return;
    await adminSetStatus(bookingId, 'abgelehnt', reason || 'Kein Grund angegeben');
}

async function adminToggleLock(username) {
    await fetch('api/admin/users.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ username }) });
    await renderAdminUsers();
}

async function adminLogout() {
    await fetch('api/auth/logout.php', { method: 'POST' });
    window.location.reload();
}

// Niclas: Funktionen für GebrauchtwagenList.php
function applyUrlFilter() {
    const filter = new URLSearchParams(window.location.search).get('filter');
    if (!filter) return;

    document.querySelectorAll('.car-card').forEach(card => {
        let visible;
        switch (filter) {
            case 'neuwagen':       visible = card.dataset.kategorie === 'neuwagen'; break;
            case 'gebrauchtwagen': visible = card.dataset.kategorie === 'gebrauchtwagen'; break;
            case 'elektro':        visible = card.dataset.fuel === 'Elektro'; break;
            case 'sonderangebot':  visible = Number(card.dataset.price) <= 60000; break;
            default:               visible = true;
        }
        card.style.display = visible ? '' : 'none';
    });
}

function updateBudgetLabel() {
    const value = Number(document.getElementById("budgetInput").value);
    document.getElementById("budgetValue").textContent = value.toLocaleString("de-DE") + " €";
}

function filterByBudget() {
    const budget = Number(document.getElementById("budgetInput").value);
    if (budget <= 0) { alert("Bitte ein gültiges Budget eingeben."); return; }
    document.querySelectorAll(".car-card").forEach(car => {
        car.style.display = Number(car.dataset.price) <= budget ? "" : "none";
    });
}

function resetBudgetFilter() {
    document.querySelectorAll(".car-card").forEach(car => { car.style.display = ""; });
    document.getElementById("budgetInput").value = 150000;
    updateBudgetLabel();
}

function sortCarsByPrice(asc) {
    const container = document.getElementById("carLayout");
    Array.from(container.querySelectorAll(".car-card"))
        .sort((a, b) => asc
            ? Number(a.dataset.price) - Number(b.dataset.price)
            : Number(b.dataset.price) - Number(a.dataset.price))
        .forEach(car => container.appendChild(car));
}

function searchCars() {
    applyAllFilters();
}

function updateYearLabel() {
    const value = Number(document.getElementById("yearInput").value);
    document.getElementById("yearValue").textContent = value;
}

function applyAllFilters() {
    const budget     = Number(document.getElementById("budgetInput").value);
    const year       = Number(document.getElementById("yearInput").value);
    const searchTerm = document.getElementById("carSearchInput").value.toLowerCase();

    document.querySelectorAll(".car-card").forEach(car => {
        const matchesBudget = Number(car.dataset.price) <= budget;
        const matchesYear   = Number(car.dataset.year) >= year;
        const matchesSearch = searchTerm === "" ||
            car.dataset.make.toLowerCase().includes(searchTerm) ||
            car.dataset.model.toLowerCase().includes(searchTerm);
        car.style.display = (matchesBudget && matchesYear && matchesSearch) ? "" : "none";
    });
}

function resetAllFilters() {
    document.getElementById("budgetInput").value = 150000;
    updateBudgetLabel();
    document.getElementById("yearInput").value = 1980;
    updateYearLabel();
    document.getElementById("carSearchInput").value = "";
    document.querySelectorAll(".car-card").forEach(car => { car.style.display = ""; });
}
