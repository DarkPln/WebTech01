// Tim

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

    username.addEventListener('input',  () => { if (errorMessage) errorMessage.style.display = 'none'; checkFormValidity(); });
    username.addEventListener('change', checkFormValidity);
    password.addEventListener('input',  () => { if (errorMessage) errorMessage.style.display = 'none'; checkFormValidity(); });
    password.addEventListener('change', checkFormValidity);

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('registered') === '1' && successMessage) {
        successMessage.textContent = 'Registrierung erfolgreich! Bitte jetzt einloggen.';
        successMessage.style.display = 'block';
    }

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (loginBtn.disabled) return;

        const r    = await fetch(BASE_URL + '/api/auth/login', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ username: username.value.trim(), password: password.value }),
        });
        const data = await r.json();

        if (data.success) {
            window.location.href = data.isAdmin ? BASE_URL + '/admin' : BASE_URL + '/user';
        } else {
            if (errorMessage) {
                errorMessage.textContent = data.message || 'Falscher Benutzername oder Passwort.';
                errorMessage.style.display = 'block';
            }
        }
    });

    checkFormValidity();
    setTimeout(checkFormValidity, 300);
}

function initRegistrationForm() {
    const form = document.getElementById('registrationForm');
    if (!form) return;

    const benutzername        = document.getElementById('benutzername');
    const passwort            = document.getElementById('passwort');
    const passwortWiederholen = document.getElementById('passwort_wiederholen');
    const submitBtn           = document.getElementById('submitBtn');
    const errorMessage        = document.getElementById('reg-error');

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

        const r    = await fetch(BASE_URL + '/api/auth/register', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ username: benutzername.value.trim(), password: passwort.value }),
        });
        const data = await r.json();

        if (data.success) {
            window.location.href = BASE_URL + '/auth/login?registered=1';
        } else {
            if (errorMessage) {
                errorMessage.textContent = data.message || 'Registrierung fehlgeschlagen.';
                errorMessage.style.display = 'block';
            }
        }
    });

    submitBtn.disabled = true;
}

async function initLogout() {
    if (!document.getElementById('logoutPage')) return;
    await fetch(BASE_URL + '/api/auth/logout', { method: 'POST' });
    authState = { loggedIn: false, username: '', isAdmin: false, isLocked: false };
}
