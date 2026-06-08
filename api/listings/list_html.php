<?php
// Eigene Inserate des eingeloggten Nutzers als fertiges HTML zurückgeben

session_start();
require_once '../../db.php';

if (!isset($_SESSION['user_id'])) {
    echo '<p style="color:#888; font-size:14px;">Nicht eingeloggt.</p>';
    exit;
}

$uid     = $_SESSION['user_id'];
$uname   = $_SESSION['username'];
$abfrage = getDB()->prepare(
    'SELECT listing_key AS id, make, model, year, price, status, created_at AS createdAt
     FROM listings
     WHERE user_id = ? OR username = ?
     ORDER BY created_at DESC'
);
$abfrage->bind_param('is', $uid, $uname);
$abfrage->execute();
$inserate = $abfrage->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($inserate)): ?>

    <p style="color:#888; font-size:14px;">Sie haben noch keine Inserate eingereicht.</p>

<?php else: ?>

    <?php
    $statusLabels = ['eingereicht' => 'Eingereicht', 'genehmigt' => 'Genehmigt', 'abgelehnt' => 'Abgelehnt'];
    ?>

    <?php foreach ($inserate as $ins): ?>

        <?php
        $datum       = date('d.m.Y', strtotime($ins['createdAt']));
        $label       = $statusLabels[$ins['status']] ?? $ins['status'];
        $statusKlasse = $ins['status'] === 'genehmigt' ? 'status-fertig'
                      : ($ins['status'] === 'abgelehnt' ? 'status-abgelehnt'
                      : 'status-in_bearbeitung');
        ?>

        <div class="buchung-card">
            <div class="buchung-header">
                <span class="buchung-car">
                    <?= htmlspecialchars($ins['make']) ?>
                    <?= htmlspecialchars($ins['model']) ?>
                    (<?= htmlspecialchars($ins['year']) ?>)
                </span>
                <span class="buchung-status <?= $statusKlasse ?>"><?= $label ?></span>
            </div>
            <div class="buchung-meta">
                Preis: <?= number_format($ins['price'], 0, ',', '.') ?> € &nbsp;|&nbsp; Eingereicht: <?= $datum ?>
            </div>
            <?php if ($ins['status'] === 'abgelehnt'): ?>
                <div class="buchung-reason">Vom Administrator abgelehnt</div>
            <?php endif; ?>
        </div>

    <?php endforeach; ?>

<?php endif; ?>
