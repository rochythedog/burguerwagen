<?php

class User
{
    private ?int $id = null;
    private ?string $nombre = null;
    private ?string $apellidos = null;
    private ?string $email = null;
    private ?string $password_hash = null;
    private ?string $rol = 'customer';
    private ?string $creado_en = null;

    public function __construct() {}

    public function getId(): ?int { return $this->id; }
    public function getNombre(): ?string { return $this->nombre; }
    public function getApellidos(): ?string { return $this->apellidos; }
    public function getEmail(): ?string { return $this->email; }
    public function getPassword(): ?string { return $this->password_hash; }
    public function getRol(): ?string { return $this->rol; }
    public function getCreadoEn(): ?string { return $this->creado_en; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setNombre(?string $nombre): void { $this->nombre = $nombre; }
    public function setApellidos(?string $apellidos): void { $this->apellidos = $apellidos; }
    public function setEmail(?string $email): void { $this->email = $email; }
    public function setPassword(?string $password): void { $this->password_hash = $password; }
    public function setRol(?string $rol): void { $this->rol = $rol; }
    public function setCreadoEn(?string $creado_en): void { $this->creado_en = $creado_en; }
}
