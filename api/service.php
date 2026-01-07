<?php
session_start();
header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    if (!isset($_SESSION['admin'])) {
        throw new Exception('Acceso denegado');
    }

    $root = dirname(__DIR__); 

    $configFile = $root . '/config/config.php';
    if (file_exists($configFile)) {
        require_once $configFile;
    } else {
        throw new Exception('No se encuentra el archivo de configuración config.php');
    }

    $dbPath = $root . '/core/Database.php';
    if (!file_exists($dbPath)) $dbPath = $root . '/config/Database.php';
    
    if (!file_exists($dbPath)) throw new Exception('No se encuentra Database.php');
    
    require_once $dbPath;
    require_once $root . '/core/Model.php';

    require_once $root . '/models/Product.php';
    require_once $root . '/models/Order.php';
    require_once $root . '/models/Log.php';
    require_once $root . '/models/User.php';
    
    require_once $root . '/models/ProductDAO.php';
    require_once $root . '/models/OrderDAO.php';
    require_once $root . '/models/LogDAO.php';
    
    if(file_exists($root . '/models/CategoryDAO.php')) {
        require_once $root . '/models/CategoryDAO.php';
    }

    $action = $_GET['action'] ?? '';

    if ($action === 'get_data') {
        $orderDAO = new OrderDAO();
        $productDAO = new ProductDAO();
        $logDAO = new LogDAO();

        if (method_exists($productDAO, 'getAllAsArray')) {
            $products = $productDAO->getAllAsArray();
        } else {
            $objs = $productDAO->getAll();
            $products = [];
            foreach($objs as $p) {
                $products[] = [
                    'id' => $p->getId(),
                    'nombre' => $p->getNombre(),
                    'precio' => $p->getPrecio(),
                    'descripcion' => $p->getDescripcion(),
                    'imagen' => $p->getImagen(),
                    'categoria_id' => $p->getCategoriaId()
                ];
            }
        }

        if (method_exists($orderDAO, 'getAllForAdmin')) {
            $orders = $orderDAO->getAllForAdmin();
        } else {
            $orders = []; 
            $db = Database::getConnection();
            $res = $db->query("SELECT p.*, u.email as usuario_email FROM pedidos p LEFT JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.id DESC");
            while($row = $res->fetch_assoc()) {
                $orders[] = $row;
            }
        }

        $data = [
            'orders' => $orders,
            'products' => $products,
            'logs' => $logDAO->getAll()
        ];
        echo json_encode($data);

    } elseif ($action === 'save_product') {
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $imagen = $_POST['imagen'];
        $categoria_id = $_POST['categoria_id'] ?? 1;

        $product = new Product();
        $product->setNombre($nombre);
        $product->setPrecio((float)$precio);
        $product->setDescripcion($descripcion);
        $product->setImagen($imagen);
        $product->setCategoriaId((int)$categoria_id);
        $product->setActivo(true);

        $productDAO = new ProductDAO();
        
        if ($id !== '') {
            $product->setId((int)$id);
            $result = $productDAO->update($product);
            $msg = "Actualizado producto ID: $id";
        } else {
            $result = $productDAO->save($product);
            $msg = "Creado producto: $nombre";
        }

        if ($result) {
            $logDAO = new LogDAO();
            $log = new Log();
            $log->setUsuarioId($_SESSION['user_id']);
            $log->setTipo('admin');
            $log->setAccion($msg);
            $logDAO->save($log);
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Error al guardar producto");
        }

    } elseif ($action === 'delete_product') {
        $id = $_POST['id'];
        $productDAO = new ProductDAO();
        if ($productDAO->delete($id)) {
            $logDAO = new LogDAO();
            $log = new Log();
            $log->setUsuarioId($_SESSION['user_id']);
            $log->setTipo('admin');
            $log->setAccion("Eliminado producto ID: $id");
            $logDAO->save($log);
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Error al eliminar producto");
        }

    } elseif ($action === 'update_order_status') {
        $id = $_POST['id'];
        $status = $_POST['status'];
        
        $orderDAO = new OrderDAO();
        if ($orderDAO->updateStatus($id, $status)) {
            $logDAO = new LogDAO();
            $log = new Log();
            $log->setUsuarioId($_SESSION['user_id']);
            $log->setTipo('admin');
            $log->setAccion("Pedido #$id cambiado a $status");
            $logDAO->save($log);
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Error al actualizar estado");
        }

    } else {
        echo json_encode(['error' => 'Acción desconocida']);
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
