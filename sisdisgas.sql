-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2022 a las 05:32:05
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sisdisgas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `binnacle`
--

CREATE TABLE `binnacle` (
  `id` int(10) UNSIGNED NOT NULL,
  `action` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `recordid` int(10) UNSIGNED NOT NULL,
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

CREATE TABLE `concepto` (
  `id` int(10) UNSIGNED NOT NULL,
  `concepto` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(18, 'SALIDA DE ALMACÉN', 1, '2020-10-27 05:00:00', '2020-10-20 05:00:00', NULL),
(19, 'PAGO DE DEUDA POR COMPRA AL PROVEEDOR', 1, '2022-10-10 05:00:00', '2022-10-10 05:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config_general`
--

CREATE TABLE `config_general` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `config_general`
--

INSERT INTO `config_general` (`id`, `nombre`, `descripcion`, `valor`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DESCUENTO FISE', 'PRECIO DE DESCUENTO DE VALE FISE PARA BALÓN DE GAS', '25', '2022-09-20 03:44:28', '2022-09-20 03:50:22', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_mov_almacen`
--

CREATE TABLE `detalle_mov_almacen` (
  `id` int(10) UNSIGNED NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_envase` decimal(10,2) DEFAULT NULL,
  `cantidad_envase` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `movimiento_id` int(10) UNSIGNED NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `detalle_mov_almacen`
--

INSERT INTO `detalle_mov_almacen` (`id`, `precio`, `cantidad`, `precio_envase`, `cantidad_envase`, `subtotal`, `movimiento_id`, `producto_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '45.00', 0, '110.00', 30, '3300.00', 2, 4, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(2, '45.00', 0, '110.00', 56, '6160.00', 2, 5, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(3, '5.00', 0, '25.00', 32, '800.00', 2, 1, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(4, '5.00', 15, NULL, 0, '75.00', 2, 9, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(5, '13.00', 26, NULL, 0, '338.00', 2, 19, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(6, '23.00', 30, NULL, 0, '690.00', 2, 12, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(7, '54.00', 14, '110.00', 0, '756.00', 3, 5, '2022-10-08 15:23:50', '2022-10-08 15:23:50', NULL),
(8, '54.00', 5, '110.00', 0, '270.00', 3, 4, '2022-10-08 15:23:50', '2022-10-08 15:23:50', NULL),
(9, '10.00', 6, '25.00', 0, '60.00', 3, 1, '2022-10-08 15:23:51', '2022-10-08 15:23:51', NULL),
(10, '54.00', 1, '110.00', 0, '54.00', 6, 5, '2022-10-10 04:26:09', '2022-10-10 04:26:09', NULL),
(11, '54.00', 1, '110.00', 0, '54.00', 7, 4, '2022-10-10 04:26:33', '2022-10-10 04:26:33', NULL),
(12, '54.00', 1, '110.00', 0, '54.00', 8, 5, '2022-10-10 04:28:15', '2022-10-10 04:28:15', NULL),
(13, '54.00', 1, '110.00', 0, '54.00', 9, 4, '2022-10-10 04:29:05', '2022-10-10 04:29:05', NULL),
(14, '54.00', 1, '110.00', 0, '54.00', 13, 4, '2022-10-10 04:32:33', '2022-10-10 04:32:33', NULL),
(15, '54.00', 1, '110.00', 0, '54.00', 14, 4, '2022-10-10 04:33:03', '2022-10-10 04:33:03', NULL),
(16, '54.00', 1, '110.00', 0, '54.00', 15, 4, '2022-10-10 04:35:37', '2022-10-10 04:35:37', NULL),
(17, '54.00', 1, '110.00', 0, '54.00', 16, 5, '2022-10-10 04:39:40', '2022-10-10 04:39:40', NULL),
(18, '54.00', 1, '110.00', 0, '54.00', 17, 5, '2022-10-10 04:41:17', '2022-10-10 04:41:17', NULL),
(19, '54.00', 1, '110.00', 0, '54.00', 18, 5, '2022-10-10 04:44:00', '2022-10-10 04:44:00', NULL),
(20, '54.00', 1, '110.00', 0, '54.00', 20, 4, '2022-10-10 04:45:14', '2022-10-10 04:45:14', NULL),
(21, '54.00', 1, '110.00', 0, '54.00', 22, 4, '2022-10-10 04:47:56', '2022-10-10 04:47:56', NULL),
(22, '18.00', 1, '0.00', 0, '18.00', 22, 19, '2022-10-10 04:47:56', '2022-10-10 04:47:56', NULL),
(23, '54.00', 1, '110.00', 0, '54.00', 23, 5, '2022-10-10 04:50:31', '2022-10-10 04:50:31', NULL),
(24, '54.00', 1, '110.00', 0, '54.00', 24, 5, '2022-10-10 04:51:42', '2022-10-10 04:51:42', NULL),
(25, '54.00', 2, '110.00', 0, '108.00', 27, 5, '2022-10-10 04:54:02', '2022-10-10 04:54:02', NULL),
(26, '54.00', 1, '110.00', 0, '54.00', 29, 4, '2022-10-10 04:55:20', '2022-10-10 04:55:20', NULL),
(27, '54.00', 1, '110.00', 0, '54.00', 30, 4, '2022-10-10 04:58:12', '2022-10-10 04:58:12', NULL),
(28, '54.00', 1, '110.00', 0, '54.00', 31, 4, '2022-10-10 05:03:54', '2022-10-10 05:03:54', NULL),
(29, '54.00', 1, '110.00', 0, '54.00', 32, 5, '2022-10-10 05:07:28', '2022-10-10 05:07:28', NULL),
(30, '54.00', 1, '110.00', 0, '54.00', 33, 5, '2022-10-10 05:13:02', '2022-10-10 05:13:02', NULL),
(31, '54.00', 1, '110.00', 0, '54.00', 35, 5, '2022-10-10 23:15:00', '2022-10-10 23:15:00', NULL),
(32, '45.00', 1, '110.00', 0, '45.00', 37, 5, '2022-10-11 00:12:37', '2022-10-11 00:12:37', NULL),
(33, '45.00', 1, '110.00', 0, '45.00', 39, 5, '2022-10-11 00:30:58', '2022-10-11 00:30:58', NULL),
(34, '45.00', 0, '110.00', 2, '220.00', 40, 4, '2022-10-11 00:36:15', '2022-10-11 00:36:15', NULL),
(35, '45.00', 3, '110.00', 0, '135.00', 40, 5, '2022-10-11 00:36:15', '2022-10-11 00:36:15', NULL),
(36, '45.00', 0, '110.00', 2, '220.00', 42, 4, '2022-10-11 00:37:36', '2022-10-11 00:37:36', NULL),
(37, '45.00', 3, '110.00', 0, '135.00', 42, 5, '2022-10-11 00:37:36', '2022-10-11 00:37:36', NULL),
(38, '45.00', 0, '110.00', 5, '550.00', 43, 4, '2022-10-11 00:38:56', '2022-10-11 00:38:56', NULL),
(39, '45.00', 3, '110.00', 0, '135.00', 43, 5, '2022-10-11 00:38:57', '2022-10-11 00:38:57', NULL),
(40, '45.00', 1, '110.00', 0, '45.00', 45, 5, '2022-10-11 00:46:25', '2022-10-11 00:46:25', NULL),
(41, '45.00', 0, '110.00', 1, '110.00', 45, 4, '2022-10-11 00:46:25', '2022-10-11 00:46:25', NULL),
(42, '45.00', 1, '110.00', 0, '45.00', 47, 5, '2022-10-11 00:49:35', '2022-10-11 00:49:35', NULL),
(43, '45.00', 0, '110.00', 1, '110.00', 47, 4, '2022-10-11 00:49:35', '2022-10-11 00:49:35', NULL),
(44, '45.00', 1, '110.00', 0, '45.00', 48, 5, '2022-10-11 00:51:21', '2022-10-11 00:51:21', NULL),
(45, '45.00', 0, '110.00', 1, '110.00', 48, 4, '2022-10-11 00:51:21', '2022-10-11 00:51:21', NULL),
(46, '45.00', 1, '110.00', 0, '45.00', 53, 5, '2022-10-11 01:07:14', '2022-10-11 01:07:14', NULL),
(47, '45.00', 0, '110.00', 1, '110.00', 53, 4, '2022-10-11 01:07:14', '2022-10-11 01:07:14', NULL),
(48, '54.00', 1, '110.00', 0, '54.00', 60, 5, '2022-11-05 03:22:55', '2022-11-05 03:22:55', NULL),
(49, '54.00', 1, '110.00', 0, '54.00', 61, 5, '2022-11-05 03:27:17', '2022-11-05 03:27:17', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pagos`
--

CREATE TABLE `detalle_pagos` (
  `id` int(10) UNSIGNED NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `tipo` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credito` int(11) NOT NULL,
  `pedido_id` int(10) UNSIGNED NOT NULL,
  `metodo_pago_id` int(10) UNSIGNED NOT NULL,
  `pago_credito_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `detalle_pagos`
--

INSERT INTO `detalle_pagos` (`id`, `monto`, `tipo`, `credito`, `pedido_id`, `metodo_pago_id`, `pago_credito_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '60.00', 'R', 0, 6, 1, 6, '2022-10-10 04:26:09', '2022-10-10 04:26:09', NULL),
(2, '30.00', 'R', 0, 7, 1, 7, '2022-10-10 04:26:33', '2022-10-10 04:26:33', NULL),
(3, '24.00', 'R', 0, 7, 2, 7, '2022-10-10 04:26:33', '2022-10-10 04:26:33', NULL),
(4, '24.00', 'R', 0, 8, 1, 8, '2022-10-10 04:28:15', '2022-10-10 04:28:15', NULL),
(5, '54.00', 'R', 0, 9, 2, 9, '2022-10-10 04:29:05', '2022-10-10 04:29:05', NULL),
(6, '10.00', 'R', 1, 8, 1, 10, '2022-10-10 04:29:34', '2022-10-10 04:29:34', NULL),
(7, '10.00', 'R', 1, 8, 1, 11, '2022-10-10 04:29:41', '2022-10-10 04:48:56', '2022-10-10 04:48:56'),
(8, '10.00', 'S', 1, 8, 1, 12, '2022-10-10 04:29:56', '2022-10-10 04:46:30', '2022-10-10 04:46:30'),
(9, '54.00', 'R', 0, 13, 3, 13, '2022-10-10 04:32:33', '2022-10-10 04:32:33', NULL),
(10, '60.00', 'R', 0, 14, 1, 14, '2022-10-10 04:33:03', '2022-10-10 04:33:03', NULL),
(11, '55.00', 'R', 0, 15, 1, 15, '2022-10-10 04:35:37', '2022-10-10 04:35:37', NULL),
(12, '54.00', 'R', 0, 16, 3, 16, '2022-10-10 04:39:40', '2022-10-10 04:39:40', NULL),
(13, '20.00', 'S', 0, 18, 1, 19, '2022-10-10 04:44:00', '2022-10-10 04:44:00', NULL),
(14, '20.00', 'S', 0, 18, 2, 19, '2022-10-10 04:44:00', '2022-10-10 04:44:00', NULL),
(15, '20.00', 'S', 0, 18, 3, 19, '2022-10-10 04:44:00', '2022-10-10 04:44:00', NULL),
(16, '60.00', 'R', 0, 20, 1, 20, '2022-10-10 04:45:14', '2022-10-10 04:45:14', NULL),
(17, '80.00', 'R', 0, 22, 1, 22, '2022-10-10 04:47:56', '2022-10-10 04:47:56', NULL),
(18, '24.00', 'R', 0, 23, 1, 23, '2022-10-10 04:50:31', '2022-10-10 04:50:31', NULL),
(19, '30.00', 'R', 0, 23, 2, 23, '2022-10-10 04:50:31', '2022-10-10 04:50:31', NULL),
(20, '60.00', 'R', 0, 24, 1, 24, '2022-10-10 04:51:42', '2022-10-10 04:51:42', NULL),
(21, '60.00', 'S', 0, 27, 1, 28, '2022-10-10 04:54:02', '2022-10-10 04:54:02', NULL),
(22, '50.00', 'S', 0, 27, 2, 28, '2022-10-10 04:54:02', '2022-10-10 04:54:02', NULL),
(23, '60.00', 'R', 0, 29, 1, 29, '2022-10-10 04:55:20', '2022-10-10 04:55:20', NULL),
(24, '60.00', 'R', 0, 31, 1, 31, '2022-10-10 05:03:54', '2022-10-10 05:03:54', NULL),
(25, '60.00', 'R', 0, 32, 1, NULL, '2022-10-10 05:07:28', '2022-10-10 05:07:28', NULL),
(26, '60.00', 'S', 0, 33, 1, NULL, '2022-10-10 05:13:02', '2022-10-10 05:13:02', NULL),
(27, '24.00', 'S', 0, 35, 1, 36, '2022-10-10 23:15:00', '2022-10-10 23:15:00', NULL),
(28, '45.00', 'C', 0, 37, 1, NULL, '2022-10-11 00:12:37', '2022-10-11 00:12:37', NULL),
(29, '355.00', 'C', 0, 40, 1, NULL, '2022-10-11 00:36:15', '2022-10-11 00:36:15', NULL),
(30, '155.00', 'C', 0, 45, 1, NULL, '2022-10-11 00:46:25', '2022-10-11 00:46:25', NULL),
(31, '55.00', 'C', 0, 48, 1, NULL, '2022-10-11 00:51:21', '2022-10-11 00:51:21', NULL),
(32, '20.00', 'C', 1, 48, 1, 50, '2022-10-11 01:03:45', '2022-10-11 01:04:51', '2022-10-11 01:04:51'),
(33, '10.00', 'C', 1, 48, 1, 51, '2022-10-11 01:07:03', '2022-10-11 01:07:03', NULL),
(34, '20.00', 'C', 1, 48, 1, 52, '2022-10-11 01:07:06', '2022-10-11 01:07:06', NULL),
(35, '100.00', 'C', 1, 43, 1, 54, '2022-10-11 01:07:55', '2022-10-11 01:07:55', NULL),
(36, '5.00', 'R', 0, 60, 1, NULL, '2022-11-05 03:22:55', '2022-11-05 03:22:55', NULL),
(37, '20.00', 'R', 0, 61, 1, 61, '2022-11-05 03:27:17', '2022-11-05 03:27:17', NULL),
(38, '15.00', 'R', 1, 61, 1, 62, '2022-11-05 03:29:54', '2022-11-05 03:29:54', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_prestamo`
--

CREATE TABLE `detalle_prestamo` (
  `id` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipo` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalle_mov_almacen_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `detalle_prestamo`
--

INSERT INTO `detalle_prestamo` (`id`, `cantidad`, `fecha`, `tipo`, `detalle_mov_almacen_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '2022-11-05 04:21:52', 'P', 48, '2022-11-05 04:21:52', '2022-11-05 04:21:52', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_turno_pedido`
--

CREATE TABLE `detalle_turno_pedido` (
  `id` int(10) UNSIGNED NOT NULL,
  `pedido_id` int(10) UNSIGNED NOT NULL,
  `turno_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `detalle_turno_pedido`
--

INSERT INTO `detalle_turno_pedido` (`id`, `pedido_id`, `turno_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 1, '2022-10-10 04:23:57', '2022-10-10 04:23:57', NULL),
(2, 5, 2, '2022-10-10 04:24:17', '2022-10-10 04:24:17', NULL),
(3, 6, 1, '2022-10-10 04:26:09', '2022-10-10 04:26:09', NULL),
(4, 7, 2, '2022-10-10 04:26:33', '2022-10-10 04:26:33', NULL),
(5, 8, 1, '2022-10-10 04:28:15', '2022-10-10 04:28:15', NULL),
(6, 9, 1, '2022-10-10 04:29:05', '2022-10-10 04:29:05', NULL),
(7, 10, 2, '2022-10-10 04:29:34', '2022-10-10 04:29:34', NULL),
(8, 11, 1, '2022-10-10 04:29:41', '2022-10-10 04:29:41', NULL),
(9, 13, 2, '2022-10-10 04:32:33', '2022-10-10 04:32:33', NULL),
(10, 14, 2, '2022-10-10 04:33:03', '2022-10-10 04:33:03', NULL),
(11, 15, 1, '2022-10-10 04:35:37', '2022-10-10 04:35:37', NULL),
(12, 16, 2, '2022-10-10 04:39:40', '2022-10-10 04:39:40', NULL),
(13, 20, 2, '2022-10-10 04:45:14', '2022-10-10 04:45:14', NULL),
(14, 21, 1, '2022-10-10 04:45:37', '2022-10-10 04:45:37', NULL),
(15, 22, 1, '2022-10-10 04:47:56', '2022-10-10 04:47:56', NULL),
(16, 23, 2, '2022-10-10 04:50:31', '2022-10-10 04:50:31', NULL),
(17, 24, 1, '2022-10-10 04:51:42', '2022-10-10 04:51:42', NULL),
(18, 25, 1, '2022-10-10 04:52:40', '2022-10-10 04:52:40', NULL),
(19, 26, 1, '2022-10-10 04:52:51', '2022-10-10 04:52:51', NULL),
(20, 29, 1, '2022-10-10 04:55:20', '2022-10-10 04:55:20', NULL),
(21, 30, 1, '2022-10-10 04:58:12', '2022-10-10 04:58:12', NULL),
(22, 31, 2, '2022-10-10 05:03:54', '2022-10-10 05:03:54', NULL),
(23, 32, 2, '2022-10-10 05:07:28', '2022-10-10 05:07:28', NULL),
(24, 55, 2, '2022-10-11 01:09:00', '2022-10-11 01:09:00', NULL),
(25, 57, 3, '2022-10-12 00:42:28', '2022-10-12 00:42:28', NULL),
(26, 58, 4, '2022-10-12 00:52:44', '2022-10-12 00:52:44', NULL),
(27, 59, 5, '2022-11-05 03:14:28', '2022-11-05 03:14:28', NULL),
(28, 60, 5, '2022-11-05 03:22:55', '2022-11-05 03:22:55', NULL),
(29, 61, 1, '2022-11-05 03:27:17', '2022-11-05 03:27:17', NULL),
(30, 62, 4, '2022-11-05 03:29:54', '2022-11-05 03:29:54', NULL),
(31, 63, 5, '2022-11-05 03:31:16', '2022-11-05 03:31:16', NULL),
(32, 64, 5, '2022-11-05 03:31:54', '2022-11-05 03:31:54', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex`
--

CREATE TABLE `kardex` (
  `id` int(10) UNSIGNED NOT NULL,
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
  `sucursal_id` int(10) UNSIGNED NOT NULL,
  `detalle_mov_almacen_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `kardex`
--

INSERT INTO `kardex` (`id`, `fecha`, `cantidad`, `cantidad_envase`, `tipo`, `precio_compra`, `precio_venta`, `precio_compra_envase`, `precio_venta_envase`, `stock_anterior`, `stock_actual`, `sucursal_id`, `detalle_mov_almacen_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2022-10-08 15:22:49', 0, 30, 'I', '45.00', NULL, '110.00', NULL, 0, 30, 1, 1, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(2, '2022-10-08 15:22:49', 0, 56, 'I', '45.00', NULL, '110.00', NULL, 0, 56, 1, 2, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(3, '2022-10-08 15:22:49', 0, 32, 'I', '5.00', NULL, '25.00', NULL, 0, 32, 1, 3, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(4, '2022-10-08 15:22:49', 15, 0, 'I', '5.00', NULL, NULL, NULL, 0, 15, 1, 4, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(5, '2022-10-08 15:22:49', 26, 0, 'I', '13.00', NULL, NULL, NULL, 0, 26, 1, 5, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(6, '2022-10-08 15:22:49', 30, 0, 'I', '23.00', NULL, NULL, NULL, 0, 30, 1, 6, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(7, '2022-10-08 15:23:50', 14, 0, 'E', NULL, '54.00', NULL, '110.00', 56, 42, 1, 7, '2022-10-08 15:23:50', '2022-10-08 15:23:50', NULL),
(8, '2022-10-08 15:23:51', 5, 0, 'E', NULL, '54.00', NULL, '110.00', 30, 25, 1, 8, '2022-10-08 15:23:51', '2022-10-08 15:23:51', NULL),
(9, '2022-10-08 15:23:51', 6, 0, 'E', NULL, '10.00', NULL, '25.00', 32, 26, 1, 9, '2022-10-08 15:23:51', '2022-10-08 15:23:51', NULL),
(10, '2022-10-10 04:26:09', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 42, 41, 1, 10, '2022-10-10 04:26:09', '2022-10-10 04:26:09', NULL),
(11, '2022-10-10 04:26:33', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 25, 24, 1, 11, '2022-10-10 04:26:33', '2022-10-10 04:26:33', NULL),
(12, '2022-10-10 04:28:15', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 41, 40, 1, 12, '2022-10-10 04:28:15', '2022-10-10 04:28:15', NULL),
(13, '2022-10-10 04:29:05', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 24, 23, 1, 13, '2022-10-10 04:29:05', '2022-10-10 04:29:05', NULL),
(14, '2022-10-10 04:32:33', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 23, 22, 1, 14, '2022-10-10 04:32:33', '2022-10-10 04:32:33', NULL),
(15, '2022-10-10 04:33:03', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 22, 21, 1, 15, '2022-10-10 04:33:03', '2022-10-10 04:33:03', NULL),
(16, '2022-10-10 04:35:37', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 21, 20, 1, 16, '2022-10-10 04:35:37', '2022-10-10 04:35:37', NULL),
(17, '2022-10-10 04:39:40', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 40, 39, 1, 17, '2022-10-10 04:39:40', '2022-10-10 04:39:40', NULL),
(18, '2022-10-10 04:41:17', 1, 0, 'I', NULL, '54.00', NULL, '110.00', 39, 40, 1, 18, '2022-10-10 04:41:17', '2022-10-10 04:41:17', NULL),
(19, '2022-10-10 04:44:00', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 40, 39, 1, 19, '2022-10-10 04:44:00', '2022-10-10 04:44:00', NULL),
(20, '2022-10-10 04:45:14', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 20, 19, 1, 20, '2022-10-10 04:45:14', '2022-10-10 04:45:14', NULL),
(21, '2022-10-10 04:47:56', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 19, 18, 1, 21, '2022-10-10 04:47:56', '2022-10-10 04:47:56', NULL),
(22, '2022-10-10 04:47:56', 1, 0, 'E', NULL, '18.00', NULL, '0.00', 26, 25, 1, 22, '2022-10-10 04:47:56', '2022-10-10 04:47:56', NULL),
(23, '2022-10-10 04:50:31', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 39, 38, 1, 23, '2022-10-10 04:50:31', '2022-10-10 04:50:31', NULL),
(24, '2022-10-10 04:51:42', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 38, 37, 1, 24, '2022-10-10 04:51:42', '2022-10-10 04:51:42', NULL),
(25, '2022-10-10 04:54:02', 2, 0, 'E', NULL, '54.00', NULL, '110.00', 37, 35, 1, 25, '2022-10-10 04:54:02', '2022-10-10 04:54:02', NULL),
(26, '2022-10-10 04:55:20', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 18, 17, 1, 26, '2022-10-10 04:55:20', '2022-10-10 04:55:20', NULL),
(27, '2022-10-10 04:58:12', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 17, 16, 1, 27, '2022-10-10 04:58:12', '2022-10-10 04:58:12', NULL),
(28, '2022-10-10 05:03:54', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 16, 15, 1, 28, '2022-10-10 05:03:54', '2022-10-10 05:03:54', NULL),
(29, '2022-10-10 05:07:28', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 35, 34, 1, 29, '2022-10-10 05:07:28', '2022-10-10 05:07:28', NULL),
(30, '2022-10-10 05:13:02', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 34, 33, 1, 30, '2022-10-10 05:13:02', '2022-10-10 05:13:02', NULL),
(31, '2022-10-10 23:15:00', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 33, 32, 1, 31, '2022-10-10 23:15:00', '2022-10-10 23:15:00', NULL),
(32, '2022-10-11 00:12:37', 1, 0, 'I', '45.00', NULL, '110.00', NULL, 32, 33, 1, 32, '2022-10-11 00:12:37', '2022-10-11 00:12:37', NULL),
(33, '2022-10-11 00:30:58', 1, 0, 'E', NULL, NULL, NULL, NULL, 33, 32, 1, 33, '2022-10-11 00:30:58', '2022-10-11 00:30:58', NULL),
(34, '2022-10-11 00:36:15', 0, 2, 'I', '45.00', NULL, '110.00', NULL, 15, 17, 1, 34, '2022-10-11 00:36:15', '2022-10-11 00:36:15', NULL),
(35, '2022-10-11 00:36:15', 3, 0, 'I', '45.00', NULL, '110.00', NULL, 32, 35, 1, 35, '2022-10-11 00:36:15', '2022-10-11 00:36:15', NULL),
(36, '2022-10-11 00:37:36', 0, 2, 'E', NULL, NULL, NULL, NULL, 17, 15, 1, 36, '2022-10-11 00:37:36', '2022-10-11 00:37:36', NULL),
(37, '2022-10-11 00:37:36', 3, 0, 'E', NULL, NULL, NULL, NULL, 35, 32, 1, 37, '2022-10-11 00:37:36', '2022-10-11 00:37:36', NULL),
(38, '2022-10-11 00:38:57', 0, 5, 'I', '45.00', NULL, '110.00', NULL, 15, 20, 1, 38, '2022-10-11 00:38:57', '2022-10-11 00:38:57', NULL),
(39, '2022-10-11 00:38:57', 3, 0, 'I', '45.00', NULL, '110.00', NULL, 32, 35, 1, 39, '2022-10-11 00:38:57', '2022-10-11 00:38:57', NULL),
(40, '2022-10-11 00:46:25', 1, 0, 'I', '45.00', NULL, '110.00', NULL, 35, 36, 1, 40, '2022-10-11 00:46:25', '2022-10-11 00:46:25', NULL),
(41, '2022-10-11 00:46:25', 0, 1, 'I', '45.00', NULL, '110.00', NULL, 20, 21, 1, 41, '2022-10-11 00:46:25', '2022-10-11 00:46:25', NULL),
(42, '2022-10-11 00:49:35', 1, 0, 'E', NULL, NULL, NULL, NULL, 36, 35, 1, 42, '2022-10-11 00:49:35', '2022-10-11 00:49:35', NULL),
(43, '2022-10-11 00:49:35', 0, 1, 'E', NULL, NULL, NULL, NULL, 21, 20, 1, 43, '2022-10-11 00:49:35', '2022-10-11 00:49:35', NULL),
(44, '2022-10-11 00:51:21', 1, 0, 'I', '45.00', NULL, '110.00', NULL, 35, 36, 1, 44, '2022-10-11 00:51:21', '2022-10-11 00:51:21', NULL),
(45, '2022-10-11 00:51:21', 0, 1, 'I', '45.00', NULL, '110.00', NULL, 20, 21, 1, 45, '2022-10-11 00:51:21', '2022-10-11 00:51:21', NULL),
(46, '2022-10-11 01:07:14', 1, 0, 'E', NULL, NULL, NULL, NULL, 36, 35, 1, 46, '2022-10-11 01:07:14', '2022-10-11 01:07:14', NULL),
(47, '2022-10-11 01:07:14', 0, 1, 'E', NULL, NULL, NULL, NULL, 21, 20, 1, 47, '2022-10-11 01:07:14', '2022-10-11 01:07:14', NULL),
(48, '2022-11-05 03:22:55', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 35, 34, 1, 48, '2022-11-05 03:22:55', '2022-11-05 03:22:55', NULL),
(49, '2022-11-05 03:27:17', 1, 0, 'E', NULL, '54.00', NULL, '110.00', 34, 33, 1, 49, '2022-11-05 03:27:17', '2022-11-05 03:27:17', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menuoption`
--

CREATE TABLE `menuoption` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `menuoptioncategory_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `menuoption`
--

INSERT INTO `menuoption` (`id`, `name`, `link`, `order`, `menuoptioncategory_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Categoría de opción de menú', 'categoriaopcionmenu', 6, 9, '2017-07-23 22:17:30', '2022-09-20 02:40:19', NULL),
(2, 'Opción de menú', 'opcionmenu', 7, 9, '2017-07-23 22:17:30', '2022-09-20 02:40:13', NULL),
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
(46, 'Buscar pedido', 'pedidos', 4, 12, '2020-10-03 20:31:52', '2022-09-03 18:37:32', NULL),
(47, 'Movimiento de almacén', 'movalmacen', 1, 14, '2020-10-03 21:00:30', '2020-10-30 23:00:55', NULL),
(48, 'Compras por pagar', 'compraspagar', 2, 11, '2020-10-22 18:42:59', '2020-10-22 18:42:59', NULL),
(49, 'Préstamo de envases', 'prestamoenvase', 4, 14, '2020-11-03 17:18:55', '2020-11-03 17:18:55', NULL),
(50, 'Devolución de envases', 'devolucion', 5, 14, '2020-11-03 17:19:23', '2022-09-03 20:23:45', '2022-09-03 20:23:45'),
(51, 'Pedidos de caja actual', 'pedidos_actual', 2, 12, '2022-09-03 18:36:47', '2022-09-03 18:37:38', NULL),
(52, 'Configuración general', 'configgeneral', 5, 9, '2022-09-20 02:40:06', '2022-09-20 03:33:56', NULL),
(53, 'Métodos de pago', 'metodopago', 4, 9, '2022-09-20 02:40:48', '2022-09-20 02:40:48', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menuoptioncategory`
--

CREATE TABLE `menuoptioncategory` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `icon` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'glyphicon glyphicon-expand',
  `menuoptioncategory_id` int(10) UNSIGNED DEFAULT NULL,
  `position` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Estructura de tabla para la tabla `metodo_pagos`
--

CREATE TABLE `metodo_pagos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `metodo_pagos`
--

INSERT INTO `metodo_pagos` (`id`, `nombre`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'EFECTIVO', '2022-09-20 03:21:58', '2022-09-20 03:23:46', NULL),
(2, 'YAPE', '2022-09-20 03:24:13', '2022-09-20 03:24:13', NULL),
(3, 'PLIN', '2022-09-20 03:24:22', '2022-09-20 03:24:22', NULL),
(4, 'TRANSFERENCIA BCP', '2022-09-20 03:24:34', '2022-09-20 03:24:34', NULL),
(5, 'TRANSFERENCIA INTERBANK', '2022-09-20 03:24:45', '2022-09-20 03:24:45', NULL),
(6, 'AAA', '2022-09-20 03:50:00', '2022-09-20 03:50:05', '2022-09-20 03:50:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(42, '2020_09_11_200556_create_table_almacen', 18),
(43, '2020_09_11_201245_create_table_lote', 19),
(44, '2020_09_11_202038_create_table_stock', 20),
(45, '2020_09_11_202543_create_table_detalle_mov_almacen', 21),
(46, '2020_09_11_203236_create_table_kardex', 22),
(47, '2020_12_21_205832_crear_tabla_detalle_prestamo', 23),
(48, '2022_09_19_215602_crear_tabla_metodo_pagos', 24),
(50, '2022_09_19_220449_crear_tabla_config_general', 25),
(51, '2020_08_07_181901_create_tabla_detalle_pagos', 26);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento`
--

CREATE TABLE `movimiento` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipomovimiento_id` int(10) UNSIGNED NOT NULL,
  `tipodocumento_id` int(10) UNSIGNED DEFAULT NULL,
  `venta_id` int(10) UNSIGNED DEFAULT NULL,
  `compra_id` int(10) DEFAULT NULL,
  `num_caja` int(11) DEFAULT NULL,
  `num_venta` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_compra` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) NOT NULL,
  `total_pagado` decimal(10,2) DEFAULT NULL,
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
  `concepto_id` int(10) UNSIGNED DEFAULT NULL,
  `persona_id` int(10) UNSIGNED DEFAULT NULL,
  `trabajador_id` int(10) UNSIGNED DEFAULT NULL,
  `sucursal_id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `movimiento`
--

INSERT INTO `movimiento` (`id`, `tipomovimiento_id`, `tipodocumento_id`, `venta_id`, `compra_id`, `num_caja`, `num_venta`, `num_compra`, `fecha`, `total`, `total_pagado`, `subtotal`, `igv`, `montoefectivo`, `montovisa`, `montomaster`, `vuelto`, `comentario`, `comentario_anulado`, `estado`, `pedido_sucursal`, `balon_a_cuenta`, `balon_prestado`, `vale_balon_subcafae`, `vale_balon_monto`, `vale_balon_fise`, `codigo_vale_monto`, `codigo_vale_fise`, `codigo_vale_subcafae`, `monto_vale_balon`, `monto_vale_fise`, `ingreso_caja_principal`, `ingreso_cierre_id`, `concepto_id`, `persona_id`, `trabajador_id`, `sucursal_id`, `usuario_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-08 15:20:14', '200.00', NULL, '200.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, 1, '2022-10-08 15:20:14', '2022-10-08 15:20:14', NULL),
(2, 4, 4, NULL, NULL, NULL, NULL, '0001-0000001', '2022-10-08 15:22:49', '11363.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, 2, 1, 1, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(3, 4, 1, NULL, NULL, NULL, NULL, '0001-0000001', '2022-10-08 15:23:50', '1086.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18, NULL, 2, 1, 1, '2022-10-08 15:23:50', '2022-10-08 15:23:50', NULL),
(4, 1, NULL, NULL, NULL, 2, NULL, NULL, '2022-10-10 04:23:57', '70.00', NULL, '70.00', NULL, '70.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 3, 1, 1, '2022-10-10 04:23:57', '2022-10-10 04:23:57', NULL),
(5, 1, NULL, NULL, NULL, 3, NULL, NULL, '2022-10-10 04:24:17', '70.00', NULL, '70.00', NULL, '70.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 29, 1, 1, '2022-10-10 04:24:17', '2022-10-10 04:24:17', NULL),
(6, 2, 3, NULL, NULL, NULL, '0001-0000001', NULL, '2022-10-10 04:26:09', '54.00', '60.00', '45.76', '8.24', '0.00', '0.00', '0.00', '6.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 127, 3, 1, 1, '2022-10-10 04:26:09', '2022-10-10 04:26:09', NULL),
(7, 2, 3, NULL, NULL, NULL, '0001-0000002', NULL, '2022-10-10 04:26:33', '54.00', '54.00', '45.76', '8.24', '0.00', '0.00', '0.00', '0.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 126, 29, 1, 1, '2022-10-10 04:26:33', '2022-10-10 04:26:33', NULL),
(8, 2, 3, NULL, NULL, NULL, '0001-0000003', NULL, '2022-10-10 04:28:15', '54.00', '24.00', '45.76', '8.24', '0.00', '0.00', '0.00', '0.00', '', NULL, 1, 0, 1, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 112, 3, 1, 1, '2022-10-10 04:28:15', '2022-10-10 04:28:15', NULL),
(9, 2, 3, NULL, NULL, NULL, '0001-0000004', NULL, '2022-10-10 04:29:05', '54.00', '54.00', '45.76', '8.24', '0.00', '0.00', '0.00', '0.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 112, 3, 1, 1, '2022-10-10 04:29:05', '2022-10-10 04:29:05', NULL),
(10, 5, NULL, 8, NULL, NULL, NULL, NULL, '2022-10-10 04:29:34', '10.00', NULL, '10.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE PEDIDO A CRÉDITO: TK0001-0000003', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 112, 29, 1, 1, '2022-10-10 04:29:34', '2022-10-10 04:29:34', NULL),
(11, 5, NULL, 8, NULL, NULL, NULL, NULL, '2022-10-10 04:29:41', '10.00', NULL, '10.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE PEDIDO A CRÉDITO: TK0001-0000003', 'ANULADO DEUDA TURNO', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 112, 3, 1, 1, '2022-10-10 04:29:41', '2022-10-10 04:48:56', NULL),
(12, 1, NULL, 8, NULL, 4, NULL, NULL, '2022-10-10 04:29:56', '10.00', NULL, '10.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE PEDIDO A CRÉDITO: TK0001-0000003', 'ANULADO PRUEBA', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 112, 3, 1, 1, '2022-10-10 04:29:56', '2022-10-10 04:46:30', NULL),
(13, 2, 3, NULL, NULL, NULL, '0001-0000005', NULL, '2022-10-10 04:32:33', '54.00', '54.00', '45.76', '8.24', '0.00', '0.00', '0.00', '0.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 117, 29, 1, 1, '2022-10-10 04:32:33', '2022-10-10 04:32:33', NULL),
(14, 2, 3, NULL, NULL, NULL, '0001-0000006', NULL, '2022-10-10 04:33:03', '54.00', '60.00', '45.76', '8.24', '0.00', '0.00', '0.00', '6.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 115, 29, 1, 1, '2022-10-10 04:33:03', '2022-10-10 04:33:03', NULL),
(15, 2, 3, NULL, NULL, NULL, '0001-0000007', NULL, '2022-10-10 04:35:37', '54.00', '55.00', '45.76', '8.24', '0.00', '0.00', '0.00', '1.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 113, 3, 1, 1, '2022-10-10 04:35:37', '2022-10-10 04:35:37', NULL),
(16, 2, 3, NULL, NULL, NULL, '0001-0000008', NULL, '2022-10-10 04:39:40', '54.00', '54.00', '45.76', '8.24', '0.00', '0.00', '0.00', '0.00', '', 'ANULADO', 0, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 118, 29, 1, 1, '2022-10-10 04:39:40', '2022-10-10 04:41:17', NULL),
(17, 7, NULL, 16, NULL, NULL, NULL, NULL, '2022-10-10 04:41:17', '0.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2022-10-10 04:41:17', '2022-10-10 04:41:17', NULL),
(18, 2, 3, NULL, NULL, NULL, '0001-0000009', NULL, '2022-10-10 04:44:00', '54.00', '60.00', '45.76', '8.24', '0.00', '0.00', '0.00', '6.00', '', NULL, 1, 1, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 118, 2, 1, 1, '2022-10-10 04:44:00', '2022-10-10 04:44:00', NULL),
(19, 1, NULL, 18, NULL, 5, NULL, NULL, '2022-10-10 04:44:00', '54.00', '60.00', '45.76', NULL, '0.00', '0.00', '0.00', NULL, 'Pago de: TK0001-0000009', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 118, 2, 1, 1, '2022-10-10 04:44:00', '2022-10-10 04:44:00', NULL),
(20, 2, 3, NULL, NULL, NULL, '0001-0000010', NULL, '2022-10-10 04:45:14', '54.00', '60.00', '45.76', '8.24', '0.00', '0.00', '0.00', '6.00', 'PEDRO RUIZ', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 29, 1, 1, '2022-10-10 04:45:14', '2022-10-10 04:45:14', NULL),
(21, 6, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-10 04:45:37', '15.00', NULL, '15.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, 3, 1, 1, '2022-10-10 04:45:37', '2022-10-10 04:45:37', NULL),
(22, 2, 3, NULL, NULL, NULL, '0001-0000011', NULL, '2022-10-10 04:47:56', '72.00', '80.00', '61.02', '10.98', '0.00', '0.00', '0.00', '8.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 76, 3, 1, 1, '2022-10-10 04:47:56', '2022-10-10 04:47:56', NULL),
(23, 2, 3, NULL, NULL, NULL, '0001-0000012', NULL, '2022-10-10 04:50:31', '54.00', '54.00', '45.76', '8.24', '0.00', '0.00', '0.00', '0.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 111, 29, 1, 1, '2022-10-10 04:50:31', '2022-10-10 04:50:31', NULL),
(24, 2, 3, NULL, NULL, NULL, '0001-0000013', NULL, '2022-10-10 04:51:42', '54.00', '60.00', '45.76', '8.24', '0.00', '0.00', '0.00', '6.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 113, 3, 1, 1, '2022-10-10 04:51:42', '2022-10-10 04:51:42', NULL),
(25, 1, NULL, NULL, NULL, 6, NULL, NULL, '2022-10-10 04:52:40', '313.00', NULL, '313.00', NULL, '0.00', '0.00', '0.00', NULL, 'PRIMERA DESCARGA', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, NULL, 3, 1, 1, '2022-10-10 04:52:40', '2022-10-10 04:52:40', NULL),
(26, 1, NULL, NULL, NULL, 7, NULL, NULL, '2022-10-10 04:52:51', '70.00', NULL, '70.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, 3, 1, 1, '2022-10-10 04:52:51', '2022-10-10 04:52:51', NULL),
(27, 2, 3, NULL, NULL, NULL, '0001-0000014', NULL, '2022-10-10 04:54:02', '108.00', '110.00', '91.53', '16.47', '0.00', '0.00', '0.00', '2.00', 'RESTAURANTE', NULL, 1, 1, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 115, 2, 1, 1, '2022-10-10 04:54:02', '2022-10-10 04:54:02', NULL),
(28, 1, NULL, 27, NULL, 8, NULL, NULL, '2022-10-10 04:54:02', '108.00', '110.00', '91.53', NULL, '0.00', '0.00', '0.00', NULL, 'Pago de: TK0001-0000014', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 115, 2, 1, 1, '2022-10-10 04:54:02', '2022-10-10 04:54:02', NULL),
(29, 2, 3, NULL, NULL, NULL, '0001-0000015', NULL, '2022-10-10 04:55:20', '54.00', '60.00', '45.76', '8.24', '0.00', '0.00', '0.00', '6.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 3, 1, 1, '2022-10-10 04:55:20', '2022-10-10 04:55:20', NULL),
(30, 2, 3, NULL, NULL, NULL, '0001-0000016', NULL, '2022-10-10 04:58:12', '54.00', '0.00', '45.76', '8.24', '0.00', '0.00', '0.00', '0.00', 'ESTO ES PRUEBA', NULL, 1, 0, 1, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 3, 1, 1, '2022-10-10 04:58:12', '2022-10-10 04:58:12', NULL),
(31, 2, 3, NULL, NULL, NULL, '0001-0000017', NULL, '2022-10-10 05:03:54', '54.00', '60.00', '45.76', '8.24', '0.00', '0.00', '0.00', '6.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 117, 29, 1, 1, '2022-10-10 05:03:54', '2022-10-10 05:03:54', NULL),
(32, 2, 3, NULL, NULL, NULL, '0001-0000018', NULL, '2022-10-10 05:07:28', '54.00', '60.00', '45.76', '8.24', '0.00', '0.00', '0.00', '6.00', '', NULL, 1, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 29, 1, 1, '2022-10-10 05:07:28', '2022-10-10 05:07:28', NULL),
(33, 2, 3, NULL, NULL, NULL, '0001-0000019', NULL, '2022-10-10 05:13:02', '54.00', '60.00', '45.76', '8.24', '0.00', '0.00', '0.00', '6.00', '', NULL, 1, 1, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 118, 2, 1, 1, '2022-10-10 05:13:02', '2022-10-10 05:13:02', NULL),
(34, 1, NULL, 33, NULL, 9, NULL, NULL, '2022-10-10 05:13:02', '54.00', '60.00', '45.76', NULL, '0.00', '0.00', '0.00', NULL, 'Pago de: TK0001-0000019', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 118, 2, 1, 1, '2022-10-10 05:13:02', '2022-10-10 05:13:02', NULL),
(35, 2, 3, NULL, NULL, NULL, '0001-0000020', NULL, '2022-10-10 23:15:00', '54.00', '24.00', '45.76', '8.24', '0.00', '0.00', '0.00', '0.00', 'DBE 30', NULL, 1, 1, 1, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 2, 1, 1, '2022-10-10 23:15:00', '2022-10-10 23:15:00', NULL),
(36, 1, NULL, 35, NULL, 10, NULL, NULL, '2022-10-10 23:15:00', '54.00', '24.00', '45.76', NULL, '0.00', '0.00', '0.00', NULL, 'Pago de: TK0001-0000020', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 2, 1, 1, '2022-10-10 23:15:00', '2022-10-10 23:15:00', NULL),
(37, 3, 4, NULL, NULL, NULL, NULL, '0001-0000001', '2022-10-11 00:12:37', '45.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, '', 'COMPRA ANULADA', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 114, 2, 1, 1, '2022-10-11 00:12:37', '2022-10-11 00:30:58', NULL),
(38, 1, NULL, NULL, 37, 11, NULL, NULL, '2022-10-11 00:12:37', '45.00', NULL, '45.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC-0001-0000001', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 114, NULL, 1, 1, '2022-10-11 00:12:37', '2022-10-11 00:12:37', NULL),
(39, 7, NULL, NULL, 37, NULL, NULL, NULL, '2022-10-11 00:30:58', '0.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2022-10-11 00:30:58', '2022-10-11 00:30:58', NULL),
(40, 3, 4, NULL, NULL, NULL, NULL, '0001-0000002', '2022-10-11 00:36:15', '355.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, '', 'FGHG', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 114, 2, 1, 1, '2022-10-11 00:36:15', '2022-10-11 00:37:36', NULL),
(41, 1, NULL, NULL, 40, 12, NULL, NULL, '2022-10-11 00:36:15', '355.00', NULL, '355.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC-0001-0000002', 'FGHG', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 114, NULL, 1, 1, '2022-10-11 00:36:15', '2022-10-11 00:37:36', NULL),
(42, 7, NULL, NULL, 40, NULL, NULL, NULL, '2022-10-11 00:37:36', '0.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2022-10-11 00:37:36', '2022-10-11 00:37:36', NULL),
(43, 3, 4, NULL, NULL, NULL, NULL, '0001-0000001', '2022-10-11 00:38:56', '685.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 114, 2, 1, 1, '2022-10-11 00:38:56', '2022-10-11 00:38:56', NULL),
(44, 1, NULL, NULL, 43, 13, NULL, NULL, '2022-10-11 00:38:56', '685.00', NULL, '685.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC-0001-0000001', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 114, NULL, 1, 1, '2022-10-11 00:38:56', '2022-10-11 00:38:56', '2022-10-11 00:38:56'),
(45, 3, 4, NULL, NULL, NULL, NULL, '0001-0000004', '2022-10-11 00:46:25', '155.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, '', 'ASAASD', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 114, 2, 1, 1, '2022-10-11 00:46:25', '2022-10-11 00:49:35', NULL),
(46, 1, NULL, NULL, 45, 13, NULL, NULL, '2022-10-11 00:46:25', '155.00', NULL, '155.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC-0001-0000004', 'ASAASD', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 114, NULL, 1, 1, '2022-10-11 00:46:25', '2022-10-11 00:49:35', NULL),
(47, 7, NULL, NULL, 45, NULL, NULL, NULL, '2022-10-11 00:49:35', '0.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2022-10-11 00:49:35', '2022-10-11 00:49:35', NULL),
(48, 3, 4, NULL, NULL, NULL, NULL, '0001-0000005', '2022-10-11 00:51:21', '155.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, '', 'IO', 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 114, 2, 1, 1, '2022-10-11 00:51:21', '2022-10-11 01:07:14', NULL),
(49, 1, NULL, NULL, 48, 14, NULL, NULL, '2022-10-11 00:51:21', '55.00', NULL, '55.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC-0001-0000005', 'IO', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 114, NULL, 1, 1, '2022-10-11 00:51:21', '2022-10-11 01:07:14', NULL),
(50, 1, NULL, NULL, 48, 15, NULL, NULL, '2022-10-11 01:03:45', '20.00', NULL, '20.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC0001-0000005', 'IO', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, 114, NULL, 1, 1, '2022-10-11 01:03:45', '2022-10-11 01:07:14', NULL),
(51, 1, NULL, NULL, 48, 16, NULL, NULL, '2022-10-11 01:07:03', '10.00', NULL, '10.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC0001-0000005', 'IO', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, 114, NULL, 1, 1, '2022-10-11 01:07:03', '2022-10-11 01:07:14', NULL),
(52, 1, NULL, NULL, 48, 17, NULL, NULL, '2022-10-11 01:07:06', '20.00', NULL, '20.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC0001-0000005', 'IO', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, 114, NULL, 1, 1, '2022-10-11 01:07:06', '2022-10-11 01:07:14', NULL),
(53, 7, NULL, NULL, 48, NULL, NULL, NULL, '2022-10-11 01:07:14', '0.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2022-10-11 01:07:14', '2022-10-11 01:07:14', NULL),
(54, 1, NULL, NULL, 43, 18, NULL, NULL, '2022-10-11 01:07:55', '100.00', NULL, '100.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE COMPRA: FC0001-0000001', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, 114, NULL, 1, 1, '2022-10-11 01:07:55', '2022-10-11 01:07:55', NULL),
(55, 1, NULL, NULL, NULL, 19, NULL, NULL, '2022-10-11 01:09:00', '350.00', NULL, '350.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, NULL, 29, 1, 1, '2022-10-11 01:09:00', '2022-10-11 01:09:00', NULL),
(56, 1, NULL, NULL, NULL, 1, NULL, NULL, '2022-10-12 00:42:22', '100.00', NULL, '100.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 2, 1, '2022-10-12 00:42:22', '2022-10-12 00:42:22', NULL),
(57, 1, NULL, NULL, NULL, 2, NULL, NULL, '2022-10-12 00:42:28', '70.00', NULL, '70.00', NULL, '70.00', '0.00', '0.00', NULL, '', 'ASD', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 76, 2, 1, '2022-10-12 00:42:28', '2022-10-12 00:52:37', NULL),
(58, 1, NULL, NULL, NULL, 3, NULL, NULL, '2022-10-12 00:52:44', '50.00', NULL, '50.00', NULL, '50.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 76, 2, 1, '2022-10-12 00:52:44', '2022-10-12 00:52:44', NULL),
(59, 1, NULL, NULL, NULL, 20, NULL, NULL, '2022-11-05 03:14:28', '70.00', NULL, '70.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 29, 1, 1, '2022-11-05 03:14:28', '2022-11-05 03:14:28', NULL),
(60, 2, 3, NULL, NULL, NULL, '0001-0000021', NULL, '2022-11-05 03:22:55', '4.00', '5.00', '3.39', '0.61', '0.00', '0.00', '0.00', '1.00', '', NULL, 1, 0, 0, 1, 0, 1, 0, 'asd', NULL, NULL, '50.00', NULL, NULL, NULL, 3, 133, 29, 1, 1, '2022-11-05 03:22:55', '2022-11-05 04:21:52', NULL),
(61, 2, 3, NULL, NULL, NULL, '0001-0000022', NULL, '2022-11-05 03:27:17', '54.00', '20.00', '45.76', '8.24', '0.00', '0.00', '0.00', '0.00', 'HJKHKG', NULL, 1, 0, 1, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 3, 1, 1, '2022-11-05 03:27:17', '2022-11-05 03:27:17', NULL),
(62, 5, NULL, 61, NULL, NULL, NULL, NULL, '2022-11-05 03:29:54', '15.00', NULL, '15.00', NULL, '0.00', '0.00', '0.00', NULL, 'PAGO DE PEDIDO A CRÉDITO: TK0001-0000022', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 1, 76, 1, 1, '2022-11-05 03:29:54', '2022-11-05 03:29:54', NULL),
(63, 6, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-05 03:31:16', '12.00', NULL, '12.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, 29, 1, 1, '2022-11-05 03:31:16', '2022-11-05 03:31:16', NULL),
(64, 1, NULL, NULL, NULL, 21, NULL, NULL, '2022-11-05 03:31:54', '62.00', NULL, '62.00', NULL, '0.00', '0.00', '0.00', NULL, '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, NULL, 29, 1, 1, '2022-11-05 03:31:54', '2022-11-05 03:31:54', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operacion`
--

CREATE TABLE `operacion` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `operacion_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `operacion_id` int(10) UNSIGNED NOT NULL,
  `menuoption_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso_operacion`
--

CREATE TABLE `permiso_operacion` (
  `id` int(10) UNSIGNED NOT NULL,
  `operacionmenu_id` int(10) UNSIGNED NOT NULL,
  `usertype_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission`
--

CREATE TABLE `permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `usertype_id` int(10) UNSIGNED NOT NULL,
  `menuoption_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(785, 1, 15, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(786, 1, 30, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(787, 1, 41, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(788, 1, 3, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(789, 1, 4, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(790, 1, 26, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(791, 1, 33, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(792, 1, 36, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(793, 1, 32, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(794, 1, 53, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(795, 1, 52, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(796, 1, 1, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(797, 1, 2, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(798, 1, 34, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(799, 1, 42, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(800, 1, 48, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(801, 1, 35, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(802, 1, 51, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(803, 1, 39, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(804, 1, 46, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(805, 1, 37, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(806, 1, 38, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(807, 1, 47, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(808, 1, 43, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(809, 1, 44, '2022-09-20 02:44:37', '2022-09-20 02:44:37'),
(810, 1, 49, '2022-09-20 02:44:37', '2022-09-20 02:44:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE `person` (
  `id` int(10) UNSIGNED NOT NULL,
  `apellido_pat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_mat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombres` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dni` int(8) DEFAULT NULL,
  `razon_social` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruc` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `celular` int(9) DEFAULT NULL,
  `tipo_persona` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `person`
--

INSERT INTO `person` (`id`, `apellido_pat`, `apellido_mat`, `nombres`, `dni`, `razon_social`, `ruc`, `direccion`, `celular`, `tipo_persona`, `sucursal_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, NULL, NULL, NULL, 'VARIOS', NULL, NULL, NULL, 'C', NULL, '2020-06-29 05:00:00', '2020-06-30 20:23:32', NULL),
(2, 'ORDOÑEZ', 'CHOLAN', 'DAVID RUBEN', 16497030, NULL, NULL, NULL, NULL, 'A', 1, '2020-02-24 00:32:40', '2022-09-12 01:01:52', NULL),
(3, 'NUÑEZ', 'BANCES', 'NOE DANILO', 74859612, NULL, NULL, 'Calle Paracas 215 - La Victoria', 975353672, 'T', 1, '2020-02-25 21:17:32', '2022-09-19 04:26:22', NULL),
(5, NULL, NULL, NULL, NULL, 'LUIS ACUÑA GUEVARA', '10766656980', 'prueba cell', 963307082, 'C', NULL, '2020-06-26 01:32:13', '2022-09-11 23:35:12', '2022-09-11 23:35:12'),
(13, 'ACUñA', 'ORTIZ', 'LUIS', 12345679, NULL, NULL, 'prueba calle', 123456789, 'C', NULL, '2020-07-04 01:47:28', '2022-09-11 23:35:51', '2022-09-11 23:35:51'),
(26, NULL, NULL, NULL, NULL, 'PRUEBITA', '12345678944', 'Av. Canta Callao, Callao 07031', 124563780, 'C', NULL, '2020-07-04 02:52:06', '2022-09-11 23:35:14', '2022-09-11 23:35:14'),
(28, 'DE LOS SANTOS', 'DEL MONTE', 'JOSE DARKIEL', 78654139, NULL, NULL, 'av prueba 1245', 784596321, 'T', 2, '2020-07-10 02:58:36', '2022-09-11 23:31:42', '2022-09-11 23:31:42'),
(29, 'ORDOÑEZ', 'CHOLAN', 'DAVID RUBEN', 16497030, NULL, NULL, 'PASAJE UNIÓN 364', 945696541, 'T', 1, '2020-07-15 23:10:23', '2022-09-21 03:18:28', NULL),
(30, 'ORTIZ', 'GUERRERO', 'LUIS', 78459634, NULL, NULL, 'prueba direccion', 784596321, 'C', NULL, '2020-07-15 23:11:16', '2022-09-11 23:35:06', '2022-09-11 23:35:06'),
(66, 'DIAZ', 'BARBOZA', 'JOSE DARKIEL', 79468215, NULL, NULL, 'Av. Canta Callao, Callao 07031', 784596120, 'C', NULL, '2020-07-23 05:42:14', '2022-09-11 23:36:00', '2022-09-11 23:36:00'),
(74, 'GUEVARA', 'HERNANDEZ', 'ENRIQUETA BEATRIZ', 16763290, NULL, NULL, 'Calle 7de Junio #106\r\n106', 985833666, 'C', NULL, '2020-08-04 17:30:36', '2022-09-11 23:35:58', '2022-09-11 23:35:58'),
(75, 'GOMEZ', 'FLORES', 'JAVIER JIMMY', 76952048, NULL, NULL, 'av prueba', 789546213, 'C', NULL, '2020-08-26 18:54:36', '2022-09-11 23:35:55', '2022-09-11 23:35:55'),
(76, 'NEIRA', 'RODRIGUEZ', 'JHONATAN DIEGO', 76079920, NULL, NULL, 'GIRON PROGRESO 253 - EL PORVENIR', 912672490, 'T', 2, '2020-09-08 03:02:29', '2022-10-03 20:16:06', NULL),
(77, 'AYALA', 'VIDARTE', 'MARIANA ANDREA', 75985418, NULL, NULL, 'prueba direccion amor', 932929895, 'C', NULL, '2020-09-08 03:03:21', '2022-09-11 23:36:05', '2022-09-11 23:36:05'),
(78, NULL, NULL, NULL, NULL, 'OPTIMIZA CONSTRUCCION Y SERVICIOS S.A.C.', '20506065896', 'prueba direccion 2', 123456789, 'P', NULL, '2020-09-08 03:21:14', '2022-09-11 23:36:37', '2022-09-11 23:36:37'),
(79, NULL, NULL, NULL, NULL, 'BANCO BBVA PERU', '20100130204', 'direccion prueba', 784596321, 'P', NULL, '2020-09-08 20:02:35', '2020-09-08 20:02:41', '2020-09-08 20:02:41'),
(80, NULL, NULL, NULL, NULL, 'ACUÑA GUEVARA LUIS EDGARDO', '10766656981', 'proveedor prueba', 963307082, 'P', NULL, '2020-09-13 00:17:23', '2020-09-13 00:17:43', '2020-09-13 00:17:43'),
(81, 'HUAMAN', 'TIRADO', 'MARCOS JOEL', 16763250, NULL, NULL, 'prueba', 784596321, 'C', NULL, '2020-09-14 23:17:01', '2022-09-11 23:35:45', '2022-09-11 23:35:45'),
(82, NULL, NULL, NULL, NULL, 'ACUÑA GUEVARA LUIS EDGARDO', '10766656981', NULL, NULL, 'P', NULL, '2020-09-15 00:56:59', '2022-09-11 23:36:39', '2022-09-11 23:36:39'),
(83, NULL, NULL, NULL, NULL, 'BANCO BBVA PERU', '20100130204', 'direccion prueba bbva', 987456321, 'P', NULL, '2020-09-15 01:00:33', '2022-09-11 23:36:42', '2022-09-11 23:36:42'),
(84, NULL, NULL, NULL, NULL, 'BI GRAND CONFECCIONES S.A.C.', '20553856451', 'prueba', 784456789, 'P', NULL, '2020-09-25 20:56:07', '2020-09-25 22:59:00', '2020-09-25 22:59:00'),
(85, NULL, NULL, NULL, NULL, 'SINERGIAS ECONOMICAS SOCIEDAD ANONIMA CERRADA - ECOSINERGIAS S.A.C.', '20552271794', 'hola', 789456123, 'C', NULL, '2020-09-25 21:28:04', '2020-09-25 21:29:41', '2020-09-25 21:29:41'),
(86, NULL, NULL, NULL, NULL, 'INSUMOS PISQUEROS DEL SUR E.I.R.L.', '20558629585', 'asd', 486124789, 'C', NULL, '2020-09-25 21:29:16', '2020-09-25 21:29:49', '2020-09-25 21:29:49'),
(87, NULL, NULL, NULL, NULL, 'ADSASDAD', '12345678945', 'asdadadsadasds', 789456123, 'C', NULL, '2020-09-25 21:30:43', '2022-09-11 23:35:17', '2022-09-11 23:35:17'),
(88, NULL, NULL, NULL, NULL, 'H & J E HIJOS E.I.R.L', '20518639928', 'asd', 789456132, 'C', NULL, '2020-09-25 21:32:17', '2022-09-11 23:35:19', '2022-09-11 23:35:19'),
(89, NULL, NULL, NULL, NULL, 'COMERCIAL FERRETERA PRISMA S.A.C.', '20525994741', 'asddd', 123456789, 'C', NULL, '2020-09-25 22:05:29', '2022-09-11 23:35:22', '2022-09-11 23:35:22'),
(90, NULL, NULL, NULL, NULL, 'REPRESENTACIONES DIERA S.A.C.', '20553476462', 'asdqwe', 112233445, 'C', NULL, '2020-09-25 22:08:07', '2022-09-11 23:35:25', '2022-09-11 23:35:25'),
(91, NULL, NULL, NULL, NULL, 'BI GRAND CONFECCIONES S.A.C.', '20553856452', 'prueba de cliente', 789456123, 'C', NULL, '2020-09-25 22:10:29', '2022-09-11 23:35:27', '2022-09-11 23:35:27'),
(92, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248984', 'hola prueba', 123456789, 'P', NULL, '2020-09-25 22:47:26', '2020-09-25 22:58:57', '2020-09-25 22:58:57'),
(93, NULL, NULL, NULL, NULL, 'CORPORACION CARMINA SAC', '20601155185', 'hola prueba', 987456123, 'P', NULL, '2020-09-25 22:56:43', '2020-09-25 22:59:07', '2020-09-25 22:59:07'),
(94, NULL, NULL, NULL, NULL, 'ARTROSCOPICTRAUMA S.A.C.', '20538856674', 'prueba', 789456412, 'P', NULL, '2020-09-25 22:58:14', '2020-09-25 22:59:04', '2020-09-25 22:59:04'),
(95, NULL, NULL, NULL, NULL, 'ARTROSCOPICTRAUMA S.A.C.', '20538856674', 'prueba direccion', 748596120, 'P', NULL, '2020-09-25 22:59:27', '2022-09-11 23:36:44', '2022-09-11 23:36:44'),
(96, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248984', 'pruebaaaaaa', 784512369, 'P', NULL, '2020-09-25 23:00:01', '2020-09-25 23:00:43', '2020-09-25 23:00:43'),
(97, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248984', 'asd prueba', 123456789, 'P', NULL, '2020-09-25 23:01:24', '2020-09-25 23:05:18', '2020-09-25 23:05:18'),
(98, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL 2', '20543248985', 'prueba direccion', 123456789, 'P', NULL, '2020-09-25 23:02:37', '2020-09-25 23:05:16', '2020-09-25 23:05:16'),
(99, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL 3', '20543248986', 'prueba dicrecasda 1', 789456123, 'P', NULL, '2020-09-25 23:03:07', '2020-09-25 23:05:13', '2020-09-25 23:05:13'),
(100, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248912', 'prueba', 789456123, 'C', NULL, '2020-09-25 23:03:49', '2022-09-11 23:35:29', '2022-09-11 23:35:29'),
(101, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL 3', '20543248988', 'hola direccion prueba', 789456123, 'C', NULL, '2020-09-25 23:04:23', '2022-09-11 23:35:32', '2022-09-11 23:35:32'),
(102, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248984', 'prueba direccion', 123456789, 'P', NULL, '2020-09-25 23:07:06', '2020-09-25 23:08:16', '2020-09-25 23:08:16'),
(103, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL', '20543248984', 'prueba', 784512487, 'P', NULL, '2020-09-25 23:08:34', '2022-09-11 23:36:47', '2022-09-11 23:36:47'),
(104, NULL, NULL, NULL, NULL, 'MODAS LOREN EIRL 2', '20543248985', 'prueba 2 direccion', 789456123, 'P', NULL, '2020-09-25 23:09:19', '2022-09-11 23:36:50', '2022-09-11 23:36:50'),
(105, 'PRUEBA', 'PRUEBA', 'LUIS', 12121212, NULL, NULL, 'Holi', 789456123, 'C', NULL, '2020-11-06 03:56:56', '2022-09-11 23:35:03', '2022-09-11 23:35:03'),
(106, 'ACUñA', 'FINAL', 'LUIS', 22052022, NULL, NULL, 'ñaña', 454545666, 'C', NULL, '2022-09-08 00:01:56', '2022-09-11 23:36:08', '2022-09-11 23:36:08'),
(107, NULL, NULL, NULL, NULL, 'ñññOOO', '10766656982', 'ññpp', 963545556, 'C', NULL, '2022-09-08 02:57:02', '2022-09-11 23:35:35', '2022-09-11 23:35:35'),
(108, NULL, NULL, NULL, NULL, 'ñOOOOOO', '10766656983', 'vv', 123456789, 'C', NULL, '2022-09-08 03:01:18', '2022-09-11 23:35:38', '2022-09-11 23:35:38'),
(109, NULL, NULL, NULL, NULL, 'ñOAOSDOMA', '10555105555', 'sdfa', 121896254, 'C', NULL, '2022-09-08 03:05:53', '2022-09-11 23:35:40', '2022-09-11 23:35:40'),
(110, 'ZAPATA', 'CERVERA', 'MANUELA GIOVANNA', 1662087, NULL, NULL, 'Pasaje Unión 364', 99826615, 'A', 1, '2022-09-11 23:53:39', '2022-09-11 23:53:39', NULL),
(111, NULL, NULL, NULL, NULL, 'NATIVIDAD VIDAURRE CHAPOñAN', '10166426214', 'Chiclayo', 987463217, 'C', NULL, '2022-09-12 02:39:02', '2022-09-12 02:39:02', NULL),
(112, NULL, NULL, NULL, NULL, 'ROBERTO DAVID ZORRILLA PEREDO', '10411516593', 'Juan Cuglievan 1116 - Chiclayo', 957327741, 'C', NULL, '2022-09-12 02:45:39', '2022-09-12 02:45:39', NULL),
(113, NULL, NULL, NULL, NULL, 'CESAR ARTURO MARCIO BOCANEGRA', '10460733362', 'Colon 204', 987654321, 'C', NULL, '2022-09-12 02:55:34', '2022-09-12 02:55:34', NULL),
(114, NULL, NULL, NULL, NULL, 'SIPAN GAS E.I.R.L.', '20313301495', 'Mz.C LOTE 27 ZONA INDUSTRIAL (PARQUE INDUSTRIAL LOTE 27-28) - CHICLAYO', 74208141, 'P', NULL, '2022-09-12 03:29:25', '2022-09-12 03:29:25', NULL),
(115, NULL, NULL, NULL, NULL, 'SERVICIOS EMPRESARIALES DEL NORTE LOS MADEROS EIRL', '20487944328', 'Balta 417 - Chiclayo', 987654321, 'C', NULL, '2022-09-12 03:46:19', '2022-09-12 03:46:19', NULL),
(116, NULL, NULL, NULL, NULL, 'ALIYS RESTAURANTE', '20605718257', 'Grau 467', 987654321, 'C', NULL, '2022-09-12 23:41:47', '2022-09-12 23:41:47', NULL),
(117, NULL, NULL, NULL, NULL, 'MEDINA CHULLI DE LA PIEDRA ZOILA ROSA (IZAGA CAFE SNACK)', '10164158859', 'Izaga 413', 987654321, 'C', NULL, '2022-09-12 23:53:35', '2022-09-12 23:53:35', NULL),
(118, 'ACUñA', '-', 'LUIS', 76665698, NULL, NULL, 'Av De las artes sur 665 san borja', 963307082, 'C', NULL, '2022-09-13 22:49:18', '2022-09-13 22:49:18', NULL),
(119, NULL, NULL, NULL, NULL, '', NULL, 'ZAÑA', NULL, 'C', NULL, '2022-09-17 16:48:48', '2022-09-21 04:33:18', '2022-09-21 04:33:18'),
(120, NULL, NULL, NULL, NULL, '', NULL, 'izaga 320', NULL, 'C', NULL, '2022-09-17 16:49:51', '2022-09-21 04:33:21', '2022-09-21 04:33:21'),
(121, 'ACUÑA', '', '', NULL, '', NULL, 'ZAÑA', NULL, 'C', NULL, '2022-09-17 16:58:38', '2022-09-21 04:33:24', '2022-09-21 04:33:24'),
(122, 'ACUñA 2', '', '', NULL, '', NULL, '123456', NULL, 'C', NULL, '2022-09-17 16:59:04', '2022-09-21 04:33:30', '2022-09-21 04:33:30'),
(123, 'LUIS', '', '', NULL, '', NULL, '1245', NULL, 'C', NULL, '2022-09-17 16:59:59', '2022-09-21 04:33:35', '2022-09-21 04:33:35'),
(124, 'FAM ACUÑA', '', '', NULL, '', NULL, '123456', NULL, 'C', NULL, '2022-09-17 17:00:11', '2022-09-21 04:33:27', '2022-09-21 04:33:27'),
(125, 'RAMIREZ', '', '', NULL, '', NULL, 'ZAÑA 123', NULL, 'C', NULL, '2022-09-17 17:03:43', '2022-09-21 04:33:32', '2022-09-21 04:33:32'),
(126, 'GUEVARA', '', 'JUAN', NULL, '', NULL, 'Elias aguire1234', NULL, 'C', NULL, '2022-09-27 20:41:41', '2022-09-27 20:41:41', NULL),
(127, 'GUEVARA', '', 'PEDRO', NULL, '', NULL, 'ALFONSO UGARTE 456', NULL, 'C', NULL, '2022-09-27 20:49:23', '2022-09-27 20:49:23', NULL),
(128, 'GUEVARA', '', 'PEDRO', NULL, '', NULL, 'ALFONSO UGARTE 456', NULL, 'C', NULL, '2022-09-27 20:50:20', '2022-09-27 20:50:20', NULL),
(129, 'GUEVARA', '', 'ENRIQUETA', 16763290, '', NULL, 'zaña', NULL, 'C', NULL, '2022-10-11 21:17:31', '2022-10-11 21:17:31', NULL),
(130, 'LUIAS PRUEBA', '', '', NULL, '', NULL, '1234', NULL, 'C', NULL, '2022-10-12 00:36:23', '2022-10-12 00:36:23', NULL),
(131, 'LUIS', '', '', NULL, '', NULL, 'prueba', NULL, 'C', NULL, '2022-10-12 00:40:02', '2022-10-12 00:40:02', NULL),
(132, 'PéRUBA', '', 'LUIS 2', NULL, '', NULL, 'operasd', NULL, 'C', NULL, '2022-10-12 00:40:19', '2022-10-12 00:40:19', NULL),
(133, 'FAM ACUÑA', '', '', NULL, '', NULL, '7 de juni o 106', NULL, 'C', NULL, '2022-11-05 03:16:53', '2022-11-05 03:16:53', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(10) UNSIGNED NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `descripcion`, `precio_venta`, `precio_compra`, `precio_venta_envase`, `precio_compra_envase`, `frecuente`, `editable`, `recargable`, `stock_seguridad`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'AGUA PETRA', '10.00', '5.00', '25.00', '25.00', 1, 0, 1, 10, '2018-05-24 05:00:00', '2022-09-12 00:06:20', NULL),
(3, 'AGUA PETRA', '10.00', '5.00', '25.00', '25.00', 1, 0, 1, 10, '2018-05-25 00:07:37', '2022-09-12 00:05:36', '2022-09-12 00:05:36'),
(4, 'BALÓN PREMIUM', '54.00', '45.00', '110.00', '110.00', 1, 1, 1, 10, '2018-09-20 03:27:18', '2022-10-08 15:22:49', NULL),
(5, 'BALÓN NORMAL', '54.00', '45.00', '110.00', '110.00', 1, 1, 1, 10, '2018-10-25 02:58:46', '2022-10-06 22:27:47', NULL),
(8, 'AGUA BRUVA KIT COMPLETO', '20.00', '13.00', NULL, NULL, 1, 0, 0, 10, '2018-10-27 17:00:11', '2020-11-06 04:28:47', '2020-11-06 04:28:47'),
(9, 'MANGUERA REFORZADA XMT', '8.00', '5.00', NULL, NULL, 1, 1, 0, 10, '2018-11-16 04:36:21', '2022-10-03 20:20:47', NULL),
(12, 'REGULADOR FISHER', '35.00', '23.00', NULL, NULL, 1, 0, 0, 10, '2018-11-16 05:16:21', '2022-09-12 00:10:01', NULL),
(13, 'ALCOHOL 96% LT', '12.00', '7.00', NULL, NULL, 0, 0, 0, 10, '2018-12-07 22:44:58', '2022-11-05 03:44:46', NULL),
(14, 'AGUA NEUTRA KIT COMPLETO', '24.00', '15.00', NULL, NULL, 1, 0, 0, 10, '2020-07-08 20:49:19', '2020-11-06 04:28:50', '2020-11-06 04:28:50'),
(15, 'BALÓN NUEVO - RECIPIENTE', '80.00', '70.00', NULL, NULL, 1, 0, 0, 10, '2020-09-13 16:49:49', '2020-11-05 02:18:02', '2020-11-05 02:18:02'),
(16, 'BALÓN NORMAL + RECIPIENTE', '80.00', '70.00', NULL, NULL, 0, 1, NULL, NULL, '2020-11-05 02:17:37', '2020-11-05 14:17:04', '2020-11-05 14:17:04'),
(17, 'BALÓN PREMIUM + RECIPIENTE', '80.00', '70.00', NULL, NULL, 0, 1, NULL, NULL, '2020-11-05 02:17:54', '2020-11-05 14:17:08', '2020-11-05 14:17:08'),
(18, 'NUEVO', '12.00', '12.00', '10.00', '10.00', 0, 0, 1, NULL, '2020-11-06 04:29:18', '2020-11-06 04:29:26', '2020-11-06 04:29:26'),
(19, 'REGULADOR DE ALTA', '18.00', '13.00', NULL, NULL, 1, 1, 0, NULL, '2022-09-12 00:28:21', '2022-09-23 15:39:29', NULL),
(20, 'PRUEBA', '10.00', '10.00', NULL, NULL, 1, 0, 0, NULL, '2022-10-11 21:20:42', '2022-10-11 21:21:19', '2022-10-11 21:21:19'),
(21, 'PRUEBA2', '10.00', '10.00', NULL, NULL, 1, 0, 0, NULL, '2022-10-11 21:21:04', '2022-10-11 21:21:17', '2022-10-11 21:21:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `id` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `envases_total` int(11) DEFAULT NULL,
  `envases_llenos` int(11) DEFAULT NULL,
  `envases_vacios` int(11) DEFAULT NULL,
  `envases_prestados` int(11) DEFAULT NULL,
  `sucursal_id` int(10) UNSIGNED NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`id`, `cantidad`, `envases_total`, `envases_llenos`, `envases_vacios`, `envases_prestados`, `sucursal_id`, `producto_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 20, 35, 20, 15, NULL, 1, 4, '2022-10-08 15:22:49', '2022-10-11 01:07:14', NULL),
(2, 33, 56, 33, 23, 1, 1, 5, '2022-10-08 15:22:49', '2022-11-05 04:21:52', NULL),
(3, 26, 32, 26, 6, NULL, 1, 1, '2022-10-08 15:22:49', '2022-10-08 15:23:51', NULL),
(4, 15, NULL, NULL, NULL, NULL, 1, 9, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL),
(5, 25, NULL, NULL, NULL, NULL, 1, 19, '2022-10-08 15:22:49', '2022-10-10 04:47:56', NULL),
(6, 30, NULL, NULL, NULL, NULL, 1, 12, '2022-10-08 15:22:49', '2022-10-08 15:22:49', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cant_balon_normal` int(11) DEFAULT NULL,
  `cant_balon_premium` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id`, `nombre`, `direccion`, `telefono`, `cant_balon_normal`, `cant_balon_premium`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SERVIGAS EL PÓRVENIR', 'PSJE. LA UNIÓN 364', '074-225555', 80, 10, '2018-05-15 05:00:00', '2022-09-13 22:33:02', NULL),
(2, 'CENTRO GAS CAMPODONICO GYD', 'CALLE ARICA 1885', '074-207140', 28, 10, '2018-05-17 02:10:52', '2022-09-12 00:19:24', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE `tipodocumento` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abreviatura` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipomovimiento_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `tipomovimiento` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipomovimiento`
--

INSERT INTO `tipomovimiento` (`id`, `descripcion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CAJA', '2018-11-13 04:51:50', '2018-11-13 04:51:50', NULL),
(2, 'VENTA', '2018-11-13 04:51:50', '2018-11-13 04:51:50', NULL),
(3, 'COMPRA', '2019-11-20 05:00:00', '2019-11-20 05:00:00', NULL),
(4, 'ALMACEN', '2019-11-20 05:00:00', '2019-11-20 05:00:00', NULL),
(5, 'CREDITO', '2020-08-10 05:00:00', '2020-08-10 05:00:00', NULL),
(6, 'GASTOS REPARTIDOR', '2020-10-15 05:00:00', '2020-10-15 05:00:00', NULL),
(7, 'DEVOLUCIÓN', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno_repartidor`
--

CREATE TABLE `turno_repartidor` (
  `id` int(10) UNSIGNED NOT NULL,
  `inicio` timestamp NULL DEFAULT NULL,
  `fin` timestamp NULL DEFAULT NULL,
  `estado` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apertura_id` int(10) UNSIGNED NOT NULL,
  `trabajador_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `turno_repartidor`
--

INSERT INTO `turno_repartidor` (`id`, `inicio`, `fin`, `estado`, `apertura_id`, `trabajador_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2022-10-10 04:23:57', NULL, 'I', 1, 3, '2022-10-10 04:23:57', '2022-10-10 04:23:57', NULL),
(2, '2022-10-10 04:24:17', '2022-10-11 01:09:00', 'C', 1, 29, '2022-10-10 04:24:17', '2022-10-11 01:09:00', NULL),
(3, '2022-10-12 00:42:28', NULL, 'I', 56, 76, '2022-10-12 00:42:28', '2022-10-12 00:52:37', '2022-10-12 00:52:37'),
(4, '2022-10-12 00:52:44', NULL, 'I', 56, 76, '2022-10-12 00:52:44', '2022-10-12 00:52:44', NULL),
(5, '2022-11-05 03:14:28', '2022-11-05 03:31:54', 'C', 1, 29, '2022-11-05 03:14:28', '2022-11-05 03:31:54', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `login` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'H',
  `usertype_id` int(10) UNSIGNED NOT NULL,
  `person_id` int(10) UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `email`, `state`, `usertype_id`, `person_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', '$2y$10$cdvwNuPe3GDMXEXwXfDSHOU3QCzRig.yW7w.mnUEOGmRBgiOk4gh2', 'acuna.guevara.luis.edgardo@gmail.com', 'H', 1, 2, '1iAOVy1j0DG7jTtdoVcXqsMBUtf2vuE0lqxC8GTDXVYT4unrsAdG3hyw6JZF', '2017-07-23 22:17:32', '2022-09-11 23:20:36', NULL),
(2, 'orejitas', '$2y$10$bb.RBv4Blp4W8vzcfp0.muweC7h/Ynd8pi04Da6/qgOjO7Lp6oZcW', NULL, 'H', 1, 3, NULL, '2020-07-04 02:59:10', '2022-09-11 23:38:33', '2022-09-11 23:38:33'),
(3, 'giovanna', '$2y$10$xpjgwVbJfABYjrXgCtaIUeiZ8EvsvCFRfYuZ387zSu9pUXlE0XF9a', NULL, 'H', 1, 110, NULL, '2022-09-12 00:02:04', '2022-09-12 00:02:04', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usertype`
--

CREATE TABLE `usertype` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `binnacle_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `concepto`
--
ALTER TABLE `concepto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `config_general`
--
ALTER TABLE `config_general`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_mov_almacen`
--
ALTER TABLE `detalle_mov_almacen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_mov_almacen_movimiento_id_foreign` (`movimiento_id`),
  ADD KEY `detalle_mov_almacen_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `detalle_pagos`
--
ALTER TABLE `detalle_pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_pagos_pedido_id_foreign` (`pedido_id`),
  ADD KEY `detalle_pagos_metodo_pago_id_foreign` (`metodo_pago_id`),
  ADD KEY `detalle_pagos_pago_credito_id_foreign` (`pago_credito_id`);

--
-- Indices de la tabla `detalle_prestamo`
--
ALTER TABLE `detalle_prestamo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_prestamo_detalle_mov_almacen_id_foreign` (`detalle_mov_almacen_id`);

--
-- Indices de la tabla `detalle_turno_pedido`
--
ALTER TABLE `detalle_turno_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_turno_pedido_pedido_id_foreign` (`pedido_id`),
  ADD KEY `detalle_turno_pedido_turno_id_foreign` (`turno_id`);

--
-- Indices de la tabla `kardex`
--
ALTER TABLE `kardex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kardex_detalle_mov_almacen_id_foreign` (`detalle_mov_almacen_id`),
  ADD KEY `kardex_sucursal_id_foreign` (`sucursal_id`);

--
-- Indices de la tabla `menuoption`
--
ALTER TABLE `menuoption`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menuoption_menuoptioncategory_id_foreign` (`menuoptioncategory_id`);

--
-- Indices de la tabla `menuoptioncategory`
--
ALTER TABLE `menuoptioncategory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menuoptioncategory_menuoptioncategory_id_foreign` (`menuoptioncategory_id`);

--
-- Indices de la tabla `metodo_pagos`
--
ALTER TABLE `metodo_pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimiento`
--
ALTER TABLE `movimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `concepto_id` (`concepto_id`),
  ADD KEY `tipomovimiento_id` (`tipomovimiento_id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `sucursal_id` (`sucursal_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `cliente_id` (`persona_id`),
  ADD KEY `trabajador_id` (`trabajador_id`);

--
-- Indices de la tabla `operacion`
--
ALTER TABLE `operacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `operacion_menu`
--
ALTER TABLE `operacion_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operacion_menu_operacion_id_foreign` (`operacion_id`),
  ADD KEY `operacion_menu_menuoption_id_foreign` (`menuoption_id`);

--
-- Indices de la tabla `permiso_operacion`
--
ALTER TABLE `permiso_operacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permiso_operacion_operacionmenu_id_foreign` (`operacionmenu_id`),
  ADD KEY `permiso_operacion_usertype_id_foreign` (`usertype_id`);

--
-- Indices de la tabla `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_usertype_id_foreign` (`usertype_id`),
  ADD KEY `permission_menuoption_id_foreign` (`menuoption_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_producto_id_foreign` (`producto_id`),
  ADD KEY `stock_sucursal_id_foreign` (`sucursal_id`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipodocumento_tipomovimiento_id_foreign` (`tipomovimiento_id`);

--
-- Indices de la tabla `tipomovimiento`
--
ALTER TABLE `tipomovimiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `turno_repartidor`
--
ALTER TABLE `turno_repartidor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turno_repartidor_apertura_id_foreign` (`apertura_id`),
  ADD KEY `turno_repartidor_trabajador_id_foreign` (`trabajador_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_login_unique` (`login`),
  ADD KEY `user_usertype_id_foreign` (`usertype_id`),
  ADD KEY `user_person_id_foreign` (`person_id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `concepto`
--
ALTER TABLE `concepto`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `config_general`
--
ALTER TABLE `config_general`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `detalle_mov_almacen`
--
ALTER TABLE `detalle_mov_almacen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT de la tabla `detalle_pagos`
--
ALTER TABLE `detalle_pagos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT de la tabla `detalle_prestamo`
--
ALTER TABLE `detalle_prestamo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `detalle_turno_pedido`
--
ALTER TABLE `detalle_turno_pedido`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT de la tabla `kardex`
--
ALTER TABLE `kardex`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT de la tabla `menuoption`
--
ALTER TABLE `menuoption`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT de la tabla `menuoptioncategory`
--
ALTER TABLE `menuoptioncategory`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `metodo_pagos`
--
ALTER TABLE `metodo_pagos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT de la tabla `movimiento`
--
ALTER TABLE `movimiento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT de la tabla `operacion`
--
ALTER TABLE `operacion`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `operacion_menu`
--
ALTER TABLE `operacion_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `permiso_operacion`
--
ALTER TABLE `permiso_operacion`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=811;
--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `tipomovimiento`
--
ALTER TABLE `tipomovimiento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `turno_repartidor`
--
ALTER TABLE `turno_repartidor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `usertype`
--
ALTER TABLE `usertype`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
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
  ADD CONSTRAINT `detalle_pagos_metodo_pago_id_foreign` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodo_pagos` (`id`),
  ADD CONSTRAINT `detalle_pagos_pago_credito_id_foreign` FOREIGN KEY (`pago_credito_id`) REFERENCES `movimiento` (`id`),
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
  ADD CONSTRAINT `venta_id` FOREIGN KEY (`venta_id`) REFERENCES `movimiento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
