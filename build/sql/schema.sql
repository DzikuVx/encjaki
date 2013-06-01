
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- news
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news`
(
	`NewsID` BIGINT NOT NULL AUTO_INCREMENT,
	`UserID` BIGINT NOT NULL,
	`ctime__` DATETIME,
	`Title` VARCHAR(255) NOT NULL,
	`Text` LONGTEXT NOT NULL,
	`Published` TINYINT(1) DEFAULT 0 NOT NULL,
	`Type` VARCHAR(32) DEFAULT 'normal' NOT NULL,
	`Language` CHAR(2) DEFAULT 'pl' NOT NULL,
	PRIMARY KEY (`NewsID`),
	INDEX `news_FI_1` (`UserID`),
	CONSTRAINT `news_FK_1`
		FOREIGN KEY (`UserID`)
		REFERENCES `user` (`UserID`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- link
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `link`;

CREATE TABLE `link`
(
	`LinkID` BIGINT NOT NULL AUTO_INCREMENT,
	`Language` CHAR(2) DEFAULT 'pl' NOT NULL,
	`Name` VARCHAR(255) NOT NULL,
	`Link` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`LinkID`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
	`UserID` BIGINT NOT NULL AUTO_INCREMENT,
	`Login` VARCHAR(255) NOT NULL,
	`Name` VARCHAR(255) NOT NULL,
	`Password` VARCHAR(255) NOT NULL,
	`Locked` TINYINT(1) DEFAULT 0 NOT NULL,
	`ctime__` DATETIME,
	PRIMARY KEY (`UserID`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- statistics
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `statistics`;

CREATE TABLE `statistics`
(
	`StatisticsID` BIGINT NOT NULL AUTO_INCREMENT,
	`UserID` BIGINT NOT NULL,
	`Class` VARCHAR(24),
	`Turn` INTEGER NOT NULL,
	`Population` INTEGER(24),
	`Parameter` VARCHAR(32),
	`Value` INTEGER NOT NULL,
	PRIMARY KEY (`StatisticsID`),
	INDEX `statistics_FI_1` (`UserID`),
	CONSTRAINT `statistics_FK_1`
		FOREIGN KEY (`UserID`)
		REFERENCES `user` (`UserID`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
