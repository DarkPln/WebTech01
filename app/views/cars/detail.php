<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?= $compareMode ? 'Fahrzeugvergleich' : htmlspecialchars($fahrzeug['name']) ?> – Auto24</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>

<?php require VIEW_PATH . 'partials/nav.php'; ?>

<?php
// IDs der aktuell verglichenen Fahrzeuge als JSON für JS
$currentIdsJson = json_encode(array_map('intval', $currentIds));
?>

<?php if ($compareMode): ?>

<!-- ═══════════════ VERGLEICHSANSICHT ═══════════════ -->
<div class="cmp-wrap">

    <div class="cmp-header">
        <h1>Fahrzeug<span>vergleich</span></h1>
        <p class="cmp-subtitle">
            <?= implode(' &nbsp;vs.&nbsp; ', array_map(
                fn($f) => htmlspecialchars($f['marke'] . ' ' . $f['modell']),
                $fahrzeuge
            )) ?>
        </p>
    </div>

    <?php if (!empty($notFoundIds)): ?>
    <div class="cmp-warning">
        ⚠ Folgende ID<?= count($notFoundIds) > 1 ? 's' : '' ?> <?= count($notFoundIds) > 1 ? 'wurden' : 'wurde' ?> nicht gefunden und aus dem Vergleich entfernt:
        <strong><?= implode(', ', array_map('intval', $notFoundIds)) ?></strong>
    </div>
    <?php endif; ?>

    <!-- Fahrzeugbilder -->
    <div class="cmp-images" style="grid-template-columns: repeat(<?= count($fahrzeuge) ?>, 1fr);">
        <?php foreach ($fahrzeuge as $f): ?>
        <div class="cmp-img-col">
            <a href="<?= BASE_URL ?>/cars/detail?id=<?= $f['iid'] ?>">
                <img src="<?= htmlspecialchars($f['imagepath']) ?>"
                     alt="<?= htmlspecialchars($f['name']) ?>">
            </a>
            <div class="cmp-car-make"><?= htmlspecialchars(strtoupper($f['marke'])) ?></div>
            <div class="cmp-car-model"><?= htmlspecialchars($f['modell']) ?></div>
            <div class="cmp-car-sub"><?= $f['baujahr'] ?> · <?= htmlspecialchars($f['kraftstoff']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Vergleichstabelle -->
    <table class="cmp-table">
        <thead>
            <tr>
                <th class="cmp-label"></th>
                <?php foreach ($fahrzeuge as $f): ?>
                <th class="cmp-val cmp-head"><?= htmlspecialchars($f['marke'] . ' ' . $f['modell']) ?></th>
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
            <td class="cmp-label"><?= $label ?></td>
            <?php foreach ($fahrzeuge as $f): ?>
            <td class="cmp-val"><?= $fn($f) ?></td>
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
                <option value="<?= (int)$c['iid'] ?>">
                    <?= htmlspecialchars($c['marke'] . ' ' . $c['modell'] . ' (' . $c['baujahr'] . ')') ?>
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
        <a href="<?= BASE_URL ?>/cars" class="item-btn-secondary">← Zurück zur Liste</a>
    </div>

</div>

<?php else: ?>

<!-- ═══════════════ EINZELANSICHT ═══════════════ -->
<div class="item-wrap">

    <div>
        <div class="item-img-wrap">
            <span class="item-badge">Gebraucht</span>
            <img src="<?= htmlspecialchars($fahrzeug['imagepath']) ?>" alt="<?= htmlspecialchars($fahrzeug['name']) ?>">
        </div>

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
                <div class="item-spec-val"><?= htmlspecialchars($fahrzeug['antrieb']) ?></div>
                <div class="item-spec-key">Antrieb</div>
            </div>
        </div>
    </div>

    <div class="item-info">
        <div class="item-make"><?= htmlspecialchars($fahrzeug['marke']) ?></div>
        <div class="item-name"><?= htmlspecialchars($fahrzeug['modell']) ?></div>
        <div class="item-year"><?= $fahrzeug['baujahr'] ?> · <?= htmlspecialchars($fahrzeug['kraftstoff']) ?> · <?= ucfirst(htmlspecialchars($fahrzeug['unterkategorie'])) ?></div>

        <p class="item-desc"><?= htmlspecialchars($fahrzeug['beschreibung']) ?></p>

        <div class="item-details">
            <div class="item-detail-row"><span class="item-detail-label">Marke</span><span class="item-detail-val"><?= htmlspecialchars($fahrzeug['marke']) ?></span></div>
            <div class="item-detail-row"><span class="item-detail-label">Modell</span><span class="item-detail-val"><?= htmlspecialchars($fahrzeug['modell']) ?></span></div>
            <div class="item-detail-row"><span class="item-detail-label">Baujahr</span><span class="item-detail-val"><?= $fahrzeug['baujahr'] ?></span></div>
            <div class="item-detail-row"><span class="item-detail-label">Kraftstoff</span><span class="item-detail-val"><?= htmlspecialchars($fahrzeug['kraftstoff']) ?></span></div>
            <div class="item-detail-row"><span class="item-detail-label">Kilometerstand</span><span class="item-detail-val"><?= number_format($fahrzeug['kilometerstand'], 0, ',', '.') ?> km</span></div>
            <div class="item-detail-row"><span class="item-detail-label">Leistung</span><span class="item-detail-val"><?= $fahrzeug['leistung_ps'] ?> PS</span></div>
            <div class="item-detail-row"><span class="item-detail-label">Antrieb</span><span class="item-detail-val"><?= htmlspecialchars($fahrzeug['antrieb']) ?></span></div>
        </div>

        <div class="item-price-box">
            <div class="item-price-label">Preis</div>
            <div class="item-price"><?= number_format($fahrzeug['preis'], 0, ',', '.') ?> €</div>
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
                        <input type="number" id="itemFinancingAmount" value="<?= (int)$fahrzeug['preis'] ?>" min="1000" autocomplete="off">
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
                data-id="<?= $fahrzeug['iid'] ?>"
                data-make="<?= htmlspecialchars($fahrzeug['marke']) ?>"
                data-model="<?= htmlspecialchars($fahrzeug['modell']) ?>"
                data-year="<?= $fahrzeug['baujahr'] ?>"
                data-fuel="<?= htmlspecialchars($fahrzeug['kraftstoff']) ?>"
                data-km="<?= number_format($fahrzeug['kilometerstand'], 0, ',', '.') ?> km"
                data-drive="<?= htmlspecialchars($fahrzeug['antrieb']) ?>"
                data-price="<?= $fahrzeug['preis'] ?>"
                data-badge="Gebraucht"
                class="car-card--ghost">
                <img class="car-img car-img--hidden" src="<?= htmlspecialchars($fahrzeug['imagepath']) ?>">
                <button id="itemFavBtn" class="car-fav item-fav-btn">♡</button>
            </div>

            <button
                id="buchungsBtn"
                class="item-btn-primary"
                data-car-id="<?= htmlspecialchars($fahrzeug['iid']) ?>"
                data-car-name="<?= htmlspecialchars($fahrzeug['name']) ?>"
                data-car-price="<?= (int)$fahrzeug['preis'] ?>"
                <?= $isSold ? 'disabled' : '' ?>>
                <?= $isSold ? 'Bereits reserviert' : 'Jetzt buchen' ?>
            </button>
            <p id="buchungsNote" class="item-buchungs-note" <?= $isSold ? 'style="display:block;"' : '' ?>><?= $isSold ? 'Dieses Fahrzeug ist bereits reserviert und nicht mehr verfügbar.' : '' ?></p>

            <a href="<?= BASE_URL ?>/cars/pdf?id=<?= $fahrzeug['iid'] ?>"
               target="_blank"
               class="item-btn-secondary">Fahrzeugdatenblatt (PDF)</a>
            <a href="<?= BASE_URL ?>/cars" class="item-btn-secondary">← Zurück zur Liste</a>

            <!-- Vergleich mit Dropdown -->
            <div class="item-compare-box">
                <label for="compareSelect">Mit einem anderen Fahrzeug vergleichen:</label>
                <div class="item-compare-row">
                    <select class="choice-select" id="compareSelect">
                        <option value="">— Fahrzeug auswählen —</option>
                        <?php foreach ($alleCars as $c):
                            if ((int)$c['iid'] === (int)$fahrzeug['iid']) continue; ?>
                        <option value="<?= (int)$c['iid'] ?>">
                            <?= htmlspecialchars($c['marke'] . ' ' . $c['modell'] . ' (' . $c['baujahr'] . ')') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="compare-button" type="button" onclick="
                        var sel = document.getElementById('compareSelect');
                        if (sel.value) window.location.href = '<?= BASE_URL ?>/cars/detail?id=<?= $fahrzeug['iid'] ?>&id=' + sel.value;
                    ">Vergleichen</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php endif; ?>

<script src="<?= BASE_URL ?>/js/favLogik.js"></script>
<?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
