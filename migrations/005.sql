ALTER TABLE `config` ADD `param_ru` VARCHAR(60) NOT NULL AFTER `param`;
UPDATE `config` SET `param_ru` = 'Время автосейва(минуты)' WHERE `config`.`param` = 'autosavePeriodInMinutes';
UPDATE `config` SET `param_ru` = 'Шрифт' WHERE `config`.`param` = 'font';
UPDATE `config` SET `param_ru` = 'Размер шрифта' WHERE `config`.`param` = 'fontSize';
UPDATE `config` SET `param_ru` = 'Позиция меток' WHERE `config`.`param` = 'positionFlagsLeft';


