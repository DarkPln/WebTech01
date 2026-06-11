<!-- Niclas -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login - Auto24</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>
    <?php require VIEW_PATH . 'partials/nav.php'; ?>

    <main class="home-main">
        <section class="auth-section">
            <div class="auth-card">
                <h1>Login</h1>
                <p>Bitte loggen Sie sich mit Ihrem Account ein.</p>
                <p class="demo-hint">Demo: Benutzername <strong>TestUser</strong>, Passwort <strong>TestPass123</strong></p>

                <div id="successMessage" class="success-message" style="display:none;"></div>

                <form id="loginForm">
                    <label for="username">Benutzername:</label>
                    <input type="text" id="username" name="username" required autocomplete="username">
                    <span class="field-error" id="username-error"></span>

                    <label for="password">Passwort:</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                    <span class="field-error" id="password-error"></span>

                    <div id="errorMessage" class="error-message" style="display:none;"></div>
                    <input type="submit" value="Einloggen" id="loginBtn">
                </form>

                <p><a href="<?= BASE_URL ?>/auth/register">Noch kein Konto? Jetzt registrieren</a></p>
                <p><a href="<?= BASE_URL ?>/">Zurück zur Startseite</a></p>
            </div>
        </section>
    </main>

    <?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
<!-- Niclas -->
