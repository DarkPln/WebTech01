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

async function renderUserInserate() {
    var container = document.getElementById('userInserate');
    if (!container) return;

    const r    = await fetch('api/listings/list.php');
    const data = await r.json();
    var inserate = data.listings || [];

    if (inserate.length === 0) {
        container.innerHTML = '<p style="color:#888; font-size:14px;">Sie haben noch keine Inserate eingereicht.</p>';
        return;
    }

    inserate.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
    container.innerHTML = inserate.map(function(ins) {
        var date       = new Date(ins.createdAt).toLocaleDateString('de-DE');
        var label      = INSERAT_STATUS_LABELS[ins.status] || ins.status;
        var statusClass = ins.status === 'genehmigt' ? 'status-fertig'
                        : ins.status === 'abgelehnt' ? 'status-abgelehnt'
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

async function initLogout() {
    if (!document.getElementById('logoutPage')) return;
    await fetch('api/auth/logout.php', { method: 'POST' });
    authState = { loggedIn: false, username: '', isAdmin: false, isLocked: false };
}

// ===== INITIALISIERUNG =====

document.addEventListener('DOMContentLoaded', async () => {
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
function getTotalPrice(priceWOTax) {
    return priceWOTax * 1.19;
}

function calculatePrice() {
    const input = document.getElementById("priceInput");
    const value = Number(input.value);
    if (value <= 0) { alert("Bitte gültigen Preis eingeben"); return; }
    const total = getTotalPrice(value);
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

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        var valid = true;
        form.querySelectorAll('[required]').forEach(function(f) {
            if (!f.value.trim()) { f.classList.add('invalid'); valid = false; }
            else f.classList.remove('invalid');
        });
        if (!valid) return;

        var inserat = {
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
        };

        const r    = await fetch('api/listings/create.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(inserat),
        });
        const data = await r.json();

        if (data.success) {
            form.reset();
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

async function createBooking(carId, carName, carPrice) {
    const r = await fetch('api/bookings/create.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ carId, carName, carPrice }),
    });
    return r.json();
}

async function cancelBooking(bookingId) {
    const r = await fetch('api/bookings/cancel.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id: bookingId }),
    });
    return r.json();
}

//schutz vor Cross-Site-Scripting (XSS) Angriffen
function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

var BUCHUNG_STATUS_LABELS = {
    bestellt:        'Bestellt',
    in_bearbeitung:  'In Bearbeitung',
    versandt:        'Versandt, aber nicht erhalten',
    fertig:          'Fertig',
    storniert:       'Storniert',
    abgelehnt:       'Abgelehnt',
};

async function initBookingsPage() {
    var container = document.getElementById('buchungenContainer');
    if (!container) return;

    if (!authState.loggedIn) {
        window.location.href = 'login.php';
        return;
    }

    var displayEl = document.getElementById('buchungenUsername');
    if (displayEl) displayEl.textContent = authState.username;

    await renderBookingsPage();
}

async function renderBookingsPage() {
    var container = document.getElementById('buchungenContainer');
    if (!container) return;

    const r      = await fetch('api/bookings/list.php');
    const data   = await r.json();
    var bookings = data.bookings || [];

    if (bookings.length === 0) {
        container.innerHTML = '<p class="buchungen-empty">Sie haben noch keine Buchungen.<br><a href="gebrauchtwagenList.php" class="home-btn-primary" style="display:inline-block;margin-top:20px;">Fahrzeuge ansehen</a></p>';
        return;
    }

    container.innerHTML = bookings.map(function(b) {
        var date     = new Date(b.createdAt).toLocaleDateString('de-DE');
        var canCancel = b.status === 'bestellt';
        var label    = BUCHUNG_STATUS_LABELS[b.status] || b.status;
        var html     = '<div class="buchung-card">' +
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

async function handleCancelBooking(bookingId) {
    if (!confirm('Buchung wirklich stornieren?')) return;
    const data = await cancelBooking(bookingId);
    if (data.success) await renderBookingsPage();
}

function initBookingButton() {
    var btn = document.getElementById('buchungsBtn');
    if (!btn) return;

    var carId    = btn.dataset.carId;
    var carName  = btn.dataset.carName;
    var carPrice = btn.dataset.carPrice;
    var note     = document.getElementById('buchungsNote');

    if (!authState.loggedIn) {
        btn.disabled = true;
        btn.title    = 'Bitte einloggen, um zu buchen.';
        if (note) { note.textContent = 'Bitte einloggen, um dieses Fahrzeug zu buchen.'; note.style.display = 'block'; }
        return;
    }

    if (authState.isLocked) {
        btn.disabled = true;
        btn.title    = 'Ihr Konto ist vom Administrator gesperrt.';
        if (note) { note.textContent = 'Ihr Konto ist vom Administrator gesperrt.'; note.style.display = 'block'; }
        return;
    }

    btn.addEventListener('click', async function() {
        if (!confirm('Möchten Sie "' + carName + '" jetzt buchen?')) return;
        const data = await createBooking(carId, carName, carPrice);
        if (data.success) {
            window.location.href = 'buchungen.php';
        } else {
            alert(data.message || 'Buchung fehlgeschlagen.');
        }
    });
}

// Für mehrere Buttons in der Merkliste (class statt id) -Lukas
function initBookingButtons() {
    var btns = document.querySelectorAll('.buchungsBtn');
    if (!btns.length) return;

    btns.forEach(function(btn) {
        var carId    = btn.dataset.carId;
        var carName  = btn.dataset.carName;
        var carPrice = btn.dataset.carPrice;

        if (!authState.loggedIn) {
            btn.disabled = true;
            btn.title    = 'Bitte einloggen, um zu buchen.';
            return;
        }
        if (authState.isLocked) {
            btn.disabled = true;
            btn.title    = 'Ihr Konto ist vom Administrator gesperrt.';
            return;
        }

        btn.addEventListener('click', async function() {
            if (!confirm('Möchten Sie "' + carName + '" jetzt buchen?')) return;
            const data = await createBooking(carId, carName, carPrice);
            if (data.success) {
                window.location.href = 'buchungen.php';
            } else {
                alert(data.message || 'Buchung fehlgeschlagen.');
            }
        });
    });
}

// ===== ADMIN (Tim) =====

async function initAdminPage() {
    var loginSection = document.getElementById('adminLoginSection');
    var dashboard    = document.getElementById('adminDashboard');
    if (!loginSection && !dashboard) return;

    if (authState.isAdmin) {
        if (loginSection) loginSection.style.display = 'none';
        if (dashboard)    dashboard.style.display    = 'block';
        await renderAdminOrders();
        await renderAdminUsers();
        await renderAdminInserate();

        document.querySelectorAll('.admin-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.admin-tab-content').forEach(c => c.classList.remove('active'));
                tab.classList.add('active');
                var target = document.getElementById(tab.dataset.target);
                if (target) target.classList.add('active');
            });
        });
    } else {
        if (loginSection) loginSection.style.display = 'flex';
        if (dashboard)    dashboard.style.display    = 'none';

        var form = document.getElementById('adminLoginForm');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                var u   = document.getElementById('adminUsername').value.trim();
                var p   = document.getElementById('adminPassword').value;
                var err = document.getElementById('adminLoginError');

                const r    = await fetch('api/auth/login.php', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify({ username: u, password: p }),
                });
                const data = await r.json();

                if (data.success && data.isAdmin) {
                    window.location.reload();
                } else {
                    if (err) { err.textContent = 'Falscher Benutzername oder Passwort.'; err.style.display = 'block'; }
                }
            });
        }
    }
}

