<?php

class AdminOrderController
{
    // Carga la vista principal del panel
    public function index()
    {
        // Solo permitimos acceso si hay sesión de admin
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            require_once 'views/layout/header.php';
            require_once 'views/admin/index.php';
            require_once 'views/layout/footer.php';
        } else {
            header("Location: index.php");
        }
    }
}
