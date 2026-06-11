// ===== NUTZERBEREICH (Dashboard) =====
// Autor: tim

function showMsg(el, text) {
    if (!el) return;
    el.textContent = text;
    el.style.display = 'block';
    setTimeout(() => { el.style.display = 'none'; }, 3500);
}

async function loadUnreadBadge() {
    var badge = document.getElementById('msgBadge');
    if (!badge) return;
    try {
        const r    = await fetch(BASE_URL + '/api/messages/unread-count');
        const data = await r.json();
        if (data.count > 0) {
            badge.textContent = data.count;
            badge.style.display = 'inline-flex';
        } else {
            badge.style.display = 'none';
        }
    } catch (e) {
        badge.style.display = 'none';
    }
}

async function loadMessages() {
    var container = document.getElementById('messagesContainer');
    if (!container) return;
    const r = await fetch(BASE_URL + '/api/messages/list-html');
    container.innerHTML = await r.text();
    loadUnreadBadge();
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
async function renderUserInserate() {
    var container = document.getElementById('userInserate');
    if (!container) return;
    const antwort       = await fetch(BASE_URL + '/api/listings/list-html');
    container.innerHTML = await antwort.text();
}

function initUserForm() {
    if (!document.getElementById('tabProfil')) return;

    if (!authState.loggedIn) {
        window.location.href = BASE_URL + '/auth/login';
        return;
    }

    // --- Header ---
    var displayName = document.getElementById('display-username');
    if (displayName) displayName.textContent = authState.username;

    var adminLink = document.getElementById('adminDashboardLink');
    if (adminLink && authState.isAdmin) adminLink.style.display = 'inline-block';

    // --- Tab switching ---
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

    // --- Profile form ---
    var profileForm   = document.getElementById('profileForm');
    var usernameInput = document.getElementById('username');
    var emailInput    = document.getElementById('email');
    var phoneInput    = document.getElementById('phone');
    var cityInput     = document.getElementById('city');
    var profileSave   = document.getElementById('profileSaveBtn');
    var profileOk     = document.getElementById('profileSuccess');
    var profileErr    = document.getElementById('profileError');

    if (usernameInput) usernameInput.value = authState.username;
    if (emailInput && authState.email) emailInput.value = authState.email;
    if (phoneInput && authState.phone) phoneInput.value = authState.phone;
    if (cityInput  && authState.city)  cityInput.value  = authState.city;

    function checkProfileValidity() {
        profileSave.disabled = validateUsername(usernameInput.value).length > 0;
    }

    usernameInput.addEventListener('input', () => {
        validateField(usernameInput, validateUsername);
        if (profileErr) profileErr.style.display = 'none';
        checkProfileValidity();
    });

    profileForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (profileSave.disabled) return;
        const r    = await fetch(BASE_URL + '/api/auth/update', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({
                username: usernameInput.value.trim(),
                email:    emailInput ? emailInput.value.trim() : '',
                phone:    phoneInput ? phoneInput.value.trim() : '',
                city:     cityInput  ? cityInput.value.trim()  : '',
            }),
        });
        const data = await r.json();
        if (data.success) {
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

    checkProfileValidity();

    // --- Password form ---
    var passwordForm    = document.getElementById('passwordForm');
    var passwordInput   = document.getElementById('password');
    var passwordConfirm = document.getElementById('password_confirm');
    var passwordSave    = document.getElementById('passwordSaveBtn');
    var passwordOk      = document.getElementById('passwordSuccess');
    var passwordErr     = document.getElementById('passwordError');

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

    passwordForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (passwordSave.disabled) return;
        const r    = await fetch(BASE_URL + '/api/auth/update', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({
                username: authState.username,
                password: passwordInput.value,
                email:    authState.email || '',
                phone:    authState.phone || '',
            }),
        });
        const data = await r.json();
        if (data.success) {
            passwordInput.value   = '';
            passwordConfirm.value = '';
            checkPasswordValidity();
            showMsg(passwordOk, 'Passwort erfolgreich geändert!');
        } else {
            showMsg(passwordErr, data.message || 'Fehler beim Ändern.');
        }
    });

    checkPasswordValidity();

    // --- Unread badge + mark-all-read ---
    loadUnreadBadge();
    var markBtn = document.getElementById('markAllReadBtn');
    if (markBtn) {
        markBtn.addEventListener('click', async () => {
            await fetch(BASE_URL + '/api/messages/mark-read', { method: 'POST' });
            await loadMessages();
            loadUnreadBadge();
        });
    }

    // --- Initial load for default tab ---
    renderUserInserate();
}
