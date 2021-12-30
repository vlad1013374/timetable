ALTER TABLE `config` ADD `type` ENUM('string', 'int', 'bool', 'text') NOT NULL AFTER `value`;
UPDATE `config` SET `type` = 'int' WHERE `config`.`param` = 'autosavePeriodInMinutes';
INSERT INTO `config` (`param`, `value`, `type`, `section`) VALUES ('positionFlagsLeft', '1', 'bool', 'editor');