<?php
// Inserate-Liste als fertiges HTML zurückgeben, damit JS es direkt einfügen kann

session_start();
require_once '../../db.php';

if (empty($_SESSION['is_admin'])) {
    echo '<p class="admin-empty">Kein Zugriff.</p>';
    exit;
}

// Alle Inserate aus der Datenbank laden, neueste zuerst
$db      = getDB();
$result  = mysqli_query($db,
    'SELECT listing_key AS id, username AS userId, contact_name AS name,
            make, model, year, price, km, fuel, type,
            cond AS `condition`, description AS `desc`,
            status, created_at AS createdAt
     FROM listings
     ORDER BY created_at DESC'
);
$inserate = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (empty($inserate)): ?>

    <p class="admin-empty">Keine eingereichten Inserate.</p>

<?php else: ?>

    <?php foreach ($inserate as $ins): ?>

        <?php
        // CSS-Klasse und Anzeigetext je nach Status bestimmen
        $statusKlasse = $ins['status'] === 'genehmigt' ? 'status-fertig'
                      : ($ins['status'] === 'abgelehnt' ? 'status-abgelehnt'
                      : 'status-in_bearbeitung');

        $statusLabels = ['eingereicht' => 'Eingereicht', 'genehmigt' => 'Genehmigt', 'abgelehnt' => 'Abgelehnt'];
        $label        = $statusLabels[$ins['status']] ?? $ins['status'];
        $datum        = date('d.m.Y', strtotime($ins['createdAt']));
        ?>

        <div class="admin-order-card">

            <div class="admin-order-header">
                <div class="admin-order-car">
                    <?= htmlspecialchars($ins['make']) ?>
                    <?= htmlspecialchars($ins['model']) ?>
                    (<?= htmlspecialchars($ins['year']) ?>)
                </div>
                <span class="buchung-status <?= $statusKlasse ?>">
                    <?= $label ?>
                </span>
            </div>

            <div class="admin-order-meta">
                <span>Von: <strong><?= htmlspecialchars($ins['name']) ?></strong></span>
                <span>Nutzer: <strong><?= htmlspecialchars($ins['userId']) ?></strong></span>
                <span>Preis: <strong><?= number_format($ins['price'], 0, ',', '.') ?> €</strong></span>
                <span>Datum: <?= $datum ?></span>
            </div>

            <div class="admin-order-meta" style="margin-top:-8px;">
                <span><?= htmlspecialchars($ins['km']) ?> km</span>
                <span><?= htmlspecialchars($ins['fuel']) ?></span>
                <span><?= htmlspecialchars($ins['type']) ?></span>
                <span><?= htmlspecialchars($ins['condition']) ?></span>
            </div>

            <?php if ($ins['desc']): ?>
                <div style="font-size:13px; color:#bdbdbd; margin-bottom:8px;">
                    <?= htmlspecialchars($ins['desc']) ?>
                </div>
            <?php endif; ?>

            <?php if ($ins['status'] === 'eingereicht'): ?>
                <div class="admin-order-actions">
                    <button onclick="adminSetInseratStatus('<?= $ins['id'] ?>', 'genehmigt')">Genehmigen</button>
                    <button class="btn-reject" onclick="adminSetInseratStatus('<?= $ins['id'] ?>', 'abgelehnt')">Ablehnen</button>
                </div>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>
