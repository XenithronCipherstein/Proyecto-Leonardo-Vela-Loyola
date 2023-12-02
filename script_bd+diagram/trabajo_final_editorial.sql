-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-12-2023 a las 04:57:33
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
-- Base de datos: `trabajo_final_editorial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `article`
--

CREATE TABLE `article` (
  `ID_Article` int(11) NOT NULL,
  `title` varchar(160) DEFAULT NULL,
  `journal` varchar(160) DEFAULT NULL,
  `volume` varchar(16) DEFAULT NULL,
  `number` smallint(6) DEFAULT NULL,
  `pages` varchar(32) DEFAULT NULL,
  `pub_month` char(3) DEFAULT NULL,
  `pub_year` int(11) DEFAULT NULL,
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `article`
--

INSERT INTO `article` (`ID_Article`, `title`, `journal`, `volume`, `number`, `pages`, `pub_month`, `pub_year`, `note`) VALUES
(2, 'Titulo del Articulo', 'Nombre de la revista', 'Volumen1', 123, '1-20', 'Ene', 2023, 'La Nota es una accion que hace cuando...');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `author`
--

CREATE TABLE `author` (
  `ID_Publication` int(11) NOT NULL,
  `ID_Person` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `author`
--

INSERT INTO `author` (`ID_Publication`, `ID_Person`) VALUES
(4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `book`
--

CREATE TABLE `book` (
  `ID_Book` int(11) NOT NULL,
  `title` varchar(160) DEFAULT NULL,
  `ID_Publisher` int(11) DEFAULT NULL,
  `volume` varchar(16) DEFAULT NULL,
  `series` varchar(160) DEFAULT NULL,
  `edition` varchar(16) DEFAULT NULL,
  `pub_month` char(3) DEFAULT NULL,
  `pub_year` int(11) DEFAULT NULL,
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `book`
--

INSERT INTO `book` (`ID_Book`, `title`, `ID_Publisher`, `volume`, `series`, `edition`, `pub_month`, `pub_year`, `note`) VALUES
(1, 'PrimerTitulo', 1, 'Volumen1', 'Segunda serie', '0', 'Ene', 2023, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `editor`
--

CREATE TABLE `editor` (
  `ID_Publication` int(11) NOT NULL,
  `ID_Person` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `editor`
--

INSERT INTO `editor` (`ID_Publication`, `ID_Person`) VALUES
(4, 1),
(7, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inbook`
--

