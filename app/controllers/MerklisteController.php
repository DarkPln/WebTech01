<?php

class MerklisteController extends Controller {
    public function index(): void {
        $userId = $_SESSION['user_id'] ?? null;
        $useDB  = ($userId !== null && $userId > 0);

        if ($useDB) {
            $favIds = Favorite::findByUser((int)$userId);
            $gemerkteAutos = empty($favIds) ? [] : Favorite::getCarsForUser((int)$userId);
        } else {
            $favIds = $_SESSION['favorites'] ?? [];
            if (empty($favIds)) {
                $gemerkteAutos = [];
            } else {
                $db    = Database::getInstance();
                $list  = implode(',', array_map('intval', $favIds));
                $res   = mysqli_query($db, "SELECT * FROM cars WHERE iid IN ($list)");
                $gemerkteAutos = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
            }
        }

        $total         = array_sum(array_column($gemerkteAutos, 'preis'));
        $anzahl        = count($gemerkteAutos);
        $rabattProzent = $anzahl >= 3 ? 20 : ($anzahl >= 2 ? 10 : 0);
        $rabattBetrag  = $total * ($rabattProzent / 100);
        $endbetrag     = $total - $rabattBetrag;

        $this->render('merkliste/index', compact('gemerkteAutos', 'total', 'anzahl', 'rabattProzent', 'rabattBetrag', 'endbetrag'));
    }

    public function remove(): void {
        $userId = $_SESSION['user_id'] ?? null;
        $useDB  = ($userId !== null && $userId > 0);

        if ($useDB) {
            $db = Database::getInstance();
            if (isset($_POST['remove_id'])) {
                $removeId = (int)$_POST['remove_id'];
                mysqli_query($db, "DELETE FROM favorites WHERE user_id = $userId AND car_id = $removeId");
            }
            if (isset($_POST['clear_all'])) {
                mysqli_query($db, "DELETE FROM favorites WHERE user_id = $userId");
            }
        } else {
            if (!isset($_SESSION['favorites'])) $_SESSION['favorites'] = [];
            if (isset($_POST['remove_id'])) {
                $rid = (int)$_POST['remove_id'];
                $_SESSION['favorites'] = array_values(array_filter($_SESSION['favorites'], fn($id) => $id !== $rid));
            }
            if (isset($_POST['clear_all'])) {
                $_SESSION['favorites'] = [];
            }
        }

        $this->redirect(BASE_URL . '/merkliste');
    }
}
