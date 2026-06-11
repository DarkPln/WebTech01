<?php

class FavoriteController extends Controller {
    public function toggle(): void {
        $input  = json_decode(file_get_contents('php://input'), true) ?? [];
        $action = $input['action'] ?? '';
        $carId  = isset($input['carId']) ? (int)$input['carId'] : null;

        $userId = $_SESSION['user_id'] ?? null;
        $useDB  = ($userId !== null && $userId > 0);

        if ($useDB) {
            $db = Database::getInstance();

            if ($action === 'clear') {
                mysqli_query($db, "DELETE FROM favorites WHERE user_id = $userId");
                $this->json(['success' => true, 'status' => 'cleared', 'favorites' => []]);
                return;
            }

            if ($carId === null) {
                $this->json(['success' => false, 'message' => 'Keine ID']);
                return;
            }

            $status = Favorite::toggle((int)$userId, $carId);
            $favs   = Favorite::findByUser((int)$userId);
            $this->json(['success' => true, 'status' => $status, 'favorites' => $favs]);
        } else {
            if (!isset($_SESSION['favorites'])) $_SESSION['favorites'] = [];

            if ($action === 'clear') {
                $_SESSION['favorites'] = [];
                $this->json(['success' => true, 'status' => 'cleared', 'favorites' => []]);
                return;
            }

            if ($carId === null) {
                $this->json(['success' => false, 'message' => 'Keine ID']);
                return;
            }

            if (in_array($carId, $_SESSION['favorites'], true)) {
                $_SESSION['favorites'] = array_values(array_filter($_SESSION['favorites'], fn($id) => $id !== $carId));
                $status = 'removed';
            } else {
                $_SESSION['favorites'][] = $carId;
                $status = 'added';
            }

            $this->json(['success' => true, 'status' => $status, 'favorites' => $_SESSION['favorites']]);
        }
    }

    public function get(): void {
        $userId = $_SESSION['user_id'] ?? null;
        $useDB  = ($userId !== null && $userId > 0);

        if ($useDB) {
            $favIds = Favorite::findByUser((int)$userId);
            $cars   = empty($favIds) ? [] : Favorite::getCarsForUser((int)$userId);
            $this->json(['favorites' => $favIds, 'cars' => $cars]);
        } else {
            $favIds = $_SESSION['favorites'] ?? [];
            $cars   = [];
            if (!empty($favIds)) {
                $db   = Database::getInstance();
                $list = implode(',', array_map('intval', $favIds));
                $res  = mysqli_query($db, "SELECT * FROM cars WHERE iid IN ($list)");
                $cars = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
            }
            $this->json(['favorites' => $favIds, 'cars' => $cars]);
        }
    }
}
