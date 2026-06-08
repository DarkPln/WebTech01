<!-- Niclas -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
    <script src="validation.js"></script>
</head>
<body>
    <?php
        $pageTitle   = "Login";
        $pageSubtext = "Bitte loggen Sie sich mit Ihrem Account ein.";
    ?>
    <?php require_once 'nav.php'; ?>

    <main class="home-main">
        <section class="auth-section">
            <div class="auth-card">
                <h1><?php echo $pageTitle; ?></h1>
                <p><?php echo $pageSubtext; ?></p>
                <p class="demo-hint">Demo: Benutzername <strong>TestUser</strong>, Passwort <strong>TestPass123</strong></p>

                <div id="successMessage" class="success-message" style="display:none;"></div>

                <form id="loginForm">
                    <label for="username">Benutzername:</label>

                    <input type="text" id="username" name="username" required autocomplete="username">
                    <span class="field-error" id="username-error"></span>

                    <label for="password">Passwort:</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                    <span class="field-error" id="password-error"></span>

                    <!-- errorMessage: JS zeigt hier Fehlermeldungen bei falschem Login an. -->
                    <div id="errorMessage" class="error-message" style="display:none;"></div>

                    <!-- loginBtn: JS setzt disabled=true beim Laden und aktiviert ihn erst,
                         wenn beide Felder nicht leer sind (checkFormValidity). -->
                    <input type="submit" value="Einloggen" id="loginBtn">
                </form>

                <p><a href="registration.php">Noch kein Konto? Jetzt registrieren</a></p>
                <p><a href="index.php">Zurück zur Startseite</a></p>
            </div>
        </section>
    </main>

    <?php require_once 'footer.php'; ?>

</body>
</html>
<!-- Niclas -->
