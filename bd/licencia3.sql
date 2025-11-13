-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2025 a las 05:28:24
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
-- Base de datos: `licencia3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `giro`
--

CREATE TABLE `giro` (
  `idgiro` int(11) NOT NULL,
  `nombregiro` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `giro`
--

INSERT INTO `giro` (`idgiro`, `nombregiro`) VALUES
(1, 'AGENTE BANCARIO'),
(2, 'ALMACEN'),
(3, 'ALQUILER'),
(4, 'AUTOSERVICIO'),
(5, 'BARBERIA'),
(6, 'BAZAR'),
(7, 'BODEGA'),
(8, 'BOTICA'),
(9, 'CABINAS DE INTERNET'),
(10, 'CAFERETERIA'),
(11, 'CARPINTERIA'),
(12, 'CASA APUESTA DEPORTIVA'),
(13, 'CASA PRESTAMO'),
(14, 'CENTRO FISIOTERAPIA'),
(15, 'CENTRO MEDICO'),
(16, 'CERRAJERIA'),
(17, 'CEVICHERIA'),
(18, 'COMPRA Y VENTA LANA'),
(19, 'CONFECCION'),
(20, 'CONFITERIA'),
(21, 'CONSULTORIO DENTAL'),
(22, 'CONSULTORIO MEDICO'),
(23, 'DISTRIBUIDORA'),
(24, 'DROGUERIA'),
(25, 'INS. EDUCATIVA'),
(26, 'ELABORACION PROD. LACTEOS'),
(27, 'ENTIDAD FINANCIERA'),
(28, 'ESTACION DE RUTA'),
(29, 'ESHIBICON DE VEHICULO'),
(30, 'FARMACIA'),
(31, 'FUENTE DE SODA'),
(32, 'GIMNASIO'),
(33, 'GRASS SINTETICO'),
(34, 'GRIFO'),
(35, 'HOSPEDAJE'),
(36, 'IMPRESIONES'),
(37, 'JUEGO MECANICO'),
(38, 'JUGUERIA'),
(39, 'LAVADERO DE VEHICULO'),
(40, 'LIBRERÍA'),
(41, 'LOCAL DE EVENTOS'),
(42, 'LUBRICENTRO'),
(43, 'MADEDERA'),
(44, 'MANTENIMIENTO COMPUTADORA'),
(45, 'MECANICA'),
(46, 'MERCADO'),
(47, 'MERCERIA'),
(48, 'MINIMARKET'),
(49, 'OFICINA ADMINISTRATIVA'),
(50, 'PANADERIA'),
(51, 'PELUQUERIA'),
(52, 'PET SHOP'),
(53, 'PIÑATERIA'),
(54, 'PIZZERIA'),
(55, 'PLASTIQUERIA'),
(56, 'PLAYA DE ESTACIONAMIENTO'),
(57, 'POLLERIA'),
(58, 'RESTAURANTE'),
(59, 'SALON DE BELLEZA'),
(60, 'SASTRERIA'),
(61, 'SOLDADURA'),
(62, 'SERV. TEC. LINEA BLANCA'),
(63, 'VENTA DE ART. FERRETERIA'),
(64, 'VENTA ACCES. CELULAR'),
(65, 'VIDRIERIA'),
(66, 'VETERINARIA'),
(67, 'VENTA DE ROPA'),
(68, 'VENTA DE LUBRICANTES'),
(69, 'VENTA DE PRODUCTOS DE PANADERIA'),
(70, 'VENTA DE FRUTOS SECOS'),
(71, 'VENTA GLP ENVASADO'),
(72, 'VENTA DE GRANOS'),
(73, 'VENTA DE ABARROTES'),
(74, 'VENTA VARIOS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intervenciones`
--

