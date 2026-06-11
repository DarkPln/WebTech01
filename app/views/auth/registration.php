<!-- Niclas -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung - Auto24</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>
    <?php require VIEW_PATH . 'partials/nav.php'; ?>

    <main class="home-main">
        <section class="auth-section">
            <div class="auth-card">
                <label>Passwort generieren:</label>
                <p>Geben Sie ein Stichwort ein, um ein sicheres Passwort zu generieren:</p>
                <input type="string" id="pwInput">
                <button onclick="generatePassword()">Generieren</button>
                <p id="generatedPassword"></p>

                <h1>Registrierung</h1>
                <p>Erstellen Sie ein neues Auto24-Konto.</p>

                <div id="reg-error" class="error-message" style="display:none;"></div>

                <form id="registrationForm">
                    <label for="benutzername">Benutzername:</label>
                    <input type="text" id="benutzername" name="benutzername" required autocomplete="username">
                    <span class="field-error" id="benutzername-error"></span>

                    <label for="passwort">Passwort:</label>
                    <input type="password" id="passwort" name="passwort" required autocomplete="new-password">
                    <span class="field-error" id="passwort-error"></span>

                    <label for="passwort_wiederholen">Passwort wiederholen:</label>
                    <input type="password" id="passwort_wiederholen" name="passwort_wiederholen" required autocomplete="new-password">
                    <span class="field-error" id="passwort_wiederholen-error"></span>

                    <input type="submit" value="Registrieren" id="submitBtn">
                    <input type="reset" value="Zurücksetzen">
                </form>

                <p><a href="<?= BASE_URL ?>/auth/login">Bereits ein Konto? Jetzt einloggen</a></p>
            </div>
        </section>
    </main>

    <?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
<!-- Niclas -->
