<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Fahrzeug nicht gefunden – Auto24</title>
    <link rel="stylesheet" href="<?php echo  BASE_URL ?>/mystyle.css">
    <!-- Niclas: Implementiert css Datei -->    
</head>
<body>
    <!-- Niclas: Link zu einer Seite und fügt Inhalt mit require direkt hier ein;
     Unterschied zu include: bei require bricht PHP ab, wenn Datei nicht da, 
     bei include läuft PHP mit Warnung weiter -->
     <?php require VIEW_PATH . 'partials/nav.php'; ?>
    <?php require VIEW_PATH . 'partials/nav.php'; ?>

    <main class="error-page">
        <div class="error-container">
            <div class="error-code">404</div>
            <h1 class="error-title">Fahrzeug nicht gefunden</h1>
            <p class="error-message">
                Sehr geehrter Kunde, leider ist das von Ihnen gewünschte Fahrzeug derzeit nicht verfügbar.
                Dies kann an unterschiedlichen Gründen liegen.
                Bitte prüfen Sie, ob Sie ein anderes Fahrzeug suchen wollten oder schauen Sie sich bei unseren Angeboten um.
            </p>
            <div class="error-actions">
                <a href="<?php echo  BASE_URL ?>/cars" class="error-btn-primary">Fahrzeugangebot ansehen</a>
                <a href="<?php echo  BASE_URL ?>/" class="error-btn-secondary">Zur Hauptseite</a>
            </div>
        </div>
    </main>

    <?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>