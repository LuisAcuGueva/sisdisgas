-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-12-2020 a las 23:28:43
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `sisdisgas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `binnacle`
--

CREATE TABLE IF NOT EXISTS `binnacle` (
`id` int(10) unsigned NOT NULL,
  `action` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `recordid` int(10) unsigned NOT NULL,
  `table` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concepto`
--

CREATE TABLE IF NOT EXISTS `concepto` (
`id` int(10) unsigned NOT NULL,
  `concepto` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `concepto`
--

INSERT INTO `concepto` (`id`, `concepto`, `tipo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'APERTURA DE CAJA', 0, '2018-10-03 05:00:00', '2018-10-03 05:00:00', NULL),
(2, 'CIERRE DE CAJA', 1, '2018-10-03 05:00:00', '2018-10-03 05:00:00', NULL),
(3, 'PAGO DE CLIENTE', 0, '2018-10-03 05:00:00', '2018-10-03 05:00:00', NULL),
(4, 'PAGO AL PROVEEDOR POR COMPRA', 1, '2018-10-03 05:00:00', '2018-10-03 05:00:00', NULL),
(5, 'REPARACIÓN DE LA MOTO', 1, '2020-10-15 05:00:00', '2020-10-15 05:00:00', NULL),
(6, 'INGRESO POR VUELTO', 0, '2019-02-21 20:58:04', '2019-02-21 20:58:04', NULL),
(7, 'COMIDA (DESAYUNO | ALMUERZO)', 1, '2020-10-15 05:00:00', '2020-10-15 05:00:00', NULL),
(8, 'OTROS EGRESOS', 1, '2018-10-03 05:00:00', '2018-10-03 05:00:00', NULL),
(9, 'COMBUSTIBLE PARA MOTO', 1, '2020-10-15 05:00:00', '2020-10-15 05:00:00', NULL),
(10, 'OTROS INGRESOS', 0, '2018-10-03 05:00:00', '2018-10-03 05:00:00', NULL),
(11, 'INGRESO DE ALMACÉN', 0, '2020-10-27 05:00:00', '2020-10-27 05:00:00', NULL),
(12, 'VUELTO PARA TURNO DEL REPARTIDOR', 1, '2020-07-08 22:11:49', '2020-07-16 22:11:18', NULL),
(13, 'INGRESO DE PEDIDOS DEL REPARTIDOR', 0, '2020-07-23 04:58:22', '2020-07-23 04:58:22', NULL),
(14, 'CIERRE TURNO DEL REPARTIDOR', 0, '2020-07-23 04:58:56', '2020-07-23 04:58:56', NULL),
(15, 'VUELTO AL INICIAR TURNO', 1, '2020-07-23 05:14:42', '2020-07-23 05:14:42', NULL),
(16, 'PAGO DE DEUDA DE PEDIDO A CRÉDITO', 0, '2020-08-08 03:25:15', '2020-08-08 03:25:54', NULL),
(17, 'INGRESO DE CAJA DE OTRA SUCURSAL', 0, '2020-08-16 02:44:24', '2020-08-16 02:44:24', NULL),
(18, 'SALIDA DE ALMACÉN', 1, '2020-10-27 05:00:00', '2020-10-20 05:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_mov_almacen`
--

CREATE TABLE IF NOT EXISTS `detalle_mov_almacen` (
`id` int(10) unsigned NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_envase` decimal(10,2) DEFAULT NULL,
  `cantidad_envase` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `movimiento_id` int(10) unsigned NOT NULL,
  `producto_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_mov_almacen`
--

INSERT INTO `detalle_mov_almacen` (`id`, `precio`, `cantidad`, `precio_envase`, `cantidad_envase`, `subtotal`, `movimiento_id`, `producto_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(46, '28.00', 0, '60.00', 30, '1800.00', 147, 5, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(47, '25.00', 0, '65.00', 10, '650.00', 147, 4, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(48, '10.00', 0, '16.00', 15, '240.00', 147, 3, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(49, '9.00', 0, '17.00', 20, '340.00', 147, 1, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(50, '8.00', 15, NULL, 0, '120.00', 147, 13, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(51, '3.00', 15, NULL, 0, '45.00', 147, 9, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(52, '15.00', 18, NULL, 0, '270.00', 147, 12, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(53, '37.00', 1, '80.00', NULL, '37.00', 148, 5, '2020-12-24 18:18:32', '2020-12-24 18:18:32', NULL),
(54, '7.00', 1, NULL, NULL, '7.00', 148, 9, '2020-12-24 18:18:32', '2020-12-24 18:18:32', NULL),
(55, '29.00', 1, NULL, NULL, '29.00', 148, 12, '2020-12-24 18:18:32', '2020-12-24 18:18:32', NULL),
(56, '12.00', 1, '20.00', NULL, '12.00', 149, 3, '2020-12-24 18:19:51', '2020-12-24 18:19:51', NULL),
(57, '12.00', 1, '20.00', NULL, '12.00', 149, 1, '2020-12-24 18:19:51', '2020-12-24 18:19:51', NULL),
(58, '36.00', 1, '80.00', NULL, '36.00', 150, 4, '2020-12-24 18:23:53', '2020-12-24 18:23:53', NULL),
(59, '7.00', 1, NULL, NULL, '7.00', 150, 9, '2020-12-24 18:23:53', '2020-12-24 18:23:53', NULL),
(60, '29.00', 1, NULL, NULL, '29.00', 150, 12, '2020-12-24 18:23:53', '2020-12-24 18:23:53', NULL),
(61, '12.00', 1, '20.00', NULL, '12.00', 151, 3, '2020-12-24 18:25:25', '2020-12-24 18:25:25', NULL),
(62, '37.00', 1, '80.00', NULL, '37.00', 151, 5, '2020-12-24 18:25:25', '2020-12-24 18:25:25', NULL),
(63, '28.00', 0, '60.00', 15, '900.00', 153, 5, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(64, '25.00', 0, '65.00', 5, '325.00', 153, 4, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(65, '10.00', 0, '16.00', 10, '160.00', 153, 3, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(66, '9.00', 0, '17.00', 6, '102.00', 153, 1, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(67, '8.00', 10, NULL, 0, '80.00', 153, 13, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(68, '3.00', 10, NULL, 0, '30.00', 154, 9, '2020-12-24 18:27:56', '2020-12-24 18:27:56', NULL),
(69, '15.00', 10, NULL, 0, '150.00', 154, 12, '2020-12-24 18:27:56', '2020-12-24 18:27:56', NULL),
(70, '36.00', 2, '80.00', NULL, '72.00', 156, 5, '2020-12-24 18:29:30', '2020-12-24 18:29:30', NULL),
(71, '37.00', 1, '80.00', NULL, '37.00', 159, 5, '2020-12-24 18:30:19', '2020-12-24 18:30:19', NULL),
(72, '7.00', 1, NULL, NULL, '7.00', 159, 9, '2020-12-24 18:30:19', '2020-12-24 18:30:19', NULL),
(73, '29.00', 1, NULL, NULL, '29.00', 159, 12, '2020-12-24 18:30:19', '2020-12-24 18:30:19', NULL),
(74, '37.00', 2, '80.00', NULL, '74.00', 161, 5, '2020-12-24 18:30:41', '2020-12-24 18:30:41', NULL),
(75, '36.00', 1, '80.00', NULL, '36.00', 161, 4, '2020-12-24 18:30:41', '2020-12-24 18:30:41', NULL),
(76, '37.00', 1, '80.00', NULL, '37.00', 174, 5, '2020-12-24 18:39:19', '2020-12-24 18:39:19', NULL),
(77, '37.00', 1, '80.00', NULL, '37.00', 179, 5, '2020-12-24 18:40:59', '2020-12-24 18:40:59', NULL),
(78, '7.00', 1, NULL, NULL, '7.00', 179, 9, '2020-12-24 18:40:59', '2020-12-24 18:40:59', NULL),
(79, '29.00', 1, NULL, NULL, '29.00', 179, 12, '2020-12-24 18:40:59', '2020-12-24 18:40:59', NULL),
(80, '36.00', 1, '80.00', NULL, '36.00', 180, 4, '2020-12-24 18:44:27', '2020-12-24 18:44:27', NULL),
(81, '28.00', 5, '60.00', 10, '740.00', 184, 5, '2020-12-24 22:19:53', '2020-12-24 22:19:53', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pagos`
--

CREATE TABLE IF NOT EXISTS `detalle_pagos` (
`id` int(10) unsigned NOT NULL,
  `pedido_id` int(10) unsigned NOT NULL,
  `pago_id` int(10) unsigned NOT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `tipo` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_pagos`
--

INSERT INTO `detalle_pagos` (`id`, `pedido_id`, `pago_id`, `monto`, `tipo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 151, 157, '10.00', 'S', '2020-12-24 18:29:44', '2020-12-24 18:29:44', NULL),
(2, 151, 158, '15.00', 'R', '2020-12-24 18:29:51', '2020-12-24 18:29:51', NULL),
(3, 159, 160, '50.00', 'R', '2020-12-24 18:30:19', '2020-12-24 18:30:19', NULL),
(4, 151, 181, '10.00', 'S', '2020-12-24 22:17:50', '2020-12-24 22:17:50', NULL),
(5, 151, 182, '4.00', 'S', '2020-12-24 22:18:30', '2020-12-24 22:18:30', NULL),
(6, 151, 183, '5.00', 'S', '2020-12-24 22:18:58', '2020-12-24 22:18:58', NULL),
(7, 184, 185, '340.00', 'C', '2020-12-24 22:19:53', '2020-12-24 22:19:53', NULL),
(8, 184, 186, '100.00', 'C', '2020-12-24 22:20:21', '2020-12-24 22:20:21', NULL),
(9, 184, 187, '50.00', 'C', '2020-12-24 22:20:54', '2020-12-24 22:20:54', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_prestamo`
--

CREATE TABLE IF NOT EXISTS `detalle_prestamo` (
`id` int(10) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `detalle_mov_almacen_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_prestamo`
--

INSERT INTO `detalle_prestamo` (`id`, `cantidad`, `fecha`, `detalle_mov_almacen_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 1, '2020-12-24 22:22:15', 76, '2020-12-24 22:22:15', '2020-12-24 22:22:15', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_turno_pedido`
--

CREATE TABLE IF NOT EXISTS `detalle_turno_pedido` (
`id` int(10) unsigned NOT NULL,
  `pedido_id` int(10) unsigned NOT NULL,
  `turno_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_turno_pedido`
--

INSERT INTO `detalle_turno_pedido` (`id`, `pedido_id`, `turno_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 142, 16, '2020-12-08 17:51:29', '2020-12-08 17:51:29', NULL),
(2, 143, 17, '2020-12-08 17:51:34', '2020-12-08 17:51:34', NULL),
(3, 144, 18, '2020-12-08 17:51:39', '2020-12-08 17:51:39', NULL),
(4, 146, 19, '2020-12-08 17:52:07', '2020-12-08 17:52:07', NULL),
(5, 148, 16, '2020-12-24 18:18:32', '2020-12-24 18:18:32', NULL),
(6, 149, 16, '2020-12-24 18:19:51', '2020-12-24 18:19:51', NULL),
(7, 150, 17, '2020-12-24 18:23:53', '2020-12-24 18:23:53', NULL),
(8, 152, 16, '2020-12-24 18:25:25', '2020-12-24 18:25:25', NULL),
(9, 156, 19, '2020-12-24 18:29:30', '2020-12-24 18:29:30', NULL),
(10, 158, 19, '2020-12-24 18:29:51', '2020-12-24 18:29:51', NULL),
(11, 160, 18, '2020-12-24 18:30:19', '2020-12-24 18:30:19', NULL),
(12, 163, 16, '2020-12-24 18:33:02', '2020-12-24 18:33:02', NULL),
(13, 164, 19, '2020-12-24 18:33:18', '2020-12-24 18:33:18', NULL),
(14, 165, 17, '2020-12-24 18:33:32', '2020-12-24 18:33:32', NULL),
(15, 166, 19, '2020-12-24 18:33:55', '2020-12-24 18:33:55', NULL),
(16, 167, 18, '2020-12-24 18:34:01', '2020-12-24 18:34:01', NULL),
(17, 168, 17, '2020-12-24 18:34:06', '2020-12-24 18:34:06', NULL),
(18, 169, 16, '2020-12-24 18:34:11', '2020-12-24 18:34:11', NULL),
(19, 176, 20, '2020-12-24 18:39:35', '2020-12-24 18:39:35', NULL),
(20, 178, 21, '2020-12-24 18:39:52', '2020-12-24 18:39:52', NULL),
(21, 179, 20, '2020-12-24 18:40:59', '2020-12-24 18:40:59', NULL),
(22, 180, 21, '2020-12-24 18:44:27', '2020-12-24 18:44:27', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex`
--

CREATE TABLE IF NOT EXISTS `kardex` (
`id` int(10) unsigned NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cantidad` int(11) NOT NULL,
  `cantidad_envase` int(11) DEFAULT NULL,
  `tipo` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `precio_compra_envase` decimal(10,2) DEFAULT NULL,
  `precio_venta_envase` decimal(10,2) DEFAULT NULL,
  `stock_anterior` int(11) NOT NULL,
  `stock_actual` int(11) NOT NULL,
  `sucursal_id` int(10) unsigned NOT NULL,
  `detalle_mov_almacen_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `kardex`
--

INSERT INTO `kardex` (`id`, `fecha`, `cantidad`, `cantidad_envase`, `tipo`, `precio_compra`, `precio_venta`, `precio_compra_envase`, `precio_venta_envase`, `stock_anterior`, `stock_actual`, `sucursal_id`, `detalle_mov_almacen_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2020-12-08 05:00:00', 0, 30, 'I', '28.00', NULL, '60.00', NULL, 0, 30, 1, 46, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(2, '2020-12-08 05:00:00', 0, 10, 'I', '25.00', NULL, '65.00', NULL, 0, 10, 1, 47, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(3, '2020-12-08 05:00:00', 0, 15, 'I', '10.00', NULL, '16.00', NULL, 0, 15, 1, 48, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(4, '2020-12-08 05:00:00', 0, 20, 'I', '9.00', NULL, '17.00', NULL, 0, 20, 1, 49, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(5, '2020-12-08 05:00:00', 15, 0, 'I', '8.00', NULL, NULL, NULL, 0, 15, 1, 50, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(6, '2020-12-08 05:00:00', 15, 0, 'I', '3.00', NULL, NULL, NULL, 0, 15, 1, 51, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(7, '2020-12-08 05:00:00', 18, 0, 'I', '15.00', NULL, NULL, NULL, 0, 18, 1, 52, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(8, '2020-12-24 18:18:32', 1, NULL, 'E', NULL, '37.00', NULL, '80.00', 30, 29, 1, 53, '2020-12-24 18:18:32', '2020-12-24 18:18:32', NULL),
(9, '2020-12-24 18:18:32', 1, NULL, 'E', NULL, '7.00', NULL, NULL, 15, 14, 1, 54, '2020-12-24 18:18:32', '2020-12-24 18:18:32', NULL),
(10, '2020-12-24 18:18:32', 1, NULL, 'E', NULL, '29.00', NULL, NULL, 18, 17, 1, 55, '2020-12-24 18:18:32', '2020-12-24 18:18:32', NULL),
(11, '2020-12-24 18:19:51', 1, NULL, 'E', NULL, '12.00', NULL, '20.00', 15, 14, 1, 56, '2020-12-24 18:19:51', '2020-12-24 18:19:51', NULL),
(12, '2020-12-24 18:19:51', 1, NULL, 'E', NULL, '12.00', NULL, '20.00', 20, 19, 1, 57, '2020-12-24 18:19:51', '2020-12-24 18:19:51', NULL),
(13, '2020-12-24 18:23:53', 1, NULL, 'E', NULL, '36.00', NULL, '80.00', 10, 9, 1, 58, '2020-12-24 18:23:53', '2020-12-24 18:23:53', NULL),
(14, '2020-12-24 18:23:53', 1, NULL, 'E', NULL, '7.00', NULL, NULL, 14, 13, 1, 59, '2020-12-24 18:23:53', '2020-12-24 18:23:53', NULL),
(15, '2020-12-24 18:23:53', 1, NULL, 'E', NULL, '29.00', NULL, NULL, 17, 16, 1, 60, '2020-12-24 18:23:53', '2020-12-24 18:23:53', NULL),
(16, '2020-12-24 18:25:25', 1, NULL, 'E', NULL, '12.00', NULL, '20.00', 14, 13, 1, 61, '2020-12-24 18:25:25', '2020-12-24 18:25:25', NULL),
(17, '2020-12-24 18:25:25', 1, NULL, 'E', NULL, '37.00', NULL, '80.00', 29, 28, 1, 62, '2020-12-24 18:25:25', '2020-12-24 18:25:25', NULL),
(18, '2020-12-24 05:00:00', 0, 15, 'I', '28.00', NULL, '60.00', NULL, 0, 15, 2, 63, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(19, '2020-12-24 05:00:00', 0, 5, 'I', '25.00', NULL, '65.00', NULL, 0, 5, 2, 64, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(20, '2020-12-24 05:00:00', 0, 10, 'I', '10.00', NULL, '16.00', NULL, 0, 10, 2, 65, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(21, '2020-12-24 05:00:00', 0, 6, 'I', '9.00', NULL, '17.00', NULL, 0, 6, 2, 66, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(22, '2020-12-24 05:00:00', 10, 0, 'I', '8.00', NULL, NULL, NULL, 0, 10, 2, 67, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(23, '2020-12-24 05:00:00', 10, 0, 'I', '3.00', NULL, NULL, NULL, 0, 10, 2, 68, '2020-12-24 18:27:56', '2020-12-24 18:27:56', NULL),
(24, '2020-12-24 05:00:00', 10, 0, 'I', '15.00', NULL, NULL, NULL, 0, 10, 2, 69, '2020-12-24 18:27:56', '2020-12-24 18:27:56', NULL),
(25, '2020-12-24 18:29:30', 2, NULL, 'E', NULL, '36.00', NULL, '80.00', 15, 13, 2, 70, '2020-12-24 18:29:30', '2020-12-24 18:29:30', NULL),
(26, '2020-12-24 18:30:19', 1, NULL, 'E', NULL, '37.00', NULL, '80.00', 28, 27, 1, 71, '2020-12-24 18:30:19', '2020-12-24 18:30:19', NULL),
(27, '2020-12-24 18:30:19', 1, NULL, 'E', NULL, '7.00', NULL, NULL, 13, 12, 1, 72, '2020-12-24 18:30:19', '2020-12-24 18:30:19', NULL),
(28, '2020-12-24 18:30:19', 1, NULL, 'E', NULL, '29.00', NULL, NULL, 16, 15, 1, 73, '2020-12-24 18:30:19', '2020-12-24 18:30:19', NULL),
(29, '2020-12-24 18:30:41', 2, NULL, 'E', NULL, '37.00', NULL, '80.00', 27, 25, 1, 74, '2020-12-24 18:30:41', '2020-12-24 18:30:41', NULL),
(30, '2020-12-24 18:30:41', 1, NULL, 'E', NULL, '36.00', NULL, '80.00', 9, 8, 1, 75, '2020-12-24 18:30:41', '2020-12-24 18:30:41', NULL),
(31, '2020-12-24 18:39:19', 1, NULL, 'E', NULL, '37.00', NULL, '80.00', 25, 24, 1, 76, '2020-12-24 18:39:19', '2020-12-24 18:39:19', NULL),
(32, '2020-12-24 18:40:59', 1, NULL, 'E', NULL, '37.00', NULL, '80.00', 24, 23, 1, 77, '2020-12-24 18:40:59', '2020-12-24 18:40:59', NULL),
(33, '2020-12-24 18:40:59', 1, NULL, 'E', NULL, '7.00', NULL, NULL, 12, 11, 1, 78, '2020-12-24 18:40:59', '2020-12-24 18:40:59', NULL),
(34, '2020-12-24 18:40:59', 1, NULL, 'E', NULL, '29.00', NULL, NULL, 15, 14, 1, 79, '2020-12-24 18:40:59', '2020-12-24 18:40:59', NULL),
(35, '2020-12-24 18:44:27', 1, NULL, 'E', NULL, '36.00', NULL, '80.00', 5, 4, 2, 80, '2020-12-24 18:44:27', '2020-12-24 18:44:27', NULL),
(36, '2020-12-24 05:00:00', 5, 10, 'I', '28.00', NULL, '60.00', NULL, 23, 38, 1, 81, '2020-12-24 22:19:53', '2020-12-24 22:19:53', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menuoption`
--

CREATE TABLE IF NOT EXISTS `menuoption` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `menuoptioncategory_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `menuoption`
--

INSERT INTO `menuoption` (`id`, `name`, `link`, `order`, `menuoptioncategory_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Categoría de opción de menú', 'categoriaopcionmenu', 4, 9, '2017-07-23 22:17:30', '2020-10-30 22:59:39', NULL),
(2, 'Opción de menú', 'opcionmenu', 5, 9, '2017-07-23 22:17:30', '2020-10-30 22:59:46', NULL),
(3, 'Tipos de usuario', 'tipousuario', 3, 3, '2017-07-23 22:17:30', '2017-07-23 22:17:30', NULL),
(4, 'Usuario', 'usuario', 4, 3, '2017-07-23 22:17:30', '2017-07-23 22:17:30', NULL),
(15, 'Trabajadores', 'trabajador', 1, 1, '2018-05-23 17:15:13', '2020-06-25 22:37:04', NULL),
(26, 'Caja diaria', 'cajadiaria', 1, 6, '2020-02-23 23:01:33', '2020-09-25 20:43:09', NULL),
(27, 'Esquelas', 'esquela', 1, 7, '2020-02-23 23:15:15', '2020-06-03 22:24:48', '2020-06-03 22:24:48'),
(28, 'Cartas', 'carta', 2, 7, '2020-02-23 23:15:32', '2020-06-03 22:24:51', '2020-06-03 22:24:51'),
(29, 'Cargar data', 'subirdata', 1, 8, '2020-02-26 17:16:59', '2020-06-03 22:27:20', '2020-06-03 22:27:20'),
(30, 'Clientes', 'cliente', 2, 1, '2020-02-26 17:20:13', '2020-06-03 22:42:24', NULL),
(31, 'Cargas', 'carga', 2, 8, '2020-03-09 19:18:12', '2020-06-03 22:27:24', '2020-06-03 22:27:24'),
(32, 'Concepto', 'concepto', 3, 9, '2020-06-26 00:18:59', '2020-09-24 22:13:02', NULL),
(33, 'Sucursal', 'sucursal', 1, 9, '2020-06-26 00:55:27', '2020-06-26 00:55:27', NULL),
(34, 'Caja', 'caja', 1, 10, '2020-06-27 22:51:06', '2020-06-27 22:51:06', NULL),
(35, 'Registrar pedido', 'venta', 1, 12, '2020-06-27 22:51:31', '2020-10-30 22:58:50', NULL),
(36, 'Productos', 'producto', 2, 9, '2020-07-01 23:32:37', '2020-10-30 23:00:41', NULL),
(37, 'Repartidores en turno', 'turno', 1, 13, '2020-07-09 17:11:09', '2020-10-30 23:01:37', NULL),
(38, 'Turnos de repartidores completados', 'turnoscompletados', 2, 13, '2020-07-23 17:54:21', '2020-10-30 23:01:44', NULL),
(39, 'Pedidos a crédito', 'baloncredito', 3, 12, '2020-08-07 15:44:12', '2020-10-30 22:59:05', NULL),
(40, 'almacen', 'almacen', 1, 11, '2020-09-05 03:48:25', '2020-09-09 15:08:48', '2020-09-09 15:08:48'),
(41, 'Proveedor', 'proveedor', 3, 1, '2020-09-08 02:47:22', '2020-09-08 02:47:22', NULL),
(42, 'Compras', 'compras', 1, 11, '2020-09-09 15:07:18', '2020-09-09 20:00:11', NULL),
(43, 'Inventario', 'inventario', 2, 14, '2020-09-09 15:07:41', '2020-10-30 23:01:02', NULL),
(44, 'Kardex', 'kardex', 3, 14, '2020-09-09 15:08:11', '2020-10-30 23:01:07', NULL),
(45, 'Almacén', 'almacen', 2, 9, '2020-09-24 22:13:20', '2020-10-20 01:58:43', '2020-10-20 01:58:43'),
(46, 'Pedidos de caja actual', 'pedidos', 2, 12, '2020-10-03 20:31:52', '2020-11-04 17:30:28', NULL),
(47, 'Movimiento de almacén', 'movalmacen', 1, 14, '2020-10-03 21:00:30', '2020-10-30 23:00:55', NULL),
(48, 'Compras por pagar', 'compraspagar', 2, 11, '2020-10-22 18:42:59', '2020-10-22 18:42:59', NULL),
(49, 'Préstamo de envases', 'prestamoenvase', 4, 14, '2020-11-03 17:18:55', '2020-11-03 17:18:55', NULL),
(50, 'Devolución de envases', 'devolucion', 5, 14, '2020-11-03 17:19:23', '2020-11-03 17:19:23', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menuoptioncategory`
--

CREATE TABLE IF NOT EXISTS `menuoptioncategory` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `icon` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'glyphicon glyphicon-expand',
  `menuoptioncategory_id` int(10) unsigned DEFAULT NULL,
  `position` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `menuoptioncategory`
--

INSERT INTO `menuoptioncategory` (`id`, `name`, `order`, `icon`, `menuoptioncategory_id`, `position`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Personas', 6, 'fa fa-users', NULL, 'V', '2017-07-23 22:17:30', '2020-10-30 23:00:22', NULL),
(2, 'Trámite', 2, 'fa fa-bank', NULL, 'V', '2017-07-23 22:17:30', '2020-02-23 22:58:47', '2020-02-23 22:58:47'),
(3, 'Usuarios', 7, 'fa fa-user', NULL, 'V', '2017-07-23 22:17:30', '2020-10-30 23:00:27', NULL),
(4, 'Consultas', 3, 'fa fa-bank', NULL, 'V', '2018-05-23 17:11:34', '2020-02-23 22:58:40', '2020-02-23 22:58:40'),
(5, 'Configuración', 4, 'fa fa-bank', NULL, 'V', '2018-05-23 17:12:05', '2020-02-23 22:58:09', '2020-02-23 22:58:09'),
(6, 'Reportes', 8, 'fa fa-bar-chart', NULL, 'V', '2018-11-04 14:53:22', '2020-10-30 23:03:59', NULL),
(7, 'Acciones Inductivas', 3, 'fa fa-bank', NULL, 'V', '2020-02-23 23:13:35', '2020-06-03 22:27:09', '2020-06-03 22:27:09'),
(8, 'Datos', 1, 'fa fa-bank', NULL, 'V', '2020-02-26 17:16:16', '2020-06-03 22:27:31', '2020-06-03 22:27:31'),
(9, 'Configuración', 9, 'fa fa-cog', NULL, 'V', '2020-06-26 00:46:59', '2020-10-30 23:04:24', NULL),
(10, 'Caja', 1, 'fa fa-inbox', NULL, 'V', '2020-06-27 22:50:18', '2020-10-30 22:54:47', NULL),
(11, 'Compras', 4, 'fa fa-cubes', NULL, 'V', '2020-09-05 03:47:51', '2020-10-30 22:57:33', NULL),
(12, 'Pedidos', 2, 'fa fa-shopping-cart', NULL, 'V', '2020-10-30 22:55:40', '2020-10-30 22:55:40', NULL),
(13, 'Repartidores', 3, 'fa fa-bicycle', NULL, 'V', '2020-10-30 22:57:08', '2020-10-30 23:02:15', NULL),
(14, 'Almacén', 5, 'fa fa-dropbox', NULL, 'V', '2020-10-30 23:00:14', '2020-10-30 23:04:44', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
`id` int(10) unsigned NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2017_03_22_142422_crear_tabla_departamento', 1),
(2, '2017_03_22_142444_crear_tabla_provincia', 1),
(3, '2017_03_22_142546_crear_tabla_distrito', 1),
(4, '2017_03_22_142709_crear_tabla_workertype', 1),
(6, '2017_03_22_142751_crear_tabla_menuoptioncategory', 1),
(7, '2017_03_22_142804_crear_tabla_menuoption', 1),
(8, '2017_03_22_142828_crear_tabla_usertype', 1),
(9, '2017_03_22_142904_crear_tabla_permission', 1),
(10, '2017_03_22_142921_crear_tabla_user', 1),
(11, '2017_03_22_142939_crear_tabla_binnacle', 1),
(12, '2017_08_24_161501_crear_tabla_empresa', 2),
(14, '2017_08_28_120756_crear_tabla_rolpersona', 3),
(15, '2018_05_10_115933_crear_tabla_categoria', 4),
(16, '2018_05_11_083449_crear_tabla_marca', 4),
(17, '2018_05_11_084049_crear_tabla_unidad', 4),
(18, '2018_05_11_084238_crear_tabla_producto', 4),
(20, '2018_05_15_103607_crear_tabla_sucursal', 5),
(21, '2018_05_16_211523_crear_tabla_operacion', 6),
(22, '2018_05_16_212332_crear_tabla_operacion_menu', 7),
(24, '2018_05_16_213515_crear_tabla_permiso_operacion', 8),
(25, '2018_05_23_123325_create_tabla_sevicio', 9),
(26, '2018_05_23_182509_crear_tabla_venta', 10),
(27, '2018_05_23_182532_crear_tabla_detalle_venta', 10),
(29, '2018_10_24_184336_crear_tabla_concepto', 11),
(30, '2018_11_06_181400_crear_tabla_serieventa_sucursal', 12),
(32, '2017_03_22_142724_crear_tabla_person', 13),
(35, '2020_02_26_200519_crear_tabla_carga', 14),
(36, '2020_02_26_202605_crear_tabla_accion_inductiva', 14),
(37, '2020_02_28_113740_crear_tabla_contribuyente', 14),
(38, '2020_07_09_170255_turno_repartidor_table', 15),
(39, '2020_07_09_232818_crear_tabla_detalle_turno_pedido', 16),
(40, '2020_08_07_181901_create_tabla_detalle_pagos', 17),
(42, '2020_09_11_200556_create_table_almacen', 18),
(43, '2020_09_11_201245_create_table_lote', 19),
(44, '2020_09_11_202038_create_table_stock', 20),
(45, '2020_09_11_202543_create_table_detalle_mov_almacen', 21),
(46, '2020_09_11_203236_create_table_kardex', 22),
(47, '2020_12_21_205832_crear_tabla_detalle_prestamo', 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento`
--

CREATE TABLE IF NOT EXISTS `movimiento` (
`id` int(10) unsigned NOT NULL,
  `tipomovimiento_id` int(10) unsigned NOT NULL,
  `tipodocumento_id` int(10) unsigned DEFAULT NULL,
  `venta_id` int(10) unsigned DEFAULT NULL,
  `compra_id` int(10) DEFAULT NULL,
  `num_caja` int(11) DEFAULT NULL,
  `num_venta` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_compra` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `igv` decimal(10,2) DEFAULT NULL,
  `montoefectivo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `montovisa` decimal(10,2) NOT NULL DEFAULT '0.00',
  `montomaster` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vuelto` decimal(10,2) DEFAULT NULL,
  `comentario` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comentario_anulado` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` int(11) NOT NULL,
  `pedido_sucursal` int(1) DEFAULT NULL,
  `balon_a_cuenta` int(1) DEFAULT NULL,
  `balon_prestado` int(1) DEFAULT NULL,
  `vale_balon_subcafae` int(1) DEFAULT NULL,
  `vale_balon_monto` int(1) DEFAULT NULL,
  `vale_balon_fise` int(1) DEFAULT NULL,
  `codigo_vale_monto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_vale_fise` varchar(21) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_vale_subcafae` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monto_vale_balon` decimal(10,2) DEFAULT NULL,
  `monto_vale_fise` decimal(10,2) DEFAULT NULL,
  `ingreso_caja_principal` int(1) DEFAULT NULL,
  `ingreso_cierre_id` int(10) DEFAULT NULL,
  `concepto_id` int(10) unsigned DEFAULT NULL,
  `persona_id` int(10) unsigned DEFAULT NULL,
  `trabajador_id` int(10) unsigned DEFAULT NULL,
  `sucursal_id` int(10) unsigned NOT NULL,
  `usuario_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=188 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `movimiento`
--

INSERT INTO `movimiento` (`id`, `tipomovimiento_id`, `tipodocumento_id`, `venta_id`, `compra_id`, `num_caja`, `num_venta`, `num_compra`, `fecha`, `total`, `subtotal`, `igv`, `montoefectivo`, `montovisa`, `montomaster`, `vuelto`, `comentario`, `comentario_anulado`, `estado`, `pedido_sucursal`, `balon_a_cuenta`, `balon_prestado`, `vale_balon_subcafae`, `vale_balon_monto`, `vale_balon_fise`, `codigo_vale_monto`, `codigo_vale_fise`, `codigo_vale_subcafae`, `monto_vale_balon`, `monto_vale_fise`, `ingreso_caja_principal`, `ingreso_cierre_id`, `concepto_id`, `persona_id`, `trabajador_id`, `sucursal_id`, `usuario_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(141, 1, NULL, NULL, NULL, 1, NULL, NULL, '2020-12-08 17:51:21', '1000.00', '1000.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, 1, '2020-12-08 17:51:21', '2020-12-08 17:51:21', NULL),
(142, 1, NULL, NULL, NULL, 2, NULL, NULL, '2020-12-08 17:51:29', '30.00', '30.00', NULL, '30.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 3, 1, 1, '2020-12-08 17:51:29', '2020-12-08 17:51:29', NULL),
(143, 1, NULL, NULL, NULL, 3, NULL, NULL, '2020-12-08 17:51:34', '40.00', '40.00', NULL, '40.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 29, 1, 1, '2020-12-08 17:51:34', '2020-12-08 17:51:34', NULL),
(144, 1, NULL, NULL, NULL, 4, NULL, NULL, '2020-12-08 17:51:39', '30.00', '30.00', NULL, '30.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 76, 1, 1, '2020-12-08 17:51:39', '2020-12-08 17:51:39', NULL),
(145, 1, NULL, NULL, NULL, 1, NULL, NULL, '2020-12-08 17:52:03', '750.00', '750.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 2, 1, '2020-12-08 17:52:03', '2020-12-08 17:52:03', NULL),
(146, 1, NULL, NULL, NULL, 2, NULL, NULL, '2020-12-08 17:52:07', '50.00', '50.00', NULL, '50.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 28, 2, 1, '2020-12-08 17:52:07', '2020-12-08 17:52:07', NULL),
(147, 4, 4, NULL, NULL, NULL, NULL, '1234-123445', '2020-12-08 17:53:28', '3465.00', NULL, NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, 2, 1, 1, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(148, 2, 3, NULL, NULL, NULL, '0001-0000001', NULL, '2020-12-24 18:18:32', '73.00', '61.86', '11.14', '73.00', '0.00', '0.00', '7.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 77, 3, 1, 1, '2020-12-24 18:18:32', '2020-12-24 18:18:32', NULL),
(149, 2, 3, NULL, NULL, NULL, '0001-0000002', NULL, '2020-12-24 18:19:51', '24.00', '20.34', '3.66', '24.00', '0.00', '0.00', '6.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 74, 3, 1, 1, '2020-12-24 18:19:51', '2020-12-24 18:19:51', NULL),
(150, 2, 3, NULL, NULL, NULL, '0001-0000003', NULL, '2020-12-24 18:23:53', '56.00', '47.46', '8.54', '56.00', '0.00', '0.00', '4.00', '', NULL, 1, 0, 0, NULL, 0, 0, 1, NULL, '123456789', NULL, NULL, '16.00', NULL, NULL, 3, 74, 29, 1, 1, '2020-12-24 18:23:53', '2020-12-24 18:23:53', NULL),
(151, 2, 3, NULL, NULL, NULL, '0001-0000004', NULL, '2020-12-24 18:25:25', '49.00', '41.53', '7.47', '0.00', '0.00', '0.00', '0.00', '', NULL, 1, 0, 1, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 81, 3, 1, 1, '2020-12-24 18:25:25', '2020-12-24 18:25:25', NULL),
(152, 5, NULL, 151, NULL, NULL, NULL, NULL, '2020-12-24 18:25:25', '0.00', '0.00', NULL, '0.00', '0.00', '0.00', NULL, 'Pedido a crédito: TK-0001-0000004', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 81, 3, 1, 1, '2020-12-24 18:25:25', '2020-12-24 18:25:25', NULL),
(153, 4, 4, NULL, NULL, NULL, NULL, '1234-1232133', '2020-12-24 18:27:25', '1567.00', NULL, NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, 2, 2, 1, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(154, 3, 4, NULL, NULL, NULL, NULL, '1234-1234567', '2020-12-24 18:27:56', '180.00', NULL, NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 83, 2, 2, 1, '2020-12-24 18:27:56', '2020-12-24 18:27:56', NULL),
(155, 1, NULL, NULL, 154, 3, NULL, NULL, '2020-12-24 18:27:56', '180.00', '180.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC-1234-1234567', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 83, NULL, 2, 1, '2020-12-24 18:27:56', '2020-12-24 18:27:56', NULL),
(156, 2, 3, NULL, NULL, NULL, '0001-0000001', NULL, '2020-12-24 18:29:30', '36.00', '30.51', '5.49', '36.00', '0.00', '0.00', '4.00', '', NULL, 1, 0, 0, NULL, 1, 0, 0, NULL, NULL, '3333', NULL, NULL, NULL, NULL, 3, 13, 28, 2, 1, '2020-12-24 18:29:30', '2020-12-24 18:29:30', NULL),
(157, 1, NULL, 151, NULL, 5, NULL, NULL, '2020-12-24 18:29:44', '10.00', '10.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE PEDIDO A CRÉDITO: TK-0001-0000004', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 81, 3, 1, 1, '2020-12-24 18:29:44', '2020-12-24 18:29:44', NULL),
(158, 5, NULL, 151, NULL, NULL, NULL, NULL, '2020-12-24 18:29:51', '15.00', '15.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE PEDIDO A CRÉDITO: TK-0001-0000004', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 81, 28, 1, 1, '2020-12-24 18:29:51', '2020-12-24 18:29:51', NULL),
(159, 2, 3, NULL, NULL, NULL, '0001-0000005', NULL, '2020-12-24 18:30:19', '73.00', '61.86', '11.14', '50.00', '0.00', '0.00', '0.00', '', NULL, 1, 0, 1, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 74, 76, 1, 1, '2020-12-24 18:30:19', '2020-12-24 18:30:19', NULL),
(160, 5, NULL, 159, NULL, NULL, NULL, NULL, '2020-12-24 18:30:19', '50.00', '50.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE PEDIDO A CRÉDITO: TK-0001-0000005', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 74, 76, 1, 1, '2020-12-24 18:30:19', '2020-12-24 18:30:19', NULL),
(161, 2, 3, NULL, NULL, NULL, '0001-0000006', NULL, '2020-12-24 18:30:41', '110.00', '93.22', '16.78', '110.00', '0.00', '0.00', '0.00', '', NULL, 1, 1, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 100, 2, 1, 1, '2020-12-24 18:30:41', '2020-12-24 18:30:41', NULL),
(162, 1, NULL, 161, NULL, 6, NULL, NULL, '2020-12-24 18:30:41', '110.00', '93.22', NULL, '0.00', '0.00', '0.00', NULL, 'Pago de: TK0001-0000006', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 100, 2, 1, 1, '2020-12-24 18:30:41', '2020-12-24 18:30:41', NULL),
(163, 1, NULL, NULL, NULL, 7, NULL, NULL, '2020-12-24 18:33:02', '50.00', '50.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, 3, 1, 1, '2020-12-24 18:33:02', '2020-12-24 18:33:02', NULL),
(164, 1, NULL, NULL, NULL, 4, NULL, NULL, '2020-12-24 18:33:18', '20.00', '20.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, NULL, 28, 2, 1, '2020-12-24 18:33:18', '2020-12-24 18:33:18', NULL),
(165, 6, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-24 18:33:32', '36.00', '36.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, 29, 1, 1, '2020-12-24 18:33:32', '2020-12-24 18:33:32', NULL),
(166, 1, NULL, NULL, NULL, 5, NULL, NULL, '2020-12-24 18:33:55', '81.00', '81.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, NULL, 28, 2, 1, '2020-12-24 18:33:55', '2020-12-24 18:33:55', NULL),
(167, 1, NULL, NULL, NULL, 8, NULL, NULL, '2020-12-24 18:34:01', '80.00', '80.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, NULL, 76, 1, 1, '2020-12-24 18:34:01', '2020-12-24 18:34:01', NULL),
(168, 1, NULL, NULL, NULL, 9, NULL, NULL, '2020-12-24 18:34:06', '60.00', '60.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, NULL, 29, 1, 1, '2020-12-24 18:34:06', '2020-12-24 18:34:06', NULL),
(169, 1, NULL, NULL, NULL, 10, NULL, NULL, '2020-12-24 18:34:11', '177.00', '177.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, NULL, 3, 1, 1, '2020-12-24 18:34:11', '2020-12-24 18:34:11', NULL),
(170, 1, NULL, NULL, NULL, 6, NULL, NULL, '2020-12-24 18:35:30', '621.00', '621.00', NULL, '621.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 171, 2, NULL, NULL, 2, 1, '2020-12-24 18:35:30', '2020-12-24 18:38:46', NULL),
(171, 1, NULL, NULL, NULL, 11, NULL, NULL, '2020-12-24 18:38:46', '621.00', NULL, NULL, '0.00', '0.00', '0.00', NULL, 'INGRESO DE CAJA DE SUCURSAL 2 - ', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, NULL, NULL, 1, 1, '2020-12-24 18:38:46', '2020-12-24 18:38:46', NULL),
(172, 1, NULL, NULL, NULL, 12, NULL, NULL, '2020-12-24 18:38:51', '1908.00', '1908.00', NULL, '1908.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 1, 1, '2020-12-24 18:38:51', '2020-12-24 18:38:51', NULL),
(173, 1, NULL, NULL, NULL, 13, NULL, NULL, '2020-12-24 18:38:58', '1500.00', '1500.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, 1, '2020-12-24 18:38:58', '2020-12-24 18:38:58', NULL),
(174, 2, 3, NULL, NULL, NULL, '0001-0000007', NULL, '2020-12-24 18:39:19', '37.00', '31.36', '5.64', '37.00', '0.00', '0.00', '3.00', '', NULL, 1, 1, 0, 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 74, 2, 1, 1, '2020-12-24 18:39:19', '2020-12-24 22:22:15', NULL),
(175, 1, NULL, 174, NULL, 14, NULL, NULL, '2020-12-24 18:39:19', '37.00', '31.36', NULL, '0.00', '0.00', '0.00', NULL, 'Pago de: TK0001-0000007', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 74, 2, 1, 1, '2020-12-24 18:39:19', '2020-12-24 18:39:19', NULL),
(176, 1, NULL, NULL, NULL, 15, NULL, NULL, '2020-12-24 18:39:35', '50.00', '50.00', NULL, '50.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 3, 1, 1, '2020-12-24 18:39:35', '2020-12-24 18:39:35', NULL),
(177, 1, NULL, NULL, NULL, 7, NULL, NULL, '2020-12-24 18:39:44', '1000.00', '1000.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 2, 1, '2020-12-24 18:39:44', '2020-12-24 18:39:44', NULL),
(178, 1, NULL, NULL, NULL, 8, NULL, NULL, '2020-12-24 18:39:52', '50.00', '50.00', NULL, '50.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 28, 2, 1, '2020-12-24 18:39:52', '2020-12-24 18:39:52', NULL),
(179, 2, 3, NULL, NULL, NULL, '0001-0000008', NULL, '2020-12-24 18:40:59', '73.00', '61.86', '11.14', '73.00', '0.00', '0.00', '7.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 30, 3, 1, 1, '2020-12-24 18:40:59', '2020-12-24 18:40:59', NULL),
(180, 2, 3, NULL, NULL, NULL, '0001-0000002', NULL, '2020-12-24 18:44:27', '36.00', '30.51', '5.49', '36.00', '0.00', '0.00', '4.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 105, 28, 2, 1, '2020-12-24 18:44:27', '2020-12-24 18:44:27', NULL),
(181, 1, NULL, 151, NULL, 16, NULL, NULL, '2020-12-24 22:17:50', '10.00', '10.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE PEDIDO A CRÉDITO: TK-0001-0000004', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 81, 3, 1, 1, '2020-12-24 22:17:50', '2020-12-24 22:17:50', NULL),
(182, 1, NULL, 151, NULL, 17, NULL, NULL, '2020-12-24 22:18:30', '4.00', '4.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE PEDIDO A CRÉDITO: TK-0001-0000004', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 81, 3, 1, 1, '2020-12-24 22:18:30', '2020-12-24 22:18:30', NULL),
(183, 1, NULL, 151, NULL, 18, NULL, NULL, '2020-12-24 22:18:58', '5.00', '5.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE PEDIDO A CRÉDITO: TK0001-0000004', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 81, 3, 1, 1, '2020-12-24 22:18:58', '2020-12-24 22:18:58', NULL),
(184, 3, 4, NULL, NULL, NULL, NULL, '1234-1234567', '2020-12-24 22:19:53', '740.00', NULL, NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 83, 2, 1, 1, '2020-12-24 22:19:53', '2020-12-24 22:19:53', NULL),
(185, 1, NULL, NULL, 184, 19, NULL, NULL, '2020-12-24 22:19:53', '340.00', '340.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC-1234-1234567', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 83, NULL, 1, 1, '2020-12-24 22:19:53', '2020-12-24 22:19:53', NULL),
(186, 1, NULL, NULL, 184, 20, NULL, NULL, '2020-12-24 22:20:21', '100.00', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC-1234-1234567', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 83, NULL, 1, 1, '2020-12-24 22:20:21', '2020-12-24 22:20:21', NULL),
(187, 1, NULL, NULL, 184, 21, NULL, NULL, '2020-12-24 22:20:54', '50.00', '50.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC1234-1234567', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 83, NULL, 1, 1, '2020-12-24 22:20:54', '2020-12-24 22:20:54', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operacion`
--

CREATE TABLE IF NOT EXISTS `operacion` (
`id` int(10) unsigned NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `operacion`
--

INSERT INTO `operacion` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Nuevo', '2018-05-16 05:00:00', '2018-05-16 05:00:00', NULL),
(2, 'Editar', '2018-05-16 05:00:00', '2018-05-16 05:00:00', NULL),
(3, 'Eliminar', '2018-05-16 05:00:00', '2018-05-16 05:00:00', NULL),
(4, 'Extornar', '2018-05-16 05:00:00', '2018-05-16 05:00:00', NULL),
(5, 'Apertura de caja', '2018-05-16 05:00:00', '2018-05-16 05:00:00', NULL),
(6, 'Cierre de caja', '2018-05-16 05:00:00', '2018-05-16 05:00:00', NULL),
(7, 'Permisos', '2018-05-16 05:00:00', '2018-05-16 05:00:00', NULL),
(8, 'Operaciones', '2018-05-16 05:00:00', '2018-05-16 05:00:00', NULL),
(9, 'Anular', '2018-05-16 05:00:00', '2018-05-16 05:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operacion_menu`
--

CREATE TABLE IF NOT EXISTS `operacion_menu` (
`id` int(10) unsigned NOT NULL,
  `operacion_id` int(10) unsigned NOT NULL,
  `menuoption_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `operacion_menu`
--

INSERT INTO `operacion_menu` (`id`, `operacion_id`, `menuoption_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 15, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(2, 2, 15, '2020-02-23 05:00:00', '2020-02-23 05:00:00', NULL),
(3, 3, 15, '2020-02-23 05:00:00', '2020-02-23 05:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso_operacion`
--

CREATE TABLE IF NOT EXISTS `permiso_operacion` (
`id` int(10) unsigned NOT NULL,
  `operacionmenu_id` int(10) unsigned NOT NULL,
  `usertype_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
`id` int(10) unsigned NOT NULL,
  `usertype_id` int(10) unsigned NOT NULL,
  `menuoption_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=714 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permission`
--

INSERT INTO `permission` (`id`, `usertype_id`, `menuoption_id`, `created_at`, `updated_at`) VALUES
(33, 3, 6, '2017-09-09 20:44:05', '2017-09-09 20:44:05'),
(34, 3, 7, '2017-09-09 20:44:05', '2017-09-09 20:44:05'),
(35, 3, 8, '2017-09-09 20:44:05', '2017-09-09 20:44:05'),
(36, 3, 9, '2017-09-09 20:44:05', '2017-09-09 20:44:05'),
(82, 4, 6, '2018-05-19 03:12:46', '2018-05-19 03:12:46'),
(83, 4, 9, '2018-05-19 03:12:47', '2018-05-19 03:12:47'),
(84, 4, 13, '2018-05-19 03:12:47', '2018-05-19 03:12:47'),
(85, 4, 1, '2018-05-19 03:12:47', '2018-05-19 03:12:47'),
(86, 4, 3, '2018-05-19 03:12:47', '2018-05-19 03:12:47'),
(87, 4, 4, '2018-05-19 03:12:47', '2018-05-19 03:12:47'),
(374, 2, 15, '2020-06-27 22:51:45', '2020-06-27 22:51:45'),
(375, 2, 1, '2020-06-27 22:51:45', '2020-06-27 22:51:45'),
(376, 2, 2, '2020-06-27 22:51:45', '2020-06-27 22:51:45'),
(377, 2, 3, '2020-06-27 22:51:45', '2020-06-27 22:51:45'),
(378, 2, 4, '2020-06-27 22:51:45', '2020-06-27 22:51:45'),
(379, 2, 34, '2020-06-27 22:51:45', '2020-06-27 22:51:45'),
(380, 2, 35, '2020-06-27 22:51:45', '2020-06-27 22:51:45'),
(690, 1, 15, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(691, 1, 30, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(692, 1, 41, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(693, 1, 3, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(694, 1, 4, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(695, 1, 26, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(696, 1, 33, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(697, 1, 36, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(698, 1, 32, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(699, 1, 1, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(700, 1, 2, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(701, 1, 34, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(702, 1, 42, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(703, 1, 48, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(704, 1, 35, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(705, 1, 46, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(706, 1, 39, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(707, 1, 37, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(708, 1, 38, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(709, 1, 47, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(710, 1, 43, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(711, 1, 44, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(712, 1, 49, '2020-11-03 17:19:46', '2020-11-03 17:19:46'),
(713, 1, 50, '2020-11-03 17:19:46', '2020-11-03 17:19:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE IF NOT EXISTS `person` (
`id` int(10) unsigned NOT NULL,
  `apellido_pat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_mat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombres` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dni` int(8) DEFAULT NULL,
  `razon_social` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruc` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `celular` int(9) DEFAULT NULL,
  `tipo_persona` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `person`
--

INSERT INTO `person` (`id`, `apellido_pat`, `apellido_mat`, `nombres`, `dni`, `razon_social`, `ruc`, `direccion`, `celular`, `tipo_persona`, `sucursal_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, NULL, NULL, NULL, 'VARIOS', NULL, NULL, NULL, 'C', NULL, '2020-06-29 05:00:00', '2020-06-30 20:23:32', NULL),
(2, 'ACUÑA', 'GUEVARA', 'LUIS EDGARDO', 76665698, NULL, NULL, 'prueba', 963307082, 'A', 1, '2020-02-24 00:32:40', '2020-10-30 23:02:59', NULL),
(3, 'NUÑEZ', 'BANCES', 'OREJITAS', 74859612, NULL, NULL, 'calle 7 de junii 106', 879456123, 'T', 1, '2020-02-25 21:17:32', '2020-09-08 02:36:24', NULL),
(5, NULL, NULL, NULL, NULL, 'LUIS ACUÑA GUEVARA', '10766656980', 'prueba cell', 963307082, 'C', NULL, '2020-06-26 01:32:13', '2020-07-21 21:36:41', NULL),
(13, 'ACUñA', 'ORTIZ', 'LUIS', 12345679, NULL, NULL, 'prueba calle', 123456789, 'C', NULL, '2020-07-04 01:47:28', '2020-09-08 02:37:56', NULL),
(26, NULL, NULL, NULL, NULL, 'PRUEBITA', '12345678944', 'Av. Canta Callao, Callao 07031', 124563780, 'C', NULL, '2020-07-04 02:52:06', '2020-07-23 16:57:57', NULL),
(28, 'DE LOS SANTOS', 'DEL MONTE', 'JOSE DARKIEL', 78654139, NULL, NULL, 'av prueba 1245', 784596321, 'T', 2, '2020-07-10 02:58:36', '2020-09-08 02:36:07', NULL),
(29, 'PRUEBA1', 'PRUEBA2', 'PRUEBA', 12345678, NULL, NULL, 'prueba calle zaña', 784956321, 'T', 1, '2020-07-15 23:10:23', '2020-10-10 22:49:45', NULL),
(30, 'ORTIZ', 'GUERRERO', 'LUIS', 78459634, NULL, NULL, 'prueba direccion', 784596321, 'C', NULL, '2020-07-15 23:11:16', '2020-09-08 02:38:06', NULL),
(66, 'DIAZ', 'BARBOZA', 'JOSE DARKIEL', 79468215, NULL, NULL, 'Av. Canta Callao, Callao 07031', 784596120, 'C', NULL, '2020-07-23 05:42:14', '2020-07-23 05:42:14', NULL),
(74, 'GUEVARA', 'HERNANDEZ', 'ENRIQUETA BEATRIZ', 16763290, NULL, NULL, 'Calle 7de Junio #106\r\n106', 985833666, 'C', NULL, '2020-08-04 17:30:36', '2020-08-04 17:30:36', NULL),
(75, 'GOMEZ', 'FLORES', 'JAVIER JIMMY', 76952048, NULL, NULL, 'av prueba', 789546213, 'C', NULL, '2020-08-26 18:54:36', '2020-08-26 18:54:36', NULL),
(76, 'EMETERIO', 'CALDERON', 'KAREN LIZBETH', 47557620, NULL, NULL, 'prueba calle', 748596213, 'T', 1, '2020-09-08 03:02:29', '2020-09-08 03:02:29', NULL),
(77, 'AYALA', 'VIDARTE', 'MARIANA ANDREA', 75985418, NULL, NULL, 'prueba direccion amor', 932929895, 'C', NULL, '2020-09-08 03:03:21', '2020-09-08 03:03:21', NULL),
(78, NULL, NULL, NULL, NULL, 'OPTIMIZA CONSTRUCCION Y SERVICIOS S.A.C.', '20506065896', 'prueba direccion 2', 123456789, 'P', NULL, '2020-09-08 03:21:14', '2020-09-08 03:21:18', NULL),
(79, NULL, NULL, NULL, NULL, 'BANCO BBVA PERU', '20100130204', 'direccion prueba', 784596321, 'P', NULL, '2020-09-08 20:02:35', '2020-09-08 20:02:41', '2020-09-08 20:02:41'),
(80, NULL, NULL, NULL, NULL, 'ACUÑA GUEVARA LUIS EDGARDO', '10766656981', 'proveedor prueba', 963307082, 'P', NULL, '2020-09-13 00:17:23', '2020-09-13 00:17:43', '2020-09-13 00:17:43'),
(81, 'HUAMAN', 'TIRADO', 'MARCOS JOEL', 16763250, NULL, NULL, 'prueba', 784596321, 'C', NULL, '2020-09-14 23:17:01', '2020-09-14 23:17:01', NULL),
(82, NULL, NULL, NULL, NULL, 'ACUÑA GUEVARA LUIS EDGARDO', '10766656981', NULL, NULL, 'P', NULL, '2020-09-15 00:56:59', '2020-09-15 00:56:59', NULL),
(83, NULL, NULL, NULL, NULL, 'BANCO BBVA PERU', '20100130204', 'direccion prueba bbva', 987456321, 'P', NULL, '2020-09-15 01:00:33', '2020-09-15 01:00:33', NULL),
(84, NULL, NULL, NULL, NULL, 'BI GRAND CONFECCIONES S.A.C.', '20553856451', 'prueba', 784456789, 'P', NULL, '2020-09-25 20:56:07', '2020-09-25 22:59:00', '2020-09-25 22:59:00'),
(85, NULL, NULL, NULL, NULL, 'SINERGIAS ECONOMICAS SOCIEDAD ANONIMA CERRADA - ECOSINERGIAS S.A.C.', '20552271794', 'hola', 789456123, 'C', NULL, '2020-09-25 21:28:04', '2020-09-25 21:29:41', '2020-09-25 21:29:41'),
(86, NULL, NULL, NULL, NULL, 'INSUMOS PISQUEROS DEL SUR E.I.R.L.', '20558629585', 'asd', 486124789, 'C', NULL, '2020-09-25 21:29:16', '2020-09-25 21:29:49', '2020-09-25 21:29:49'),
(87, NULL, NULL, NULL, NULL, 'ADSASDAD', '12345678945', 'asdadadsadasds', 789456123, 'C', NULL, '2020-09-25 21:30:43', '2020-09-25 21:30:43', NULL),
(88, NULL, NULL, NULL, NULL, 'H & J E HIJOS E.I.R.L', '20518639928', 'asd', 789456132, 'C', NULL, '2020-09-25 21:32:17', '2020-09-25 21:32:17', NULL),
(89, NULL, NULL, NULL, NULL, 'COMERCIAL FERRETERA PRISMA S.A.C.', '20525994741', 'asddd', 123456789, 'C', NULL, '2020-09-25 22:05:29', '2020-09-25 22:05:29', NULL),
(90, NULL, NULL, NULL, NULL, 'REPRESENTACIONES DIERA S.A.C.', '20553476462', 'asdqwe', 112233445, 'C', NULL, '2020-09-25 22:08:07', '2020-09-25 22:08:07', NULL),
(91, NULL, NULL, NULL, NULL, 'BI GRAND CONFECCIONES S.A.C.', '20553856452', 'prueba de cliente', 789456123, 'C', NULL, '2020-09-25 22:10:29', '2020-09-25 22:10:29', NULL),
(92, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248984', 'hola prueba', 123456789, 'P', NULL, '2020-09-25 22:47:26', '2020-09-25 22:58:57', '2020-09-25 22:58:57'),
(93, NULL, NULL, NULL, NULL, 'CORPORACION CARMINA SAC', '20601155185', 'hola prueba', 987456123, 'P', NULL, '2020-09-25 22:56:43', '2020-09-25 22:59:07', '2020-09-25 22:59:07'),
(94, NULL, NULL, NULL, NULL, 'ARTROSCOPICTRAUMA S.A.C.', '20538856674', 'prueba', 789456412, 'P', NULL, '2020-09-25 22:58:14', '2020-09-25 22:59:04', '2020-09-25 22:59:04'),
(95, NULL, NULL, NULL, NULL, 'ARTROSCOPICTRAUMA S.A.C.', '20538856674', 'prueba direccion', 748596120, 'P', NULL, '2020-09-25 22:59:27', '2020-09-25 22:59:27', NULL),
(96, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248984', 'pruebaaaaaa', 784512369, 'P', NULL, '2020-09-25 23:00:01', '2020-09-25 23:00:43', '2020-09-25 23:00:43'),
(97, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248984', 'asd prueba', 123456789, 'P', NULL, '2020-09-25 23:01:24', '2020-09-25 23:05:18', '2020-09-25 23:05:18'),
(98, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL 2', '20543248985', 'prueba direccion', 123456789, 'P', NULL, '2020-09-25 23:02:37', '2020-09-25 23:05:16', '2020-09-25 23:05:16'),
(99, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL 3', '20543248986', 'prueba dicrecasda 1', 789456123, 'P', NULL, '2020-09-25 23:03:07', '2020-09-25 23:05:13', '2020-09-25 23:05:13'),
(100, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248912', 'prueba', 789456123, 'C', NULL, '2020-09-25 23:03:49', '2020-09-25 23:03:49', NULL),
(101, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL 3', '20543248988', 'hola direccion prueba', 789456123, 'C', NULL, '2020-09-25 23:04:23', '2020-09-25 23:04:23', NULL),
(102, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248984', 'prueba direccion', 123456789, 'P', NULL, '2020-09-25 23:07:06', '2020-09-25 23:08:16', '2020-09-25 23:08:16'),
(103, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248984', 'prueba', 784512487, 'P', NULL, '2020-09-25 23:08:34', '2020-09-25 23:08:34', NULL),
(104, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL 2', '20543248985', 'prueba 2 direccion', 789456123, 'P', NULL, '2020-09-25 23:09:19', '2020-09-25 23:09:19', NULL),
(105, 'PRUEBA', 'PRUEBA', 'LUIS', 12121212, NULL, NULL, 'Holi', 789456123, 'C', NULL, '2020-11-06 03:56:56', '2020-11-06 03:56:56', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE IF NOT EXISTS `producto` (
`id` int(10) unsigned NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta_envase` decimal(10,2) DEFAULT NULL,
  `precio_compra_envase` decimal(10,2) DEFAULT NULL,
  `frecuente` int(1) NOT NULL,
  `editable` int(1) NOT NULL DEFAULT '0',
  `recargable` int(1) DEFAULT NULL,
  `stock_seguridad` int(3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `descripcion`, `precio_venta`, `precio_compra`, `precio_venta_envase`, `precio_compra_envase`, `frecuente`, `editable`, `recargable`, `stock_seguridad`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'AGUA NEUTRA', '12.00', '9.00', '20.00', '17.00', 1, 0, 1, 10, '2018-05-24 05:00:00', '2020-11-06 04:28:43', NULL),
(3, 'AGUA BRUVA', '12.00', '10.00', '20.00', '16.00', 1, 0, 1, 10, '2018-05-25 00:07:37', '2020-11-06 04:28:33', NULL),
(4, 'BALÓN PREMIUM', '36.00', '25.00', '80.00', '65.00', 1, 1, 1, 10, '2018-09-20 03:27:18', '2020-11-06 04:28:21', NULL),
(5, 'BALÓN NORMAL', '37.00', '28.00', '80.00', '60.00', 1, 1, 1, 10, '2018-10-25 02:58:46', '2020-11-12 01:28:12', NULL),
(8, 'AGUA BRUVA KIT COMPLETO', '20.00', '13.00', NULL, NULL, 1, 0, 0, 10, '2018-10-27 17:00:11', '2020-11-06 04:28:47', '2020-11-06 04:28:47'),
(9, 'MANGUERA REFORZADA', '7.00', '3.00', NULL, NULL, 1, 0, 0, 10, '2018-11-16 04:36:21', '2020-09-09 15:42:48', NULL),
(12, 'REGULADOR FISHER', '29.00', '15.00', NULL, NULL, 1, 0, 0, 10, '2018-11-16 05:16:21', '2020-11-02 16:56:24', NULL),
(13, 'ALCOHOL 96% LT', '13.00', '8.00', NULL, NULL, 1, 1, 0, 10, '2018-12-07 22:44:58', '2020-11-05 14:55:54', NULL),
(14, 'AGUA NEUTRA KIT COMPLETO', '24.00', '15.00', NULL, NULL, 1, 0, 0, 10, '2020-07-08 20:49:19', '2020-11-06 04:28:50', '2020-11-06 04:28:50'),
(15, 'BALÓN NUEVO - RECIPIENTE', '80.00', '70.00', NULL, NULL, 1, 0, 0, 10, '2020-09-13 16:49:49', '2020-11-05 02:18:02', '2020-11-05 02:18:02'),
(16, 'BALÓN NORMAL + RECIPIENTE', '80.00', '70.00', NULL, NULL, 0, 1, NULL, NULL, '2020-11-05 02:17:37', '2020-11-05 14:17:04', '2020-11-05 14:17:04'),
(17, 'BALÓN PREMIUM + RECIPIENTE', '80.00', '70.00', NULL, NULL, 0, 1, NULL, NULL, '2020-11-05 02:17:54', '2020-11-05 14:17:08', '2020-11-05 14:17:08'),
(18, 'NUEVO', '12.00', '12.00', '10.00', '10.00', 0, 0, 1, NULL, '2020-11-06 04:29:18', '2020-11-06 04:29:26', '2020-11-06 04:29:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
`id` int(10) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `envases_total` int(11) DEFAULT NULL,
  `envases_llenos` int(11) DEFAULT NULL,
  `envases_vacios` int(11) DEFAULT NULL,
  `envases_prestados` int(11) DEFAULT NULL,
  `sucursal_id` int(10) unsigned NOT NULL,
  `producto_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`id`, `cantidad`, `envases_total`, `envases_llenos`, `envases_vacios`, `envases_prestados`, `sucursal_id`, `producto_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(26, 38, 40, 38, 2, NULL, 1, 5, '2020-12-08 17:53:28', '2020-12-24 22:19:53', NULL),
(27, 8, 10, 8, 2, NULL, 1, 4, '2020-12-08 17:53:28', '2020-12-24 18:30:41', NULL),
(28, 13, 15, 13, 2, NULL, 1, 3, '2020-12-08 17:53:28', '2020-12-24 18:25:25', NULL),
(29, 19, 20, 19, 1, NULL, 1, 1, '2020-12-08 17:53:28', '2020-12-24 18:19:51', NULL),
(30, 15, NULL, NULL, NULL, NULL, 1, 13, '2020-12-08 17:53:28', '2020-12-08 17:53:28', NULL),
(31, 11, NULL, NULL, NULL, NULL, 1, 9, '2020-12-08 17:53:28', '2020-12-24 18:40:59', NULL),
(32, 14, NULL, NULL, NULL, NULL, 1, 12, '2020-12-08 17:53:28', '2020-12-24 18:40:59', NULL),
(33, 13, 15, 13, 2, NULL, 2, 5, '2020-12-24 18:27:25', '2020-12-24 18:29:30', NULL),
(34, 4, 5, 4, 1, NULL, 2, 4, '2020-12-24 18:27:25', '2020-12-24 18:44:27', NULL),
(35, 10, 10, 10, 0, NULL, 2, 3, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(36, 6, 6, 6, 0, NULL, 2, 1, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(37, 10, NULL, NULL, NULL, NULL, 2, 13, '2020-12-24 18:27:25', '2020-12-24 18:27:25', NULL),
(38, 10, NULL, NULL, NULL, NULL, 2, 9, '2020-12-24 18:27:56', '2020-12-24 18:27:56', NULL),
(39, 10, NULL, NULL, NULL, NULL, 2, 12, '2020-12-24 18:27:56', '2020-12-24 18:27:56', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
`id` int(10) unsigned NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cant_balon_normal` int(11) DEFAULT NULL,
  `cant_balon_premium` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id`, `nombre`, `direccion`, `telefono`, `cant_balon_normal`, `cant_balon_premium`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PRINCIPAL', 'AV. LUIS GONZALES #1420', '074-485976', 60, 10, '2018-05-15 05:00:00', '2020-11-04 16:51:45', NULL),
(2, 'SUCURSAL 2', 'AV. EMILIANO NIÑO #114', '074-485963', 29, 10, '2018-05-17 02:10:52', '2020-11-04 16:54:40', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE IF NOT EXISTS `tipodocumento` (
`id` int(10) unsigned NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abreviatura` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipomovimiento_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipodocumento`
--

INSERT INTO `tipodocumento` (`id`, `descripcion`, `abreviatura`, `tipomovimiento_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'BOLETA DE VENTA', 'BV', 2, '2020-09-10 05:00:00', '2020-09-10 05:00:00', NULL),
(2, 'FACTURA DE VENTA', 'FV', 2, '2018-11-01 05:00:00', '2018-11-01 05:00:00', NULL),
(3, 'TICKET DE VENTA', 'TK', 2, '2018-11-01 05:00:00', '2018-11-01 05:00:00', NULL),
(4, 'FACTURA DE COMPRA', 'FC', 3, '2019-11-30 05:00:00', '2019-11-30 05:00:00', NULL),
(5, 'BOLETA DE COMPRA', 'BC', 3, '2019-11-30 05:00:00', '2019-11-30 05:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomovimiento`
--

CREATE TABLE IF NOT EXISTS `tipomovimiento` (
`id` int(10) unsigned NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipomovimiento`
--

INSERT INTO `tipomovimiento` (`id`, `descripcion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CAJA', '2018-11-13 04:51:50', '2018-11-13 04:51:50', NULL),
(2, 'VENTA', '2018-11-13 04:51:50', '2018-11-13 04:51:50', NULL),
(3, 'COMPRA', '2019-11-20 05:00:00', '2019-11-20 05:00:00', NULL),
(4, 'ALMACEN', '2019-11-20 05:00:00', '2019-11-20 05:00:00', NULL),
(5, 'CREDITO', '2020-08-10 05:00:00', '2020-08-10 05:00:00', NULL),
(6, 'GASTOS REPARTIDOR', '2020-10-15 05:00:00', '2020-10-15 05:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno_repartidor`
--

CREATE TABLE IF NOT EXISTS `turno_repartidor` (
`id` int(10) unsigned NOT NULL,
  `inicio` timestamp NULL DEFAULT NULL,
  `fin` timestamp NULL DEFAULT NULL,
  `estado` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apertura_id` int(10) unsigned NOT NULL,
  `trabajador_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `turno_repartidor`
--

INSERT INTO `turno_repartidor` (`id`, `inicio`, `fin`, `estado`, `apertura_id`, `trabajador_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(16, '2020-12-08 17:51:29', '2020-12-24 18:34:11', 'C', 141, 3, '2020-12-08 17:51:29', '2020-12-24 18:34:11', NULL),
(17, '2020-12-08 17:51:34', '2020-12-24 18:34:06', 'C', 141, 29, '2020-12-08 17:51:34', '2020-12-24 18:34:06', NULL),
(18, '2020-12-08 17:51:39', '2020-12-24 18:34:01', 'C', 141, 76, '2020-12-08 17:51:39', '2020-12-24 18:34:01', NULL),
(19, '2020-12-08 17:52:07', '2020-12-24 18:33:55', 'C', 145, 28, '2020-12-08 17:52:07', '2020-12-24 18:33:55', NULL),
(20, '2020-12-24 18:39:35', NULL, 'I', 173, 3, '2020-12-24 18:39:35', '2020-12-24 18:39:35', NULL),
(21, '2020-12-24 18:39:52', NULL, 'I', 177, 28, '2020-12-24 18:39:52', '2020-12-24 18:39:52', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(10) unsigned NOT NULL,
  `login` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'H',
  `usertype_id` int(10) unsigned NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `email`, `state`, `usertype_id`, `person_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', '$2y$10$bb.RBv4Blp4W8vzcfp0.muweC7h/Ynd8pi04Da6/qgOjO7Lp6oZcW', 'acuna.guevara.luis.edgardo@gmail.com', 'H', 1, 2, '0psp2YIIleQUHf6X8XJhgR5yOwlTFU4LSSPdxDNULox0owjqmIj5xKigTs5S', '2017-07-23 22:17:32', '2020-03-03 23:54:29', NULL),
(2, 'orejitas', '$2y$10$bb.RBv4Blp4W8vzcfp0.muweC7h/Ynd8pi04Da6/qgOjO7Lp6oZcW', NULL, 'H', 1, 3, NULL, '2020-07-04 02:59:10', '2020-07-04 02:59:10', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usertype`
--

CREATE TABLE IF NOT EXISTS `usertype` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usertype`
--

INSERT INTO `usertype` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ADMINISTRADOR PRINCIPAL', '2017-07-23 22:17:31', '2017-07-23 22:17:31', NULL),
(2, 'ADMINISTRADOR', '2017-07-23 22:17:31', '2020-06-27 22:52:31', '2020-06-27 22:52:31'),
(3, 'VERIFICADOR', '2017-07-23 22:17:31', '2020-06-26 00:55:49', '2020-06-26 00:55:49'),
(4, 'SUPERVISOR', '2017-07-23 22:17:31', '2020-06-26 00:55:47', '2020-06-26 00:55:47'),
(5, 'PRUEBA', '2018-11-11 16:20:08', '2020-02-23 22:55:55', '2020-02-23 22:55:55'),
(6, 'EQUIPO PROGRAMACIÓN', '2020-02-23 22:57:45', '2020-06-26 00:55:52', '2020-06-26 00:55:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `binnacle`
--
ALTER TABLE `binnacle`
 ADD PRIMARY KEY (`id`), ADD KEY `binnacle_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `concepto`
--
ALTER TABLE `concepto`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_mov_almacen`
--
ALTER TABLE `detalle_mov_almacen`
 ADD PRIMARY KEY (`id`), ADD KEY `detalle_mov_almacen_movimiento_id_foreign` (`movimiento_id`), ADD KEY `detalle_mov_almacen_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `detalle_pagos`
--
ALTER TABLE `detalle_pagos`
 ADD PRIMARY KEY (`id`), ADD KEY `detalle_pagos_pedido_id_foreign` (`pedido_id`);

--
-- Indices de la tabla `detalle_prestamo`
--
ALTER TABLE `detalle_prestamo`
 ADD PRIMARY KEY (`id`), ADD KEY `detalle_prestamo_detalle_mov_almacen_id_foreign` (`detalle_mov_almacen_id`);

--
-- Indices de la tabla `detalle_turno_pedido`
--
ALTER TABLE `detalle_turno_pedido`
 ADD PRIMARY KEY (`id`), ADD KEY `detalle_turno_pedido_pedido_id_foreign` (`pedido_id`), ADD KEY `detalle_turno_pedido_turno_id_foreign` (`turno_id`);

--
-- Indices de la tabla `kardex`
--
ALTER TABLE `kardex`
 ADD PRIMARY KEY (`id`), ADD KEY `kardex_detalle_mov_almacen_id_foreign` (`detalle_mov_almacen_id`), ADD KEY `kardex_sucursal_id_foreign` (`sucursal_id`);

--
-- Indices de la tabla `menuoption`
--
ALTER TABLE `menuoption`
 ADD PRIMARY KEY (`id`), ADD KEY `menuoption_menuoptioncategory_id_foreign` (`menuoptioncategory_id`);

--
-- Indices de la tabla `menuoptioncategory`
--
ALTER TABLE `menuoptioncategory`
 ADD PRIMARY KEY (`id`), ADD KEY `menuoptioncategory_menuoptioncategory_id_foreign` (`menuoptioncategory_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimiento`
--
ALTER TABLE `movimiento`
 ADD PRIMARY KEY (`id`), ADD KEY `concepto_id` (`concepto_id`), ADD KEY `tipomovimiento_id` (`tipomovimiento_id`), ADD KEY `venta_id` (`venta_id`), ADD KEY `sucursal_id` (`sucursal_id`), ADD KEY `usuario_id` (`usuario_id`), ADD KEY `cliente_id` (`persona_id`), ADD KEY `trabajador_id` (`trabajador_id`);

--
-- Indices de la tabla `operacion`
--
ALTER TABLE `operacion`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `operacion_menu`
--
ALTER TABLE `operacion_menu`
 ADD PRIMARY KEY (`id`), ADD KEY `operacion_menu_operacion_id_foreign` (`operacion_id`), ADD KEY `operacion_menu_menuoption_id_foreign` (`menuoption_id`);

--
-- Indices de la tabla `permiso_operacion`
--
ALTER TABLE `permiso_operacion`
 ADD PRIMARY KEY (`id`), ADD KEY `permiso_operacion_operacionmenu_id_foreign` (`operacionmenu_id`), ADD KEY `permiso_operacion_usertype_id_foreign` (`usertype_id`);

--
-- Indices de la tabla `permission`
--
ALTER TABLE `permission`
 ADD PRIMARY KEY (`id`), ADD KEY `permission_usertype_id_foreign` (`usertype_id`), ADD KEY `permission_menuoption_id_foreign` (`menuoption_id`);

--
-- Indices de la tabla `person`
--
ALTER TABLE `person`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
 ADD PRIMARY KEY (`id`), ADD KEY `stock_producto_id_foreign` (`producto_id`), ADD KEY `stock_sucursal_id_foreign` (`sucursal_id`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
 ADD PRIMARY KEY (`id`), ADD KEY `tipodocumento_tipomovimiento_id_foreign` (`tipomovimiento_id`);

--
-- Indices de la tabla `tipomovimiento`
--
ALTER TABLE `tipomovimiento`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `turno_repartidor`
--
ALTER TABLE `turno_repartidor`
 ADD PRIMARY KEY (`id`), ADD KEY `turno_repartidor_apertura_id_foreign` (`apertura_id`), ADD KEY `turno_repartidor_trabajador_id_foreign` (`trabajador_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user_login_unique` (`login`), ADD KEY `user_usertype_id_foreign` (`usertype_id`), ADD KEY `user_person_id_foreign` (`person_id`);

--
-- Indices de la tabla `usertype`
--
ALTER TABLE `usertype`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `binnacle`
--
ALTER TABLE `binnacle`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `concepto`
--
ALTER TABLE `concepto`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `detalle_mov_almacen`
--
ALTER TABLE `detalle_mov_almacen`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT de la tabla `detalle_pagos`
--
ALTER TABLE `detalle_pagos`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `detalle_prestamo`
--
ALTER TABLE `detalle_prestamo`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `detalle_turno_pedido`
--
ALTER TABLE `detalle_turno_pedido`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT de la tabla `kardex`
--
ALTER TABLE `kardex`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT de la tabla `menuoption`
--
ALTER TABLE `menuoption`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT de la tabla `menuoptioncategory`
--
ALTER TABLE `menuoptioncategory`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT de la tabla `movimiento`
--
ALTER TABLE `movimiento`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=188;
--
-- AUTO_INCREMENT de la tabla `operacion`
--
ALTER TABLE `operacion`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `operacion_menu`
--
ALTER TABLE `operacion_menu`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `permiso_operacion`
--
ALTER TABLE `permiso_operacion`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `permission`
--
ALTER TABLE `permission`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=714;
--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `tipomovimiento`
--
ALTER TABLE `tipomovimiento`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `turno_repartidor`
--
ALTER TABLE `turno_repartidor`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `usertype`
--
ALTER TABLE `usertype`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `binnacle`
--
ALTER TABLE `binnacle`
ADD CONSTRAINT `binnacle_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `detalle_mov_almacen`
--
ALTER TABLE `detalle_mov_almacen`
ADD CONSTRAINT `detalle_mov_almacen_movimiento_id_foreign` FOREIGN KEY (`movimiento_id`) REFERENCES `movimiento` (`id`),
ADD CONSTRAINT `detalle_mov_almacen_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `detalle_pagos`
--
ALTER TABLE `detalle_pagos`
ADD CONSTRAINT `detalle_pagos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `movimiento` (`id`);

--
-- Filtros para la tabla `detalle_prestamo`
--
ALTER TABLE `detalle_prestamo`
ADD CONSTRAINT `detalle_prestamo_detalle_mov_almacen_id_foreign` FOREIGN KEY (`detalle_mov_almacen_id`) REFERENCES `detalle_mov_almacen` (`id`);

--
-- Filtros para la tabla `detalle_turno_pedido`
--
ALTER TABLE `detalle_turno_pedido`
ADD CONSTRAINT `detalle_turno_pedido_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `movimiento` (`id`),
ADD CONSTRAINT `detalle_turno_pedido_turno_id_foreign` FOREIGN KEY (`turno_id`) REFERENCES `turno_repartidor` (`id`);

--
-- Filtros para la tabla `kardex`
--
ALTER TABLE `kardex`
ADD CONSTRAINT `kardex_detalle_mov_almacen_id_foreign` FOREIGN KEY (`detalle_mov_almacen_id`) REFERENCES `detalle_mov_almacen` (`id`),
ADD CONSTRAINT `kardex_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursal` (`id`);

--
-- Filtros para la tabla `menuoption`
--
ALTER TABLE `menuoption`
ADD CONSTRAINT `menuoption_menuoptioncategory_id_foreign` FOREIGN KEY (`menuoptioncategory_id`) REFERENCES `menuoptioncategory` (`id`);

--
-- Filtros para la tabla `menuoptioncategory`
--
ALTER TABLE `menuoptioncategory`
ADD CONSTRAINT `menuoptioncategory_menuoptioncategory_id_foreign` FOREIGN KEY (`menuoptioncategory_id`) REFERENCES `menuoptioncategory` (`id`);

--
-- Filtros para la tabla `movimiento`
--
ALTER TABLE `movimiento`
ADD CONSTRAINT `cliente_id` FOREIGN KEY (`persona_id`) REFERENCES `person` (`id`),
ADD CONSTRAINT `concepto_id` FOREIGN KEY (`concepto_id`) REFERENCES `concepto` (`id`),
ADD CONSTRAINT `sucursal_id` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursal` (`id`),
ADD CONSTRAINT `tipomovimiento_id` FOREIGN KEY (`tipomovimiento_id`) REFERENCES `tipomovimiento` (`id`),
ADD CONSTRAINT `trabajador_id` FOREIGN KEY (`trabajador_id`) REFERENCES `person` (`id`),
ADD CONSTRAINT `usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `user` (`id`),
ADD CONSTRAINT `venta_id` FOREIGN KEY (`venta_id`) REFERENCES `movimiento` (`id`);

--
-- Filtros para la tabla `operacion_menu`
--
ALTER TABLE `operacion_menu`
ADD CONSTRAINT `operacion_menu_menuoption_id_foreign` FOREIGN KEY (`menuoption_id`) REFERENCES `menuoption` (`id`),
ADD CONSTRAINT `operacion_menu_operacion_id_foreign` FOREIGN KEY (`operacion_id`) REFERENCES `operacion` (`id`);

--
-- Filtros para la tabla `permiso_operacion`
--
ALTER TABLE `permiso_operacion`
ADD CONSTRAINT `permiso_operacion_operacionmenu_id_foreign` FOREIGN KEY (`operacionmenu_id`) REFERENCES `operacion_menu` (`id`),
ADD CONSTRAINT `permiso_operacion_usertype_id_foreign` FOREIGN KEY (`usertype_id`) REFERENCES `usertype` (`id`);

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
ADD CONSTRAINT `stock_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
ADD CONSTRAINT `tipodocumento_tipomovimiento_id_foreign` FOREIGN KEY (`tipomovimiento_id`) REFERENCES `tipomovimiento` (`id`);

--
-- Filtros para la tabla `turno_repartidor`
--
ALTER TABLE `turno_repartidor`
ADD CONSTRAINT `turno_repartidor_apertura_id_foreign` FOREIGN KEY (`apertura_id`) REFERENCES `movimiento` (`id`),
ADD CONSTRAINT `turno_repartidor_trabajador_id_foreign` FOREIGN KEY (`trabajador_id`) REFERENCES `person` (`id`);

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
ADD CONSTRAINT `user_person_id_foreign` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
ADD CONSTRAINT `user_usertype_id_foreign` FOREIGN KEY (`usertype_id`) REFERENCES `usertype` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
