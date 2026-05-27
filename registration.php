<!-- Niclas -->
<!DOCTYPE html>
<html lang = "de">
    <head>
        <meta charset = "UTF-8">
        <title>Registrierung</title>
        <link rel="stylesheet" href="mystyle.css">
        <script src="validation.js"></script>
    </head>
    <body>
        <?php
            $pageTitle   = "Registrierung";
            $pageSubtext = "Erstellen Sie ein neues Auto24-Konto.";
            $pwGenLabel  = "Passwort generieren:";
            $pwGenHint   = "Geben Sie ein Stichwort ein, um ein sicheres Passwort zu generieren:";
        ?>
        <nav>
            <a href="index.php" class="nav-logo">Auto<span>24</span></a>
            <ul class="nav-links">
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
                    <label><?php echo $pwGenLabel; ?></label>
                        <p><?php echo $pwGenHint; ?></p>
                        <input type="string" id="pwInput">
                        <button onclick="generatePassword()">Generieren</button>
                        <p id="generatedPassword"></p>

                    <h1><?php echo $pageTitle; ?></h1>
                    <p><?php echo $pageSubtext; ?></p>

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

                    <p><a href="login.php">Bereits ein Konto? Jetzt einloggen</a></p>
                </div>

            </section>
        </main>
    </body>

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
</html>
<!-- Niclas -->
