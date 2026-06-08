<?php
session_start();
require_once 'db.php';

$userId = $_SESSION['user_id'] ?? null;
$useDB  = ($userId !== null && $userId > 0);

// POST: einzeln entfernen oder alle leeren
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($useDB) {
        $db = getDB();
        if (isset($_POST['remove_id'])) {
            $db->prepare('DELETE FROM favorites WHERE user_id = ? AND car_id = ?')
               ->execute([$userId, (int)$_POST['remove_id']]);
        }
        if (isset($_POST['clear_all'])) {
            $db->prepare('DELETE FROM favorites WHERE user_id = ?')->execute([$userId]);
        }
    } else {
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
    }
    header('Location: merkliste.php');
    exit;
}

// Favoriten laden: DB (eingeloggt) oder Session (Gast)
if ($useDB) {
    $stmt = getDB()->prepare('SELECT car_id FROM favorites WHERE user_id = ?');
    $stmt->execute([$userId]);
    $favorites = array_column($stmt->fetchAll(), 'car_id');
} else {
    $favorites = $_SESSION['favorites'] ?? [];
}

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

<?php require_once 'nav.php'; ?>

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
            <div class="merkliste-summary">
                <div class="merkliste-summary-row">
                    <span class="merkliste-summary-label">Gesamtwert</span>
                    <span class="merkliste-summary-val"><?= number_format($total, 0, ',', '.') ?> €</span>
                </div>
                <?php if ($rabattProzent > 0): ?>
                <div class="merkliste-summary-row">
                    <span class="merkliste-summary-label">Rabatt (<?= $rabattProzent ?> %)</span>
                    <span class="merkliste-summary-val merkliste-summary-discount">−<?= number_format($rabattBetrag, 0, ',', '.') ?> €</span>
                </div>
                <?php endif; ?>
                <div class="merkliste-summary-sep"></div>
                <div class="merkliste-summary-row">
                    <span class="merkliste-summary-label merkliste-summary-label--total">Endbetrag</span>
                    <span class="merkliste-summary-val merkliste-summary-val--total"><?= number_format($endbetrag, 0, ',', '.') ?> €</span>
                </div>
            </div>
        <div class="merkliste-footer-btns">

            <a href="gebrauchtwagenList.php" class="merkliste-btn-primary">Weiter suchen</a>
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
