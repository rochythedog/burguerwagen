<?php
class AdminOrderController extends Controller
{
    // INDEX: show admin orders page (data from API)
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
