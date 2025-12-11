<?php
class AdminOrderController extends Controller
{
    // carga la vista de admin + comprueba rol
    public function index(): void
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            die("Admin only.");
        }

        $this->render('admin/orders', [
            'title' => 'Admin orders'
        ]);
    }
}