function renderOrderList(containerId, bookings) {
    var container = document.getElementById(containerId);
    if (!container) return;
    if (bookings.length === 0) {
        container.innerHTML = '<p class="admin-empty">Keine Aufträge.</p>';
        return;
    }
    container.innerHTML = bookings.map(function(b) {
        var date    = new Date(b.createdAt).toLocaleDateString('de-DE');
        var label   = BUCHUNG_STATUS_LABELS[b.status] || b.status;
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
            (actions   ? '<div class="admin-order-actions">' + actions + '</div>' : '') +
        '</div>';
    }).join('');
}

async function renderAdminOrders() {
    const r      = await fetch('api/admin/orders.php');
    const data   = await r.json();
    var bookings = (data.bookings || []).sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
    renderOrderList('adminOrdersNew',        bookings.filter(b => b.status === 'bestellt'));
    renderOrderList('adminOrdersProcessing', bookings.filter(b => b.status === 'in_bearbeitung' || b.status === 'versandt'));
    renderOrderList('adminOrdersRejected',   bookings.filter(b => b.status === 'abgelehnt' || b.status === 'storniert'));
    renderOrderList('adminOrdersCompleted',  bookings.filter(b => b.status === 'fertig'));
}

var INSERAT_STATUS_LABELS = {
    eingereicht: 'Eingereicht',
    genehmigt:   'Genehmigt',
    abgelehnt:   'Abgelehnt',
};

