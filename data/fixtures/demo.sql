-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb5+lenny1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 10 Août 2009 à 18:48
-- Version du serveur: 5.0.51
-- Version de PHP: 5.2.6-1+lenny3

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

SET AUTOCOMMIT=0;
START TRANSACTION;

--
-- Base de données: `temposnuevo`
--

--
-- Contenu de la table `Activity`
--

INSERT INTO `Activity` (`id`, `name`, `color`, `minimum_occupation`, `maximum_occupation`, `minimum_delay`) VALUES
(1, 'Tennis', '#fcff38', 2, 4, 60),
(2, 'Boxe', '#ff3d3d', 2, 4, 60),
(3, 'Football', '#9ce977', 2, 22, 60),
(4, 'Golf', '#ffffff', 1, 10, 60),
(5, 'Réunion', '#e854b1', 1, 50, 60);

--
-- Contenu de la table `Activity_has_Feature`
--

INSERT INTO `Activity_has_Feature` (`Activity_id`, `Feature_id`) VALUES
(1, 1),
(5, 2);

--
-- Contenu de la table `Card`
--


--
-- Contenu de la table `CardUser`
--


--
-- Contenu de la table `ClosePeriod`
--


--
-- Contenu de la table `DayPeriod`
--

INSERT INTO `DayPeriod` (`id`, `start`, `stop`, `day_of_week`, `Room_id`) VALUES
(1, '08:00:00', '20:00:00', 0, 1),
(2, '08:00:00', '20:00:00', 1, 1),
(3, '08:00:00', '20:00:00', 2, 1),
(4, '08:00:00', '20:00:00', 3, 1),
(5, '08:00:00', '20:00:00', 4, 1),
(6, '08:00:00', '20:00:00', 5, 1),
(8, '08:00:00', '20:00:00', 0, 2),
(9, '08:00:00', '20:00:00', 1, 2),
(10, '08:00:00', '20:00:00', 2, 2),
(11, '08:00:00', '20:00:00', 3, 2),
(12, '08:00:00', '20:00:00', 4, 2),
(13, '08:00:00', '20:00:00', 5, 2),
(14, '08:00:00', '20:00:00', 0, 4),
(15, '08:00:00', '20:00:00', 1, 4),
(16, '08:00:00', '20:00:00', 2, 4),
(17, '08:00:00', '20:00:00', 3, 4),
(18, '08:00:00', '20:00:00', 4, 4),
(20, '08:00:00', '20:00:00', 0, 3),
(21, '08:00:00', '20:00:00', 1, 3),
(22, '08:00:00', '20:00:00', 2, 3),
(23, '08:00:00', '20:00:00', 3, 3),
(24, '08:00:00', '20:00:00', 4, 3),
(25, '08:00:00', '00:00:00', 5, 3),
(26, '00:00:00', '04:00:00', 6, 3),
(27, '08:00:00', '12:00:00', 0, 5),
(28, '08:00:00', '12:00:00', 1, 5),
(29, '08:00:00', '12:00:00', 2, 5),
(30, '08:00:00', '12:00:00', 3, 5),
(31, '08:00:00', '12:00:00', 4, 5),
(32, '08:00:00', '12:00:00', 5, 5),
(34, '14:00:00', '19:00:00', 0, 5),
(35, '14:00:00', '19:00:00', 1, 5),
(36, '14:00:00', '19:00:00', 2, 5),
(37, '14:00:00', '19:00:00', 3, 5),
(38, '14:00:00', '19:00:00', 4, 5),
(41, '08:00:00', '12:00:00', 0, 6),
(42, '08:00:00', '12:00:00', 1, 6),
(43, '08:00:00', '12:00:00', 2, 6),
(44, '08:00:00', '12:00:00', 3, 6),
(45, '08:00:00', '12:00:00', 4, 6),
(46, '08:00:00', '12:00:00', 5, 6),
(47, '14:00:00', '19:00:00', 0, 6),
(48, '14:00:00', '19:00:00', 1, 6),
(49, '14:00:00', '19:00:00', 2, 6),
(50, '14:00:00', '19:00:00', 3, 6),
(51, '14:00:00', '19:00:00', 4, 6),
(52, '08:00:00', '20:00:00', 0, 7),
(53, '08:00:00', '20:00:00', 1, 7),
(54, '08:00:00', '20:00:00', 2, 7),
(55, '08:00:00', '20:00:00', 3, 7),
(56, '08:00:00', '20:00:00', 4, 7),
(57, '08:00:00', '20:00:00', 5, 7);

--
-- Contenu de la table `EnergyAction`
--

