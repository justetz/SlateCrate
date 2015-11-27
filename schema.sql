-- MySQL Script generated by Justin R Etzine
-- Tue Nov 24 16:05:15 2015
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema slatecrate
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `slatecrate` ;

-- -----------------------------------------------------
-- Schema slatecrate
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `slatecrate` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `slatecrate` ;


-- -----------------------------------------------------
-- Table `slatecrate`.`categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `slatecrate`.`categories` ;

CREATE TABLE IF NOT EXISTS `slatecrate`.`categories` (
  `category_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `title` VARCHAR(50) NOT NULL COMMENT '',
  `links` INT(3) NOT NULL DEFAULT 0 COMMENT '',
  `prefix` VARCHAR(4) NOT NULL COMMENT '',
  `user_id` VARCHAR(50) NOT NULL COMMENT '',
  `creation_date` DATETIME NOT NULL COMMENT '',
  PRIMARY KEY (`category_id`)  COMMENT '',
  INDEX `CATEGORIES_FK1_idx` (`user_id` ASC)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `slatecrate`.`links`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `slatecrate`.`links` ;

CREATE TABLE IF NOT EXISTS `slatecrate`.`links` (
  `link_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `link` VARCHAR(2000) NOT NULL COMMENT '',
  `user_id` VARCHAR(50) NOT NULL COMMENT '',
  `creation_date` DATETIME NOT NULL COMMENT '',
  `title` VARCHAR(200) NOT NULL COMMENT '',
  `category_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`link_id`)  COMMENT '',
  INDEX `LINKS_FK1_idx` (`user_id` ASC)  COMMENT '',
  INDEX `LINKS_FK2_idx` (`category_id` ASC)  COMMENT '',
  CONSTRAINT `LINKS_FK1`
    FOREIGN KEY (`user_id`)
    REFERENCES `slatecrate`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `LINKS_FK2`
    FOREIGN KEY (`category_id`)
    REFERENCES `slatecrate`.`categories` (`category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
