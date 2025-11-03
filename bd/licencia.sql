-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-08-2021 a las 07:36:01
-- Versión del servidor: 10.4.20-MariaDB
-- Versión de PHP: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `licencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `giro`
--

CREATE TABLE `giro` (
  `idgiro` int(11) NOT NULL,
  `nombregiro` varchar(60) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `giro`
--

INSERT INTO `giro` (`idgiro`, `nombregiro`) VALUES
(1, 'pizarra'),
(2, 'libro'),
(3, 'coche'),
(4, 'lapiz');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencia`
--

CREATE TABLE `licencia` (
  `idlicencia` int(11) NOT NULL,
  `exp_num` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `idtienda` int(11) NOT NULL,
  `idgiro` int(11) NOT NULL,
  `nombre_comercial` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `numrecibo_tesoreria` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `num_resolucion` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `vigencia_lic` date NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_expedicion` date NOT NULL,
  `qr` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_lic` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `num_tipolic` char(6) COLLATE utf8_spanish_ci NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `licencia`
--

INSERT INTO `licencia` (`idlicencia`, `exp_num`, `idtienda`, `idgiro`, `nombre_comercial`, `numrecibo_tesoreria`, `num_resolucion`, `vigencia_lic`, `fecha_ingreso`, `fecha_expedicion`, `qr`, `tipo_lic`, `num_tipolic`, `condicion`) VALUES
(1, '00001', 1, 2, 'adada', '445665656', '985665', '2021-09-03', '2021-08-24', '2021-09-04', '00001.png', '2', '000001', 1),
(2, '00002', 3, 1, 'asdasd', '7787878', '787878', '2021-08-28', '2021-08-23', '2021-08-28', '00002.png', '2', '000002', 1),
(3, '00003', 2, 3, 'asdasdasd', '2232', '989998', '2021-08-28', '2021-08-31', '2021-08-30', '00003.png', '1', '000001', 1),
(4, '00004', 2, 3, 'wewew', '4555', '6566', '2021-08-28', '2021-08-31', '2021-08-27', '00004.png', '2', '000003', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tienda`
--

CREATE TABLE `tienda` (
  `idtienda` int(11) NOT NULL,
  `numruc` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `nombres_per` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `apellidop_per` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `apellidom_per` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `ubic_tienda` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `area_tienda` varchar(40) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tienda`
--

INSERT INTO `tienda` (`idtienda`, `numruc`, `nombres_per`, `apellidop_per`, `apellidom_per`, `ubic_tienda`, `area_tienda`) VALUES
(1, '545554', 'manuel jesus', 'ramos', 'aliaga', 'nmnmnm', 'nmmn'),
(2, '66532154', 'hghg', 'hg', 'gh', 'ghgh', 'gh'),
(3, '4544545', 'g', 'vbb', 'asdas', 'vc', 'bv');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idpersona` int(11) NOT NULL,
  `nombres` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `apellidop` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `apellidom` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `dni` varchar(8) COLLATE utf8_spanish_ci NOT NULL,
  `contrasena` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idpersona`, `nombres`, `apellidop`, `apellidom`, `direccion`, `dni`, `contrasena`, `condicion`) VALUES
(1, 'admin', 'admin', 'admin', 'admin', '22222222', '$2y$10$8aLo7qAVr0lt/rwvG.C9B.KJEXz.zNJ9OI.jtmD7bfZ5MD2ZZoTHq', 1),
(2, 'aad', 'ad', 'asdas', 'asdsa', '66666666', '$2y$10$.3BC42m9nqR3G05k2KlxMOIbbR2p2t.6GsPwNImNTsDrT5eNBsvv.', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `giro`
--
ALTER TABLE `giro`
  ADD PRIMARY KEY (`idgiro`);

--
-- Indices de la tabla `licencia`
--
ALTER TABLE `licencia`
  ADD PRIMARY KEY (`idlicencia`),
  ADD KEY `licencia-tienda` (`idtienda`),
  ADD KEY `licencia-giro` (`idgiro`);

--
-- Indices de la tabla `tienda`
--
ALTER TABLE `tienda`
  ADD PRIMARY KEY (`idtienda`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idpersona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `giro`
--
ALTER TABLE `giro`
  MODIFY `idgiro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `licencia`
--
ALTER TABLE `licencia`
  MODIFY `idlicencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tienda`
--
ALTER TABLE `tienda`
  MODIFY `idtienda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idpersona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `licencia`
--
ALTER TABLE `licencia`
  ADD CONSTRAINT `licencia-giro` FOREIGN KEY (`idgiro`) REFERENCES `giro` (`idgiro`),
  ADD CONSTRAINT `licencia-tienda` FOREIGN KEY (`idtienda`) REFERENCES `tienda` (`idtienda`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
