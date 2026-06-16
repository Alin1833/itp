-- ============================================================
-- Copie a bazei de date `itp` (varianta ITP - Copy) sub numele `itp_db`
-- Generat din dump-ul phpMyAdmin al bazei `itp` (cu tabela imagini)
-- Import: mysql -u root < itp_db.sql   SAU phpMyAdmin > Import
-- ============================================================

CREATE DATABASE IF NOT EXISTS `itp_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `itp_db`;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 12:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itp`
--

-- --------------------------------------------------------

--
-- Table structure for table `imagini`
--

CREATE TABLE `imagini` (
  `caleImagine` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspectii`
--

CREATE TABLE `inspectii` (
  `idInspectie` int(11) NOT NULL,
  `denumireInspectie` varchar(100) NOT NULL,
  `idPret` int(11) DEFAULT NULL,
  `durata` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listapreturi`
--

CREATE TABLE `listapreturi` (
  `idPret` int(11) NOT NULL,
  `specificatii` varchar(100) NOT NULL,
  `pret` double(10,2) NOT NULL,
  `dataStabilirePret` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `masina`
--

CREATE TABLE `masina` (
  `idMasina` int(11) NOT NULL,
  `serieSasiu` varchar(13) DEFAULT NULL,
  `masaMasina` int(11) DEFAULT NULL,
  `marcaMasina` varchar(100) DEFAULT NULL,
  `categorie` varchar(2) DEFAULT NULL,
  `RCA` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programare`
--

CREATE TABLE `programare` (
  `idInspectie` int(11) NOT NULL,
  `idProprietar` int(11) NOT NULL,
  `idMasina` int(11) NOT NULL,
  `dataProgramare` date NOT NULL,
  `ora` time(6) DEFAULT NULL,
  `ultimulITP` date DEFAULT NULL,
  `nrInmatriculare` varchar(7) DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proprietar`
--

CREATE TABLE `proprietar` (
  `idProprietar` int(11) NOT NULL,
  `nume` varchar(100) NOT NULL,
  `prenume` varchar(100) NOT NULL,
  `telefon` varchar(10) NOT NULL,
  `idUser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recenzie`
--

CREATE TABLE `recenzie` (
  `idRecenzie` int(11) NOT NULL,
  `comentariu` varchar(255) NOT NULL,
  `dataRecenzie` date NOT NULL,
  `rating` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `rol` varchar(6) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `parola` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`idUser`, `rol`, `email`, `parola`) VALUES
(1, 'admin', 'alin2004andres@gmail.com', '$2y$10$mOR7Oc24Miss.Rj8RiO8HOs9UNwu1Gvhode9N8KaYuxmaE1rnlZCa'),
(2, 'client', 'vasile.popa@example.com', 'popa2024'),
(3, 'client', 'hodoflorin@gmail.com', '$2y$10$fDQbgJcKuu1aaX9Z28zLmO2bEagAKUnCEwiWR7KgPvuiCdvmImFE2');

-- --------------------------------------------------------

--
-- Table structure for table `zilelibere`
--

CREATE TABLE `zilelibere` (
  `zi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zilelibere`
--

INSERT INTO `zilelibere` (`zi`) VALUES
('2025-05-17'),
('2025-05-23'),
('2025-05-24'),
('2025-05-30'),
('2025-05-31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inspectii`
--
ALTER TABLE `inspectii`
  ADD PRIMARY KEY (`idInspectie`) USING BTREE,
  ADD KEY `IdPret` (`idPret`) USING BTREE;

--
-- Indexes for table `listapreturi`
--
ALTER TABLE `listapreturi`
  ADD PRIMARY KEY (`idPret`) USING BTREE;

--
-- Indexes for table `masina`
--
ALTER TABLE `masina`
  ADD PRIMARY KEY (`idMasina`);

--
-- Indexes for table `programare`
--
ALTER TABLE `programare`
  ADD PRIMARY KEY (`idInspectie`,`idProprietar`,`idMasina`,`dataProgramare`) USING BTREE,
  ADD KEY `idProprietar` (`idProprietar`),
  ADD KEY `idMasina` (`idMasina`);

--
-- Indexes for table `proprietar`
--
ALTER TABLE `proprietar`
  ADD PRIMARY KEY (`idProprietar`),
  ADD KEY `IdUser` (`idUser`) USING BTREE;

--
-- Indexes for table `recenzie`
--
ALTER TABLE `recenzie`
  ADD PRIMARY KEY (`idRecenzie`) USING BTREE,
  ADD KEY `IdUser` (`idUser`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`) USING BTREE;

--
-- Indexes for table `zilelibere`
--
ALTER TABLE `zilelibere`
  ADD PRIMARY KEY (`zi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inspectii`
--
ALTER TABLE `inspectii`
  MODIFY `idInspectie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `listapreturi`
--
ALTER TABLE `listapreturi`
  MODIFY `idPret` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `masina`
--
ALTER TABLE `masina`
  MODIFY `idMasina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proprietar`
--
ALTER TABLE `proprietar`
  MODIFY `idProprietar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recenzie`
--
ALTER TABLE `recenzie`
  MODIFY `idRecenzie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inspectii`
--
ALTER TABLE `inspectii`
  ADD CONSTRAINT `inspectii_ibfk_1` FOREIGN KEY (`IdPret`) REFERENCES `listapreturi` (`IdPret`);

--
-- Constraints for table `programare`
--
ALTER TABLE `programare`
  ADD CONSTRAINT `programare_ibfk_1` FOREIGN KEY (`IdInspectie`) REFERENCES `inspectii` (`IdInspectie`),
  ADD CONSTRAINT `programare_ibfk_2` FOREIGN KEY (`idProprietar`) REFERENCES `proprietar` (`idProprietar`),
  ADD CONSTRAINT `programare_ibfk_3` FOREIGN KEY (`idMasina`) REFERENCES `masina` (`idMasina`);

--
-- Constraints for table `proprietar`
--
ALTER TABLE `proprietar`
  ADD CONSTRAINT `proprietar_ibfk_1` FOREIGN KEY (`IdUser`) REFERENCES `user` (`IdUser`);

--
-- Constraints for table `recenzie`
--
ALTER TABLE `recenzie`
  ADD CONSTRAINT `recenzie_ibfk_1` FOREIGN KEY (`IdUser`) REFERENCES `user` (`IdUser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
