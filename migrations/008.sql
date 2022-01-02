ALTER TABLE `timetables_autosave` ADD `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `flags`;
ALTER TABLE `timetables_autosave` CHANGE `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
RENAME TABLE `timetables_autosave` TO `autosave`;