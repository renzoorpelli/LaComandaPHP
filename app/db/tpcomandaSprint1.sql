-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2022 at 12:51 AM
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
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(2, 2, 1, NULL);

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
  `total_pedido` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `codigo_producto`, `precio`, `id_tipo`, `fecha_baja`) VALUES
(1, 'Hamburguesa', 'aaaxx2123', 200, 1, '2022-11-19');

-- --------------------------------------------------------

--
-- Table structure for table `producto_pedido`
--

CREATE TABLE `producto_pedido` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(2, 'bebida');

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
(5, 'juancito', '$2y$10$sZ5XzpxLpuWJUlxBLZVRaeesZ63snfFOOYEJKt6SSVSlemxwz7vD.', 2, '2022-11-19');

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
  ADD KEY `tipoProducto_ibfk_1` (`id_tipo`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estado_mesa`
--
ALTER TABLE `estado_mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `estado_pedido`
--
ALTER TABLE `estado_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs_empleado`
--
ALTER TABLE `logs_empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `producto_pedido`
--
ALTER TABLE `producto_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tipo_producto`
--
ALTER TABLE `tipo_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
