<?php
require_once 'Offer.php';

class OfferDAO extends Model
{
    public function __construct() {
        parent::__construct();
    }

    public function getAll(): array {
        $sql = "SELECT * FROM ofertas ORDER BY id DESC";
        $result = $this->db->query($sql);
        $offers = [];
        while ($row = $result->fetch_assoc()) {
            $offers[] = $this->mapToOffer($row);
        }
        return $offers;
    }

    public function getAllAsArray(): array {
        $sql = "SELECT * FROM ofertas ORDER BY id DESC";
        $result = $this->db->query($sql);
        $offers = [];
        while ($row = $result->fetch_assoc()) {
            $offers[] = $row;
        }
        return $offers;
    }

    public function getById(int $id): ?Offer {
        $sql = "SELECT * FROM ofertas WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $this->mapToOffer($row);
        }
        return null;
    }

    public function save(Offer $offer): bool {
        $sql = "INSERT INTO ofertas (nombre, valor, es_porcentaje, inicio, fin, activa) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $nombre = $offer->getNombre();
        $valor = $offer->getValor();
        $es_porcentaje = $offer->isEsPorcentaje() ? 1 : 0;
        $inicio = $offer->getInicio();
        $fin = $offer->getFin();
        $activa = $offer->isActiva() ? 1 : 0;
        $stmt->bind_param("sdissi", $nombre, $valor, $es_porcentaje, $inicio, $fin, $activa);
        $result = $stmt->execute();
        if ($result) {
            $offer->setId($this->db->insert_id);
        }
        return $result;
    }

    public function update(Offer $offer): bool {
        $sql = "UPDATE ofertas SET nombre = ?, valor = ?, es_porcentaje = ?, inicio = ?, fin = ?, activa = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $nombre = $offer->getNombre();
        $valor = $offer->getValor();
        $es_porcentaje = $offer->isEsPorcentaje() ? 1 : 0;
        $inicio = $offer->getInicio();
        $fin = $offer->getFin();
        $activa = $offer->isActiva() ? 1 : 0;
        $id = $offer->getId();
        $stmt->bind_param("sdissii", $nombre, $valor, $es_porcentaje, $inicio, $fin, $activa, $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM ofertas WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    private function mapToOffer(array $row): Offer {
        $o = new Offer();
        $o->setId($row['id']);
        $o->setNombre($row['nombre']);
        $o->setValor((float)$row['valor']);
        $o->setEsPorcentaje((bool)$row['es_porcentaje']);
        $o->setInicio($row['inicio'] ?? null);
        $o->setFin($row['fin'] ?? null);
        $o->setActiva((bool)$row['activa']);
        return $o;
    }
}
