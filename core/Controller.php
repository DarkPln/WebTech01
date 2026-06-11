<?php

abstract class Controller {
    protected function render(string $view, array $data = []): void {
        extract($data);
        $viewPath = BASE_PATH . 'app/views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo '404 – View nicht gefunden: ' . htmlspecialchars($view);
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

    protected function requireLogin(): void {
        if (empty($_SESSION['user_id']) && ($_SESSION['user_id'] ?? null) !== 0) {
            $this->redirect(BASE_URL . '/auth/login');
        }
    }

    protected function requireAdmin(): void {
        if (empty($_SESSION['is_admin'])) {
            $this->json(['success' => false, 'message' => 'Kein Zugriff']);
            exit;
        }
    }
}
