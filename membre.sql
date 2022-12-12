-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 03 mars 2021 à 13:46
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jeux`
--

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `idMembre` int(11) NOT NULL AUTO_INCREMENT,
  `nomMembre` varchar(32) NOT NULL,
  `prenomMembre` varchar(20) NOT NULL,
  `mailMembre` varchar(50) NOT NULL,
  `telMembre` varchar(10) NOT NULL,
  `rueMembre` varchar(36) NOT NULL,
  `cpMembre` varchar(5) NOT NULL,
  `villeMembre` varchar(30) NOT NULL,
  `loginMembre` varchar(20) NOT NULL,
  `mdpMembre` char(128) NOT NULL,
  `selMembre` char(128) NOT NULL,
  PRIMARY KEY (`idMembre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`idMembre`, `nomMembre`, `prenomMembre`, `mailMembre`, `telMembre`, `rueMembre`, `cpMembre`, `villeMembre`, `loginMembre`, `mdpMembre`, `selMembre`) VALUES
(1, 'Dubois', 'Didier', 'dubois.didier@gmail.com', '0685451236', '48 rue des acacias', '57000', 'Metz', 'dubois', '7e3c5f890206b7cad6a01f51ba98db1713f37219bdf7668d35c6f71e3bf8f0ee73d3cce3b380234fd39628d57c33ff5baf7e7a258fdfcf85efaf32a5be77a87a', '9943e30b1efe68df39ac3734fa014bdd51cfc2f8eb93df70a04cfa070f19a9c9f71fc588161227ed76ef0a79a1ea6003f2cefe511fde90a1eb210824bb7d4f4d'),
(2, 'Celon', 'Elodie', 'elodie35@gmail.com', '0689451235', '18 rue des Tilleuls', '57000', 'Metz', 'celon', 'f332c43c4aed2f69497df8f52ce3ed9483737eb689fd86614a267a034bc0c2fc4bc1677b96767f286499bba623b87a8993d2825af203df34573f9435222222d1', '8e78f28b94066853c4f13017fa561e233b3ff48dc28455c61acf3590f7897deb82514ba38a51fe57b89bd220ebbb9bcf72af287463ae9395aa7d2e52121b745c'),
(3, 'Garance', 'Kevin', 'garance@gmail.com', '0678451236', '5 avenue Victor hugo', '57000', 'Metz', 'garance', '2ac84572232ba491a89635b19afb37217dbd8c9e5d79080981d6fc283f13addfed0607a006090fcce0fa21dcf18f770e6138532732c0449e1b7a5d9fa478b6b7', 'f53d9e6fe21b24ee613f5cb5303b6b0dab1619906e42721be3c004bcbe7f08f95977a4da1753a4b439402693cb76d79e068459b24406939bbb6b24bf4d6bff88');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
