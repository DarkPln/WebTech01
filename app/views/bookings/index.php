<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meine Buchungen – Auto24</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>

<!-- Gemeinsame Navigation (nav.php) -->
<?php require VIEW_PATH . 'partials/nav.php'; ?>

<main class="home-main">
    <section class="auth-section auth-section--left">
        <div class="buchungen-wrapper">
            <h1>Meine <span style="color: rgb(227,27,27);">Buchungen</span></h1>
            <!-- #buchungenUsername wird von initBuchungenPage() in bookings.js mit dem Nutzernamen befüllt -->
            <p class="buchungen-intro">
                Eingeloggt als: <strong id="buchungenUsername"></strong>
            </p>

            <!-- Leerer Container; initBuchungenPage() lädt per fetch() HTML-Fragment vom Server und setzt es per innerHTML ein -->
            <div id="buchungenContainer">
                <p class="buchungen-loading">Buchungen werden geladen…</p>
            </div>

            <a href="<?= BASE_URL ?>/cars" class="home-btn-secondary buchungen-cars-link">
                Weitere Fahrzeuge ansehen
            </a>
            <a href="<?= BASE_URL ?>/user" class="buchungen-back-link">
                ← Zurück zum Profil
            </a>
        </div>
    </section>
</main>

<!-- footer.php lädt alle JS-Dateien global; validation.js ruft danach initBuchungenPage() auf -->
<?php require VIEW_PATH . 'partials/footer.php'; ?>

</body>
</html>
<!-- Tim -->