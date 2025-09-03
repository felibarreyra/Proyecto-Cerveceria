-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-04-2025 a las 18:16:10
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
-- Base de datos: `delpechcerveceria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `barriles`
--

CREATE TABLE `barriles` (
  `id_barril` int(11) NOT NULL,
  `codigo` varchar(11) NOT NULL,
  `id_variedad` int(11) NOT NULL,
  `id_lugar` int(11) NOT NULL,
  `litros` enum('20','30','50') NOT NULL,
  `estado` enum('LLENO','VACIO','EN USO','') NOT NULL,
  `fecha_venta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `barriles`
--

INSERT INTO `barriles` (`id_barril`, `codigo`, `id_variedad`, `id_lugar`, `litros`, `estado`, `fecha_venta`) VALUES
(70, '03', 4, 4, '20', 'VACIO', NULL),
(71, '04', 4, 4, '20', 'VACIO', NULL),
(72, '05', 4, 4, '20', 'VACIO', NULL),
(73, '06', 4, 4, '20', 'VACIO', NULL),
(74, '07', 4, 4, '20', 'VACIO', NULL),
(75, '08', 4, 4, '20', 'VACIO', NULL),
(76, '09', 4, 4, '20', 'VACIO', NULL),
(77, '10', 4, 4, '20', 'VACIO', NULL),
(78, '11', 4, 4, '30', 'VACIO', NULL),
(79, '12', 4, 4, '30', 'VACIO', NULL),
(80, '13', 4, 4, '30', 'VACIO', NULL),
(81, '14', 4, 4, '30', 'VACIO', NULL),
(82, '15', 4, 4, '30', 'VACIO', NULL),
(83, '16', 4, 4, '30', 'VACIO', NULL),
(84, '17', 4, 4, '30', 'VACIO', NULL),
(85, '18', 4, 4, '30', 'VACIO', NULL),
(86, '19', 4, 4, '30', 'VACIO', NULL),
(87, '20', 4, 4, '30', 'VACIO', NULL),
(88, '21', 4, 4, '30', 'VACIO', NULL),
(89, '22', 4, 4, '30', 'VACIO', NULL),
(90, '23', 4, 4, '30', 'VACIO', NULL),
(91, '51', 4, 4, '50', 'VACIO', NULL),
(92, '52', 4, 4, '50', 'VACIO', NULL),
(93, '53', 4, 4, '50', 'VACIO', NULL),
(94, '54', 4, 4, '50', 'VACIO', NULL),
(95, '55', 4, 4, '50', 'VACIO', NULL),
(96, '56', 4, 4, '50', 'VACIO', NULL),
(97, '57', 4, 4, '50', 'VACIO', NULL),
(98, '58', 4, 4, '50', 'VACIO', NULL),
(99, '59', 4, 4, '50', 'VACIO', NULL),
(100, '60', 4, 4, '50', 'VACIO', NULL),
(101, '61', 4, 4, '50', 'VACIO', NULL),
(102, '62', 4, 4, '50', 'VACIO', NULL),
(103, '63', 4, 4, '50', 'VACIO', NULL),
(104, '64', 4, 4, '50', 'VACIO', NULL),
(105, '65', 4, 4, '50', 'VACIO', NULL),
(106, '66', 4, 4, '50', 'VACIO', NULL),
(107, '67', 4, 4, '50', 'VACIO', NULL),
(108, '68', 4, 4, '50', 'VACIO', NULL),
(109, '69', 4, 4, '50', 'VACIO', NULL),
(110, '70', 4, 4, '50', 'VACIO', NULL),
(111, '71', 4, 4, '50', 'VACIO', NULL),
(112, '72', 4, 4, '50', 'VACIO', NULL),
(113, '73', 4, 4, '50', 'VACIO', NULL),
(114, '74', 4, 4, '50', 'VACIO', NULL),
(115, '75', 4, 4, '50', 'VACIO', NULL),
(116, '76', 4, 4, '50', 'VACIO', NULL),
(117, '77', 4, 4, '50', 'VACIO', NULL),
(118, '78', 4, 4, '50', 'VACIO', NULL),
(119, '79', 4, 4, '50', 'VACIO', NULL),
(120, '80', 4, 4, '50', 'VACIO', NULL),
(121, '81', 4, 4, '50', 'VACIO', NULL),
(122, '82', 4, 4, '50', 'VACIO', NULL),
(123, '83', 4, 4, '50', 'VACIO', NULL),
(124, '84', 4, 4, '50', 'VACIO', NULL),
(125, '85', 4, 4, '50', 'VACIO', NULL),
(126, '86', 4, 4, '50', 'VACIO', NULL),
(127, '87', 4, 4, '50', 'VACIO', NULL),
(128, '88', 4, 4, '50', 'VACIO', NULL),
(129, '89', 4, 4, '50', 'VACIO', NULL),
(130, '90', 4, 4, '50', 'VACIO', NULL),
(131, '91', 4, 4, '50', 'VACIO', NULL),
(132, '92', 4, 4, '50', 'VACIO', NULL),
(133, '93', 4, 4, '50', 'VACIO', NULL),
(134, '94', 4, 4, '50', 'VACIO', NULL),
(135, '95', 4, 4, '50', 'VACIO', NULL),
(136, '96', 4, 4, '50', 'VACIO', NULL),
(137, '97', 4, 4, '50', 'VACIO', NULL),
(138, '98', 4, 4, '50', 'VACIO', NULL),
(139, '99', 4, 4, '50', 'VACIO', NULL),
(140, '100', 4, 4, '50', 'VACIO', NULL),
(141, '101', 4, 4, '50', 'VACIO', NULL),
(142, '102', 4, 4, '50', 'VACIO', NULL),
(143, '103', 4, 4, '50', 'VACIO', NULL),
(144, '104', 4, 4, '50', 'VACIO', NULL),
(145, '105', 4, 4, '50', 'VACIO', NULL),
(146, '106', 4, 4, '50', 'VACIO', NULL),
(147, '107', 4, 4, '50', 'VACIO', NULL),
(148, '108', 4, 4, '50', 'VACIO', NULL),
(149, '109', 4, 4, '50', 'VACIO', NULL),
(150, '110', 4, 4, '50', 'VACIO', NULL),
(151, '111', 4, 4, '50', 'VACIO', NULL),
(152, '112', 4, 4, '50', 'VACIO', NULL),
(153, '113', 4, 4, '50', 'VACIO', NULL),
(154, '114', 4, 4, '50', 'VACIO', NULL),
(155, '115', 4, 4, '50', 'VACIO', NULL),
(156, '116', 4, 4, '50', 'VACIO', NULL),
(157, '117', 4, 4, '50', 'VACIO', NULL),
(158, '118', 4, 4, '50', 'VACIO', NULL),
(159, '119', 4, 4, '50', 'VACIO', NULL),
(160, '120', 4, 4, '50', 'VACIO', NULL),
(164, '01', 4, 4, '20', 'VACIO', NULL),
(165, '02', 4, 4, '20', 'VACIO', NULL),
(167, '24', 4, 4, '30', 'VACIO', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugar`
--

CREATE TABLE `lugar` (
  `id_lugar` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lugar`
--

INSERT INTO `lugar` (`id_lugar`, `nombre`) VALUES
(1, 'CAMARA'),
(2, 'BROTHERS'),
(4, 'ZONA_VACIOS'),
(5, 'LA CHACRA'),
(6, 'MIRADOR'),
(7, 'EL MITRE'),
(8, 'BRUNA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `variedades`
--

CREATE TABLE `variedades` (
  `id_variedad` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio_x_litro` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `variedades`
--

INSERT INTO `variedades` (`id_variedad`, `nombre`, `precio_x_litro`) VALUES
(1, 'SESSION IPA', 2200.00),
(2, 'BLONDE', 1750.00),
(3, 'HONEY', 1950.00),
(4, 'VACIO', NULL),
(5, 'IRISH RED', 1900.00),
(6, 'PORTER', 2000.00),
(7, 'NEIPA', 2300.00),
(8, 'WITBIER', 1950.00),
(9, 'GOLDEN PARADISE', 1800.00),
(10, 'PECAN BROWN', 2000.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `barriles`
--
ALTER TABLE `barriles`
  ADD PRIMARY KEY (`id_barril`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_variedad` (`id_variedad`),
  ADD KEY `id_lugar` (`id_lugar`);

--
-- Indices de la tabla `lugar`
--
ALTER TABLE `lugar`
  ADD PRIMARY KEY (`id_lugar`);

--
-- Indices de la tabla `variedades`
--
ALTER TABLE `variedades`
  ADD PRIMARY KEY (`id_variedad`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `barriles`
--
ALTER TABLE `barriles`
  MODIFY `id_barril` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT de la tabla `lugar`
--
ALTER TABLE `lugar`
  MODIFY `id_lugar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `variedades`
--
ALTER TABLE `variedades`
  MODIFY `id_variedad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `barriles`
--
ALTER TABLE `barriles`
  ADD CONSTRAINT `barriles_ibfk_1` FOREIGN KEY (`id_variedad`) REFERENCES `variedades` (`id_variedad`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barriles_ibfk_2` FOREIGN KEY (`id_lugar`) REFERENCES `lugar` (`id_lugar`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
