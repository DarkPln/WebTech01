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



// fav logik: Lukas 

const favorites = new Map();
let notTimer = null;

function showNotification(message) {
    let not = document.getElementById('favNot');
    if (!not) {
        not = document.createElement('div');
        not.id = 'favNot';
        not.className = 'fav-notification';
        document.body.appendChild(not);
    }
    not.textContent = message;
    not.classList.add('show');
    clearTimeout(notTimer);
    notTimer = setTimeout(() => not.classList.remove('show'), 2500);
}

// Panel umschalten
const favOvl = document.getElementById('favOvl');
const favList = document.getElementById('favList');

function togglePanel() {
    if (!favOvl || !favList) return;
    const isActive = favList.classList.toggle('active');
    favOvl.classList.toggle('active', isActive);
    document.body.style.overflow = isActive ? 'hidden' : '';
}
window.togglePanel = togglePanel;

favList?.addEventListener('click', e => e.stopPropagation());

function getCarData(card) {
    const imageSrc = card.dataset.image || card.dataset.img || card.querySelector('img')?.src || card.closest('.car-card')?.querySelector('img')?.src || '';
    return {
        id: card.dataset.id || `${card.querySelector('.car-make')?.textContent || ''}-${card.querySelector('.car-model')?.textContent || ''}`.trim().replace(/\s+/g, '-').toLowerCase(),
        make: card.dataset.make || card.querySelector('.car-make')?.textContent?.trim() || '',
        model: card.dataset.model || card.querySelector('.car-model')?.textContent?.trim() || '',
        year: card.dataset.year || card.querySelector('.car-year')?.textContent?.trim().split('·')[0]?.trim() || '',
        fuel: card.dataset.fuel || card.querySelector('.car-year')?.textContent?.trim().split('·')[1]?.trim() || '',
        km: card.dataset.km || card.querySelector('.car-spec .car-spec-val')?.textContent?.trim() || '',
        price: card.dataset.price || card.querySelector('.car-price')?.textContent?.trim() || '0 €',
        image: imageSrc
    };
}

function renderList() {
    const list = document.getElementById('favItems');
    if (!list) return;
    list.innerHTML = '';

    favorites.forEach((car, id) => {
        const item = document.createElement('div');
        item.className = 'fav-item';
        item.dataset.id = id;
        item.innerHTML = `
            <div class="fav-item-preview">
                ${car.image ? `<img src="${car.image}" alt="${car.make} ${car.model}" />` : ''}
            </div>
            <div class="fav-item-info">
                <div class="fav-item-title">${car.make} ${car.model}</div>
                <div class="fav-item-price">${car.price}</div>
                <div class="fav-item-meta">${car.year} · ${car.fuel} · ${car.km}</div>
            </div>
            <button class="fav-item-remove-btn" onclick="removeFav('${id}')" title="Entfernen">✕</button>
        `;
        list.appendChild(item);
    });
}

function updateUI() {
    const counter = favorites.size;
    const count = document.getElementById('favCount');
    const counterEl = document.getElementById('favListCount');
    const footer = document.getElementById('favListFooter');
    const emptyEl = document.getElementById('favListEmpty');
    const totalCostEl = document.getElementById('totalCostValue');

    if (count) {
        count.textContent = counter;
        count.style.display = counter > 0 ? 'flex' : 'none';
    }
    if (counterEl) {
        counterEl.textContent =
            counter === 0 ? '0 Fahrzeuge in den Favoriten' :
            counter === 1 ? '1 Fahrzeug in den Favoriten' :
            `${counter} Fahrzeuge in den Favoriten`;
    }
    if (emptyEl) emptyEl.style.display = counter === 0 ? 'flex' : 'none';
    if (footer) footer.style.display = counter > 0 ? 'block' : 'none';

    if (totalCostEl) {
        const total = Array.from(favorites.values()).reduce((sum, car) => {
            return sum + (parseInt(car.price.replace(/[^0-9]/g, ''), 10) || 0);
        }, 0);
        totalCostEl.textContent = total.toLocaleString('de-DE') + ' €';
    }

    renderList();
}

function toggleFavorite(btn) {
    const card = btn.closest('.car-card');
    const source = card || btn;

    const car = getCarData(source);
    if (!car.id) return;

    if (favorites.has(car.id)) {
        favorites.delete(car.id);
        btn.textContent = '♡';
        btn.classList.remove('active');
        showNotification(`${car.make} ${car.model} entfernt`);
    } else {
        favorites.set(car.id, car);
        btn.textContent = '♥';
        btn.classList.add('active');
        showNotification(`${car.make} ${car.model} hinzugefügt`);
    }

    updateUI();
}

function removeFav(id) {
    const car = favorites.get(id);
    if (!car) return;
    favorites.delete(id);
    const card = document.querySelector(`.car-card[data-id="${id}"]`);
    if (card) {
        const btn = card.querySelector('.car-fav');
        if (btn) {
            btn.textContent = '♡';
            btn.classList.remove('active');
        }
    }
    showNotification(`${car.make} ${car.model} entfernt`);
    updateUI();
}

function clearAllFavs() {
    favorites.clear();
    document.querySelectorAll('.car-card .car-fav.active').forEach(btn => {
        btn.textContent = '♡';
        btn.classList.remove('active');
    });
    showNotification('Favoriten geleert');
    updateUI();
}

// Initialisierung der Favoriten-Logik:
// Fügt für alle Elemente mit `.car-fav` einen Klick-Listener hinzu,
// der `toggleFavorite(btn)` aufruft. Dadurch werden Herz-Klicks in
// der Liste verarbeitet (hinzufügen/entfernen von Favoriten).
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.car-fav').forEach(btn => {
        btn.addEventListener('click', event => {
            event.stopPropagation();
            toggleFavorite(btn);
        });
    });

    document.querySelectorAll('.fav-list-btn').forEach(btn => {
        btn.addEventListener('click', event => {
            event.stopPropagation();
            toggleFavorite(btn);
        });
    });

    document.getElementById('clearFavListBtn')?.addEventListener('click', clearAllFavs);
    updateUI();
});

// fav logik ende Lukas 
