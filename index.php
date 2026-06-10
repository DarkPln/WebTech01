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
        require_once 'db.php';

        $heroBadge    = "Ihre Fahrzeugbörse";
        $heroHeadline = "Willkommen bei";
        $heroText     = "Entdecken Sie die besten Angebote für Neu- und Gebrauchtwagen. Kaufen, verkaufen und vergleichen Sie Fahrzeuge einfach, sicher und schnell.";
        $cat1Title    = "Auto kaufen";
        $cat1Text     = "Finden Sie passende Fahrzeuge aus verschiedenen Kategorien.";
        $cat2Title    = "Auto verkaufen";
        $cat2Text     = "Inserieren Sie Ihr Fahrzeug schnell und unkompliziert.";
        $cat3Title    = "Login-Bereich";
        $cat3Text     = "Melden Sie sich an, um Inserate zu verwalten.";
        $aboutHeadline = "Über uns";
        $aboutText    = "Auto24 ist eine moderne Online-Plattform für den Kauf und Verkauf von Fahrzeugen. Unser Ziel ist es, eine benutzerfreundliche und sichere Umgebung zu schaffen, in der Käufer und Verkäufer schnell zusammenfinden.";

        $carRes       = getDB()->query("SELECT iid, marke, modell, preis, imagepath, baujahr FROM cars ORDER BY id ASC");
        $carouselCars = $carRes ? $carRes->fetch_all(MYSQLI_ASSOC) : [];
        $carouselRepeat = empty($carouselCars) ? 0 : max(3, (int)ceil(18 / count($carouselCars)));
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

    <?php if (!empty($carouselCars)): ?>
    <section class="car-carousel-section">
        <div class="car-carousel-heading">
            <div class="carousel-label">Unser Angebot</div>
            <h2>Aktuelle <span>Fahrzeuge</span></h2>
        </div>
        <div class="car-carousel-viewport">
            <div class="car-carousel-track" id="carouselTrack" data-repeat="<?php echo $carouselRepeat; ?>">
                <?php for ($r = 0; $r < $carouselRepeat; $r++): ?>
                    <?php foreach ($carouselCars as $c): ?>
                    <a href="item.php?pid=<?php echo (int)$c['iid']; ?>" class="carousel-card">
                        <div class="carousel-card-img">
                            <img src="<?php echo htmlspecialchars($c['imagepath']); ?>"
                                 alt="<?php echo htmlspecialchars($c['marke'] . ' ' . $c['modell']); ?>"
                                 loading="lazy">
                        </div>
                        <div class="carousel-card-body">
                            <div class="carousel-card-make"><?php echo htmlspecialchars(strtoupper($c['marke'])); ?></div>
                            <div class="carousel-card-model"><?php echo htmlspecialchars($c['modell']); ?></div>
                            <div class="carousel-card-price"><?php echo number_format((float)$c['preis'], 0, ',', '.'); ?> €</div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

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
    <script src="carousel.js"></script>
</body>
</html>
<!-- Niclas -->
