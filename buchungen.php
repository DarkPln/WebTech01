<?php
// Nutzer-Buchungsübersicht – Tim
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meine Buchungen – Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>

<?php require_once 'nav.php'; ?>

<main class="home-main">
    <section class="auth-section" style="align-items:flex-start; padding-top: 60px;">
        <div class="buchungen-wrapper" style="padding: 40px 20px; max-width: 720px; width:100%;">
            <h1>Meine <span style="color: rgb(227,27,27);">Buchungen</span></h1>
            <p style="text-align:left; color:#bdbdbd; margin-bottom:28px;">
                Eingeloggt als: <strong id="buchungenUsername" style="color:white;"></strong>
            </p>

            <div id="buchungenContainer">
                <p style="color:#888;">Buchungen werden geladen&hellip;</p>
            </div>

            <a href="gebrauchtwagenList.php" class="home-btn-secondary" style="display:inline-block; margin-top: 30px;">
                Weitere Fahrzeuge ansehen
            </a>
            <a href="user.php" style="display:inline-block; margin-top:10px; color:#888; font-size:13px;">
                &larr; Zurück zum Profil
            </a>
        </div>
    </section>
</main>

<?php require_once 'footer.php'; ?>

<script src="validation.js"></script>
</body>
</html>
