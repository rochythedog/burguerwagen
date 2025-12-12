<?php
require_once 'Log.php';

class LogDAO extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save(Log $log): bool
    {
        $sql = "INSERT INTO logs (usuario_id, tipo, accion) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return false;

        $uid = $log->getUsuarioId();
        $tipo = $log->getTipo();
        $accion = $log->getAccion();

        $stmt->bind_param("iss", $uid, $tipo, $accion);
        return $stmt->execute();
    }
}
