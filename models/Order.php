<?php
class Order extends Model
{
    // INDEX for a user
    public function getOrdersByUser(int $userId): array
    {
        $sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    // SHOW with details (user + address + items)
    public function getOrderWithDetails(int $orderId): ?array
    {
        $sql = "SELECT p.*, u.nombre, u.apellidos,
                       d.direccion, d.ciudad, d.cp, d.pais
                FROM pedidos p
                JOIN usuarios u ON p.usuario_id = u.id
                JOIN direcciones d ON p.direccion_id = d.id
                WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!($order = $result->fetch_assoc())) {
            return null;
        }

        $sqlItems = "SELECT lp.*, pr.nombre AS product_name
                     FROM lineas_pedido lp
                     JOIN productos pr ON lp.producto_id = pr.id
                     WHERE lp.pedido_id = ?";
        $stmtItems = $this->db->prepare($sqlItems);
        $stmtItems->bind_param("i", $orderId);
        $stmtItems->execute();
        $resultItems = $stmtItems->get_result();

        $items = [];
        while ($row = $resultItems->fetch_assoc()) {
            $items[] = $row;
        }
        $order['items'] = $items;

        return $order;
    }

    // CREATE: from cart items
    // $items: [ ['product_id'=>..., 'quantity'=>..., 'unit_price'=>...], ... ]
    public function createOrder(int $userId, int $addressId, float $total, string $currency, array $items): ?int
    {
        $this->db->begin_transaction();
        try {
            $sql = "INSERT INTO pedidos (usuario_id, direccion_id, moneda, total) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) throw new Exception();

            $stmt->bind_param("iisd", $userId, $addressId, $currency, $total);
            $stmt->execute();
            $orderId = $stmt->insert_id;

            $sqlItem = "INSERT INTO lineas_pedido (pedido_id, producto_id, cantidad, precio_unitario)
                        VALUES (?, ?, ?, ?)";
            $stmtItem = $this->db->prepare($sqlItem);
            if (!$stmtItem) throw new Exception();

            foreach ($items as $item) {
                $productId = (int)$item['product_id'];
                $quantity  = (int)$item['quantity'];
                $price     = (float)$item['unit_price'];

                $stmtItem->bind_param("iiid", $orderId, $productId, $quantity, $price);
                $stmtItem->execute();
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollback();
            return null;
        }
    }
}
