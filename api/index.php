<?php
// config basica que explico david
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getConnection();
$recurso = $_GET['resource'] ?? 'pedidos';
$metodo  = $_SERVER['REQUEST_METHOD'];

// devuelve la respuesta
function responder($status, $data, $code = 200) {
    http_response_code($code);
    echo json_encode(['status' => $status, 'data' => $data]);
    exit;
}

// router 
if ($recurso === 'pedidos') {
    if ($metodo === 'GET') {
        if (isset($_GET['id'])) {
            // Ver un pedido
            $id = (int)$_GET['id'];
            
            // consulta para recoger los datos del pedido
            $stmt = $db->prepare("SELECT p.*, CONCAT(u.nombre, ' ', u.apellidos) as customer FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $pedido = $stmt->get_result()->fetch_assoc();
            //si no existe el pedido devuelve error
            if (!$pedido) responder('error', 'Pedido no encontrado', 404);

            // recogemos items del pedido
            $stmtItems = $db->prepare("SELECT lp.*, pr.nombre as product_name FROM lineas_pedido lp JOIN productos pr ON lp.producto_id = pr.id WHERE lp.pedido_id = ?");
            $stmtItems->bind_param("i", $id);
            $stmtItems->execute();
            $resultItems = $stmtItems->get_result();
            
            $items = [];
            while ($row = $resultItems->fetch_assoc()) $items[] = $row;
            
            $pedido['items'] = $items;
            responder('success', $pedido);

        } else {
            // listamos los pedidos
            $sql = "SELECT p.id, CONCAT(u.nombre, ' ', u.apellidos) as customer, p.estado as status, p.total, p.fecha as date FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.fecha DESC";
            $result = $db->query($sql);
            
            $pedidos = [];
            while ($row = $result->fetch_assoc()) $pedidos[] = $row;
            responder('success', $pedidos);
        }
    }
    elseif ($metodo === 'PUT') {
        // si es put actualizamos estado
        $input = json_decode(file_get_contents("php://input"), true);
        $id = (int)$input['id'];
        $status = $input['status'];

        $stmt = $db->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        
        if ($stmt->execute()) responder('success', 'Actualizado');
        else responder('error', 'Error al actualizar', 500);
    }
    elseif ($metodo === 'DELETE') {
        // si es delete entramos aqui para borrarlo
        $id = (int)$_GET['id'];
        //la query que borra el pedido de la base de datos
        $db->query("DELETE FROM lineas_pedido WHERE pedido_id = $id");
        if ($db->query("DELETE FROM pedidos WHERE id = $id")) {
            responder('success', 'Eliminado');
        } else {
            responder('error', 'Error al eliminar', 500);
        }
    }
} else {
    responder('error', 'Recurso no encontrado', 404);
}
