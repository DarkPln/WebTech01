<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Nutzerbereich</title>
    <link rel="stylesheet" href="mystyle.css">
    <!-- validation.js enthält initUserForm(), die beim DOMContentLoaded-Event automatisch
         ausgeführt wird. Sie prüft zuerst den localStorage auf den Login-Status und leitet
         bei fehlendem Login sofort zu login.php weiter (Auth-Guard). -->
    <script src="validation.js"></script>
</head>

<body>
    <?php
        $pageTitle    = "Nutzerbereich";
        $sectionTitle = "Persönliche Informationen";
        $saveLabel    = "Änderungen speichern";
        $logoutText   = "Abmelden";
    ?>
    <nav>
        <a href="index.php" class="nav-logo">Auto<span>24</span></a>
        <ul class="nav-links">
            <li><a href="index.php">Fahrzeuge</a></li>
            <li><a href="index.php">Neuwagen</a></li>
            <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
            <li><a href="fahrzeug-verkaufen.php">Auto verkaufen</a></li>
        </ul>
        <div class="nav-right">
            <a href="login.php" class="nav-auth-link" id="navAuthLink">Login</a>
            <button class="mode-btn" onclick="toggleMode()">Light</button>
        </div>
    </nav>

    <main class="home-main">
        <section class="auth-section">
            <div class="auth-card">
                <!-- display-username: JS schreibt hier per .textContent den Benutzernamen
                     aus dem localStorage hinein – sowohl beim Laden als auch nach dem Speichern. -->
                <h1>Willkommen, <span id="display-username" style="color: rgb(227,27,7);"></span></h1>
                <h2><?php echo $sectionTitle; ?></h2>

                <!-- saveSuccess: JS setzt .style.display='block' nach erfolgreichem Speichern
                     und blendet die Meldung nach 3 Sekunden via setTimeout() wieder aus. -->
                <div id="saveSuccess" class="success-message" style="display:none;"></div>

                <!-- userForm: Trigger für initUserForm(). Die IDs der Inputs (username, password,
                     password_confirm) werden in validation.js per getElementById() referenziert. -->
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

                    <!-- saveBtn: JS deaktiviert diesen Button (disabled=true) solange die Validierung
                         fehlschlägt – der Nutzer kann erst speichern, wenn alle Felder korrekt sind. -->
                    <input type="submit" value="<?php echo $saveLabel; ?>" id="saveBtn">
                </form>

                <p><a href="logout.php"><?php echo $logoutText; ?></a></p>
            </div>
        </section>
    </main>

     <footer>

        <div class ="footer-top">
            <div class = footer-logo-dsc>
            <div class = "footer-logo">
                Auto
                <span>24</span>
                </div>

            <div class = "footer-dsc"> Deutschlands praktische Fahrzeugbörse für Neu- und Gebrauchtwagen. Unkompliziert, sicher und schnell.</div>
            </div>


            <div>
                <div class="footer-heading">Fahrzeuge</div>
                <ul class="footer-links">
                    <li><a href="#">Neuwagen</a></li>
                    <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
                    <li><a href="#">Elektrofahrzeuge</a></li>
                    <li><a href="#">Sonderangebote</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-heading">Kundenservice</div>
                <ul class="footer-links">
                    <li><a href="fahrzeug-verkaufen.php">Fahrzeug verkaufen</a></li>
                    <li><a href="faq.php">Hilfe &amp; FAQ</a></li>
                    <li><a href="finanzierung.php">Finanzierung</a></li>
                    <li><a href="versicherung.php">Versicherung</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-heading">Unternehmen</div>
                <ul class="footer-links">
                    <li><a href="about.php">Über uns</a></li>
                    <li><a href="datenschutz.php">Datenschutz</a></li>
                    <li><a href="agb.php">AGB</a></li>
                    <li><a href="partner.php">Partner</a></li>
                </ul>
            </div>
        </div>
    </footer>

</body>
</html>
<!-- Tim -->
