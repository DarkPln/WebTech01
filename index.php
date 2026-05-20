<!-- Niclas -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Willkommen bei unserer Web-Anwendung "Auto24"-Startseite</title>
    <link rel="stylesheet" href="mystyle.css">
    <script>
        // Nav-Auth-Link vor dem Rendern setzen, um Flackern zu vermeiden
        document.addEventListener('DOMContentLoaded', function() {
            const link = document.getElementById('navAuthLink');
            if (!link) return;
            if (localStorage.getItem('loggedIn') === 'true') {
                const user = localStorage.getItem('loggedInUser') || 'Konto';
                link.textContent = user;
                link.href = 'user.php';
            }
        });
    </script>
</head>
<body>
    <?php
        $heroBadge   = "Ihre Fahrzeugbörse";
        $heroHeadline = "Willkommen bei";
        $heroText    = "Entdecken Sie die besten Angebote für Neu- und Gebrauchtwagen. Kaufen, verkaufen und vergleichen Sie Fahrzeuge einfach, sicher und schnell.";
        $cat1Title   = "Auto kaufen";
        $cat1Text    = "Finden Sie passende Fahrzeuge aus verschiedenen Kategorien.";
        $cat2Title   = "Auto verkaufen";
        $cat2Text    = "Inserieren Sie Ihr Fahrzeug schnell und unkompliziert.";
        $cat3Title   = "Login-Bereich";
        $cat3Text    = "Melden Sie sich an, um Inserate zu verwalten.";
        $aboutHeadline = "Über uns";
        $aboutText   = "Auto24 ist eine moderne Online-Plattform für den Kauf und Verkauf von Fahrzeugen. Unser Ziel ist es, eine benutzerfreundliche und sichere Umgebung zu schaffen, in der Käufer und Verkäufer schnell zusammenfinden.";
    ?>
    <nav>
        <a href="index.php" class="nav-logo">Auto<span>24</span></a>
        <ul class = "nav-links">
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
    <main class="home-main">

    <section class="home-hero">
        <div class="home-hero-content">
            <div class="home-badge"><?php echo $heroBadge; ?></div>

            <h1><?php echo $heroHeadline; ?> <span>Auto24</span></h1>

            <p><?php echo $heroText; ?></p>

            <div class="home-buttons">
                <a href="gebrauchtwagenList.php" class="home-btn-primary">Autos ansehen</a>
                <a href="about.php" class="home-btn-secondary">Auto verkaufen</a>
            </div>
        </div>
    </section>

    <section class="home-section">
        <h2>Unsere Kategorien</h2>

        <div class="home-category-grid">
            <div class="home-category-card">
                <h3><?php echo $cat1Title; ?></h3>
                <p><?php echo $cat1Text; ?></p>
                <a href="gebrauchtwagenList.php">Autos kaufen</a>
            </div>

            <div class="home-category-card">
                <h3><?php echo $cat2Title; ?></h3>
                <p><?php echo $cat2Text; ?></p>
                <a href="about.php">Autos verkaufen</a>
            </div>

            <div class="home-category-card">
                <h3><?php echo $cat3Title; ?></h3>
                <p><?php echo $cat3Text; ?></p>
                <a href="login.php">Zum Login</a>
            </div>
        </div>
    </section>

    <section class="home-about">
        <h2><?php echo $aboutHeadline; ?></h2>
        <p><?php echo $aboutText; ?></p>
        <a href="about.php" class="home-btn-secondary">Mehr erfahren</a>
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
                <ul class = "footer-links">
                    <li><a href="#">Neuwagen</a></li>
                    <li><a href="#">Gebrauchtwagen</a></li>
                    <li><a href="#">Elektrofahrzeuge</a></li>
                    <li><a href="#">Sonderangebote</a></li>
                </ul>
            </div>
            <div>
                <div class = "footer-heading">Kundenservice</div>
                <ul class = "footer-links">
                    <li><a href="#">Fahrzeug verkaufen</a></li>
                    <li><a href="#">Hilfe & FAQ</a></li>
                    <li><a href="#">Finanzierung</a></li>
                    <li><a href="#">Versicherung</a></li>
                </ul>
                </div>
            <div>
                <div class = "footer-heading">Unternehmen</div>
                <ul class = "footer-links">
                    <li><a href="about.php">Über uns</a></li>
                    <li><a href = "#"> Datenschutz </a></li>
                    <li><a href = "#"> AGB </a></li>
                    <li> <a href = "#"> Partner </a></li>
                </ul>
            </div>
            </div>
         </div>

        </div>
    </footer>
    <script src="validation.js"></script>
</body>
</html>
<!-- Niclas -->
