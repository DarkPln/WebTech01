<?php
// Tim

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



// Lukas 

    public function listHtml(): void {
        if (!isset($_SESSION['user_id'])) {
            echo '<p class="buchungen-empty">Nicht eingeloggt.</p>';
            return;
        }

        $uid      = (int)$_SESSION['user_id'];
        $db       = Database::getInstance();
        $res      = mysqli_query($db,
            "SELECT b.booking_key AS id, b.car_name AS carName, b.car_price AS carPrice,
                    b.status, b.reason, b.created_at AS createdAt,
                    c.imagepath
             FROM bookings b
             LEFT JOIN cars c ON c.id = b.car_id
             WHERE b.user_id = $uid
             ORDER BY b.created_at DESC"
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

        foreach ($buchungen as $buchung) {
            $datum    = date('d.m.Y', strtotime($buchung['createdAt']));
            $label    = $statusLabels[$buchung['status']] ?? $buchung['status'];
            $id       = htmlspecialchars($buchung['id']);
            $status   = htmlspecialchars($buchung['status']);
            $carName  = htmlspecialchars($buchung['carName']);
            $price    = number_format($buchung['carPrice'], 0, ',', '.');
            ?>
<div class="buchung-card">
    <div class="buchung-header">
        <div class="buchung-car"><?= $carName ?></div>
        <span class="buchung-status status-<?= $status ?>"><?= $label ?></span>
    </div>
    <div class="buchung-meta">
        <span>Preis: <strong><?= $price ?> €</strong></span>
        <span>Bestellt am: <?= $datum ?></span>
    </div>
    <?php if ($buchung['status'] === 'abgelehnt' && $buchung['reason']): ?>
    <div class="buchung-reason">Ablehnungsgrund: <?= htmlspecialchars($buchung['reason']) ?></div>
    <?php endif; ?>
    <?php if ($buchung['status'] === 'bestellt'): ?>
    <div class="buchung-actions">
        <button class="buchung-cancel-btn" onclick="handleCancelBooking('<?= $id ?>')">Buchung stornieren</button>
    </div>
    <?php endif; ?>
</div>
            <?php
        }
    }
}
