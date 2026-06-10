<?php
// Buchungsliste als fertiges HTML zurückgeben

session_start();
require_once '../../db.php';

if (!isset($_SESSION['user_id'])) {
    echo '<p class="buchungen-empty">Nicht eingeloggt.</p>';
    exit;
}

// Buchungen des eingeloggten Nutzers laden, neueste zuerst
$uid       = $_SESSION['user_id'];
$db        = getDB();
$res       = mysqli_query($db,
    "SELECT booking_key AS id, car_name AS carName, car_price AS carPrice,
            status, reason, created_at AS createdAt
     FROM bookings
     WHERE user_id = $uid
     ORDER BY created_at DESC"
);
$buchungen = mysqli_fetch_all($res, MYSQLI_ASSOC);

if (empty($buchungen)): ?>

    <p class="buchungen-empty">
        Sie haben noch keine Buchungen.<br>
        <a href="gebrauchtwagenList.php" class="home-btn-primary" style="display:inline-block; margin-top:20px;">
            Fahrzeuge ansehen
        </a>
    </p>

<?php else: ?>

    <?php
    $statusLabels = [
        'bestellt'       => 'Bestellt',
        'in_bearbeitung' => 'In Bearbeitung',
        'versandt'       => 'Versandt, aber nicht erhalten',
        'fertig'         => 'Fertig',
        'storniert'      => 'Storniert',
        'abgelehnt'      => 'Abgelehnt',
    ];
    ?>

    <?php foreach ($buchungen as $buchung): ?>

        <?php
        $datum = date('d.m.Y', strtotime($buchung['createdAt']));
        $label = $statusLabels[$buchung['status']] ?? $buchung['status'];
        ?>

        <div class="buchung-card">

            <div class="buchung-header">
                <div class="buchung-car"><?= htmlspecialchars($buchung['carName']) ?></div>
                <span class="buchung-status status-<?= htmlspecialchars($buchung['status']) ?>">
                    <?= $label ?>
                </span>
            </div>

            <div class="buchung-meta">
                <span>Preis: <strong><?= number_format($buchung['carPrice'], 0, ',', '.') ?> €</strong></span>
                <span>Bestellt am: <?= $datum ?></span>
            </div>

            <?php if ($buchung['status'] === 'abgelehnt' && $buchung['reason']): ?>
                <div class="buchung-reason">
                    Ablehnungsgrund: <?= htmlspecialchars($buchung['reason']) ?>
                </div>
            <?php endif; ?>

            <?php if ($buchung['status'] === 'bestellt'): ?>
                <button class="buchung-cancel-btn"
                        onclick="handleCancelBooking('<?= $buchung['id'] ?>')">
                    Buchung stornieren
                </button>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>
