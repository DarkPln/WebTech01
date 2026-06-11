<?php
session_start();
require_once '../../db.php';

if (empty($_SESSION['is_admin'])) {
    echo '<p class="admin-empty">Kein Zugriff.</p>';
    exit;
}

$db     = getDB();
$result = mysqli_query($db,
    'SELECT iid, name, marke, modell, baujahr, preis, kilometerstand,
            kraftstoff, unterkategorie, leistung_ps, imagepath
     FROM cars
     ORDER BY iid DESC'
);
$cars = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (empty($cars)): ?>

    <p class="admin-empty">Keine Fahrzeuge vorhanden.</p>

<?php else: ?>

    <?php foreach ($cars as $car): ?>
        <div class="admin-order-card">

            <div class="admin-order-header">
                <div class="admin-order-car">
                    <?= htmlspecialchars($car['marke']) ?>
                    <?= htmlspecialchars($car['modell']) ?>
                    (<?= htmlspecialchars($car['baujahr']) ?>)
                </div>
                <span style="font-size:12px; color:#888;">ID: <?= (int)$car['iid'] ?></span>
            </div>

            <div class="admin-order-meta">
                <span>Preis: <strong><?= number_format($car['preis'], 0, ',', '.') ?> €</strong></span>
                <span><?= number_format($car['kilometerstand'], 0, ',', '.') ?> km</span>
                <span><?= htmlspecialchars($car['kraftstoff']) ?></span>
                <span><?= htmlspecialchars(ucfirst($car['unterkategorie'])) ?></span>
                <?php if ($car['leistung_ps']): ?>
                    <span><?= (int)$car['leistung_ps'] ?> PS</span>
                <?php endif; ?>
            </div>

            <div class="admin-order-actions">
                <button class="btn-reject" onclick="adminDeleteCar(<?= (int)$car['iid'] ?>, this)">Löschen</button>
            </div>

        </div>
    <?php endforeach; ?>

<?php endif; ?>
