<?php

class ListingController extends Controller {
    public function sellView(): void {
        $this->render('listings/sell');
    }

    public function create(): void {
        ob_start();
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
            }
        }

        try {
            $listingKey = Listing::create($_POST, $uploadedImages, $userId, $username);
            ob_end_clean();
            $this->json(['success' => true, 'id' => $listingKey]);
        } catch (Throwable $e) {
            ob_end_clean();
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function list(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false, 'listings' => []]);
            return;
        }
        $listings = Listing::findByUser((int)$_SESSION['user_id']);
        $this->json(['success' => true, 'listings' => $listings]);
    }

    public function listHtml(): void {
        if (!isset($_SESSION['user_id'])) {
            echo '<p style="color:#888; font-size:14px;">Nicht eingeloggt.</p>';
            return;
        }

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
