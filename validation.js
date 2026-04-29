// Formularvalidierung für Auto24
// Autor: tim

// ===== GEMEINSAME VALIDIERUNGSFUNKTIONEN =====

/**
 * Validiert einen Benutzernamen
 * Anforderungen: Min. 5 Zeichen, mind. 1 Groß- und 1 Kleinbuchstabe
 */
function validateUsername(value) {
    const errors = [];
    if (value.length < 5) {
        errors.push('Mindestens 5 Zeichen erforderlich');
    }
    if (!/[a-z]/.test(value)) {
        errors.push('Mindestens ein Kleinbuchstabe erforderlich');
    }
    if (!/[A-Z]/.test(value)) {
        errors.push('Mindestens ein Großbuchstabe erforderlich');
    }
    return errors;
}

/**
 * Validiert ein Passwort
 * Anforderungen: Min. 10 Zeichen
 */
function validatePassword(value) {
    const errors = [];
    if (value.length < 10) {
        errors.push('Mindestens 10 Zeichen erforderlich');
    }
    return errors;
}

/**
 * Prüft ob zwei Passwörter übereinstimmen
 */
function validatePasswordMatch(password, passwordRepeat) {
    if (password !== passwordRepeat) {
        return ['Passwörter stimmen nicht überein'];
    }
    return [];
}

/**
 * Validiert ein einzelnes Feld und zeigt visuelle Rückmeldung
 */
function validateField(field, validator, ...args) {
    const value = field.value;
    const errorSpan = document.getElementById(field.id + '-error');
    const errors = validator(value, ...args);

    if (errors.length > 0) {
        field.classList.add('invalid');
        field.classList.remove('valid');
        if (errorSpan) errorSpan.textContent = errors[0];
        return false;
    } else if (value.length > 0) {
        field.classList.remove('invalid');
        field.classList.add('valid');
        if (errorSpan) errorSpan.textContent = '';
        return true;
    } else {
        field.classList.remove('invalid', 'valid');
        if (errorSpan) errorSpan.textContent = '';
        return false;
    }
}

// ===== REGISTRIERUNGS-FORMULAR =====

function initRegistrationForm() {
    const form = document.getElementById('registrationForm');
    if (!form) return;

    const benutzername = document.getElementById('benutzername');
    const passwort = document.getElementById('passwort');
    const passwortWiederholen = document.getElementById('passwort_wiederholen');
    const submitBtn = document.getElementById('submitBtn');

    // Formular-Gültigkeit prüfen
    function checkFormValidity() {
        const usernameValid = validateUsername(benutzername.value).length === 0;
        const passwordValid = validatePassword(passwort.value).length === 0;
        const passwordMatchValid = validatePasswordMatch(passwort.value, passwortWiederholen.value).length === 0;

        const isValid = usernameValid && passwordValid && passwordMatchValid && 
                       benutzername.value.length > 0 && passwort.value.length > 0;

        submitBtn.disabled = !isValid;
    }

    // Event-Listener für Echtzeit-Validierung
    benutzername.addEventListener('input', () => {
        validateField(benutzername, validateUsername);
        checkFormValidity();
    });

    passwort.addEventListener('input', () => {
        validateField(passwort, validatePassword);
        if (passwortWiederholen.value.length > 0) {
            validateField(passwortWiederholen, validatePasswordMatch, passwort.value);
        }
        checkFormValidity();
    });

    passwortWiederholen.addEventListener('input', () => {
        validateField(passwortWiederholen, validatePasswordMatch, passwort.value);
        checkFormValidity();
    });

    // Form-Submit
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!submitBtn.disabled) {
            alert('Registrierung erfolgreich!');
            localStorage.setItem('registeredUser', benutzername.value);
            window.location.href = 'login.html';
        }
    });

    // Initial Button deaktivieren
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

    // Button-Status prüfen
    function checkFormValidity() {
        const usernameValid = validateUsername(username.value).length === 0;
        const passwordValid = validatePassword(password.value).length === 0;
        const isValid = usernameValid && passwordValid && username.value.length > 0 && password.value.length > 0;
        loginBtn.disabled = !isValid;
    }

    // Event-Listener
    username.addEventListener('input', () => {
        validateField(username, validateUsername);
        checkFormValidity();
    });

    password.addEventListener('input', () => {
        validateField(password, validatePassword);
        checkFormValidity();
    });

    // Form-Submit
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        if (loginBtn.disabled) return;

        // Demo-Zugangsdaten (erfüllen die Validierungskriterien)
        if (username.value === 'TestUser' && password.value === 'TestPass123') {
            localStorage.setItem('loggedIn', 'true');
            localStorage.setItem('username', username.value);
            errorMessage.style.display = 'none';
            window.location.href = 'user.html';
        } else {
            errorMessage.textContent = 'Falscher Benutzername oder Passwort!';
            errorMessage.style.display = 'block';
        }
    });

    // Initial Button deaktivieren
    loginBtn.disabled = true;
}

// ===== BENUTZERPROFIL-FORMULAR =====

function initUserForm() {
    const userForm = document.getElementById('userForm');
    if (!userForm) return;

    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    const saveBtn = document.getElementById('saveBtn');

    // Formular-Gültigkeit prüfen
    function checkFormValidity() {
        const usernameValid = validateUsername(username.value).length === 0;
        const passwordValid = validatePassword(password.value).length === 0;
        const passwordMatchValid = validatePasswordMatch(password.value, passwordConfirm.value).length === 0;

        const isValid = usernameValid && passwordValid && passwordMatchValid && 
                       username.value.length > 0 && password.value.length > 0;

        saveBtn.disabled = !isValid;
    }

    // Event-Listener
    username.addEventListener('input', () => {
        validateField(username, validateUsername);
        checkFormValidity();
    });

    password.addEventListener('input', () => {
        validateField(password, validatePassword);
        if (passwordConfirm.value.length > 0) {
            validateField(passwordConfirm, validatePasswordMatch, password.value);
        }
        checkFormValidity();
    });

    passwordConfirm.addEventListener('input', () => {
        validateField(passwordConfirm, validatePasswordMatch, password.value);
        checkFormValidity();
    });

    // Form-Submit
    userForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!saveBtn.disabled) {
            alert('Änderungen erfolgreich gespeichert!');
            localStorage.setItem('username', username.value);
        }
    });

    // Gespeicherten Benutzernamen laden
    const savedUsername = localStorage.getItem('username');
    if (savedUsername) {
        username.value = savedUsername;
        validateField(username, validateUsername);
    }
    saveBtn.disabled = true;
}

// ===== INITIALISIERUNG =====

// Wird ausgeführt wenn das DOM vollständig geladen ist
document.addEventListener('DOMContentLoaded', function() {
    initRegistrationForm();
    initLoginForm();
    initUserForm();
});
