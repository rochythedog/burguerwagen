<?php
require_once 'Address.php';

class AddressDAO extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save(Address $address): bool
    {
        $sql = "INSERT INTO direcciones (usuario_id, alias, direccion, ciudad, cp, pais) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return false;

        $uid = $address->getUsuarioId();
        $alias = $address->getAlias();
        $dir = $address->getDireccion();
        $ciudad = $address->getCiudad();
        $cp = $address->getCp();
        $pais = $address->getPais();

        $stmt->bind_param("isssss", $uid, $alias, $dir, $ciudad, $cp, $pais);
        $result = $stmt->execute();

        if ($result) {
            $address->setId($this->db->insert_id);
        }
        
        return $result;
    }

    public function getAllByUser(int $userId): array
    {
        $sql = "SELECT * FROM direcciones WHERE usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return [];

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $addresses = [];
        while ($row = $result->fetch_assoc()) {
            $addr = new Address();
            $addr->setId($row['id']);
            $addr->setUsuarioId($row['usuario_id']);
            $addr->setAlias($row['alias']);
            $addr->setDireccion($row['direccion']);
            $addr->setCiudad($row['ciudad']);
            $addr->setCp($row['cp']);
            $addr->setPais($row['pais']);
            $addresses[] = $addr;
        }
        return $addresses;
    }
    
    public function getById(int $id): ?Address
    {
        $sql = "SELECT * FROM direcciones WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $addr = new Address();
            $addr->setId($row['id']);
            $addr->setUsuarioId($row['usuario_id']);
            $addr->setAlias($row['alias']);
            $addr->setDireccion($row['direccion']);
            $addr->setCiudad($row['ciudad']);
            $addr->setCp($row['cp']);
            $addr->setPais($row['pais']);
            return $addr;
        }
        return null;
    }
}
