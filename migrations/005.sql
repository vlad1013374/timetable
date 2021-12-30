ALTER TABLE `config` ADD `param_ru` VARCHAR(60) NOT NULL AFTER `param`;
UPDATE `config` SET `param_ru` = 'Время автосейва' WHERE `config`.`param` = 'autosavePeriodInMinutes';
UPDATE `config` SET `param_ru` = 'Шрифт' WHERE `config`.`param` = 'font';
<<<<<<< HEAD
UPDATE `config` SET `param_ru` = 'Размер шрифта' WHERE `config`.`param` = 'fontSize';
=======
UPDATE `config` SET `param_ru` = 'Размер шрифта' WHERE `config`.`param` = 'fontSize';
>>>>>>> 3186ecfee44980cfebe8cc7c3ee901e41f152cdf
