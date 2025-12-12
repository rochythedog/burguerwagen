<?php
class Product extends Model
{
    private ?int $id = null;
    private ?int $categoria_id = null;
    private ?string $nombre = null;
    private ?string $descripcion = null;
    private ?float $precio = null;
    private ?string $imagen = null;
    private ?bool $activo = true;

    public function __construct() {}

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getCategoriaId(): ?int { return $this->categoria_id; }
    public function getNombre(): ?string { return $this->nombre; }
    public function getDescripcion(): ?string { return $this->descripcion; }
    public function getPrecio(): ?float { return $this->precio; }
    public function getImagen(): ?string { return $this->imagen; }
    public function isActivo(): ?bool { return $this->activo; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setCategoriaId(?int $categoria_id): void { $this->categoria_id = $categoria_id; }
    public function setNombre(?string $nombre): void { $this->nombre = $nombre; }
    public function setDescripcion(?string $descripcion): void { $this->descripcion = $descripcion; }
    public function setPrecio(?float $precio): void { $this->precio = $precio; }
    public function setImagen(?string $imagen): void { $this->imagen = $imagen; }
    public function setActivo(?bool $activo): void { $this->activo = $activo; }

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
