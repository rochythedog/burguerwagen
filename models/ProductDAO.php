<?php
require_once 'Product.php';

class ProductDAO extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM productos WHERE activo = 1 ORDER BY id DESC";
        $result = $this->db->query($sql);
        $products = [];
        
        while ($row = $result->fetch_assoc()) {
            $products[] = $this->mapToProduct($row);
        }
        return $products;
    }

    public function getById(int $id): ?Product
    {
        $sql = "SELECT * FROM productos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $this->mapToProduct($row);
        }
        return null;
    }

    private function mapToProduct(array $row): Product
    {
        $p = new Product();
        $p->setId($row['id']);
        $p->setCategoriaId($row['categoria_id'] ?? null);
        $p->setNombre($row['nombre']);
        $p->setDescripcion($row['descripcion'] ?? null);
        $p->setPrecio((float)$row['precio']);
        $p->setImagen($row['imagen'] ?? null);
        $p->setActivo((bool)$row['activo']);
        return $p;
    }
}
