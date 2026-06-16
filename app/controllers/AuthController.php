<!-- Tim -->
<?php

class AuthController extends Controller {
    public function loginView(): void {
        $this->render('auth/login');
    }

    public function registerView(): void {
        $this->render('auth/registration');
    }

    public function logoutView(): void {
        $this->render('auth/logout');
    }

    public function userView(): void {
        $this->requireLogin();
        $this->render('user/index');
    }

    public function adminView(): void {
        if (empty($_SESSION['is_admin'])) {
            $this->redirect(BASE_URL . '/auth/login');
        }
        $this->render('admin/index');
    }

    public function login(): void {
        ob_start();
        $input    = json_decode(file_get_contents('php://input'), true) ?? [];
        $username = trim($input['username'] ?? '');
        $password = $input['password'] ?? '';

        if ($username === 'admin' && $password === 'Admin1234') {
            $_SESSION['user_id']  = 0;
            $_SESSION['username'] = 'admin';
            $_SESSION['is_admin'] = true;
            ob_end_clean();
            $this->json(['success' => true, 'isAdmin' => true, 'username' => 'admin']);
            return;
        }

        if ($username === 'TestUser' && $password === 'TestPass123') {
            $_SESSION['user_id']  = -1;
            $_SESSION['username'] = 'TestUser';
            $_SESSION['is_admin'] = false;
            ob_end_clean();
            $this->json(['success' => true, 'isAdmin' => false, 'username' => 'TestUser']);
            return;
        }

        $user = User::findByUsername($username);

        if (!$user || $user['password'] !== $password) {
            ob_end_clean();
            $this->json(['success' => false, 'message' => 'Falscher Benutzername oder Passwort']);
            return;
        }

        if ($user['is_locked']) {
            ob_end_clean();
            $this->json(['success' => false, 'message' => 'Ihr Konto ist vom Administrator gesperrt']);
            return;
        }

        $_SESSION['user_id']  = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = (bool)$user['is_admin'];

        ob_end_clean();
        $this->json(['success' => true, 'isAdmin' => (bool)$user['is_admin'], 'username' => $user['username']]);
    }

    public function logout(): void {
        session_destroy();
        $this->json(['success' => true]);
    }

    public function register(): void {
        $input    = json_decode(file_get_contents('php://input'), true) ?? [];
        $username = trim($input['username'] ?? '');
        $password = $input['password'] ?? '';

        if (strlen($username) < 5 || strlen($password) < 10) {
            $this->json(['success' => false, 'message' => 'Ungültige Eingaben']);
            return;
        }

        if (User::usernameExists($username)) {
            $this->json(['success' => false, 'message' => 'Benutzername bereits vergeben']);
            return;
        }

        User::create($username, $password);
        $this->json(['success' => true]);
    }

    public function status(): void {
        ob_start();

        if (!isset($_SESSION['user_id'])) {
            ob_end_clean();
            $this->json(['loggedIn' => false]);
            return;
        }

        $isLocked = false;
        $email = $phone = $city = null;

        if ($_SESSION['user_id'] > 0) {
            $userId = (int)$_SESSION['user_id'];
            $db     = Database::getInstance();
            $res    = mysqli_query($db, "SELECT is_locked, email, phone, city FROM users WHERE id = $userId");
            if ($res) {
                $row = mysqli_fetch_assoc($res);
                if ($row) {
                    $isLocked = (bool)$row['is_locked'];
                    $email    = $row['email'];
                    $phone    = $row['phone'];
                    $city     = $row['city'];
                }
            }
        }

        ob_end_clean();
        $this->json([
            'loggedIn' => true,
            'username' => $_SESSION['username'],
            'isAdmin'  => (bool)($_SESSION['is_admin'] ?? false),
            'isLocked' => $isLocked,
            'email'    => $email,
            'phone'    => $phone,
            'city'     => $city,
        ]);
    }

    public function update(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
            $this->json(['success' => false, 'message' => 'Nicht eingeloggt']);
            return;
        }

        $input       = json_decode(file_get_contents('php://input'), true) ?? [];
        $newUsername = trim($input['username'] ?? '');
        $newPassword = $input['password'] ?? '';
        $newEmail    = trim($input['email'] ?? '');
        $newPhone    = trim($input['phone'] ?? '');
        $newCity     = trim($input['city']  ?? '');

        if (strlen($newUsername) < 5) {
            $this->json(['success' => false, 'message' => 'Benutzername zu kurz (min. 5 Zeichen)']);
            return;
        }

        if ($newPassword !== '' && strlen($newPassword) < 10) {
            $this->json(['success' => false, 'message' => 'Passwort zu kurz (min. 10 Zeichen)']);
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $db     = Database::getInstance();

        $chkRes = mysqli_query($db, "SELECT id FROM users WHERE username = '$newUsername' AND id != $userId");
        if (mysqli_fetch_assoc($chkRes)) {
            $this->json(['success' => false, 'message' => 'Benutzername bereits vergeben']);
            return;
        }

        $fields = ['username' => $newUsername, 'email' => $newEmail, 'phone' => $newPhone, 'city' => $newCity];
        if ($newPassword !== '') $fields['password'] = $newPassword;

        User::update($userId, $fields);
        $_SESSION['username'] = $newUsername;
        $this->json(['success' => true, 'username' => $newUsername]);
    }
}