// ===== ADMIN =====
// Autor: tim

// Admin-Seite initialisieren: Dashboard zeigen wenn eingeloggt, sonst zu Login weiterleiten
async function initAdminPage() {
    var loginBereich = document.getElementById('adminLoginSection');
    var dashboard    = document.getElementById('adminDashboard');
    if (!loginBereich && !dashboard) return;

    if (authState.isAdmin) {
        if (loginBereich) loginBereich.style.display = 'none';
        if (dashboard)    dashboard.style.display    = 'block';

        await renderAdminOrders();
        await renderAdminUsers();
        await renderAdminInserate();
        await renderAdminCars();

        document.querySelectorAll('.admin-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.admin-tab-content').forEach(c => c.classList.remove('active'));
                tab.classList.add('active');
                var zielBereich = document.getElementById(tab.dataset.target);
                if (zielBereich) zielBereich.classList.add('active');
            });
        });
    } else {
        window.location.href = 'login.php';
    }
}

// Alle vier Auftrags-Tabs parallel vom Server laden und befüllen
async function renderAdminOrders() {
    const bereiche     = ['new', 'processing', 'rejected', 'completed'];
    const containerIds = ['adminOrdersNew', 'adminOrdersProcessing', 'adminOrdersRejected', 'adminOrdersCompleted'];

    const antworten = await Promise.all(
        bereiche.map(b => fetch('api/admin/orders_html.php?bereich=' + b).then(r => r.text()))
    );

    bereiche.forEach((_, i) => {
        document.getElementById(containerIds[i]).innerHTML = antworten[i];
    });
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
async function renderAdminInserate() {
    var container = document.getElementById('adminInserate');
    if (!container) return;
    const antwort       = await fetch('api/admin/inserate_html.php');
    container.innerHTML = await antwort.text();
}

async function adminSetInseratStatus(id, status) {
    await fetch('api/admin/listings.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id, status })
    });
    await renderAdminInserate();
}

// PHP rendert das fertige HTML, JS fügt es nur noch in die Seite ein
async function renderAdminUsers() {
    var container = document.getElementById('adminUsersList');
    if (!container) return;
    const antwort       = await fetch('api/admin/users_html.php');
    container.innerHTML = await antwort.text();
}

async function adminSetStatus(bookingId, status, reason) {
    await fetch('api/admin/orders.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id: bookingId, status, reason: reason || '' })
    });
    await renderAdminOrders();
}

async function adminRejectOrder(bookingId) {
    var reason = prompt('Bitte geben Sie einen Ablehnungsgrund an (z.B. nicht verfügbare Items):');
    if (reason === null) return;
    await adminSetStatus(bookingId, 'abgelehnt', reason || 'Kein Grund angegeben');
}

async function adminToggleLock(username) {
    await fetch('api/admin/users.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ username })
    });
    await renderAdminUsers();
}

async function renderAdminCars() {
    var container = document.getElementById('adminCars');
    if (!container) return;
    const antwort       = await fetch('api/admin/cars_html.php');
    container.innerHTML = await antwort.text();
}

async function adminDeleteCar(iid, btn) {
    if (!confirm('Fahrzeug dauerhaft löschen?')) return;
    btn.disabled = true;
    const res  = await fetch('api/admin/cars.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ iid })
    });
    const data = await res.json();
    if (data.success) {
        await renderAdminCars();
    } else {
        alert(data.message || 'Löschen fehlgeschlagen');
        btn.disabled = false;
    }
}

async function adminLogout() {
    await fetch('api/auth/logout.php', { method: 'POST' });
    window.location.reload();
}
