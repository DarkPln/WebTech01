<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meine Buchungen – Auto24</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>

<?php require VIEW_PATH . 'partials/nav.php'; ?>

<main class="home-main">
    <section class="auth-section auth-section--left">
        <div class="buchungen-wrapper">
            <h1>Meine <span style="color: rgb(227,27,27);">Buchungen</span></h1>
            <p class="buchungen-intro">
                Eingeloggt als: <strong id="buchungenUsername"></strong>
            </p>

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

<?php require VIEW_PATH . 'partials/footer.php'; ?>

</body>
</html>
