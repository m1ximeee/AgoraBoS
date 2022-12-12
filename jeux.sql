-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 03 oct. 2018 à 17:24
-- Version du serveur :  5.7.21
-- Version de PHP :  7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `agora`
--
CREATE DATABASE IF NOT EXISTS `agora` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agora`;

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

DROP TABLE IF EXISTS `genre`;
CREATE TABLE IF NOT EXISTS `genre` (
  `idGenre` int(11) NOT NULL AUTO_INCREMENT,
  `libGenre` varchar(24) NOT NULL,
  PRIMARY KEY (`idGenre`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `genre`
--

INSERT INTO `genre` (`idGenre`, `libGenre`) VALUES
(1, 'Action'),
(2, 'Aventure'),
(3, 'Combat'),
(4, 'Course'),
(5, 'Gestion'),
(6, 'Jeu de rôle'),
(7, 'Ligue fantasy'),
(8, 'Réflexion'),
(9, 'Simulation'),
(10, 'Sport'),
(11, 'Simulation'),
(12, 'Sport'),
(13, 'Stratégie'),
(14, 'Porte-monstre-trésor');

-- --------------------------------------------------------

--
-- Structure de la table `jeu_video`
--

DROP TABLE IF EXISTS `jeu_video`;
CREATE TABLE IF NOT EXISTS `jeu_video` (
  `refJeu` varchar(16) NOT NULL,
  `idPlateforme` int(11) DEFAULT NULL,
  `idPegi` int(11) DEFAULT NULL,
  `idGenre` int(11) DEFAULT NULL,
  `idMarque` int(11) DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `prix` decimal(6,2) NOT NULL,
  `dateParution` date NOT NULL DEFAULT '2018-03-16',
  PRIMARY KEY (`refJeu`),
  KEY `fk_jeu_video_genre` (`idGenre`) USING BTREE,
  KEY `fk_jeu_video_pegi` (`idPegi`) USING BTREE,
  KEY `fk_jeu_video_marque` (`idMarque`) USING BTREE,
  KEY `fk_jeu_video_plateforme` (`idPlateforme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `jeu_video`
--

INSERT INTO `jeu_video` (`refJeu`, `idPlateforme`, `idPegi`, `idGenre`, `idMarque`, `nom`, `prix`, `dateParution`) VALUES
('BF8763098765', 2, 3, 10, 2, 'FIFA 18 - Edition essentielles', '59.99', '2017-09-29'),
('CF47563837YG', 3, 1, 13, 8, 'Paddington : escapades à Londres', '18.30', '2015-06-19'),
('EG763547598RF', 3, 2, 6, 13, 'Pokémon X', '39.90', '2013-10-12'),
('ER493746Y78', 8, 5, 1, 3, 'Rise of the Tomb Raider', '19.90', '2015-11-13'),
('ER6753FG987', 3, 3, 2, 1, 'Minecraft Story Mode - L\'aventure Complète -', '39.89', '2016-12-16'),
('ES47562098754', 4, 2, 2, 13, 'The Legend of Zelda - The Wind Waker HD ', '29.80', '2016-04-15'),
('ET86987453T5', 7, 5, 1, 10, 'La terre de milieu : L\'Ombre de la Guerre', '59.90', '2017-10-10'),
('RT4958673II2', 4, 2, 2, 13, 'New Super Mario Bros.', '18.90', '2016-04-15'),
('TF98653JU8', 15, 3, 2, 1, 'Minecraft Story Mode - L\'aventure Complète -', '39.89', '2016-12-16'),
('U174645475GT', 2, 3, 10, 1, 'Gran Turismo 6', '21.50', '2013-06-12'),
('YT65487BJI', 3, 1, 2, 13, 'Mario Kart 7 ', '39.90', '2012-11-28');

-- --------------------------------------------------------

--
-- Structure de la table `marque`
--

