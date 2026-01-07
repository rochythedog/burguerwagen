-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para burguerwagen
CREATE DATABASE IF NOT EXISTS `burguerwagen` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `burguerwagen`;

-- Volcando estructura para tabla burguerwagen.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categorias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla burguerwagen.categorias: ~6 rows (aproximadamente)
INSERT INTO `categorias` (`id`, `nombre`, `parent_id`) VALUES
	(5, 'Hamburguesas', NULL),
	(6, 'Acompañamientos', NULL),
	(7, 'Bebidas', NULL),
	(8, 'Postres', NULL),
	(9, 'Menús', NULL),
	(10, 'Entrantes', NULL);

-- Volcando estructura para tabla burguerwagen.direcciones
CREATE TABLE IF NOT EXISTS `direcciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `alias` varchar(60) DEFAULT NULL,
  `direccion` varchar(255) NOT NULL,
  `ciudad` varchar(120) NOT NULL,
  `cp` varchar(15) NOT NULL,
  `pais` varchar(80) DEFAULT 'España',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `direcciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla burguerwagen.direcciones: ~7 rows (aproximadamente)
INSERT INTO `direcciones` (`id`, `usuario_id`, `alias`, `direccion`, `ciudad`, `cp`, `pais`) VALUES
	(1, 1, 'Oficina', 'Calle Admin 1', 'Barcelona', '08001', 'España'),
	(2, 2, 'Casa', 'Calle Cliente 22', 'Tarragona', '43001', 'España'),
	(3, 1, 'Envío 07/01/2026', 'Calle Pau Casals casa 8', 'El Vendrell', '43700', 'España'),
	(4, 1, 'Envío 07/01/2026', 'Calle Pau Casals casa 8', 'El Vendrell', '43700', 'España'),
	(5, 1, 'Envío 07/01/2026', 'Calle Pau Casals casa 8', 'El Vendrell', '43700', 'España'),
	(6, 1, 'Envío 07/01/2026', 'AV. VALENCIA, 6, 6º 2ª', 'MOLINS DE REI', '08750', 'España'),
	(7, 1, 'Envío 07/01/2026', 'AV. VALENCIA, 6, 6º 2ª', 'MOLINS DE REI', '08750', 'España'),
	(8, 1, 'Envío 07/01/2026', 'Casa Pacheco 13', 'Pacheco', '13213', 'España');

