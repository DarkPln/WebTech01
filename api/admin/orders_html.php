<?php
// Admin-Auftragsliste als fertiges HTML zurückgeben
// Der Parameter ?bereich=new|processing|rejected|completed bestimmt welche Aufträge gezeigt werden

session_start();
require_once '../../db.php';

if (empty($_SESSION['is_admin'])) {
    echo '<p class="admin-empty">Kein Zugriff.</p>';
    exit;
}

// Je nach Bereich andere Status-Filter verwenden
$bereich = $_GET['bereich'] ?? 'new';

$filterMap = [
    'new'        => "status = 'bestellt'",
    'processing' => "status IN ('in_bearbeitung', 'versandt')",
    'rejected'   => "status IN ('abgelehnt', 'storniert')",
    'completed'  => "status = 'fertig'",
];

// Unbekannte Bereiche ablehnen (Sicherheit gegen unerwartete SQL-Werte)
if (!isset($filterMap[$bereich])) {
    echo '<p class="admin-empty">Unbekannter Bereich.</p>';
    exit;
}

$result    = getDB()->query(
    'SELECT booking_key AS id, username AS userId, car_name AS carName,
            car_price AS carPrice, status, reason, created_at AS createdAt
     FROM bookings
     WHERE ' . $filterMap[$bereich] . '
     ORDER BY created_at DESC'
);
$buchungen = $result->fetch_all(MYSQLI_ASSOC);

if (empty($buchungen)): ?>

    <p class="admin-empty">Keine Aufträge.</p>

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
        $datum  = date('d.m.Y', strtotime($buchung['createdAt']));
        $label  = $statusLabels[$buchung['status']] ?? $buchung['status'];
        $id     = htmlspecialchars($buchung['id']);
        $status = htmlspecialchars($buchung['status']);
        ?>

        <div class="admin-order-card">

            <div class="admin-order-header">
                <div class="admin-order-car"><?= htmlspecialchars($buchung['carName']) ?></div>
                <span class="buchung-status status-<?= $status ?>"><?= $label ?></span>
            </div>

            <div class="admin-order-meta">
                <span>Nutzer: <strong><?= htmlspecialchars($buchung['userId']) ?></strong></span>
                <span>Preis: <strong><?= number_format($buchung['carPrice'], 0, ',', '.') ?> €</strong></span>
                <span>Datum: <?= $datum ?></span>
            </div>

            <?php if ($buchung['reason']): ?>
                <div class="buchung-reason">Grund: <?= htmlspecialchars($buchung['reason']) ?></div>
            <?php endif; ?>

            <?php if ($buchung['status'] === 'bestellt'): ?>
                <div class="admin-order-actions">
                    <button onclick="adminSetStatus('<?= $id ?>', 'in_bearbeitung')">In Bearbeitung</button>
                    <button class="btn-reject" onclick="adminRejectOrder('<?= $id ?>')">Ablehnen</button>
                </div>
            <?php elseif ($buchung['status'] === 'in_bearbeitung'): ?>
                <div class="admin-order-actions">
                    <button onclick="adminSetStatus('<?= $id ?>', 'versandt')">Als versandt markieren</button>
                    <button onclick="adminSetStatus('<?= $id ?>', 'fertig')">Fertigstellen</button>
                    <button class="btn-reject" onclick="adminRejectOrder('<?= $id ?>')">Ablehnen</button>
                </div>
            <?php elseif ($buchung['status'] === 'versandt'): ?>
                <div class="admin-order-actions">
                    <button onclick="adminSetStatus('<?= $id ?>', 'fertig')">Als erhalten markieren</button>
                </div>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>
