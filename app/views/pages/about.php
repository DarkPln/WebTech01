<!-- Lukas -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Über uns - Auto24</title>
    <link rel="stylesheet" href="<?php echo  BASE_URL ?>/mystyle.css">
</head>

<body class="about-page">
    <?php require VIEW_PATH . 'partials/nav.php'; ?>

    <main class="home-main">

        <section class="home-hero">
            <div class="home-hero-content">
                <div class="home-badge">Über uns</div>
                <h1>Auto<span>24</span></h1>
                <p>Die führende Online-Plattform für den Kauf und Verkauf von Fahrzeugen. Unkompliziert, sicher und schnell – Ihre vertrauenswürdige Fahrzeugbörse seit 2024.</p>
            </div>
        </section>

        <section class="about-services-section">
            <h2>Unsere Dienstleistungen</h2>

            <div class="about-service-grid">
                <div class="about-service-card">
                    <h3>Kauf von Neuwagen</h3>
                    <p>Entdecken Sie die neuesten Modelle führender Automobilhersteller zu fairen Preisen.</p>
                </div>

                <div class="about-service-card">
                    <h3>Kauf von Gebrauchtwagen</h3>
                    <p>Hochwertige geprüfte Gebrauchtwagen mit transparenter Fahrzeughistorie.</p>
                </div>

                <div class="about-service-card">
                    <h3>Verkauf von Fahrzeugen</h3>
                    <p>Verkaufen Sie Ihr Fahrzeug schnell und unkompliziert über unsere Plattform.</p>
                </div>

                <div class="about-service-card">
                    <h3>Fahrzeugvermittlung</h3>
                    <p>Professionelle Vermittlung zwischen Käufern und Verkäufern mit Bestpreisgarantie.</p>
                </div>
            </div>
        </section>

        <section class="home-about">
            <h2>Kontaktinformationen</h2>

            <div class="about-contact-wrapper">
                <div class="about-contact-card">
                    <p><strong>Unternehmen:</strong> Auto24 GmbH</p>
                    <p><strong>Adresse:</strong> Altschauerberg 8, 85049 Ingolstadt, Deutschland</p>
                    <p><strong>E-Mail:</strong> info@auto24.de</p>
                    <p><strong>Telefon:</strong> +49 (0) 841 / 123 456</p>
                    <p><strong>Öffnungszeiten:</strong> Montag - Freitag: 8:00 - 18:00 Uhr</p>
                </div>
            </div>
        </section>

        <section class="home-section">
            <h2>Unser Team</h2>
            <p class="about-team-intro">Auto24 wird von einem engagierten Team aus erfahrenen Fachleuten geleitet, die sich für Innovation und Kundenzufriedenheit einsetzen.</p>

            <div class="about-team-grid">
                <div class="home-category-card">
                    <h3>Lukas Neumayer</h3>
                    <p class="about-team-role">Geschäftsführer</p>
                </div>

                <div class="home-category-card">
                    <h3>Niclas Reuter</h3>
                    <p class="about-team-role">Geschäftsführer</p>
                </div>

                <div class="home-category-card">
                    <h3>Tim Höhn</h3>
                    <p class="about-team-role">Geschäftsführer</p>
                </div>
            </div>
        </section>

        <section class="home-about">
            <h2>Bereit loszulegen?</h2>
            <p>Entdecken Sie unsere große Auswahl an Fahrzeugen oder verkaufen Sie Ihr eigenes Auto.</p>
            <div class="home-buttons">
                <a href="<?php echo  BASE_URL ?>/cars" class="home-btn-primary">Fahrzeuge ansehen</a>
                <a href="<?php echo  BASE_URL ?>/" class="home-btn-secondary">Zur Startseite</a>
            </div>
        </section>

    </main>

    <?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
<!-- Tim -->