CREATE TABLE `intervenciones` (
  `idintervencion` int(11) NOT NULL,
  `idlicencia` int(11) NOT NULL,
  `idtienda` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencia`
--

CREATE TABLE `licencia` (
  `idlicencia` int(11) NOT NULL,
  `exp_num` varchar(90) NOT NULL,
  `idtienda` int(11) NOT NULL,
  `idgiro` int(11) NOT NULL,
  `nombre_comercial` varchar(90) NOT NULL,
  `numrecibo_tesoreria` varchar(90) NOT NULL,
  `num_resolucion` varchar(90) NOT NULL,
  `vigencia_lic` date DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_expedicion` varchar(50) NOT NULL,
  `qr` varchar(50) NOT NULL,
  `tipo_lic` char(1) NOT NULL,
  `num_tipolic` char(6) NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT 1,
  `NumResITSE` varchar(90) NOT NULL,
  `EstadoITSE` tinyint(1) DEFAULT 1,
  `expedicionITSE` date NOT NULL,
  `vigenciaITSE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tienda`
--

CREATE TABLE `tienda` (
  `idtienda` int(11) NOT NULL,
  `numruc` varchar(11) NOT NULL,
  `nombres_per` varchar(80) NOT NULL,
  `apellidop_per` varchar(90) NOT NULL,
  `apellidom_per` varchar(90) NOT NULL,
  `ubic_tienda` varchar(150) NOT NULL,
  `area_tienda` varchar(40) NOT NULL,
  `dni` varchar(8) DEFAULT NULL,
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `celular` varchar(9) DEFAULT NULL,
  `id_zona` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idpersona` int(11) NOT NULL,
  `nombres` varchar(80) NOT NULL,
  `apellidop` varchar(90) NOT NULL,
  `apellidom` varchar(90) NOT NULL,
  `direccion` varchar(90) NOT NULL,
  `dni` varchar(8) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(80) NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT 1,
  `tipo_usuario` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idpersona`, `nombres`, `apellidop`, `apellidom`, `direccion`, `dni`, `correo`, `contrasena`, `condicion`, `tipo_usuario`) VALUES
(1, 'Andriu', 'Goya', 'Acosta', 'Jron Rosa Fung Pineda', '60932604', 'andriu@gmail.com', '$2y$10$Ekvtr3uMW6difqANY4CdPeIVZdPvbkd0oexLU9uowlbEiHghRMlJO', 1, 'Administrador'),
(2, 'Liz', 'Mantari', 'Valentin', 'Calle S/N', '87654321', 'liz@gmail.com', '$2y$10$tgkWIMFJvnuvVWBprQJHc.9aw8FweUNdDdjFeP4XtBPVAaOEntJ6S', 1, 'Administrador'),
(3, 'Antony', 'Yupanqui', 'Gallardo', 'AV S/N', '12345678', 'antony@gmail.com', '$2y$10$XOiXJe3iDdD5VLDLAtjI6ORJw3ygb735VINTR2Ic3dZOkMs6aEgYu', 1, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonas`
--

CREATE TABLE `zonas` (
  `id_zona` int(11) NOT NULL,
  `nombre_zona` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `zonas`
--

INSERT INTO `zonas` (`id_zona`, `nombre_zona`) VALUES
(1, 'Mirador Peñaloza'),
(2, 'Nueva Argentina'),
(3, 'Hualashuata Nueva Generación'),
(4, 'Pichkana'),
(5, 'Buenos Aires'),
(6, 'Vista Alegre'),
(7, 'Atalaya'),
(8, 'Héroes de Azapampa'),
(9, 'Bosques de Azapampa'),
(10, 'Villa Retama'),
(11, 'Peje'),
(12, 'Los Jazmines');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `giro`
--
ALTER TABLE `giro`
  ADD PRIMARY KEY (`idgiro`);

--
-- Indices de la tabla `intervenciones`
--
ALTER TABLE `intervenciones`
  ADD PRIMARY KEY (`idintervencion`),
  ADD KEY `fk_intervencion_licencia` (`idlicencia`),
  ADD KEY `fk_intervencion_tienda` (`idtienda`);

--
-- Indices de la tabla `licencia`
--
ALTER TABLE `licencia`
  ADD PRIMARY KEY (`idlicencia`),
  ADD KEY `fk_licencia_tienda` (`idtienda`),
  ADD KEY `fk_licencia_giro` (`idgiro`);

--
-- Indices de la tabla `tienda`
--
ALTER TABLE `tienda`
  ADD PRIMARY KEY (`idtienda`),
  ADD KEY `fk_tienda_zona` (`id_zona`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idpersona`);

--
-- Indices de la tabla `zonas`
--
ALTER TABLE `zonas`
  ADD PRIMARY KEY (`id_zona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `giro`
--
ALTER TABLE `giro`
  MODIFY `idgiro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `intervenciones`
--
ALTER TABLE `intervenciones`
  MODIFY `idintervencion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `licencia`
--
ALTER TABLE `licencia`
  MODIFY `idlicencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tienda`
--
ALTER TABLE `tienda`
  MODIFY `idtienda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idpersona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `zonas`
--
ALTER TABLE `zonas`
  MODIFY `id_zona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `intervenciones`
--
ALTER TABLE `intervenciones`
  ADD CONSTRAINT `fk_intervencion_licencia` FOREIGN KEY (`idlicencia`) REFERENCES `licencia` (`idlicencia`),
  ADD CONSTRAINT `fk_intervencion_tienda` FOREIGN KEY (`idtienda`) REFERENCES `tienda` (`idtienda`);

--
-- Filtros para la tabla `licencia`
--
ALTER TABLE `licencia`
  ADD CONSTRAINT `fk_licencia_giro` FOREIGN KEY (`idgiro`) REFERENCES `giro` (`idgiro`),
  ADD CONSTRAINT `fk_licencia_tienda` FOREIGN KEY (`idtienda`) REFERENCES `tienda` (`idtienda`);

--
-- Filtros para la tabla `tienda`
--
ALTER TABLE `tienda`
  ADD CONSTRAINT `fk_tienda_zona` FOREIGN KEY (`id_zona`) REFERENCES `zonas` (`id_zona`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
