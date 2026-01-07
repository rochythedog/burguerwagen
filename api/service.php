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
    require_once $root . '/models/OrderItem.php';
    require_once $root . '/models/Log.php';
    require_once $root . '/models/User.php';
    
    require_once $root . '/models/ProductDAO.php';
    require_once $root . '/models/OrderDAO.php';
    require_once $root . '/models/OrderItemDAO.php';
    require_once $root . '/models/LogDAO.php';
    require_once $root . '/models/Offer.php';
    require_once $root . '/models/OfferDAO.php';
    if (file_exists($root . '/models/AddressDAO.php')) {
        require_once $root . '/models/AddressDAO.php';
    }
    
    if(file_exists($root . '/models/CategoryDAO.php')) {
        require_once $root . '/models/CategoryDAO.php';
    }

    $action = $_GET['action'] ?? '';

    if ($action === 'get_data') {
        $orderDAO = new OrderDAO();
        $productDAO = new ProductDAO();
        $logDAO = new LogDAO();
        $offerDAO = new OfferDAO();

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

        $offers = $offerDAO->getAllAsArray();

        // Users for admin order creation
        $db = Database::getConnection();
        $users = [];
        $resU = $db->query("SELECT id, nombre, email FROM usuarios ORDER BY nombre ASC");
        while ($row = $resU->fetch_assoc()) { $users[] = $row; }

        $data = [
            'orders' => $orders,
            'products' => $products,
            'offers' => $offers,
            'users' => $users,
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
        $id = (int)($_POST['id'] ?? 0);
        if ($id === 0) {
            throw new Exception('ID de producto inválido');
        }
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

    } elseif ($action === 'delete_order') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id === 0) {
            throw new Exception('ID de pedido inválido');
        }
        
        $orderDAO = new OrderDAO();
        if ($orderDAO->delete($id)) {
            $logDAO = new LogDAO();
            $log = new Log();
            $log->setUsuarioId($_SESSION['user_id']);
            $log->setTipo('admin');
            $log->setAccion("Eliminado pedido ID: $id");
            $logDAO->save($log);
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Error al eliminar pedido");
        }

    } elseif ($action === 'save_offer') {
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'];
        $valor = $_POST['valor'];
        $es_porcentaje = isset($_POST['es_porcentaje']) ? 1 : 0;
        $inicio = $_POST['inicio'] ?? null;
        $fin = $_POST['fin'] ?? null;
        $activa = isset($_POST['activa']) ? 1 : 0;

        $offer = new Offer();
        $offer->setNombre($nombre);
        $offer->setValor((float)$valor);
        $offer->setEsPorcentaje($es_porcentaje);
        $offer->setInicio($inicio);
        $offer->setFin($fin);
        $offer->setActiva($activa);

        $offerDAO = new OfferDAO();
        
        if ($id !== '') {
            $offer->setId((int)$id);
            $result = $offerDAO->update($offer);
            $msg = "Actualizada oferta: $nombre";
        } else {
            $result = $offerDAO->save($offer);
            $msg = "Creada oferta: $nombre";
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
            throw new Exception("Error al guardar oferta");
        }

    } elseif ($action === 'delete_offer') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id === 0) {
            throw new Exception('ID de oferta inválido');
        }
        $offerDAO = new OfferDAO();
        if ($offerDAO->delete($id)) {
            $logDAO = new LogDAO();
            $log = new Log();
            $log->setUsuarioId($_SESSION['user_id']);
            $log->setTipo('admin');
            $log->setAccion("Eliminada oferta ID: $id");
            $logDAO->save($log);
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Error al eliminar oferta");
        }

    } elseif ($action === 'get_user_addresses') {
        $userId = (int)($_GET['user_id'] ?? 0);
        if ($userId <= 0) { echo json_encode([]); exit; }
        $db = Database::getConnection();
        $addresses = [];
        $stmt = $db->prepare("SELECT id, alias, direccion, ciudad, cp, pais FROM direcciones WHERE usuario_id = ? ORDER BY id DESC");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        while($row = $res->fetch_assoc()) { $addresses[] = $row; }
        echo json_encode($addresses);

    } elseif ($action === 'save_order') {
        // Expected: usuario_id, direccion_id, estado, moneda, items (JSON: [{producto_id, cantidad}])
        $usuario_id = (int)($_POST['usuario_id'] ?? 0);
        $direccion_id = (int)($_POST['direccion_id'] ?? 0);
        $estado = $_POST['estado'] ?? 'pending';
        $moneda = $_POST['moneda'] ?? 'EUR';
        $itemsJson = $_POST['items'] ?? '[]';
        $items = json_decode($itemsJson, true) ?: [];

        if ($usuario_id <= 0 || $direccion_id <= 0 || empty($items)) {
            throw new Exception('Datos de pedido inválidos');
        }

        $db = Database::getConnection();
        $db->begin_transaction();
        try {
            // Calcular total usando precio actual del producto
            $total = 0.0;
            $prodStmt = $db->prepare("SELECT precio FROM productos WHERE id = ?");
            foreach ($items as $it) {
                $pid = (int)($it['producto_id'] ?? 0);
                $cant = (int)($it['cantidad'] ?? 0);
                if ($pid <= 0 || $cant <= 0) continue;
                $prodStmt->bind_param('i', $pid);
                $prodStmt->execute();
                $res = $prodStmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $precio = (float)$row['precio'];
                    $total += $precio * $cant;
                }
            }

            // Crear pedido
            $orderDAO = new OrderDAO();
            $order = new Order();
            $order->setUsuarioId($usuario_id);
            $order->setDireccionId($direccion_id);
            $order->setEstado($estado);
            $order->setMoneda($moneda);
            $order->setTotal($total);
            if (!$orderDAO->save($order)) {
                throw new Exception('No se pudo crear el pedido');
            }

            $pedido_id = $order->getId();
            // Guardar líneas
            $itemDAO = new OrderItemDAO();
            $prodStmt2 = $db->prepare("SELECT precio FROM productos WHERE id = ?");
            foreach ($items as $it) {
                $pid = (int)$it['producto_id'];
                $cant = (int)$it['cantidad'];
                if ($pid <= 0 || $cant <= 0) continue;
                $prodStmt2->bind_param('i', $pid);
                $prodStmt2->execute();
                $res2 = $prodStmt2->get_result();
                if ($row2 = $res2->fetch_assoc()) {
                    $precioUnit = (float)$row2['precio'];
                    $item = new OrderItem();
                    $item->setPedidoId($pedido_id);
                    $item->setProductoId($pid);
                    $item->setCantidad($cant);
                    $item->setPrecioUnitario($precioUnit);
                    if (!$itemDAO->save($item)) {
                        throw new Exception('No se pudo crear una línea de pedido');
                    }
                }
            }

            // Log
            $logDAO = new LogDAO();
            $log = new Log();
            $log->setUsuarioId($_SESSION['user_id']);
            $log->setTipo('admin');
            $log->setAccion("Creado pedido admin ID: $pedido_id para usuario $usuario_id");
            $logDAO->save($log);

            $db->commit();
            echo json_encode(['success' => true, 'pedido_id' => $pedido_id]);
        } catch (Throwable $ex) {
            $db->rollback();
            throw $ex;
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
