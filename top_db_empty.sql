-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table top_data_29/7.att
CREATE TABLE IF NOT EXISTS `att` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `st_id` varchar(50) NOT NULL DEFAULT '',
  `tec_id` varchar(50) NOT NULL DEFAULT '',
  `date` date DEFAULT current_timestamp(),
  `enter` time DEFAULT current_timestamp(),
  `exit` time DEFAULT NULL,
  `total` time DEFAULT NULL,
  `spc` varchar(50) NOT NULL DEFAULT '',
  `aprove` int(3) NOT NULL DEFAULT 0,
  `processed` int(3) NOT NULL DEFAULT 0,
  `session_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `att_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.att: ~0 rows (approximately)
DELETE FROM `att`;

-- Dumping structure for table top_data_29/7.classes
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.classes: ~12 rows (approximately)
DELETE FROM `classes`;
INSERT INTO `classes` (`id`, `name`, `type`) VALUES
	(1, 'الصف الأول', 1),
	(2, 'الصف الثاني', 1),
	(3, 'الصف الثالث', 1),
	(4, 'الصف الرابع', 1),
	(5, 'الصف الخامس', 1),
	(6, 'الصف السادس', 1),
	(7, 'الصف السابع', 2),
	(8, 'الصف الثامن', 2),
	(9, 'الصف التاسع', 2),
	(10, 'الصف العاشر', 3),
	(11, 'الصف الحادي عشر', 3),
	(12, 'الصف الثاني عشر', 3);

-- Dumping structure for table top_data_29/7.income
CREATE TABLE IF NOT EXISTS `income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `cashier` varchar(50) NOT NULL DEFAULT '0',
  `payer` varchar(50) NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT 0,
  `student_id` int(11) NOT NULL DEFAULT 0,
  `session_id` int(11) NOT NULL DEFAULT 0,
  `material` varchar(50) NOT NULL DEFAULT '0',
  `notes` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_income_students` (`student_id`),
  KEY `FK_income_sessions` (`session_id`),
  CONSTRAINT `FK_income_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.income: ~0 rows (approximately)
DELETE FROM `income`;

-- Dumping structure for table top_data_29/7.outcome
CREATE TABLE IF NOT EXISTS `outcome` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `receiver` varchar(50) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT 0,
  `notes` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.outcome: ~0 rows (approximately)
DELETE FROM `outcome`;

-- Dumping structure for table top_data_29/7.schools
CREATE TABLE IF NOT EXISTS `schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `FK_schools_school_types` (`type`),
  CONSTRAINT `FK_schools_school_types` FOREIGN KEY (`type`) REFERENCES `school_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.schools: ~0 rows (approximately)
DELETE FROM `schools`;

-- Dumping structure for table top_data_29/7.school_types
CREATE TABLE IF NOT EXISTS `school_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.school_types: ~2 rows (approximately)
DELETE FROM `school_types`;
INSERT INTO `school_types` (`id`, `type`) VALUES
	(1, 'مدرسة حكومية'),
	(2, 'مدرسة خاصة');

-- Dumping structure for table top_data_29/7.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_name` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  `material` varchar(255) DEFAULT NULL,
  `hours` varchar(50) DEFAULT NULL,
  `meetings` varchar(50) DEFAULT '0',
  `price` decimal(10,2) DEFAULT NULL,
  `is_group` int(1) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `FK_sessions_sessions_status` (`status`),
  CONSTRAINT `FK_sessions_sessions_status` FOREIGN KEY (`status`) REFERENCES `sessions_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.sessions: ~0 rows (approximately)
DELETE FROM `sessions`;

-- Dumping structure for table top_data_29/7.sessions_status
CREATE TABLE IF NOT EXISTS `sessions_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.sessions_status: ~3 rows (approximately)
DELETE FROM `sessions_status`;
INSERT INTO `sessions_status` (`id`, `name`) VALUES
	(1, 'جاري'),
	(2, 'منتهي'),
	(3, 'متوقف');

-- Dumping structure for table top_data_29/7.session_students
CREATE TABLE IF NOT EXISTS `session_students` (
  `session_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `session_cost` decimal(10,2) DEFAULT NULL,
  `total_payments` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(20) NOT NULL DEFAULT 'not paid',
  `hours` decimal(5,2) DEFAULT 0.00,
  PRIMARY KEY (`session_id`,`student_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `session_students_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`),
  CONSTRAINT `session_students_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.session_students: ~0 rows (approximately)
DELETE FROM `session_students`;

-- Dumping structure for table top_data_29/7.session_teachers
CREATE TABLE IF NOT EXISTS `session_teachers` (
  `session_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `session_amount` int(11) NOT NULL DEFAULT 0,
  `paid_amount` int(11) NOT NULL DEFAULT 0,
  `payment_status` varchar(50) NOT NULL DEFAULT 'not paid',
  `percentage` decimal(2,2) NOT NULL DEFAULT 0.50,
  PRIMARY KEY (`session_id`,`teacher_id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `session_teachers_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`),
  CONSTRAINT `session_teachers_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.session_teachers: ~0 rows (approximately)
DELETE FROM `session_teachers`;

-- Dumping structure for table top_data_29/7.spc
CREATE TABLE IF NOT EXISTS `spc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `class_type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_class_type` (`class_type`),
  CONSTRAINT `fk_class_type` FOREIGN KEY (`class_type`) REFERENCES `classes` (`type`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.spc: ~0 rows (approximately)
DELETE FROM `spc`;

-- Dumping structure for table top_data_29/7.students
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `tec_id` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(10) NOT NULL DEFAULT '',
  `class` int(11) NOT NULL DEFAULT 1,
  `InSess` int(11) NOT NULL DEFAULT 0,
  `att_id` int(11) NOT NULL DEFAULT 0,
  `img` text NOT NULL DEFAULT 'student.jpg',
  `school` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `FK_students_classes` (`class`),
  KEY `FK_students_schools` (`school`),
  CONSTRAINT `FK_students_classes` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_students_schools` FOREIGN KEY (`school`) REFERENCES `schools` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.students: ~0 rows (approximately)
DELETE FROM `students`;

-- Dumping structure for table top_data_29/7.teacher
CREATE TABLE IF NOT EXISTS `teacher` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `user` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `img` text NOT NULL DEFAULT 'super.jpg',
  `role` int(3) NOT NULL,
  `att_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.teacher: ~1 rows (approximately)
DELETE FROM `teacher`;
INSERT INTO `teacher` (`id`, `user`, `password`, `name`, `img`, `role`, `att_id`) VALUES
	(1, 'admin@top.com', '123', 'محمد الدبس', 'super.jpg', 1, 0);

-- Dumping structure for table top_data_29/7.teacher_specializations
CREATE TABLE IF NOT EXISTS `teacher_specializations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `spec` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `teacher_specializations_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data_29/7.teacher_specializations: ~0 rows (approximately)
DELETE FROM `teacher_specializations`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
