ALTER TABLE `config` CHANGE `type` `type` ENUM('string','int','bool','text','color') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
INSERT INTO `config` (`param`, `param_ru`, `param_description`, `value`, `type`, `section`) VALUES ('activeRowColor', 'Цвет выделенной строки', '', '#22a7f0', 'color', 'editor');
INSERT INTO `config` (`param`, `param_ru`, `param_description`, `value`, `type`, `section`) VALUES ('isActiveRowHover', 'Выделять строку при наведении', '', '0', 'bool', 'editor');
ALTER TABLE `config` ADD ordering INT NOT NULL AFTER `section`;
UPDATE `config` SET `ordering` = '5000' WHERE `config`.`param` = 'activeRowColor'; 
UPDATE `config` SET `ordering` = '5000' WHERE `config`.`param` = 'isActiveRowHover';