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

<nav>
    <a href="index.php" class="nav-logo">Auto<span>24</span></a>
    <ul class="nav-links">
        <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
        <li><a href="fahrzeug-verkaufen.php">Auto verkaufen</a></li>
    </ul>
    <div class="nav-right">
        <a href="login.php" class="nav-auth-link" id="navAuthLink">Login</a>
        <button class="mode-btn" onclick="toggleMode()">Light</button>
    </div>
</nav>

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

<footer>
    <div class="footer-top">
        <div class="footer-logo-dsc">
            <div class="footer-logo">Auto<span>24</span></div>
            <div class="footer-dsc">Deutschlands praktische Fahrzeugbörse für Neu- und Gebrauchtwagen. Unkompliziert, sicher und schnell.</div>
        </div>
        <div>
            <div class="footer-heading">Fahrzeuge</div>
            <ul class="footer-links">
                <li><a href="#">Neuwagen</a></li>
                <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-heading">Kundenservice</div>
            <ul class="footer-links">
                <li><a href="faq.php">Hilfe &amp; FAQ</a></li>
                <li><a href="finanzierung.php">Finanzierung</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-heading">Unternehmen</div>
            <ul class="footer-links">
                <li><a href="about.php">Über uns</a></li>
                <li><a href="datenschutz.php">Datenschutz</a></li>
            </ul>
        </div>
    </div>
</footer>

<script src="validation.js"></script>
</body>
</html>
