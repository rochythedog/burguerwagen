<?php
class Category extends Model
{
    private ?int $id = null;
    private ?string $nombre = null;

    public function __construct() {}

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNombre(): ?string { return $this->nombre; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setNombre(?string $nombre): void { $this->nombre = $nombre; }

    public function getAllCategories(): array
    {
        $sql = "SELECT * FROM categorias ORDER BY nombre";
        $result = $this->db->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
}
