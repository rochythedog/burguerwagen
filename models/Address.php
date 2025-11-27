<?php
class Address extends Model
{
    public function getAddressesByUser(int $userId): array
    {
        $sql = "SELECT * FROM direcciones WHERE usuario_id = ?";
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

    public function createAddress(int $userId, string $alias, string $address, string $city, string $zip, string $country): bool
    {
        $sql = "INSERT INTO direcciones (usuario_id, alias, direccion, ciudad, cp, pais)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("isssss", $userId, $alias, $address, $city, $zip, $country);
        return $stmt->execute();
    }
}