INSERT INTO `EnergyAction` (`id`, `name`, `delayUp`, `delayDown`, `identifier`, `processIdUp`, `processIdDown`, `start`, `stop`, `status`) VALUES
(1, 'Domotique_1:Football', 10, 5, '1', '1', '1', '20:00:00', '08:00:00', 0);

--
-- Contenu de la table `Feature`
--

INSERT INTO `Feature` (`id`, `name`, `is_exclusive`) VALUES
(1, 'Type de court', 1),
(2, 'Équipement', 0);

--
-- Contenu de la table `FeatureValue`
--

INSERT INTO `FeatureValue` (`id`, `value`, `Feature_id`) VALUES
(1, 'Terre-battue', 1),
(2, 'Synthétique', 1),
(3, 'Goudron', 1),
(4, 'Vidéoprojecteur', 2),
(5, 'Machine à café', 2),
(6, 'Vidéoconférence', 2);

--
-- Contenu de la table `Message`
--


--
-- Contenu de la table `Reservation`
--


--
-- Contenu de la table `ReservationReason`
--

INSERT INTO `ReservationReason` (`id`, `Activity_id`, `name`) VALUES
(1, 1, 'Tournoi'),
(2, 1, 'Loisir'),
(3, 1, 'Entraînement'),
(4, 1, 'Cours'),
(5, 5, 'Planifiée'),
(6, 5, 'Non-planifiée'),
(7, 3, 'Entraînement'),
(8, 3, 'Tournoi'),
(9, 3, 'Amical'),
(10, 2, 'Entraînement'),
(11, 2, 'Tournoi');

--
-- Contenu de la table `Role`
--

INSERT INTO `Role` (`id`, `name`) VALUES
('activityManager', 'Gestionnaire des activités'),
('admin', 'Administrateur'),
('reportingManager', 'Gestionnaire du reporting'),
('userManager', 'Gestionnaire des utilisateurs'),
('zoneManager', 'Gestionnaire des zones');

--
-- Contenu de la table `Room`
--

INSERT INTO `Room` (`id`, `name`, `capacity`, `address`, `description`, `is_active`) VALUES
(1, 'Court Municipal #1', 4, '', '', 1),
(2, 'Court Municipal #2', 4, '', '', 1),
(3, 'Stade Central', NULL, '', '', 1),
(4, 'Salle polyvalente', NULL, '', '', 1),
(5, 'Salle de réunion A1', NULL, '', '', 1),
(6, 'Salle de réunion A2', NULL, '', '', 1),
(7, 'Terrain de Golf', NULL, '', '', 1);

--
-- Contenu de la table `RoomProfile`
--

INSERT INTO `RoomProfile` (`id`, `name`, `physical_access_id`, `Room_id`) VALUES
(1, 'Acces_Physique_1:Entrainement', '1', 4),
(2, 'Acces_Physique_1:Manifestation', '2', 4),
(4, 'Acces_Physique_1:Profil_Court_Municipal_1', '1', 1),
(5, 'Acces_Physique_1:Profil_Court_Municipal_2', '1', 2),
(6, 'Acces_Physique_1:Profil_Reunion_A1', '2', 5),
(7, 'Acces_Physique_1:Profil_Reunion_A2', '2', 6),
(8, 'Acces_Physique_2:Football', '3', 3),
(9, 'Acces_Physique_2:Golf', '5', 7);

--
-- Contenu de la table `Room_has_Activity`
--

INSERT INTO `Room_has_Activity` (`Room_id`, `Activity_id`) VALUES
(1, 1),
(2, 1),
(4, 2),
(3, 3),
(4, 3),
(7, 4),
(5, 5),
(6, 5);


--
-- Dumping data for table `Room_has_EnergyAction`
--

INSERT INTO `Room_has_EnergyAction` (`Room_id`, `EnergyAction_id`) VALUES
(3, 1);


--
-- Contenu de la table `Room_has_FeatureValue`
--

INSERT INTO `Room_has_FeatureValue` (`Room_id`, `FeatureValue_id`) VALUES
(1, 1),
(2, 2),
(5, 4),
(6, 4),
(6, 5),
(5, 6);

--
-- Contenu de la table `Subscription`
--

