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

    public function getByCategory(int $categoryId): array
    {
        $sql = "SELECT * FROM productos WHERE activo = 1 AND categoria_id = ? ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        
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

    // Método para obtener arrays crudos para la API del admin
    public function getAllAsArray(): array
    {
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        $result = $this->db->query($sql);
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    public function save(Product $product): bool
    {
        $sql = "INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen, activo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $categoriaId = $product->getCategoriaId();
        $nombre = $product->getNombre();
        $descripcion = $product->getDescripcion();
        $precio = $product->getPrecio();
        $imagen = $product->getImagen();
        $activo = $product->isActivo() ? 1 : 0;

        $stmt->bind_param("issdsi", $categoriaId, $nombre, $descripcion, $precio, $imagen, $activo);
        return $stmt->execute();
    }

    public function update(Product $product): bool
    {
        $sql = "UPDATE productos SET categoria_id = ?, nombre = ?, descripcion = ?, precio = ?, imagen = ?, activo = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $categoriaId = $product->getCategoriaId();
        $nombre = $product->getNombre();
        $descripcion = $product->getDescripcion();
        $precio = $product->getPrecio();
        $imagen = $product->getImagen();
        $activo = $product->isActivo() ? 1 : 0;
        $id = $product->getId();

        $stmt->bind_param("issdsii", $categoriaId, $nombre, $descripcion, $precio, $imagen, $activo, $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM productos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        return $stmt->execute();
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
