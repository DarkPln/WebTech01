<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Hilfe &amp; FAQ - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>
    <?php
        $pageTitle = "Hilfe &amp; FAQ";
        $heroBadge = "Häufige Fragen";
        $heroText  = "Hier finden Sie Antworten auf die häufigsten Fragen rund um den Kauf, Verkauf und die Nutzung von Auto24.";
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

        <div class="faq-section">

            <div class="faq-category">
                <h2>Fahrzeug kaufen</h2>

                <details class="faq-item">
                    <summary>Wie finde ich ein passendes Fahrzeug?</summary>
                    <p>Nutzen Sie unsere Suchfunktion auf der Gebrauchtwagenlistenseite. Sie können nach Marke, Modell, Baujahr, Kilometerstand und Preis filtern. Klicken Sie auf ein Fahrzeug, um die Detailansicht mit allen Informationen zu öffnen.</p>
                </details>

                <details class="faq-item">
                    <summary>Sind alle Fahrzeuge geprüft?</summary>
                    <p>Fahrzeuge mit dem DEKRA- oder TÜV-Siegel wurden von einem unabhängigen Gutachter geprüft. Achten Sie beim Kauf auf entsprechende Prüfzeichen im Inserat. Wir empfehlen außerdem, jedes Fahrzeug vor dem Kauf persönlich zu besichtigen.</p>
                </details>

                <details class="faq-item">
                    <summary>Wie kontaktiere ich einen Verkäufer?</summary>
                    <p>In der Detailansicht eines Inserats finden Sie die Kontaktdaten des Verkäufers oder ein Kontaktformular. Sie können den Verkäufer direkt anschreiben oder anrufen, um einen Besichtigungstermin zu vereinbaren.</p>
                </details>

                <details class="faq-item">
                    <summary>Was muss ich beim Fahrzeugkauf beachten?</summary>
                    <p>Prüfen Sie Fahrzeugpapiere (Zulassungsbescheinigung I und II), Hauptuntersuchung (HU), Scheckheft und den Kilometerstand. Lassen Sie das Fahrzeug vor dem Kauf von einem Fachmann oder beim TÜV/DEKRA prüfen. Überweisen Sie Kaufbeträge nie ohne vorherige Besichtigung.</p>
                </details>
            </div>

            <div class="faq-category">
                <h2>Fahrzeug verkaufen</h2>

                <details class="faq-item">
                    <summary>Wie kann ich mein Fahrzeug inserieren?</summary>
                    <p>Registrieren Sie sich kostenlos auf Auto24 und füllen Sie das Formular auf der <a href="fahrzeug-verkaufen.php">Fahrzeug verkaufen</a>-Seite aus. Geben Sie alle relevanten Daten und Fotos Ihres Fahrzeugs an. Nach Prüfung durch unser Team wird Ihr Inserat veröffentlicht.</p>
                </details>

                <details class="faq-item">
                    <summary>Was kostet das Inserieren?</summary>
                    <p>Das Inserieren ist für Privatpersonen kostenlos. Gewerbliche Anbieter können zwischen verschiedenen Paketen wählen. Details finden Sie in unserer aktuellen Preisliste.</p>
                </details>

                <details class="faq-item">
                    <summary>Wie lange ist mein Inserat aktiv?</summary>
                    <p>Privatinserate sind standardmäßig 60 Tage aktiv und können kostenlos verlängert werden. Wenn Ihr Fahrzeug verkauft ist, können Sie das Inserat jederzeit deaktivieren.</p>
                </details>

                <details class="faq-item">
                    <summary>Welche Fotos sollte ich hochladen?</summary>
                    <p>Hochladen sollten Sie mindestens: Frontansicht, Heckansicht, beide Seitenansichten, Innenraum (Fahrersitz, Rücksitz), Armaturenbrett, Motorraum und bei Mängeln entsprechende Detailfotos. Gute Fotos erhöhen die Verkaufschancen deutlich.</p>
                </details>
            </div>

            <div class="faq-category">
                <h2>Finanzierung &amp; Versicherung</h2>

                <details class="faq-item">
                    <summary>Wie funktioniert die Fahrzeugfinanzierung bei Auto24?</summary>
                    <p>Über unsere <a href="finanzierung.php">Finanzierungsseite</a> können Sie monatliche Raten berechnen und verschiedene Angebote unserer Partnerbanken vergleichen. Nach der Online-Anfrage erhalten Sie innerhalb von 24 Stunden eine Rückmeldung.</p>
                </details>

                <details class="faq-item">
                    <summary>Welche Kfz-Versicherung brauche ich?</summary>
                    <p>In Deutschland ist eine Kfz-Haftpflichtversicherung gesetzlich vorgeschrieben. Zusätzlich empfehlen wir je nach Fahrzeugwert eine Teilkasko- oder Vollkaskoversicherung. Informationen und Angebote finden Sie auf unserer <a href="versicherung.php">Versicherungsseite</a>.</p>
                </details>

                <details class="faq-item">
                    <summary>Kann ich einen Kredit ohne SCHUFA beantragen?</summary>
                    <p>Einige unserer Partnerbanken bieten SCHUFA-neutrale Anfragen an. Diese werden nicht als Kreditabfrage vermerkt und haben keinen Einfluss auf Ihren SCHUFA-Score. Sprechen Sie direkt mit den Beratern unserer Finanzierungspartner.</p>
                </details>
            </div>

            <div class="faq-category">
                <h2>Konto &amp; Registrierung</h2>

                <details class="faq-item">
                    <summary>Wie erstelle ich ein Konto?</summary>
                    <p>Klicken Sie oben rechts auf "Login" und dann auf "Noch kein Konto? Jetzt registrieren". Wählen Sie einen Benutzernamen (min. 5 Zeichen, Groß- und Kleinbuchstaben) und ein sicheres Passwort (min. 10 Zeichen).</p>
                </details>

                <details class="faq-item">
                    <summary>Ich habe mein Passwort vergessen – was kann ich tun?</summary>
                    <p>Da wir Passwörter lokal in Ihrem Browser speichern, kann ein vergessenes Passwort leider nicht zurückgesetzt werden. In diesem Fall müssen Sie ein neues Konto anlegen. Wir arbeiten an einer serverseitigen Lösung für zukünftige Versionen.</p>
                </details>

                <details class="faq-item">
                    <summary>Wie kann ich mein Konto löschen?</summary>
                    <p>Wenden Sie sich per E-Mail an datenschutz@auto24.de mit dem Betreff "Kontodellung". Wir bearbeiten Ihre Anfrage innerhalb von 3 Werktagen. Alternativ können Sie alle Daten durch das Löschen des Browser-Speichers entfernen.</p>
                </details>

                <details class="faq-item">
                    <summary>Sind meine Daten sicher?</summary>
                    <p>Ja. Ihre Zugangsdaten werden ausschließlich lokal in Ihrem Browser gespeichert und nicht an unsere Server übertragen. Details finden Sie in unserer <a href="datenschutz.php">Datenschutzerklärung</a>.</p>
                </details>
            </div>

        </div>

        <section class="home-about">
            <h2>Weitere Fragen?</h2>
            <p>Haben Sie eine Frage, die hier nicht beantwortet wird? Kontaktieren Sie uns direkt – wir helfen Ihnen gerne weiter.</p>
            <div class="home-buttons">
                <a href="about.php" class="home-btn-primary">Kontakt aufnehmen</a>
                <a href="index.php" class="home-btn-secondary">Zur Startseite</a>
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
