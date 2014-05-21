SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `vinyl` ;
CREATE SCHEMA IF NOT EXISTS `vinyl` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
SHOW WARNINGS;
USE `vinyl` ;

-- -----------------------------------------------------
-- Table `artist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `artist` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `artist` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `track`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `track` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `track` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `artist_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_track_artist_idx` (`artist_id` ASC),
  CONSTRAINT `fk_track_artist`
    FOREIGN KEY (`artist_id`)
    REFERENCES `artist` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `label`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `label` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `label` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `format`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `format` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `format` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

INSERT INTO `format`(`name`) VALUES('Single');
INSERT INTO `format`(`name`) VALUES('LP');
INSERT INTO `format`(`name`) VALUES('EP');

-- -----------------------------------------------------
-- Table `rpm`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `rpm` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `rpm` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

INSERT INTO `rpm`(`name`) VALUES(33);
INSERT INTO `rpm`(`name`) VALUES(45);
INSERT INTO `rpm`(`name`) VALUES(78);

-- -----------------------------------------------------
-- Table `condition`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `condition` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `condition` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

INSERT INTO `condition`(`name`) VALUES('Mint');
INSERT INTO `condition`(`name`) VALUES('Good');
INSERT INTO `condition`(`name`) VALUES('Fair');
INSERT INTO `condition`(`name`) VALUES('Poor');
INSERT INTO `condition`(`name`) VALUES('Unplayable');

-- -----------------------------------------------------
-- Table `size`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `size` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `size` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

INSERT INTO `size`(`name`) VALUES(7);
INSERT INTO `size`(`name`) VALUES(10);
INSERT INTO `size`(`name`) VALUES(12);

-- -----------------------------------------------------
-- Table `location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `location` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `location` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `record`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `record` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `record` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `label_id` INT NOT NULL,
  `format_id` INT NOT NULL,
  `rpm_id` INT NOT NULL,
  `condition_id` INT NOT NULL,
  `released` INT NOT NULL,
  `sides` INT NOT NULL,
  `size_id` INT NOT NULL,
  `location_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_record_label1_idx` (`label_id` ASC),
  INDEX `fk_record_format1_idx` (`format_id` ASC),
  INDEX `fk_record_rpm1_idx` (`rpm_id` ASC),
  INDEX `fk_record_condition1_idx` (`condition_id` ASC),
  INDEX `fk_record_size1_idx` (`size_id` ASC),
  INDEX `fk_record_location1_idx` (`location_id` ASC),
  CONSTRAINT `fk_record_label1`
    FOREIGN KEY (`label_id`)
    REFERENCES `label` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_record_format1`
    FOREIGN KEY (`format_id`)
    REFERENCES `format` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_record_rpm1`
    FOREIGN KEY (`rpm_id`)
    REFERENCES `rpm` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_record_condition1`
    FOREIGN KEY (`condition_id`)
    REFERENCES `condition` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_record_size1`
    FOREIGN KEY (`size_id`)
    REFERENCES `size` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_record_location1`
    FOREIGN KEY (`location_id`)
    REFERENCES `location` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `lk_record_track`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lk_record_track` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `lk_record_track` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `record_id` INT NOT NULL,
  `track_id` INT NOT NULL,
  `side` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_lk_record_track_record1_idx` (`record_id` ASC),
  INDEX `fk_lk_record_track_track1_idx` (`track_id` ASC),
  CONSTRAINT `fk_lk_record_track_record1`
    FOREIGN KEY (`record_id`)
    REFERENCES `record` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lk_record_track_track1`
    FOREIGN KEY (`track_id`)
    REFERENCES `track` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `lk_record_artist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lk_record_artist` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `lk_record_artist` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `record_id` INT NOT NULL,
  `artist_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_lk_record_artist_record1_idx` (`record_id` ASC),
  INDEX `fk_lk_record_artist_artist1_idx` (`artist_id` ASC),
  CONSTRAINT `fk_lk_record_artist_record1`
    FOREIGN KEY (`record_id`)
    REFERENCES `record` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lk_record_artist_artist1`
    FOREIGN KEY (`artist_id`)
    REFERENCES `artist` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

DROP TABLE IF EXISTS `admin_user`;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `admin_user`(
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(48) NOT NULL,
  `password` VARCHAR(40) NOT NULL,
  PRIMARY KEY(`id`)
)
ENGINE = InnoDB;



