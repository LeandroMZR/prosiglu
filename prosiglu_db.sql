-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2025 a las 20:11:30
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
-- Base de datos: `prosiglu_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carritos`
--

INSERT INTO `carritos` (`id`, `usuario_id`, `fecha_creacion`) VALUES
(1, 1, '2025-05-21 12:40:53'),
(2, 2, '2025-05-21 17:01:30'),
(3, 4, '2025-05-21 18:16:15'),
(4, 5, '2025-05-21 18:21:09'),
(5, 6, '2025-05-22 20:01:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_productos`
--

CREATE TABLE `carrito_productos` (
  `id` int(11) NOT NULL,
  `carrito_id` int(11) NOT NULL,
  `gtin` varchar(20) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(5, 'Carne Rebozada'),
(4, 'Cervezas'),
(3, 'Congelados'),
(1, 'Panadería y Repostería'),
(2, 'Pastas y Harinas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `gtin` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(6,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `subcategoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`gtin`, `nombre`, `descripcion`, `precio`, `imagen`, `subcategoria_id`) VALUES
('8499990000015', 'Pan de molde\r\n', NULL, 2.50, 'panMolde.jpg', 1),
('8499990000023', 'Pan baguette', NULL, 2.80, 'panBaguette.jpg', 1),
('8499990000030', 'Pan de centeno', NULL, 2.70, 'panCenteno.jpg', 1),
('8499990000047', 'Pan rústico', NULL, 2.90, 'panRustico.jpg', 1),
('8499990000054', 'Base de pizza fina', NULL, 3.00, 'basePizzaFina.jpg', 2),
('8499990000061', 'Base de pizza gruesa', NULL, 3.20, 'basePizzaGruesa.jpg', 2),
('8499990000078', 'Galletas de chocolate', NULL, 2.60, 'galletasChocolate.jpg', 3),
('8499990000085', 'Galletas de avena', NULL, 2.40, 'galletasAvena.jpg', 3),
('8499990000092', 'Galletas María', NULL, 2.30, 'galletaMaria.jpg', 3),
('8499990000108', 'Muffins de chocolate', NULL, 3.20, 'muffinsChocolate.jpg', 4),
('8499990000115', 'Croissants', NULL, 3.00, 'croissants.jpg', 4),
('8499990000122', 'Napolitanas', NULL, 3.10, 'napolitanas.jpg', 4),
('8499990000139', 'Bizcocho de vainilla', NULL, 3.50, 'bizcochoVainilla.jpg', 5),
('8499990000146', 'Brownie', NULL, 3.70, 'brownie.jpg', 5),
('8499990000153', 'Tarta de queso', NULL, 4.00, 'tartaQueso.jpg', 5),
('8499990000160', 'Barritas energéticas de frutos secos', NULL, 1.80, 'barritasEnergeticasFrutosSecos.jpg', 6),
('8499990000177', 'Barritas energéticas de chocolate', NULL, 1.90, 'barritasEnergeticasChocolate.jpg', 6),
('8499990000184', 'Granola con miel', NULL, 2.50, 'granolaMiel.jpg', 6),
('8499990000191', 'Granola con chocolate', NULL, 2.60, 'granolaChocolate.jpg', 6),
('8499990000207', 'Almendras saborizadas', NULL, 2.20, 'almendrasSaborizadas.jpg', 6),
('8499990000214', 'Espaguetis', NULL, 2.90, 'espaguetis.jpg', 7),
('8499990000221', 'Macarrones', NULL, 2.80, 'macarrones.jpg', 7),
('8499990000238', 'Lasaña', NULL, 3.20, 'lasaña.jpg', 7),
('8499990000245', 'Harina de arroz', NULL, 1.50, 'harinaArroz.jpg', 8),
('8499990000252', 'Harina de almendra', NULL, 2.30, 'harinaAlmendra.jpg', 8),
('8499990000269', 'Harina de coco', NULL, 2.00, 'harinaCoco.jpg', 8),
('8499990000276', 'Pizza margarita', NULL, 4.00, 'pizzaMargarita.jpg', 9),
('8499990000283', 'Pizza cuatro quesos', NULL, 4.20, 'pizzaCuatroQuesos.jpg', 9),
('8499990000290', 'Empanadas de carne', NULL, 3.90, 'empanadaCarne.jpg', 9),
('8499990000306', 'Lasaña boloñesa', NULL, 4.50, 'lasañaBoloñesa.jpg', 9),
('8499990000313', 'San Jacobos', NULL, 4.30, 'sanJacobos.jpg', 9),
('8499990000320', 'Nuggets de pollo', NULL, 3.80, 'nuggetsPollo.jpg', 9),
('8499990000337', 'Cerveza rubia', NULL, 1.80, 'cervezaRubia.jpg', 10),
('8499990000344', 'Cerveza negra', NULL, 2.00, 'cervezaNegra.jpg', 10),
('8499990000351', 'Fingers de pollo', NULL, 3.50, 'fingersPollo.jpg', 11),
('8499990000368', 'Milanesa de ternera', NULL, 3.90, 'milanesa.jpeg', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcategorias`
--

CREATE TABLE `subcategorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subcategorias`
--

INSERT INTO `subcategorias` (`id`, `nombre`, `categoria_id`) VALUES
(1, 'Panes', 1),
(2, 'Bases de pizza', 1),
(3, 'Galletas', 1),
(4, 'Muffins y Bollería', 1),
(5, 'Bizcochos y Pasteles', 1),
(6, 'Snacks Saludables', 1),
(7, 'Pastas sin gluten', 2),
(8, 'Harinas especiales', 2),
(9, 'Congelados', 3),
(10, 'Cervezas', 4),
(11, 'Carne Rebozada', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `cp` varchar(10) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `email`, `telefono`, `password`, `ciudad`, `cp`, `direccion`) VALUES
(1, 'hola@gmail.com', 'hola@gmail.com', '543455467', '$2y$10$MUSl4ClUywN0TkHisE.hneRl6ZR9CAWHgyCCInyhUhRPLCOTMo.k6', NULL, NULL, NULL),
(2, 'ejemplo@gmail.com', 'ejemplo@gmail.com', '987654321', '$2y$10$boIiviiyF3vj8JLrX57omO1hMxIYsqUQTjNulb8YNS3PeRuA6UdN.', NULL, NULL, NULL),
(4, 'ejempla@gmail.com', 'ejempla@gmail.com', '987654321', '$2y$10$RakA9gEcFteA7h6vSJuMo.E8H3Ej31/med0zluP6wknUdrequUHvW', NULL, NULL, NULL),
(5, 'wiwiwi@wiwi.wi', 'wiwiwi@wiwi.wi', '123456789', '$2y$10$URD/tnYKyTBitTZJx95PrehZrGAhIBzjktdVY/osxyj4GaePBKkXm', NULL, NULL, NULL),
(6, 'ejemplo2@gmail.com', 'ejemplo2@gmail.com', '214365870', '$2y$10$AVvMRQmZgLIuFT1MEcqz4OViflsTTxAJe4kKul5vvr7P2LixLXLYa', 'Ejemplo', '12345', 'Calle de Ejemplo, 2');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `carrito_productos`
--
ALTER TABLE `carrito_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carrito_id` (`carrito_id`),
  ADD KEY `gtin` (`gtin`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`gtin`),
  ADD KEY `subcategoria_id` (`subcategoria_id`);

--
-- Indices de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `carrito_productos`
--
ALTER TABLE `carrito_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `carritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `carrito_productos`
--
ALTER TABLE `carrito_productos`
  ADD CONSTRAINT `carrito_productos_ibfk_1` FOREIGN KEY (`carrito_id`) REFERENCES `carritos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carrito_productos_ibfk_2` FOREIGN KEY (`gtin`) REFERENCES `productos` (`gtin`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`subcategoria_id`) REFERENCES `subcategorias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD CONSTRAINT `subcategorias_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
