-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-04-2025 a las 15:18:37
-- Versión del servidor: 10.4.16-MariaDB
-- Versión de PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `portfolio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elementos`
--

CREATE TABLE `elementos` (
  `ID_elemento` int(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `tone` tinyint(1) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `stock_lote` int(11) NOT NULL DEFAULT 0,
  `stock_pale` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `elementos`
--

INSERT INTO `elementos` (`ID_elemento`, `nombre`, `codigo`, `tipo`, `tone`, `imagen`, `stock_lote`, `stock_pale`) VALUES
(150, 'PRUEBA', 'PRUEBA', 'Materias', 0, '', 2, 4),
(151, 'PRUEBA', 'PRUEBA', 'Bobinas', 0, '', 1, 2),
(152, 'PRUEBA', 'PRUEBA', 'Productos', 0, '', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

CREATE TABLE `incidencias` (
  `id_incidencias` int(10) NOT NULL,
  `tecnico` varchar(100) NOT NULL,
  `elemento` varchar(100) NOT NULL,
  `lote` varchar(100) NOT NULL,
  `pale` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `comentario` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE `lote` (
  `Id_lote` int(10) NOT NULL,
  `lote` varchar(100) CHARACTER SET utf8 NOT NULL,
  `stock_lote` int(100) NOT NULL,
  `ID_elementos` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `lote`
--

INSERT INTO `lote` (`Id_lote`, `lote`, `stock_lote`, `ID_elementos`) VALUES
(50, 'PRUEBA1', 2, 150),
(51, 'PRUEBA2', 2, 151),
(52, 'PRUEBA3', 2, 152),
(53, 'PRUEBA', 2, 150);

--
-- Disparadores `lote`
--
DELIMITER $$
CREATE TRIGGER `after_lote_insert` AFTER INSERT ON `lote` FOR EACH ROW BEGIN
    UPDATE elementos
    SET stock_lote = stock_lote + 1
    WHERE ID_elemento = NEW.ID_elementos;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pale`
--

CREATE TABLE `pale` (
  `ID_pale` int(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ID_lote` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pale`
--

INSERT INTO `pale` (`ID_pale`, `nombre`, `ID_lote`) VALUES
(351, 'PRUEBA1-1', 50),
(352, 'PRUEBA1-2', 50),
(353, 'PRUEBA2-1', 51),
(354, 'PRUEBA2-2', 51),
(355, 'PRUEBA3-1', 52),
(356, 'PRUEBA3-2', 52),
(357, 'PRUEBA-1', 53),
(358, 'PRUEBA-2', 53);

--
-- Disparadores `pale`
--
DELIMITER $$
CREATE TRIGGER `after_pale_insert` AFTER INSERT ON `pale` FOR EACH ROW BEGIN
    UPDATE elementos
    SET stock_pale = stock_pale + 1
    WHERE ID_elemento = (SELECT ID_elementos FROM lote WHERE Id_lote = NEW.Id_lote);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_stock_lote_after_delete` AFTER DELETE ON `pale` FOR EACH ROW BEGIN
    UPDATE lote
    SET stock_lote = stock_lote - 1
    WHERE Id_lote = OLD.Id_lote;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `fecha_produccion` date NOT NULL,
  `fecha_entrega` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `nombre`, `tipo`, `estado`, `fecha_produccion`, `fecha_entrega`) VALUES
(4, 'PRUEBA', 'Trailer', 0, '9999-12-31', '2025-04-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_productos`
--

CREATE TABLE `pedido_productos` (
  `id_pedido_producto` int(10) NOT NULL,
  `id_pedido` int(100) NOT NULL,
  `cantidad` int(100) NOT NULL,
  `ID_elemento` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pedido_productos`
--

INSERT INTO `pedido_productos` (`id_pedido_producto`, `id_pedido`, `cantidad`, `ID_elemento`) VALUES
(6, 4, 2, 152);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `ID` int(10) NOT NULL,
  `tecnico` varchar(30) NOT NULL,
  `fecha` datetime NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`ID`, `tecnico`, `fecha`, `descripcion`) VALUES
(973, 'PRUEBA', '2025-04-01 14:14:30', 'Lote PRUEBA registrado. Cantidad añadida: 2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnico`
--

CREATE TABLE `tecnico` (
  `ID` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `rol` enum('admin','tecnico','administracion') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tecnico`
--

INSERT INTO `tecnico` (`ID`, `usuario`, `email`, `contrasena`, `imagen`, `rol`) VALUES
(97, 'PRUEBA', 'PRUEBA', '$2y$10$GGfzxFLcssP0ikiqkfnP1uP0tC/DYKzGJtP9aiePw6rPwfn5v21Ie', NULL, 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `elementos`
--
ALTER TABLE `elementos`
  ADD PRIMARY KEY (`ID_elemento`);

--
-- Indices de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD PRIMARY KEY (`id_incidencias`);

--
-- Indices de la tabla `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`Id_lote`),
  ADD KEY `ID_elementos` (`ID_elementos`) USING BTREE;

--
-- Indices de la tabla `pale`
--
ALTER TABLE `pale`
  ADD PRIMARY KEY (`ID_pale`),
  ADD KEY `ID_lote` (`ID_lote`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Indices de la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  ADD PRIMARY KEY (`id_pedido_producto`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_elemento` (`ID_elemento`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `tecnico`
--
ALTER TABLE `tecnico`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `elementos`
--
ALTER TABLE `elementos`
  MODIFY `ID_elemento` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  MODIFY `id_incidencias` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lote`
--
ALTER TABLE `lote`
  MODIFY `Id_lote` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `pale`
--
ALTER TABLE `pale`
  MODIFY `ID_pale` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=359;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  MODIFY `id_pedido_producto` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=974;

--
-- AUTO_INCREMENT de la tabla `tecnico`
--
ALTER TABLE `tecnico`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `lote`
--
ALTER TABLE `lote`
  ADD CONSTRAINT `id_elementos` FOREIGN KEY (`ID_elementos`) REFERENCES `elementos` (`ID_elemento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pale`
--
ALTER TABLE `pale`
  ADD CONSTRAINT `id_pale` FOREIGN KEY (`ID_lote`) REFERENCES `lote` (`Id_lote`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  ADD CONSTRAINT `id_elemento` FOREIGN KEY (`ID_elemento`) REFERENCES `elementos` (`ID_elemento`),
  ADD CONSTRAINT `id_pedidos` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
