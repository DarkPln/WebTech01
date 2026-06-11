<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Merkliste - Auto24</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/mystyle.css">
</head>
<body>

<?php require VIEW_PATH . 'partials/nav.php'; ?>

<div class="merkliste-wrap">

    <div class="merkliste-title">MERKLISTE</div>
    <div class="merkliste-sub">
        <?php
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
            <a href="<?= BASE_URL ?>/cars" class="merkliste-btn-primary" style="display:inline-block; margin-top:8px;">Zur Fahrzeugsuche</a>
        </div>
    <?php else: ?>
        <div class="merkliste-header">
            <div></div><div>Fahrzeug</div><div>Kilometerstand</div><div>Preis</div><div></div>
        </div>

        <?php foreach ($gemerkteAutos as $auto): ?>
            <div class="merkliste-row">
                <input type="checkbox" class="merkliste-checkbox"
                    data-car-id="<?= $auto['iid'] ?>"
                    data-car-name="<?= htmlspecialchars($auto['name']) ?>"
                    data-car-price="<?= (int)$auto['preis'] ?>">

                <img src="<?= htmlspecialchars($auto['imagepath']) ?>" alt="<?= htmlspecialchars($auto['name']) ?>">

                <div>
                    <div class="merkliste-row-info-make"><?= htmlspecialchars($auto['marke']) ?></div>
                    <div class="merkliste-row-info-model"><?= htmlspecialchars($auto['modell']) ?></div>
                    <div class="merkliste-row-info-meta">
                        <?= $auto['baujahr'] ?> · <?= htmlspecialchars($auto['kraftstoff']) ?> · <?= $auto['leistung_ps'] ?> PS · <?= ucfirst(htmlspecialchars($auto['unterkategorie'])) ?>
                    </div>
                </div>

                <div class="merkliste-row-spec"><?= number_format($auto['kilometerstand'], 0, ',', '.') ?> km</div>

                <div class="merkliste-row-price">
                    <?= number_format($auto['preis'], 0, ',', '.') ?> €
                    <span>inkl. MwSt.</span>
                </div>

                <div style="display:flex; flex-direction:column; gap:8px; align-items:center;">
                    <form method="POST" action="<?= BASE_URL ?>/merkliste/remove">
                        <input type="hidden" name="remove_id" value="<?= $auto['iid'] ?>">
                        <button class="merkliste-remove-btn" type="submit" title="Entfernen">&#x2715;</button>
                    </form>
                    <button class="buchungsBtn car-btn"
                        data-car-id="<?= $auto['iid'] ?>"
                        data-car-name="<?= htmlspecialchars($auto['name']) ?>"
                        data-car-price="<?= (int)$auto['preis'] ?>">Buchen</button>
                </div>
            </div>
        <?php endforeach; ?>

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
                <a href="<?= BASE_URL ?>/cars" class="merkliste-btn-primary">Weiter suchen</a>
                <form method="POST" action="<?= BASE_URL ?>/merkliste/remove" style="margin:0">
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

<script src="<?= BASE_URL ?>/js/favLogik.js"></script>
<?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