-- Volcando estructura para tabla burguerwagen.lineas_pedido
CREATE TABLE IF NOT EXISTS `lineas_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `lineas_pedido_ibfk_2` (`producto_id`),
  CONSTRAINT `lineas_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  CONSTRAINT `lineas_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla burguerwagen.lineas_pedido: ~5 rows (aproximadamente)
INSERT INTO `lineas_pedido` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
	(6, 3, 20, 1, 14.90),
	(7, 4, 20, 2, 14.90),
	(8, 5, 21, 1, 24.90),
	(9, 5, 19, 1, 13.90),
	(10, 6, 17, 1, 2.90),
	(11, 7, 20, 2, 14.90),
	(12, 8, 17, 1, 2.90);

-- Volcando estructura para tabla burguerwagen.logs
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla burguerwagen.logs: ~29 rows (aproximadamente)
INSERT INTO `logs` (`id`, `usuario_id`, `tipo`, `accion`, `timestamp`) VALUES
	(1, 1, 'auth', 'Admin login', '2026-01-07 17:15:00'),
	(2, 1, 'admin', 'Access admin orders panel', '2026-01-07 17:15:00'),
	(3, 2, 'pedido', 'Order #1 created', '2026-01-07 17:15:00'),
	(4, 1, 'admin', 'Pedido #2 cambiado a confirm', '2026-01-07 17:32:38'),
	(5, 1, 'admin', 'Pedido #2 cambiado a preparing', '2026-01-07 17:32:50'),
	(6, 1, 'admin', 'Eliminado producto ID: 4', '2026-01-07 17:41:33'),
	(7, 1, 'admin', 'Eliminado producto ID: 4', '2026-01-07 17:42:00'),
	(8, 1, 'admin', 'Eliminado producto ID: 4', '2026-01-07 17:42:09'),
	(9, 1, 'admin', 'Eliminado producto ID: 3', '2026-01-07 17:42:17'),
	(10, 1, 'admin', 'Eliminado producto ID: 4', '2026-01-07 17:44:22'),
	(11, 1, 'admin', 'Actualizado producto ID: 3', '2026-01-07 17:44:40'),
	(12, 1, 'admin', 'Creado producto: prueba', '2026-01-07 17:44:52'),
	(13, 1, 'admin', 'Pedido #2 cambiado a delivered', '2026-01-07 17:46:04'),
	(14, 1, 'admin', 'Pedido #2 cambiado a confirm', '2026-01-07 17:46:13'),
	(15, 1, 'admin', 'Pedido #2 cambiado a delivered', '2026-01-07 17:46:55'),
	(16, 1, 'admin', 'Pedido #1 cambiado a preparing', '2026-01-07 17:47:02'),
	(17, 1, 'admin', 'Pedido #1 cambiado a sended', '2026-01-07 17:47:11'),
	(18, 1, 'admin', 'Pedido #2 cambiado a paid', '2026-01-07 17:48:51'),
	(19, 1, 'admin', 'Pedido #1 cambiado a preparing', '2026-01-07 17:49:07'),
	(20, 1, 'admin', 'Pedido #1 cambiado a delivered', '2026-01-07 17:49:12'),
	(21, 1, 'admin', 'Pedido #1 cambiado a cancelled', '2026-01-07 17:49:18'),
	(22, 1, 'admin', 'Pedido #1 cambiado a pending', '2026-01-07 17:49:22'),
	(23, 1, 'admin', 'Pedido #1 cambiado a paid', '2026-01-07 17:49:26'),
	(24, 1, 'admin', 'Eliminado producto ID: 5', '2026-01-07 17:59:55'),
	(25, 1, 'admin', 'Eliminado producto ID: 3', '2026-01-07 17:59:57'),
	(26, 1, 'admin', 'Eliminado producto ID: 2', '2026-01-07 17:59:59'),
	(27, 1, 'admin', 'Eliminado producto ID: 1', '2026-01-07 18:00:01'),
	(28, 1, 'admin', 'Eliminado producto ID: 10', '2026-01-07 18:00:06'),
	(29, 1, 'admin', 'Eliminado pedido ID: 1', '2026-01-07 18:27:02'),
	(30, 1, 'admin', 'Creada oferta: prueba', '2026-01-07 18:40:23'),
	(31, 1, 'admin', 'Actualizada oferta: pruebaa', '2026-01-07 18:49:08'),
	(32, 1, 'admin', 'Creado pedido admin ID: 8 para usuario 2', '2026-01-07 18:53:32');

-- Volcando estructura para tabla burguerwagen.ofertas
CREATE TABLE IF NOT EXISTS `ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(160) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `es_porcentaje` tinyint(1) DEFAULT 1,
  `inicio` date DEFAULT NULL,
  `fin` date DEFAULT NULL,
  `activa` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla burguerwagen.ofertas: ~0 rows (aproximadamente)
INSERT INTO `ofertas` (`id`, `nombre`, `valor`, `es_porcentaje`, `inicio`, `fin`, `activa`) VALUES
	(1, 'pruebaa', 10.00, 1, '2026-01-07', '2026-02-04', 1);

