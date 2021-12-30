CREATE TABLE `timetables_autosave` (
  `id` int(11) UNSIGNED NOT NULL,
  `week_id` int(11) UNSIGNED DEFAULT NULL,
  `day` date DEFAULT NULL,
  `lesson_id` int(11) UNSIGNED DEFAULT NULL,
  `class_id` int(11) UNSIGNED DEFAULT NULL,
  `subject_id` int(11) UNSIGNED DEFAULT NULL,
  `room_id` int(11) UNSIGNED DEFAULT NULL,
  `teacher_id` int(11) UNSIGNED DEFAULT NULL,
  `comment` tinyint(1) UNSIGNED DEFAULT NULL,
  `flags` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;