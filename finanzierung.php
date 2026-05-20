<!-- Niclas / Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Finanzierung - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>
    <?php
        $pageTitle       = "Fahrzeugfinanzierung";
        $heroBadge       = "Auto24 Bank";
        $heroText        = "Berechnen Sie Ihre monatliche Rate und finden Sie die passende Finanzierung für Ihr Wunschfahrzeug.";
        $calcTitle       = "Finanzierungsrechner";
        $calcHint        = "Geben Sie den Fahrzeugpreis und die gewünschte Laufzeit ein. Wir berechnen Ihre monatliche Rate bei 5 % Jahreszins.";
        $taxTitle        = "Preisrechner";
        $taxHint         = "Berechnen Sie den Bruttopreis inklusive 19 % Mehrwertsteuer.";
        $loanTermHint    = "Laufzeit in Monaten (12 – 48):";
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
            <h2>Unsere Konditionen</h2>
            <div class="finance-info-grid">
                <div class="finance-info-card">
                    <h3>5 %</h3>
                    <p>Effektiver Jahreszins</p>
                </div>
                <div class="finance-info-card">
                    <h3>12–48</h3>
                    <p>Laufzeit in Monaten</p>
                </div>
                <div class="finance-info-card">
                    <h3>0 €</h3>
                    <p>Bearbeitungsgebühr</p>
                </div>
                <div class="finance-info-card">
                    <h3>24h</h3>
                    <p>Schnelle Kreditentscheidung</p>
                </div>
            </div>
        </section>

        <section class="home-about">
            <h2><?php echo $calcTitle; ?></h2>
            <p><?php echo $calcHint; ?></p>
        </section>

        <section class="home-section">
            <div class="finance-calc-card">
                <h3>Auto24 Bank – Finanzierungshilfe</h3>
                <label for="financingInput">Finanzierungsbetrag (€):</label>
                <input type="number" id="financingInput" placeholder="z. B. 25000" min="1000">
                <label for="loanTermInput"><?php echo $loanTermHint; ?></label>
                <input type="number" id="loanTermInput" placeholder="z. B. 36" min="12" max="48">
                <input type="submit" value="Rate berechnen" onclick="calculateFinancing()">
                <p id="financingResult"></p>
                <p id="financingResult2"></p>
            </div>
        </section>

        <section class="home-about">
            <h2><?php echo $taxTitle; ?></h2>
            <p><?php echo $taxHint; ?></p>
        </section>

        <section class="home-section">
            <div class="finance-calc-card">
                <h3>MwSt.-Rechner</h3>
                <label for="priceInput">Netto-Preis (€):</label>
                <input type="number" id="priceInput" placeholder="z. B. 20000" min="1">
                <input type="submit" value="Bruttopreis berechnen" onclick="calculatePrice()">
                <p id="priceWithoutTax"></p>
                <p id="priceWithTax"></p>
            </div>
        </section>

        <section class="home-section">
            <h2>So funktioniert die Finanzierung</h2>
            <div class="home-category-grid">
                <div class="home-category-card">
                    <h3>1. Berechnen</h3>
                    <p>Nutzen Sie unseren Rechner, um Ihre gewünschte monatliche Rate zu ermitteln.</p>
                </div>
                <div class="home-category-card">
                    <h3>2. Anfragen</h3>
                    <p>Stellen Sie eine unverbindliche Finanzierungsanfrage über unsere Partnerbanken.</p>
                </div>
                <div class="home-category-card">
                    <h3>3. Genehmigen</h3>
                    <p>Erhalten Sie innerhalb von 24 Stunden eine Kreditentscheidung.</p>
                </div>
                <div class="home-category-card">
                    <h3>4. Fahren</h3>
                    <p>Nach Vertragsunterzeichnung erhalten Sie schnellstmöglich Ihr Fahrzeug.</p>
                </div>
            </div>
        </section>

        <section class="home-about">
            <h2>Fahrzeug gefunden?</h2>
            <p>Entdecken Sie unsere aktuellen Gebraucht- und Neuwagen und berechnen Sie direkt Ihre persönliche Finanzierungsrate.</p>
            <div class="home-buttons">
                <a href="gebrauchtwagenList.php" class="home-btn-primary">Fahrzeuge ansehen</a>
                <a href="partner.php" class="home-btn-secondary">Unsere Partnerbanken</a>
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
<!-- Niclas / Tim -->
