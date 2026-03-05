-- ============================================================================
-- BASE DE DATOS: tienda_mvc
-- DESCRIPCION: Script completo de la base de datos con todos los modulos
-- implementados (pagos, envios, ecommerce, carrito, reportes y administracion).
-- ============================================================================
CREATE DATABASE IF NOT EXISTS tienda_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tienda_mvc;

-- Desactivar temporalmente revision de llaves foraneas para evitar errores
SET FOREIGN_KEY_CHECKS=0;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- ===========================================================
-- Tabla: auditoria_pedidos
-- Proposito: Registra el historial de cambios de estado de cada pedido.
-- ===========================================================

DROP TABLE IF EXISTS `auditoria_pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auditoria_pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `estado_anterior` varchar(50) DEFAULT NULL,
  `estado_nuevo` varchar(50) DEFAULT NULL,
  `fecha_accion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: auditoria_pedidos

LOCK TABLES `auditoria_pedidos` WRITE;
/*!40000 ALTER TABLE `auditoria_pedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `auditoria_pedidos` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: auditoria_usuarios
-- Proposito: Guarda historial de acciones de los usuarios.
-- ===========================================================

DROP TABLE IF EXISTS `auditoria_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auditoria_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `detalle` text DEFAULT NULL,
  `fecha_accion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: auditoria_usuarios

LOCK TABLES `auditoria_usuarios` WRITE;
/*!40000 ALTER TABLE `auditoria_usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `auditoria_usuarios` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: carrito
-- Proposito: Almacena temporalmente los productos antes del checkout.
-- ===========================================================

DROP TABLE IF EXISTS `carrito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carrito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: carrito

LOCK TABLES `carrito` WRITE;
/*!40000 ALTER TABLE `carrito` DISABLE KEYS */;
/*!40000 ALTER TABLE `carrito` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: categorias
-- Proposito: Agrupa los productos por tipo.
-- ===========================================================

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: categorias

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Smartphones','Teléfonos inteligentes de última generación',1),(2,'Laptops','Computadoras portátiles para trabajo y gaming',1),(3,'Accesorios','Audífonos, cargadores y fundas',1);
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: configuracion_general
-- Proposito: Almacena configuraciones clave-valor globales de la tienda.
-- ===========================================================

DROP TABLE IF EXISTS `configuracion_general`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracion_general` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) NOT NULL,
  `valor` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: configuracion_general

LOCK TABLES `configuracion_general` WRITE;
/*!40000 ALTER TABLE `configuracion_general` DISABLE KEYS */;
INSERT INTO `configuracion_general` VALUES (2,'facebook_url',''),(3,'instagram_url',''),(4,'whatsapp_numero',''),(17,'Logo','Logo_url');
/*!40000 ALTER TABLE `configuracion_general` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: configuracion_payphone
-- ===========================================================

DROP TABLE IF EXISTS `configuracion_payphone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracion_payphone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token_autorizacion` varchar(1000) NOT NULL,
  `store_id` varchar(255) NOT NULL,
  `ambiente` enum('Pruebas','Prouccion') DEFAULT 'Pruebas',
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: configuracion_payphone

LOCK TABLES `configuracion_payphone` WRITE;
/*!40000 ALTER TABLE `configuracion_payphone` DISABLE KEYS */;
INSERT INTO `configuracion_payphone` VALUES (1,'Ingresa_tu_token_aqui','Ingresa_tu_tienda_aqui','Pruebas','2026-03-03 18:10:09');
/*!40000 ALTER TABLE `configuracion_payphone` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: configuracion_precios_servicio
-- Proposito: Define multiplicadores de cobro urgencias para servicio técnico.
-- ===========================================================

DROP TABLE IF EXISTS `configuracion_precios_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracion_precios_servicio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concepto` varchar(100) NOT NULL,
  `tipo` enum('Monto','Multiplicador') DEFAULT 'Multiplicador',
  `valor` decimal(10,2) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: configuracion_precios_servicio

LOCK TABLES `configuracion_precios_servicio` WRITE;
/*!40000 ALTER TABLE `configuracion_precios_servicio` DISABLE KEYS */;
INSERT INTO `configuracion_precios_servicio` VALUES (1,'Base','Monto',20.00,'Precio base del servicio'),(2,'Urgente','Monto',30.00,'Extra por prioridad Urgente'),(3,'Alta','Monto',15.00,'Extra por prioridad Alta'),(4,'24h','Multiplicador',2.50,'Multiplicador si es menos de 24h'),(5,'48h','Multiplicador',1.50,'Multiplicador si es menos de 48h');
/*!40000 ALTER TABLE `configuracion_precios_servicio` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: configuracion_whatsapp
-- ===========================================================

DROP TABLE IF EXISTS `configuracion_whatsapp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracion_whatsapp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(20) NOT NULL,
  `nombre_admin` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: configuracion_whatsapp

LOCK TABLES `configuracion_whatsapp` WRITE;
/*!40000 ALTER TABLE `configuracion_whatsapp` DISABLE KEYS */;
INSERT INTO `configuracion_whatsapp` VALUES (4,'593995372293','Danny');
/*!40000 ALTER TABLE `configuracion_whatsapp` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: configuracion_whatsapp_api
-- Proposito: Credenciales de UltraMsg para mensajes automatizados.
-- ===========================================================

DROP TABLE IF EXISTS `configuracion_whatsapp_api`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracion_whatsapp_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` varchar(100) DEFAULT NULL,
  `mensaje_token` varchar(255) DEFAULT NULL,
  `api_url` varchar(255) DEFAULT 'https://api.ultramsg.com',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: configuracion_whatsapp_api

LOCK TABLES `configuracion_whatsapp_api` WRITE;
/*!40000 ALTER TABLE `configuracion_whatsapp_api` DISABLE KEYS */;
INSERT INTO `configuracion_whatsapp_api` VALUES (1,'instance163662','i15hyv2q4itujqm4','https://api.ultramsg.com');
/*!40000 ALTER TABLE `configuracion_whatsapp_api` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: detalle_pedidos
-- Proposito: Desglosa los artículos exactos comprados dentro de cada pedido principal.
-- ===========================================================

DROP TABLE IF EXISTS `detalle_pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `detalle_pedidos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  CONSTRAINT `detalle_pedidos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: detalle_pedidos

LOCK TABLES `detalle_pedidos` WRITE;
/*!40000 ALTER TABLE `detalle_pedidos` DISABLE KEYS */;
INSERT INTO `detalle_pedidos` VALUES (13,9,29,1,899.99);
/*!40000 ALTER TABLE `detalle_pedidos` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: marcas
-- Proposito: Fabricantes del catálogo de productos.
-- ===========================================================

DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: marcas

LOCK TABLES `marcas` WRITE;
/*!40000 ALTER TABLE `marcas` DISABLE KEYS */;
INSERT INTO `marcas` VALUES (10,'Samsung','public/img/marcas/1772405567_Logo-Samsung.webp',1),(11,'Xiaomi','public/img/marcas/1772405583_Logo-Xiaomi.webp',1),(12,'iPhone','public/img/marcas/1772405599_Logo-iPhone.webp',1),(13,'Tecno','public/img/marcas/1772405611_Logo-Tecno.webp',1),(14,'Infinix','public/img/marcas/1772405622_Logo-Infinix.webp',1),(15,'Huawei','public/img/marcas/1772405640_Logo-Huawei.webp',1),(16,'OPPO','public/img/marcas/1772405657_Logo-Oppo.webp',1);
/*!40000 ALTER TABLE `marcas` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: metodos_envio
-- Proposito: Tipos de envíos configurados por el administrador (Retiro, Servientrega, etc).
-- ===========================================================

DROP TABLE IF EXISTS `metodos_envio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metodos_envio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `costo_base` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tiempo_estimado` varchar(100) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: metodos_envio

LOCK TABLES `metodos_envio` WRITE;
/*!40000 ALTER TABLE `metodos_envio` DISABLE KEYS */;
INSERT INTO `metodos_envio` VALUES (1,'Retiro en Tienda',0.00,'Inmediato',1),(2,'Servientregas',6.00,'48 horas',1);
/*!40000 ALTER TABLE `metodos_envio` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: metodos_pago
-- Proposito: Opciones de pago disponibles en el checkout.
-- ===========================================================

DROP TABLE IF EXISTS `metodos_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metodos_pago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL,
  `banco` varchar(100) DEFAULT NULL,
  `numero_cuenta` varchar(100) DEFAULT NULL,
  `titular` varchar(100) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: metodos_pago

LOCK TABLES `metodos_pago` WRITE;
/*!40000 ALTER TABLE `metodos_pago` DISABLE KEYS */;
INSERT INTO `metodos_pago` VALUES (1,'PayPhone',NULL,NULL,NULL,1),(5,'Transferencia','Banco Pichincha','2208958205','Danny Paredes',1);
/*!40000 ALTER TABLE `metodos_pago` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: noticias
-- Proposito: Almacena comunicados, noticias u ofertas tipo blog.
-- ===========================================================

DROP TABLE IF EXISTS `noticias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `noticias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `autor` varchar(100) DEFAULT 'Redacción',
  `contenido` text NOT NULL,
  `tipo_media` enum('texto','imagen','video','mixto') DEFAULT 'texto',
  `media_url` varchar(255) DEFAULT NULL,
  `url_externa` varchar(255) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: noticias

LOCK TABLES `noticias` WRITE;
/*!40000 ALTER TABLE `noticias` DISABLE KEYS */;
INSERT INTO `noticias` VALUES (1,'Nueva Colección de Verano 2026','Moda Tech','Estamos emocionados de presentar nuestra nueva línea de accesorios inteligentes para este verano. Diseñados para durar y destacar.','imagen','public/img/Logo.webp',NULL,NULL,'2026-03-02 01:29:21'),(2,'Gran Apertura de Nueva Sucursal','Admin','¡Acompáñanos este sábado! Tendremos música en vivo, descuentos exclusivos y muchas sorpresas para los primeros 100 visitantes.','video','public/video/promo.mp4','https://nuestra-web.com/evento',NULL,'2026-03-02 01:29:21'),(3,'Lanzamiento: Nuevo iPhone 16 Pro','Redacción','El futuro ha llegado. Conoce todas las especificaciones del nuevo dispositivo que revolucionará la fotografía móvil.','mixto','public/img/Logo.webp','https://apple.com',NULL,'2026-03-02 01:29:21'),(4,'Mantenimiento Programado del Sistema','Soporte IT','Este domingo de 2:00 AM a 4:00 AM realizaremos mejoras en nuestros servidores para brindarte una experiencia más rápida.','texto',NULL,NULL,NULL,'2026-03-02 01:29:21');
/*!40000 ALTER TABLE `noticias` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: ofertas
-- Proposito: Descuentos temporales en base al porcentaje para un producto.
-- ===========================================================

DROP TABLE IF EXISTS `ofertas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `descuento_porcentaje` decimal(5,2) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `ofertas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: ofertas

LOCK TABLES `ofertas` WRITE;
/*!40000 ALTER TABLE `ofertas` DISABLE KEYS */;
INSERT INTO `ofertas` VALUES (2,28,5.00,'2026-03-01 00:00:00','2026-03-11 00:00:00');
/*!40000 ALTER TABLE `ofertas` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: pedidos
-- Proposito: Cabecera principal de compra, registra método, total gastado y comprobante de depósito.
-- ===========================================================

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `metodo_pago_id` int(11) NOT NULL,
  `metodo_envio_id` int(11) DEFAULT NULL,
  `costo_envio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(50) DEFAULT 'Pendiente',
  `motivo_rechazo` text DEFAULT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `comprobante_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `metodo_pago_id` (`metodo_pago_id`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_pago` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: pedidos

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (9,9,5,2,0.00,899.99,'Confirmado','Se confirma','2026-03-03 20:49:36','public/img/comprobantes/receipt_69a7496076865_1772570976.png');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: productos
-- Proposito: Catálogo principal de tiendas, inventario, precios e imágenes.
-- ===========================================================

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) NOT NULL,
  `marca_id` int(11) NOT NULL,
  `referencia_id` int(11) DEFAULT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen_url` text DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `referencia_id` (`referencia_id`),
  KEY `idx_productos_categoria` (`categoria_id`),
  KEY `idx_productos_marca` (`marca_id`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`),
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`referencia_id`) REFERENCES `referencias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: productos

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (9,1,12,NULL,'iPhone 15 Pro',NULL,999.99,15,'[\"public\\/img\\/productos\\/1772407855_0_5.png\",\"public\\/img\\/productos\\/1772407855_1_4.png\",\"public\\/img\\/productos\\/1772407855_2_3.png\",\"public\\/img\\/productos\\/1772407855_3_2.png\",\"public\\/img\\/productos\\/1772407855_4_1.png\"]',1,'2026-03-01 23:30:55'),(28,1,12,NULL,'iPhone 15 Pro Max','Edición de 256GB con tecnología ProMotion.',1199.99,9,'[\"public/img/productos/1772407855_0_5.png\"]',1,'2026-03-02 01:54:22'),(29,1,12,NULL,'iPhone 15 Plus','Pantalla más grande y mayor duración de batería.',899.99,17,'[\"public/img/productos/1772407855_0_5.png\"]',1,'2026-03-02 01:54:22'),(30,1,12,NULL,'iPhone 14 Pro','Chip A16 Bionic y Dynamic Island.',899.00,12,'[\"public/img/productos/1772407855_0_5.png\"]',1,'2026-03-02 01:54:22'),(31,1,12,NULL,'iPhone 13 Mini','Compacto y potente.',599.99,7,'[\"public/img/productos/1772407855_0_5.png\"]',1,'2026-03-02 01:54:22'),(32,1,12,NULL,'iPhone SE 2022','El iPhone más asequible con 5G.',429.00,30,'[\"public/img/productos/1772407855_0_5.png\"]',1,'2026-03-02 01:54:22'),(33,1,12,NULL,'iPhone 12 Pro','Clásico de acero con tres cámaras.',699.00,5,'[\"public/img/productos/1772407855_0_5.png\"]',1,'2026-03-02 01:54:22'),(34,1,12,NULL,'iPad Pro M2','Potencia de laptop en una tablet.',1099.00,15,'[\"public/img/productos/1772407855_0_5.png\"]',1,'2026-03-02 01:54:22'),(35,1,12,NULL,'Apple Watch Series 9','Cuidado de la salud en tu muñeca.',399.99,45,'[\"public/img/productos/1772407855_0_5.png\"]',1,'2026-03-02 01:54:22'),(36,1,12,NULL,'AirPods Max','Sonido premium de alta fidelidad.',549.00,20,'[\"public/img/productos/1772407855_0_5.png\"]',1,'2026-03-02 01:54:22');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: redes_sociales
-- Proposito: Enlaces oficiales a mostrar en el footer del sitio web.
-- ===========================================================

DROP TABLE IF EXISTS `redes_sociales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `redes_sociales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `url_destino` text NOT NULL,
  `icono` varchar(100) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: redes_sociales

LOCK TABLES `redes_sociales` WRITE;
/*!40000 ALTER TABLE `redes_sociales` DISABLE KEYS */;
INSERT INTO `redes_sociales` VALUES (1,'Facebook','https://www.facebook.com/danny.paredes.637926','facebook',1),(3,'Whatsapp','https://w.app/593995372293','whatsapp',1);
/*!40000 ALTER TABLE `redes_sociales` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: referencias
-- Proposito: Testimonios de clientes satisfechos a mostrar en el landing page.
-- ===========================================================

DROP TABLE IF EXISTS `referencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_autor` varchar(100) NOT NULL,
  `comentario` text DEFAULT NULL,
  `estrellas` int(11) DEFAULT 5,
  `media_url` varchar(255) DEFAULT NULL,
  `tipo_media` enum('texto','imagen','video','mixto') DEFAULT 'texto',
  `url_referencia` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: referencias

LOCK TABLES `referencias` WRITE;
/*!40000 ALTER TABLE `referencias` DISABLE KEYS */;
INSERT INTO `referencias` VALUES (2,'N A','NNNNNNNNNNNNNNNAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA\r\nNA NA NA NA NA NANANA N AN ANA N AN\r\nNA ANA NA ANA ANA NA AN ANA ANAANANANANANA ANA AN ANA\r\nNANANANANAANNANANANANANANANANANANANANANANANANANANANANANANANANAN',4,NULL,'texto','https://www.facebook.com/share/p/1CQ5ERA1mf/'),(3,'Juan Pérez','El servicio al cliente es excepcional. Compré un iPhone y llegó en menos de 24 horas. ¡Altamente recomendado!',5,NULL,'texto','https://google.com'),(4,'María García','Me encanta la calidad de los productos. La sección de ofertas es increíble, pude ahorrar mucho dinero en mi última compra.',4,'public/img/Logo.webp','imagen',NULL),(5,'Tech Reviewers Inc.','Una de las mejores tiendas online que hemos analizado. Interfaz fluida, pagos seguros y catálogo muy variado.',5,'public/img/Logo.webp','mixto','https://youtube.com'),(6,'Empresa Logística Internacional','NNNNNNNNNNNNNNNNNAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',3,NULL,'texto',NULL),(7,'Carlos Rodríguez','Excelente variedad de marcas. El proceso de registro fue muy sencillo y la atención post-venta es de primera clase.',5,NULL,'texto',NULL);
/*!40000 ALTER TABLE `referencias` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: reglas_envio
-- Proposito: Ofertas configuradas, ej. envío gratis por un monto superior determinado.
-- ===========================================================

DROP TABLE IF EXISTS `reglas_envio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reglas_envio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `monto_minimo_carrito` decimal(10,2) NOT NULL,
  `costo_fijo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: reglas_envio

LOCK TABLES `reglas_envio` WRITE;
/*!40000 ALTER TABLE `reglas_envio` DISABLE KEYS */;
INSERT INTO `reglas_envio` VALUES (1,'Envio Gratis > 899.99',899.99,0.00,1);
/*!40000 ALTER TABLE `reglas_envio` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: roles
-- Proposito: Niveles de autorización general.
-- ===========================================================

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: roles

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'administrador'),(2,'usuario');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: servicio_tecnico
-- Proposito: Solicitudes de reparaciones de clientes.
-- ===========================================================

DROP TABLE IF EXISTS `servicio_tecnico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicio_tecnico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `dispositivo` varchar(100) DEFAULT NULL,
  `descripcion_problema` text NOT NULL,
  `prioridad` enum('Baja','Media','Alta','Urgente') DEFAULT 'Media',
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `precio_estimado` decimal(10,2) DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'Pendiente',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_entregado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `servicio_tecnico_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: servicio_tecnico

LOCK TABLES `servicio_tecnico` WRITE;
/*!40000 ALTER TABLE `servicio_tecnico` DISABLE KEYS */;
INSERT INTO `servicio_tecnico` VALUES (20,9,'na','NA\r\n','Urgente',NULL,'2026-03-07 20:00:00',150.00,'Entregado','2026-03-03 20:16:56','2026-03-03 15:20:12');
/*!40000 ALTER TABLE `servicio_tecnico` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: servicios_mantenimiento
-- Proposito: Listado de servicios ofertados (limpieza, cambio de display, etc).
-- ===========================================================

DROP TABLE IF EXISTS `servicios_mantenimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicios_mantenimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `icono` varchar(50) DEFAULT 'fas fa-tools',
  `precio_desde` decimal(10,2) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: servicios_mantenimiento

LOCK TABLES `servicios_mantenimiento` WRITE;
/*!40000 ALTER TABLE `servicios_mantenimiento` DISABLE KEYS */;
INSERT INTO `servicios_mantenimiento` VALUES (1,'Cambio de Pantalla','Reparación de vidrios rotos y pantallas LCD o OLED con repuestos originales.','fas fa-mobile-alt',45.00,1,'2026-03-02 01:59:29'),(2,'Cambio de Batería','¿Tu equipo ya no dura? Cambiamos tu batería vieja por una nueva de alto rendimiento.','fas fa-battery-full',25.00,1,'2026-03-02 01:59:29'),(3,'Limpieza y Mantenimiento','Eliminación de polvo interno, cambio de pasta térmica y optimización de hardware.','fas fa-broom',30.00,1,'2026-03-02 01:59:29'),(4,'Recuperación de Datos','¿Perdiste tu información? Recuperamos fotos y archivos de discos duros y memorias.','fas fa-hdd',60.00,1,'2026-03-02 01:59:29'),(5,'Soporte de Software','Instalación de sistemas, eliminación de virus y optimización de rendimiento.','fas fa-laptop-code',20.00,1,'2026-03-02 01:59:29');
/*!40000 ALTER TABLE `servicios_mantenimiento` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: ubicacion_local
-- Proposito: Coordenadas físicas inyectadas al mapa de ubicación en el home.
-- ===========================================================

DROP TABLE IF EXISTS `ubicacion_local`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ubicacion_local` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `direccion` text NOT NULL,
  `latitud` varchar(50) DEFAULT NULL,
  `longitud` varchar(50) DEFAULT NULL,
  `iframe_mapa` text DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `horario` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: ubicacion_local

LOCK TABLES `ubicacion_local` WRITE;
/*!40000 ALTER TABLE `ubicacion_local` DISABLE KEYS */;
INSERT INTO `ubicacion_local` VALUES (1,'Local 1','Na','-1.041939','-78.590646','1',NULL,NULL);
/*!40000 ALTER TABLE `ubicacion_local` ENABLE KEYS */;
UNLOCK TABLES;


