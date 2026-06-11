<?php

class BookingController extends Controller {
    public function index(): void {
        $this->render('bookings/index');
    }

    public function create(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false, 'message' => 'Nicht eingeloggt']);
            return;
        }

        $input     = json_decode(file_get_contents('php://input'), true) ?? [];
        $carId     = (int)($input['carId'] ?? 0);
        $carName   = trim($input['carName'] ?? '');
        $carPrice  = (float)($input['carPrice'] ?? 0);

        if (!$carId) {
            $this->json(['success' => false, 'message' => 'Ungültige Fahrzeug-ID']);
            return;
        }

        $uid = $_SESSION['user_id'];
        if ($uid > 0) {
            $db      = Database::getInstance();
            $lockRes = mysqli_query($db, "SELECT is_locked FROM users WHERE id = $uid");
            $nutzer  = mysqli_fetch_assoc($lockRes);
            if ($nutzer && $nutzer['is_locked']) {
                $this->json(['success' => false, 'message' => 'Ihr Konto ist gesperrt']);
                return;
            }
        }

        $key = Booking::create([
            'user_id'   => $uid,
            'username'  => $_SESSION['username'],
            'car_id'    => $carId,
            'car_name'  => $carName,
            'car_price' => $carPrice,
        ]);

        $this->json(['success' => true, 'id' => $key]);
    }

    public function cancel(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false, 'message' => 'Nicht eingeloggt']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $key   = $input['id'] ?? '';

        if (Booking::cancel($key, (int)$_SESSION['user_id'])) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Stornierung nicht möglich']);
        }
    }

    public function listJson(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false, 'bookings' => []]);
            return;
        }
        $this->json(['success' => true, 'bookings' => Booking::findByUser((int)$_SESSION['user_id'])]);
    }

    public function listHtml(): void {
        if (!isset($_SESSION['user_id'])) {
            echo '<p class="buchungen-empty">Nicht eingeloggt.</p>';
            return;
        }

        $uid      = (int)$_SESSION['user_id'];
        $db       = Database::getInstance();
        $res      = mysqli_query($db,
            "SELECT booking_key AS id, car_name AS carName, car_price AS carPrice,
                    status, reason, created_at AS createdAt
             FROM bookings WHERE user_id = $uid ORDER BY created_at DESC"
        );
        $buchungen = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

        $statusLabels = [
            'bestellt'       => 'Bestellt',
            'in_bearbeitung' => 'In Bearbeitung',
            'versandt'       => 'Versandt, aber nicht erhalten',
            'fertig'         => 'Fertig',
            'storniert'      => 'Storniert',
            'abgelehnt'      => 'Abgelehnt',
        ];

        if (empty($buchungen)) {
            echo '<p class="buchungen-empty">Sie haben noch keine Buchungen.<br>
                <a href="/cars" class="home-btn-primary" style="display:inline-block; margin-top:20px;">Fahrzeuge ansehen</a></p>';
            return;
        }

        foreach ($buchungen as $b) {
            $datum   = date('d.m.Y', strtotime($b['createdAt']));
            $label   = $statusLabels[$b['status']] ?? $b['status'];
            $id      = htmlspecialchars($b['id']);
            $status  = htmlspecialchars($b['status']);
            $carName = htmlspecialchars($b['carName']);
            $price   = number_format($b['carPrice'], 0, ',', '.');
            $reason  = ($b['status'] === 'abgelehnt' && $b['reason'])
                ? '<div class="buchung-reason">Ablehnungsgrund: ' . htmlspecialchars($b['reason']) . '</div>'
                : '';
            $cancelBtn = $b['status'] === 'bestellt'
                ? "<button class=\"buchung-cancel-btn\" onclick=\"handleCancelBooking('$id')\">Buchung stornieren</button>"
                : '';
            echo "
<div class=\"buchung-card\">
    <div class=\"buchung-header\">
        <div class=\"buchung-car\">$carName</div>
        <span class=\"buchung-status status-$status\">$label</span>
    </div>
    <div class=\"buchung-meta\">
        <span>Preis: <strong>$price €</strong></span>
        <span>Bestellt am: $datum</span>
    </div>
    $reason
    $cancelBtn
</div>";
        }
    }
}
