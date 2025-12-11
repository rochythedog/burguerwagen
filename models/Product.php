<?php
class Product extends Model
{
    // INDEX: listar todos los productos activos
    public function getAllActiveProducts(): array
    {
        $sql = "SELECT p.*, c.nombre AS category_name
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.activo = 1
                ORDER BY p.nombre";
        $result = $this->db->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    // encontrar por id
    public function getProductById(int $id): ?array
    {
        $sql = "SELECT p.*, c.nombre AS category_name
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }
}
