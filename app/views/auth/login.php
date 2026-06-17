<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login - Auto24</title>
    <link rel="stylesheet" href="<?php echo  BASE_URL ?>/mystyle.css">
</head>
<body>
    <!-- Gemeinsame Navigation (nav.php), zeigt Login/Logout-Status per JS -->
    <?php require VIEW_PATH . 'partials/nav.php'; ?>

    <main class="home-main">
        <section class="auth-section">
            <div class="auth-card">
                <h1>Login</h1>
                <p>Bitte loggen Sie sich mit Ihrem Account ein.</p>
                <!-- Hardcoded Demo-Account für Testzwecke (TestUser / TestPass123) -->
                <p class="demo-hint">Demo: Benutzername <strong>TestUser</strong>, Passwort <strong>TestPass123</strong></p>

                <!-- Wird von initLoginForm() in auth.js bei erfolgreichem Login befüllt -->
                <div id="successMessage" class="success-message"></div>

                <!-- initLoginForm() in auth.js hängt sich per id="loginForm" an dieses Formular -->
                <form id="loginForm">
                    <label for="username">Benutzername:</label>
                    <input type="text" id="username" name="username" required autocomplete="username">
                    <!-- Feldfehler werden per JS in diese Spans geschrieben -->
                    <span class="field-error" id="username-error"></span>

                    <label for="password">Passwort:</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                    <span class="field-error" id="password-error"></span>

                    <!-- Allgemeine Fehlermeldung vom Server (z.B. falsches Passwort) -->
                    <div id="errorMessage" class="error-message"></div>
                    <input type="submit" value="Einloggen" id="loginBtn">
                </form>

                <!-- BASE_URL stellt sicher dass Links in Unterverzeichnissen funktionieren -->
                <p><a href="<?php echo  BASE_URL ?>/auth/register">Noch kein Konto? Jetzt registrieren</a></p>
                <p><a href="<?php echo  BASE_URL ?>/">Zurück zur Startseite</a></p>
            </div>
        </section>
    </main>

    <!-- footer.php lädt alle JS-Dateien global, validation.js ruft danach initLoginForm() auf -->
    <?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
<!-- Tim -->
