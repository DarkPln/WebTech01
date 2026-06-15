<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Seite nicht gefunden – Auto24</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>
    <?php require VIEW_PATH . 'partials/nav.php'; ?>

    <main class="error-page">
        <div class="error-container">
            <div class="error-code">404</div>
            <h1 class="error-title">Seite nicht gefunden</h1>
            <p class="error-message">
                Sehr geehrter Kunde, leider ist die von Ihnen gewünschte Seite derzeit nicht verfügbar.
                Dies kann an unterschiedlichen Gründen liegen.
            </p>
            <div class="error-actions">
                <a href="<?= BASE_URL ?>/cars" class="error-btn-primary">Fahrzeugangebot ansehen</a>
                <a href="<?= BASE_URL ?>/" class="error-btn-secondary">Zur Hauptseite</a>
            </div>
        </div>
    </main>

    <?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
