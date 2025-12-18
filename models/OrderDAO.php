<?php
require_once 'Order.php';

class OrderDAO extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save(Order $order): bool
    {
        $sql = "INSERT INTO pedidos (usuario_id, direccion_id, estado, moneda, total) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return false;

        $uid = $order->getUsuarioId();
        $dirId = $order->getDireccionId();
        $estado = $order->getEstado();
        $moneda = $order->getMoneda();
        $total = $order->getTotal();

        $stmt->bind_param("iisss", $uid, $dirId, $estado, $moneda, $total);
        $result = $stmt->execute();
        
        if ($result) {
            $order->setId($this->db->insert_id);
        }
        return $result;
    }

    public function getAllByUser(int $userId): array
    {
        $sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $this->mapToOrder($row);
        }
        return $orders;
    }

    public function getById(int $id): ?Order
    {
        $sql = "SELECT * FROM pedidos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $this->mapToOrder($row);
        }
        return null;
    }

    // Método para el panel de administración
    public function getAllForAdmin(): array
    {
        $sql = "SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email 
                FROM pedidos p 
                INNER JOIN usuarios u ON p.usuario_id = u.id 
                ORDER BY p.fecha DESC";
        $result = $this->db->query($sql);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE pedidos SET estado = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    private function mapToOrder(array $row): Order
    {
        $o = new Order();
        $o->setId($row['id']);
        $o->setUsuarioId($row['usuario_id']);
        $o->setDireccionId($row['direccion_id']);
        $o->setEstado($row['estado']);
        $o->setMoneda($row['moneda']);
        $o->setTotal((float)$row['total']);
        $o->setFecha($row['fecha']);
        return $o;
    }
}