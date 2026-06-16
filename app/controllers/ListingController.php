<?php
//Niclas: Controller für die Inserate, mit Logik für Inseratsseiten (Erstellen, Auflisten der eigenen Inserate)
class ListingController extends Controller {
    //Rendert die Seite zum Erstellen eines neuen Inserats (keine Logik):
    public function sellView(): void {
        $this->render('listings/sell');
    }

    //prüft hochgeladende Bilder; erstellt neues Inserat in DB
    public function create(): void {
        $userId   = $_SESSION['user_id'] ?? null;
        $username = $_SESSION['username'] ?? 'Gast';

        $uploadedImages = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['tmp_name'][0])) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 5 * 1024 * 1024;

            foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
                if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
                $mime = mime_content_type($tmpName);
                if (!in_array($mime, $allowed, true)) continue;
                if ($_FILES['images']['size'][$i] > $maxSize) continue;

                $data = file_get_contents($tmpName);
                if ($data === false) continue;

                $uploadedImages[] = 'data:' . $mime . ';base64,' . base64_encode($data);
                break;
                //nur das erste gültige Bild wird verarbeitet
            }
        }

        try {
            $listingKey = Listing::create($_POST, $uploadedImages, $userId, $username);
            $this->json(['success' => true, 'id' => $listingKey]);
            //erfogreiche Erstellung Inserat: JSON-Antwort: true
        } catch (Throwable $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
        //bei Fehler
    }

    //gibt alle Inserate des Nutzers zurück;
    //Wird per AJAX aufgerufen
    public function list(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false, 'listings' => []]);
            return;
        }
        $listings = Listing::findByUser((int)$_SESSION['user_id']);
        $this->json(['success' => true, 'listings' => $listings]);
    }

    //gibt HTML für die Auflistung der eigenen Inserate zurück (Status, Datum, Auto-Details);
    public function listHtml(): void {
        //kein Rendern; gibt direkt HTML zurück, das per AJAX in die Seite eingefügt wird
        if (!isset($_SESSION['user_id'])) {
            echo '<p style="color:#888; font-size:14px;">Nicht eingeloggt.</p>';
            return;
        }
        //direktes echoen von HTML (bei AJAX-Anfragen)

        $uid      = $_SESSION['user_id'];
        $username = $_SESSION['username'] ?? '';
        $db       = Database::getInstance();
        $res      = mysqli_query($db,
            "SELECT listing_key AS id, make, model, year, price, status, created_at AS createdAt
             FROM listings
             WHERE user_id = $uid OR username = '$username'
             ORDER BY created_at DESC"
        );
        $inserate = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

        $statusLabels = ['eingereicht' => 'Eingereicht', 'genehmigt' => 'Genehmigt', 'abgelehnt' => 'Abgelehnt'];

        if (empty($inserate)) {
            echo '<p style="color:#888; font-size:14px;">Sie haben noch keine Inserate eingereicht.</p>';
            return;
        }

        foreach ($inserate as $ins) {
            $datum        = date('d.m.Y', strtotime($ins['createdAt']));
            $label        = $statusLabels[$ins['status']] ?? $ins['status'];
            $statusKlasse = $ins['status'] === 'genehmigt' ? 'status-fertig'
                          : ($ins['status'] === 'abgelehnt' ? 'status-abgelehnt' : 'status-in_bearbeitung');
            $make  = htmlspecialchars($ins['make']);
            $model = htmlspecialchars($ins['model']);
            $year  = htmlspecialchars($ins['year']);
            $price = number_format($ins['price'], 0, ',', '.');
            $reason = $ins['status'] === 'abgelehnt' ? '<div class="buchung-reason">Vom Administrator abgelehnt</div>' : '';
            echo "
<div class=\"buchung-card\">
    <div class=\"buchung-header\">
        <span class=\"buchung-car\">$make $model ($year)</span>
        <span class=\"buchung-status $statusKlasse\">$label</span>
    </div>
    <div class=\"buchung-meta\">Preis: $price € | Eingereicht: $datum</div>
    $reason
</div>";
        }
    }
}
//MVC: Model View Controller, Controller enthält keine SQL-Queries und kein HTML
//Controller muss für jede Funktion eine Seite rendern
