<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Abgemeldet</title>
    <link rel="stylesheet" href="mystyle.css">
    <script src="validation.js"></script>
</head>

<body>
    <?php
        $pageTitle   = "Abgemeldet";
        $pageSubtext = "Sie wurden erfolgreich abgemeldet.";
    ?>
    <?php require_once 'nav.php'; ?>

    <div id="logoutPage"></div>

    <main class="home-main">
        <section class="auth-section">
            <div class="auth-card">
                <h1><?php echo $pageTitle; ?></h1>
                <p><?php echo $pageSubtext; ?></p>
                <p><a href="login.php">Zum Login</a></p>
                <p><a href="index.php">Zur Hauptseite</a></p>
            </div>
        </section>
    </main>

    <?php require_once 'footer.php'; ?>

</body>
</html>
<!-- Tim -->