async function renderAdminInserate() {
    var container = document.getElementById('adminInserate');
    if (!container) return;

    const r    = await fetch('api/admin/listings.php');
    const data = await r.json();
    var inserate = (data.listings || []).sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));

    if (inserate.length === 0) {
        container.innerHTML = '<p class="admin-empty">Keine eingereichten Inserate.</p>';
        return;
    }
    container.innerHTML = inserate.map(function(ins) {
        var date       = new Date(ins.createdAt).toLocaleDateString('de-DE');
        var label      = INSERAT_STATUS_LABELS[ins.status] || ins.status;
        var statusClass = ins.status === 'genehmigt' ? 'status-fertig' : ins.status === 'abgelehnt' ? 'status-abgelehnt' : 'status-in_bearbeitung';
        var actions    = ins.status === 'eingereicht'
            ? '<button onclick="adminApproveInserat(\'' + ins.id + '\')">Genehmigen</button>' +
              '<button class="btn-reject" onclick="adminRejectInserat(\'' + ins.id + '\')">Ablehnen</button>'
            : '';
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
            (actions  ? '<div class="admin-order-actions">' + actions + '</div>' : '') +
        '</div>';
    }).join('');
}

async function adminApproveInserat(id) {
    await fetch('api/admin/listings.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id, status: 'genehmigt' }) });
    await renderAdminInserate();
}

async function adminRejectInserat(id) {
    await fetch('api/admin/listings.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id, status: 'abgelehnt' }) });
    await renderAdminInserate();
}

async function renderAdminUsers() {
    var container = document.getElementById('adminUsersList');
    if (!container) return;

    const r    = await fetch('api/admin/users.php');
    const data = await r.json();
    var users  = data.users || [];

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

async function adminSetStatus(bookingId, status) {
    await fetch('api/admin/orders.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: bookingId, status, reason: '' }) });
    await renderAdminOrders();
}

async function adminRejectOrder(bookingId) {
    var reason = prompt('Bitte geben Sie einen Ablehnungsgrund an (z.B. nicht verfügbare Items):');
    if (reason === null) return;
    await fetch('api/admin/orders.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: bookingId, status: 'abgelehnt', reason: reason || 'Kein Grund angegeben' }) });
    await renderAdminOrders();
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
    document.getElementById("budgetInput").value = 100000;
    updateBudgetLabel();
}

function sortCarsByPriceAsc() {
    const container = document.getElementById("carLayout");
    Array.from(container.querySelectorAll(".car-card"))
        .sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price))
        .forEach(car => container.appendChild(car));
}

function sortCarsByPriceDesc() {
    const container = document.getElementById("carLayout");
    Array.from(container.querySelectorAll(".car-card"))
        .sort((a, b) => Number(b.dataset.price) - Number(a.dataset.price))
        .forEach(car => container.appendChild(car));
}

function searchCars() {
    const searchTerm = document.getElementById("carSearchInput").value.toLowerCase();
    document.querySelectorAll(".car-card").forEach(car => {
        car.style.display =
            (car.dataset.make.toLowerCase().includes(searchTerm) || car.dataset.model.toLowerCase().includes(searchTerm))
            ? "" : "none";
    });
}

function resetCarSearch() {
    document.getElementById("carSearchInput").value = "";
    document.querySelectorAll(".car-card").forEach(car => { car.style.display = ""; });
}
