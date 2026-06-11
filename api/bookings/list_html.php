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
    "SELECT b.booking_key AS id, b.car_name AS carName, b.car_price AS carPrice,
            b.status, b.reason, b.created_at AS createdAt,
            c.imagepath
     FROM bookings b
     LEFT JOIN cars c ON c.id = b.car_id
     WHERE b.user_id = $uid
     ORDER BY b.created_at DESC"
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

            <div class="buchung-actions">
                <?php if ($buchung['status'] === 'bestellt'): ?>
                    <button class="buchung-cancel-btn"
                            onclick="handleCancelBooking('<?= $buchung['id'] ?>')">
                        Buchung stornieren
                    </button>
                <?php endif; ?>
                <?php
                $isStorno = in_array($buchung['status'], ['storniert', 'abgelehnt']) ? '1' : '0';
                $imgAttr  = htmlspecialchars($buchung['imagepath'] ?? '', ENT_QUOTES);
                ?>
                <button class="msg-download-btn"
                        data-car="<?= htmlspecialchars($buchung['carName'], ENT_QUOTES) ?>"
                        data-price="<?= number_format($buchung['carPrice'], 0, ',', '.') ?>"
                        data-status="<?= htmlspecialchars($label, ENT_QUOTES) ?>"
                        data-date="<?= $datum ?>"
                        data-reason="<?= htmlspecialchars($buchung['reason'] ?? '', ENT_QUOTES) ?>"
                        data-img="<?= $imgAttr ?>"
                        data-storno="<?= $isStorno ?>"
                        onclick="downloadBookingPDF(this)"
                        title="Als PDF herunterladen">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    PDF
                </button>
            </div>

        </div>

    <?php endforeach; ?>

<?php endif; ?>
