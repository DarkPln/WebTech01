<?php
ob_start(); // Schluckt unbeabsichtigte Ausgaben (PHP-Warnings etc.) damit immer valides JSON zurückkommt
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

try {
    $userId   = $_SESSION['user_id'] ?? null;
    $username = $_SESSION['username'] ?? 'Gast';

    $key   = 'i_' . time() . '_' . bin2hex(random_bytes(3));
    $price = (float)($_POST['price'] ?? 0);

    $db = getDB();

    $uid       = $userId === null ? 'NULL' : (int)$userId;
    $eKey      = mysqli_real_escape_string($db, $key);
    $eUsername = mysqli_real_escape_string($db, $username);
    $eMake     = mysqli_real_escape_string($db, $_POST['make']      ?? '');
    $eModel    = mysqli_real_escape_string($db, $_POST['model']     ?? '');
    $eYear     = mysqli_real_escape_string($db, $_POST['year']      ?? '');
    $eKm       = mysqli_real_escape_string($db, $_POST['km']        ?? '');
    $eFuel     = mysqli_real_escape_string($db, $_POST['fuel']      ?? '');
    $eGearbox  = mysqli_real_escape_string($db, $_POST['gearbox']   ?? '');
    $ePower    = mysqli_real_escape_string($db, $_POST['power']     ?? '');
    $eAntrieb  = mysqli_real_escape_string($db, $_POST['antrieb']   ?? '');
    $eType     = mysqli_real_escape_string($db, $_POST['type']      ?? '');
    $eCond     = mysqli_real_escape_string($db, $_POST['condition'] ?? '');
    $eDesc     = mysqli_real_escape_string($db, $_POST['desc']      ?? '');
    $eName     = mysqli_real_escape_string($db, $_POST['name']      ?? '');
    $eEmail    = mysqli_real_escape_string($db, $_POST['email']     ?? '');
    $ePhone    = mysqli_real_escape_string($db, $_POST['phone']     ?? '');

    // Handle image upload (max. 1 Bild)
    $uploadedImages = [];
    if (isset($_FILES['images']) && !empty($_FILES['images']['tmp_name'][0])) {
        $uploadDir = __DIR__ . '/../../uploads/listings/' . $key . '/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            throw new RuntimeException('Upload-Verzeichnis konnte nicht erstellt werden.');
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;

        foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
            if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
            if (!in_array($_FILES['images']['type'][$i], $allowed, true)) continue;
            if ($_FILES['images']['size'][$i] > $maxSize) continue;

            $origExt  = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
            $safeExt  = in_array($origExt, ['jpg', 'jpeg', 'png', 'webp'], true) ? $origExt : 'jpg';
            $filename = $i . '_' . bin2hex(random_bytes(4)) . '.' . $safeExt;

            if (move_uploaded_file($tmpName, $uploadDir . $filename)) {
                $uploadedImages[] = 'uploads/listings/' . $key . '/' . $filename;
            }
            break; // nur 1 Bild
        }
    }

    $eImages = mysqli_real_escape_string($db, json_encode($uploadedImages));

    $ok = mysqli_query($db,
        "INSERT INTO listings
            (listing_key, user_id, username, make, model, year, km, fuel, gearbox, power, antrieb, type, cond, price, description, contact_name, email, phone, images)
         VALUES ('$eKey', $uid, '$eUsername', '$eMake', '$eModel', '$eYear', '$eKm', '$eFuel', '$eGearbox', '$ePower', '$eAntrieb', '$eType', '$eCond', $price, '$eDesc', '$eName', '$eEmail', '$ePhone', '$eImages')"
    );

    if (!$ok) {
        throw new RuntimeException('Datenbankfehler: ' . mysqli_error($db));
    }

    ob_end_clean();
    echo json_encode(['success' => true, 'id' => $key]);

} catch (Throwable $e) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
