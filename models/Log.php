<?php
class Log extends Model
{
    public function addLog(?int $userId, string $type, string $action): void
    {
        $sql = "INSERT INTO logs (usuario_id, tipo, accion) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return;

        if ($userId === null) {
            $null = null;
            $stmt->bind_param("iss", $null, $type, $action);
        } else {
            $stmt->bind_param("iss", $userId, $type, $action);
        }
        $stmt->execute();
    }
}
