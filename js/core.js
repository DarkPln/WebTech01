// ===== GEMEINSAME VALIDIERUNGSFUNKTIONEN =====
// Autor: tim

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
