<?php
$data = json_decode(file_get_contents("items.json"), true);
$fahrzeuge = $data["fahrzeuge"];

if (!isset($_GET["pid"])) {
    die("Parameter fehlt!");
}

if (empty($_GET["pid"])) {
    die("Keine ID übergeben!");
}

$pid = $_GET["pid"];
$fahrzeug = null;

foreach ($fahrzeuge as $auto) {
    if ($auto["iid"] == $pid) {
        $fahrzeug = $auto;
        break;
    }
}

if ($fahrzeug === null) {
    die("Fahrzeug wurde nicht gefunden!");
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo $fahrzeug["name"]; ?></title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>

<nav>
    <a href="index.php" class="nav-logo">Auto<span>24</span></a>
    <ul class="nav-links">
        <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
        <li><a href="fahrzeug-verkaufen.php">Auto verkaufen</a></li>
    </ul>
    <div class="nav-right">
        <a href="login.php" class="nav-auth-link" id="navAuthLink">Login</a>
        <button class="mode-btn" onclick="toggleMode()">Light</button>
    </div>
</nav>

<main class="home-main">
    <section class="auth-section">
        <div class="auth-card">

            <h1><?php echo $fahrzeug["name"]; ?></h1>

            <img 
                src="<?php echo $fahrzeug["imagepath"]; ?>" 
                alt="<?php echo $fahrzeug["name"]; ?>" 
                class="car-img"
            >

            <p><?php echo $fahrzeug["beschreibung"]; ?></p>

            <p><strong>Preis:</strong> <?php echo number_format($fahrzeug["preis"], 0, ",", "."); ?> €</p>
            <p><strong>Marke:</strong> <?php echo $fahrzeug["marke"]; ?></p>
            <p><strong>Modell:</strong> <?php echo $fahrzeug["modell"]; ?></p>
            <p><strong>Baujahr:</strong> <?php echo $fahrzeug["baujahr"]; ?></p>
            <p><strong>Kraftstoff:</strong> <?php echo $fahrzeug["kraftstoff"]; ?></p>
            <p><strong>Kilometerstand:</strong> <?php echo number_format($fahrzeug["kilometerstand"], 0, ",", "."); ?> km</p>
            <p><strong>Leistung:</strong> <?php echo $fahrzeug["leistung_ps"]; ?> PS</p>
            <p><strong>Antrieb:</strong> <?php echo $fahrzeug["antrieb"]; ?></p>

            <div style="margin: 24px 0 8px;">
                <button
                    id="buchungsBtn"
                    data-car-id="<?php echo htmlspecialchars($fahrzeug['iid']); ?>"
                    data-car-name="<?php echo htmlspecialchars($fahrzeug['name']); ?>"
                    data-car-price="<?php echo (int)$fahrzeug['preis']; ?>"
                    style="max-width:400px;">
                    Jetzt buchen
                </button>
                <p id="buchungsNote" class="buchungs-note"></p>
            </div>

            <a href="gebrauchtwagenList.php" class="home-btn-secondary" style="display:inline-block; max-width:400px;">
                Zurück zur Liste
            </a>

        </div>
    </section>
</main>

<script src="validation.js"></script>
</body>
</html>