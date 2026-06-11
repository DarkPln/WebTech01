// ===== NUTZERBEREICH (Dashboard) =====
// Autor: tim

function downloadMsgPDF(btn) {
    if (!window.jspdf) { alert('PDF-Bibliothek nicht geladen.'); return; }
    const { jsPDF } = window.jspdf;

    const title = btn.dataset.title || '';
    const body  = btn.dataset.body  || '';
    const date  = btn.dataset.date  || '';

    const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
    const red = [204, 0, 0];
    const dark = [20, 20, 20];
    const grey = [110, 110, 110];

    // Header
    doc.setFontSize(26);
    doc.setFont('helvetica', 'bold');
    doc.setTextColor(...red);
    doc.text('AUTO', 20, 22);
    doc.setTextColor(...dark);
    doc.text('24', 46, 22);

    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(...grey);
    doc.text('Benachrichtigung', 20, 29);

    // Trennlinie
    doc.setDrawColor(...red);
    doc.setLineWidth(0.5);
    doc.line(20, 33, 190, 33);

    // Datum
    doc.setFontSize(9);
    doc.setTextColor(...grey);
    doc.text(date, 190, 40, { align: 'right' });

    // Titel
    doc.setFontSize(16);
    doc.setFont('helvetica', 'bold');
    doc.setTextColor(...dark);
    doc.text(title, 20, 50);

    // Body
    if (body) {
        doc.setFontSize(11);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(50, 50, 50);
        const lines = doc.splitTextToSize(body, 170);
        doc.text(lines, 20, 62);
    }

    // Footer
    doc.setDrawColor(220, 220, 220);
    doc.setLineWidth(0.3);
    doc.line(20, 278, 190, 278);
    doc.setFontSize(8);
    doc.setTextColor(160, 160, 160);
    doc.text('AUTO24 – Ihr Fahrzeugportal', 105, 284, { align: 'center' });

    const filename = 'AUTO24_' + title.replace(/[^a-zA-Z0-9äöüÄÖÜ]/g, '_').slice(0, 40) + '.pdf';
    doc.save(filename);
}

function downloadBookingPDF(btn) {
    if (!window.jspdf) { alert('PDF-Bibliothek nicht geladen.'); return; }

    const car      = btn.dataset.car    || '';
    const price    = btn.dataset.price  || '';
    const status   = btn.dataset.status || '';
    const date     = btn.dataset.date   || '';
    const reason   = btn.dataset.reason || '';
    const imgSrc   = btn.dataset.img    || '';
    const isStorno = btn.dataset.storno === '1';
    const docType  = isStorno ? 'Storno-Bestätigung' : 'Buchungsbestätigung';

    function buildPDF(imgDataUrl) {
        const { jsPDF } = window.jspdf;
        const doc  = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
        const red  = [204, 0, 0];
        const dark = [20, 20, 20];
        const grey = [110, 110, 110];

        // ── Header ──────────────────────────────────────────────
        doc.setFontSize(26);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(...red);
        doc.text('AUTO', 20, 22);
        doc.setTextColor(...dark);
        doc.text('24', 46, 22);

        doc.setFontSize(9);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(...grey);
        doc.text(docType, 20, 29);

        doc.setDrawColor(...red);
        doc.setLineWidth(0.5);
        doc.line(20, 33, 190, 33);

        // ── Fahrzeugbild ─────────────────────────────────────────
        let detailY = 40;
        if (imgDataUrl) {
            doc.addImage(imgDataUrl, 'JPEG', 20, 38, 170, 82);
            detailY = 128;
        }

        // ── Datum ────────────────────────────────────────────────
        doc.setFontSize(9);
        doc.setTextColor(...grey);
        doc.text('Datum: ' + date, 190, detailY, { align: 'right' });
        detailY += 10;

        // ── Fahrzeugname ─────────────────────────────────────────
        doc.setFontSize(16);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(...dark);
        doc.text(car, 20, detailY);
        detailY += 10;

        // ── Details ──────────────────────────────────────────────
        doc.setFontSize(11);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(50, 50, 50);
        doc.text('Preis:   ' + price + ' €', 20, detailY);
        detailY += 8;
        doc.text('Status:  ' + status, 20, detailY);
        detailY += 8;

        if (reason) {
            doc.setFontSize(10);
            doc.setTextColor(...grey);
            const lines = doc.splitTextToSize('Hinweis: ' + reason, 170);
            doc.text(lines, 20, detailY);
        }

        // ── Footer ───────────────────────────────────────────────
        doc.setDrawColor(220, 220, 220);
        doc.setLineWidth(0.3);
        doc.line(20, 267, 190, 267);

        doc.setFontSize(7.5);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(150, 150, 150);
        doc.text(
            'AUTO24 GmbH  •  Altschauerberg 8, 85049 Ingolstadt  •  info@auto24.de  •  +49 (0) 841 / 123 456',
            105, 273, { align: 'center' }
        );
        doc.text(
            'Mo–Fr 8:00–18:00 Uhr  •  Geschäftsführer: Lukas Neumayer, Niclas Reuter, Tim Höhn',
            105, 279, { align: 'center' }
        );

        const safeName = car.replace(/[^a-zA-Z0-9äöüÄÖÜ]/g, '_').slice(0, 40);
        doc.save('AUTO24_' + docType + '_' + safeName + '.pdf');
    }

    if (imgSrc) {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function () {
            const canvas = document.createElement('canvas');
            canvas.width  = img.naturalWidth;
            canvas.height = img.naturalHeight;
            canvas.getContext('2d').drawImage(img, 0, 0);
            buildPDF(canvas.toDataURL('image/jpeg', 0.85));
        };
        img.onerror = function () { buildPDF(null); };
        img.src = imgSrc;
    } else {
        buildPDF(null);
    }
}

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
        const r    = await fetch('api/messages/unread_count.php');
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
    const r = await fetch('api/messages/list_html.php');
    container.innerHTML = await r.text();
    loadUnreadBadge();
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
async function renderUserInserate() {
    var container = document.getElementById('userInserate');
    if (!container) return;
    const antwort       = await fetch('api/listings/list_html.php');
    container.innerHTML = await antwort.text();
}

function initUserForm() {
    if (!document.getElementById('tabProfil')) return;

    if (!authState.loggedIn) {
        window.location.href = 'login.php';
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
        const r    = await fetch('api/auth/update.php', {
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
        const r    = await fetch('api/auth/update.php', {
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
            await fetch('api/messages/mark_read.php', { method: 'POST' });
            await loadMessages();
            loadUnreadBadge();
        });
    }

    // --- Initial load for default tab ---
    renderUserInserate();
}
