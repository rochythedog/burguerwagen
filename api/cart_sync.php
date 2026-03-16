<?php
session_start();
header('Content-Type: application/json');

try {
    $root = dirname(__DIR__);

    require_once $root . '/config/config.php';
    require_once $root . '/config/database.php';
    require_once $root . '/core/Model.php';
    require_once $root . '/models/Product.php';
    require_once $root . '/models/ProductDAO.php';

    $raw = file_get_contents('php://input');
    $items = json_decode($raw, true);

    if (!is_array($items)) {
        echo json_encode(['success' => false, 'error' => 'datos invalidos']);
        exit;
    }

    $_SESSION['carrito'] = [];
    $productDAO = new ProductDAO();

    foreach ($items as $item) {
        $product_id = (int)($item['product_id'] ?? 0);
        $quantity   = (int)($item['quantity'] ?? 1);

        if ($product_id <= 0) continue;

        $producto = $productDAO->getById($product_id);
        if (!$producto) continue;

        $prodStd          = new stdClass();
        $prodStd->id      = $producto->getId();
        $prodStd->nombre  = $producto->getNombre();
        $prodStd->precio  = $producto->getPrecio();
        $prodStd->imagen  = $producto->getImagen();

        $_SESSION['carrito'][] = [
            'id_producto' => $producto->getId(),
            'precio'      => $producto->getPrecio(),
            'unidades'    => $quantity,
            'producto'    => $prodStd
        ];
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit;
?>
