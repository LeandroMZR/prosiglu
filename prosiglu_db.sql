-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-05-2025 a las 17:33:33
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
('8499990000015', 'Pan de molde sin gluten', NULL, 2.50, 'imagenes/panMolde.jpg', 1),
('8499990000023', 'Pan baguette sin gluten', NULL, 2.80, 'imagenes/panBaguette.jpg', 1),
('8499990000030', 'Pan de centeno sin gluten', NULL, 2.70, 'imagenes/panCenteno.jpg', 1),
('8499990000047', 'Pan rústico sin gluten', NULL, 2.90, 'imagenes/panRustico.jpg', 1),
('8499990000054', 'Base de pizza fina sin gluten', NULL, 3.00, 'imagenes/basePizzaFina.jpg', 2),
('8499990000061', 'Base de pizza gruesa sin gluten', NULL, 3.20, 'imagenes/basePizzaGruesa.jpg', 2),
('8499990000078', 'Galletas de chocolate sin gluten', NULL, 2.60, 'imagenes/galletasChocolate.jpg', 3),
('8499990000085', 'Galletas de avena sin gluten', NULL, 2.40, 'imagenes/galletasAvena.jpg', 3),
('8499990000092', 'Galletas María sin gluten', NULL, 2.30, 'imagenes/galletaMaria.jpg', 3),
('8499990000108', 'Muffins de chocolate sin gluten', NULL, 3.20, 'imagenes/muffinsChocolate.jpg', 4),
('8499990000115', 'Croissants sin gluten', NULL, 3.00, 'imagenes/croissants.jpg', 4),
('8499990000122', 'Napolitanas sin gluten', NULL, 3.10, 'imagenes/napolitanas.jpg', 4),
('8499990000139', 'Bizcocho de vainilla sin gluten', NULL, 3.50, 'imagenes/bizcochoVainilla.jpg', 5),
('8499990000146', 'Brownie sin gluten', NULL, 3.70, 'imagenes/brownie.jpg', 5),
('8499990000153', 'Tarta de queso sin gluten', NULL, 4.00, 'imagenes/tartaQueso.jpg', 5),
('8499990000160', 'Barritas energéticas de frutos secos', NULL, 1.80, 'imagenes/barritasEnergeticasFrutosSecos.jpg', 6),
('8499990000177', 'Barritas energéticas de chocolate', NULL, 1.90, 'imagenes/barritasEnergeticasChocolate.jpg', 6),
('8499990000184', 'Granola con miel sin gluten', NULL, 2.50, 'imagenes/granolaMiel.jpg', 6),
('8499990000191', 'Granola con chocolate sin gluten', NULL, 2.60, 'imagenes/granolaChocolate.jpg', 6),
('8499990000207', 'Almendras saborizadas sin gluten', NULL, 2.20, 'imagenes/almendrasSaborizadas.jpg', 6),
('8499990000214', 'Espaguetis sin gluten', NULL, 2.90, 'imagenes/espaguetis.jpg', 7),
('8499990000221', 'Macarrones sin gluten', NULL, 2.80, 'imagenes/macarrones.jpg', 7),
('8499990000238', 'Lasaña sin gluten', NULL, 3.20, 'imagenes/lasaña.jpg', 7),
('8499990000245', 'Harina de arroz sin gluten', NULL, 1.50, 'imagenes/harinaArroz.jpg', 8),
('8499990000252', 'Harina de almendra sin gluten', NULL, 2.30, 'imagenes/harinaAlmendra.jpg', 8),
('8499990000269', 'Harina de coco sin gluten', NULL, 2.00, 'imagenes/harinaCoco.jpg', 8),
('8499990000276', 'Pizza margarita sin gluten', NULL, 4.00, 'imagenes/pizzaMargarita.jpg', 9),
('8499990000283', 'Pizza cuatro quesos sin gluten', NULL, 4.20, 'imagenes/pizzaCuatroQuesos.jpg', 9),
('8499990000290', 'Empanadas de carne sin gluten', NULL, 3.90, 'imagenes/empanadaCarne.jpg', 9),
('8499990000306', 'Lasaña boloñesa sin gluten', NULL, 4.50, 'imagenes/lasañaBoloñesa.jpg', 9),
('8499990000313', 'San Jacobos sin gluten', NULL, 4.30, 'imagenes/sanJacobos.jpg', 9),
('8499990000320', 'Nuggets de pollo sin gluten', NULL, 3.80, 'imagenes/nuggetsPollo.jpg', 9),
('8499990000337', 'Cerveza rubia sin gluten', NULL, 1.80, 'imagenes/cervezaRubia.jpg', 10),
('8499990000344', 'Cerveza negra sin gluten', NULL, 2.00, 'imagenes/cervezaNegra.jpg', 10),
('8499990000351', 'Fingers de pollo sin gluten', NULL, 3.50, 'imagenes/fingersPollo.jpg', 11),
('8499990000368', 'Milanesa de ternera sin gluten', NULL, 3.90, 'imagenes/milanesa.jpeg', 11);

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
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
