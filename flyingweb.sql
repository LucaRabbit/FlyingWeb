-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 27 jan. 2026 à 21:18
-- Version du serveur : 8.0.44
-- Version de PHP : 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `flyingweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `IdAdmin` int NOT NULL,
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `MotDePasse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`IdAdmin`, `Email`, `MotDePasse`) VALUES
(1, 'admin@example.com', '$2y$10$t9EKJIzrnQTAJBM/OoBPhOGSCeC0q4xwpg8788j4deeD8U76iJfNi');

-- --------------------------------------------------------

--
-- Structure de la table `aeroport`
--

CREATE TABLE `aeroport` (
  `IdAeroport` int NOT NULL,
  `CodeIATA` char(3) NOT NULL,
  `NomOfficiel` varchar(150) NOT NULL,
  `Ville` varchar(100) NOT NULL,
  `Pays` varchar(100) NOT NULL,
  `LongueurAvionMax` int NOT NULL,
  `NomAeroport` varchar(200) GENERATED ALWAYS AS (concat(`Ville`,_utf8mb4' – ',`NomOfficiel`,_utf8mb4' (',`CodeIATA`,_utf8mb4')')) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `aeroport`
--

INSERT INTO `aeroport` (`IdAeroport`, `CodeIATA`, `NomOfficiel`, `Ville`, `Pays`, `LongueurAvionMax`) VALUES
(1, 'CDG', 'Charles-de-Gaulle', 'Paris', 'France', 4200),
(2, 'LYS', 'Saint-Exupéry', 'Lyon', 'France', 4000),
(3, 'NCE', 'Côte d\'Azur', 'Nice', 'France', 3500),
(4, 'AMS', 'Schiphol', 'Amsterdam', 'Pays-Bas', 3800),
(5, 'MAD', 'Barajas', 'Madrid', 'Espagne', 4100),
(6, 'LHR', 'Heathrow', 'Londres', 'Royaume-Uni', 3900);

-- --------------------------------------------------------

--
-- Structure de la table `avion`
--

CREATE TABLE `avion` (
  `IdAvion` int NOT NULL,
  `Immatriculation` varchar(10) DEFAULT NULL,
  `Modele` varchar(50) NOT NULL,
  `NbPlacesPassager` int NOT NULL,
  `LongueurAvion` decimal(5,2) NOT NULL,
  `StatutAvion` enum('AuSol','EnVol','Maintenance','HorsService') NOT NULL DEFAULT 'AuSol',
  `IdAeroportActuel` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `avion`
--

INSERT INTO `avion` (`IdAvion`, `Immatriculation`, `Modele`, `NbPlacesPassager`, `LongueurAvion`, `StatutAvion`, `IdAeroportActuel`) VALUES
(1, 'F-A100', 'Airbus A320', 4, 37.57, 'AuSol', 1),
(2, 'F-A200', 'Airbus A320', 4, 37.57, 'EnVol', NULL),
(3, 'F-A300', 'Airbus A319', 4, 33.84, 'AuSol', 4),
(4, 'F-A400', 'Embraer 190', 4, 36.24, 'AuSol', 4),
(5, 'F-A500', 'Airbus A321', 4, 44.51, 'Maintenance', 1),
(6, 'F-A600', 'Boeing 737', 4, 39.50, 'HorsService', 5);

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE `logs` (
  `IdLog` int NOT NULL,
  `TableName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Action` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `RecordId` int NOT NULL,
  `OldData` json DEFAULT NULL,
  `NewData` json DEFAULT NULL,
  `PerformedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `passager`
--

CREATE TABLE `passager` (
  `IdPassager` int NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `Prenom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `passager`
--

INSERT INTO `passager` (`IdPassager`, `Nom`, `Prenom`) VALUES
(1, 'Dupont', 'Alice'),
(2, 'Martin', 'Bob'),
(3, 'Durand', 'Claire'),
(4, 'Petit', 'David'),
(5, 'Bernard', 'Emma'),
(6, 'Moreau', 'Felix'),
(7, 'Roux', 'Lea'),
(8, 'Garcia', 'Hugo'),
(9, 'Lambert', 'Ines'),
(10, 'Morel', 'Jules'),
(11, 'Fontaine', 'Karim'),
(12, 'Chevalier', 'Laura');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `IdReservation` int NOT NULL,
  `DateReservation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `NbPassagersReserve` int NOT NULL DEFAULT '1',
  `TokenLien` char(64) NOT NULL,
  `EmailReservant` varchar(255) NOT NULL,
  `StatutReservation` enum('EnAttente','Annulee','AnnuleeVol','Confirmee','Cloturee') NOT NULL DEFAULT 'EnAttente',
  `IdVolAller` int NOT NULL,
  `IdVolRetour` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`IdReservation`, `DateReservation`, `NbPassagersReserve`, `TokenLien`, `EmailReservant`, `StatutReservation`, `IdVolAller`, `IdVolRetour`) VALUES
(1, '2026-01-26 12:00:16', 2, 'a791366f6f6201254edcac9ec72017b12d8a69513b5e1e29a29a4048ffb16e27', 'alice@example.com', 'Confirmee', 1, 2),
(2, '2026-01-26 12:00:16', 2, 'd653b016db1da8f5b36f84a44465e5d6adffeb38ae46cc701e3462343727717b', 'bob@example.com', 'Confirmee', 3, NULL),
(3, '2026-01-26 12:00:16', 1, '021bbd75cf6530bd40f4ab8b131600e0188f4a8c51c9ebe112daef408a498e56', 'claire@example.com', 'Cloturee', 6, NULL),
(4, '2026-01-26 12:00:16', 1, 'ec7e008b5f4d05c43a532426d1d7b7cdb8e4452e3e29f9e4a1ad8833078a6907', 'david@example.com', 'AnnuleeVol', 5, NULL),
(5, '2026-01-26 12:00:16', 2, 'd9a37366a195e2efef8c64a72b24d75e4e7eb395cc56f1bbcdf3bf03e48cd909', 'emma@example.com', 'AnnuleeVol', 4, 5),
(6, '2026-01-26 12:00:16', 3, '550a92e330a86fa0a314c5bdde5a76c4168d753b45144d2780cbf2c26aa033dc', 'felix@example.com', 'EnAttente', 7, 8),
(7, '2026-01-26 12:00:16', 1, 'e74859e65cd1ffc903fb6132168effe3230d0a6d5f39fc0eeeb44526371ec7b6', 'lea@example.com', 'Annulee', 1, NULL),
(8, '2026-01-26 12:00:16', 1, 'ec185795d711d3cd6b5663d3a54651c720a8f3111b87a74957c02986c8b8cc3a', 'hugo@example.com', 'EnAttente', 2, NULL),
(9, '2026-01-26 12:00:16', 2, 'fed5e5f18daedb3b01e265930e7b78096e8975cd6e280f0b4892eaaae57f6c52', 'ines@example.com', 'Confirmee', 3, NULL),
(10, '2026-01-26 12:00:16', 2, '38fea159d1d76d6ffbff32f5d5b40e72680947773f579982ff3e54515ee95aa2', 'jules@example.com', 'Cloturee', 6, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `reservation_passager`
--

CREATE TABLE `reservation_passager` (
  `IdReservationPassager` int NOT NULL,
  `NumeroSiege` int NOT NULL,
  `IdReservation` int NOT NULL,
  `IdPassager` int NOT NULL,
  `IdVol` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservation_passager`
--

INSERT INTO `reservation_passager` (`IdReservationPassager`, `NumeroSiege`, `IdReservation`, `IdPassager`, `IdVol`) VALUES
(1, 1, 1, 1, 1),
(2, 2, 1, 2, 1),
(3, 1, 1, 1, 2),
(4, 2, 1, 2, 2),
(5, 1, 2, 3, 3),
(6, 2, 2, 4, 3),
(7, 1, 3, 5, 6),
(8, 1, 4, 6, 5),
(9, 1, 5, 7, 4),
(10, 2, 5, 8, 4),
(11, 2, 5, 7, 5),
(12, 3, 5, 8, 5),
(13, 1, 6, 9, 7),
(14, 2, 6, 10, 7),
(15, 3, 6, 11, 7),
(16, 1, 6, 9, 8),
(17, 2, 6, 10, 8),
(18, 3, 6, 11, 8),
(19, 3, 7, 12, 1),
(20, 3, 8, 3, 2),
(21, 3, 9, 5, 3),
(22, 4, 9, 6, 3),
(23, 2, 10, 7, 6),
(24, 3, 10, 8, 6);

-- --------------------------------------------------------

--
-- Structure de la table `vol`
--

CREATE TABLE `vol` (
  `IdVol` int NOT NULL,
  `NumeroVol` varchar(20) NOT NULL,
  `DateHeureDepartUTC` datetime NOT NULL,
  `DateHeureArriveeUTC` datetime NOT NULL,
  `StatutVol` enum('Planifie','EnCours','Arrive','Annule') NOT NULL DEFAULT 'Planifie',
  `IdAvion` int NOT NULL,
  `IdAeroportDepart` int NOT NULL,
  `IdAeroportArrivee` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vol`
--

INSERT INTO `vol` (`IdVol`, `NumeroVol`, `DateHeureDepartUTC`, `DateHeureArriveeUTC`, `StatutVol`, `IdAvion`, `IdAeroportDepart`, `IdAeroportArrivee`) VALUES
(1, 'AF101', '2026-03-01 08:00:00', '2026-03-01 09:00:00', 'Planifie', 1, 1, 2),
(2, 'AF102', '2026-03-01 11:00:00', '2026-03-01 12:00:00', 'Planifie', 1, 2, 1),
(3, 'AF201', '2026-03-01 07:00:00', '2026-03-01 08:00:00', 'EnCours', 2, 2, 3),
(4, 'AF202', '2026-03-01 10:00:00', '2026-03-01 11:00:00', 'Planifie', 2, 3, 5),
(5, 'AF203', '2026-03-01 13:00:00', '2026-03-01 14:00:00', 'Annule', 2, 5, 2),
(6, 'AF301', '2026-03-01 09:00:00', '2026-03-01 10:00:00', 'Arrive', 3, 3, 4),
(7, 'AF302', '2026-03-01 12:00:00', '2026-03-01 13:00:00', 'Planifie', 3, 4, 3),
(8, 'AF401', '2026-03-01 15:00:00', '2026-03-01 16:00:00', 'Planifie', 4, 4, 5);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`IdAdmin`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Index pour la table `aeroport`
--
ALTER TABLE `aeroport`
  ADD PRIMARY KEY (`IdAeroport`);

--
-- Index pour la table `avion`
--
ALTER TABLE `avion`
  ADD PRIMARY KEY (`IdAvion`),
  ADD UNIQUE KEY `Immatriculation` (`Immatriculation`),
  ADD KEY `fk_avion_aeroport_actuel` (`IdAeroportActuel`);

--
-- Index pour la table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`IdLog`);

--
-- Index pour la table `passager`
--
ALTER TABLE `passager`
  ADD PRIMARY KEY (`IdPassager`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`IdReservation`),
  ADD KEY `fk_reservation_vol_aller` (`IdVolAller`),
  ADD KEY `fk_reservation_vol_retour` (`IdVolRetour`);

--
-- Index pour la table `reservation_passager`
--
ALTER TABLE `reservation_passager`
  ADD PRIMARY KEY (`IdReservationPassager`),
  ADD UNIQUE KEY `IdReservation` (`IdReservation`,`IdPassager`,`IdVol`),
  ADD UNIQUE KEY `IdVol` (`IdVol`,`NumeroSiege`),
  ADD KEY `fk_respass_passager` (`IdPassager`);

--
-- Index pour la table `vol`
--
ALTER TABLE `vol`
  ADD PRIMARY KEY (`IdVol`),
  ADD UNIQUE KEY `NumeroVol` (`NumeroVol`),
  ADD KEY `fk_vol_avion` (`IdAvion`),
  ADD KEY `fk_vol_aeroport_depart` (`IdAeroportDepart`),
  ADD KEY `fk_vol_aeroport_arrivee` (`IdAeroportArrivee`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `IdAdmin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `aeroport`
--
ALTER TABLE `aeroport`
  MODIFY `IdAeroport` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `avion`
--
ALTER TABLE `avion`
  MODIFY `IdAvion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `logs`
--
ALTER TABLE `logs`
  MODIFY `IdLog` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `passager`
--
ALTER TABLE `passager`
  MODIFY `IdPassager` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `IdReservation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `reservation_passager`
--
ALTER TABLE `reservation_passager`
  MODIFY `IdReservationPassager` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `vol`
--
ALTER TABLE `vol`
  MODIFY `IdVol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avion`
--
ALTER TABLE `avion`
  ADD CONSTRAINT `fk_avion_aeroport_actuel` FOREIGN KEY (`IdAeroportActuel`) REFERENCES `aeroport` (`IdAeroport`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_reservation_vol_aller` FOREIGN KEY (`IdVolAller`) REFERENCES `vol` (`IdVol`),
  ADD CONSTRAINT `fk_reservation_vol_retour` FOREIGN KEY (`IdVolRetour`) REFERENCES `vol` (`IdVol`);

--
-- Contraintes pour la table `reservation_passager`
--
ALTER TABLE `reservation_passager`
  ADD CONSTRAINT `fk_respass_passager` FOREIGN KEY (`IdPassager`) REFERENCES `passager` (`IdPassager`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respass_reservation` FOREIGN KEY (`IdReservation`) REFERENCES `reservation` (`IdReservation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_respass_vol` FOREIGN KEY (`IdVol`) REFERENCES `vol` (`IdVol`);

--
-- Contraintes pour la table `vol`
--
ALTER TABLE `vol`
  ADD CONSTRAINT `fk_vol_aeroport_arrivee` FOREIGN KEY (`IdAeroportArrivee`) REFERENCES `aeroport` (`IdAeroport`),
  ADD CONSTRAINT `fk_vol_aeroport_depart` FOREIGN KEY (`IdAeroportDepart`) REFERENCES `aeroport` (`IdAeroport`),
  ADD CONSTRAINT `fk_vol_avion` FOREIGN KEY (`IdAvion`) REFERENCES `avion` (`IdAvion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
