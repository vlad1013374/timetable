UPDATE `config` SET `param_ru` = 'Располагать метки слева' WHERE `config`.`param` = 'positionFlagsLeft';
UPDATE `config` SET `param_ru` = 'Автосохранение каждые' WHERE `config`.`param` = 'autosavePeriodInMinutes';
ALTER TABLE `config` ADD `param_description` VARCHAR(50) NOT NULL DEFAULT '' AFTER `param_ru`;
UPDATE `config` SET `value` = '12', `type` = 'int' WHERE `config`.`param` = 'fontSize';
UPDATE `config` SET `param_description` = 'минут' WHERE `config`.`param` = 'autosavePeriodInMinutes';
INSERT INTO `config` (`param`, `param_ru`, `param_description`, `value`, `type`, `section`) VALUES ('font_print', 'Шрифт', '', 'Calibri', 'string', 'print');
INSERT INTO `config` (`param`, `param_ru`, `param_description`, `value`, `type`, `section`) VALUES ('fontSize_print', 'Размер шрифта', '', '10', 'int', 'print');