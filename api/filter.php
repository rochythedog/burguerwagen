<?php
header('Content-Type: application/json');

try {
    $root = dirname(__DIR__);
    
    $configFile = $root . '/config/config.php';
    if (file_exists($configFile)) {
        require_once $configFile;
    } else {
        throw new Exception('No se encuentra config.php');
    }

    $dbPath = $root . '/core/Database.php';
    if (!file_exists($dbPath)) $dbPath = $root . '/config/Database.php';
    
    if (!file_exists($dbPath)) throw new Exception('No se encuentra Database.php');
    
    require_once $dbPath;
    require_once $root . '/core/Model.php';
    require_once $root . '/models/Product.php';
    require_once $root . '/models/ProductDAO.php';

    $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    $productDAO = new ProductDAO();
    
    if ($categoryId > 0) {
        $productsObjects = $productDAO->getByCategory($categoryId);
    } else {
        $productsObjects = $productDAO->getAll();
    }
    
    // Convertimos a array y limitamos a 6
    $products = [];
    foreach ($productsObjects as $p) {
        $products[] = [
            'id' => $p->getId(),
            'nombre' => $p->getNombre(),
            'precio' => $p->getPrecio(),
            'imagen' => $p->getImagen(),
            'descripcion' => $p->getDescripcion()
        ];
    }
    
    $products = array_slice($products, 0, 6);
    
    echo json_encode([
        'success' => true,
        'products' => $products,
        'count' => count($products)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
