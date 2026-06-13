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
    <div class="admin-nav-left">
        <a href="<?= BASE_URL ?>/" class="nav-logo nav-logo--sm">Auto<span>24</span></a>
        <span class="admin-badge">Admin</span>
    </div>
    <div class="admin-nav-right">
        <a href="<?= BASE_URL ?>/" class="admin-back-link">← Zur Seite</a>
        <button class="admin-logout-btn" onclick="adminLogout()">Abmelden</button>
    </div>
</nav>

<main class="admin-main">
    <div id="adminDashboard">
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
                <h3 class="admin-tab-title">Neue Aufträge (Status: Bestellt)</h3>
                <div id="adminOrdersNew"></div>
            </div>
            <div id="tabBearbeitung" class="admin-tab-content">
                <h3 class="admin-tab-title">Aufträge in Bearbeitung & Versandt</h3>
                <div id="adminOrdersProcessing"></div>
            </div>
            <div id="tabAbgelehnt" class="admin-tab-content">
                <h3 class="admin-tab-title">Abgelehnte & stornierte Aufträge</h3>
                <div id="adminOrdersRejected"></div>
            </div>
            <div id="tabAbgeschlossen" class="admin-tab-content">
                <h3 class="admin-tab-title">Abgeschlossene Aufträge</h3>
                <div id="adminOrdersCompleted"></div>
            </div>
            <div id="tabNutzer" class="admin-tab-content">
                <h3 class="admin-tab-title">Registrierte Nutzer</h3>
                <p class="admin-tab-hint">Gesperrte Nutzer können keine Buchungen tätigen.</p>
                <div id="adminUsersList"></div>
            </div>
            <div id="tabInserate" class="admin-tab-content">
                <h3 class="admin-tab-title">Eingereichte Inserate</h3>
                <div id="adminInserate"></div>
            </div>
            <div id="tabFahrzeuge" class="admin-tab-content">
                <h3 class="admin-tab-title">Fahrzeuge verwalten</h3>
                <p class="admin-tab-hint">Gelöschte Fahrzeuge werden dauerhaft aus der Fahrzeugliste entfernt.</p>
                <div id="adminCars"></div>
            </div>
        </div>
    </div>
</main>

<?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
<!-- Tim -->
