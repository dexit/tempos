-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb5+lenny1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 10 Août 2009 à 17:34
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


--
-- Contenu de la table `Activity_has_Feature`
--


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


--
-- Contenu de la table `EnergyAction`
--


--
-- Contenu de la table `Feature`
--


--
-- Contenu de la table `FeatureValue`
--


--
-- Contenu de la table `Message`
--


--
-- Contenu de la table `Reservation`
--


--
-- Contenu de la table `ReservationReason`
--


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


--
-- Contenu de la table `Room_has_Activity`
--


--
-- Contenu de la table `Room_has_FeatureValue`
--


--
-- Contenu de la table `Subscription`
--


--
-- Contenu de la table `User`
--

INSERT INTO `User` (`id`, `login`, `password_hash`, `family_name`, `surname`, `is_active`, `card_number`, `birthdate`, `is_member`, `email_address`, `address`, `phone_number`, `created_at`, `photograph`) VALUES
(1, 'admin', 'c4fd46fd942c21581b40cb0ce22dd754b07aa840', 'Administrator', 'Admin', 1, '00000000', '1985-03-26', 1, NULL, NULL, NULL, '2009-08-10 17:33:09', NULL);

--
-- Contenu de la table `UserGroup`
--


--
-- Contenu de la table `UserGroup_has_Activity`
--


--
-- Contenu de la table `UserGroup_has_Chief`
--


--
-- Contenu de la table `UserGroup_has_User`
--


--
-- Contenu de la table `User_has_Role`
--

INSERT INTO `User_has_Role` (`User_id`, `Role_id`) VALUES
(1, 'admin');

--
-- Contenu de la table `Zone`
--


--
-- Contenu de la table `Zone_has_Room`
--


SET FOREIGN_KEY_CHECKS=1;

COMMIT;

