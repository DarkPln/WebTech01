<!-- Tim -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Datenschutz - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>
    <?php
        $pageTitle   = "Datenschutzerklärung";
        $lastUpdated = "Stand: Mai 2026";
        $company     = "Auto24 GmbH";
        $address     = "Altschauerberg 8, 85049 Ingolstadt";
        $email       = "datenschutz@auto24.de";
    ?>

    <?php require_once 'nav.php'; ?>

    <!-- Hinweis: Teile des Inhalts dieser Seite, insbesondere Texte, wurden mithilfe von KI-Tools (u.a. Claude) erstellt. -->
    <main class="content-main">
        <div class="content-section">
            <div class="home-badge"><?php echo $lastUpdated; ?></div>
            <h1><?php echo $pageTitle; ?></h1>
            <p>Der Schutz Ihrer persönlichen Daten ist uns ein besonderes Anliegen. Wir verarbeiten Ihre Daten daher ausschließlich auf Grundlage der gesetzlichen Bestimmungen (DSGVO, TMG).</p>

            <h2>1. Verantwortlicher</h2>
            <p><?php echo $company; ?><br>
               <?php echo $address; ?><br>
               E-Mail: <?php echo $email; ?></p>

            <h2>2. Erhebung und Verarbeitung personenbezogener Daten</h2>
            <p>Wir erheben und verarbeiten personenbezogene Daten nur, soweit dies zur Bereitstellung unserer Dienste erforderlich ist:</p>
            <ul>
                <li><strong>Kontodaten:</strong> Benutzername und Passwort bei der Registrierung</li>
                <li><strong>Fahrzeugdaten:</strong> Informationen zu inserierten Fahrzeugen</li>
                <li><strong>Nutzungsdaten:</strong> IP-Adresse, Browsertyp, besuchte Seiten, Uhrzeit des Zugriffs</li>
                <li><strong>Kommunikation:</strong> Inhalte von Kontaktanfragen und Nachrichten</li>
            </ul>

            <h2>3. Zweck der Datenverarbeitung</h2>
            <p>Ihre Daten werden zu folgenden Zwecken verarbeitet:</p>
            <ul>
                <li>Bereitstellung und Verbesserung unserer Plattform</li>
                <li>Bearbeitung von Kauf- und Verkaufsanfragen</li>
                <li>Kundenservice und Support</li>
                <li>Verhinderung von Missbrauch und Betrug</li>
                <li>Einhaltung gesetzlicher Verpflichtungen</li>
            </ul>

            <h2>4. Speicherdauer</h2>
            <p>Wir speichern personenbezogene Daten nur so lange, wie es für die jeweiligen Verarbeitungszwecke erforderlich ist oder gesetzliche Aufbewahrungspflichten bestehen. Kontodaten werden nach Löschung des Accounts unverzüglich entfernt, sofern keine handels- oder steuerrechtlichen Aufbewahrungspflichten entgegenstehen.</p>

            <h2>5. Cookies</h2>
            <p>Unsere Website verwendet technisch notwendige Cookies sowie den lokalen Browserspeicher (localStorage), um:</p>
            <ul>
                <li>Ihren Login-Status zu speichern</li>
                <li>Ihre Darstellungspräferenzen (Hell-/Dunkel-Modus) zu merken</li>
                <li>Ihre Merkliste für Fahrzeuge zu speichern</li>
            </ul>
            <p>Diese Daten werden ausschließlich lokal in Ihrem Browser gespeichert und nicht an unsere Server übertragen.</p>

            <h2>6. Weitergabe von Daten</h2>
            <p>Eine Weitergabe Ihrer personenbezogenen Daten an Dritte erfolgt nur, wenn Sie ausdrücklich eingewilligt haben, dies zur Vertragserfüllung erforderlich ist oder wir gesetzlich dazu verpflichtet sind.</p>

            <h2>7. Ihre Rechte</h2>
            <p>Sie haben folgende Rechte in Bezug auf Ihre gespeicherten Daten:</p>
            <ul>
                <li><strong>Auskunftsrecht</strong> (Art. 15 DSGVO)</li>
                <li><strong>Recht auf Berichtigung</strong> (Art. 16 DSGVO)</li>
                <li><strong>Recht auf Löschung</strong> (Art. 17 DSGVO)</li>
                <li><strong>Recht auf Einschränkung der Verarbeitung</strong> (Art. 18 DSGVO)</li>
                <li><strong>Recht auf Datenübertragbarkeit</strong> (Art. 20 DSGVO)</li>
                <li><strong>Widerspruchsrecht</strong> (Art. 21 DSGVO)</li>
            </ul>
            <p>Zur Ausübung dieser Rechte wenden Sie sich bitte an: <strong><?php echo $email; ?></strong></p>

            <h2>8. Beschwerderecht</h2>
            <p>Sie haben das Recht, sich bei der zuständigen Datenschutzbehörde zu beschweren. Die zuständige Aufsichtsbehörde für Bayern ist das Bayerische Landesamt für Datenschutzaufsicht (BayLDA).</p>

            <h2>9. Änderungen dieser Datenschutzerklärung</h2>
            <p>Wir behalten uns vor, diese Datenschutzerklärung anzupassen, um sie an geänderte Rechtslagen oder bei Änderungen des Dienstes anzupassen. Die jeweils aktuelle Version ist auf dieser Seite verfügbar.</p>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>

    <script src="validation.js"></script>
</body>
</html>
<!-- Tim -->
