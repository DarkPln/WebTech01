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
    $result = $db->query(
        'SELECT listing_key AS id, username AS userId, contact_name AS name,
                make, model, year, price, km, fuel, type, cond AS `condition`,
                description AS `desc`, status, created_at AS createdAt
         FROM listings
         ORDER BY created_at DESC'
    );
    echo json_encode(['success' => true, 'listings' => $result->fetch_all(MYSQLI_ASSOC)]);
} else {
    $input  = json_decode(file_get_contents('php://input'), true) ?? [];
    $key    = $input['id']     ?? '';
    $status = $input['status'] ?? '';

    $eStatus = $db->real_escape_string($status);
    $eKey    = $db->real_escape_string($key);

    // Genehmigtes Inserat in die Fahrzeugliste übernehmen
    if ($status === 'genehmigt') {
        $current = $db->query("SELECT status FROM listings WHERE listing_key = '$eKey'")->fetch_assoc();

        if ($current && $current['status'] !== 'genehmigt') {
            $listing = $db->query("SELECT * FROM listings WHERE listing_key = '$eKey'")->fetch_assoc();

            if ($listing) {
                $nextIid = (int)$db->query("SELECT COALESCE(MAX(iid), 100) + 1 AS next FROM cars")->fetch_assoc()['next'];

                $kraftMap = ['benzin' => 'Benzin', 'diesel' => 'Diesel', 'elektro' => 'Elektro', 'hybrid' => 'Hybrid', 'lpg' => 'LPG'];

                $eName   = $db->real_escape_string(trim($listing['make'] . ' ' . $listing['model']));
                $eMarke  = $db->real_escape_string($listing['make']);
                $eModell = $db->real_escape_string($listing['model']);
                $eBeschr = $db->real_escape_string($listing['description'] ?? '');
                $eUnkat    = $db->real_escape_string(strtolower($listing['type'] ?? ''));
                $eKraft    = $db->real_escape_string($kraftMap[strtolower($listing['fuel'] ?? '')] ?? ucfirst($listing['fuel'] ?? ''));
                $eAntrieb  = $db->real_escape_string($listing['antrieb'] ?? '');
                $baujahr = (int)$listing['year'];
                $km      = (int)$listing['km'];
                $preis   = (float)$listing['price'];
                $ps      = (int)$listing['power'];

                $db->query(
                    "INSERT INTO cars (iid, name, beschreibung, imagepath, preis, kategorie, unterkategorie, marke, modell, baujahr, kraftstoff, kilometerstand, leistung_ps, antrieb)
                     VALUES ($nextIid, '$eName', '$eBeschr', 'https://placehold.co/800x500/1a1a1a/cccccc?text=Kein+Bild',
                             $preis, 'gebrauchtwagen', '$eUnkat', '$eMarke', '$eModell', $baujahr, '$eKraft', $km, $ps, '$eAntrieb')"
                );
            }
        }
    }

    $db->query("UPDATE listings SET status = '$eStatus' WHERE listing_key = '$eKey'");

    echo json_encode(['success' => true]);
}
