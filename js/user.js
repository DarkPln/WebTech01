//Tim

// Meldung in ein Element schreiben und nach 3,5 Sekunden wieder ausblenden
function showMsg(el, text) {
    if (!el) return;
    el.textContent = text;
    el.style.display = 'block';
    setTimeout(() => { el.style.display = 'none'; }, 3500);
}

// Anzahl ungelesener Nachrichten laden und Badge in der Nav aktualisieren
function loadUnreadBadge() {
    var badge = document.getElementById('msgBadge');
    if (!badge) return;
    fetch(BASE_URL + '/api/messages/unread-count')
        .then(r => r.json())
        .then(data => {
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'inline-flex';
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(() => {
            badge.style.display = 'none';
        });
}

// Nachrichten-HTML vom Server laden, in den Container einsetzen und Badge aktualisieren
function loadMessages() {
    var container = document.getElementById('messagesContainer');
    if (!container) return Promise.resolve();
    return fetch(BASE_URL + '/api/messages/list-html')
        .then(r => r.text())
        .then(text => {
            container.innerHTML = text;
            loadUnreadBadge();
        });
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
function renderUserInserate() {
    var container = document.getElementById('userInserate');
    if (!container) return Promise.resolve();
    return fetch(BASE_URL + '/api/listings/list-html')
        .then(antwort => antwort.text())
        .then(text => { container.innerHTML = text; });
}


// Lukas 


// Nutzerprofil-Seite initialisieren: Tabs, Profilformular und Passwortformular verdrahten
function initUserForm() {
    if (!document.getElementById('tabProfil')) return;

    // Nicht eingeloggte Nutzer zur Login-Seite weiterleiten
    if (!authState.loggedIn) {
        window.location.href = BASE_URL + '/auth/login';
        return;
    }

    var displayName = document.getElementById('display-username');
    if (displayName) displayName.textContent = authState.username;

    // Admin-Dashboard-Link nur für Admins einblenden
    var adminLink = document.getElementById('adminDashboardLink');
    if (adminLink && authState.isAdmin) adminLink.style.display = 'inline-block';

    // Tab-Klick: aktiven Tab wechseln und Tab-Inhalt bei Bedarf nachladen
    document.querySelectorAll('.admin-tab[data-target]').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.admin-tab-content').forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            var panel = document.getElementById(tab.dataset.target);
            if (panel) panel.classList.add('active');

            if (tab.dataset.target === 'tabNachrichten') loadMessages();
            if (tab.dataset.target === 'tabBuchungen')   renderBookingsPage();
            if (tab.dataset.target === 'tabInserate')    renderUserInserate();
        });
    });

    var profileForm   = document.getElementById('profileForm');
    var usernameInput = document.getElementById('username');
    var emailInput    = document.getElementById('email');
    var phoneInput    = document.getElementById('phone');
    var cityInput     = document.getElementById('city');
    var profileSave   = document.getElementById('profileSaveBtn');
    var profileOk     = document.getElementById('profileSuccess');
    var profileErr    = document.getElementById('profileError');

    // Formularfelder mit aktuellen Profildaten aus authState vorbelegen
    if (usernameInput) usernameInput.value = authState.username;
    if (emailInput && authState.email) emailInput.value = authState.email;
    if (phoneInput && authState.phone) phoneInput.value = authState.phone;
    if (cityInput  && authState.city)  cityInput.value  = authState.city;

    // Speichern-Button deaktivieren wenn der Username ungültig ist
    function checkProfileValidity() {
        profileSave.disabled = validateUsername(usernameInput.value).length > 0;
    }

    usernameInput.addEventListener('input', () => {
        validateField(usernameInput, validateUsername);
        if (profileErr) profileErr.style.display = 'none';
        checkProfileValidity();
    });

    // Profildaten per fetch() speichern und authState sofort aktualisieren
    profileForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (profileSave.disabled) return;
        fetch(BASE_URL + '/api/auth/update', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({
                username: usernameInput.value.trim(),
                email:    emailInput ? emailInput.value.trim() : '',
                phone:    phoneInput ? phoneInput.value.trim() : '',
                city:     cityInput  ? cityInput.value.trim()  : '',
            }),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // authState sofort aktualisieren damit Nav-Anzeige ohne Reload stimmt
                    authState.username = data.username;
                    if (displayName) displayName.textContent = data.username;
                    if (emailInput) authState.email = emailInput.value.trim();
                    if (phoneInput) authState.phone = phoneInput.value.trim();
                    if (cityInput)  authState.city  = cityInput.value.trim();
                    showMsg(profileOk, 'Profil gespeichert!');
                } else {
                    showMsg(profileErr, data.message || 'Fehler beim Speichern.');
                }
            });
    });

    checkProfileValidity();

    var passwordForm    = document.getElementById('passwordForm');
    var passwordInput   = document.getElementById('password');
    var passwordConfirm = document.getElementById('password_confirm');
    var passwordSave    = document.getElementById('passwordSaveBtn');
    var passwordOk      = document.getElementById('passwordSuccess');
    var passwordErr     = document.getElementById('passwordError');

    // Speichern-Button nur freischalten wenn Passwort gültig und Wiederholung übereinstimmt
    function checkPasswordValidity() {
        passwordSave.disabled =
            validatePassword(passwordInput.value).length > 0 ||
            validatePasswordMatch(passwordInput.value, passwordConfirm.value).length > 0;
    }

    passwordInput.addEventListener('input', () => {
        validateField(passwordInput, validatePassword);
        if (passwordConfirm.value.length > 0)
            validateField(passwordConfirm, validatePasswordMatch, passwordInput.value);
        if (passwordErr) passwordErr.style.display = 'none';
        checkPasswordValidity();
    });
    passwordConfirm.addEventListener('input', () => {
        validateField(passwordConfirm, validatePasswordMatch, passwordInput.value);
        checkPasswordValidity();
    });

    // Passwort per fetch() ändern, Felder nach Erfolg leeren
    passwordForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (passwordSave.disabled) return;
        fetch(BASE_URL + '/api/auth/update', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({
                username: authState.username,
                password: passwordInput.value,
                email:    authState.email || '',
                phone:    authState.phone || '',
            }),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    passwordInput.value   = '';
                    passwordConfirm.value = '';
                    checkPasswordValidity();
                    showMsg(passwordOk, 'Passwort erfolgreich geändert!');
                } else {
                    showMsg(passwordErr, data.message || 'Fehler beim Ändern.');
                }
            });
    });

    checkPasswordValidity();

    loadUnreadBadge();
    // "Alle als gelesen markieren"-Button, alle Nachrichten auf gelesen setzen und Liste neu laden
    var markBtn = document.getElementById('markAllReadBtn');
    if (markBtn) {
        markBtn.addEventListener('click', () => {
            fetch(BASE_URL + '/api/messages/mark-read', { method: 'POST' })
                .then(() => loadMessages())
                .then(() => loadUnreadBadge());
        });
    }

    renderUserInserate();
}
