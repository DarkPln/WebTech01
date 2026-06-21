<?php
//Niclas, Lukas 

abstract class Controller {


        // seite wird gerendet vermittlung an view bzw an die php datei die displayed 
    protected function render(string $view, array $data = []): void {
        extract($data); // array keys zu variablen damit nutzbar in den views 
        $viewPath = BASE_PATH . 'app/views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            http_response_code(404);
            require BASE_PATH . 'app/views/errors/404.php';
            return;
        }
        require $viewPath;
    }

    protected function json(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
    }


    protected function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }

    // muss eingeloggt sein
    protected function requireLogin(): void {
        if (empty($_SESSION['user_id']) && ($_SESSION['user_id'] ?? null) !== 0) {
            $this->redirect(BASE_URL . '/auth/login');
        }
    }


    // adminzugriff 
    protected function requireAdmin(): void {
        if (empty($_SESSION['is_admin'])) {
            $this->json(['success' => false, 'message' => 'Kein Zugriff']);
            exit;
        }
    }
}
