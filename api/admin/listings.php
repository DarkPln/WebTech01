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

    // Genehmigtes Inserat in die Fahrzeugliste übernehmen
    if ($status === 'genehmigt') {
        $curRes  = mysqli_query($db, "SELECT status FROM listings WHERE listing_key = '$eKey'");
        $current = mysqli_fetch_assoc($curRes);

        if ($current && $current['status'] !== 'genehmigt') {
            $lstRes  = mysqli_query($db, "SELECT * FROM listings WHERE listing_key = '$eKey'");
            $listing = mysqli_fetch_assoc($lstRes);

            if ($listing) {
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
        }
    }

    mysqli_query($db, "UPDATE listings SET status = '$eStatus' WHERE listing_key = '$eKey'");

    echo json_encode(['success' => true]);
}
