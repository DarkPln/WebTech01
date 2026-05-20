<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Über uns - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>

<body class="about-page">
    <?php
        $pageTitle      = "Über uns - Auto24";
        $heroBadge      = "Über uns";
        $heroText       = "Die führende Online-Plattform für den Kauf und Verkauf von Fahrzeugen. Unkompliziert, sicher und schnell – Ihre vertrauenswürdige Fahrzeugbörse seit 2024.";
        $contactCompany = "Auto24 GmbH";
        $contactAddress = "Altschauerberg 8, 85049 Ingolstadt, Deutschland";
        $contactEmail   = "info@auto24.de";
        $contactPhone   = "+49 (0) 841 / 123 456";
        $contactHours   = "Montag - Freitag: 8:00 - 18:00 Uhr";
        $teamIntro      = "Auto24 wird von einem engagierten Team aus erfahrenen Fachleuten geleitet, die sich für Innovation und Kundenzufriedenheit einsetzen.";
        $ctaText        = "Entdecken Sie unsere große Auswahl an Fahrzeugen oder verkaufen Sie Ihr eigenes Auto.";
    ?>

    <nav>
        <a href="index.php" class="nav-logo">Auto<span>24</span></a>
        <ul class = "nav-links">
            <li><a href="index.php">Fahrzeuge</a></li>
            <li><a href="index.php">Neuwagen</a></li>
            <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
            <li><a href="about.php">Auto verkaufen</a></li>
        </ul>
        <button class ="toggle-mode" onclick="toggleMode()">Modus wechseln</button>
    </nav>


    <main class="home-main">

        <!-- Hero Section -->
        <section class="home-hero">
            <div class="home-hero-content">
                <div class="home-badge"><?php echo $heroBadge; ?></div>
                <h1>Auto<span>24</span></h1>
                <p><?php echo $heroText; ?></p>
            </div>
        </section>

        <!-- Services Section -->
        <section class="about-services-section">
            <h2>Unsere Dienstleistungen</h2>

            <div class="about-service-grid">
                <div class="about-service-card">
                    <div class="about-service-icon">🚗</div>
                    <h3>Kauf von Neuwagen</h3>
                    <p>Entdecken Sie die neuesten Modelle führender Automobilhersteller zu fairen Preisen.</p>
                </div>

                <div class="about-service-card">
                    <div class="about-service-icon">🔧</div>
                    <h3>Kauf von Gebrauchtwagen</h3>
                    <p>Hochwertige geprüfte Gebrauchtwagen mit transparenter Fahrzeughistorie.</p>
                </div>

                <div class="about-service-card">
                    <div class="about-service-icon">💰</div>
                    <h3>Verkauf von Fahrzeugen</h3>
                    <p>Verkaufen Sie Ihr Fahrzeug schnell und unkompliziert über unsere Plattform.</p>
                </div>

                <div class="about-service-card">
                    <div class="about-service-icon">🤝</div>
                    <h3>Fahrzeugvermittlung</h3>
                    <p>Professionelle Vermittlung zwischen Käufern und Verkäufern mit Bestpreisgarantie.</p>
                </div>
            </div>
        </section>

        <!-- Contact Information Section -->
        <section class="home-about">
            <h2>Kontaktinformationen</h2>

            <div class="about-contact-wrapper">
                <div class="about-contact-card">
                    <p><strong>Unternehmen:</strong> <?php echo $contactCompany; ?></p>
                    <p><strong>Adresse:</strong> <?php echo $contactAddress; ?></p>
                    <p><strong>E-Mail:</strong> <?php echo $contactEmail; ?></p>
                    <p><strong>Telefon:</strong> <?php echo $contactPhone; ?></p>
                    <p><strong>Öffnungszeiten:</strong> <?php echo $contactHours; ?></p>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="home-section">
            <h2>Unser Team</h2>
            <p class="about-team-intro"><?php echo $teamIntro; ?></p>

            <div class="about-team-grid">
                <div class="home-category-card">
                    <div class="home-category-icon">👨‍💼</div>
                    <h3>Lukas Neumayer</h3>
                    <p class="about-team-role">Geschäftsführer</p>
                </div>

                <div class="home-category-card">
                    <div class="home-category-icon">👨‍💼</div>
                    <h3>Niclas Reuter</h3>
                    <p class="about-team-role">Geschäftsführer</p>
                </div>

                <div class="home-category-card">
                    <div class="home-category-icon">👨‍💼</div>
                    <h3>Tim Höhn</h3>
                    <p class="about-team-role">Geschäftsführer</p>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="home-about">
            <h2>Bereit loszulegen?</h2>
            <p><?php echo $ctaText; ?></p>
            <div class="home-buttons">
                <a href="gebrauchtwagenList.php" class="home-btn-primary">Fahrzeuge ansehen</a>
                <a href="index.php" class="home-btn-secondary">Zur Startseite</a>
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
    <script src="validation.js"></script>
</body>
</html>
<!-- Tim -->
