<?php
require_once 'User.php';

class UserDAO extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Registra un nuevo usuario (Ajustado a tu tabla real: sin direccion ni telefono)
     */
    public function create(User $user): bool
    {
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password_hash, rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return false;

        $nombre = $user->getNombre();
        $apellidos = $user->getApellidos();
        $email = $user->getEmail();
        $password = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $rol = $user->getRol();

        $stmt->bind_param("sssss", $nombre, $apellidos, $email, $password, $rol);
        
        return $stmt->execute();
    }

    /**
     * Actualiza los datos (Ajustado a tu tabla real)
     */
    public function update(User $user): bool
    {
        $sql = "UPDATE usuarios SET nombre=?, apellidos=?, email=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return false;

        $nombre = $user->getNombre();
        $apellidos = $user->getApellidos();
        $email = $user->getEmail();
        $id = $user->getId();

        $stmt->bind_param("sssi", $nombre, $apellidos, $email, $id);
        
        return $stmt->execute();
    }

    /**
     * Busca un usuario por email y devuelve un objeto User
     */
    public function getByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return null;

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $this->mapToUser($row);
        }
        
        return null;
    }

    /**
     * Busca un usuario por ID y devuelve un objeto User
     */
    public function getById(int $id): ?User
    {
        $sql = "SELECT * FROM usuarios WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $this->mapToUser($row);
        }
        
        return null;
    }

    /**
     * Método auxiliar para convertir array de BD a Objeto User
     */
    private function mapToUser(array $row): User
    {
        $user = new User();
        $user->setId($row['id']);
        $user->setNombre($row['nombre']);
        $user->setApellidos($row['apellidos']);
        $user->setEmail($row['email']);
        $user->setPassword($row['password_hash']); // Ojo: es el hash
        $user->setRol($row['rol']);
        $user->setCreadoEn($row['creado_en']);
        return $user;
    }
}
