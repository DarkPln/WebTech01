<?php
// Tim

// Verwaltet Buchungen: erstellen, stornieren und anzeigen
class BookingController extends Controller {

    // Buchungsübersicht des Nutzers anzeigen
    public function index(): void {
        $this->render('bookings/list');
    }

    // Neue Buchung erstellen
    public function create(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false, 'message' => 'Nicht eingeloggt']);
            return;
        }

        // Roher JSON-Body lesen, da JS application/json schickt statt $_POST
        $input    = json_decode(file_get_contents('php://input'), true) ?? [];
        $carId    = (int)($input['carId'] ?? 0);
        $carName  = trim($input['carName'] ?? '');
        $carPrice = (float)($input['carPrice'] ?? 0);

        if (!$carId) {
            $this->json(['success' => false, 'message' => 'Ungültige Fahrzeug-ID']);
            return;
        }

        // Gesperrte Konten dürfen nicht buchen (hardcoded Accounts user_id 0/-1 überspringen)
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

        // Buchung in DB anlegen und generierten Key zurückgeben
        $key = Booking::create([
            'user_id'   => $uid,
            'username'  => $_SESSION['username'],
            'car_id'    => $carId,
            'car_name'  => $carName,
            'car_price' => $carPrice,
        ]);

        $this->json(['success' => true, 'id' => $key]);
    }

    // Eigene Buchung stornieren (nur der Buchungsbesitzer darf stornieren)
    public function cancel(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false, 'message' => 'Nicht eingeloggt']);
            return;
        }

        // Roher JSON-Body lesen, da JS application/json schickt statt $_POST
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $key   = $input['id'] ?? '';

        // user_id wird mitgegeben damit Nutzer nur eigene Buchungen stornieren können
        if (Booking::cancel($key, (int)$_SESSION['user_id'])) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Stornierung nicht möglich']);
        }
    }

    // Alle Buchungen des eingeloggten Nutzers als JSON zurückgeben
    public function listJson(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false, 'bookings' => []]);
            return;
        }
        $this->json(['success' => true, 'bookings' => Booking::findByUser((int)$_SESSION['user_id'])]);
    }


// Lukas

    // HTML-Fragment mit allen Buchungen des Nutzers (JS setzt es per innerHTML ein)
    public function listHtml(): void {
        if (!isset($_SESSION['user_id'])) {
            echo '<p class="buchungen-empty">Nicht eingeloggt.</p>';
            return;
        }

        $uid = (int)$_SESSION['user_id'];
        $db  = Database::getInstance();
        // LEFT JOIN auf cars um das Vorschaubild zu laden (kann NULL sein wenn Auto gelöscht wurde)
        $res = mysqli_query($db,
            "SELECT b.booking_key AS id, b.car_name AS carName, b.car_price AS carPrice,
                    b.status, b.reason, b.created_at AS createdAt,
                    c.imagepath
             FROM bookings b
             LEFT JOIN cars c ON c.id = b.car_id
             WHERE b.user_id = $uid
             ORDER BY b.created_at DESC"
        );
        $buchungen = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

        // Status-DB-Werte auf lesbare Labels mappen
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
            $datum   = date('d.m.Y', strtotime($buchung['createdAt']));
            $label   = $statusLabels[$buchung['status']] ?? $buchung['status'];
            $id      = htmlspecialchars($buchung['id']);
            $status  = htmlspecialchars($buchung['status']);
            $carName = htmlspecialchars($buchung['carName']);
            $price   = number_format($buchung['carPrice'], 0, ',', '.');
            ?>
<div class="buchung-card">
    <div class="buchung-header">
        <div class="buchung-car"><?php echo  $carName ?></div>
        <span class="buchung-status status-<?php echo  $status ?>"><?php echo  $label ?></span>
    </div>
    <div class="buchung-meta">
        <span>Preis: <strong><?php echo  $price ?> €</strong></span>
        <span>Bestellt am: <?php echo  $datum ?></span>
    </div>
    <?php if ($buchung['status'] === 'abgelehnt' && $buchung['reason']): ?>
    <div class="buchung-reason">Ablehnungsgrund: <?php echo  htmlspecialchars($buchung['reason']) ?></div>
    <?php endif; ?>
    <?php if ($buchung['status'] === 'bestellt'): // Stornieren nur bei noch offenen Buchungen ?>
    <div class="buchung-actions">
        <button class="buchung-cancel-btn" onclick="handleCancelBooking('<?php echo  $id ?>')">Buchung stornieren</button>
    </div>
    <?php endif; ?>
</div>
            <?php
        }
    }
}
