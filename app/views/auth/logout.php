<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Abgemeldet - Auto24</title>
    <link rel="stylesheet" href="<?php echo  BASE_URL ?>/mystyle.css">
</head>
<body>
    <!-- Gemeinsame Navigation (nav.php) -->
    <?php require VIEW_PATH . 'partials/nav.php'; ?>

    <!-- initLogoutPage() in auth.js erkennt diese id und löst den Logout-POST aus -->
    <div id="logoutPage"></div>

    <main class="home-main">
        <section class="auth-section">
            <div class="auth-card">
                <h1>Abgemeldet</h1>
                <p>Sie wurden erfolgreich abgemeldet.</p>
                <p><a href="<?php echo  BASE_URL ?>/auth/login">Zum Login</a></p>
                <p><a href="<?php echo  BASE_URL ?>/">Zur Hauptseite</a></p>
            </div>
        </section>
    </main>

    <!-- footer.php lädt alle JS-Dateien global; validation.js ruft danach initLogoutPage() auf -->
    <?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
<!-- Tim -->