-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2024 a las 22:06:55
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
-- Base de datos: `epd6`
--
CREATE DATABASE IF NOT EXISTS `epd6` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `epd6`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `descripcion`) VALUES
(57, 'Prueba Rol 3 <Rol: 3> ha realizado una creacion del producto con identificacdor: 1, en la tabla \"producto\".'),
(58, 'Prueba Rol 3 <Rol: 3> ha realizado una vista del producto con identificador: 1, de la tabla \"producto\".'),
(59, 'Prueba Rol 3 <Rol: 3> ha realizado una creacion del producto con identificacdor: 2, en la tabla \"producto\".'),
(60, 'Prueba Rol 3 <Rol: 3> ha realizado una creacion del producto con identificacdor: 3, en la tabla \"producto\".'),
(61, 'Prueba Rol 3 <Rol: 3> ha realizado una vista del producto con identificador: 3, de la tabla \"producto\".'),
(62, 'Prueba Rol 3 <Rol: 3> ha realizado una creacion del producto con identificacdor: 4, en la tabla \"producto\".'),
(63, 'Prueba Rol 3 <Rol: 3> ha realizado una creacion del producto con identificacdor: 5, en la tabla \"producto\".'),
(64, 'Prueba Rol 3 <Rol: 3> ha realizado una creacion del producto con identificacdor: 6, en la tabla \"producto\".');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `sku` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `num_pasillo` int(11) NOT NULL,
  `num_estanteria` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`sku`, `descripcion`, `num_pasillo`, `num_estanteria`, `cantidad`) VALUES
(1, 'Botellas de agua', 1, 1, 500),
(2, 'Paquete de patata', 1, 2, 200),
(3, 'Filipinos chocoblanco', 2, 6, 10000000),
(4, 'Bombones de chocolate', 5, 4, 50004),
(5, 'Panettone', 55, 22, 8512),
(6, 'Arbol de navidad', 8, 2, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(1, 'administrativo'),
(2, 'operario'),
(3, 'administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `password`, `nombre`, `apellidos`, `id_rol`) VALUES
(30, 'pruebarol1@almacen.com', '$2y$10$HuIpODu.M1F8/FWAD5p0p.rDAWhEYESb7dBLkJlRXXjny/oyDvQSS', 'Prueba', 'Rol 1', 1),
(31, 'pruebarol2@almacen.com', '$2y$10$k0W7SjOvx3LM/jXBitA1HeHc3w4C7tIWtUoyt3BPsBcb97fN/uStu', 'Prueba', 'Rol 2', 2),
(32, 'pruebarol3@almacen.com', '$2y$10$DXCEscXVo.CAi99b4zwT6.T.tiJp663udxnCgeuLXu.3m/O3G7T.S', 'Prueba', 'Rol 3', 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`sku`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
