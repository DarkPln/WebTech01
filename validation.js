// Formularvalidierung für Auto24
// Autor: tim
// ===== GEMEINSAME VALIDIERUNGSFUNKTIONEN =====

// Gibt ein Array mit Fehlermeldungen zurück – leeres Array bedeutet "gültig".
// Regex-Test: /[a-z]/.test(value) gibt true, wenn mindestens ein Kleinbuchstabe vorkommt.
// Dieses Muster (Fehler sammeln statt sofort abbrechen) erlaubt mehrere Fehlermeldungen gleichzeitig.
function validateUsername(value) {
    const errors = [];
    if (value.length < 5) errors.push('Mindestens 5 Zeichen erforderlich');
    if (!/[a-z]/.test(value)) errors.push('Mindestens ein Kleinbuchstabe erforderlich');
    if (!/[A-Z]/.test(value)) errors.push('Mindestens ein Großbuchstabe erforderlich');
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
    const errors = extraArg !== undefined
        ? validator(field.value, extraArg)  // z.B. validatePasswordMatch(wert, erstesPasswort)
        : validator(field.value);           // z.B. validateUsername(wert)

    // Sucht den zugehörigen Fehler-<span> anhand der Feld-ID.
    // Konvention: Feld-ID "passwort" → Span-ID "passwort-error".
    const errorSpan = document.getElementById(field.id + '-error');

    // Beide Klassen zuerst entfernen, damit nie beide gleichzeitig gesetzt sind.
    field.classList.remove('valid', 'invalid');

    // Fall 1: Es gibt Fehler → Feld rot markieren und ersten Fehler anzeigen.
    if (errors.length > 0) {
        field.classList.add('invalid');
        if (errorSpan) errorSpan.textContent = errors[0];
        return false;
    }

    // Fall 2: Keine Fehler und Feld hat Inhalt → Feld grün markieren.
    if (field.value.length > 0) {
        field.classList.add('valid');
        if (errorSpan) errorSpan.textContent = '';
        return true;
    }

    // Fall 3: Feld ist leer → keine Markierung, kein Fehlertext.
    if (errorSpan) errorSpan.textContent = '';
    return false;
}

// ===== NUTZERVERWALTUNG (localStorage) =====

// localStorage ist ein persistenter Schlüssel-Wert-Speicher im Browser – Daten bleiben nach
// dem Schließen des Tabs erhalten. Er speichert nur Strings, daher JSON für Objekte/Arrays.
// JSON.parse() wandelt den gespeicherten JSON-String zurück in ein JS-Array.
// Der || '[]' Fallback liefert ein leeres Array, wenn der Schlüssel noch nicht existiert.
function getUsers() {
    return JSON.parse(localStorage.getItem('auto24_users') || '[]');
}

// JSON.stringify() serialisiert das JS-Array in einen String, der im localStorage abgelegt wird.
function saveUsers(users) {
    localStorage.setItem('auto24_users', JSON.stringify(users));
}

// Array.find() gibt das erste Element zurück, für das die Callback-Funktion true ergibt.
// Arrow-Function als Callback: u => u.username === username – kurz und lesbar.
// || null stellt sicher, dass der Rückgabewert explizit null ist (nicht undefined).
function findUser(username) {
    return getUsers().find(u => u.username === username) || null;
}

// ===== REGISTRIERUNGS-FORMULAR =====

// Initialisierungsfunktion: prüft zuerst, ob das Formular auf dieser Seite existiert.
// Das ermöglicht es, dieses Skript auf allen Seiten einzubinden – ohne Fehler.
// Alle DOM-Referenzen werden einmalig abgefragt (Performance) und in Konstanten gespeichert.
function initRegistrationForm() {
    const form = document.getElementById('registrationForm');
    if (!form) return;

    const benutzername = document.getElementById('benutzername');
    const passwort = document.getElementById('passwort');
    const passwortWiederholen = document.getElementById('passwort_wiederholen');
    const submitBtn = document.getElementById('submitBtn');
    const errorMessage = document.getElementById('reg-error');

    // checkFormValidity() ist eine innere Funktion mit Zugriff auf alle äußeren
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
    // Arrow-Functions (() => {...}) als Handler binden kein eigenes 'this'.
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
    // window.location.href = ... führt eine programmatische Navigation durch.
    // '?registered=1' im URL übergibt einen Parameter an die Login-Seite.
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
        window.location.href = 'login.html?registered=1';
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

    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (loginBtn.disabled) return;

        const uname = username.value.trim();
        const pwd = password.value;
        const user = findUser(uname);
        // Hardcoded Demo-Zugangsdaten als Fallback für Tests ohne vorherige Registrierung.
        const isDemo = uname === 'TestUser' && pwd === 'TestPass123';

        // Optional Chaining (?.): user?.password gibt undefined zurück wenn user null ist –
        // kein Fehler, kein extra null-Check nötig.
        if (user?.password === pwd || isDemo) {
            localStorage.setItem('loggedIn', 'true');
            localStorage.setItem('loggedInUser', uname);
            window.location.href = 'user.html';
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
        // -1 bedeutet "nicht gefunden" – nur dann updaten, wenn der Nutzer existiert.
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
