<?php
session_start();
require_once "db.php";

if (!isset($_GET["pid"])) die("Parameter fehlt!");
if (empty($_GET["pid"]))  die("Keine ID übergeben!");

$pid  = (int)$_GET["pid"];
$db   = getDB();
$stmt = $db->prepare("SELECT * FROM cars WHERE iid = ?");
$stmt->bind_param('i', $pid);
$stmt->execute();
$fahrzeug = $stmt->get_result()->fetch_assoc();

if (!$fahrzeug) die("Fahrzeug wurde nicht gefunden!");
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?= $fahrzeug["name"] ?> – Auto24</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>

<?php $showFav = true; require_once 'nav.php'; ?>

<!-- HAUPTINHALT -->
<div class="item-wrap">

    <!-- LINKS: Bild + Specs -->
    <div>
        <div class="item-img-wrap">
            <span class="item-badge">Gebraucht</span>
            <img src="<?= $fahrzeug['imagepath'] ?>" alt="<?= $fahrzeug['name'] ?>">
        </div>

        <!-- Specs -->
        <div class="item-specs-row">
            <div class="item-spec">
                <div class="item-spec-val"><?= $fahrzeug['leistung_ps'] ?> PS</div>
                <div class="item-spec-key">Leistung</div>
            </div>
            <div class="item-spec">
                <div class="item-spec-val"><?= number_format($fahrzeug['kilometerstand'], 0, ',', '.') ?> km</div>
                <div class="item-spec-key">Kilometerstand</div>
            </div>
            <div class="item-spec">
                <div class="item-spec-val"><?= $fahrzeug['antrieb'] ?></div>
                <div class="item-spec-key">Antrieb</div>
            </div>
        </div>
    </div>

    <!-- RECHTS: Info + Kaufen -->
    <div class="item-info">
        <div class="item-make"><?= $fahrzeug['marke'] ?></div>
        <div class="item-name"><?= $fahrzeug['modell'] ?></div>
        <div class="item-year"><?= $fahrzeug['baujahr'] ?> · <?= $fahrzeug['kraftstoff'] ?> · <?= ucfirst($fahrzeug['unterkategorie']) ?></div>

        <p class="item-desc"><?= $fahrzeug['beschreibung'] ?></p>

        <!-- Details Tabelle -->
        <div class="item-details">
            <div class="item-detail-row">
                <span class="item-detail-label">Marke</span>
                <span class="item-detail-val"><?= $fahrzeug['marke'] ?></span>
            </div>
            <div class="item-detail-row">
                <span class="item-detail-label">Modell</span>
                <span class="item-detail-val"><?= $fahrzeug['modell'] ?></span>
            </div>
            <div class="item-detail-row">
                <span class="item-detail-label">Baujahr</span>
                <span class="item-detail-val"><?= $fahrzeug['baujahr'] ?></span>
            </div>
            <div class="item-detail-row">
                <span class="item-detail-label">Kraftstoff</span>
                <span class="item-detail-val"><?= $fahrzeug['kraftstoff'] ?></span>
            </div>
            <div class="item-detail-row">
                <span class="item-detail-label">Kilometerstand</span>
                <span class="item-detail-val"><?= number_format($fahrzeug['kilometerstand'], 0, ',', '.') ?> km</span>
            </div>
            <div class="item-detail-row">
                <span class="item-detail-label">Leistung</span>
                <span class="item-detail-val"><?= $fahrzeug['leistung_ps'] ?> PS</span>
            </div>
            <div class="item-detail-row">
                <span class="item-detail-label">Antrieb</span>
                <span class="item-detail-val"><?= $fahrzeug['antrieb'] ?></span>
            </div>
        </div>

        <!-- Preis + Buttons -->
        <div class="item-price-box">
            <div class="item-price-label">Preis</div>
            <div class="item-price"><?= number_format($fahrzeug['preis'], 0, ',', '.') ?> €</div>
            <div class="item-price-note">inkl. MwSt.</div>
        </div>

    <div class="car-card"
        data-id="<?= $fahrzeug['iid'] ?>"
        data-make="<?= $fahrzeug['marke'] ?>"
        data-model="<?= $fahrzeug['modell'] ?>"
        data-year="<?= $fahrzeug['baujahr'] ?>"
        data-fuel="<?= $fahrzeug['kraftstoff'] ?>"
        data-km="<?= number_format($fahrzeug['kilometerstand'], 0, ',', '.') ?> km"
        data-drive="<?= $fahrzeug['antrieb'] ?>"
        data-price="<?= $fahrzeug['preis'] ?>"
        data-badge="Gebraucht"
        style="background:none; border:none; padding:0; margin:0; overflow:visible;">
        
        <img class="car-img" src="<?= $fahrzeug['imagepath'] ?>" style="display:none">
        
        <button id="itemFavBtn" class="car-fav item-fav-btn">
            ♡
        </button>
    </div>


        <button
            id="buchungsBtn"
            class="item-btn-primary"
            data-car-id="<?= htmlspecialchars($fahrzeug['iid']) ?>"
            data-car-name="<?= htmlspecialchars($fahrzeug['name']) ?>"
            data-car-price="<?= (int)$fahrzeug['preis'] ?>">
            Jetzt buchen
        </button>
        <p id="buchungsNote" class="item-buchungs-note"></p>

        <a href="gebrauchtwagenList.php" class="item-btn-secondary">
            ← Zurück zur Liste
        </a>
    </div>

</div>



<script src="validation.js"></script>
<script src="favLogik.js"></script>
</body>
</html>
