//Tim

// Admin-Seite initialisieren: Dashboard zeigen wenn eingeloggt, sonst zu Login weiterleiten
function initAdminPage() {
    var loginBereich = document.getElementById('adminLoginSection');
    var dashboard    = document.getElementById('adminDashboard');
    if (!loginBereich && !dashboard) return Promise.resolve();

    if (authState.isAdmin) {
        if (loginBereich) loginBereich.style.display = 'none';
        if (dashboard)    dashboard.style.display    = 'block';

        return renderAdminOrders()
            .then(() => renderAdminUsers())
            .then(() => renderAdminInserate())
            .then(() => renderAdminCars())
            .then(() => {
                document.querySelectorAll('.admin-tab').forEach(function(tab) {
                    tab.addEventListener('click', function() {
                        document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
                        document.querySelectorAll('.admin-tab-content').forEach(c => c.classList.remove('active'));
                        tab.classList.add('active');
                        var zielBereich = document.getElementById(tab.dataset.target);
                        if (zielBereich) zielBereich.classList.add('active');
                    });
                });
            });
    } else {
        window.location.href = BASE_URL + '/auth/login';
        return Promise.resolve();
    }
}

// Alle vier Auftrags-Tabs parallel vom Server laden und befüllen
function renderAdminOrders() {
    const bereiche     = ['new', 'processing', 'rejected', 'completed'];
    const containerIds = ['adminOrdersNew', 'adminOrdersProcessing', 'adminOrdersRejected', 'adminOrdersCompleted'];

    return Promise.all(
        bereiche.map(b => fetch(BASE_URL + '/api/admin/orders-html?bereich=' + b).then(r => r.text()))
    ).then(antworten => {
        bereiche.forEach((_, i) => {
            document.getElementById(containerIds[i]).innerHTML = antworten[i];
        });
    });
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
function renderAdminInserate() {
    var container = document.getElementById('adminInserate');
    if (!container) return Promise.resolve();
    return fetch(BASE_URL + '/api/admin/listings-html')
        .then(antwort => antwort.text())
        .then(text => { container.innerHTML = text; });
}

// Inserat-Status (genehmigt/abgelehnt) an den Server schicken und Tab neu laden
function adminSetInseratStatus(id, status) {
    return fetch(BASE_URL + '/api/admin/listings', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id, status })
    }).then(() => renderAdminInserate());
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
function renderAdminUsers() {
    var container = document.getElementById('adminUsersList');
    if (!container) return Promise.resolve();
    return fetch(BASE_URL + '/api/admin/users-html')
        .then(antwort => antwort.text())
        .then(text => { container.innerHTML = text; });
}

// Buchungs-Status ändern und optional einen Ablehnungsgrund mitschicken, danach Tabs neu laden
function adminSetStatus(bookingId, status, reason) {
    return fetch(BASE_URL + '/api/admin/orders', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id: bookingId, status, reason: reason || '' })
    }).then(() => renderAdminOrders());
}

// Lukas

// buchung ablehnen; mit grund der vom admin formuliert werden kann 

function adminRejectOrder(bookingId) {
    var reason = prompt('Bitte geben Sie einen Ablehnungsgrund an (z.B. nicht verfügbare Items):');
    if (reason === null) return;
    return adminSetStatus(bookingId, 'abgelehnt', reason || 'Kein Grund angegeben');
}


// user sperrung 

function adminToggleLock(username) {
    return fetch(BASE_URL + '/api/admin/users', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ username })
    }).then(() => renderAdminUsers());
}


// fahrzeuge anzeigen für löschung

function renderAdminCars() {
    var container = document.getElementById('adminCars');
    if (!container) return Promise.resolve();
    return fetch(BASE_URL + '/api/admin/cars-html')
        .then(antwort => antwort.text())
        .then(text => { container.innerHTML = text; });
}

// löschung auto 

function adminDeleteCar(iid, btn) {
    if (!confirm('Fahrzeug dauerhaft löschen?')) return;
    btn.disabled = true;
    fetch(BASE_URL + '/api/admin/cars', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id: iid })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                return renderAdminCars();
            } else {
                alert(data.message || 'Löschen fehlgeschlagen');
                btn.disabled = false;
            }
        });
}

// admin logoout 

function adminLogout() {
    fetch(BASE_URL + '/api/auth/logout', { method: 'POST' })
        .then(() => window.location.reload());
}
