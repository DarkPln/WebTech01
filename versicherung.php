<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Versicherung - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>
    <?php
        $pageTitle = "Kfz-Versicherung";
        $heroBadge = "Rundum geschützt";
        $heroText  = "Finden Sie die passende Kfz-Versicherung für Ihr Fahrzeug – vom gesetzlich vorgeschriebenen Haftpflichtschutz bis zur umfassenden Vollkasko.";
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

        <section class="home-hero">
            <div class="home-hero-content">
                <div class="home-badge"><?php echo $heroBadge; ?></div>
                <h1><?php echo $pageTitle; ?></h1>
                <p><?php echo $heroText; ?></p>
            </div>
        </section>

        <section class="home-section">
            <h2>Unsere Versicherungsarten</h2>
            <div class="insurance-grid">

                <div class="insurance-card">
                    <div class="insurance-badge">Pflicht</div>
                    <h3>Haftpflicht</h3>
                    <p>Die Kfz-Haftpflichtversicherung ist in Deutschland gesetzlich vorgeschrieben. Sie deckt Schäden ab, die Sie mit Ihrem Fahrzeug bei Dritten verursachen – sowohl Personen- als auch Sachschäden.</p>
                    <ul>
                        <li>Personenschäden bis 7,5 Mio. €</li>
                        <li>Sachschäden bis 1,22 Mio. €</li>
                        <li>Vermögensschäden bis 50.000 €</li>
                    </ul>
                </div>

                <div class="insurance-card">
                    <div class="insurance-badge">Empfohlen</div>
                    <h3>Teilkasko</h3>
                    <p>Die Teilkaskoversicherung schützt Ihr Fahrzeug vor Schäden durch äußere Einflüsse, für die Sie nicht verantwortlich sind.</p>
                    <ul>
                        <li>Diebstahl und Einbruch</li>
                        <li>Brand und Explosion</li>
                        <li>Sturm, Hagel, Blitzschlag</li>
                        <li>Glasbruch und Tierbiss</li>
                        <li>Überschwemmung</li>
                    </ul>
                </div>

                <div class="insurance-card">
                    <div class="insurance-badge">Vollschutz</div>
                    <h3>Vollkasko</h3>
                    <p>Die Vollkaskoversicherung bietet den umfassendsten Schutz und umfasst zusätzlich zur Teilkasko auch selbst verursachte Unfallschäden am eigenen Fahrzeug.</p>
                    <ul>
                        <li>Alle Leistungen der Teilkasko</li>
                        <li>Selbstverschuldete Unfälle</li>
                        <li>Vandalismusschäden</li>
                        <li>Parkschäden</li>
                        <li>Fahrerflucht durch Dritte</li>
                    </ul>
                </div>

            </div>
        </section>

        <section class="home-about">
            <h2>Welche Versicherung brauche ich?</h2>
            <p>Bei älteren Fahrzeugen (über 5–7 Jahre) reicht häufig eine Haftpflicht- oder Teilkaskoversicherung. Bei Neuwagen und Fahrzeugen mit hohem Restwert empfehlen wir eine Vollkaskoversicherung. Unser Tipp: Vergleichen Sie die Kosten der Versicherungsprämie mit dem Wiederbeschaffungswert Ihres Fahrzeugs.</p>
        </section>

        <section class="home-section">
            <h2>Unsere Versicherungspartner</h2>
            <div class="home-category-grid">
                <div class="home-category-card">
                    <h3>HUK-Coburg</h3>
                    <p>Deutschlands größter Kfz-Versicherer mit über 13 Millionen Kunden und ausgezeichneten Leistungen.</p>
                    <a href="partner.php">Mehr erfahren</a>
                </div>
                <div class="home-category-card">
                    <h3>ADAC Versicherung</h3>
                    <p>Exklusiver Kfz-Schutz für ADAC-Mitglieder mit besonderem Pannenschutz und Pannenhilfe.</p>
                    <a href="partner.php">Mehr erfahren</a>
                </div>
                <div class="home-category-card">
                    <h3>Allianz</h3>
                    <p>Umfassender Versicherungsschutz mit individuell anpassbaren Tarifen und schneller Schadensabwicklung.</p>
                    <a href="partner.php">Mehr erfahren</a>
                </div>
                <div class="home-category-card">
                    <h3>Zurich</h3>
                    <p>Weltweiter Assistance-Service und zuverlässiger Schutz mit attraktiven Rabatten für unfallfreies Fahren.</p>
                    <a href="partner.php">Mehr erfahren</a>
                </div>
            </div>
        </section>

        <section class="home-about">
            <h2>Jetzt Angebot anfordern</h2>
            <p>Kontaktieren Sie uns oder einen unserer Versicherungspartner direkt – für ein individuelles Angebot zugeschnitten auf Ihr Fahrzeug.</p>
            <div class="home-buttons">
                <a href="partner.php" class="home-btn-primary">Zu den Partnern</a>
                <a href="faq.php" class="home-btn-secondary">Häufige Fragen</a>
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

    <script src="validation.js"></script>
</body>
</html>
<!-- Tim -->
