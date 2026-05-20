<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Abgemeldet</title>
    <link rel="stylesheet" href="mystyle.css">
    <!-- validation.js wird im <head> ohne defer eingebunden. Die initLogout()-Funktion
         wird über den DOMContentLoaded-Listener in validation.js aufgerufen, sodass
         das DOM erst vollständig geladen ist, bevor auf Elemente zugegriffen wird. -->
    <script src="validation.js"></script>
</head>

<body>
    <?php
        $pageTitle   = "Abgemeldet";
        $pageSubtext = "Sie wurden erfolgreich abgemeldet.";
    ?>
    <nav>
        <a href="index.php" class="nav-logo">Auto<span>24</span></a>
        <ul class="nav-links">
            <li><a href="index.php">Fahrzeuge</a></li>
            <li><a href="index.php">Neuwagen</a></li>
            <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
            <li><a href="about.php">Auto verkaufen</a></li>
        </ul>
        <div class="nav-right">
            <a href="login.php" class="nav-auth-link" id="navAuthLink">Login</a>
            <button class="mode-btn" onclick="toggleMode()">Light</button>
        </div>
    </nav>

    <!-- Dieses leere div ist der "Trigger" für initLogout() in validation.js.
         document.getElementById('logoutPage') findet es und startet dadurch den
         Logout-Vorgang (localStorage leeren). Auf keiner anderen Seite existiert dieses Element. -->
    <div id="logoutPage"></div>

    <main class="home-main">
        <section class="auth-section">
            <div class="auth-card">
                <h1><?php echo $pageTitle; ?></h1>
                <p><?php echo $pageSubtext; ?></p>
                <p><a href="login.php">Zum Login</a></p>
                <p><a href="index.php">Zur Hauptseite</a></p>
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
                <div class = "footer-heading">Fahrzeuge</div>
                <ul class "footer-links">
                    <li><a href="#">Neuwagen</a></li>
                    <li><a href="#">Gebrauchtwagen</a></li>
                    <li><a href="#">Elektrofahrzeuge</a></li>
                    <li><a href="#">Sonderangebote</a></li>
                </ul>
            </div>
            <div>
                <div class = "footer-heading">Kundenservice</div>
                <ul class "footer-links">
                    <li><a href="#">Fahrzeug verkaufen</a></li>
                    <li><a href="#">Hilfe & FAQ</a></li>
                    <li><a href="#">Finanzierung</a></li>
                    <li><a href="#">Versicherung</a></li>
                </ul>
                </div>
            <div>
                <div class = "footer-heading">Unternehmen</div>
                <ul class "footer-links">
                    <li><a href="about.php">Über uns</a></li>
                    <li><a href = "#"> Datenschutz </a></li>
                    <li><a href = "#"> AGB </a></li>
                    <li> <a href = "#"> Partner </a></li>
                </ul>
            </div>
            </div>
         </div>


        <div class="footer-bot">
        <span> &copy 2026 Auto24. Alle Rechte vorbehalten.</span>
        <div class="footer-bot-links">
            <a href="index.php">Home</a>
            <a href="gebrauchtwagenList.php">Gebrauchtwagen</a>
            <a href="about.php">Impressum</a>
        </div>


        </div>
    </footer>

</body>
</html>
<!-- Tim -->
