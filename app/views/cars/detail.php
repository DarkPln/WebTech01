<!DOCTYPE html>
<!-- Lukas, Tim, Niclas -->
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo $compareMode ? 'Fahrzeugvergleich' : htmlspecialchars($fahrzeug['name']); ?> - Auto24</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/mystyle.css">
</head>
<body>

<?php require VIEW_PATH . 'partials/nav.php'; ?>

<?php
// IDs der aktuell verglichenen Fahrzeuge als JSON für JS
$currentIdsJson = json_encode(array_map('intval', $currentIds));
?>

<?php if ($compareMode): ?>

<!-- vergleichssicht  -->
<div class="cmp-wrap">

    <div class="cmp-header">
        <h1>Fahrzeug<span>vergleich</span></h1>
        <p class="cmp-subtitle">
            <?php echo implode(' &nbsp;vs.&nbsp; ', array_map(
                fn($f) => htmlspecialchars($f['marke'] . ' ' . $f['modell']),
                $fahrzeuge
            )); ?>
        </p>
    </div>

    <?php if (!empty($notFoundIds)): ?>
    <div class="cmp-warning">
        ⚠ Folgende ID<?php echo count($notFoundIds) > 1 ? 's' : ''; ?> <?php echo count($notFoundIds) > 1 ? 'wurden' : 'wurde'; ?> nicht gefunden und aus dem Vergleich entfernt:
        <strong><?php echo implode(', ', array_map('intval', $notFoundIds)); ?></strong>
    </div>
    <?php endif; ?>

    <!-- Fahrzeugbilder -->
    <div class="cmp-images" style="grid-template-columns: repeat(<?php echo count($fahrzeuge); ?>, 1fr);">
        <?php foreach ($fahrzeuge as $f): ?>
        <div class="cmp-img-col">
            <a href="<?php echo BASE_URL; ?>/cars/detail?id=<?php echo $f['iid']; ?>">
                <img src="<?php echo htmlspecialchars($f['imagepath']); ?>"
                     alt="<?php echo htmlspecialchars($f['name']); ?>">
            </a>
            <div class="cmp-car-make"><?php echo htmlspecialchars(strtoupper($f['marke'])); ?></div>
            <div class="cmp-car-model"><?php echo htmlspecialchars($f['modell']); ?></div>
            <div class="cmp-car-sub"><?php echo $f['baujahr']; ?> · <?php echo htmlspecialchars($f['kraftstoff']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Vergleichstabelle -->
    <table class="cmp-table">
        <thead>
            <tr>
                <th class="cmp-label"></th>
                <?php foreach ($fahrzeuge as $f): ?>
                <th class="cmp-val cmp-head"><?php echo htmlspecialchars($f['marke'] . ' ' . $f['modell']); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
        <?php
        $rows = [
            ['Preis',          fn($f) => '<strong>' . number_format($f['preis'], 0, ',', '.') . ' €</strong>'],
            ['Baujahr',        fn($f) => $f['baujahr']],
            ['Kraftstoff',     fn($f) => htmlspecialchars($f['kraftstoff'])],
            ['Kilometerstand', fn($f) => number_format($f['kilometerstand'], 0, ',', '.') . ' km'],
            ['Leistung',       fn($f) => $f['leistung_ps'] . ' PS'],
            ['Antrieb',        fn($f) => htmlspecialchars($f['antrieb'])],
            ['Kategorie',      fn($f) => ucfirst(htmlspecialchars($f['unterkategorie']))],
            ['Verfügbarkeit',  fn($f) => $f['isSold'] ? '<span style="color:#ff6666">Reserviert</span>' : '<span style="color:#66cc88">Verfügbar</span>'],
        ];
        foreach ($rows as [$label, $fn]):
        ?>
        <tr>
            <td class="cmp-label"><?php echo $label; ?></td>
            <?php foreach ($fahrzeuge as $f): ?>
            <td class="cmp-val"><?php echo $fn($f); ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Weiteres Fahrzeug hinzufügen -->
    <div class="item-compare-box">
        <label for="cmpAddSelect">Weiteres Fahrzeug zum Vergleich hinzufügen:</label>
        <div class="item-compare-row">
            <select id="cmpAddSelect">
                <option value="">— Fahrzeug auswählen —</option>
                <?php foreach ($alleCars as $c):
                    if (in_array((int)$c['iid'], array_map('intval', $currentIds))) continue; ?>
                <option value="<?php echo (int)$c['iid']; ?>">
                    <?php echo htmlspecialchars($c['marke'] . ' ' . $c['modell'] . ' (' . $c['baujahr'] . ')'); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <button class="item-compare-btn" type="button" onclick="
                var sel = document.getElementById('cmpAddSelect');
                if (sel.value) window.location.href = window.location.href + '&id=' + sel.value;
            ">Hinzufügen</button>
        </div>
    </div>

    <!-- Aktionen -->
    <div class="cmp-actions">
        <a href="<?php echo BASE_URL; ?>/cars" class="item-btn-secondary">← Zurück zur Liste</a>
    </div>

</div>

<?php else: ?>

<!-- Einzelansicht -->
<div class="item-wrap">

    <div>
        <div class="item-img-wrap">
            <span class="item-badge">Gebraucht</span>
            <img src="<?php echo htmlspecialchars($fahrzeug['imagepath']); ?>" alt="<?php echo htmlspecialchars($fahrzeug['name']); ?>">
        </div>

        <div class="item-specs-row">
            <div class="item-spec">
                <div class="item-spec-val"><?php echo $fahrzeug['leistung_ps']; ?> PS</div>
                <div class="item-spec-key">Leistung</div>
            </div>
            <div class="item-spec">
                <div class="item-spec-val"><?php echo number_format($fahrzeug['kilometerstand'], 0, ',', '.'); ?> km</div>
                <div class="item-spec-key">Kilometerstand</div>
            </div>
            <div class="item-spec">
                <div class="item-spec-val"><?php echo htmlspecialchars($fahrzeug['antrieb']); ?></div>
                <div class="item-spec-key">Antrieb</div>
            </div>
        </div>
    </div>

    <div class="item-info">
        <div class="item-make"><?php echo htmlspecialchars($fahrzeug['marke']); ?></div>
        <div class="item-name"><?php echo htmlspecialchars($fahrzeug['modell']); ?></div>
        <div class="item-year"><?php echo $fahrzeug['baujahr']; ?> · <?php echo htmlspecialchars($fahrzeug['kraftstoff']); ?> · <?php echo ucfirst(htmlspecialchars($fahrzeug['unterkategorie'])); ?></div>

        <p class="item-desc"><?php echo htmlspecialchars($fahrzeug['beschreibung']); ?></p>

        <div class="item-details">
            <div class="item-detail-row"><span class="item-detail-label">Marke</span><span class="item-detail-val"><?php echo htmlspecialchars($fahrzeug['marke']); ?></span></div>
            <div class="item-detail-row"><span class="item-detail-label">Modell</span><span class="item-detail-val"><?php echo htmlspecialchars($fahrzeug['modell']); ?></span></div>
            <div class="item-detail-row"><span class="item-detail-label">Baujahr</span><span class="item-detail-val"><?php echo $fahrzeug['baujahr']; ?></span></div>
            <div class="item-detail-row"><span class="item-detail-label">Kraftstoff</span><span class="item-detail-val"><?php echo htmlspecialchars($fahrzeug['kraftstoff']); ?></span></div>
            <div class="item-detail-row"><span class="item-detail-label">Kilometerstand</span><span class="item-detail-val"><?php echo number_format($fahrzeug['kilometerstand'], 0, ',', '.'); ?> km</span></div>
            <div class="item-detail-row"><span class="item-detail-label">Leistung</span><span class="item-detail-val"><?php echo $fahrzeug['leistung_ps']; ?> PS</span></div>
            <div class="item-detail-row"><span class="item-detail-label">Antrieb</span><span class="item-detail-val"><?php echo htmlspecialchars($fahrzeug['antrieb']); ?></span></div>
        </div>

        <div class="item-price-box">
            <div class="item-price-label">Preis</div>
            <div class="item-price"><?php echo number_format($fahrzeug['preis'], 0, ',', '.'); ?> €</div>
            <div class="item-price-note">inkl. MwSt.</div>
        </div>

        <div class="item-financing">
            <button class="item-financing-toggle" id="financingToggle" type="button">
                <span>Finanzierung berechnen</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="item-financing-body" id="financingBody">
                <div class="item-financing-grid">
                    <div>
                        <label for="itemFinancingAmount">Betrag (€)</label>
                        <input type="number" id="itemFinancingAmount" value="<?php echo (int)$fahrzeug['preis']; ?>" min="1000" autocomplete="off">
                    </div>
                    <div>
                        <label for="itemLoanTerm">Laufzeit (Monate)</label>
                        <input type="number" id="itemLoanTerm" value="24" min="12" max="48" autocomplete="off">
                    </div>
                </div>
                <button type="button" onclick="calculateItemFinancing()" class="item-financing-btn">Berechnen</button>
                <div class="item-financing-result" id="itemFinancingResult"></div>
            </div>
        </div>

        <div class="item-actions">
            <div class="car-card"
                data-id="<?php echo $fahrzeug['iid']; ?>"
                data-make="<?php echo htmlspecialchars($fahrzeug['marke']); ?>"
                data-model="<?php echo htmlspecialchars($fahrzeug['modell']); ?>"
                data-year="<?php echo $fahrzeug['baujahr']; ?>"
                data-fuel="<?php echo htmlspecialchars($fahrzeug['kraftstoff']); ?>"
                data-km="<?php echo number_format($fahrzeug['kilometerstand'], 0, ',', '.'); ?> km"
                data-drive="<?php echo htmlspecialchars($fahrzeug['antrieb']); ?>"
                data-price="<?php echo $fahrzeug['preis']; ?>"
                data-badge="Gebraucht"
                class="car-card--ghost">
                <img class="car-img car-img--hidden" src="<?php echo htmlspecialchars($fahrzeug['imagepath']); ?>">
                <button id="itemFavBtn" class="car-fav item-fav-btn">♡</button>
            </div>

            <button
                id="buchungsBtn"
                class="item-btn-primary"
                data-car-id="<?php echo htmlspecialchars($fahrzeug['iid']); ?>"
                data-car-name="<?php echo htmlspecialchars($fahrzeug['name']); ?>"
                data-car-price="<?php echo (int)$fahrzeug['preis']; ?>"
                <?php echo $isSold ? 'disabled' : ''; ?>>
                <?php echo $isSold ? 'Bereits reserviert' : 'Jetzt buchen'; ?>
            </button>
            <p id="buchungsNote" class="item-buchungs-note" <?php echo $isSold ? 'style="display:block;"' : ''; ?>><?php echo $isSold ? 'Dieses Fahrzeug ist bereits reserviert und nicht mehr verfügbar.' : ''; ?></p>

            <a href="<?php echo BASE_URL; ?>/cars/pdf?id=<?php echo $fahrzeug['iid']; ?>"
               target="_blank"
               class="item-btn-secondary">Fahrzeugdatenblatt (PDF)</a>
            <a href="<?php echo BASE_URL; ?>/cars" class="item-btn-secondary">← Zurück zur Liste</a>

            <!-- Vergleich mit Dropdown -->
            <div class="item-compare-box">
                <label for="compareSelect">Mit einem anderen Fahrzeug vergleichen:</label>
                <div class="item-compare-row">
                    <select class="choice-select" id="compareSelect">
                        <option value="">— Fahrzeug auswählen —</option>
                        <?php foreach ($alleCars as $c):
                            if ((int)$c['iid'] === (int)$fahrzeug['iid']) continue; ?>
                        <option value="<?php echo (int)$c['iid']; ?>">
                            <?php echo htmlspecialchars($c['marke'] . ' ' . $c['modell'] . ' (' . $c['baujahr'] . ')'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="compare-button" type="button" onclick="
                        var sel = document.getElementById('compareSelect');
                        if (sel.value) window.location.href = '<?php echo BASE_URL; ?>/cars/detail?id=<?php echo $fahrzeug['iid']; ?>&id=' + sel.value;
                    ">Vergleichen</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php endif; ?>

<script src="<?php echo BASE_URL; ?>/js/favLogik.js"></script>
<?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
