-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 04-01-2021 a las 09:43:43
-- Versión del servidor: 10.3.27-MariaDB-cll-lve
-- Versión de PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `wfvrkfap_sunat_padron`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sunat`
--

CREATE TABLE `sunat` (
  `idPadron` int(11) NOT NULL,
  `ruc` varchar(250) NOT NULL,
  `razon_social` varchar(250) NOT NULL,
  `estado` varchar(250) NOT NULL,
  `domicilio` varchar(250) NOT NULL,
  `ubigeo` varchar(250) NOT NULL,
  `via` varchar(250) NOT NULL,
  `nombre_via` varchar(250) NOT NULL,
  `cod_zona` varchar(250) NOT NULL,
  `tipo_zona` varchar(250) NOT NULL,
  `numero` varchar(250) NOT NULL,
  `interior` varchar(250) NOT NULL,
  `lote` varchar(250) NOT NULL,
  `departamento` varchar(250) NOT NULL,
  `MANZANA` varchar(250) NOT NULL,
  `KILÓMETRO` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `sunat`
--
ALTER TABLE `sunat`
  ADD PRIMARY KEY (`idPadron`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `sunat`
--
ALTER TABLE `sunat`
  MODIFY `idPadron` int(11) NOT NULL AUTO_INCREMENT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



CREATE TABLE `sunattemp` (
 `RUC` varchar(11) NOT NULL,
 `NOMBRE O RAZÓN SOCIAL` varchar(250) NOT NULL,
 `ESTADO DEL CONTRIBUYENTE` varchar(250) NOT NULL,
 `CONDICIÓN DE DOMICILIO` varchar(250) NOT NULL,
 `UBIGEO` varchar(250) NOT NULL,
 `TIPO DE VÍA` varchar(250) NOT NULL,
 `NOMBRE DE VÍA` varchar(250) NOT NULL,
 `CÓDIGO DE ZONA` varchar(250) NOT NULL,
 `TIPO DE ZONA` varchar(250) NOT NULL,
 `NÚMERO` varchar(250) NOT NULL,
 `INTERIOR` varchar(250) NOT NULL,
 `LOTE` varchar(250) NOT NULL,
 `DEPARTAMENTO` varchar(250) NOT NULL,
 `MANZANA` varchar(250) NOT NULL,
 `KILÓMETRO` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4