-- ===========================================================
-- Tabla: usuarios
-- Proposito: Cuentas registradas de la plataforma y su contraseña encriptada en SHA-256.
-- ===========================================================

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rol_id` int(11) NOT NULL DEFAULT 2,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `codigo_verificacion` varchar(10) DEFAULT NULL,
  `codigo_expiracion` datetime DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Volcando registros y datos iniciales para la tabla: usuarios

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,1,'Admin','Sistema','admin@tienda.com','8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92','504911','2026-02-28 01:36:27','2026-02-27 23:46:49','+593995372293','Pujili'),(6,2,'Danny','Paredes','paredesd1104@gmail.com','134cd6880aa59b2a21618f7e96a10d9b4691ab1eb3db6fec18234de9820f2329','991215','2026-02-28 01:41:15','2026-02-28 00:26:15','+593979080606',''),(7,2,'Admin','User','admin@admin.com','3eb3fe66b31e3b4d10fa70b5cad49c7112294af6ae4e476a1c405155d45aa121',NULL,NULL,'2026-03-02 00:50:16',NULL,NULL),(8,2,'Super','Admin','superadmin@admin.com','3eb3fe66b31e3b4d10fa70b5cad49c7112294af6ae4e476a1c405155d45aa121',NULL,NULL,'2026-03-02 00:57:27',NULL,NULL),(9,2,'Danny','Pv.','datatu.020@gmail.com','e6f24fc29021782919c394db7cacf87a50cc53c99c95d955cc664e79c5839c3e',NULL,NULL,'2026-03-02 04:53:00','+593979080606','admin');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


SET FOREIGN_KEY_CHECKS=1;
