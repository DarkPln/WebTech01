<?php
session_start();

// POST: einzeln entfernen oder alle leeren
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['favorites'])) $_SESSION['favorites'] = [];

    if (isset($_POST['remove_id'])) {
        $rid = (int)$_POST['remove_id'];
        $_SESSION['favorites'] = array_values(
            array_filter($_SESSION['favorites'], fn($id) => $id !== $rid)
        );
    }
    if (isset($_POST['clear_all'])) {
        $_SESSION['favorites'] = [];
    }
    header('Location: merkliste.php');
    exit;
}

$favorites = $_SESSION['favorites'] ?? [];

$json     = file_get_contents('items.json');
$data     = json_decode($json, true);
$allAutos = [];
foreach ($data['fahrzeuge'] as $f) {
    $allAutos[$f['iid']] = $f;
}

$gemerkteAutos = [];
foreach ($favorites as $iid) {
    if (isset($allAutos[$iid])) {
        $gemerkteAutos[] = $allAutos[$iid];
    }
}

$total = array_sum(array_column($gemerkteAutos, 'preis'));
$anzahl = count($gemerkteAutos);

if ($anzahl >= 3) {
    $rabattProzent = 20;
} elseif ($anzahl >= 2) {
    $rabattProzent = 10;
} else {
    $rabattProzent = 0;
}

$rabattBetrag = $total * ($rabattProzent / 100);
$endbetrag = $total - $rabattBetrag;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Merkliste - Auto24</title>
    <link rel="stylesheet" href="mystyle.css">

</head>
<body>

<nav>
    <a href="index.php" class="nav-logo">Auto<span>24</span></a>
    <ul class="nav-links">
        <li><a href="index.php">Neuwagen</a></li>
        <li><a href="gebrauchtwagenList.php">Gebrauchtwagen</a></li>
        <li><a href="fahrzeug-verkaufen.php">Auto verkaufen</a></li>
    </ul>
    <div class="nav-right">
        <a href="merkliste.php" class="nFav-btn">
            ♡ Merkliste (<?= count($favorites) ?>)
        </a>
    </div>
</nav>

<div class="merkliste-wrap">

    <div class="merkliste-title">MERKLISTE</div>
<div class="merkliste-sub">
    <?php
    $anzahl = count($gemerkteAutos);
    if ($anzahl === 0)     echo 'Keine Fahrzeuge gespeichert';
    elseif ($anzahl === 1) echo '1 Fahrzeug gespeichert';
    else                   echo $anzahl . ' Fahrzeuge gespeichert';
    ?>
</div>

    <?php if (empty($gemerkteAutos)): ?>

        <div class="merkliste-empty">
            <div class="merkliste-empty-heart">♡</div>
            <div class="merkliste-empty-title">Deine Merkliste ist leer</div>
            <p>Füge Fahrzeuge über das Herz-Symbol hinzu.</p>
            <br>
            <a href="gebrauchtwagenList.php" class="merkliste-btn-primary" style="display:inline-block; margin-top:8px;">
                Zur Fahrzeugsuche
            </a>
        </div>

    <?php else: ?>

        <!-- Tabellen-Header -->
        <div class="merkliste-header">
            <div></div>
            <div>Fahrzeug</div>
            <div>Kilometerstand</div>
            <div>Preis</div>
            <div></div>
        </div>

        <!-- Zeilen -->
        <?php foreach ($gemerkteAutos as $auto): ?>
            <div class="merkliste-row">

                <!-- Checkbox -->
                <input type="checkbox" class="merkliste-checkbox" 
                data-car-id="<?= $auto['iid'] ?>"
                data-car-name="<?= htmlspecialchars($auto['name']) ?>"
                data-car-price="<?= (int)$auto['preis'] ?>">

                <!-- Bild -->
                <img src="<?= $auto['imagepath'] ?>" alt="<?= $auto['name'] ?>">

                <!-- Info -->
                <div>
                    <div class="merkliste-row-info-make"><?= $auto['marke'] ?></div>
                    <div class="merkliste-row-info-model"><?= $auto['modell'] ?></div>
                    <div class="merkliste-row-info-meta">
                        <?= $auto['baujahr'] ?> · <?= $auto['kraftstoff'] ?> · <?= $auto['leistung_ps'] ?> PS · <?= ucfirst($auto['unterkategorie']) ?>
                    </div>
                </div>

                <!-- KM -->
                <div class="merkliste-row-spec">
                    <?= number_format($auto['kilometerstand'], 0, ',', '.') ?> km
                </div>

                <!-- Preis -->
                <div class="merkliste-row-price">
                    <?= number_format($auto['preis'], 0, ',', '.') ?> €
                    <span>inkl. MwSt.</span>
                </div>

                <div style="display:flex; flex-direction:column; gap:8px; align-items:center;">
            
                <!-- Entfernen -->
                <form method="POST" action="merkliste.php">
                    <input type="hidden" name="remove_id" value="<?= $auto['iid'] ?>">
                    <button class="merkliste-remove-btn" type="submit" title="Entfernen">&#x2715;</button>
                </form>

                <!-- Buchen Button — gleiche data-* wie in item.php -->
                <button
                    class="buchungsBtn car-btn"
                    data-car-id="<?= $auto['iid'] ?>"
                    data-car-name="<?= htmlspecialchars($auto['name']) ?>"
                    data-car-price="<?= (int)$auto['preis'] ?>">
                    Buchen
                </button>
            </div>
            </div>
        <?php endforeach; ?>

        <!-- Zusammenfassung -->
        <div class="merkliste-footer">
            <div>
            <!--Rabattberechnung-->
                
            <div class="merkliste-footer-total-label">Gesamtwert</div>
            <div class="merkliste-footer-total-val">
                <?= number_format($total, 0, ',', '.') ?> €
            </div>

            <div class="merkliste-footer-total-label">Rabatt</div>
            <div class="merkliste-footer-total-val">
                <?= $rabattProzent ?> %
                (-<?= number_format($rabattBetrag, 0, ',', '.') ?> €)
            </div>

            <div class="merkliste-footer-total-label">Endbetrag</div>
            <div class="merkliste-footer-total-val">
                <?= number_format($endbetrag, 0, ',', '.') ?> €
            </div>
        </div>
        <div class="merkliste-footer-btns">

            <a href="gebrauchtwagenList.php" class="merkliste-btn-primary" style="display:inline-flex; align-items:center;">Weiter suchen</a>
                <form method="POST" action="merkliste.php" style="margin:0">
                    <input type="hidden" name="clear_all" value="1">
                    <button class="merkliste-btn-ghost" type="submit">Alle entfernen</button>
                </form>

                <button id="buchungAusgewaehlt" class="merkliste-btn-booking" disabled>
                buchen (<span id="ausgewaehltCount">0</span>)
                </button>
            </div>
        </div>

    <?php endif; ?>

</div>


<script src="validation.js"></script>
<script src="favLogik.js"></script>
</body>
</html>
