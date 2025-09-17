-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-09-2025 a las 23:21:21
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
-- Base de datos: `basededatossistec`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas`
--

CREATE TABLE `alertas` (
  `id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `nivel` enum('peligro','cuidado') DEFAULT 'cuidado',
  `fecha` datetime DEFAULT current_timestamp(),
  `numero_tarjeta_rfid` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alertas`
--

INSERT INTO `alertas` (`id`, `mensaje`, `ubicacion`, `nivel`, `fecha`, `numero_tarjeta_rfid`) VALUES
(1, '⚠️ Tarjeta no registrada (UID: 93BC60AD) detectada.', 'Desconocido', 'cuidado', '2025-06-21 22:03:36', NULL),
(2, '📟 PANTALLA 01 ha salido del laboratorio', 'Laboratorio 101', 'peligro', '2025-06-21 22:03:41', NULL),
(3, '⚠️ Tarjeta no registrada (UID: 93BC60AD) detectada.', 'Desconocido', 'cuidado', '2025-06-21 22:05:48', NULL),
(4, '📟 PANTALLA 01 ha salido del laboratorio', 'Laboratorio 101', 'peligro', '2025-06-21 22:05:52', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ubicacion` varchar(100) NOT NULL,
  `estado` enum('Autorizado','No autorizado') NOT NULL DEFAULT 'Autorizado',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `numero_tarjeta_rfid` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `nombre`, `ubicacion`, `estado`, `fecha_registro`, `numero_tarjeta_rfid`) VALUES
(1, 'PANTALLA 01', 'Laboratorio 101', 'Autorizado', '2025-06-22 03:03:24', '03AD87A5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` text NOT NULL,
  `rol` enum('admin','tecnico','invitado') NOT NULL DEFAULT 'invitado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`, `rol`) VALUES
(1, 'JUAN', '$2y$10$hLQS9Sebi/TmF7Q6V4OEru5zbFkCxIuvpdCsiPYTFU54eSNYbl2e2', 'admin'),
(2, 'BORIS', '$2y$10$mKoYbdCQaHHr37LclbmO0.tf6hivUx/dA9IdvosQ01FmGuK8BJlxC', 'tecnico'),
(3, 'jb', '$2y$10$Ce1Mkem4ekQU9wjctLTQC.Z8HarjY8GAh5afADTRykssiMROqzQlS', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_alertas_equipos` (`numero_tarjeta_rfid`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_tarjeta_rfid` (`numero_tarjeta_rfid`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `fk_alertas_equipos` FOREIGN KEY (`numero_tarjeta_rfid`) REFERENCES `equipos` (`numero_tarjeta_rfid`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
