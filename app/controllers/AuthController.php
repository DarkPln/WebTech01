<?php
// Tim

// Verwaltet Login, Logout, Registrierung und Profilaktualisierung
class AuthController extends Controller {

    // Login-Seite anzeigen
    public function loginView(): void {
        $this->render('auth/login');
    }

    // Registrierungsseite anzeigen
    public function registerView(): void {
        $this->render('auth/registration');
    }

    // Logout-Seite anzeigen
    public function logoutView(): void {
        $this->render('auth/logout');
    }

    // Nutzerprofil anzeigen (nur eingeloggte Nutzer)
    public function userView(): void {
        $this->requireLogin();
        $this->render('user/index');
    }

    // Admin-Dashboard anzeigen (nur Admins, sonst Weiterleitung zu Login)
    public function adminView(): void {
        if (empty($_SESSION['is_admin'])) {
            $this->redirect(BASE_URL . '/auth/login');
        }
        $this->render('admin/panel');
    }

    // Login verarbeiten und Session setzen
    public function login(): void {
        // Roher JSON-Body lesen, da JS application/json schickt statt $_POST
        $input    = json_decode(file_get_contents('php://input'), true) ?? [];
        $username = trim($input['username'] ?? '');
        $password = $input['password'] ?? '';

        // Hardcoded Admin-Account, wird nicht in der DB geprüft
        if ($username === 'admin' && $password === 'Admin1234') {
            $_SESSION['user_id']  = 0;
            $_SESSION['username'] = 'admin';
            $_SESSION['is_admin'] = true;
            $this->json(['success' => true, 'isAdmin' => true, 'username' => 'admin']);
            return;
        }

        // Hardcoded Testnutzer für Demo-Zwecke
        if ($username === 'TestUser' && $password === 'TestPass123') {
            $_SESSION['user_id']  = -1;
            $_SESSION['username'] = 'TestUser';
            $_SESSION['is_admin'] = false;
            $this->json(['success' => true, 'isAdmin' => false, 'username' => 'TestUser']);
            return;
        }

        // Normaler Nutzer: in der DB nachschlagen
        $user = User::findByUsername($username);

        // Passwort wird als Klartext verglichen (kein Hashing)
        if (!$user || $user['password'] !== $password) {
            $this->json(['success' => false, 'message' => 'Falscher Benutzername oder Passwort']);
            return;
        }

        // Gesperrte Konten dürfen sich nicht einloggen
        if ($user['is_locked']) {
            $this->json(['success' => false, 'message' => 'Ihr Konto ist vom Administrator gesperrt']);
            return;
        }

        // Session-Variablen setzen – JS liest diese per /api/auth/status ab
        $_SESSION['user_id']  = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = (bool)$user['is_admin'];

        $this->json(['success' => true, 'isAdmin' => (bool)$user['is_admin'], 'username' => $user['username']]);
    }

    // Session beenden und Nutzer ausloggen
    public function logout(): void {
        session_destroy();
        $this->json(['success' => true]);
    }

    // Neuen Nutzer registrieren nach Validierung von Username und Passwort
    public function register(): void {
        // Roher JSON-Body lesen, da JS application/json schickt statt $_POST
        $input    = json_decode(file_get_contents('php://input'), true) ?? [];
        $username = trim($input['username'] ?? '');
        $password = $input['password'] ?? '';

        // Mindestlängen prüfen bevor DB-Zugriff
        if (strlen($username) < 5 || strlen($password) < 10) {
            $this->json(['success' => false, 'message' => 'Ungültige Eingaben']);
            return;
        }

        // Doppelte Usernamen verhindern
        if (User::usernameExists($username)) {
            $this->json(['success' => false, 'message' => 'Benutzername bereits vergeben']);
            return;
        }

        // Passwort wird als Klartext gespeichert (kein Hashing)
        User::create($username, $password);
        $this->json(['success' => true]);
    }

    // Aktuellen Login-Status als JSON zurückgeben (wird beim Seitenstart von JS abgefragt)
    public function status(): void {
        // Keine Session → nicht eingeloggt
        if (!isset($_SESSION['user_id'])) {
            $this->json(['loggedIn' => false]);
            return;
        }

        $isLocked = false;
        $email = $phone = $city = null;

        // Hardcoded Accounts (user_id 0 und -1) haben keinen DB-Eintrag
        if ($_SESSION['user_id'] > 0) {
            $userId = (int)$_SESSION['user_id'];
            $db     = Database::getInstance();
            // Sperrstatus und Profildaten aus der DB nachladen
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

        // Vollständiges authState-Objekt, das JS global speichert
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


// Lukas; update fkt für User Tab 

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

// auch hier prüfung passwort

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

        
// delegation an model speicherung in db 

        User::update($userId, $fields);
        $_SESSION['username'] = $newUsername;
        $this->json(['success' => true, 'username' => $newUsername]);
    }
}