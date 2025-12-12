<?php
require_once 'models/OrderDAO.php';
require_once 'models/OrderItemDAO.php';

class AdminOrderController extends Controller
{
    // carga la vista de admin + comprueba rol
    public function index(): void
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            die("Admin only.");
        }

        $orderDAO = new OrderDAO();
        $orders = $orderDAO->getAll();

        $this->render('admin/orders', [
            'title' => 'Admin orders',
            'orders' => $orders
        ]);
    }

    public function detail()
    {
        if (isset($_SESSION['admin']) && isset($_GET['id'])) {
            $id = $_GET['id'];
            
            $orderDAO = new OrderDAO();
            $order = $orderDAO->getById($id);
            
            $orderItemDAO = new OrderItemDAO();
            $products = $orderItemDAO->getByOrderId($id);

            require_once 'views/layout/header.php';
            require_once 'views/order/detail.php';
            require_once 'views/layout/footer.php';
        } else {
            header("Location: index.php?controller=adminOrder&action=index");
        }
    }

    public function status()
    {
        if (isset($_SESSION['admin']) && isset($_POST['order_id']) && isset($_POST['estado'])) {
            $id = $_POST['order_id'];
            $estado = $_POST['estado'];

            $orderDAO = new OrderDAO();
            $orderDAO->updateStatus($id, $estado);

            header("Location: index.php?controller=adminOrder&action=detail&id=" . $id);
        } else {
            header("Location: index.php");
        }
    }
}
