-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 03 Juin 2026 à 15:56
-- Version du serveur :  5.6.20
-- Version de PHP :  5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `sae23`
--

-- --------------------------------------------------------

--
-- Structure de la table `Administration`
--

CREATE TABLE IF NOT EXISTS `Administration` (
  `login` varchar(30) NOT NULL,
  `mdp` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Administration`
--

INSERT INTO `Administration` (`login`, `mdp`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `batiment`
--

CREATE TABLE IF NOT EXISTS `batiment` (
  `ID_bat` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `capteur`
--

CREATE TABLE IF NOT EXISTS `capteur` (
  `nom_capteur` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `unité` varchar(255) NOT NULL,
  `nom_salle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mesure`
--

CREATE TABLE IF NOT EXISTS `mesure` (
  `ID_mesure` int(255) NOT NULL,
  `date` date NOT NULL,
  `horaire` time NOT NULL,
  `valeur` float NOT NULL,
  `nom_capteur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE IF NOT EXISTS `salle` (
  `nom_salle` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `capacité` int(255) NOT NULL,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `batiment`
--
ALTER TABLE `batiment`
 ADD PRIMARY KEY (`ID_bat`), ADD UNIQUE KEY `ID` (`ID_bat`);

--
-- Index pour la table `capteur`
--
ALTER TABLE `capteur`
 ADD PRIMARY KEY (`nom_capteur`), ADD KEY `nom_salle` (`nom_salle`);

--
-- Index pour la table `mesure`
--
ALTER TABLE `mesure`
 ADD PRIMARY KEY (`ID_mesure`), ADD KEY `nom_capteur` (`nom_capteur`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
 ADD PRIMARY KEY (`nom_salle`), ADD KEY `ID` (`ID`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `batiment`
--
ALTER TABLE `batiment`
ADD CONSTRAINT `batiment_ibfk_1` FOREIGN KEY (`ID_bat`) REFERENCES `salle` (`ID`);

--
-- Contraintes pour la table `capteur`
--
ALTER TABLE `capteur`
ADD CONSTRAINT `capteur_ibfk_1` FOREIGN KEY (`nom_capteur`) REFERENCES `mesure` (`nom_capteur`);

--
-- Contraintes pour la table `salle`
--
ALTER TABLE `salle`
ADD CONSTRAINT `salle_ibfk_1` FOREIGN KEY (`nom_salle`) REFERENCES `capteur` (`nom_salle`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
