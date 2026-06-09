<?php
// Nutzerliste als fertiges HTML zurückgeben

session_start();
require_once '../../db.php';

if (empty($_SESSION['is_admin'])) {
    echo '<p class="admin-empty">Kein Zugriff.</p>';
    exit;
}

$result = getDB()->query('SELECT username, is_locked AS locked FROM users ORDER BY created_at ASC');
$nutzer = $result->fetch_all(MYSQLI_ASSOC);

if (empty($nutzer)): ?>

    <p class="admin-empty">Keine registrierten Nutzer.</p>

<?php else: ?>

    <?php foreach ($nutzer as $eintrag): ?>

        <?php
        $name     = htmlspecialchars($eintrag['username']);
        $gesperrt = (bool)$eintrag['locked'];
        ?>

        <div class="admin-user-row">
            <span class="admin-user-name"><?= $name ?></span>
            <span class="admin-user-status <?= $gesperrt ? 'user-locked' : 'user-active' ?>">
                <?= $gesperrt ? 'Gesperrt' : 'Aktiv' ?>
            </span>
            <button class="<?= $gesperrt ? 'btn-unlock' : 'btn-lock' ?>"
                    onclick="adminToggleLock('<?= $name ?>')">
                <?= $gesperrt ? 'Entsperren' : 'Sperren' ?>
            </button>
        </div>

    <?php endforeach; ?>

<?php endif; ?>
