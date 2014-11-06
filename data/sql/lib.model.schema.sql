
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- Activity
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Activity`;


CREATE TABLE `Activity`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(64)  NOT NULL,
	`color` VARCHAR(16) default '#ffffff' NOT NULL,
	`minimum_occupation` INTEGER(11) default 1 NOT NULL,
	`maximum_occupation` INTEGER(11) default 2 NOT NULL,
	`minimum_delay` INTEGER(11) default 60 NOT NULL,
	PRIMARY KEY (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Activity_has_Feature
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Activity_has_Feature`;


CREATE TABLE `Activity_has_Feature`
(
	`Activity_id` INTEGER(11)  NOT NULL,
	`Feature_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`Activity_id`,`Feature_id`),
	KEY `Activity_has_Feature_FI_2`(`Feature_id`),
	CONSTRAINT `Activity_has_Feature_FK_1`
		FOREIGN KEY (`Activity_id`)
		REFERENCES `Activity` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Activity_has_Feature_FK_2`
		FOREIGN KEY (`Feature_id`)
		REFERENCES `Feature` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Card
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Card`;


CREATE TABLE `Card`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`card_number` VARCHAR(32)  NOT NULL,
	`pin_code` VARCHAR(16)  NOT NULL,
	`is_active` TINYINT(4) default 1 NOT NULL,
	`owner` INTEGER(11),
	PRIMARY KEY (`id`),
	KEY `Card_FI_1`(`owner`),
	CONSTRAINT `Card_FK_1`
		FOREIGN KEY (`owner`)
		REFERENCES `CardUser` (`id`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- CardUser
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `CardUser`;


CREATE TABLE `CardUser`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`family_name` VARCHAR(64)  NOT NULL,
	`surname` VARCHAR(64)  NOT NULL,
	`birthdate` DATE,
	PRIMARY KEY (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- ClosePeriod
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ClosePeriod`;


CREATE TABLE `ClosePeriod`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`start` DATETIME  NOT NULL,
	`stop` DATETIME  NOT NULL,
	`Room_id` INTEGER(11)  NOT NULL,
	`reason` VARCHAR(128)  NOT NULL,
	PRIMARY KEY (`id`),
	KEY `ClosePeriod_FI_1`(`Room_id`),
	CONSTRAINT `ClosePeriod_FK_1`
		FOREIGN KEY (`Room_id`)
		REFERENCES `Room` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- DayPeriod
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `DayPeriod`;


CREATE TABLE `DayPeriod`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`start` TIME  NOT NULL,
	`stop` TIME  NOT NULL,
	`day_of_week` INTEGER(11) default 0 NOT NULL,
	`Room_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`id`),
	KEY `DayPeriod_FI_1`(`Room_id`),
	CONSTRAINT `DayPeriod_FK_1`
		FOREIGN KEY (`Room_id`)
		REFERENCES `Room` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- EnergyAction
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `EnergyAction`;


CREATE TABLE `EnergyAction`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(64)  NOT NULL,
	`delayUp` INTEGER(11) default 0 NOT NULL,
	`delayDown` INTEGER(11) default 0 NOT NULL,
	`identifier` VARCHAR(64),
	`processIdUp` VARCHAR(64),
	`processIdDown` VARCHAR(64),
	`start` TIME  NOT NULL,
	`stop` TIME  NOT NULL,
	`status` TINYINT(4) default 0 NOT NULL,
	PRIMARY KEY (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Feature
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Feature`;


CREATE TABLE `Feature`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(128)  NOT NULL,
	`is_exclusive` TINYINT(4) default 1 NOT NULL,
	PRIMARY KEY (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- FeatureValue
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `FeatureValue`;


CREATE TABLE `FeatureValue`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`value` VARCHAR(128)  NOT NULL,
	`Feature_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`id`),
	KEY `FeatureValue_FI_1`(`Feature_id`),
	CONSTRAINT `FeatureValue_FK_1`
		FOREIGN KEY (`Feature_id`)
		REFERENCES `Feature` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Message
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Message`;


CREATE TABLE `Message`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`subject` VARCHAR(256)  NOT NULL,
	`text` TEXT  NOT NULL,
	`created_at` DATETIME,
	`recipient_id` INTEGER(11)  NOT NULL,
	`sender` VARCHAR(256)  NOT NULL,
	`sender_id` INTEGER(11),
	`was_read` TINYINT(4) default 0 NOT NULL,
	`owner_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`id`),
	KEY `Message_FI_1`(`recipient_id`),
	KEY `Message_FI_2`(`sender_id`),
	KEY `Message_FI_3`(`owner_id`),
	CONSTRAINT `Message_FK_1`
		FOREIGN KEY (`recipient_id`)
		REFERENCES `User` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Message_FK_2`
		FOREIGN KEY (`sender_id`)
		REFERENCES `User` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Message_FK_3`
		FOREIGN KEY (`owner_id`)
		REFERENCES `User` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Reservation
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Reservation`;


CREATE TABLE `Reservation`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`RoomProfile_id` INTEGER(11)  NOT NULL,
	`Activity_id` INTEGER(11)  NOT NULL,
	`date` DATETIME  NOT NULL,
	`duration` INTEGER(11) default 60 NOT NULL,
	`is_activated` TINYINT(4) default 1 NOT NULL,
	`ReservationReason_id` INTEGER(11),
	`comment` VARCHAR(256),
	`UserGroup_id` INTEGER(11),
	`Card_id` INTEGER(11),
	`User_id` INTEGER(11),
	`ReservationParent_id` INTEGER,
	`members_count` INTEGER(11) default 0 NOT NULL,
	`guests_count` INTEGER(11) default 0 NOT NULL,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`status` INTEGER(11) default 0 NOT NULL,
	`price` INTEGER(11) default 0,
	`custom_1` VARCHAR(256),
	`custom_2` VARCHAR(256),
	`custom_3` VARCHAR(256),
	PRIMARY KEY (`id`),
	KEY `Reservation_FI_1`(`RoomProfile_id`),
	KEY `Reservation_FI_2`(`Activity_id`),
	KEY `Reservation_FI_3`(`ReservationReason_id`),
	KEY `Reservation_FI_4`(`UserGroup_id`),
	KEY `Reservation_FI_5`(`Card_id`),
	KEY `Reservation_FI_6`(`User_id`),
	KEY `Reservation_FI_7`(`ReservationParent_id`),
	CONSTRAINT `Reservation_FK_1`
		FOREIGN KEY (`RoomProfile_id`)
		REFERENCES `RoomProfile` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Reservation_FK_2`
		FOREIGN KEY (`Activity_id`)
		REFERENCES `Activity` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Reservation_FK_3`
		FOREIGN KEY (`ReservationReason_id`)
		REFERENCES `ReservationReason` (`id`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `Reservation_FK_4`
		FOREIGN KEY (`UserGroup_id`)
		REFERENCES `UserGroup` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Reservation_FK_5`
		FOREIGN KEY (`Card_id`)
		REFERENCES `Card` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Reservation_FK_6`
		FOREIGN KEY (`User_id`)
		REFERENCES `User` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Reservation_FK_7`
		FOREIGN KEY (`ReservationParent_id`)
		REFERENCES `Reservation` (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- ReservationOtherMembers
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ReservationOtherMembers`;


CREATE TABLE `ReservationOtherMembers`
(
	`Reservation_id` INTEGER(11)  NOT NULL,
	`User_id` INTEGER(11)  NOT NULL,
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`),
	KEY `ReservationOtherMembers_FI_1`(`Reservation_id`),
	KEY `ReservationOtherMembers_FI_2`(`User_id`),
	CONSTRAINT `ReservationOtherMembers_FK_1`
		FOREIGN KEY (`Reservation_id`)
		REFERENCES `Reservation` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `ReservationOtherMembers_FK_2`
		FOREIGN KEY (`User_id`)
		REFERENCES `User` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- ReservationReason
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ReservationReason`;


CREATE TABLE `ReservationReason`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`Activity_id` INTEGER(11)  NOT NULL,
	`name` VARCHAR(64)  NOT NULL,
	PRIMARY KEY (`id`),
	KEY `ReservationReason_FI_1`(`Activity_id`),
	CONSTRAINT `ReservationReason_FK_1`
		FOREIGN KEY (`Activity_id`)
		REFERENCES `Activity` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Role
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Role`;


CREATE TABLE `Role`
(
	`id` VARCHAR(32)  NOT NULL,
	`name` VARCHAR(64)  NOT NULL,
	PRIMARY KEY (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Room
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Room`;


CREATE TABLE `Room`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(64)  NOT NULL,
	`capacity` INTEGER(11) default 1,
	`address` VARCHAR(256),
	`description` VARCHAR(256),
	`is_active` TINYINT(4) default 1 NOT NULL,
	PRIMARY KEY (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- RoomProfile
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `RoomProfile`;


CREATE TABLE `RoomProfile`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(256)  NOT NULL,
	`physical_access_id` VARCHAR(256)  NOT NULL,
	`Room_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`id`),
	KEY `RoomProfile_FI_1`(`Room_id`),
	CONSTRAINT `RoomProfile_FK_1`
		FOREIGN KEY (`Room_id`)
		REFERENCES `Room` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Room_has_Activity
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Room_has_Activity`;


CREATE TABLE `Room_has_Activity`
(
	`Room_id` INTEGER(11)  NOT NULL,
	`Activity_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`Room_id`,`Activity_id`),
	KEY `Room_has_Activity_FI_2`(`Activity_id`),
	CONSTRAINT `Room_has_Activity_FK_1`
		FOREIGN KEY (`Room_id`)
		REFERENCES `Room` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Room_has_Activity_FK_2`
		FOREIGN KEY (`Activity_id`)
		REFERENCES `Activity` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Room_has_EnergyAction
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Room_has_EnergyAction`;


CREATE TABLE `Room_has_EnergyAction`
(
	`Room_id` INTEGER(11)  NOT NULL,
	`EnergyAction_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`Room_id`,`EnergyAction_id`),
	KEY `Room_has_EnergyAction_FI_2`(`EnergyAction_id`),
	CONSTRAINT `Room_has_EnergyAction_FK_1`
		FOREIGN KEY (`Room_id`)
		REFERENCES `Room` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Room_has_EnergyAction_FK_2`
		FOREIGN KEY (`EnergyAction_id`)
		REFERENCES `EnergyAction` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Room_has_FeatureValue
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Room_has_FeatureValue`;


CREATE TABLE `Room_has_FeatureValue`
(
	`Room_id` INTEGER(11)  NOT NULL,
	`FeatureValue_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`Room_id`,`FeatureValue_id`),
	KEY `Room_has_FeatureValue_FI_2`(`FeatureValue_id`),
	CONSTRAINT `Room_has_FeatureValue_FK_1`
		FOREIGN KEY (`Room_id`)
		REFERENCES `Room` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Room_has_FeatureValue_FK_2`
		FOREIGN KEY (`FeatureValue_id`)
		REFERENCES `FeatureValue` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Subscription
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Subscription`;


CREATE TABLE `Subscription`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`Activity_id` INTEGER(11)  NOT NULL,
	`Zone_id` INTEGER(11)  NOT NULL,
	`start` DATE,
	`stop` DATE,
	`credit` INTEGER(11),
	`is_active` TINYINT(4) default 1 NOT NULL,
	`Card_id` INTEGER(11),
	`User_id` INTEGER(11),
	`minimum_delay` INTEGER(11) default 12 NOT NULL,
	`maximum_delay` INTEGER(11) default 7 NOT NULL,
	`maximum_duration` INTEGER(11) default 120 NOT NULL,
	`hours_per_week` INTEGER(11) default 4 NOT NULL,
	`UserGroup_id` INTEGER(11),
	`minimum_duration` INTEGER(11) default 60 NOT NULL,
	PRIMARY KEY (`id`),
	KEY `Subscription_FI_1`(`Activity_id`),
	KEY `Subscription_FI_2`(`Zone_id`),
	KEY `Subscription_FI_3`(`Card_id`),
	KEY `Subscription_FI_4`(`User_id`),
	KEY `Subscription_FI_5`(`UserGroup_id`),
	CONSTRAINT `Subscription_FK_1`
		FOREIGN KEY (`Activity_id`)
		REFERENCES `Activity` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Subscription_FK_2`
		FOREIGN KEY (`Zone_id`)
		REFERENCES `Zone` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Subscription_FK_3`
		FOREIGN KEY (`Card_id`)
		REFERENCES `Card` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Subscription_FK_4`
		FOREIGN KEY (`User_id`)
		REFERENCES `User` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Subscription_FK_5`
		FOREIGN KEY (`UserGroup_id`)
		REFERENCES `UserGroup` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- User
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `User`;


CREATE TABLE `User`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`login` VARCHAR(64)  NOT NULL,
	`password_hash` VARCHAR(64)  NOT NULL,
	`family_name` VARCHAR(64)  NOT NULL,
	`surname` VARCHAR(64)  NOT NULL,
	`is_active` TINYINT(4) default 1 NOT NULL,
	`card_number` VARCHAR(32)  NOT NULL,
	`birthdate` DATE,
	`is_member` TINYINT(4) default 1 NOT NULL,
	`email_address` VARCHAR(128),
	`address` VARCHAR(256),
	`phone_number` VARCHAR(64),
	`created_at` DATETIME,
	`photograph` LONGBLOB,
	PRIMARY KEY (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- UserGroup
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `UserGroup`;


CREATE TABLE `UserGroup`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(64)  NOT NULL,
	PRIMARY KEY (`id`)
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- UserGroup_has_Activity
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `UserGroup_has_Activity`;


CREATE TABLE `UserGroup_has_Activity`
(
	`UserGroup_id` INTEGER(11)  NOT NULL,
	`Activity_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`UserGroup_id`,`Activity_id`),
	KEY `UserGroup_has_Activity_FI_2`(`Activity_id`),
	CONSTRAINT `UserGroup_has_Activity_FK_1`
		FOREIGN KEY (`UserGroup_id`)
		REFERENCES `UserGroup` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `UserGroup_has_Activity_FK_2`
		FOREIGN KEY (`Activity_id`)
		REFERENCES `Activity` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- UserGroup_has_Chief
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `UserGroup_has_Chief`;


CREATE TABLE `UserGroup_has_Chief`
(
	`UserGroup_id` INTEGER(11)  NOT NULL,
	`User_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`UserGroup_id`,`User_id`),
	KEY `UserGroup_has_Chief_FI_2`(`User_id`),
	CONSTRAINT `UserGroup_has_Chief_FK_1`
		FOREIGN KEY (`UserGroup_id`)
		REFERENCES `UserGroup` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `UserGroup_has_Chief_FK_2`
		FOREIGN KEY (`User_id`)
		REFERENCES `User` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- UserGroup_has_User
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `UserGroup_has_User`;


CREATE TABLE `UserGroup_has_User`
(
	`UserGroup_id` INTEGER(11)  NOT NULL,
	`User_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`UserGroup_id`,`User_id`),
	KEY `UserGroup_has_User_FI_2`(`User_id`),
	CONSTRAINT `UserGroup_has_User_FK_1`
		FOREIGN KEY (`UserGroup_id`)
		REFERENCES `UserGroup` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `UserGroup_has_User_FK_2`
		FOREIGN KEY (`User_id`)
		REFERENCES `User` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- User_has_Role
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `User_has_Role`;


CREATE TABLE `User_has_Role`
(
	`User_id` INTEGER(11)  NOT NULL,
	`Role_id` VARCHAR(32)  NOT NULL,
	PRIMARY KEY (`User_id`,`Role_id`),
	KEY `User_has_Role_FI_2`(`Role_id`),
	CONSTRAINT `User_has_Role_FK_1`
		FOREIGN KEY (`User_id`)
		REFERENCES `User` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `User_has_Role_FK_2`
		FOREIGN KEY (`Role_id`)
		REFERENCES `Role` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Zone
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Zone`;


CREATE TABLE `Zone`
(
	`id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(64)  NOT NULL,
	`parent_zone` INTEGER(11),
	PRIMARY KEY (`id`),
	KEY `Zone_FI_1`(`parent_zone`),
	CONSTRAINT `Zone_FK_1`
		FOREIGN KEY (`parent_zone`)
		REFERENCES `Zone` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

#-----------------------------------------------------------------------------
#-- Zone_has_Room
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `Zone_has_Room`;


CREATE TABLE `Zone_has_Room`
(
	`Zone_id` INTEGER(11)  NOT NULL,
	`Room_id` INTEGER(11)  NOT NULL,
	PRIMARY KEY (`Zone_id`,`Room_id`),
	KEY `Zone_has_Room_FI_2`(`Room_id`),
	CONSTRAINT `Zone_has_Room_FK_1`
		FOREIGN KEY (`Zone_id`)
		REFERENCES `Zone` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE,
	CONSTRAINT `Zone_has_Room_FK_2`
		FOREIGN KEY (`Room_id`)
		REFERENCES `Room` (`id`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
)Engine=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
