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
(3, 'ALMACEN Y VENTA DE TRUCHA'),
(4, 'ALQUILER'),
(5, 'AUTOSERVICIO'),
(6, 'BARBERIA'),
(7, 'BARBER SHOP'),
(8, 'BAZAR'),
(9, 'BODEGA'),
(10, 'BOTICA'),
(11, 'CABINAS DE INTERNET'),
(12, 'CAFETERIA'),
(13, 'CARPINTERIA'),
(14, 'CASA APUESTA DEPORTIVA'),
(15, 'CASA DE APUESTAS DEPORTIVAS'),
(16, 'CASA PRESTAMO'),
(17, 'CASA DE EMPEÑO'),
(18, 'CENTRO FISIOTERAPIA'),
(19, 'CENTRO MEDICO'),
(20, 'CENTRO DE FAENAMIENTO DE AVES'),
(21, 'CERRAJERIA'),
(22, 'CEVICHERIA'),
(23, 'CHIFA'),
(24, 'COMPRA Y VENTA LANA'),
(25, 'CONFECCION'),
(26, 'CONFITERIA'),
(27, 'CONSULTORIO DENTAL'),
(28, 'CONSULTORIO MEDICO'),
(29, 'DISTRIBUIDORA'),
(30, 'DROGUERIA'),
(31, 'INS. EDUCATIVA / INSTITUCIÓN EDUCATIVA (UNIFICADO A: INSTITUCION EDUCATIVA)'),
(32, 'ELABORACION PROD. LACTEOS'),
(33, 'ENTIDAD FINANCIERA'),
(34, 'ESTACION DE RUTA'),
(35, 'EXHIBICION DE VEHICULO'),
(36, 'FARMACIA'),
(37, 'FERRETERIA'),
(38, 'FUENTE DE SODA'),
(39, 'FOTOCOPIAS'),
(40, 'GIMNASIO'),
(41, 'GRASS SINTETICO'),
(42, 'GRIFO'),
(43, 'HOSPEDAJE'),
(44, 'IMPRESIONES (UNIFICANDO IMPRESIÓN)'),
(45, 'INTERNET'),
(46, 'JUEGO MECANICO'),
(47, 'JUGUERIA'),
(48, 'LAVADERO DE VEHICULO'),
(49, 'LIBRERIA'),
(50, 'LLANTERIA'),
(51, 'LOCAL DE EVENTOS'),
(52, 'LUBRICENTRO'),
(53, 'MADEDERA'),
(54, 'MANTENIMIENTO COMPUTADORA'),
(55, 'MERCADO'),
(56, 'MERCERIA'),
(57, 'MARISQUERIA'),
(58, 'MECANICA'),
(59, 'MECANICA DE MOTOS'),
(60, 'MINIMARKET'),
(61, 'OFICINA ADMINISTRATIVA'),
(62, 'PANADERIA'),
(63, 'PELUQUERIA'),
(64, 'PET SHOP'),
(65, 'PIÑATERIA'),
(66, 'PIZZERIA'),
(67, 'PLAYA DE ESTACIONAMIENTO'),
(68, 'POLLERIA'),
(69, 'REPARACION DE TUBOS DE ESCAPE'),
(70, 'RESTAURANTE'),
(71, 'SALON DE BELLEZA'),
(72, 'SASTRERIA'),
(73, 'SERV. TEC. LINEA BLANCA'),
(74, 'SOLDADURA'),
(75, 'TALLER DE PLANCHADO Y PINTURA'),
(76, 'TALLER DE REPARACION RADIO Y TV'),
(77, 'TAPICERIA'),
(78, 'TAPIZADO DE MOTOS'),
(79, 'TORNERIA'),
(80, 'VENTA DE ART. FERRETERIA'),
(81, 'VENTA ACCES. CELULAR'),
(82, 'VENTA DE ROPA'),
(83, 'VENTA DE LUBRICANTES'),
(84, 'VENTA DE PRODUCTOS DE PANADERIA'),
(85, 'VENTA DE FRUTOS SECOS'),
(86, 'VENTA DE GLP ENVASADO'),
(87, 'VENTA DE GRANOS'),
(88, 'VENTA DE ABARROTES'),
(89, 'VENTA DE MELAMINE'),
(90, 'VENTA DE ACCESORIOS PARA VEHICULO'),
(91, 'VENTA VARIOS'),
(92, 'VETERINARIA'),
(93, 'VIDRIERIA');

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
(1, '9 de Octubre'),
(2, 'Ancalá'),
(3, 'Auquimarca'),
(4, 'Auray'),
(5, 'Azapampa'),
(6, 'Azapampa Este'),
(7, 'Azapampa Oeste'),
(8, 'Barrio San José'),
(9, 'Chilca Cercado'),
(10, 'Cooperativa Túpac Amaru'),
(11, 'Coto-Coto'),
(12, 'La Esperanza'),
(13, 'Llamus'),
(14, 'Ocopilla'),
(15, 'Pishupyacun'),
(16, 'Progreso'),
(17, 'Santísima Cruz de Chilca'),
(18, 'Señor de los Milagros'),
(19, 'Chilca');

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
  MODIFY `idgiro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

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
  MODIFY `id_zona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
