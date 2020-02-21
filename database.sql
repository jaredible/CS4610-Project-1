CREATE DATABASE IF NOT EXISTS `university` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `university`;

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `course_number` varchar(10) NOT NULL,
  `course_name` varchar(50) DEFAULT '',
  `credit_hours` int(1) unsigned DEFAULT '0',
  `department` varchar(10) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `course` (`course_number`, `course_name`, `credit_hours`, `department`) VALUES
('CS1310', 'Intro to Computer Science', 4, 'CS'),
('CS3320', 'Data Structures', 4, 'CS'),
('CS3380', 'Database', 3, 'CS'),
('MATH2410', 'Discrete Mathematics', 3, 'MATH');

DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
  `section_identifier` int(10) unsigned NOT NULL,
  `course_number` varchar(10) NOT NULL,
  `semester` varchar(10) DEFAULT '',
  `year` varchar(10) DEFAULT '',
  `instructor` varchar(10) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `grade_report`;
CREATE TABLE IF NOT EXISTS `grade_report` (
  `student_number` int(10) unsigned NOT NULL,
  `section_identifier` int(10) NOT NULL,
  `grade` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `section` (`section_identifier`, `course_number`, `semester`, `year`, `instructor`) VALUES
(85, 'MATH2410', 'Fall', '07', 'King'),
(92, 'CS1310', 'Fall', '07', 'Anderson'),
(102, 'CS3320', 'Spring', '08', 'Knuth'),
(112, 'MATH2410', 'Fall', '08', 'Chang'),
(119, 'CS1310', 'Fall', '08', 'Anderson'),
(135, 'CS3380', 'Fall', '08', 'Stone');

INSERT INTO `grade_report` (`student_number`, `section_identifier`, `grade`) VALUES
(17, 112, 'B'),
(17, 119, 'C'),
(8, 85, 'A'),
(8, 92, 'A'),
(8, 102, 'B'),
(8, 135, 'A');

DROP TABLE IF EXISTS `prerequisite`;
CREATE TABLE IF NOT EXISTS `prerequisite` (
  `course_number` varchar(10) NOT NULL,
  `prerequisite_number` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `prerequisite` (`course_number`, `prerequisite_number`) VALUES
('CS3380', 'CS3320'),
('CS3380', 'MATH2410'),
('CS3320', 'CS1310');

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `student_number` int(10) unsigned NOT NULL,
  `name` varchar(30) DEFAULT '',
  `class` int(1) unsigned DEFAULT '0',
  `major` varchar(10) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `student` (`student_number`, `name`, `class`, `major`) VALUES
(8, 'Brown', 2, 'CS'),
(17, 'Smith', 1, 'CS');

--
-- Indexes for table `course`
--
ALTER TABLE `course`
 ADD PRIMARY KEY (`course_number`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
 ADD PRIMARY KEY (`section_identifier`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
 ADD PRIMARY KEY (`student_number`);