-- Volcando estructura para tabla burguerwagen.pedidos
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `direccion_id` int(11) NOT NULL,
  `estado` enum('pending','paid','preparing','delivered','cancelled') DEFAULT 'pending',
  `moneda` char(3) DEFAULT 'EUR',
  `total` decimal(10,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `direccion_id` (`direccion_id`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`direccion_id`) REFERENCES `direcciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla burguerwagen.pedidos: ~5 rows (aproximadamente)
INSERT INTO `pedidos` (`id`, `usuario_id`, `direccion_id`, `estado`, `moneda`, `total`, `fecha`) VALUES
	(2, 1, 3, 'paid', 'EUR', 2.50, '2026-01-07 17:15:52'),
	(3, 1, 4, '', 'EUR', 14.90, '2026-01-07 18:10:58'),
	(4, 1, 5, '', 'EUR', 29.80, '2026-01-07 18:14:52'),
	(5, 1, 6, 'pending', 'EUR', 38.80, '2026-01-07 18:18:53'),
	(6, 1, 7, 'pending', 'EUR', 2.90, '2026-01-07 18:19:49'),
	(7, 1, 8, 'pending', 'EUR', 29.80, '2026-01-07 18:48:49'),
	(8, 2, 2, 'paid', 'EUR', 2.90, '2026-01-07 18:53:32');

-- Volcando estructura para tabla burguerwagen.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) DEFAULT NULL,
  `nombre` varchar(160) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla burguerwagen.productos: ~15 rows (aproximadamente)
INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `imagen`, `activo`) VALUES
	(6, 5, 'Burguer Simple', 'Hamburguesa clásica de ternera con queso, lechuga y salsa BurguerWagen.', 8.50, 'public/img/burguersimple.webp', 1),
	(7, 5, 'Burguer Doble', 'Doble carne de ternera, doble queso y salsa especial. Para ir a lo grande.', 10.90, 'public/img/burguerdoble.webp', 1),
	(8, 5, 'Hamburguesa Pollo Simple', 'Pechuga de pollo crujiente, lechuga fresca y mayonesa suave.', 8.20, 'public/img/hamburguesapollosimple.webp', 1),
	(9, 5, 'Hamburguesa Pollo Doble', 'Doble pollo crujiente, queso fundido y salsa especial.', 10.60, 'public/img/hamburguesapollodoble.webp', 1),
	(11, 6, 'Patatas Deluxe', 'Patatas gajo especiadas, crujientes por fuera y tiernas por dentro.', 3.50, 'public/img/patatasdelux.webp', 1),
	(12, 6, 'Patatas con Queso y Bacon', 'Patatas con salsa de queso cremosa y bacon crujiente por encima.', 4.60, 'public/img/patatasconquesoybacon.webp', 1),
	(13, 10, 'Nuggets', 'Nuggets de pollo crujientes, ideales para compartir (o no).', 4.90, 'public/img/nuggets.webp', 1),
	(14, 7, 'Coca-Cola', 'Refresco clásico bien frío.', 2.40, 'public/img/cocacola.webp', 1),
	(15, 7, 'Fanta', 'Refresco de naranja refrescante.', 2.40, 'public/img/fanta.webp', 1),
	(16, 7, 'Aquarius', 'Bebida isotónica para recargar energía.', 2.60, 'public/img/aquarius.webp', 1),
	(17, 7, 'Cerveza', 'Cerveza bien fría (33cl).', 2.90, 'public/img/cerveza.webp', 1),
	(18, 8, 'Helado de Chocolate', 'Helado cremoso de chocolate con topping crujiente.', 3.20, 'public/img/heladochocolate.webp', 1),
	(19, 9, 'Menú Burguer Donut', 'Menú especial con burguer + acompañamiento + bebida, toque dulce-salado.', 13.90, 'public/img/menuburguerdonut.webp', 1),
	(20, 9, 'Menú Especial Trufa', 'Menú premium con salsa de trufa, acompañamiento y bebida.', 14.90, 'public/img/menuespecialtrufa.webp', 1),
	(21, 9, 'Menú Familiar PizzaBurguer', 'Menú familiar para compartir: variedad + acompañamientos + bebidas.', 24.90, 'public/img/menufamiliarpizzaburguer.webp', 1);

-- Volcando estructura para tabla burguerwagen.producto_oferta
CREATE TABLE IF NOT EXISTS `producto_oferta` (
  `producto_id` int(11) NOT NULL,
  `oferta_id` int(11) NOT NULL,
  PRIMARY KEY (`producto_id`,`oferta_id`),
  KEY `producto_oferta_ibfk_2` (`oferta_id`),
  CONSTRAINT `producto_oferta_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `producto_oferta_ibfk_2` FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla burguerwagen.producto_oferta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla burguerwagen.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `apellidos` varchar(120) DEFAULT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla burguerwagen.usuarios: ~2 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `password_hash`, `rol`, `creado_en`) VALUES
	(1, 'Admin', 'BurguerWagen', 'admin@burguerwagen.local', '$2y$10$3ehZrxRt7QfYSHBpP0L7GuFbuKRM/ye76b9YJRtJpatiUBH/V4mRO', 'admin', '2026-01-07 17:14:06'),
	(2, 'Cliente', 'Demo', 'cliente@burguerwagen.local', '$2y$10$1VC/poRwaE5eVWtbTZ1qD.f.aPQx7.L/Y8R.EJOWdJh59q6SA8BO.', 'customer', '2026-01-07 17:14:06');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
