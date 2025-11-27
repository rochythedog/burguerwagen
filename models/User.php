<?php
class User extends Model
{
    public function createUser(string $name, string $lastName, string $email, string $password): bool
    {
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password_hash) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $name, $lastName, $email, $hash);
        return $stmt->execute();
    }

    public function getUserByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    public function getUserById(int $id): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE id = ? LIMIT 1";
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
