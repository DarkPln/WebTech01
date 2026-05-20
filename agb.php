<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>AGB - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>
    <?php
        $pageTitle   = "Allgemeine Geschäftsbedingungen";
        $lastUpdated = "Stand: Mai 2026";
        $company     = "Auto24 GmbH";
        $address     = "Altschauerberg 8, 85049 Ingolstadt";
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

    <main class="content-main">
        <div class="content-section">
            <div class="home-badge"><?php echo $lastUpdated; ?></div>
            <h1><?php echo $pageTitle; ?></h1>
            <p>Diese Allgemeinen Geschäftsbedingungen regeln die Nutzung der Plattform <?php echo $company; ?> und gelten für alle Nutzer.</p>

            <h2>§ 1 Geltungsbereich</h2>
            <p>Diese AGB gelten für alle Verträge, die zwischen <?php echo $company; ?>, <?php echo $address; ?>, und dem Nutzer der Plattform auto24.de geschlossen werden. Abweichende Bedingungen des Nutzers werden nicht anerkannt, es sei denn, Auto24 stimmt ihrer Geltung ausdrücklich schriftlich zu.</p>

            <h2>§ 2 Leistungsbeschreibung</h2>
            <p>Auto24 betreibt eine Online-Plattform für den Kauf und Verkauf von Kraftfahrzeugen. Die Plattform bietet:</p>
            <ul>
                <li>Veröffentlichung von Fahrzeuginseraten</li>
                <li>Suche und Filterung nach Fahrzeugen</li>
                <li>Kontaktaufnahme zwischen Käufern und Verkäufern</li>
                <li>Informationen zu Finanzierung und Versicherung</li>
            </ul>

            <h2>§ 3 Registrierung und Nutzerkonto</h2>
            <p>Die Nutzung bestimmter Funktionen setzt eine Registrierung voraus. Der Nutzer ist verpflichtet:</p>
            <ul>
                <li>Wahrheitsgemäße Angaben zu machen</li>
                <li>Seine Zugangsdaten vertraulich zu behandeln</li>
                <li>Auto24 unverzüglich über unbefugte Nutzung seines Kontos zu informieren</li>
                <li>Nur eine Registrierung pro Person vorzunehmen</li>
            </ul>

            <h2>§ 4 Nutzungsrechte und Verbote</h2>
            <p>Der Nutzer erhält ein einfaches, nicht übertragbares Recht zur Nutzung der Plattform. Es ist ausdrücklich untersagt:</p>
            <ul>
                <li>Falsche oder irreführende Fahrzeugangaben zu machen</li>
                <li>Die Plattform für illegale Zwecke zu nutzen</li>
                <li>Andere Nutzer zu belästigen oder zu schädigen</li>
                <li>Automatisierte Datenabrufe (Scraping) ohne Genehmigung durchzuführen</li>
                <li>Die Sicherheitssysteme der Plattform zu umgehen</li>
            </ul>

            <h2>§ 5 Verantwortlichkeit für Inserate</h2>
            <p>Für den Inhalt der Fahrzeuginserate ist ausschließlich der jeweilige Inserent verantwortlich. Auto24 übernimmt keine Gewähr für die Richtigkeit, Vollständigkeit und Aktualität der Inseratinhalte.</p>

            <h2>§ 6 Preise und Zahlungsbedingungen</h2>
            <p>Das Einstellen von Inseraten ist für Privatpersonen kostenlos. Für gewerbliche Anbieter gelten die jeweils aktuellen Preislisten von Auto24. Alle Preise verstehen sich inklusive der gesetzlichen Mehrwertsteuer.</p>

            <h2>§ 7 Haftungsbeschränkung</h2>
            <p>Auto24 haftet nicht für:</p>
            <ul>
                <li>Schäden aus der Nutzung der Inserate durch Dritte</li>
                <li>Technische Ausfälle oder Unterbrechungen der Plattform</li>
                <li>Falsche Angaben von Inserenten</li>
                <li>Schäden aus direkten Vertragsabschlüssen zwischen Nutzern</li>
            </ul>
            <p>Die Haftung für Vorsatz und grobe Fahrlässigkeit bleibt unberührt.</p>

            <h2>§ 8 Kündigung und Sperrung</h2>
            <p>Nutzer können ihr Konto jederzeit kündigen. Auto24 behält sich das Recht vor, Nutzerkonten bei Verstößen gegen diese AGB ohne Vorankündigung zu sperren oder zu löschen.</p>

            <h2>§ 9 Änderungen der AGB</h2>
            <p>Auto24 behält sich vor, diese AGB jederzeit zu ändern. Über wesentliche Änderungen werden registrierte Nutzer per E-Mail informiert. Die weitere Nutzung der Plattform nach Inkrafttreten der geänderten AGB gilt als Zustimmung.</p>

            <h2>§ 10 Anwendbares Recht und Gerichtsstand</h2>
            <p>Es gilt das Recht der Bundesrepublik Deutschland. Gerichtsstand für alle Streitigkeiten ist Ingolstadt, sofern der Nutzer Kaufmann, juristische Person des öffentlichen Rechts oder öffentlich-rechtliches Sondervermögen ist.</p>
        </div>
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
