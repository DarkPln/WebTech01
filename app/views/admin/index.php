<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin – Auto24</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>

<nav class="admin-nav">
    <div style="display:flex; align-items:center;">
        <a href="<?= BASE_URL ?>/" class="nav-logo" style="font-size:24px;">Auto<span>24</span></a>
        <span class="admin-badge">Admin</span>
    </div>
    <div style="display:flex; align-items:center; gap:16px;">
        <a href="<?= BASE_URL ?>/" style="color:#888; font-size:13px; text-decoration:none;">← Zur Seite</a>
        <button class="admin-logout-btn" onclick="adminLogout()">Abmelden</button>
    </div>
</nav>

<main class="admin-main">
    <div id="adminDashboard" style="display:none;">
        <div class="admin-container">
            <h1 class="admin-page-title">Admin <span>Dashboard</span></h1>
            <p class="admin-page-sub">Auftragsübersicht und Nutzerverwaltung</p>

            <div class="admin-tabs">
                <button class="admin-tab active" data-target="tabNeu">Neue Aufträge</button>
                <button class="admin-tab" data-target="tabBearbeitung">In Bearbeitung</button>
                <button class="admin-tab" data-target="tabAbgelehnt">Abgelehnt / Storniert</button>
                <button class="admin-tab" data-target="tabAbgeschlossen">Abgeschlossen</button>
                <button class="admin-tab" data-target="tabNutzer">Nutzer verwalten</button>
                <button class="admin-tab" data-target="tabInserate">Inserate</button>
                <button class="admin-tab" data-target="tabFahrzeuge">Fahrzeuge</button>
            </div>

            <div id="tabNeu" class="admin-tab-content active">
                <h3 style="text-align:left; color:#bdbdbd; font-size:14px; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Neue Aufträge (Status: Bestellt)</h3>
                <div id="adminOrdersNew"></div>
            </div>
            <div id="tabBearbeitung" class="admin-tab-content">
                <h3 style="text-align:left; color:#bdbdbd; font-size:14px; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Aufträge in Bearbeitung & Versandt</h3>
                <div id="adminOrdersProcessing"></div>
            </div>
            <div id="tabAbgelehnt" class="admin-tab-content">
                <h3 style="text-align:left; color:#bdbdbd; font-size:14px; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Abgelehnte & stornierte Aufträge</h3>
                <div id="adminOrdersRejected"></div>
            </div>
            <div id="tabAbgeschlossen" class="admin-tab-content">
                <h3 style="text-align:left; color:#bdbdbd; font-size:14px; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Abgeschlossene Aufträge</h3>
                <div id="adminOrdersCompleted"></div>
            </div>
            <div id="tabNutzer" class="admin-tab-content">
                <h3 style="text-align:left; color:#bdbdbd; font-size:14px; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Registrierte Nutzer</h3>
                <p style="text-align:left; font-size:13px; color:#666; margin-bottom:20px;">
                    Gesperrte Nutzer können keine Buchungen tätigen.
                </p>
                <div id="adminUsersList"></div>
            </div>
            <div id="tabInserate" class="admin-tab-content">
                <h3 style="text-align:left; color:#bdbdbd; font-size:14px; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Eingereichte Inserate</h3>
                <div id="adminInserate"></div>
            </div>
            <div id="tabFahrzeuge" class="admin-tab-content">
                <h3 style="text-align:left; color:#bdbdbd; font-size:14px; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Fahrzeuge verwalten</h3>
                <p style="text-align:left; font-size:13px; color:#666; margin-bottom:20px;">Gelöschte Fahrzeuge werden dauerhaft aus der Fahrzeugliste entfernt.</p>
                <div id="adminCars"></div>
            </div>
        </div>
    </div>
</main>

<?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
<!-- Tim -->