INSERT INTO `Subscription` (`id`, `Activity_id`, `Zone_id`, `start`, `stop`, `credit`, `is_active`, `Card_id`, `User_id`, `minimum_delay`, `maximum_delay`, `maximum_duration`, `hours_per_week`, `UserGroup_id`, `minimum_duration`) VALUES
(2, 1, 1, '2009-08-10', '2015-08-10', NULL, 1, NULL, 1, 12, 7, 120, 6, 1, 60),
(3, 1, 1, '2009-08-10', '2015-08-10', NULL, 1, NULL, 2, 12, 7, 120, 6, 1, 60),
(4, 1, 1, '2009-08-10', '2015-08-10', NULL, 1, NULL, 3, 12, 7, 120, 6, 1, 60),
(5, 1, 1, '2009-08-10', '2015-08-10', NULL, 1, NULL, 4, 12, 7, 120, 6, 1, 60),
(6, 1, 1, '2009-08-10', '2015-08-10', NULL, 1, NULL, 6, 12, 7, 120, 6, 1, 60),
(8, 5, 4, '2009-08-10', '2015-08-10', NULL, 1, NULL, 1, 3, 31, 480, 170, 2, 30),
(9, 5, 4, '2009-08-10', '2015-08-10', NULL, 1, NULL, 2, 3, 31, 480, 170, 2, 30),
(10, 5, 4, '2009-08-10', '2015-08-10', NULL, 1, NULL, 6, 3, 31, 480, 170, 2, 30),
(11, 5, 4, '2009-08-10', '2015-08-10', NULL, 1, NULL, 5, 3, 31, 480, 170, 2, 30),
(12, 2, 2, '2009-08-10', '2015-08-10', NULL, 1, NULL, 3, 12, 7, 120, 4, NULL, 60);

--
-- Contenu de la table `User`
--

INSERT INTO `User` (`id`, `login`, `password_hash`, `family_name`, `surname`, `is_active`, `card_number`, `birthdate`, `is_member`, `email_address`, `address`, `phone_number`, `created_at`, `photograph`) VALUES
(1, 'admin', 'c4fd46fd942c21581b40cb0ce22dd754b07aa840', 'Administrator', 'Admin', 1, '00000000', '1985-03-26', 1, NULL, '', '', NULL, NULL),
(2, 'demo', '8fca9e24e172f4d8ad1a8eef637734d4f89e57dc', 'Démonstration', 'Démo', 1, '00000001', '1985-08-06', 1, NULL, '', '', NULL, NULL),
(3, 'a.dupond95', '', 'Dupond', 'Alain', 1, '00000002', '1995-08-03', 1, NULL, '', '', '2009-08-10 18:34:19', NULL),
(4, 'p.dupond85', '', 'Dupond', 'Pierre', 1, '00000003', '1985-08-11', 1, NULL, '', '', NULL, NULL),
(5, 's.martin09', '', 'Martin', 'Stéphane', 1, '00000004', '2009-08-03', 1, NULL, '', '', '2009-08-10 18:36:33', NULL),
(6, 'm.dupuis93', '', 'Dupuis', 'Monique', 1, '00000005', '1993-08-05', 1, NULL, '', '', '2009-08-10 18:37:30', NULL);

--
-- Contenu de la table `UserGroup`
--

INSERT INTO `UserGroup` (`id`, `name`) VALUES
(1, 'Club de Tennis'),
(2, 'Organisateurs de réunion');

--
-- Contenu de la table `UserGroup_has_Activity`
--

INSERT INTO `UserGroup_has_Activity` (`UserGroup_id`, `Activity_id`) VALUES
(1, 1),
(2, 5);

--
-- Contenu de la table `UserGroup_has_Chief`
--

INSERT INTO `UserGroup_has_Chief` (`UserGroup_id`, `User_id`) VALUES
(1, 1),
(2, 1),
(1, 2),
(2, 2);

--
-- Contenu de la table `UserGroup_has_User`
--

INSERT INTO `UserGroup_has_User` (`UserGroup_id`, `User_id`) VALUES
(1, 3),
(1, 4),
(2, 5),
(1, 6),
(2, 6);

--
-- Contenu de la table `User_has_Role`
--

INSERT INTO `User_has_Role` (`User_id`, `Role_id`) VALUES
(2, 'activityManager'),
(1, 'admin'),
(2, 'reportingManager'),
(2, 'userManager'),
(2, 'zoneManager');

--
-- Contenu de la table `Zone`
--

INSERT INTO `Zone` (`id`, `name`, `parent_zone`) VALUES
(1, 'Communauté Urbaine', NULL),
(2, 'Centre-ville', 1),
(3, 'Banlieue', 1),
(4, 'Mairie', 2);

--
-- Contenu de la table `Zone_has_Room`
--

INSERT INTO `Zone_has_Room` (`Zone_id`, `Room_id`) VALUES
(4, 1),
(4, 2),
(2, 3),
(3, 4),
(4, 5),
(4, 6),
(3, 7);

SET FOREIGN_KEY_CHECKS=1;

COMMIT;

