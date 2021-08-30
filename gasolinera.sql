-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-08-2021 a las 13:59:43
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
-- Base de datos: `gasolinera`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bombas`
--

CREATE TABLE `bombas` (
  `id` int(11) NOT NULL,
  `claveBomba` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `fechaCorte` date NOT NULL,
  `totalVenta` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombreCliente` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombreCliente`) VALUES
(1, 'MATERIALES EL TRIUNFO'),
(2, 'UNIVERSIDAD DE LA CAÑADA'),
(3, 'ALICIA MORA ROJAS'),
(4, 'MUNICIPIO DE TEOTITLAN DE FLORES MAGON'),
(5, 'ANALI CONCEPCION VELA GUTIERREZ'),
(6, 'ARCENIO ARROYO SANTIAGO'),
(7, 'EDIFICACIONES Y OBRAS CIVILES KACHINA'),
(8, 'TICKET CARD'),
(9, 'EFECTICARD'),
(10, 'TPV BANCOMER'),
(11, 'TPV BANORTE'),
(12, 'TPV BANORTE 2'),
(13, 'RICARDO HERNANDEZ CRUZ'),
(14, 'DIAC S.A. DE C.V.'),
(15, 'INE'),
(16, 'NORA LUZ PEREZ'),
(17, 'LASMONG INGENIERIA S.A. DE C.V.'),
(18, 'CONSTRUCTORA SERVADAC S.A. DE C.V.'),
(19, 'TRITURADOS FRANJUSA'),
(20, 'INSTITUTO PARA ADULTOS HUAUTLA'),
(21, 'INSTITUTO PARA ADULTOS'),
(22, 'BP FLEE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventasclientes`
--

CREATE TABLE `ventasclientes` (
  `id` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `totalVenta` float NOT NULL,
  `fechaVenta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bombas`
--
ALTER TABLE `bombas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventasclientes`
--
ALTER TABLE `ventasclientes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bombas`
--
ALTER TABLE `bombas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `ventasclientes`
--
ALTER TABLE `ventasclientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
