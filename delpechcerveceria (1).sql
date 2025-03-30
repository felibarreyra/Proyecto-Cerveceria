-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-03-2025 a las 00:00:46
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
(45, '001', 1, 2, '50', 'LLENO', '2025-03-28'),
(46, '002', 1, 1, '20', 'LLENO', NULL),
(47, '006', 3, 1, '20', 'LLENO', '2025-03-28'),
(48, '010', 4, 1, '20', 'VACIO', NULL);

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
(4, 'ZONA_VACIOS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `variedades`
--

CREATE TABLE `variedades` (
  `id_variedad` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `variedades`
--

INSERT INTO `variedades` (`id_variedad`, `nombre`) VALUES
(1, 'IPA'),
(2, 'BLONDE'),
(3, 'HONEY'),
(4, 'VACIO');

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
  MODIFY `id_barril` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `lugar`
--
ALTER TABLE `lugar`
  MODIFY `id_lugar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `variedades`
--
ALTER TABLE `variedades`
  MODIFY `id_variedad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
