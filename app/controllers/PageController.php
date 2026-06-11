<?php

class PageController extends Controller {
    public function show(string $page): void {
        $allowed = ['faq', 'about', 'agb', 'datenschutz', 'finanzierung', 'partner', 'versicherung'];
        if (!in_array($page, $allowed, true)) {
            http_response_code(404);
            echo '404';
            return;
        }
        $this->render('pages/' . $page);
    }
}
