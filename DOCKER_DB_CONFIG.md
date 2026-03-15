# Configuracion de base de datos para Docker

Solo la base de datos va en Docker.

## 1) Levantar la BD Docker

Desde la carpeta del proyecto:

```powershell
docker compose up -d
```

## 2) Datos de conexion
config.php

```php
DB_HOST = '127.0.0.1'
DB_PORT = 3307
DB_USER = 'root'
DB_PASS = 'root'
DB_NAME = 'burguerwagen'
```

incluir el puerto:

```php
new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
```

## 3) Comandos

Parar BD:

```powershell
docker compose down
```

Ver logs:

```powershell
docker compose logs -f burguerwagen-db
```

## Nota importante

El archivo burguerwagen.sql se importa automaticamente solo la primera vez (cuando el volumen esta vacio).

```powershell
docker compose down -v
docker compose up -d
```
