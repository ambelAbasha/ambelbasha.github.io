SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `flynow` ;
USE `flynow` ;

-- -----------------------------------------------------
-- Table `flynow`.`passenger`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flynow`.`passenger` (
  `passenger_id` INT NOT NULL AUTO_INCREMENT ,
  `p_name` VARCHAR(15) NULL DEFAULT NULL ,
  `p_lastname` VARCHAR(15) NULL DEFAULT NULL ,
  `city` VARCHAR(15) NULL DEFAULT NULL ,
  PRIMARY KEY (`passenger_id`) );


-- -----------------------------------------------------
-- Table `flynow`.`airline_company`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flynow`.`airline_company` (
  `air_com_id` INT NOT NULL AUTO_INCREMENT ,
  `comp_name` VARCHAR(40) NULL DEFAULT NULL ,
  PRIMARY KEY (`air_com_id`) );


-- -----------------------------------------------------
-- Table `flynow`.`booking`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flynow`.`booking` (
  `booking_id` INT NULL DEFAULT NULL AUTO_INCREMENT ,
  `passenger_id` INT NOT NULL ,
  PRIMARY KEY (`booking_id`) ,
  INDEX `fk_4E202805-0070-46D4-BF90-6E85490CA0A0` (`passenger_id` ASC) ,
  CONSTRAINT `fk_4E202805-0070-46D4-BF90-6E85490CA0A0`
    FOREIGN KEY (`passenger_id` )
    REFERENCES `flynow`.`passenger` (`passenger_id` ));


-- -----------------------------------------------------
-- Table `flynow`.`flight`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flynow`.`flight` (
  `flight_id` INT NOT NULL AUTO_INCREMENT ,
  `arrival_date` DATE NULL DEFAULT NULL ,
  `departure_date` DATE NULL DEFAULT NULL ,
  `destination_airport` VARCHAR(40) NULL DEFAULT NULL ,
  `departure_airport` VARCHAR(40) NULL DEFAULT NULL ,
  `business_class` INT(10) NULL DEFAULT NULL ,
  `economy_seat` INT(10) NULL DEFAULT NULL ,
  `price_economy` FLOAT NULL DEFAULT NULL ,
  `price_business` FLOAT NULL DEFAULT NULL ,
  `air_com_id` INT NULL DEFAULT NULL ,
  `passenger_id` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`flight_id`) ,
  INDEX `fk_CF8241C1-7F96-457C-9156-BF77D0D053F8` (`air_com_id` ASC) ,
  INDEX `fk_0A6CBD8D-6E16-469B-8127-CA287E542751` (`passenger_id` ASC) ,
  CONSTRAINT `fk_CF8241C1-7F96-457C-9156-BF77D0D053F8`
    FOREIGN KEY (`air_com_id` )
    REFERENCES `flynow`.`airline_company` (`air_com_id` ),
  CONSTRAINT `fk_0A6CBD8D-6E16-469B-8127-CA287E542751`
    FOREIGN KEY (`passenger_id` )
    REFERENCES `flynow`.`passenger` (`passenger_id` ));

USE `flynow` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