DROP TABLE IF EXISTS `marque`;
CREATE TABLE IF NOT EXISTS `marque` (
  `idMarque` int(11) NOT NULL AUTO_INCREMENT,
  `nomMarque` varchar(40) NOT NULL,
  PRIMARY KEY (`idMarque`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='Les marques des produits';

--
-- Déchargement des données de la table `marque`
--

INSERT INTO `marque` (`idMarque`, `nomMarque`) VALUES
(1, 'Sony'),
(2, 'Electronic arts'),
(3, 'Square Enix'),
(4, 'Konami'),
(5, 'Bandai Namco Entertainment'),
(6, 'Rockstar Games'),
(7, 'Séga'),
(8, 'Techland'),
(9, 'Ubisoft'),
(10, 'Warner Bros'),
(11, 'Bensimon'),
(12, 'Hori'),
(13, 'Nintendo'),
(15, 'Kid\'s Mania');

-- --------------------------------------------------------

--
-- Structure de la table `pegi`
--

DROP TABLE IF EXISTS `pegi`;
CREATE TABLE IF NOT EXISTS `pegi` (
  `idPegi` int(11) NOT NULL AUTO_INCREMENT,
  `ageLimite` int(11) NOT NULL,
  `descPegi` varchar(400) NOT NULL,
  PRIMARY KEY (`idPegi`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pegi`
--

INSERT INTO `pegi` (`idPegi`, `ageLimite`, `descPegi`) VALUES
(1, 3, '« adapté à toutes les classes d’âge ». En effet, il ne comporte pas de sons ou d’images susceptibles d’effrayer ou de faire peur à de jeunes enfants. Les formes de violence très modérées dans un contexte comique ou enfantin sont toutefois acceptées, mais le langage grossier n\'est pas autoris.'),
(2, 7, 'Déconseillé aux moins de 7 ans. Il contient des scènes ou sons potentiellement effrayants. La violence très modérée (c\'est-à-dire implicite, non détaillée ou non réaliste) est accepté.'),
(3, 12, 'Déconseillé aux moins de 12 ans. Il peut montrer « de la violence sous une forme plus graphique par rapport à des personnages imaginaires et/ou une violence non graphique envers des personnages à figure humaine ». Il peut également présenter des insinuations à caractère sexuel ou des postures de type sexuelles dans un cadre léger. Enfin, il peut aussi proposer des agora de hasard.'),
(4, 16, 'Déconseillé aux moins de 16 ans. Contenus possibles : la violence et/ou la sexualité sont représentés de manière semblable à ce que l\'on pourrait retrouver dans la réalité. Le jeu peut ainsi contenir de la violence explicite, un mauvais langage, des références ou contenus à caractères sexuels, mais aussi des agora de hasard ou l\'utilisation d\'alcool, tabac et drogue (forme d\'incitation).'),
(5, 18, '« destinée aux adultes ». Il peut contenir un degré de violence extrême avec une représentation de violence crue, de meurtre sans motivation, de violence contre des personnages sans défense ou de la discrimination. Il peut aussi glorifier la prise des drogues illégales et les contacts sexuels explicites ainsi que des agora de hasard.');

-- --------------------------------------------------------

--
-- Structure de la table `plateforme`
--

DROP TABLE IF EXISTS `plateforme`;
CREATE TABLE IF NOT EXISTS `plateforme` (
  `idPlateforme` int(11) NOT NULL AUTO_INCREMENT,
  `libPlateforme` varchar(24) NOT NULL,
  PRIMARY KEY (`idPlateforme`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `plateforme`
--

INSERT INTO `plateforme` (`idPlateforme`, `libPlateforme`) VALUES
(1, 'PlayStation 4'),
(2, 'PlayStation 3'),
(3, 'Nintendo 3DS'),
(4, 'Nintendo Wii'),
(5, 'PC'),
(6, 'Sony PSP'),
(7, 'Xbox 360'),
(8, 'Xbox One'),
(9, 'Nintendo 2DS'),
(11, 'Nintendo DS'),
(13, 'Nintendo Switch'),
(15, 'Nintendo Wii U'),
(17, 'PlayStation Vita');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `jeu_video`
--
ALTER TABLE `jeu_video`
  ADD CONSTRAINT `fk_jeu_video_genre` FOREIGN KEY (`idGenre`) REFERENCES `genre` (`idGenre`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_jeu_video_marque` FOREIGN KEY (`idMarque`) REFERENCES `marque` (`idMarque`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_jeu_video_pegi` FOREIGN KEY (`idPegi`) REFERENCES `pegi` (`idPegi`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_jeu_video_plateforme` FOREIGN KEY (`idPlateforme`) REFERENCES `plateforme` (`idPlateforme`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
