<?php
class OrderController extends Controller
{
    // te lleva a la vista de mis pedidos
    public function index(): void
    {
        $this->requireLogin();

        $orderModel = new Order();
        $orders = $orderModel->getOrdersByUser($_SESSION['user_id']);

        $this->render('orders/index', [
            'title'  => 'My orders',
            'orders' => $orders
        ]);
    }

    // crea json del carrito
    public function create(): void
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php');
        }

        $addressId = (int)($_POST['address_id'] ?? 0);
        $cartJson  = $_POST['cart_json'] ?? '[]';
        $currency  = 'EUR';

        $items = json_decode($cartJson, true);
        if (!is_array($items) || empty($items)) {
            die("Cart is empty or invalid.");
        }

        $total = 0.0;
        foreach ($items as $item) {
            $total += (float)$item['unit_price'] * (int)$item['quantity'];
        }

        $orderModel = new Order();
        $orderId = $orderModel->createOrder($_SESSION['user_id'], $addressId, $total, $currency, $items);

        if ($orderId) {
            $this->render('orders/created', [
                'title'   => 'Order created',
                'orderId' => $orderId,
                'total'   => $total
            ]);
        } else {
            die("Error creating order.");
        }
    }
}
