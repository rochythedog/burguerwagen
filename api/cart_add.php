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

    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'id invalido']);
        exit;
    }

    // si ya esta en el carrito, sumar unidad
    if (isset($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $i => $elemento) {
            if ($elemento['id_producto'] == $product_id) {
                $_SESSION['carrito'][$i]['unidades']++;
                echo json_encode(['success' => true]);
                exit;
            }
        }
    }

    // añadir producto nuevo
    $productDAO = new ProductDAO();
    $producto = $productDAO->getById($product_id);

    if ($producto) {
        $prodStd = new stdClass();
        $prodStd->id     = $producto->getId();
        $prodStd->nombre = $producto->getNombre();
        $prodStd->precio = $producto->getPrecio();
        $prodStd->imagen = $producto->getImagen();

        $_SESSION['carrito'][] = [
            'id_producto' => $producto->getId(),
            'precio'      => $producto->getPrecio(),
            'unidades'    => 1,
            'producto'    => $prodStd
        ];

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'producto no encontrado']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit;
?>
