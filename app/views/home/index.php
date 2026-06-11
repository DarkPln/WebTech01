<!-- Niclas -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Willkommen bei unserer Web-Anwendung "Auto24"-Startseite</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>
    <?php require VIEW_PATH . 'partials/nav.php'; ?>
    <main class="home-main">

    <section class="home-hero">
        <div class="home-hero-content">
            <div class="home-badge">Ihre Fahrzeugbörse</div>
            <h1>Willkommen bei <span>Auto24</span></h1>
            <p>Entdecken Sie die besten Angebote für Neu- und Gebrauchtwagen. Kaufen, verkaufen und vergleichen Sie Fahrzeuge einfach, sicher und schnell.</p>
            <div class="home-buttons">
                <a href="<?= BASE_URL ?>/cars" class="home-btn-primary">Autos ansehen</a>
                <a href="<?= BASE_URL ?>/listings/sell" class="home-btn-secondary">Auto verkaufen</a>
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
            <div class="car-carousel-track" id="carouselTrack" data-repeat="<?= $carouselRepeat ?>">
                <?php for ($r = 0; $r < $carouselRepeat; $r++): ?>
                    <?php foreach ($carouselCars as $c): ?>
                    <a href="<?= BASE_URL ?>/cars/<?= (int)$c['iid'] ?>" class="carousel-card">
                        <div class="carousel-card-img">
                            <img src="<?= htmlspecialchars($c['imagepath']) ?>"
                                 alt="<?= htmlspecialchars($c['marke'] . ' ' . $c['modell']) ?>"
                                 loading="lazy">
                        </div>
                        <div class="carousel-card-body">
                            <div class="carousel-card-make"><?= htmlspecialchars(strtoupper($c['marke'])) ?></div>
                            <div class="carousel-card-model"><?= htmlspecialchars($c['modell']) ?></div>
                            <div class="carousel-card-price"><?= number_format((float)$c['preis'], 0, ',', '.') ?> €</div>
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
                <h3>Auto kaufen</h3>
                <p>Finden Sie passende Fahrzeuge aus verschiedenen Kategorien.</p>
                <a href="<?= BASE_URL ?>/cars">Autos kaufen</a>
            </div>
            <div class="home-category-card">
                <h3>Auto verkaufen</h3>
                <p>Inserieren Sie Ihr Fahrzeug schnell und unkompliziert.</p>
                <a href="<?= BASE_URL ?>/listings/sell">Autos verkaufen</a>
            </div>
            <div class="home-category-card">
                <h3>Login-Bereich</h3>
                <p>Melden Sie sich an, um Inserate zu verwalten.</p>
                <a href="<?= BASE_URL ?>/auth/login">Zum Login</a>
            </div>
        </div>
    </section>

    <section class="home-about">
        <h2>Über uns</h2>
        <p>Auto24 ist eine moderne Online-Plattform für den Kauf und Verkauf von Fahrzeugen. Unser Ziel ist es, eine benutzerfreundliche und sichere Umgebung zu schaffen, in der Käufer und Verkäufer schnell zusammenfinden.</p>
        <a href="<?= BASE_URL ?>/about" class="home-btn-secondary">Mehr erfahren</a>
    </section>

    </main>

    <?php require VIEW_PATH . 'partials/footer.php'; ?>
    <script src="<?= BASE_URL ?>/js/carousel.js"></script>
</body>
</html>
<!-- Niclas -->
