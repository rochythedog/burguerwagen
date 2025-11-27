<?php
//vista base con el header y el footer
class View
{
    public function render(string $viewName, array $data = []): void
    {
        $viewPath = BASE_PATH . '/views/' . $viewName . '.php';

        if (!file_exists($viewPath)) {
            die("View '$viewName' not found.");
        }

        // Make $data keys become variables
        extract($data);

        include BASE_PATH . '/views/layout/header.php';
        include $viewPath;
        include BASE_PATH . '/views/layout/footer.php';
    }
}