CREATE TABLE `inbook` (
  `ID_Inbook` int(11) NOT NULL,
  `ID_book` int(11) DEFAULT NULL,
  `title` varchar(160) DEFAULT NULL,
  `chapter` varchar(160) DEFAULT NULL,
  `pages` varchar(16) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inbook`
--

INSERT INTO `inbook` (`ID_Inbook`, `ID_book`, `title`, `chapter`, `pages`, `type`, `note`) VALUES
(5, 1, 'PrimerTituloInBook', 'Primero', '1-20', 'Historia', 'La Nota es una accion que hace cuando...');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inproceedings`
--

CREATE TABLE `inproceedings` (
  `ID_InProceedings` int(11) NOT NULL,
  `Proceedings_ID` int(11) DEFAULT NULL,
  `title` varchar(160) DEFAULT NULL,
  `pages` varchar(32) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inproceedings`
--

INSERT INTO `inproceedings` (`ID_InProceedings`, `Proceedings_ID`, `title`, `pages`, `type`, `note`) VALUES
(7, 7, 'Primer titulo inprodeedings', '1-20', 'Inproceedings', 'nota');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institution`
--

CREATE TABLE `institution` (
  `ID_Institution` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `street` varchar(32) NOT NULL,
  `city` varchar(32) NOT NULL,
  `province` varchar(32) NOT NULL,
  `postal_code` char(10) NOT NULL,
  `country` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `institution`
--

INSERT INTO `institution` (`ID_Institution`, `name`, `street`, `city`, `province`, `postal_code`, `country`) VALUES
(1, 'Mariano Damaso Beraun', 'Jr.Kotosh', 'Huanuco', 'Huanuco', '10003', 'Peru'),
(2, 'Senati', 'Cahuide', 'Huanuco', 'Huanuco', '10003', 'Chile');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `manual`
--

CREATE TABLE `manual` (
  `ID_Manual` int(11) NOT NULL,
  `title` varchar(160) DEFAULT NULL,
  `organization` int(11) DEFAULT NULL,
  `edition` varchar(16) DEFAULT NULL,
  `pub_month` char(3) DEFAULT NULL,
  `pub_year` int(11) DEFAULT NULL,
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `manual`
--

INSERT INTO `manual` (`ID_Manual`, `title`, `organization`, `edition`, `pub_month`, `pub_year`, `note`) VALUES
(1, 'Manual', 1, 'Edicion Primera', 'Ene', 2023, 'Primera nota');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `misc`
--

CREATE TABLE `misc` (
  `ID_Misc` int(11) NOT NULL,
  `title` varchar(160) DEFAULT NULL,
  `address` varchar(160) DEFAULT NULL,
  `howpublished` varchar(160) DEFAULT NULL,
  `pub_month` char(3) DEFAULT NULL,
  `pub_year` int(11) DEFAULT NULL,
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `misc`
--

INSERT INTO `misc` (`ID_Misc`, `title`, `address`, `howpublished`, `pub_month`, `pub_year`, `note`) VALUES
(2, 'Titulo', 'Segunda ', 'Mediante una editorial', 'Dic', 2023, 'Nota');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE `person` (
  `ID_Person` int(11) NOT NULL,
  `sumame` char(32) NOT NULL,
  `given_names` char(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `person`
--

INSERT INTO `person` (`ID_Person`, `sumame`, `given_names`) VALUES
(1, 'Sumame1', 'EjemploGivenNames12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proceedings`
--

CREATE TABLE `proceedings` (
  `ID_Proceedings` int(11) NOT NULL,
  `title` varchar(160) DEFAULT NULL,
  `publisher` int(11) DEFAULT NULL,
  `volume` varchar(16) DEFAULT NULL,
  `series` varchar(160) DEFAULT NULL,
  `organization` int(11) DEFAULT NULL,
  `pub_month` char(3) DEFAULT NULL,
  `pub_year` int(11) DEFAULT NULL,
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proceedings`
--

INSERT INTO `proceedings` (`ID_Proceedings`, `title`, `publisher`, `volume`, `series`, `organization`, `pub_month`, `pub_year`, `note`) VALUES
(7, 'Primer titulo', 2, 'VOlumen1', 'Segunda Serie', 1, 'Dic', 2000, 'Nota');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publication`
--

CREATE TABLE `publication` (
  `ID_Publication` int(11) NOT NULL,
  `type` char(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publication`
--

INSERT INTO `publication` (`ID_Publication`, `type`) VALUES
(1, 'Articulo'),
(2, 'Articulo1'),
(4, 'Articulo2'),
(5, 'Articulo10'),
(7, 'Articulo123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publisher`
--

CREATE TABLE `publisher` (
  `ID_Publisher` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `street` varchar(32) NOT NULL,
  `city` varchar(32) NOT NULL,
  `province` varchar(32) NOT NULL,
  `postal_code` char(10) NOT NULL,
  `country` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publisher`
--

INSERT INTO `publisher` (`ID_Publisher`, `name`, `street`, `city`, `province`, `postal_code`, `country`) VALUES
(1, 'Primera', 'Jr.Kotosh', 'Huanuco', 'Huanuco', '10003', 'Peru'),
(2, 'Segunda', 'Cauide', 'Huanuco', 'Leoncio Prado', '10003', 'Chile');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `techreport`
--

CREATE TABLE `techreport` (
  `ID_Techreport` int(11) NOT NULL,
  `title` varchar(160) DEFAULT NULL,
  `ID_Institution` int(11) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `number` varchar(16) DEFAULT NULL,
  `pub_month` char(3) DEFAULT NULL,
  `pub_year` int(11) DEFAULT NULL,
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `techreport`
--

INSERT INTO `techreport` (`ID_Techreport`, `title`, `ID_Institution`, `type`, `number`, `pub_month`, `pub_year`, `note`) VALUES
(7, 'Segundo titulo', 1, 'Informe', '123', 'Ene', 2022, 'La nota');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `thesis`
--

CREATE TABLE `thesis` (
  `ID_Thesis` int(11) NOT NULL,
  `title` varchar(160) DEFAULT NULL,
  `school` int(11) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `pub_month` char(3) DEFAULT NULL,
  `pub_year` int(11) DEFAULT NULL,
  `note` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `thesis`
--

INSERT INTO `thesis` (`ID_Thesis`, `title`, `school`, `type`, `pub_month`, `pub_year`, `note`) VALUES
(7, 'Esta es mi Tesis', 2, 'Investigacion', 'Dic', 2023, 'nota');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `correo`, `fecha_nacimiento`, `telefono`, `username`, `contrasena`) VALUES
(1, 'Leonardo', 'Vela Loyola', '77219482vela@gmail.com', '2002-12-12', '970411498', 'admin', '$2y$10$XMN3fFI5tC75KZeJLdPAq.UdMEVfRThfoVCgrpsqA2Y/0PIHlqcFe'),
(2, 'Leonardo', 'Vela Loyola', '77219482vela@gmail.com', '2002-12-12', '970411498', 'admin', '$2y$10$CwP7/Asps.AAxJ07ngw48uHN/4T5psMhRhbYDjhk7mevO1UAN7WY.');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`ID_Article`);

--
-- Indices de la tabla `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`ID_Publication`,`ID_Person`),
  ADD KEY `ID_Person` (`ID_Person`);

--
-- Indices de la tabla `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`ID_Book`),
  ADD KEY `ID_Publisher` (`ID_Publisher`);

--
-- Indices de la tabla `editor`
--
ALTER TABLE `editor`
  ADD PRIMARY KEY (`ID_Publication`,`ID_Person`),
  ADD KEY `ID_Person` (`ID_Person`);

--
-- Indices de la tabla `inbook`
--
ALTER TABLE `inbook`
  ADD PRIMARY KEY (`ID_Inbook`),
  ADD KEY `ID_book` (`ID_book`);

--
-- Indices de la tabla `inproceedings`
--
ALTER TABLE `inproceedings`
  ADD PRIMARY KEY (`ID_InProceedings`),
  ADD KEY `Proceedings_ID` (`Proceedings_ID`);

--
-- Indices de la tabla `institution`
--
ALTER TABLE `institution`
  ADD PRIMARY KEY (`ID_Institution`);

--
-- Indices de la tabla `manual`
--
ALTER TABLE `manual`
  ADD PRIMARY KEY (`ID_Manual`),
  ADD KEY `organization` (`organization`);

--
-- Indices de la tabla `misc`
--
ALTER TABLE `misc`
  ADD PRIMARY KEY (`ID_Misc`);

--
-- Indices de la tabla `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`ID_Person`);

--
-- Indices de la tabla `proceedings`
--
ALTER TABLE `proceedings`
  ADD PRIMARY KEY (`ID_Proceedings`),
  ADD KEY `publisher` (`publisher`),
  ADD KEY `organization` (`organization`);

--
-- Indices de la tabla `publication`
--
ALTER TABLE `publication`
  ADD PRIMARY KEY (`ID_Publication`);

--
-- Indices de la tabla `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`ID_Publisher`);

--
-- Indices de la tabla `techreport`
--
ALTER TABLE `techreport`
  ADD PRIMARY KEY (`ID_Techreport`),
  ADD KEY `ID_Institution` (`ID_Institution`);

--
-- Indices de la tabla `thesis`
--
ALTER TABLE `thesis`
  ADD PRIMARY KEY (`ID_Thesis`),
  ADD KEY `school` (`school`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `institution`
--
ALTER TABLE `institution`
  MODIFY `ID_Institution` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `ID_Person` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `publication`
--
ALTER TABLE `publication`
  MODIFY `ID_Publication` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `publisher`
--
ALTER TABLE `publisher`
  MODIFY `ID_Publisher` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`ID_Article`) REFERENCES `publication` (`ID_Publication`);

--
-- Filtros para la tabla `author`
--
ALTER TABLE `author`
  ADD CONSTRAINT `author_ibfk_1` FOREIGN KEY (`ID_Publication`) REFERENCES `publication` (`ID_Publication`),
  ADD CONSTRAINT `author_ibfk_2` FOREIGN KEY (`ID_Person`) REFERENCES `person` (`ID_Person`);

--
-- Filtros para la tabla `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`ID_Book`) REFERENCES `publication` (`ID_Publication`),
  ADD CONSTRAINT `book_ibfk_2` FOREIGN KEY (`ID_Publisher`) REFERENCES `publisher` (`ID_Publisher`);

--
-- Filtros para la tabla `editor`
--
ALTER TABLE `editor`
  ADD CONSTRAINT `editor_ibfk_1` FOREIGN KEY (`ID_Publication`) REFERENCES `publication` (`ID_Publication`),
  ADD CONSTRAINT `editor_ibfk_2` FOREIGN KEY (`ID_Person`) REFERENCES `person` (`ID_Person`);

--
-- Filtros para la tabla `inbook`
--
ALTER TABLE `inbook`
  ADD CONSTRAINT `inbook_ibfk_1` FOREIGN KEY (`ID_Inbook`) REFERENCES `publication` (`ID_Publication`),
  ADD CONSTRAINT `inbook_ibfk_2` FOREIGN KEY (`ID_book`) REFERENCES `book` (`ID_Book`);

--
-- Filtros para la tabla `inproceedings`
--
ALTER TABLE `inproceedings`
  ADD CONSTRAINT `inproceedings_ibfk_1` FOREIGN KEY (`ID_InProceedings`) REFERENCES `publication` (`ID_Publication`),
  ADD CONSTRAINT `inproceedings_ibfk_2` FOREIGN KEY (`Proceedings_ID`) REFERENCES `proceedings` (`ID_Proceedings`);

--
-- Filtros para la tabla `manual`
--
ALTER TABLE `manual`
  ADD CONSTRAINT `manual_ibfk_1` FOREIGN KEY (`ID_Manual`) REFERENCES `publication` (`ID_Publication`),
  ADD CONSTRAINT `manual_ibfk_2` FOREIGN KEY (`organization`) REFERENCES `institution` (`ID_Institution`);

--
-- Filtros para la tabla `misc`
--
ALTER TABLE `misc`
  ADD CONSTRAINT `misc_ibfk_1` FOREIGN KEY (`ID_Misc`) REFERENCES `publication` (`ID_Publication`);

--
-- Filtros para la tabla `proceedings`
--
ALTER TABLE `proceedings`
  ADD CONSTRAINT `proceedings_ibfk_1` FOREIGN KEY (`ID_Proceedings`) REFERENCES `publication` (`ID_Publication`),
  ADD CONSTRAINT `proceedings_ibfk_2` FOREIGN KEY (`publisher`) REFERENCES `publisher` (`ID_Publisher`),
  ADD CONSTRAINT `proceedings_ibfk_3` FOREIGN KEY (`organization`) REFERENCES `institution` (`ID_Institution`);

--
-- Filtros para la tabla `techreport`
--
ALTER TABLE `techreport`
  ADD CONSTRAINT `techreport_ibfk_1` FOREIGN KEY (`ID_Techreport`) REFERENCES `publication` (`ID_Publication`),
  ADD CONSTRAINT `techreport_ibfk_2` FOREIGN KEY (`ID_Institution`) REFERENCES `institution` (`ID_Institution`);

--
-- Filtros para la tabla `thesis`
--
ALTER TABLE `thesis`
  ADD CONSTRAINT `thesis_ibfk_1` FOREIGN KEY (`ID_Thesis`) REFERENCES `publication` (`ID_Publication`),
  ADD CONSTRAINT `thesis_ibfk_2` FOREIGN KEY (`school`) REFERENCES `institution` (`ID_Institution`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
