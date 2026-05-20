<!-- Niclas -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
    <!-- validation.js enthält initLoginForm(), die per DOMContentLoaded automatisch startet.
         Sie prüft window.location.search auf '?registered=1' (gesetzt nach Registrierung)
         und zeigt dann die successMessage an. -->
    <script src="validation.js"></script>
</head>
<body>
    <?php
        $pageTitle   = "Login";
        $pageSubtext = "Bitte loggen Sie sich mit Ihrem Account ein.";
        $demoHint    = "Demo: Benutzername TestUser, Passwort TestPass123";
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
                <h1><?php echo $pageTitle; ?></h1>
                <p><?php echo $pageSubtext; ?></p>
                <p class="demo-hint">Demo: Benutzername <strong>TestUser</strong>, Passwort <strong>TestPass123</strong></p>

                <!-- successMessage: JS schreibt hier die Erfolgsmeldung nach Registrierung hinein
                     und setzt display='block'. Standardmäßig display:none (unsichtbar). -->
                <div id="successMessage" class="success-message" style="display:none;"></div>

                <!-- loginForm: Trigger für initLoginForm(). JS hängt einen submit-Listener an,
                     der e.preventDefault() aufruft und stattdessen localStorage prüft/setzt. -->
                <form id="loginForm">
                    <label for="username">Benutzername:</label>
                    <!-- username/password: JS lauscht auf 'input'-Events für Live-Aktivierung
                         des loginBtn. Die IDs müssen exakt mit getElementById() übereinstimmen. -->
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
<!-- Niclas -->
