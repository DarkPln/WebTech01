<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Nutzerbereich</title>
    <link rel="stylesheet" href="mystyle.css">
    <script src="validation.js"></script>
</head>

<body>
    <?php
        $pageTitle    = "Nutzerbereich";
        $sectionTitle = "Persönliche Informationen";
        $saveLabel    = "Änderungen speichern";
        $logoutText   = "Abmelden";
    ?>
    <?php require_once 'nav.php'; ?>

    <main class="home-main">
        <section class="auth-section">
            <div class="auth-card">

                <h1>Willkommen, <span id="display-username" style="color: rgb(227,27,7);"></span></h1>
                <h2><?php echo $sectionTitle; ?></h2>

                <div id="saveSuccess" class="success-message" style="display:none;"></div>

                <form id="userForm">
                    <label for="username">Benutzername:</label>
                    <input type="text" id="username" name="username" required autocomplete="username">
                    <span class="field-error" id="username-error"></span>

                    <label for="password">Neues Passwort:</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password">
                    <span class="field-error" id="password-error"></span>

                    <label for="password_confirm">Passwort bestätigen:</label>
                    <input type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password">
                    <span class="field-error" id="password_confirm-error"></span>

                    <input type="submit" value="<?php echo $saveLabel; ?>" id="saveBtn">
                </form>

                <p><a href="buchungen.php" style="color: rgb(227,27,27); font-weight:600;">Meine Buchungen ansehen</a></p>

                <hr style="margin:24px 0; border-color:#333;">
                <h2 style="margin-bottom:12px;">Meine Inserate</h2>
                <div id="userInserate"></div>

                <p style="margin-top:20px;"><a href="logout.php"><?php echo $logoutText; ?></a></p>
            </div>
        </section>
    </main>

    <?php require_once 'footer.php'; ?>

</body>
</html>
<!-- Tim -->
