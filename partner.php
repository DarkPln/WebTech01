<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Partner - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>
    <?php
        $pageTitle  = "Unsere Partner";
        $heroBadge  = "Starke Netzwerke";
        $heroText   = "Auto24 arbeitet mit führenden Finanz-, Versicherungs- und Automobilpartnern zusammen, um Ihnen den bestmöglichen Service zu bieten.";
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
            <h2>Finanzierungspartner</h2>
            <div class="partner-grid">
                <div class="partner-card">
                    <div class="partner-card-logo">Auto24 Bank</div>
                    <p>Günstige Fahrzeugfinanzierungen ab 2,9 % effektivem Jahreszins direkt über unsere Plattform.</p>
                </div>
                <div class="partner-card">
                    <div class="partner-card-logo">DKB</div>
                    <p>Die Deutsche Kreditbank bietet flexible Autokredite mit sofortiger Online-Entscheidung.</p>
                </div>
                <div class="partner-card">
                    <div class="partner-card-logo">Santander</div>
                    <p>Santander Consumer Finance – spezialisiert auf Fahrzeugfinanzierungen mit attraktiven Konditionen.</p>
                </div>
                <div class="partner-card">
                    <div class="partner-card-logo">Targobank</div>
                    <p>Ratenkredite und Autofinanzierungen mit individuell angepassten Laufzeiten von 12 bis 84 Monaten.</p>
                </div>
            </div>
        </section>

        <section class="home-about">
            <h2>Versicherungspartner</h2>
        </section>

        <section class="home-section">
            <div class="partner-grid">
                <div class="partner-card">
                    <div class="partner-card-logo">HUK-Coburg</div>
                    <p>Kfz-Versicherungen zu fairen Preisen – Deutschlands größter Kfz-Versicherer für Privatpersonen.</p>
                </div>
                <div class="partner-card">
                    <div class="partner-card-logo">ADAC</div>
                    <p>ADAC Autoversicherung mit umfassendem Schutz und exklusiven Vorteilen für ADAC-Mitglieder.</p>
                </div>
                <div class="partner-card">
                    <div class="partner-card-logo">Allianz</div>
                    <p>Allianz Kfz-Versicherungen mit individuellem Schutz und schneller Schadensabwicklung.</p>
                </div>
                <div class="partner-card">
                    <div class="partner-card-logo">Zurich</div>
                    <p>Zurich Versicherung – zuverlässiger Schutz für Ihr Fahrzeug mit weltweiter Assistance.</p>
                </div>
            </div>
        </section>

        <section class="home-about">
            <h2>Automobilpartner</h2>
        </section>

        <section class="home-section">
            <div class="partner-grid">
                <div class="partner-card">
                    <div class="partner-card-logo">TÜV Süd</div>
                    <p>Fahrzeugprüfungen, Hauptuntersuchungen und Gutachten durch zertifizierte TÜV-Experten.</p>
                </div>
                <div class="partner-card">
                    <div class="partner-card-logo">DEKRA</div>
                    <p>DEKRA-Gebrauchtwagencheck für maximale Transparenz beim Fahrzeugkauf.</p>
                </div>
                <div class="partner-card">
                    <div class="partner-card-logo">Sixt Leasing</div>
                    <p>Flexible Fahrzeugleasingoptionen für Privat- und Geschäftskunden ab günstigen Monatsraten.</p>
                </div>
                <div class="partner-card">
                    <div class="partner-card-logo">Autoscout24</div>
                    <p>Datenaustausch und Reichweitensteigerung durch die Partnerschaft mit Europas größter Fahrzeugplattform.</p>
                </div>
            </div>
        </section>

        <section class="home-about">
            <h2>Interesse an einer Partnerschaft?</h2>
            <p>Werden Sie Teil des Auto24-Netzwerks und profitieren Sie von unserer Reichweite und unserem Kundenstamm.</p>
            <div class="home-buttons">
                <a href="about.php" class="home-btn-primary">Kontakt aufnehmen</a>
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
