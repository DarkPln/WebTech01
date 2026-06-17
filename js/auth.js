// Tim

// Nav-Link je nach Login-Status auf Nutzerprofil oder Login-Seite setzen
function initNavAuthLink() {
    var link = document.getElementById('navAuthLink');
    if (!link) return;
    if (authState.loggedIn) {
        link.textContent = authState.username || 'Konto';
        link.href = BASE_URL + '/user';
    } else {
        link.textContent = 'Login';
        link.href = BASE_URL + '/auth/login';
    }
}

// Login-Formular initialisieren: Validierung, Fehlermeldungen und Submit-Handler
function initLoginForm() {
    const loginForm      = document.getElementById('loginForm');
    if (!loginForm) return;

    const username       = document.getElementById('username');
    const password       = document.getElementById('password');
    const loginBtn       = document.getElementById('loginBtn');
    const errorMessage   = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');

    // Submit-Button deaktivieren solange ein Feld leer ist
    function checkFormValidity() {
        loginBtn.disabled = username.value.trim().length === 0 || password.value.length === 0;
    }

    username.addEventListener('input',  () => { if (errorMessage) errorMessage.style.display = 'none'; checkFormValidity(); });
    username.addEventListener('change', checkFormValidity);
    password.addEventListener('input',  () => { if (errorMessage) errorMessage.style.display = 'none'; checkFormValidity(); });
    password.addEventListener('change', checkFormValidity);

    // Erfolgreiche Registrierung zuvor per URL-Parameter ?registered=1 signalisiert
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('registered') === '1' && successMessage) {
        successMessage.textContent = 'Registrierung erfolgreich! Bitte jetzt einloggen.';
        successMessage.style.display = 'block';
    }

    // Login-Daten per fetch() an den Server schicken, bei Erfolg zu Admin oder Nutzerprofil weiterleiten
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (loginBtn.disabled) return;

        fetch(BASE_URL + '/api/auth/login', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ username: username.value.trim(), password: password.value }),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Admin -> /admin, normaler Nutzer -> /user
                    window.location.href = data.isAdmin ? BASE_URL + '/admin' : BASE_URL + '/user';
                } else {
                    if (errorMessage) {
                        errorMessage.textContent = data.message || 'Falscher Benutzername oder Passwort.';
                        errorMessage.style.display = 'block';
                    }
                }
            });
    });

    checkFormValidity();
    // Autofill-Werte des Browsers werden erst nach kurzer Verzögerung sichtbar
    setTimeout(checkFormValidity, 300);
}

// Registrierungsformular initialisieren: Echtzeit-Validierung und Submit-Handler
function initRegistrationForm() {
    const form = document.getElementById('registrationForm');
    if (!form) return;

    const benutzername        = document.getElementById('benutzername');
    const passwort            = document.getElementById('passwort');
    const passwortWiederholen = document.getElementById('passwort_wiederholen');
    const submitBtn           = document.getElementById('submitBtn');
    const errorMessage        = document.getElementById('reg-error');

    // Submit-Button nur freischalten wenn alle Felder die Validierungsregeln erfüllen
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
        // Passwort-Wiederholung sofort neu prüfen wenn das Hauptfeld sich ändert
        if (passwortWiederholen.value.length > 0)
            validateField(passwortWiederholen, validatePasswordMatch, passwort.value);
        checkFormValidity();
    });

    passwortWiederholen.addEventListener('input', () => {
        validateField(passwortWiederholen, validatePasswordMatch, passwort.value);
        checkFormValidity();
    });

    // Registrierungsdaten per fetch() senden, bei Erfolg zur Login-Seite mit ?registered=1 weiterleiten
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (submitBtn.disabled) return;

        fetch(BASE_URL + '/api/auth/register', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ username: benutzername.value.trim(), password: passwort.value }),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.href = BASE_URL + '/auth/login?registered=1';
                } else {
                    if (errorMessage) {
                        errorMessage.textContent = data.message || 'Registrierung fehlgeschlagen.';
                        errorMessage.style.display = 'block';
                    }
                }
            });
    });

    submitBtn.disabled = true;
}

// Logout-POST auslösen wenn die Logout-Seite geladen wird, authState danach zurücksetzen
function initLogout() {
    if (!document.getElementById('logoutPage')) return Promise.resolve();
    return fetch(BASE_URL + '/api/auth/logout', { method: 'POST' })
        .then(() => {
            authState = { loggedIn: false, username: '', isAdmin: false, isLocked: false };
        });
}
