<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Abgemeldet - Auto24</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>
    <?php require VIEW_PATH . 'partials/nav.php'; ?>

    <div id="logoutPage"></div>

    <main class="home-main">
        <section class="auth-section">
            <div class="auth-card">
                <h1>Abgemeldet</h1>
                <p>Sie wurden erfolgreich abgemeldet.</p>
                <p><a href="<?= BASE_URL ?>/auth/login">Zum Login</a></p>
                <p><a href="<?= BASE_URL ?>/">Zur Hauptseite</a></p>
            </div>
        </section>
    </main>

    <?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
<!-- Tim -->
