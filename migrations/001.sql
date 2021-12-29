
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `capacity` tinyint(4) NOT NULL,
  `room_id` int(11) NOT NULL,
  `filter` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `capacity`, `room_id`, `filter`) VALUES
(1, '10А - ГП', 20, 150, '10'),
(2, '10А - ХБ', 20, 150, '10'),
(3, '10Б - СЭ', 20, 152, '10'),
(4, '10Б - ФМ', 20, 152, '10'),
(5, '11А - ГП', 20, 142, '11'),
(6, '11Б - ТЕХН', 20, 143, '11'),
(7, '11В - СЭ', 20, 146, '11'),
(8, '11В - ХБ', 20, 146, '11');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `param` varchar(250) NOT NULL,
  `value` varchar(250) NOT NULL,
  `section` enum('editor','web','print') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`param`, `value`, `section`) VALUES
('autosavePeriodInMinutes', '5', 'editor'),
('font', 'arial', 'editor'),
('fontSize', '12px', 'editor');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `start` time NOT NULL,
  `stop` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `name`, `start`, `stop`) VALUES
(1, '1 пара', '08:45:00', '10:20:00'),
(2, '2 пара', '10:30:00', '12:00:00'),
(3, '3 пара', '12:30:00', '14:05:00'),
(4, '4 пара', '14:15:00', '15:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `capacity` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `capacity`) VALUES
(142, '142', 25),
(143, '143', 25),
(146, '146', 25),
(147, '147', 25),
(148, '148', 25),
(150, '150', 25),
(151, '151', 25),
(152, '152', 25),
(155, '155', 5);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `short_name` varchar(50) NOT NULL,
  `default_room_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `short_name`, `default_room_id`) VALUES
(1, 'Астрономия', 'Астрономия', NULL),
(2, 'География', 'География', NULL),
(3, 'Информатика', 'Информ.', 148),
(4, 'История', 'История', NULL),
(5, 'МХК', 'МХК', NULL),
(6, 'Русский язык', 'Русск. язык', NULL),
(7, 'Физика', 'Физика', NULL),
(8, 'Физическая культура', 'Физ-ра', NULL),
(9, 'Химия', 'Химия', 142),
(10, 'Английский язык', 'Англ. яз.', NULL),
(11, 'Алгебра', 'Алгебра', NULL),
(12, 'Литература', 'Литература', NULL),
(13, 'Биология', 'Биология', NULL),
(14, 'Экономика', 'Экономика', NULL),
(15, 'Обществознание', 'Обществознание', NULL),
(16, 'Право', 'Право', NULL),
(17, 'ОБЖ', 'ОБЖ', NULL),
(18, 'Математика', 'Математика', NULL),
(19, 'Геометрия', 'Геометрия', NULL),
(20, 'История ОРМО', 'История ОРМО', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`) VALUES
(1, 'Коротченко Н.М.'),
(2, 'Антонова Л.Д.'),
(3, 'Матвеева П.Ф.'),
(4, 'Мерзляков А.В.'),
(5, 'Родина Е.В.'),
(6, 'Чередова Е.П.'),
(7, 'Алигасанова К.Л.'),
(8, 'Кучерова А.И.'),
(9, 'Исаева А.А.'),
(10, 'Полонская М.С.'),
(11, 'Лемешко Е.Ю.'),
(12, 'Мерзляков В.А.'),
(13, 'Карнаухов С.М.'),
(14, 'Дукарт С.А.'),
(15, 'Романенко Э.С.'),
(16, 'Пак В.В.'),
(17, 'Алигасанова Кр. Л.'),
(18, 'Гашков С.И.'),
(19, 'Постникова Е.И.'),
(20, 'Нариманов Р.К.'),
(21, 'Крупицкая О.Н.'),
(22, 'Совалкова Ю.В.'),
(23, 'Некряч Е.Н.'),
(24, 'Романович О.В.'),
(25, 'Нявро Р.И.'),
(26, 'Сорокин В.А.'),
(27, 'Ромашова Т.Н.'),
(28, 'Мясников В.В.'),
(29, 'Гимаева Н.Р.'),
(30, 'Жилина Т.Н.'),
(31, 'Кузнецова А.Е.'),
(32, 'Колпакова Л.В.'),
(33, 'Чернявская Ю.О.'),
(34, 'Третьяков Е.О.'),
(35, 'Коршунова А.А'),
(36, 'Aртамонова Л.В.');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subjects`
--

CREATE TABLE `teacher_subjects` (
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teacher_subjects`
--

INSERT INTO `teacher_subjects` (`teacher_id`, `subject_id`) VALUES
(1, 9),
(2, 4),
(2, 20),
(3, 15),
(4, 7),
(5, 10),
(6, 5),
(7, 15),
(7, 17),
(8, 10),
(9, 15),
(9, 16),
(10, 10),
(11, 10),
(12, 11),
(13, 3),
(14, 14),
(15, 9),
(16, 7),
(17, 3),
(18, 13),
(19, 1),
(19, 7),
(20, 11),
(21, 8),
(22, 10),
(23, 11),
(23, 19),
(24, 3),
(25, 6),
(26, 13),
(27, 11),
(27, 19),
(28, 4),
(29, 3),
(30, 2),
(31, 18),
(32, 6),
(33, 12),
(34, 12),
(35, 4),
(36, 10);

-- --------------------------------------------------------

--
-- Table structure for table `timetables`
--

CREATE TABLE `timetables` (
  `id` int(11) NOT NULL,
  `week_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `flags` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `timetables`
--

INSERT INTO `timetables` (`id`, `week_id`, `day`, `lesson_id`, `class_id`, `subject_id`, `room_id`, `teacher_id`, `comment`, `flags`) VALUES
(1, 4, '2022-01-10', 1, 1, 3, 148, 13, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(250) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `salt`, `name`) VALUES
(1, 'test', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', '', 'Иванов Иван Иванович');

-- --------------------------------------------------------

--
-- Table structure for table `weeks`
--

CREATE TABLE `weeks` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `start` date NOT NULL,
  `stop` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `weeks`
--

INSERT INTO `weeks` (`id`, `number`, `start`, `stop`) VALUES
(1, 52, '2021-12-27', '2022-01-02'),
(3, 1, '2022-01-03', '2022-01-09'),
(4, 2, '2022-01-10', '2022-01-16'),
(5, 3, '2022-01-17', '2022-01-23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rrr` (`room_id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`param`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rrr2` (`default_room_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD PRIMARY KEY (`teacher_id`,`subject_id`),
  ADD KEY `sid` (`subject_id`);

--
-- Indexes for table `timetables`
--
ALTER TABLE `timetables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `week_id` (`week_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_un` (`login`);

--
-- Indexes for table `weeks`
--
ALTER TABLE `weeks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `timetables`
--
ALTER TABLE `timetables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `weeks`
--
ALTER TABLE `weeks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `rrr` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `rrr2` FOREIGN KEY (`default_room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD CONSTRAINT `sid` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `tid` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);

--
-- Constraints for table `timetables`
--
ALTER TABLE `timetables`
  ADD CONSTRAINT `timetables_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `timetables_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`),
  ADD CONSTRAINT `timetables_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `timetables_ibfk_4` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`),
  ADD CONSTRAINT `timetables_ibfk_5` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `timetables_ibfk_6` FOREIGN KEY (`week_id`) REFERENCES `weeks` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;