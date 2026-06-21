<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Merkliste - Auto24</title>
    <link rel="stylesheet" href="<?php echo  BASE_URL ?>/mystyle.css">
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
            <a href="<?php echo  BASE_URL ?>/cars" class="merkliste-btn-primary merkliste-empty-btn">Zur Fahrzeugsuche</a>
        </div>
    <?php else: ?>
        <div class="merkliste-header">
            <div></div><div>Fahrzeug</div><div>Kilometerstand</div><div>Preis</div><div></div>
        </div>

        <?php foreach ($gemerkteAutos as $auto): ?>
            <div class="merkliste-row">
                <input type="checkbox" class="merkliste-checkbox"
                    data-car-id="<?php echo  $auto['iid'] ?>"
                    data-car-name="<?php echo  htmlspecialchars($auto['name']) ?>"
                    data-car-price="<?php echo  (int)$auto['preis'] ?>">

                <img src="<?php echo  htmlspecialchars($auto['imagepath']) ?>" alt="<?php echo  htmlspecialchars($auto['name']) ?>">

                <div>
                    <div class="merkliste-row-info-make"><?php echo  htmlspecialchars($auto['marke']) ?></div>
                    <div class="merkliste-row-info-model"><?php echo  htmlspecialchars($auto['modell']) ?></div>
                    <div class="merkliste-row-info-meta">
                        <?php echo  $auto['baujahr'] ?> · <?php echo  htmlspecialchars($auto['kraftstoff']) ?> · <?php echo  $auto['leistung_ps'] ?> PS · <?php echo  ucfirst(htmlspecialchars($auto['unterkategorie'])) ?>
                    </div>
                </div>

                <div class="merkliste-row-spec"><?php echo  number_format($auto['kilometerstand'], 0, ',', '.') ?> km</div>

                <div class="merkliste-row-price">
                    <?php echo  number_format($auto['preis'], 0, ',', '.') ?> €
                    <span>inkl. MwSt.</span>
                </div>

                <div class="merkliste-row-actions">
                    <form method="POST" action="<?php echo  BASE_URL ?>/merkliste/remove">
                        <input type="hidden" name="remove_id" value="<?php echo  $auto['iid'] ?>">
                        <button class="merkliste-remove-btn" type="submit" title="Entfernen">&#x2715;</button>
                    </form>
                    <button class="buchungsBtn car-btn"
                        data-car-id="<?php echo  $auto['iid'] ?>"
                        data-car-name="<?php echo  htmlspecialchars($auto['name']) ?>"
                        data-car-price="<?php echo  (int)$auto['preis'] ?>">Buchen</button>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="merkliste-footer">
            <div class="merkliste-summary">
                <div class="merkliste-summary-row">
                    <span class="merkliste-summary-label">Gesamtwert</span>
                    <span class="merkliste-summary-val"><?php echo  number_format($total, 0, ',', '.') ?> €</span>
                </div>
                <?php if ($rabattProzent > 0): ?>
                <div class="merkliste-summary-row">
                    <span class="merkliste-summary-label">Rabatt (<?php echo  $rabattProzent ?> %)</span>
                    <span class="merkliste-summary-val merkliste-summary-discount">−<?php echo  number_format($rabattBetrag, 0, ',', '.') ?> €</span>
                </div>
                <?php endif; ?>
                <div class="merkliste-summary-sep"></div>
                <div class="merkliste-summary-row">
                    <span class="merkliste-summary-label merkliste-summary-label--total">Endbetrag</span>
                    <span class="merkliste-summary-val merkliste-summary-val--total"><?php echo  number_format($endbetrag, 0, ',', '.') ?> €</span>
                </div>
            </div>
            <div class="merkliste-footer-btns">
                <a href="<?php echo  BASE_URL ?>/cars" class="merkliste-btn-primary">Weiter suchen</a>
                <form method="POST" action="<?php echo  BASE_URL ?>/merkliste/remove">
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

<script src="<?php echo  BASE_URL ?>/js/favLogik.js"></script>
<?php require VIEW_PATH . 'partials/footer.php'; ?>
</body>
</html>
