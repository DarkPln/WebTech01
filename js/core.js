// Lukas

// username check 
function validateUsername(value) {
    const errors = [];
    if (value.length < 5) errors.push('Mindestens 5 Zeichen erforderlich');
    if (value === value.toUpperCase()) errors.push('Mindestens ein Kleinbuchstabe erforderlich');
    if (value === value.toLowerCase()) errors.push('Mindestens ein Großbuchstabe erforderlich');
    return errors;
}


// pw check 
function validatePassword(value) {
    const errors = [];
    if (value.length < 10) errors.push('Mindestens 10 Zeichen erforderlich');
    return errors;
}

// pw match check 
function validatePasswordMatch(password, passwordRepeat) {
    if (password !== passwordRepeat) return ['Passwörter stimmen nicht überein'];
    return [];
}

// Setzen CSS Klassen bzw visuelle änderung eingabefelder & fehlermeldung 

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



// wird beim seitenstart einmal geladen; alle anderen Funktionen lesen dieses objekt
let authState = { loggedIn: false, username: '', isAdmin: false, isLocked: false };

function loadAuthState() {
    return fetch(BASE_URL + '/api/auth/status')
        .then(r => r.json())
        .then(data => { authState = data; })
        .catch(() => { authState = { loggedIn: false }; });
}
