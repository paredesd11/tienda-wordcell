-- ============================================================================
-- ARCHIVO:      BBD.sql
-- BASE DE DATOS: tienda_mvc
-- DESCRIPCION:  Script completo para crear la base de datos desde cero.
--               Incluye la creacion de todas las tablas, datos iniciales
--               necesarios para el funcionamiento del sistema y la
--               insercion del usuario administrador por defecto.
--
-- ADMINISTRADOR POR DEFECTO:
--   Correo:     admin@tienda.com
--   Contraseña: 123456  (hash SHA-256)
--
-- INSTRUCCIONES:
--   1. Abrir phpMyAdmin o tu cliente MySQL favorito.
--   2. Ejecutar este script completo.
--   3. La base de datos 'tienda_mvc' sera creada automaticamente.
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `tienda_mvc`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `tienda_mvc`;

-- Desactivar revision de llaves foraneas para crear tablas en cualquier orden
SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;


-- ============================================================================
-- TABLA: roles
-- Proposito: Niveles de autorizacion (administrador / usuario).
-- ============================================================================

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id`     INT(11)      NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50)  NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_roles_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roles` (`id`, `nombre`) VALUES
  (1, 'administrador'),
  (2, 'usuario');


-- ============================================================================
-- TABLA: usuarios
-- Proposito: Cuentas registradas. Contraseña cifrada con SHA-256.
--
-- Usuario administrador por defecto:
--   correo    : admin@tienda.com
--   contraseña: 123456
--   hash      : 8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92
-- ============================================================================

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id`                   INT(11)      NOT NULL AUTO_INCREMENT,
  `rol_id`               INT(11)      NOT NULL DEFAULT 2,
  `nombre`               VARCHAR(100) NOT NULL,
  `apellido`             VARCHAR(100) NOT NULL,
  `correo`               VARCHAR(100) NOT NULL,
  `password_hash`        VARCHAR(255) NOT NULL,
  `codigo_verificacion`  VARCHAR(10)  DEFAULT NULL,
  `codigo_expiracion`    DATETIME     DEFAULT NULL,
  `fecha_registro`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `telefono`             VARCHAR(20)  DEFAULT NULL,
  `direccion`            TEXT         DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuarios_correo` (`correo`),
  KEY `fk_usuarios_rol` (`rol_id`),
  CONSTRAINT `fk_usuarios_rol`
    FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insercion del usuario ADMINISTRADOR por defecto
INSERT INTO `usuarios`
  (`id`, `rol_id`, `nombre`, `apellido`, `correo`, `password_hash`, `fecha_registro`, `telefono`, `direccion`)
VALUES
  (1, 1, 'Admin', 'Sistema', 'admin@tienda.com',
   '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92',
   NOW(), NULL, NULL);


