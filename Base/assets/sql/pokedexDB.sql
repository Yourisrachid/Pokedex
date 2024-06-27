CREATE TABLE IF NOT EXISTS `species`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `species` VARCHAR(1024) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `pokemons`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name_english` VARCHAR(255) NOT NULL,
    `name_japanese` VARCHAR(1024) NOT NULL,
    `name_chinese` VARCHAR(1024) NOT NULL,
    `name_french` VARCHAR(255) NOT NULL,
    `hp` INT,
    `attack` INT,
    `defense` INT,
    `speed` INT,
    `special_attack` INT,
    `special_defense` INT,
    `description` TEXT,
    `height_m` DOUBLE,
    `weight_kg` DOUBLE,
    `species_id` INT UNSIGNED NOT NULL,
    `pokemon_evol_id` INT UNSIGNED,
    FOREIGN KEY (`species_id`) REFERENCES `species`(`id`),
    FOREIGN KEY (`pokemon_evol_id`) REFERENCES `pokemons`(`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `types`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `pokemon_types`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pokemon_id` INT UNSIGNED NOT NULL,
    `type_id` INT UNSIGNED NOT NULL,
    FOREIGN KEY (`pokemon_id`) REFERENCES `pokemons`(`id`),
    FOREIGN KEY (`type_id`) REFERENCES `types`(`id`)
) ENGINE=InnoDB;

