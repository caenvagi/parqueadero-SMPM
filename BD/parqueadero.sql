-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-05-2024 a las 00:59:10
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `parqueadero`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arqueo`
--

CREATE TABLE `arqueo` (
  `arqueo_id` int(11) NOT NULL,
  `fecha_apertura` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_cierre` datetime DEFAULT NULL,
  `cajero` int(11) NOT NULL,
  `monto_inicial` int(11) NOT NULL,
  `total_ingresos` int(11) NOT NULL,
  `total_egresos` int(11) NOT NULL,
  `total_cierre` int(11) NOT NULL,
  `monto_final` int(11) DEFAULT NULL,
  `cuadre` int(11) GENERATED ALWAYS AS (`monto_final` - `total_cierre`) VIRTUAL,
  `estado` varchar(10) NOT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `arqueo`
--

INSERT INTO `arqueo` (`arqueo_id`, `fecha_apertura`, `fecha_cierre`, `cajero`, `monto_inicial`, `total_ingresos`, `total_egresos`, `total_cierre`, `monto_final`, `estado`, `usuario`) VALUES
(1, '2024-04-30 16:39:01', '2024-05-01 08:30:06', 2, 0, 21000, 0, 21000, 21000, 'cerrada', 2),
(2, '2024-05-01 13:30:49', '2024-05-02 09:03:30', 2, 21000, 25000, 0, 46000, 45000, 'cerrada', 2),
(3, '2024-05-02 14:04:13', '2024-05-03 09:00:23', 3, 45000, 24000, 0, 69000, 69000, 'cerrada', 2),
(4, '2024-05-03 14:01:02', '2024-05-04 14:29:46', 3, 69000, 0, 0, 69000, 69000, 'cerrada', 2),
(5, '2024-05-04 19:31:11', '2024-05-04 14:39:48', 2, 69000, 0, 0, 69000, 69000, 'cerrada', 2),
(6, '2024-05-04 19:40:00', NULL, 2, 69000, 0, 0, 0, 0, 'abierta', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id_movimiento` int(11) NOT NULL,
  `fecha_movimiento` datetime NOT NULL DEFAULT current_timestamp(),
  `movimiento` int(11) NOT NULL,
  `desc_movimiento` varchar(200) NOT NULL,
  `valor_ingreso` int(11) NOT NULL,
  `valor_egreso` int(11) NOT NULL,
  `user_login` int(20) NOT NULL,
  `liquidado` varchar(10) NOT NULL,
  `caja_tipo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id_movimiento`, `fecha_movimiento`, `movimiento`, `desc_movimiento`, `valor_ingreso`, `valor_egreso`, `user_login`, `liquidado`, `caja_tipo`) VALUES
(1, '2024-05-01 08:29:17', 4, 'Parqueo por HORA - AND13E', 21000, 0, 2, 'SI', 'ingreso'),
(2, '2024-05-02 09:03:19', 4, 'Parqueo por HORA - BUU64F', 25000, 0, 2, 'SI', 'ingreso'),
(3, '2024-05-03 08:59:54', 4, 'Parqueo por HORA - AND13E', 24000, 0, 2, 'SI', 'ingreso'),
(4, '2024-05-04 15:31:50', 4, 'Parqueo por 12 HORAS - AAA666', 0, 0, 2, 'NO', 'ingreso'),
(5, '2024-05-04 15:32:04', 4, 'Parqueo por HORA - AAA111', 2000, 0, 2, 'NO', 'ingreso'),
(6, '2024-05-04 15:32:07', 4, 'Parqueo por HORA - CBD018', 108000, 0, 2, 'NO', 'ingreso'),
(7, '2024-05-04 15:32:09', 4, 'Parqueo por HORA - CAA123', 8000, 0, 2, 'NO', 'ingreso'),
(8, '2024-05-04 15:32:11', 4, 'Parqueo por HORA - CDQ54B', 4000, 0, 2, 'NO', 'ingreso'),
(9, '2024-05-04 15:32:13', 4, 'Parqueo por 12 HORAS - AND13E', 6000, 0, 2, 'NO', 'ingreso'),
(10, '2024-05-04 15:32:16', 4, 'Parqueo por HORA - AAA222', 3000, 0, 2, 'NO', 'ingreso'),
(11, '2024-05-04 15:32:19', 4, 'Parqueo por HORA - AAA333', 5000, 0, 2, 'NO', 'ingreso'),
(12, '2024-05-04 15:32:21', 4, 'Parqueo por HORA - AAA555', 8000, 0, 2, 'NO', 'ingreso'),
(13, '2024-05-04 15:32:23', 4, 'Parqueo por HORA - AAA444', 2000, 0, 2, 'NO', 'ingreso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_conceptos`
--

CREATE TABLE `caja_conceptos` (
  `id_concepto` int(11) NOT NULL,
  `nombre_concepto` varchar(50) NOT NULL,
  `observacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `caja_conceptos`
--

INSERT INTO `caja_conceptos` (`id_concepto`, `nombre_concepto`, `observacion`) VALUES
(4, 'Parqueadero', 'Servicio de parqueo por horas o dias.'),
(5, 'Mensualidad', 'Servicio de parqueo por mensualidades.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `cat_id` int(11) NOT NULL,
  `cat_nombre` varchar(30) NOT NULL,
  `cat_descripcion` varchar(200) NOT NULL,
  `cat_imagen` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`cat_id`, `cat_nombre`, `cat_descripcion`, `cat_imagen`) VALUES
(1, 'MOTOS', 'Motocicletas de cualquier cilindraje.', '../assets/icons/moto.svg'),
(2, 'LIVIANOS', 'Automoviles, Camperos, camionetas, Utilitarios, vans.', '../assets/icons/automobile.svg'),
(3, 'MEDIANOS', 'Turbos, Busetas, Camionetas estacadas', '../assets/icons/buseta.svg'),
(4, 'GRANDES', 'Camiones, dobletroques, buses', '../assets/icons/camion.svg'),
(5, 'PESADOS', 'Tractomulas, traileras, maquinaria pesada', '../assets/icons/truck-001.svg'),
(6, 'OTROS', 'OTROS', '../assets/icons/tractor-001.svg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nombre` varchar(50) NOT NULL,
  `celular` bigint(20) NOT NULL,
  `placa` varchar(6) NOT NULL,
  `vehiculo` varchar(100) NOT NULL,
  `categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`fecha_creacion`, `nombre`, `celular`, `placa`, `vehiculo`, `categoria`) VALUES
('2024-05-04 18:45:16', 'ENSAYO', 1, 'AAA111', 'ENSAYO', 2),
('2024-05-04 19:41:39', 'ENSAYO', 1234567890, 'AAA222', 'NPR', 3),
('2024-05-04 19:45:43', 'ENSAYO', 1234567890, 'AAA333', 'KENWORTH', 5),
('2024-05-04 19:48:25', 'ENSAYO', 1234567890, 'AAA444', 'TOYOTA', 2),
('2024-05-04 20:22:08', 'ENSAYO', 1234567890, 'AAA555', 'KENWORTH', 6),
('2024-05-04 20:29:23', 'ENSAYO', 1234567890, 'AAA666', 'NPR', 3),
('2024-05-04 20:33:41', 'ENSAYO', 1, 'AAA777', 'ENSAYO', 1),
('2024-05-04 20:37:39', 'ENSAYO', 1234567890, 'AAA888', 'MAZDA 323', 2),
('2024-05-04 20:47:42', 'ENSAYO', 1, 'AAA999', 'MONZA', 2),
('2024-04-24 21:15:04', 'CARLOS VALENCIA', 3148139800, 'AND13E', 'DISCOVER 125', 1),
('2024-05-04 20:54:36', 'ENSAYO', 12, 'BBB111', 'TOYOTA', 4),
('2024-04-27 22:17:43', 'LUISA PUERTA', 3217168856, 'BUU64F', 'AKT FLEX', 1),
('2024-05-04 16:37:22', 'PEDRO PEREZ', 1234567890, 'CAA123', 'MAZDA 323', 2),
('2024-04-30 15:10:06', 'CARLOS VALENCIA', 3148139800, 'CBD018', 'DAEWOO ESPERO', 2),
('2024-05-04 16:38:13', 'CARLOS VALENCIA', 3148139800, 'CDQ54B', 'AKT 100', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parqueo`
--

CREATE TABLE `parqueo` (
  `parqueo_id` int(11) NOT NULL,
  `placa_cli` varchar(6) NOT NULL,
  `fecha_ini` datetime NOT NULL DEFAULT current_timestamp(),
  `tarifa` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `estado` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `parqueo`
--

INSERT INTO `parqueo` (`parqueo_id`, `placa_cli`, `fecha_ini`, `tarifa`, `usuario`, `estado`) VALUES
(1, 'AND13E', '2024-04-30 11:39:40', 1, 2, 'NO'),
(2, 'BUU64F', '2024-05-01 08:44:32', 1, 2, 'NO'),
(3, 'AND13E', '2024-05-02 09:05:04', 1, 2, 'NO'),
(4, 'CBD018', '2024-05-02 10:05:38', 4, 2, 'NO'),
(5, 'CAA123', '2024-05-04 11:37:22', 4, 2, 'NO'),
(6, 'CDQ54B', '2024-05-04 11:38:13', 1, 2, 'NO'),
(7, 'AND13E', '2024-05-04 12:34:06', 2, 2, 'NO'),
(12, 'AAA111', '2024-05-04 14:27:22', 4, 2, 'NO'),
(13, 'AAA222', '2024-05-04 14:41:39', 7, 2, 'NO'),
(14, 'AAA333', '2024-05-04 14:45:43', 13, 2, 'NO'),
(15, 'AAA444', '2024-05-04 14:48:25', 4, 2, 'NO'),
(16, 'AAA555', '2024-05-04 15:22:08', 16, 2, 'NO'),
(17, 'AAA666', '2024-05-04 15:29:23', 8, 2, 'NO'),
(18, 'AAA777', '2024-05-04 15:33:41', 1, 2, 'SI'),
(19, 'AAA888', '2024-05-04 15:37:39', 4, 2, 'SI'),
(20, 'AAA999', '2024-05-04 15:47:42', 4, 2, 'SI'),
(21, 'BBB111', '2024-05-04 15:54:36', 10, 2, 'SI'),
(22, 'AND13E', '2024-05-04 16:11:29', 1, 2, 'SI'),
(23, 'BUU64F', '2024-05-04 16:12:28', 1, 2, 'SI'),
(24, 'AAA111', '2024-05-04 16:13:50', 4, 2, 'SI'),
(25, 'CBD018', '2024-05-04 17:31:04', 5, 2, 'SI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos`
--

CREATE TABLE `periodos` (
  `PeriodoId` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `periodos`
--

INSERT INTO `periodos` (`PeriodoId`, `anio`, `Nombre`) VALUES
(202301, 2023, 'Enero'),
(202302, 2023, 'Febrero'),
(202303, 2023, 'Marzo'),
(202304, 2023, 'Abril'),
(202305, 2023, 'Mayo'),
(202306, 2023, 'Junio'),
(202307, 2023, 'Julio'),
(202308, 2023, 'Agosto'),
(202309, 2023, 'Septiembre'),
(202310, 2023, 'Octubre'),
(202311, 2023, 'Noviembre'),
(202312, 2023, 'Diciembre'),
(202401, 2024, 'Enero/2024'),
(202402, 2024, 'Febrero/2024'),
(202403, 2024, 'Marzo/2024'),
(202404, 2024, 'Abril/2024'),
(202405, 2024, 'Mayo/2018'),
(202406, 2024, 'Junio/2018'),
(202407, 2024, 'Julio/2018'),
(202408, 2024, 'Agosto/2018'),
(202409, 2024, 'Septiembre/2018'),
(202410, 2024, 'Octubre/2018'),
(202411, 2024, 'Noviembre/2018'),
(202412, 2024, 'Diciembre/2018'),
(202501, 2025, 'Enero'),
(202502, 2025, 'Febrero'),
(202503, 2025, 'Marzo'),
(202504, 2025, 'abril'),
(202505, 2025, 'Mayo'),
(202506, 2025, 'Junio'),
(202507, 2025, 'Julio'),
(202508, 2025, 'Agosto'),
(202509, 2025, 'Septiembre'),
(202510, 2025, 'Octubre'),
(202511, 2025, 'Noviembre'),
(202512, 2025, 'Diciembre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibo`
--

CREATE TABLE `recibo` (
  `recibo_id` int(11) NOT NULL,
  `fecha_recibo` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ticket` int(11) NOT NULL,
  `placa` varchar(11) NOT NULL,
  `fecha_ini` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `tiempo` varchar(100) NOT NULL,
  `valor_pagado` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `cierre` varchar(2) NOT NULL DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `recibo`
--

INSERT INTO `recibo` (`recibo_id`, `fecha_recibo`, `ticket`, `placa`, `fecha_ini`, `fecha_fin`, `tiempo`, `valor_pagado`, `usuario`, `cierre`) VALUES
(1, '2024-05-01 13:29:19', 1, 'AND13E', '2024-04-30 11:39:40', '2024-05-01 08:29:17', '20 Horas y 49 min ', 21000, 2, 'NO'),
(2, '2024-05-02 14:03:22', 2, 'BUU64F', '2024-05-01 08:44:32', '2024-05-02 09:03:19', '1 Dias - 0 Horas y 18 min ', 25000, 2, 'NO'),
(3, '2024-05-03 13:59:58', 3, 'AND13E', '2024-05-02 09:05:04', '2024-05-03 08:59:54', '23 Horas y 54 min ', 24000, 2, 'NO'),
(4, '2024-05-04 20:32:04', 17, 'AAA666', '2024-05-04 15:29:23', '2024-05-04 15:31:50', '2 min ', 0, 2, 'NO'),
(5, '2024-05-04 20:32:07', 12, 'AAA111', '2024-05-04 14:27:22', '2024-05-04 15:32:04', '1 Horas y 4 min ', 2000, 2, 'NO'),
(6, '2024-05-04 20:32:09', 4, 'CBD018', '2024-05-02 10:05:38', '2024-05-04 15:32:07', '2 Dias - 5 Horas y 26 min ', 108000, 2, 'NO'),
(7, '2024-05-04 20:32:11', 5, 'CAA123', '2024-05-04 11:37:22', '2024-05-04 15:32:09', '3 Horas y 54 min ', 8000, 2, 'NO'),
(8, '2024-05-04 20:32:13', 6, 'CDQ54B', '2024-05-04 11:38:13', '2024-05-04 15:32:11', '3 Horas y 53 min ', 4000, 2, 'NO'),
(9, '2024-05-04 20:32:16', 7, 'AND13E', '2024-05-04 12:34:06', '2024-05-04 15:32:13', '2 Horas y 58 min ', 6000, 2, 'NO'),
(10, '2024-05-04 20:32:19', 13, 'AAA222', '2024-05-04 14:41:39', '2024-05-04 15:32:16', '50 min ', 3000, 2, 'NO'),
(11, '2024-05-04 20:32:21', 14, 'AAA333', '2024-05-04 14:45:43', '2024-05-04 15:32:19', '46 min ', 5000, 2, 'NO'),
(12, '2024-05-04 20:32:22', 16, 'AAA555', '2024-05-04 15:22:08', '2024-05-04 15:32:21', '10 min ', 8000, 2, 'NO'),
(13, '2024-05-04 20:32:24', 15, 'AAA444', '2024-05-04 14:48:25', '2024-05-04 15:32:23', '43 min ', 2000, 2, 'NO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifas`
--

CREATE TABLE `tarifas` (
  `tar_id` int(11) NOT NULL,
  `tar_categoria` int(11) NOT NULL,
  `tar_nombre` int(11) NOT NULL,
  `tar_valor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `tarifas`
--

INSERT INTO `tarifas` (`tar_id`, `tar_categoria`, `tar_nombre`, `tar_valor`) VALUES
(1, 1, 1, 1000),
(2, 1, 2, 6000),
(3, 1, 3, 50000),
(4, 2, 1, 2000),
(5, 2, 2, 12000),
(6, 2, 3, 120000),
(7, 3, 1, 3000),
(8, 3, 2, 18000),
(9, 3, 3, 160000),
(10, 4, 1, 4000),
(11, 4, 2, 20000),
(12, 4, 3, 200000),
(13, 5, 1, 5000),
(14, 5, 2, 30000),
(15, 5, 3, 220000),
(16, 6, 1, 8000),
(17, 6, 2, 30000),
(18, 6, 3, 250000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tar_tiempo`
--

CREATE TABLE `tar_tiempo` (
  `tar_id_nombre` int(11) NOT NULL,
  `tar_tiempo` varchar(50) NOT NULL,
  `tar_T.desc` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `tar_tiempo`
--

INSERT INTO `tar_tiempo` (`tar_id_nombre`, `tar_tiempo`, `tar_T.desc`) VALUES
(1, 'HORA', 'Valor cobrado en el sistema por horas'),
(2, '12 HORAS', 'Valor cobrado por cada 12 horas que este el vehiculo en el parqueadero'),
(3, 'MENSUALIDAD', 'Periodo cobrado por cada mes o 30 dias');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cargo`
--

CREATE TABLE `tipo_cargo` (
  `id_cargo` int(11) NOT NULL,
  `cargo_nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_cargo`
--

INSERT INTO `tipo_cargo` (`id_cargo`, `cargo_nombre`) VALUES
(1, 'Tecnologia'),
(2, 'Administrador'),
(3, 'Vigilante'),
(4, 'Auxiliar'),
(5, 'jardinero'),
(6, 'Mantenimiento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuarios`
--

CREATE TABLE `tipo_usuarios` (
  `id_tipo_usuario` int(2) NOT NULL,
  `tipo_usuario` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipo_usuarios`
--

INSERT INTO `tipo_usuarios` (`id_tipo_usuario`, `tipo_usuario`) VALUES
(1, 'Tecnologia'),
(2, 'Administrador'),
(3, 'Empleados');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(20) NOT NULL,
  `nombre` text NOT NULL,
  `tipo_cargo` int(11) NOT NULL,
  `telefono` bigint(10) NOT NULL,
  `usuario` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `clave` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `tipo_usuario` int(5) NOT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `activo` varchar(2) NOT NULL,
  `contabilidad` varchar(2) NOT NULL DEFAULT 'SI'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `tipo_cargo`, `telefono`, `usuario`, `clave`, `tipo_usuario`, `avatar`, `activo`, `contabilidad`) VALUES
(2, 'Carlos Valencia', 1, 3148139800, 'CARVAL1225', '827ccb0eea8a706c4c34a16891f84e7b', 1, '../usuarios/images/carlos.png', 'SI', 'SI'),
(3, 'Enrique Valencia', 4, 3155217685, 'ENVAL01', '01cfcd4f6b8770febfb40cb906715822', 3, '../assets/img/logo.png', 'SI', 'SI');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `arqueo`
--
ALTER TABLE `arqueo`
  ADD PRIMARY KEY (`arqueo_id`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `cajero` (`cajero`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `user_login` (`user_login`),
  ADD KEY `movimiento` (`movimiento`),
  ADD KEY `user_login_2` (`user_login`);

--
-- Indices de la tabla `caja_conceptos`
--
ALTER TABLE `caja_conceptos`
  ADD PRIMARY KEY (`id_concepto`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`placa`),
  ADD KEY `categoria` (`categoria`);

--
-- Indices de la tabla `parqueo`
--
ALTER TABLE `parqueo`
  ADD PRIMARY KEY (`parqueo_id`),
  ADD KEY `placa_cli` (`placa_cli`),
  ADD KEY `tarifa` (`tarifa`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `periodos`
--
ALTER TABLE `periodos`
  ADD PRIMARY KEY (`PeriodoId`);

--
-- Indices de la tabla `recibo`
--
ALTER TABLE `recibo`
  ADD PRIMARY KEY (`recibo_id`),
  ADD KEY `placa` (`placa`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `ticket` (`ticket`);

--
-- Indices de la tabla `tarifas`
--
ALTER TABLE `tarifas`
  ADD PRIMARY KEY (`tar_id`),
  ADD KEY `tar_categoria` (`tar_categoria`),
  ADD KEY `tar_nombre` (`tar_nombre`);

--
-- Indices de la tabla `tar_tiempo`
--
ALTER TABLE `tar_tiempo`
  ADD PRIMARY KEY (`tar_id_nombre`);

--
-- Indices de la tabla `tipo_cargo`
--
ALTER TABLE `tipo_cargo`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `tipo_usuarios`
--
ALTER TABLE `tipo_usuarios`
  ADD PRIMARY KEY (`id_tipo_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_usuario` (`tipo_usuario`),
  ADD KEY `tipo_cargo` (`tipo_cargo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `arqueo`
--
ALTER TABLE `arqueo`
  MODIFY `arqueo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `caja_conceptos`
--
ALTER TABLE `caja_conceptos`
  MODIFY `id_concepto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `parqueo`
--
ALTER TABLE `parqueo`
  MODIFY `parqueo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `recibo`
--
ALTER TABLE `recibo`
  MODIFY `recibo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `tarifas`
--
ALTER TABLE `tarifas`
  MODIFY `tar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `tar_tiempo`
--
ALTER TABLE `tar_tiempo`
  MODIFY `tar_id_nombre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_cargo`
--
ALTER TABLE `tipo_cargo`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_usuarios`
--
ALTER TABLE `tipo_usuarios`
  MODIFY `id_tipo_usuario` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `arqueo`
--
ALTER TABLE `arqueo`
  ADD CONSTRAINT `arqueo_ibfk_1` FOREIGN KEY (`cajero`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `arqueo_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `caja`
--
ALTER TABLE `caja`
  ADD CONSTRAINT `caja_ibfk_1` FOREIGN KEY (`movimiento`) REFERENCES `caja_conceptos` (`id_concepto`),
  ADD CONSTRAINT `caja_ibfk_2` FOREIGN KEY (`user_login`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `parqueo`
--
ALTER TABLE `parqueo`
  ADD CONSTRAINT `parqueo_ibfk_1` FOREIGN KEY (`placa_cli`) REFERENCES `cliente` (`placa`),
  ADD CONSTRAINT `parqueo_ibfk_2` FOREIGN KEY (`tarifa`) REFERENCES `tarifas` (`tar_id`),
  ADD CONSTRAINT `parqueo_ibfk_3` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `recibo`
--
ALTER TABLE `recibo`
  ADD CONSTRAINT `recibo_ibfk_1` FOREIGN KEY (`placa`) REFERENCES `cliente` (`placa`),
  ADD CONSTRAINT `recibo_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `recibo_ibfk_3` FOREIGN KEY (`ticket`) REFERENCES `parqueo` (`parqueo_id`);

--
-- Filtros para la tabla `tarifas`
--
ALTER TABLE `tarifas`
  ADD CONSTRAINT `tarifas_ibfk_1` FOREIGN KEY (`tar_categoria`) REFERENCES `categorias` (`cat_id`),
  ADD CONSTRAINT `tarifas_ibfk_2` FOREIGN KEY (`tar_nombre`) REFERENCES `tar_tiempo` (`tar_id_nombre`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`tipo_usuario`) REFERENCES `tipo_usuarios` (`id_tipo_usuario`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`tipo_cargo`) REFERENCES `tipo_cargo` (`id_cargo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
