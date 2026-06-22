-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host:  localhost
-- Generated on:  Wed Jun 03 2026 at 15:56
-- Server version:  5.6.20
-- PHP Version:  5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database:  `sae23`
--

-- --------------------------------------------------------

--
-- Table structure for table `Administration`
--

CREATE TABLE IF NOT EXISTS `Administration` (
  `login` varchar(30) NOT NULL,
  `mdp` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Administration`
--

INSERT INTO `Administration` (`login`, `mdp`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `batiment`
--

CREATE TABLE IF NOT EXISTS `batiment` (
  `ID_bat` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `capteur`
--

CREATE TABLE IF NOT EXISTS `capteur` (
  `nom_capteur` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `unité` varchar(255) NOT NULL,
  `nom_salle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mesure`
--

CREATE TABLE IF NOT EXISTS `mesure` (
  `ID_mesure` int(255) NOT NULL,
  `date` date NOT NULL,
  `horaire` time NOT NULL,
  `valeur` float NOT NULL,
  `nom_capteur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert Default Data
INSERT INTO `Administration` (`login`, `mdp`) VALUES ('admin', 'admin');

INSERT INTO `batiment` (`ID_bat`, `nom`, `login`, `mdp`) VALUES 
(1, 'Bâtiment E', 'gest_e', 'password'),
(2, 'Bâtiment C', 'gest_c', 'password');

INSERT INTO `salle` (`nom_salle`, `type`, `capacité`, `ID`) VALUES 
('E208', 'Salle TP', 30, 1),
('E210', 'Salle Cours', 40, 1);

INSERT INTO `capteur` (`nom_capteur`, `type`, `unité`, `nom_salle`) VALUES 
('T_E208', 'temperature', '°C', 'E208'),
('H_E208', 'humidite', '%', 'E208'),
('T_E210', 'temperature', '°C', 'E210'),
('H_E210', 'humidite', '%', 'E210');

-- --------------------------------------------------------

--
-- Table structure for table `salle`
--

CREATE TABLE IF NOT EXISTS `salle` (
  `nom_salle` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `capacité` int(255) NOT NULL,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batiment`
--
ALTER TABLE `batiment`
 ADD PRIMARY KEY (`ID_bat`), ADD UNIQUE KEY `ID` (`ID_bat`);

--
-- Indexes for table `capteur`
--
ALTER TABLE `capteur`
 ADD PRIMARY KEY (`nom_capteur`), ADD KEY `nom_salle` (`nom_salle`);

--
-- Indexes for table `mesure`
--
ALTER TABLE `mesure`
 ADD PRIMARY KEY (`ID_mesure`), ADD KEY `nom_capteur` (`nom_capteur`);

--
-- AUTO_INCREMENT for table `mesure`
--
ALTER TABLE `mesure`
 MODIFY `ID_mesure` int(255) NOT NULL AUTO_INCREMENT;

--
-- Indexes for table `salle`
--
ALTER TABLE `salle`
 ADD PRIMARY KEY (`nom_salle`), ADD KEY `ID` (`ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batiment`
--
-- No foreign key needed here, room references building.

--
-- Constraints for table `capteur`
--
ALTER TABLE `capteur`
ADD CONSTRAINT `capteur_ibfk_1` FOREIGN KEY (`nom_salle`) REFERENCES `salle` (`nom_salle`) ON DELETE CASCADE;

--
-- Constraints for table `mesure`
--
ALTER TABLE `mesure`
ADD CONSTRAINT `mesure_ibfk_1` FOREIGN KEY (`nom_capteur`) REFERENCES `capteur` (`nom_capteur`) ON DELETE CASCADE;

--
-- Constraints for table `salle`
--
ALTER TABLE `salle`
ADD CONSTRAINT `salle_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `batiment` (`ID_bat`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
