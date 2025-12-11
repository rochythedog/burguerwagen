<?php
//controlador principal base
class Controller
{
    protected function render(string $viewName, array $data = []): void
    {
        $view = new View();
        $view->render($viewName, $data);
    }

    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('index.php?controller=user&action=login');
        }
    }
}
