<!-- Niclas -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Willkommen bei unserer Web-Anwendung "Auto24"-Startseite</title>
    <link rel="stylesheet" href="mystyle.css">
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
    <?php require_once 'nav.php'; ?>
    <main class="home-main">

    <section class="home-hero">
        <div class="home-hero-content">
            <div class="home-badge"><?php echo $heroBadge; ?></div>

            <h1><?php echo $heroHeadline; ?> <span>Auto24</span></h1>

            <p><?php echo $heroText; ?></p>

            <div class="home-buttons">
                <a href="gebrauchtwagenList.php" class="home-btn-primary">Autos ansehen</a>
                <a href="fahrzeug-verkaufen.php" class="home-btn-secondary">Auto verkaufen</a>
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
                <a href="fahrzeug-verkaufen.php">Autos verkaufen</a>
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

    <?php require_once 'footer.php'; ?>
    <script src="validation.js"></script>
</body>
</html>
<!-- Niclas -->
