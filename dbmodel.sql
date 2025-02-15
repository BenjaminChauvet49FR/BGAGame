
-- ------
-- BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
-- HexField implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here

-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.

-- Example 1: create a standard "card" table to be used with the "Deck" tools (see example game "hearts"):

-- CREATE TABLE IF NOT EXISTS `card` (
--   `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
--   `card_type` varchar(16) NOT NULL,
--   `card_type_arg` int(11) NOT NULL,
--   `card_location` varchar(16) NOT NULL,
--   `card_location_arg` int(11) NOT NULL,
--   PRIMARY KEY (`card_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- Example 2: add a custom field to the standard "player" table
-- ALTER TABLE `player` ADD `player_my_custom_field` INT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `player` ADD `x_meeple` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `y_meeple` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `wood` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `food` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `iron` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `stone` INT UNSIGNED NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `hexfieldtaken` (
  `x` int(10) unsigned NOT NULL,
  `y` int(10) unsigned NOT NULL,
  `taken` boolean NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX `hexfieldtaken_ind` on `hexfieldtaken` (`y`, `x`);


CREATE TABLE IF NOT EXISTS `buildingbuilt` (
  `x` int(10) unsigned NOT NULL,
  `y` int(10) unsigned NOT NULL,
  `player` int(10) unsigned,
  `kind` int(10) unsigned
   --FOREIGN KEY (player) REFERENCES player(player_id) (les cles étrangères ne semblent pas les bienvenues)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `buildingbuilt_player_ind` on `buildingbuilt` (`player`);
CREATE UNIQUE INDEX `buildingbuilt_position_ind` on `buildingbuilt` (`y`, `x`);
