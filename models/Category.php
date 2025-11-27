<?php
class Category extends Model
{
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
