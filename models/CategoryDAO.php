<?php
require_once 'Category.php';

class CategoryDAO extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM categorias ORDER BY id ASC";
        $result = $this->db->query($sql);
        $categories = [];
        
        while ($row = $result->fetch_assoc()) {
            $categories[] = $this->mapToCategory($row);
        }
        
        return $categories;
    }

    public function getById(int $id): ?Category
    {
        $sql = "SELECT * FROM categorias WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $this->mapToCategory($row);
        }
        
        return null;
    }

    public function save(Category $category): bool
    {
        $sql = "INSERT INTO categorias (nombre) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return false;

        $nombre = $category->getNombre();
        $stmt->bind_param("s", $nombre);
        return $stmt->execute();
    }

    private function mapToCategory(array $row): Category
    {
        $c = new Category();
        $c->setId($row['id']);
        $c->setNombre($row['nombre']);
        return $c;
    }
}
