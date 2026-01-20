-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-01-2026 a las 11:37:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

-- CREAMOS LA BASE DE DATOS DEVSWAP
CREATE DATABASE IF NOT EXISTS DEVSWAP;
USE DEVSWAP;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `devswap`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `CORREO_A` varchar(50) NOT NULL,
  `NICK_A` varchar(10) DEFAULT NULL,
  `CONTRASEÑA_A` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`CORREO_A`, `NICK_A`, `CONTRASEÑA_A`) VALUES
('LUISADMIN@GMAIL.COM', 'ADMINPRO', 'LUIS123'),
('MARIO@HOTMAIL.COM', 'MARIOG', 'MARIOG'),
('PEDRO@GMAIL.COM', 'PEDROADMIN', 'PEDROADMIN123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `NOMBRE_C` varchar(50) NOT NULL,
  `DESCRIPCION_C` varchar(100) DEFAULT NULL,
  `IMAGEN_C` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`NOMBRE_C`, `DESCRIPCION_C`, `IMAGEN_C`) VALUES
('ACCESORIOS', 'ACCESORIOS: CASCOS, TECLADOS..', 'imagenes/Emoji/emoji_Accesorio.avif'),
('AUDIO', 'OBJETOS RELACIONADOS CON EL AUDIO: CASCOS, ALTAVOCES...', 'imagenes/Emoji/emoji_Audio.webp'),
('LIBROS', 'LIBROS ORIENTADOS A LA PROGRAMACIÓN', 'imagenes/Emoji/emoji_libros.png'),
('PORTATILES', 'PORTATILES DE TODAS LAS MARCAS', 'imagenes/Emoji/emoji_Portatil.png'),
('SMARTPHONE', 'SMARTPHONES DE TODAS LAS MARCAS', 'imagenes/Emoji/emoji_telefono.png'),
('TABLETS', 'TABLES DE TODAS LAS MARCAS', 'imagenes/Emoji/emoji_Tablet.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestiona_c`
--

CREATE TABLE `gestiona_c` (
  `CORREO_A` varchar(50) NOT NULL,
  `NOMBRE_C` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gestiona_c`
--

INSERT INTO `gestiona_c` (`CORREO_A`, `NOMBRE_C`) VALUES
('LUISADMIN@GMAIL.COM', 'ACCESORIOS'),
('LUISADMIN@GMAIL.COM', 'PORTATILES'),
('MARIO@HOTMAIL.COM', 'AUDIO'),
('MARIO@HOTMAIL.COM', 'LIBROS'),
('PEDRO@GMAIL.COM', 'SMARTPHONE'),
('PEDRO@GMAIL.COM', 'TABLETS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestiona_u`
--

CREATE TABLE `gestiona_u` (
  `CORREO_U` varchar(50) NOT NULL,
  `CORREO_A` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gestiona_u`
--

INSERT INTO `gestiona_u` (`CORREO_U`, `CORREO_A`) VALUES
('HUGOUSER@GMAIL.COM', 'LUISADMIN@GMAIL.COM'),
('SARAUSER@HOTMAIL.COM', 'PEDRO@GMAIL.COM'),
('SERGIO12@GMAIL.COM', 'MARIO@HOTMAIL.COM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicacion1`
--

CREATE TABLE `publicacion1` (
  `ID_P` varchar(10) NOT NULL,
  `CORREO_U` varchar(50) NOT NULL,
  `NOMBRE_C` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicacion1`
--

INSERT INTO `publicacion1` (`ID_P`, `CORREO_U`, `NOMBRE_C`) VALUES
('ID001', 'HUGOUSER@GMAIL.COM', 'AUDIO'),
('ID002', 'HUGOUSER@GMAIL.COM', 'SMARTPHONE'),
('ID003', 'SARAUSER@HOTMAIL.COM', 'LIBROS'),
('ID004', 'SARAUSER@HOTMAIL.COM', 'PORTATILES'),
('ID005', 'SERGIO12@GMAIL.COM', 'TABLETS'),
('ID006', 'SERGIO12@GMAIL.COM', 'ACCESORIOS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicacion2`
--

CREATE TABLE `publicacion2` (
  `ID_P` varchar(10) NOT NULL,
  `CAMBIO` varchar(100) DEFAULT NULL,
  `ESTADO` varchar(10) DEFAULT NULL,
  `UBICACION` varchar(20) DEFAULT NULL,
  `NOMBRE_P` varchar(100) DEFAULT NULL,
  `DESCRIPCION_P` varchar(100) DEFAULT NULL,
  `IMAGEN_P` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicacion2`
--

INSERT INTO `publicacion2` (`ID_P`, `CAMBIO`, `ESTADO`, `UBICACION`, `NOMBRE_P`, `DESCRIPCION_P`, `IMAGEN_P`) VALUES
('ID001', 'LIBROS DE PROGRAMACION', 'USADO', 'SALAMANCA', 'CASCOS SONY', 'CASCOS DE LA MARCA SONY', 'imagenes/Fotos/cascos.jpg'),
('ID002', 'IPAD AIR', 'NUEVO', 'BARCELONA', 'IPHONE 17 PRO', 'IPHONE NUEVO A ESTRENAR', 'imagenes/Fotos/iPhone.jpg'),
('ID003', 'TECLADOS DE ORDENADOR', 'USADO', 'MADRID', 'EL PROGRAMADOR PRAGMATICO', 'LIBRO SOBRE PROGRAMACION', 'imagenes/Fotos/libro.jpg'),
('ID004', 'SMARTPHONES NUEVOS', 'RESTAURADO', 'CADIZ', 'MACBOOK PRO', 'PORTATIL DE LA MARCA APPLE', 'imagenes/Fotos/MacBook.jpg'),
('ID005', 'PORTATIL', 'SEMINUEVA', 'VALLADOLID', 'XIAOMI PAD 7', 'TABLET XIAOMI USADA', 'imagenes/Fotos/tablet.jpg'),
('ID006', 'FUNDAS PARA XIAOMI', 'USADO', 'CACERES', 'TECLADO DE LUCES RGB', 'TECLADO CON LUCES PARA GAMING', 'imagenes/Fotos/tecladoRGB.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicita`
--

CREATE TABLE `solicita` (
  `CORREO_U` varchar(50) NOT NULL,
  `ID_P` varchar(10) NOT NULL,
  `CALIFICACION` float DEFAULT NULL,
  `FECHA` date DEFAULT NULL,
  `ESTADO` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicita`
--

INSERT INTO `solicita` (`CORREO_U`, `ID_P`, `CALIFICACION`, `FECHA`, `ESTADO`) VALUES
('HUGOUSER@GMAIL.COM', 'ID005', 4.5, '2025-12-02', 'ACEPTADO'),
('SARAUSER@HOTMAIL.COM', 'ID001', 3.5, '2026-01-04', 'RECHAZADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `CORREO_U` varchar(50) NOT NULL,
  `NICK_U` varchar(10) DEFAULT NULL,
  `APELLIDO1` varchar(10) DEFAULT NULL,
  `APELLIDO2` varchar(10) DEFAULT NULL,
  `NOMBRE_U` varchar(10) DEFAULT NULL,
  `TELEFONO` int(11) DEFAULT NULL,
  `CIUDAD` varchar(20) DEFAULT NULL,
  `CONTRASEÑA_U` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`CORREO_U`, `NICK_U`, `APELLIDO1`, `APELLIDO2`, `NOMBRE_U`, `TELEFONO`, `CIUDAD`, `CONTRASEÑA_U`) VALUES
('HUGOUSER@GMAIL.COM', 'HUGOPRO', 'GONZALEZ', 'PEREZ', 'HUGO', 1234, 'VALLADOLID', 'HUGOPRO'),
('SARAUSER@HOTMAIL.COM', 'SARAGARCIA', 'GARCIA', 'HERNANDEZ', 'SARA', 4567, 'MADRID', 'SARA123'),
('SERGIO12@GMAIL.COM', 'SERGIO12', 'BUENO', 'DIAZ', 'SERGIO', 1234567, 'BARCELONA', 'SERGIO12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`CORREO_A`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`NOMBRE_C`);

--
-- Indices de la tabla `gestiona_c`
--
ALTER TABLE `gestiona_c`
  ADD PRIMARY KEY (`CORREO_A`,`NOMBRE_C`),
  ADD KEY `NOMBRE_C` (`NOMBRE_C`);

--
-- Indices de la tabla `gestiona_u`
--
ALTER TABLE `gestiona_u`
  ADD PRIMARY KEY (`CORREO_U`,`CORREO_A`),
  ADD KEY `CORREO_A` (`CORREO_A`);

--
-- Indices de la tabla `publicacion1`
--
ALTER TABLE `publicacion1`
  ADD PRIMARY KEY (`ID_P`,`CORREO_U`,`NOMBRE_C`),
  ADD KEY `CORREO_U` (`CORREO_U`),
  ADD KEY `NOMBRE_C` (`NOMBRE_C`);

--
-- Indices de la tabla `publicacion2`
--
ALTER TABLE `publicacion2`
  ADD PRIMARY KEY (`ID_P`);

--
-- Indices de la tabla `solicita`
--
ALTER TABLE `solicita`
  ADD PRIMARY KEY (`CORREO_U`,`ID_P`),
  ADD KEY `ID_P` (`ID_P`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`CORREO_U`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `gestiona_c`
--
ALTER TABLE `gestiona_c`
  ADD CONSTRAINT `gestiona_c_ibfk_1` FOREIGN KEY (`CORREO_A`) REFERENCES `administrador` (`CORREO_A`) ON DELETE CASCADE,
  ADD CONSTRAINT `gestiona_c_ibfk_2` FOREIGN KEY (`NOMBRE_C`) REFERENCES `categoria` (`NOMBRE_C`) ON DELETE CASCADE;

--
-- Filtros para la tabla `gestiona_u`
--
ALTER TABLE `gestiona_u`
  ADD CONSTRAINT `gestiona_u_ibfk_1` FOREIGN KEY (`CORREO_U`) REFERENCES `usuario` (`CORREO_U`) ON DELETE CASCADE,
  ADD CONSTRAINT `gestiona_u_ibfk_2` FOREIGN KEY (`CORREO_A`) REFERENCES `administrador` (`CORREO_A`) ON DELETE CASCADE;

--
-- Filtros para la tabla `publicacion1`
--
ALTER TABLE `publicacion1`
  ADD CONSTRAINT `publicacion1_ibfk_1` FOREIGN KEY (`CORREO_U`) REFERENCES `usuario` (`CORREO_U`) ON DELETE CASCADE,
  ADD CONSTRAINT `publicacion1_ibfk_2` FOREIGN KEY (`NOMBRE_C`) REFERENCES `categoria` (`NOMBRE_C`) ON DELETE CASCADE;

--
-- Filtros para la tabla `publicacion2`
--
ALTER TABLE `publicacion2`
  ADD CONSTRAINT `publicacion2_ibfk_1` FOREIGN KEY (`ID_P`) REFERENCES `publicacion1` (`ID_P`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicita`
--
ALTER TABLE `solicita`
  ADD CONSTRAINT `solicita_ibfk_1` FOREIGN KEY (`CORREO_U`) REFERENCES `usuario` (`CORREO_U`) ON DELETE CASCADE,
  ADD CONSTRAINT `solicita_ibfk_2` FOREIGN KEY (`ID_P`) REFERENCES `publicacion1` (`ID_P`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
