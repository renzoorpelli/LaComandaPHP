-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2022 at 11:55 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tpcomanda`
--

-- --------------------------------------------------------

--
-- Table structure for table `comanda`
--

CREATE TABLE `comanda` (
  `id` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comanda`
--

INSERT INTO `comanda` (`id`, `id_mesa`, `id_pedido`, `id_empleado`) VALUES
(1, 1, 1, 8),
(2, 2, 2, 8);

-- --------------------------------------------------------

--
-- Table structure for table `estado_mesa`
--

CREATE TABLE `estado_mesa` (
  `id` int(11) NOT NULL,
  `nombre_estado` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `estado_mesa`
--

INSERT INTO `estado_mesa` (`id`, `nombre_estado`) VALUES
(1, 'cerrada'),
(2, 'con cliente esperando pedido'),
(3, 'con cliente comiendo'),
(4, 'con cliente pagando');

-- --------------------------------------------------------

--
-- Table structure for table `estado_pedido`
--

CREATE TABLE `estado_pedido` (
  `id` int(11) NOT NULL,
  `nombre_estado` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `estado_pedido`
--

INSERT INTO `estado_pedido` (`id`, `nombre_estado`) VALUES
(1, 'En preparación'),
(2, 'Listo para servir'),
(3, 'Entregado');

-- --------------------------------------------------------

--
-- Table structure for table `estado_producto`
--

CREATE TABLE `estado_producto` (
  `id` int(11) NOT NULL,
  `nombre_estado` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `estado_producto`
--

INSERT INTO `estado_producto` (`id`, `nombre_estado`) VALUES
(1, 'en preparacion'),
(2, 'listo para servir'),
(3, 'recibido en cocina');

-- --------------------------------------------------------

--
-- Table structure for table `logs_empleado`
--

CREATE TABLE `logs_empleado` (
  `id` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_logue` date NOT NULL,
  `accion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mesa`
--

CREATE TABLE `mesa` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mesa`
--

INSERT INTO `mesa` (`id`, `numero`, `id_estado`, `fecha_baja`) VALUES
(1, 1, 2, '2022-11-19'),
(2, 2, 2, NULL),
(3, 3, 1, NULL),
(4, 4, 1, NULL),
(5, 5, 1, NULL),
(6, 6, 1, NULL),
(7, 7, 1, NULL),
(8, 8, 1, NULL),
(9, 9, 1, NULL),
(10, 10, 1, NULL),
(11, 11, 1, NULL),
(12, 12, 1, NULL),
(13, 13, 1, NULL),
(14, 14, 1, NULL),
(15, 15, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `nombre_cliente` varchar(200) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `codigo_pedido` varchar(50) DEFAULT NULL,
  `tiempo_finalizacion` int(11) DEFAULT NULL,
  `total_pedido` float DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pedido`
--

INSERT INTO `pedido` (`id`, `nombre_cliente`, `id_estado`, `codigo_pedido`, `tiempo_finalizacion`, `total_pedido`, `fecha_creacion`) VALUES
(1, 'Pedro', 2, '63797a5aa6e7f', 10, 200, '2022-11-19'),
(2, 'Franco', 2, '63797bf9f3523', 60, 200, '2022-11-19'),
(3, 'Tomas', 1, '63797d7a0caf5', NULL, NULL, '2022-11-19'),
(4, 'Lucas', 1, '63797dc251c84', NULL, NULL, '2022-11-20');

-- --------------------------------------------------------

--
-- Table structure for table `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `codigo_producto` varchar(200) NOT NULL,
  `precio` float NOT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `tiempo_preparacion` int(11) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `codigo_producto`, `precio`, `id_tipo`, `fecha_baja`, `tiempo_preparacion`, `id_estado`) VALUES
(1, 'Hamburguesa Garbanzo', 'aaaxx2124', 200, 1, NULL, 10, 1),
(2, 'Hamburguesa Garbanzo', 'aaaxx2124', 200, 1, NULL, 20, 2),
(3, 'Daikiri', 'aaaxx2124', 500, 3, NULL, 20, 2),
(4, 'Corona', 'aaaxx2121', 300, 2, NULL, 20, 2);

-- --------------------------------------------------------

--
-- Table structure for table `producto_pedido`
--

CREATE TABLE `producto_pedido` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `producto_pedido`
--

INSERT INTO `producto_pedido` (`id`, `id_producto`, `id_pedido`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 2, 2),
(4, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `nombre_rol` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rol`
--

INSERT INTO `rol` (`id`, `nombre_rol`) VALUES
(1, 'socio'),
(2, 'bartender'),
(3, 'cervecero'),
(4, 'cocinero'),
(5, 'mozo');

-- --------------------------------------------------------

--
-- Table structure for table `tipo_producto`
--

CREATE TABLE `tipo_producto` (
  `id` int(11) NOT NULL,
  `nombre_tipo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tipo_producto`
--

INSERT INTO `tipo_producto` (`id`, `nombre_tipo`) VALUES
(1, 'comida'),
(2, 'cerveza'),
(3, 'trago');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `clave`, `id_rol`, `fecha_baja`) VALUES
(4, 'lito', '$2y$10$TU3Qr18EUZbDNqTl6ClzRupOH5s4UHDU3wejpAMuCkfnj2Ue0d0Ni', 1, NULL),
(5, 'juancito', '$2y$10$sZ5XzpxLpuWJUlxBLZVRaeesZ63snfFOOYEJKt6SSVSlemxwz7vD.', 2, '2022-11-19'),
(6, 'pablo', 'abc4565', 3, NULL),
(7, 'thomas', 'abc4565', 4, NULL),
(8, 'ignacio', 'abc4565', 5, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comanda`
--
ALTER TABLE `comanda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mesa` (`id_mesa`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indexes for table `estado_mesa`
--
ALTER TABLE `estado_mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estado_pedido`
--
ALTER TABLE `estado_pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estado_producto`
--
ALTER TABLE `estado_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs_empleado`
--
ALTER TABLE `logs_empleado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indexes for table `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indexes for table `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipoProducto_ibfk_1` (`id_tipo`),
  ADD KEY `productofk` (`id_estado`);

--
-- Indexes for table `producto_pedido`
--
ALTER TABLE `producto_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indexes for table `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comanda`
--
ALTER TABLE `comanda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `estado_mesa`
--
ALTER TABLE `estado_mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `estado_pedido`
--
ALTER TABLE `estado_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `estado_producto`
--
ALTER TABLE `estado_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `logs_empleado`
--
ALTER TABLE `logs_empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `producto_pedido`
--
ALTER TABLE `producto_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tipo_producto`
--
ALTER TABLE `tipo_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comanda`
--
ALTER TABLE `comanda`
  ADD CONSTRAINT `comanda_ibfk_1` FOREIGN KEY (`id_mesa`) REFERENCES `mesa` (`id`),
  ADD CONSTRAINT `comanda_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`),
  ADD CONSTRAINT `comanda_ibfk_3` FOREIGN KEY (`id_empleado`) REFERENCES `usuario` (`id`);

--
-- Constraints for table `logs_empleado`
--
ALTER TABLE `logs_empleado`
  ADD CONSTRAINT `logs_empleado_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `usuario` (`id`);

--
-- Constraints for table `mesa`
--
ALTER TABLE `mesa`
  ADD CONSTRAINT `mesa_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado_mesa` (`id`);

--
-- Constraints for table `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado_pedido` (`id`);

--
-- Constraints for table `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `productofk` FOREIGN KEY (`id_estado`) REFERENCES `estado_producto` (`id`),
  ADD CONSTRAINT `tipoProducto_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_producto` (`id`);

--
-- Constraints for table `producto_pedido`
--
ALTER TABLE `producto_pedido`
  ADD CONSTRAINT `producto_pedido_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `producto_pedido_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`);

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
