SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema Digitaal_Portfolio
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema Digitaal_Portfolio
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Digitaal_Portfolio` DEFAULT CHARACTER SET utf8 ;
USE `Digitaal_Portfolio` ;

-- -----------------------------------------------------
-- Table `Digitaal_Portfolio`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Digitaal_Portfolio`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `e-mail` VARCHAR(254) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `role` VARCHAR(13) NOT NULL,
  `firstName` VARCHAR(50) NULL,
  `lastName` VARCHAR(50) NULL,
  `phone` VARCHAR(16) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `e-mail_UNIQUE` (`e-mail` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Digitaal_Portfolio`.`rating`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Digitaal_Portfolio`.`rating` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `grade` VARCHAR(3) NOT NULL,
  `remark` VARCHAR(500) NULL,
  `giver_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_beoordeling_gebruiker1_idx` (`giver_id` ASC),
  CONSTRAINT `fk_beoordeling_gebruiker1`
    FOREIGN KEY (`giver_id`)
    REFERENCES `Digitaal_Portfolio`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Digitaal_Portfolio`.`portfolio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Digitaal_Portfolio`.`portfolio` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NULL,
  `description` VARCHAR(500) NULL,
  `owner_id` INT NOT NULL,
  `portfolio_rating_id` INT NULL,
  `header_image` VARCHAR(500) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_portfolio_gebruiker_idx` (`owner_id` ASC),
  INDEX `fk_portfolio_beoordeling1_idx` (`portfolio_rating_id` ASC),
  CONSTRAINT `fk_portfolio_gebruiker`
    FOREIGN KEY (`owner_id`)
    REFERENCES `Digitaal_Portfolio`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_portfolio_beoordeling1`
    FOREIGN KEY (`portfolio_rating_id`)
    REFERENCES `Digitaal_Portfolio`.`rating` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Digitaal_Portfolio`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Digitaal_Portfolio`.`comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `placement_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `message` VARCHAR(45) NOT NULL,
  `placer_id` INT NULL,
  `portfolio_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_commentaar_gebruiker1_idx` (`placer_id` ASC),
  INDEX `fk_commentaar_portfolio1_idx` (`portfolio_id` ASC),
  CONSTRAINT `fk_commentaar_gebruiker1`
    FOREIGN KEY (`placer_id`)
    REFERENCES `Digitaal_Portfolio`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commentaar_portfolio1`
    FOREIGN KEY (`portfolio_id`)
    REFERENCES `Digitaal_Portfolio`.`portfolio` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Digitaal_Portfolio`.`project`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Digitaal_Portfolio`.`project` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NULL,
  `description` VARCHAR(500) NULL,
  `portfolio_id` INT NOT NULL,
  `project_rating_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_project_portfolio1_idx` (`portfolio_id` ASC),
  INDEX `fk_project_beoordeling1_idx` (`project_rating_id` ASC),
  CONSTRAINT `fk_project_portfolio1`
    FOREIGN KEY (`portfolio_id`)
    REFERENCES `Digitaal_Portfolio`.`portfolio` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_project_beoordeling1`
    FOREIGN KEY (`project_rating_id`)
    REFERENCES `Digitaal_Portfolio`.`rating` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Digitaal_Portfolio`.`file`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Digitaal_Portfolio`.`file` (
  `path` VARCHAR(500) NOT NULL,
  `type` VARCHAR(20) NULL,
  `description` VARCHAR(500) NULL,
  `project_id` INT NOT NULL,
  PRIMARY KEY (`path`),
  INDEX `fk_bestand_project1_idx` (`project_id` ASC),
  CONSTRAINT `fk_bestand_project1`
    FOREIGN KEY (`project_id`)
    REFERENCES `Digitaal_Portfolio`.`project` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