-- ============================================================================
-- TABLA: categorias
-- Proposito: Agrupa los productos por tipo.
-- ============================================================================

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(100) NOT NULL,
  `descripcion` TEXT         DEFAULT NULL,
  `estado`      TINYINT(1)   DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categorias` (`nombre`, `descripcion`, `estado`) VALUES
  ('Smartphones', 'Teléfonos inteligentes de última generación', 1),
  ('Laptops',     'Computadoras portátiles para trabajo y gaming', 1),
  ('Accesorios',  'Audífonos, cargadores y fundas', 1);


-- ============================================================================
-- TABLA: marcas
-- Proposito: Fabricantes del catálogo de productos.
-- ============================================================================

DROP TABLE IF EXISTS `marcas`;
CREATE TABLE `marcas` (
  `id`       INT(11)      NOT NULL AUTO_INCREMENT,
  `nombre`   VARCHAR(100) NOT NULL,
  `logo_url` VARCHAR(255) DEFAULT NULL,
  `estado`   TINYINT(1)   DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Marcas de ejemplo (logos se asignan al subir imágenes desde el panel admin)
INSERT INTO `marcas` (`nombre`, `estado`) VALUES
  ('Samsung',  1),
  ('Xiaomi',   1),
  ('iPhone',   1),
  ('Tecno',    1),
  ('Infinix',  1),
  ('Huawei',   1),
  ('OPPO',     1);


-- ============================================================================
-- TABLA: referencias
-- Proposito: Testimonios de clientes mostrados en el landing page.
-- ============================================================================

DROP TABLE IF EXISTS `referencias`;
CREATE TABLE `referencias` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `nombre_autor` VARCHAR(100) NOT NULL,
  `comentario`   TEXT         DEFAULT NULL,
  `estrellas`    INT(11)      DEFAULT 5,
  `media_url`    VARCHAR(255) DEFAULT NULL,
  `tipo_media`   ENUM('texto','imagen','video','mixto') DEFAULT 'texto',
  `url_referencia` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================================
-- TABLA: productos
-- Proposito: Catálogo principal — inventario, precios e imágenes.
-- ============================================================================

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id`            INT(11)        NOT NULL AUTO_INCREMENT,
  `categoria_id`  INT(11)        NOT NULL,
  `marca_id`      INT(11)        NOT NULL,
  `referencia_id` INT(11)        DEFAULT NULL,
  `nombre`        VARCHAR(200)   NOT NULL,
  `descripcion`   TEXT           DEFAULT NULL,
  `precio`        DECIMAL(10,2)  NOT NULL,
  `stock`         INT(11)        NOT NULL DEFAULT 0,
  `imagen_url`    TEXT           DEFAULT NULL,
  `estado`        TINYINT(1)     DEFAULT 1,
  `fecha_creacion` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_productos_categoria` (`categoria_id`),
  KEY `idx_productos_marca`     (`marca_id`),
  KEY `fk_productos_referencia` (`referencia_id`),
  CONSTRAINT `fk_productos_categoria`
    FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  CONSTRAINT `fk_productos_marca`
    FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`),
  CONSTRAINT `fk_productos_referencia`
    FOREIGN KEY (`referencia_id`) REFERENCES `referencias` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================================
-- TABLA: carrito
-- Proposito: Almacena temporalmente productos antes del checkout.
-- ============================================================================

