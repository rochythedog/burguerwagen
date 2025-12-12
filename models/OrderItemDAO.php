<?php
require_once 'OrderItem.php';

class OrderItemDAO extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save(OrderItem $item): bool
    {
        $sql = "INSERT INTO lineas_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return false;

        $pid = $item->getPedidoId();
        $prodId = $item->getProductoId();
        $cant = $item->getCantidad();
        $precio = $item->getPrecioUnitario();

        $stmt->bind_param("iiid", $pid, $prodId, $cant, $precio);
        return $stmt->execute();
    }

    public function getByOrderId(int $orderId): array
    {
        $sql = "SELECT lp.*, p.nombre as producto_nombre, p.imagen as producto_imagen 
                FROM lineas_pedido lp 
                INNER JOIN productos p ON lp.producto_id = p.id 
                WHERE lp.pedido_id = ?";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $item = new OrderItem();
            $item->setId($row['id']);
            $item->setPedidoId($row['pedido_id']);
            $item->setProductoId($row['producto_id']);
            $item->setCantidad($row['cantidad']);
            $item->setPrecioUnitario((float)$row['precio_unitario']);
            
            // Inyectamos datos del producto para la vista
            $item->producto = (object)[
                'nombre' => $row['producto_nombre'],
                'precio' => $row['precio_unitario'], // Usamos el precio guardado en la línea
                'imagen' => $row['producto_imagen']
            ];
            
            $items[] = $item;
        }
        return $items;
    }
}
