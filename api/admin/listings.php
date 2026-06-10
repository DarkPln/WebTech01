<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (empty($_SESSION['is_admin'])) {
    echo json_encode(['success' => false, 'message' => 'Kein Zugriff']);
    exit;
}

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = mysqli_query($db,
        'SELECT listing_key AS id, username AS userId, contact_name AS name,
                make, model, year, price, km, fuel, type, cond AS `condition`,
                description AS `desc`, status, created_at AS createdAt
         FROM listings
         ORDER BY created_at DESC'
    );
    echo json_encode(['success' => true, 'listings' => mysqli_fetch_all($result, MYSQLI_ASSOC)]);
} else {
    $input  = json_decode(file_get_contents('php://input'), true) ?? [];
    $key    = $input['id']     ?? '';
    $status = $input['status'] ?? '';

    $eStatus = mysqli_real_escape_string($db, $status);
    $eKey    = mysqli_real_escape_string($db, $key);

    // Listing vorab laden (für Status-Vergleich, Car-Insert und Nachricht)
    $lstRes  = mysqli_query($db, "SELECT * FROM listings WHERE listing_key = '$eKey'");
    $listing = mysqli_fetch_assoc($lstRes);

    if (!$listing) {
        echo json_encode(['success' => false, 'message' => 'Inserat nicht gefunden']);
        exit;
    }

    $oldStatus = $listing['status'];

    // Genehmigtes Inserat in die Fahrzeugliste übernehmen
    if ($status === 'genehmigt' && $oldStatus !== 'genehmigt') {
        $nxtRes  = mysqli_query($db, "SELECT COALESCE(MAX(iid), 100) + 1 AS next FROM cars");
        $nextIid = (int)mysqli_fetch_assoc($nxtRes)['next'];

        $kraftMap = ['benzin' => 'Benzin', 'diesel' => 'Diesel', 'elektro' => 'Elektro', 'hybrid' => 'Hybrid', 'lpg' => 'LPG'];

        $eName   = mysqli_real_escape_string($db, trim($listing['make'] . ' ' . $listing['model']));
        $eMarke  = mysqli_real_escape_string($db, $listing['make']);
        $eModell = mysqli_real_escape_string($db, $listing['model']);
        $eBeschr = mysqli_real_escape_string($db, $listing['description'] ?? '');
        $eUnkat    = mysqli_real_escape_string($db, strtolower($listing['type'] ?? ''));
        $eKraft    = mysqli_real_escape_string($db, $kraftMap[strtolower($listing['fuel'] ?? '')] ?? ucfirst($listing['fuel'] ?? ''));
        $eAntrieb  = mysqli_real_escape_string($db, $listing['antrieb'] ?? '');
        $baujahr = (int)$listing['year'];
        $km      = (int)$listing['km'];
        $preis   = (float)$listing['price'];
        $ps      = (int)$listing['power'];

        // Uploaded image path; fall back to placeholder if none was provided
        $imagesArr = json_decode($listing['images'] ?? '[]', true);
        $imagepath = !empty($imagesArr[0])
            ? $imagesArr[0]
            : 'https://placehold.co/800x500/1a1a1a/cccccc?text=Kein+Bild';
        $eImagepath = mysqli_real_escape_string($db, $imagepath);

        mysqli_query($db,
            "INSERT INTO cars (iid, name, beschreibung, imagepath, preis, kategorie, unterkategorie, marke, modell, baujahr, kraftstoff, kilometerstand, leistung_ps, antrieb)
             VALUES ($nextIid, '$eName', '$eBeschr', '$eImagepath',
                     $preis, 'gebrauchtwagen', '$eUnkat', '$eMarke', '$eModell', $baujahr, '$eKraft', $km, $ps, '$eAntrieb')"
        );
    }

    mysqli_query($db, "UPDATE listings SET status = '$eStatus' WHERE listing_key = '$eKey'");

    // Nachricht an den User schicken (nur bei echtem Statuswechsel und bekanntem User)
    $userId = (int)($listing['user_id'] ?? 0);
    if ($userId > 0 && $oldStatus !== $status && in_array($status, ['genehmigt', 'abgelehnt'])) {
        $carLabel = $listing['make'] . ' ' . $listing['model'];

        if ($status === 'genehmigt') {
            $msgTitle = 'Inserat genehmigt';
            $msgBody  = 'Ihr Inserat „' . $carLabel . '" wurde genehmigt und ist ab sofort online.';
        } else {
            $msgTitle = 'Inserat abgelehnt';
            $msgBody  = 'Ihr Inserat „' . $carLabel . '" wurde abgelehnt und wird nicht veröffentlicht.';
        }

        $eTitle = mysqli_real_escape_string($db, $msgTitle);
        $eBody  = mysqli_real_escape_string($db, $msgBody);
        mysqli_query($db, "INSERT INTO messages (user_id, title, body, is_read) VALUES ($userId, '$eTitle', '$eBody', 0)");
    }

    echo json_encode(['success' => true]);
}
