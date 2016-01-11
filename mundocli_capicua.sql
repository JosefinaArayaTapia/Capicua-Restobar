-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 11, 2016 at 04:27 PM
-- Server version: 5.5.45-cll-lve
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mundocli_capicua`
--

-- --------------------------------------------------------

--
-- Table structure for table `carta_productos`
--

CREATE TABLE IF NOT EXISTS `carta_productos` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) CHARACTER SET latin1 NOT NULL,
  `descripcion` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `precio` int(11) NOT NULL,
  `fecha_publicacion` datetime NOT NULL,
  `fecha_vigencia` datetime NOT NULL,
  `estado_web` int(11) NOT NULL,
  `id_tipo_producto` int(11) NOT NULL,
  `imagen` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `puntos_producto` int(11) NOT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `id_tipo_producto_FK` (`id_tipo_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

--
-- Dumping data for table `carta_productos`
--

INSERT INTO `carta_productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `fecha_publicacion`, `fecha_vigencia`, `estado_web`, `id_tipo_producto`, `imagen`, `puntos_producto`) VALUES
(11, 'Churrasco Italiano', 'Pan amasado y verduras frescas', 2900, '2013-06-05 16:40:56', '2013-07-31 16:40:59', 1, 1, 'churrasco-italiano.png', 29),
(12, 'Ave Palta', 'Con mayonesa casera', 1590, '2013-06-05 16:42:17', '2013-07-31 16:42:20', 1, 1, 'ave_palta.jpg', 15),
(13, 'Barros Jarpa', 'Con Aji Verde', 1890, '2013-06-05 16:42:56', '2013-07-31 16:43:00', 1, 1, 'barros-jarpa.jpg', 18),
(14, 'Camaron Queso', 'Los mejores Camarones ', 690, '2013-06-05 16:43:55', '2013-07-31 16:43:58', 1, 2, 'camaron-queso.jpg', 6),
(16, 'Queso', 'El mejor Queso de la zona', 590, '2013-06-05 16:44:34', '2013-07-31 16:44:37', 1, 2, 'queso.jpg', 5),
(18, 'Pino', 'Con carne Picada', 890, '2013-06-05 16:45:08', '2013-07-31 16:45:12', 1, 2, 'empanada-pino.jpg', 8),
(20, 'Flan de Chocolate', 'Delicioso', 690, '2013-06-05 16:45:57', '2013-07-31 16:46:02', 1, 3, 'flan-chocolate.jpg', 6),
(21, 'Macedonio de Frutas', 'Con Crema', 590, '2013-06-05 16:46:43', '2013-07-31 16:46:46', 1, 3, 'macedonia.jpg', 5),
(22, 'Coca Cola', 'Individual, 250 ML', 890, '2013-06-05 16:47:52', '2013-07-31 16:47:56', 1, 4, 'coca-vaso.jpg', 8),
(29, 'Sprite', 'Individual, 250 ML', 890, '2013-06-05 16:49:02', '2013-07-31 16:49:05', 1, 4, 'sprite-1lt.jpg', 8),
(30, 'Fanta', 'Individual, 250 ML', 890, '2013-06-05 16:49:31', '2013-07-31 16:49:34', 1, 4, 'fanta-1lt.jpg', 8),
(31, 'Corona', 'Individual, 500 ML', 980, '2013-06-05 16:51:00', '2013-07-31 16:51:03', 1, 5, 'corona.jpg', 9),
(32, 'Austral', 'Individual, 500 ML', 1000, '2013-06-05 16:51:41', '2013-07-31 16:51:45', 1, 5, 'austral.jpg', 10),
(33, 'Escudo', 'Individual, 500 ML', 890, '2013-06-05 16:52:15', '2013-07-31 16:52:18', 1, 5, 'escudo-1lt.png', 8),
(34, 'Gato Negro ', 'Botella, 750 ML', 1250, '2013-06-05 16:56:09', '2013-07-31 16:56:13', 1, 6, 'gato-negro.jpg', 12),
(36, '120 Cabernet', 'Botella, 750 ML', 1450, '2013-06-05 16:57:11', '2013-07-31 16:57:15', 1, 6, '120.jpg', 14),
(37, 'Casillero del Diablo', 'Botella, 750 ML', 1980, '2013-06-05 16:57:50', '2013-07-31 16:57:53', 1, 6, 'casillero-diablo.jpg', 19),
(38, 'Chorrillana', 'Con Carne, Cebolla y Huevo', 3990, '2013-06-05 16:59:17', '2013-07-31 16:59:20', 0, 8, 'chorrillana.jpg', 39),
(40, 'Pollo con Papas', 'Papas fritas con bebida ', 2990, '2013-06-05 17:00:11', '2013-07-31 17:00:15', 0, 7, 'pollo-papas.jpg', 29),
(41, 'Completo mas vaso de bebida', 'Completo o Italiano', 1150, '2013-06-05 17:01:25', '2013-07-31 17:01:29', 0, 7, 'completo-bebida.jpg', 115),
(43, 'Carne Mechada con Tallarines', 'Con salsa Boloñesa', 2990, '2013-06-05 17:02:17', '2013-07-31 17:02:20', 0, 8, 'carne-tallarines.jpg', 29),
(47, 'Pure con Pollo', 'Pollo al jugo y pure de papas mas bebida', 2990, '2013-06-05 17:03:23', '2013-07-31 17:03:25', 0, 8, 'pollo-pure.jpg', 29),
(48, 'Arroz con Bistec', 'Arroz graneado con bebida', 2990, '2013-06-05 17:04:03', '2013-07-31 17:04:05', 0, 8, 'arroz-bistec.jpg', 29),
(50, 'Arroz con Guatita', 'Guatitas a la salsa', 3990, '2013-06-05 17:04:48', '2013-07-31 17:04:53', 0, 9, 'arroz-guatitas.jpg', 39),
(52, 'Estofado de Carne', 'Con bebida y ensalada', 3990, '2013-06-05 17:06:07', '2013-07-31 17:04:53', 0, 9, 'estofado-carne.jpg', 39),
(53, 'Cazuela de Pollo', 'Con ensalada y bebida', 3990, '2013-06-05 17:06:46', '2013-07-31 17:06:49', 0, 9, 'cazuela-pollo.jpg', 39),
(54, 'Ensalada Cesar', 'Con Bebida', 2990, '2013-06-05 17:07:18', '2013-07-31 17:07:20', 0, 10, 'ensalada-cesar.jpg', 29),
(55, 'Arroz con 1/4 de Pollo', 'Con Bebida', 2990, '2013-06-05 17:08:09', '2013-07-31 17:08:12', 0, 10, 'arroz-pollo-14.jpg', 29),
(56, 'Pescado con Ensalada', 'Con Jugo', 2590, '2013-06-05 17:08:48', '2013-07-31 17:08:51', 0, 10, 'pescado-ensalada.jpg', 25),
(57, 'Duraznos con Crema', 'Conserva', 590, '2013-06-05 17:10:28', '2013-07-31 17:10:31', 1, 3, 'duraznos-crema.jpg', 4),
(59, 'tiramisu', 'tiramusu', 1390, '2013-06-05 17:04:48', '2013-06-05 17:04:48', 1, 3, 'Tiramisu.jpg', 139),
(61, 'Botellon Gato', '1,5 Lts', 6900, '2013-06-05 17:04:48', '2013-06-05 17:04:48', 1, 6, 'botellon-gato.jpg', 690),
(63, 'Sopa de Caracol', 'Sopita', 1990, '2013-06-27 17:15:03', '2013-06-27 17:15:03', 1, 11, 'sopa-caracol.jpg', 990),
(64, 'Pure con Chuletas', 'Menu', 4300, '2013-06-28 12:51:08', '2013-06-28 12:51:08', 0, 7, 'pure_chuletas.jpg', 430);

-- --------------------------------------------------------

--
-- Table structure for table `comentarios`
--

CREATE TABLE IF NOT EXISTS `comentarios` (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `cuerpo` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `id_publicacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_comentario`),
  KEY `id_publicidad_FK` (`id_publicacion`),
  KEY `id_usuario_FK` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `comentarios`
--

INSERT INTO `comentarios` (`id_comentario`, `cuerpo`, `fecha`, `id_publicacion`, `id_usuario`) VALUES
(13, 'Valido solo con cupon', '2013-06-21 14:26:04', 15, 1),
(14, 'Felicitaciones Sr Cristian', '2013-06-27 16:34:38', 2, 1),
(15, 'espero ir, me imagino que estara muy buena ', '2013-07-12 17:41:23', 14, 18),
(16, 'holi', '2013-07-15 17:19:39', 14, 19),
(17, 'Pie de Limon Gratis a las 10 primeras personas !', '2013-07-26 13:12:08', 20, 1),
(18, 'YO quiero pie de Limon Gratis !', '2013-07-26 13:13:13', 20, 6),
(19, 'primer comentario', '2013-08-06 11:50:06', 29, 6);

-- --------------------------------------------------------

--
-- Table structure for table `estado_reservas`
--

CREATE TABLE IF NOT EXISTS `estado_reservas` (
  `id_estado_reserva` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_estado` varchar(45) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id_estado_reserva`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `estado_reservas`
--

INSERT INTO `estado_reservas` (`id_estado_reserva`, `nombre_estado`) VALUES
(1, 'Pendiente'),
(2, 'Aprobada'),
(3, 'Rechazada');

-- --------------------------------------------------------

--
-- Table structure for table `publicaciones`
--

CREATE TABLE IF NOT EXISTS `publicaciones` (
  `id_publicacion` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `contenido` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `imagen` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `fecha_publicacion` datetime NOT NULL,
  `fecha_vigencia` datetime DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tipo_publicacion` int(11) NOT NULL,
  PRIMARY KEY (`id_publicacion`),
  KEY `id_usuario_FK` (`id_usuario`),
  KEY `id_tipo_publicacion_FK` (`id_tipo_publicacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `publicaciones`
--

INSERT INTO `publicaciones` (`id_publicacion`, `titulo`, `contenido`, `imagen`, `fecha_publicacion`, `fecha_vigencia`, `id_usuario`, `id_tipo_publicacion`) VALUES
(2, 'Matrimonio Sr Cristian', '', 'alianzas.jpg', '2013-06-20 16:27:50', '2013-06-27 00:00:00', 1, 1),
(14, 'Cumbre del Rock Nacional', 'Piscola a 1000', 'img_0089.jpg', '2013-06-20 19:06:45', '2013-06-30 00:00:00', 1, 2),
(15, 'Promocion dia del padre', 'Gratis todo el dia Domingo', 'img_0341.jpg', '2013-06-20 19:29:08', '2013-06-27 00:00:00', 1, 1),
(16, 'Dia Ciclista', 'Cerveza Gratis', 'fx_c_red_white.jpg', '2013-07-01 12:13:23', '2013-07-31 00:00:00', 1, 1),
(17, 'Partido Chile vs Argentina', '18.00 ', 'default_publicacion.jpg', '2013-07-01 12:27:57', '2013-07-31 00:00:00', 1, 1),
(18, 'Promocion dia del padre', 'promo 2', 'futbol.jpg', '2013-07-01 12:36:10', '2013-07-17 00:00:00', 1, 1),
(19, 'Matrimonio Ruiz Perez', 'Evento Privado', 'evento-privado_v.png', '2013-07-01 12:40:44', '2013-07-31 00:00:00', 1, 1),
(20, 'Pie de Limon Gratis', 'Pie', 'piee.jpg', '2013-07-01 12:53:42', '2013-07-29 00:00:00', 1, 1),
(28, 'Partido Alexis Sanchez', 'Partido por Cable', 'alexis.jpg', '2013-07-26 13:37:49', '2013-07-27 00:00:00', 1, 1),
(29, 'Titulo prueba', 'Hola', 'jellyfish.jpg', '2013-08-06 11:28:15', '2013-08-07 00:00:00', 1, 2),
(30, 'Activacion Sitio', 'ACTIVAMOS EL SITIO NUEVAMENTE !', 'tiramisu.jpg', '2014-02-11 21:16:21', '2014-02-28 00:00:00', 1, 1),
(31, 'Titulacion', 'Prueba', 'imagen 001.jpg', '2014-06-14 22:07:48', '2014-06-16 00:00:00', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `rel_carta_ventas`
--

CREATE TABLE IF NOT EXISTS `rel_carta_ventas` (
  `id_rel_venta_producto` int(11) NOT NULL AUTO_INCREMENT,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad_producto` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_venta_producto`),
  KEY `id_venta_FK` (`id_venta`),
  KEY `id_producto_FK` (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `rel_carta_ventas`
--

INSERT INTO `rel_carta_ventas` (`id_rel_venta_producto`, `id_venta`, `id_producto`, `cantidad_producto`) VALUES
(1, 1, 11, 1),
(2, 1, 12, 1),
(3, 2, 11, 11),
(4, 3, 11, 3),
(5, 3, 12, 5),
(6, 3, 13, 5),
(7, 4, 22, 3),
(8, 4, 29, 2),
(9, 4, 43, 6),
(10, 4, 47, 4),
(11, 4, 48, 3),
(12, 5, 48, 23),
(13, 6, 11, 4),
(14, 7, 13, 26),
(15, 8, 11, 2),
(16, 8, 12, 2),
(17, 8, 32, 3),
(18, 8, 37, 3),
(19, 9, 18, 2),
(20, 9, 38, 4),
(21, 9, 41, 2),
(22, 10, 18, 2),
(23, 10, 38, 4),
(24, 10, 41, 2),
(25, 11, 11, 1),
(26, 11, 12, 1),
(27, 12, 18, 2),
(28, 12, 38, 4),
(29, 12, 41, 2),
(30, 13, 18, 2),
(31, 13, 38, 4),
(32, 13, 41, 2),
(33, 14, 18, 2),
(34, 14, 38, 4),
(35, 14, 41, 2),
(36, 15, 11, 2),
(38, 17, 11, 7),
(39, 17, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE IF NOT EXISTS `reservas` (
  `id_reserva` int(11) NOT NULL AUTO_INCREMENT,
  `cantidad_personas` int(11) NOT NULL,
  `fecha_realizacion` datetime NOT NULL,
  `fecha_reserva` datetime NOT NULL,
  `observacion` varchar(150) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_estado_reserva` int(11) NOT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `id_usuario_FK` (`id_usuario`),
  KEY `id_estado_reserva_FK` (`id_estado_reserva`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `cantidad_personas`, `fecha_realizacion`, `fecha_reserva`, `observacion`, `id_usuario`, `id_estado_reserva`) VALUES
(1, 2, '2013-06-21 00:30:58', '2013-06-29 00:00:00', 'cumpleaños', 2, 3),
(2, 12, '2013-06-24 12:19:26', '2013-06-28 00:00:00', 'cumpleaños anita', 6, 2),
(3, 4, '2013-07-12 17:38:30', '2013-07-23 00:00:00', 'cumplea??os de amigo', 18, 3),
(4, 4, '2013-07-15 17:18:19', '2013-07-23 00:00:00', 'cumplea??os de un amigo', 19, 3),
(5, 1, '2013-07-26 13:20:13', '2013-08-13 00:00:00', 'cumpleaños y previa', 22, 3),
(6, 4, '2014-06-14 20:06:04', '2014-06-17 00:00:00', 'Mesa pasa 3', 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_productos`
--

CREATE TABLE IF NOT EXISTS `tipo_productos` (
  `id_tipo_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_tipo` varchar(45) NOT NULL,
  `tipo_menu` int(11) NOT NULL,
  `visibilidad` int(1) NOT NULL,
  PRIMARY KEY (`id_tipo_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tipo_productos`
--

INSERT INTO `tipo_productos` (`id_tipo_producto`, `nombre_tipo`, `tipo_menu`, `visibilidad`) VALUES
(1, 'Sandwich', 0, 1),
(2, 'Empanadas', 0, 0),
(3, 'Postres', 0, 1),
(4, 'Bebidas', 0, 1),
(5, 'Cervezas', 0, 1),
(6, 'Vinos', 0, 1),
(7, 'Menu Capicua', 1, 1),
(8, 'Menu Premium', 1, 1),
(9, 'Menu del Dia', 1, 1),
(10, 'Menu Estudiante', 1, 1),
(11, 'sopas', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_publicaciones`
--

CREATE TABLE IF NOT EXISTS `tipo_publicaciones` (
  `id_tipo_publicacion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_tipo` varchar(45) NOT NULL,
  `visibilidad` int(1) NOT NULL,
  PRIMARY KEY (`id_tipo_publicacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tipo_publicaciones`
--

INSERT INTO `tipo_publicaciones` (`id_tipo_publicacion`, `nombre_tipo`, `visibilidad`) VALUES
(1, 'Evento', 1),
(2, 'Musical', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) CHARACTER SET latin1 NOT NULL,
  `apellido` varchar(45) CHARACTER SET latin1 NOT NULL,
  `email` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `password` varchar(45) CHARACTER SET latin1 NOT NULL,
  `telefono` int(11) DEFAULT NULL,
  `domicilio` varchar(200) DEFAULT NULL,
  `foto_perfil` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `puntos_acumulados` int(11) NOT NULL,
  `visibilidad` int(1) NOT NULL,
  `tipo_usuario` int(11) NOT NULL,
  `permiso` int(11) NOT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `ultimo_acceso` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `email`, `password`, `telefono`, `domicilio`, `foto_perfil`, `puntos_acumulados`, `visibilidad`, `tipo_usuario`, `permiso`, `fecha_alta`, `ultimo_acceso`) VALUES
(1, 'Juan', 'Perez', 'bascu26@hotmail.com', '0798787abb8968bdf66c3865758b54e8', 3456789, 'San Antonio', 'super_admin_default.png', 0, 0, 1, 1, '2013-05-30 22:58:35', '2014-06-18 22:46:53'),
(2, 'Jose', 'Araya', 'Jose@gmail.com', '0798787abb8968bdf66c3865758b54e8', 3456789, 'balmaceda  2914', 'default_user.png', 608, 0, 2, 0, '2013-06-05 21:15:23', '2013-06-05 21:15:23'),
(3, 'Ana', 'Paredes', 'anaparedesg@gmail.com', '0798787abb8968bdf66c3865758b54e8', 456789, 'balmaceda 7890976', '20140513_174424.jpg', 826, 1, 2, 0, '2013-06-05 22:51:13', '2014-06-18 22:48:34'),
(4, 'Luisa', 'Gonzalez', 'anita.lcbl@gmail.com', '0798787abb8968bdf66c3865758b54e8', 45678234, 'Balmaceda  2914', 'default_user.png', 155, 1, 2, 0, '2013-06-19 15:42:30', '2013-06-19 15:42:30'),
(6, 'Josefina', 'Araya', 'araya.tapia.j@outlook.com', '0798787abb8968bdf66c3865758b54e8', 23456789, 'balmaceda  2914', 'oveja linda.jpg', 4038, 1, 2, 0, '2013-06-24 11:05:23', '2015-05-14 16:06:08'),
(7, 'Anyelina', 'Bascunan', 'a.bascunanl@gmail.com', 'c6a4044254922564d4d1df659394eee3', 0, '', 'default_user.png', 0, 1, 2, 0, '2013-06-24 16:52:43', '2013-06-24 16:52:43'),
(9, 'Juancamilo', 'quintanilla', 'juank_055@hotmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 12344556, 'bucaramanga', 'img_3034.jpg', 0, 1, 2, 0, '2013-06-24 22:36:11', '2013-06-24 22:36:11'),
(18, 'Nikole', 'caballeria', 'niqole.belen@hotmail.com', '5be11898f3923796521a0bce0d889ce9', 93330843, 'del manzano block 21 entrada 3293', 'tulips.jpg', 0, 1, 2, 0, '2013-07-12 17:34:26', '2013-07-12 17:41:36'),
(19, 'Paulina', 'aravena', 'like.paulina@hotmail.com', 'ea1d6d44a4deae8f6721b8d2e459b83e', 82128218, 'pasaje la caleta  #50 mirador de re??aca', 'futbol.jpg', 0, 1, 2, 0, '2013-07-15 17:15:12', '2013-07-15 17:19:57'),
(21, 'Andrea', 'perez', 'josefina@ekekomensajeros.cl', '0798787abb8968bdf66c3865758b54e8', NULL, NULL, 'fotoadmin.png', 0, 1, 1, 2, '2013-07-26 10:44:27', '2013-07-26 12:51:17'),
(22, 'Nicol', 'Lopez', 'nicol014_@hotmail.com', 'e5e5551b5981cfe2b8eea36c70747b71', 9999999, 'La Cruz', 'nicol.jpg', 0, 1, 2, 0, '2013-07-26 13:03:16', '2013-07-26 13:20:23'),
(23, 'Josefina', 'Araya', 'josefina.araya@transvip.cl', '352c2b5ba1663b0545e2a11502485731', 0, '', 'default_user.png', 0, 1, 2, 0, '2014-06-18 21:23:24', '2014-06-18 21:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(11) DEFAULT NULL,
  `mesa` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `puntos_venta` int(11) NOT NULL,
  `descuento` int(11) DEFAULT NULL,
  `observacion` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `id_usuario_FK` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id_venta`, `total`, `mesa`, `fecha`, `puntos_venta`, `descuento`, `observacion`, `id_usuario`) VALUES
(1, 3580, 5, '2013-06-05 23:27:04', 0, 0, 'Primera Venta', 2),
(2, 21890, 7, '2013-06-05 23:28:38', 0, 0, 'Segunda venta', 3),
(3, 23370, 8, '2013-06-05 23:39:42', 0, 0, '3 venta', 2),
(4, 43320, 19, '2013-06-05 23:42:31', 0, 0, 'venta', 3),
(5, 68770, 7, '2013-06-05 23:44:37', 0, 0, 'holi', 3),
(6, 6960, 9, '2013-06-06 00:32:56', 0, 1000, 'desucneto', 3),
(7, 44640, 5, '2013-06-21 11:24:50', 0, 4500, '4500 de descuento', 2),
(8, 16100, 1, '2013-06-24 16:48:30', 0, 0, '', 4),
(9, 20820, 3, '2013-07-03 21:42:45', 0, 900, 'Se realizo descuento', 2),
(10, 20820, 3, '2013-07-03 23:03:11', 0, 900, 'Descuento realizado', 2),
(11, 3580, 2, '2013-07-04 01:12:11', 0, 0, '', 2),
(12, 21720, 3, '2013-07-05 22:55:54', 0, 0, 'no', 2),
(13, 20820, 3, '2013-07-15 11:33:39', 0, 900, 'se hizo descuento ', 2),
(14, 21720, 3, '2013-07-15 17:33:57', 0, 0, 'descuento no realizado', 2),
(15, 3600, 8, '2013-08-06 11:23:53', 0, 1000, 'desc', 6),
(17, 21890, 2, '2014-06-18 22:45:15', 218, 0, 'Sin descuento', 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carta_productos`
--
ALTER TABLE `carta_productos`
  ADD CONSTRAINT `id_tipo_producto` FOREIGN KEY (`id_tipo_producto`) REFERENCES `tipo_productos` (`id_tipo_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `fk_Comentarios_Publicidades1` FOREIGN KEY (`id_publicacion`) REFERENCES `publicaciones` (`id_publicacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Comentarios_Usuarios1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `fk_Publicaciones_Tipo_publicaciones1` FOREIGN KEY (`id_tipo_publicacion`) REFERENCES `tipo_publicaciones` (`id_tipo_publicacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Publicidades_Usuarios1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `rel_carta_ventas`
--
ALTER TABLE `rel_carta_ventas`
  ADD CONSTRAINT `fk_Carta_productos_has_Ventas_Carta_productos1` FOREIGN KEY (`id_producto`) REFERENCES `carta_productos` (`id_producto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Carta_productos_has_Ventas_Ventas1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_Reservas_Estado_reservas1` FOREIGN KEY (`id_estado_reserva`) REFERENCES `estado_reservas` (`id_estado_reserva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Reservas_Usuarios1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_Ventas_Usuarios1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