DROP TABLE IF EXISTS `carrito`;
CREATE TABLE `carrito` (
  `id`             INT(11)   NOT NULL AUTO_INCREMENT,
  `usuario_id`     INT(11)   NOT NULL,
  `producto_id`    INT(11)   NOT NULL,
  `cantidad`       INT(11)   NOT NULL DEFAULT 1,
  `fecha_agregado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_carrito_usuario`  (`usuario_id`),
  KEY `fk_carrito_producto` (`producto_id`),
  CONSTRAINT `fk_carrito_usuario`
    FOREIGN KEY (`usuario_id`)  REFERENCES `usuarios`  (`id`),
  CONSTRAINT `fk_carrito_producto`
    FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================================
-- TABLA: metodos_pago
-- Proposito: Opciones de pago disponibles en el checkout.
-- ============================================================================

DROP TABLE IF EXISTS `metodos_pago`;
CREATE TABLE `metodos_pago` (
  `id`             INT(11)      NOT NULL AUTO_INCREMENT,
  `tipo`           VARCHAR(50)  NOT NULL,
  `banco`          VARCHAR(100) DEFAULT NULL,
  `numero_cuenta`  VARCHAR(100) DEFAULT NULL,
  `titular`        VARCHAR(100) DEFAULT NULL,
  `estado`         TINYINT(1)   DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `metodos_pago` (`tipo`, `banco`, `numero_cuenta`, `titular`, `estado`) VALUES
  ('PayPhone',       NULL,                NULL,           NULL,             1),
  ('Transferencia',  'Banco Pichincha',   '2208958205',   'Admin Sistema',  1);


-- ============================================================================
-- TABLA: metodos_envio
-- Proposito: Tipos de envíos configurados (Retiro, Servientrega, etc).
-- ============================================================================

DROP TABLE IF EXISTS `metodos_envio`;
CREATE TABLE `metodos_envio` (
  `id`               INT(11)       NOT NULL AUTO_INCREMENT,
  `nombre`           VARCHAR(100)  NOT NULL,
  `costo_base`       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `tiempo_estimado`  VARCHAR(100)  DEFAULT NULL,
  `estado`           TINYINT(1)    DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `metodos_envio` (`nombre`, `costo_base`, `tiempo_estimado`, `estado`) VALUES
  ('Retiro en Tienda', 0.00, 'Inmediato',  1),
  ('Servientregas',    6.00, '48 horas',   1);


-- ============================================================================
-- TABLA: pedidos
-- Proposito: Cabecera de compra — método, total y comprobante de depósito.
-- ============================================================================

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE `pedidos` (
  `id`               INT(11)       NOT NULL AUTO_INCREMENT,
  `usuario_id`       INT(11)       NOT NULL,
  `metodo_pago_id`   INT(11)       NOT NULL,
  `metodo_envio_id`  INT(11)       DEFAULT NULL,
  `costo_envio`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total`            DECIMAL(10,2) NOT NULL,
  `estado`           VARCHAR(50)   DEFAULT 'Pendiente',
  `motivo_rechazo`   TEXT          DEFAULT NULL,
  `fecha_pedido`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comprobante_url`  VARCHAR(255)  DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pedidos_usuario`     (`usuario_id`),
  KEY `fk_pedidos_metodo_pago` (`metodo_pago_id`),
  CONSTRAINT `fk_pedidos_usuario`
    FOREIGN KEY (`usuario_id`)     REFERENCES `usuarios`     (`id`),
  CONSTRAINT `fk_pedidos_metodo_pago`
    FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_pago` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================================
-- TABLA: detalle_pedidos
-- Proposito: Desglose de artículos dentro de cada pedido.
-- ============================================================================

DROP TABLE IF EXISTS `detalle_pedidos`;
CREATE TABLE `detalle_pedidos` (
  `id`               INT(11)       NOT NULL AUTO_INCREMENT,
  `pedido_id`        INT(11)       NOT NULL,
  `producto_id`      INT(11)       NOT NULL,
  `cantidad`         INT(11)       NOT NULL,
  `precio_unitario`  DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_detalle_pedido`   (`pedido_id`),
  KEY `fk_detalle_producto` (`producto_id`),
  CONSTRAINT `fk_detalle_pedido`
    FOREIGN KEY (`pedido_id`)   REFERENCES `pedidos`   (`id`),
  CONSTRAINT `fk_detalle_producto`
    FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================================
-- TABLA: ofertas
-- Proposito: Descuentos temporales por porcentaje para un producto.
-- ============================================================================

DROP TABLE IF EXISTS `ofertas`;
CREATE TABLE `ofertas` (
  `id`                    INT(11)      NOT NULL AUTO_INCREMENT,
  `producto_id`           INT(11)      NOT NULL,
  `descuento_porcentaje`  DECIMAL(5,2) NOT NULL,
  `fecha_inicio`          DATETIME     NOT NULL,
  `fecha_fin`             DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ofertas_producto` (`producto_id`),
  CONSTRAINT `fk_ofertas_producto`
    FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================================
-- TABLA: noticias
-- Proposito: Comunicados, noticias u ofertas tipo blog.
-- ============================================================================

DROP TABLE IF EXISTS `noticias`;
CREATE TABLE `noticias` (
  `id`                 INT(11)      NOT NULL AUTO_INCREMENT,
  `titulo`             VARCHAR(200) NOT NULL,
  `autor`              VARCHAR(100) DEFAULT 'Redacción',
  `contenido`          TEXT         NOT NULL,
  `tipo_media`         ENUM('texto','imagen','video','mixto') DEFAULT 'texto',
  `media_url`          VARCHAR(255) DEFAULT NULL,
  `url_externa`        VARCHAR(255) DEFAULT NULL,
  `imagen_url`         VARCHAR(255) DEFAULT NULL,
  `fecha_publicacion`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================================
-- TABLA: redes_sociales
-- Proposito: Enlaces oficiales mostrados en el footer del sitio.
-- ============================================================================

DROP TABLE IF EXISTS `redes_sociales`;
CREATE TABLE `redes_sociales` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `nombre`       VARCHAR(100) NOT NULL,
  `url_destino`  TEXT         NOT NULL,
  `icono`        VARCHAR(100) NOT NULL,
  `estado`       TINYINT(1)   DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `redes_sociales` (`nombre`, `url_destino`, `icono`, `estado`) VALUES
  ('Facebook',  'https://www.facebook.com/', 'facebook',  1),
  ('Whatsapp',  'https://w.app/',            'whatsapp',  1);


-- ============================================================================
-- TABLA: reglas_envio
-- Proposito: Reglas especiales como envío gratis por monto mínimo de carrito.
-- ============================================================================

DROP TABLE IF EXISTS `reglas_envio`;
CREATE TABLE `reglas_envio` (
  `id`                     INT(11)       NOT NULL AUTO_INCREMENT,
  `nombre`                 VARCHAR(100)  NOT NULL,
  `monto_minimo_carrito`   DECIMAL(10,2) NOT NULL,
  `costo_fijo`             DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `estado`                 TINYINT(1)    DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `reglas_envio` (`nombre`, `monto_minimo_carrito`, `costo_fijo`, `estado`) VALUES
  ('Envío Gratis > $899.99', 899.99, 0.00, 1);


-- ============================================================================
-- TABLA: configuracion_general
-- Proposito: Configuraciones clave-valor globales de la tienda.
-- ============================================================================

DROP TABLE IF EXISTS `configuracion_general`;
CREATE TABLE `configuracion_general` (
  `id`    INT(11)      NOT NULL AUTO_INCREMENT,
  `clave` VARCHAR(100) NOT NULL,
  `valor` TEXT         NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_config_general_clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `configuracion_general` (`clave`, `valor`) VALUES
  ('facebook_url',   ''),
  ('instagram_url',  ''),
  ('whatsapp_numero',''),
  ('Logo',           '');


-- ============================================================================
-- TABLA: configuracion_payphone
-- Proposito: Credenciales de PayPhone para procesamiento de pagos en línea.
-- ============================================================================

DROP TABLE IF EXISTS `configuracion_payphone`;
CREATE TABLE `configuracion_payphone` (
  `id`                  INT(11)       NOT NULL AUTO_INCREMENT,
  `token_autorizacion`  VARCHAR(1000) NOT NULL,
  `store_id`            VARCHAR(255)  NOT NULL,
  `ambiente`            ENUM('Pruebas','Prouccion') DEFAULT 'Pruebas',
  `actualizado_en`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Registro inicial vacío — completar desde el panel admin
INSERT INTO `configuracion_payphone` (`token_autorizacion`, `store_id`, `ambiente`) VALUES
  ('Ingresa_tu_token_aqui', 'Ingresa_tu_tienda_aqui', 'Pruebas');


-- ============================================================================
-- TABLA: configuracion_whatsapp
-- Proposito: Número y nombre del admin para notificaciones por WhatsApp.
-- ============================================================================

DROP TABLE IF EXISTS `configuracion_whatsapp`;
CREATE TABLE `configuracion_whatsapp` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `numero`       VARCHAR(20)  NOT NULL,
  `nombre_admin` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `configuracion_whatsapp` (`numero`, `nombre_admin`) VALUES
  ('593000000000', 'Administrador');


-- ============================================================================
-- TABLA: configuracion_whatsapp_api
-- Proposito: Credenciales de UltraMsg para mensajes automatizados.
-- ============================================================================

DROP TABLE IF EXISTS `configuracion_whatsapp_api`;
CREATE TABLE `configuracion_whatsapp_api` (
  `id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `instance_id`   VARCHAR(100) DEFAULT NULL,
  `mensaje_token` VARCHAR(255) DEFAULT NULL,
  `api_url`       VARCHAR(255) DEFAULT 'https://api.ultramsg.com',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Registro inicial vacío — completar desde el panel admin
INSERT INTO `configuracion_whatsapp_api` (`instance_id`, `mensaje_token`, `api_url`) VALUES
  ('tu_instance_id', 'tu_token_aqui', 'https://api.ultramsg.com');


-- ============================================================================
-- TABLA: configuracion_precios_servicio
-- Proposito: Multiplicadores/montos de cobro por urgencia en servicio técnico.
-- ============================================================================

DROP TABLE IF EXISTS `configuracion_precios_servicio`;
CREATE TABLE `configuracion_precios_servicio` (
  `id`          INT(11)       NOT NULL AUTO_INCREMENT,
  `concepto`    VARCHAR(100)  NOT NULL,
  `tipo`        ENUM('Monto','Multiplicador') DEFAULT 'Multiplicador',
  `valor`       DECIMAL(10,2) NOT NULL,
  `descripcion` VARCHAR(255)  DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `configuracion_precios_servicio` (`concepto`, `tipo`, `valor`, `descripcion`) VALUES
  ('Base',    'Monto',         20.00, 'Precio base del servicio'),
  ('Urgente', 'Monto',         30.00, 'Extra por prioridad Urgente'),
  ('Alta',    'Monto',         15.00, 'Extra por prioridad Alta'),
  ('24h',     'Multiplicador',  2.50, 'Multiplicador si es menos de 24h'),
  ('48h',     'Multiplicador',  1.50, 'Multiplicador si es menos de 48h');


-- ============================================================================
-- TABLA: servicios_mantenimiento
-- Proposito: Listado de servicios ofertados (limpieza, cambio de display, etc).
-- ============================================================================

DROP TABLE IF EXISTS `servicios_mantenimiento`;
CREATE TABLE `servicios_mantenimiento` (
  `id`             INT(11)       NOT NULL AUTO_INCREMENT,
  `nombre`         VARCHAR(100)  NOT NULL,
  `descripcion`    TEXT          NOT NULL,
  `icono`          VARCHAR(50)   DEFAULT 'fas fa-tools',
  `precio_desde`   DECIMAL(10,2) DEFAULT NULL,
  `estado`         TINYINT(1)    DEFAULT 1,
  `fecha_creacion` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `servicios_mantenimiento` (`nombre`, `descripcion`, `icono`, `precio_desde`, `estado`) VALUES
  ('Cambio de Pantalla',     'Reparación de vidrios rotos y pantallas LCD o OLED con repuestos originales.', 'fas fa-mobile-alt',   45.00, 1),
  ('Cambio de Batería',      '¿Tu equipo ya no dura? Cambiamos tu batería vieja por una nueva de alto rendimiento.', 'fas fa-battery-full',  25.00, 1),
  ('Limpieza y Mantenimiento','Eliminación de polvo interno, cambio de pasta térmica y optimización de hardware.', 'fas fa-broom',         30.00, 1),
  ('Recuperación de Datos',  '¿Perdiste tu información? Recuperamos fotos y archivos de discos duros y memorias.', 'fas fa-hdd',           60.00, 1),
  ('Soporte de Software',    'Instalación de sistemas, eliminación de virus y optimización de rendimiento.',       'fas fa-laptop-code',   20.00, 1);


-- ============================================================================
-- TABLA: servicio_tecnico
-- Proposito: Solicitudes de reparaciones enviadas por clientes.
-- ============================================================================

DROP TABLE IF EXISTS `servicio_tecnico`;
CREATE TABLE `servicio_tecnico` (
  `id`                   INT(11)       NOT NULL AUTO_INCREMENT,
  `usuario_id`           INT(11)       NOT NULL,
  `dispositivo`          VARCHAR(100)  DEFAULT NULL,
  `descripcion_problema` TEXT          NOT NULL,
  `prioridad`            ENUM('Baja','Media','Alta','Urgente') DEFAULT 'Media',
  `fecha_inicio`         DATETIME      DEFAULT NULL,
  `fecha_fin`            DATETIME      DEFAULT NULL,
  `precio_estimado`      DECIMAL(10,2) DEFAULT NULL,
  `estado`               VARCHAR(50)   DEFAULT 'Pendiente',
  `fecha_solicitud`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_entregado`      DATETIME      DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_servicio_usuario` (`usuario_id`),
  CONSTRAINT `fk_servicio_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================================
-- TABLA: ubicacion_local
-- Proposito: Coordenadas del local físico para el mapa del home.
-- ============================================================================

DROP TABLE IF EXISTS `ubicacion_local`;
CREATE TABLE `ubicacion_local` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(100) NOT NULL,
  `direccion`   TEXT         NOT NULL,
  `latitud`     VARCHAR(50)  DEFAULT NULL,
  `longitud`    VARCHAR(50)  DEFAULT NULL,
  `iframe_mapa` TEXT         DEFAULT NULL,
  `telefono`    VARCHAR(50)  DEFAULT NULL,
  `horario`     VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Registro inicial — actualizar desde el panel admin
INSERT INTO `ubicacion_local` (`nombre`, `direccion`, `latitud`, `longitud`) VALUES
  ('Local Principal', 'Ingresa la dirección aquí', '-1.041939', '-78.590646');


-- ============================================================================
-- TABLA: auditoria_pedidos
-- Proposito: Historial de cambios de estado de cada pedido.
-- ============================================================================

DROP TABLE IF EXISTS `auditoria_pedidos`;
CREATE TABLE `auditoria_pedidos` (
  `id`               INT(11)     NOT NULL AUTO_INCREMENT,
  `pedido_id`        INT(11)     NOT NULL,
  `accion`           VARCHAR(50) NOT NULL,
  `estado_anterior`  VARCHAR(50) DEFAULT NULL,
  `estado_nuevo`     VARCHAR(50) DEFAULT NULL,
  `fecha_accion`     TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================================
-- TABLA: auditoria_usuarios
-- Proposito: Historial de acciones realizadas por los usuarios.
-- ============================================================================

DROP TABLE IF EXISTS `auditoria_usuarios`;
CREATE TABLE `auditoria_usuarios` (
  `id`           INT(11)     NOT NULL AUTO_INCREMENT,
  `usuario_id`   INT(11)     NOT NULL,
  `accion`       VARCHAR(50) NOT NULL,
  `detalle`      TEXT        DEFAULT NULL,
  `fecha_accion` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================================
-- Reactivar revision de llaves foraneas
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================================
-- FIN DEL SCRIPT
-- ============================================================================
-- Tablas creadas:
--   1.  roles
--   2.  usuarios                  ← incluye admin por defecto
--   3.  categorias
--   4.  marcas
--   5.  referencias
--   6.  productos
--   7.  carrito
--   8.  metodos_pago
--   9.  metodos_envio
--  10.  pedidos
--  11.  detalle_pedidos
--  12.  ofertas
--  13.  noticias
--  14.  redes_sociales
--  15.  reglas_envio
--  16.  configuracion_general
--  17.  configuracion_payphone
--  18.  configuracion_whatsapp
--  19.  configuracion_whatsapp_api
--  20.  configuracion_precios_servicio
--  21.  servicios_mantenimiento
--  22.  servicio_tecnico
--  23.  ubicacion_local
--  24.  auditoria_pedidos
--  25.  auditoria_usuarios
-- ============================================================================
