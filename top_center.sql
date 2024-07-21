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


-- Dumping database structure for top_data
CREATE DATABASE IF NOT EXISTS `top_data` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `top_data`;

-- Dumping structure for table top_data.att
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
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.att: ~38 rows (approximately)
INSERT IGNORE INTO `att` (`id`, `st_id`, `tec_id`, `date`, `enter`, `exit`, `total`, `spc`, `aprove`, `processed`, `session_id`) VALUES
	(109, '53', '4', '2024-05-11', '20:35:56', '20:46:54', '00:10:58', '3', 0, 0, 6),
	(110, '53', '4', '2024-05-11', '20:49:27', '20:56:05', '00:06:38', '3', 0, 0, 6),
	(119, '2', '4', '2024-05-11', '23:23:11', '23:23:34', '00:00:23', '2', 0, 0, 7),
	(120, '3', '4', '2024-05-11', '23:23:11', '23:23:34', '00:00:23', '2', 0, 0, 7),
	(121, '54', '4', '2024-05-11', '23:23:11', '23:23:34', '00:00:23', '2', 0, 0, 7),
	(122, '1', '4', '2024-05-11', '23:23:11', '23:23:34', '00:00:23', '2', 0, 0, 7),
	(123, '2', '4', '2024-05-11', '23:37:12', '23:38:47', '00:01:35', '2', 0, 0, 7),
	(124, '3', '4', '2024-05-11', '23:37:12', '23:38:47', '00:01:35', '2', 0, 0, 7),
	(125, '4', '4', NULL, '10:21:10', NULL, NULL, '1', 0, 0, 10),
	(126, '7', '4', NULL, '10:21:10', NULL, NULL, '1', 0, 0, 10),
	(127, '6', '4', NULL, '10:21:10', NULL, NULL, '1', 0, 0, 10),
	(128, '53', '4', NULL, '10:21:15', '10:21:27', '00:00:12', '3', 0, 0, 6),
	(129, '2', '4', NULL, '10:21:40', '10:23:47', '00:02:07', '2', 0, 0, 7),
	(130, '3', '4', NULL, '10:21:41', '10:23:47', '00:02:06', '2', 0, 0, 7),
	(131, '54', '4', NULL, '10:21:41', '10:23:47', '00:02:06', '2', 0, 0, 7),
	(132, '1', '4', NULL, '10:21:41', '10:23:47', '00:02:06', '2', 0, 0, 7),
	(135, '4', '4', '2024-05-18', '10:33:27', '20:20:50', '09:47:23', '1', 0, 0, 10),
	(136, '7', '4', '2024-05-18', '10:33:27', '20:20:50', '09:47:23', '1', 0, 0, 10),
	(137, '2,3,54,1', '4', '2024-05-18', '18:45:04', '19:35:07', '00:50:03', '2', 0, 0, 7),
	(138, '4,6,7', '4', '2024-05-18', '20:51:42', '23:51:49', '03:00:07', '3', 0, 0, 10),
	(139, '7,6', '4', '2024-05-24', '06:16:55', '06:26:46', '00:09:51', '3', 0, 0, 10),
	(140, '7,6', '4', '2024-05-24', '06:27:11', '06:31:12', '00:04:01', '3', 0, 0, 10),
	(141, '2,1', '3', '2024-05-24', '06:50:12', '19:50:12', '13:00:00', '4', 0, 0, 13),
	(142, '2,1', '3', '2024-05-24', '20:20:48', '20:21:13', '00:00:25', '3', 0, 0, 13),
	(143, '53,3,54', '4', '2024-05-25', '08:14:37', '08:30:26', '00:15:49', '3', 0, 0, 14),
	(145, '53,3,54', '4', '2024-05-25', '10:20:24', '10:22:06', '00:01:42', '3', 0, 0, 14),
	(146, '53,3,54', '4', '2024-05-25', '09:21:47', '10:21:55', '01:00:08', '3', 0, 0, 14),
	(148, '3,54,1', '4', '2024-05-25', '19:56:55', '19:57:18', '00:00:23', '2', 0, 0, 7),
	(149, '53,3,54', '4', '2024-05-25', '20:10:08', '20:10:10', '00:00:02', '3', 1, 0, 14),
	(150, '53,3,54', '4', '2024-05-25', '20:10:31', '20:11:11', '00:00:40', '1', 1, 0, 14),
	(151, '48,55', '4', '2024-06-20', '12:40:27', '15:40:33', '03:00:06', '1', 1, 1, 4),
	(152, '2,1', '4', '2024-06-20', '12:40:50', '13:40:53', '01:00:03', '10', 0, 0, 17),
	(153, '2,1', '4', '2024-06-21', '08:16:11', '08:16:11', '01:00:00', '12', 0, 0, 16),
	(154, '48,55', '4', '2024-07-02', '11:32:20', '11:33:03', '00:00:43', '1', 0, 0, 4),
	(155, '48,12', '4', '2024-07-02', '11:33:08', '11:33:26', '00:00:18', '4', 0, 0, 28),
	(156, '48,12', '4', '2024-07-02', '11:33:29', '11:34:02', '00:00:33', '1', 0, 0, 28),
	(157, '48,12', '4', '2024-07-02', '14:06:44', '14:10:37', '00:03:53', '3', 0, 0, 28),
	(158, '4,7,56', '11', '2024-07-09', '11:58:50', '11:59:07', '00:00:17', '19', 0, 0, 35);

-- Dumping structure for table top_data.classes
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.classes: ~12 rows (approximately)
INSERT IGNORE INTO `classes` (`id`, `name`, `type`) VALUES
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

-- Dumping structure for table top_data.income
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.income: ~12 rows (approximately)
INSERT IGNORE INTO `income` (`id`, `date`, `cashier`, `payer`, `amount`, `student_id`, `session_id`, `material`, `notes`) VALUES
	(14, '2024-06-20 00:00:00', 'السكرتيرة', 'ماهر', 500, 4, 15, '0', 'دفعة اولى'),
	(15, '2024-06-21 00:00:00', 'الإدارة', 'ابراهيم', 1000, 4, 15, '0', 'الدفعة الثانية لدورة اشتراك شهري - توجيهي'),
	(16, '2024-06-18 00:00:00', 'السكرتيرة', 'ماهر', 300, 4, 15, '0', 'الدفعة المسبقة'),
	(17, '2024-06-21 00:00:00', 'السكرتيرة', 'فاطمة', 40, 3, 19, '0', 'دفعة (دورة خاصة لفحص الاسعار 4)'),
	(18, '2024-07-02 00:00:00', 'السكرتيرة', 'محمد علي ', 1500, 12, 27, '0', ''),
	(19, '2024-07-02 00:00:00', 'السكرتيرة', 'تحمد', 550, 12, 8, '0', 'تبقى 50'),
	(20, '2024-07-02 00:00:00', 'السكرتيرة', 'ابراهيم', 50, 12, 8, '0', ''),
	(21, '2024-07-02 00:00:00', 'الإدارة', 'سها', 250, 48, 27, '0', ''),
	(22, '2024-07-03 00:00:00', 'الإدارة', 'بلاب', 50, 48, 27, '0', ''),
	(23, '2024-07-01 00:00:00', 'السكرتيرة', 'اتفل', 1500, 48, 27, '0', ''),
	(24, '2024-07-02 00:00:00', 'السكرتيرة', 'تت', 850, 54, 14, '0', ''),
	(25, '2024-07-09 00:00:00', 'الإدارة', 'تلراتن', 200, 56, 35, '0', '');

-- Dumping structure for table top_data.outcome
CREATE TABLE IF NOT EXISTS `outcome` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `receiver` varchar(50) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT 0,
  `notes` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.outcome: ~4 rows (approximately)
INSERT IGNORE INTO `outcome` (`id`, `date`, `receiver`, `type`, `amount`, `notes`) VALUES
	(1, '2024-05-22 00:00:00', '0', 'مستلزمات مكتب', 100, 'شحن كرت'),
	(2, '2024-07-08 00:00:00', '4', 'أجور', 400, 'دفعة دورة فحوصات'),
	(3, '2024-07-09 00:00:00', '11', 'أجور', 100, ''),
	(4, '2024-07-09 00:00:00', '11', 'أجور', 100, 'دفعة');

-- Dumping structure for table top_data.schools
CREATE TABLE IF NOT EXISTS `schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `FK_schools_school_types` (`type`),
  CONSTRAINT `FK_schools_school_types` FOREIGN KEY (`type`) REFERENCES `school_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.schools: ~5 rows (approximately)
INSERT IGNORE INTO `schools` (`id`, `name`, `type`) VALUES
	(1, 'ذكور السموع الاساسية', 1),
	(2, 'بنات السموع  الثانوية', 1),
	(3, 'ذكور ابن خلدون', 2),
	(4, 'البخاري', 2),
	(5, 'مدرسة بنات خليل الوزير', 1);

-- Dumping structure for table top_data.school_types
CREATE TABLE IF NOT EXISTS `school_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.school_types: ~2 rows (approximately)
INSERT IGNORE INTO `school_types` (`id`, `type`) VALUES
	(1, 'مدرسة حكومية'),
	(2, 'مدرسة خاصة');

-- Dumping structure for table top_data.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_name` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  `material` varchar(255) DEFAULT NULL,
  `hours` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `is_group` int(1) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `FK_sessions_sessions_status` (`status`),
  CONSTRAINT `FK_sessions_sessions_status` FOREIGN KEY (`status`) REFERENCES `sessions_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.sessions: ~28 rows (approximately)
INSERT IGNORE INTO `sessions` (`id`, `session_name`, `type`, `material`, `hours`, `price`, `is_group`, `status`) VALUES
	(4, 'testing web - حقيبة مدرسية', 'حقيبة مدرسية', '1,2,5', '5', 600.00, 0, 1),
	(6, 'mutaz - دورة خاصة', 'دورة خاصة', '3', '0', 400.00, 0, 1),
	(7, 'دورة خاصة سبيشال', 'دورة خاصة', '12', '0', 1200.00, 1, 1),
	(8, 'طلاب صف 5 ذكور السموع الاساسية', 'حقيبة مدرسية', '1,2,3,4,5', '20', 600.00, 1, 1),
	(9, 'تست السعر لطالب', 'اشتراك شهري', '1,2,3,4,5', '10', 1400.00, 0, 1),
	(10, 'اللغة الانجليزية ثانوي دورة خاصة', 'دورة خاصة', '10', '10', 1500.00, 1, 1),
	(13, 'اول ثانوي دورة خاصة', 'دورة خاصة', '11', '0', 400.00, 1, 1),
	(14, 'اشتراك شهري صف رابع', 'اشتراك شهري', '1,2,3,4,5', '12', 900.00, 1, 1),
	(15, 'اشتراك شهري - توجيهي -', 'اشتراك شهري', '1,2,3,4,5,6', '20', 1800.00, 1, 1),
	(16, 'اختبار نموذج متعدد الخطوات', 'دورة خاصة', '11,12', '0', 1200.00, 1, 1),
	(17, 'اول ثانوي علمي', 'اشتراك شهري', '9,10,11,12', '0', 1000.00, 1, 1),
	(18, 'دورة خاصة جماعية تست 2', 'دورة خاصة', '10', '2', 50.00, 1, 1),
	(19, 'دورة خاصة لفحص الاسعار 4', 'دورة خاصة', '3', '2', 40.00, 1, 1),
	(20, 'تست تسجيل سعر كامل وسعر منفصل للطلاب', 'دورة خاصة', '12', '3', 300.00, 1, 1),
	(21, 'فحص تقسيم السعر للمعلمين', 'دورة خاصة', '11', '0', 600.00, 1, 1),
	(24, 'teachers price2', 'دورة خاصة', '10', '3', 800.00, 1, 1),
	(25, 'تست نسبة المعلم لكل سشن', 'دورة خاصة', '12', '0', 300.00, 0, 1),
	(26, 'تست سعر سشن رقم 2', 'اشتراك شهري', '11,12', '12', 600.00, 1, 1),
	(27, 'تست ثالث', 'حقيبة مدرسية', '1,2,3,4', '0', 3000.00, 1, 1),
	(28, 'دورة عبري ابتدائي صف خامس', 'حقيبة مدرسية', '1,2,3,4', '20', 1200.00, 1, 1),
	(29, 'فرنسي اول ثانوي', 'دورة خاصة', '17', '10', 1200.00, 1, 1),
	(30, 'استاذين لنفس الدورة', 'دورة خاصة', '9,10', '6', 300.00, 1, 1),
	(31, 'فحوصات', 'اشتراك شهري', '9,10,11', '20', 1800.00, 1, 1),
	(32, 'فحوصات', 'اشتراك شهري', '9,10,11', '20', 1800.00, 1, 1),
	(33, 'فحوصات', 'اشتراك شهري', '9,10,11', '20', 1800.00, 1, 1),
	(34, 'فحوصات', 'اشتراك شهري', '9,10,11', '20', 1800.00, 1, 1),
	(35, 'دورة توب', 'اشتراك شهري', '10,11,19', '12', 1000.00, 1, 1),
	(60, 'ك1', 'اشتراك شهري', '1,2,3', '12', 1200.00, 1, 1);

-- Dumping structure for table top_data.sessions_status
CREATE TABLE IF NOT EXISTS `sessions_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.sessions_status: ~2 rows (approximately)
INSERT IGNORE INTO `sessions_status` (`id`, `name`) VALUES
	(1, 'جاري'),
	(2, 'منتهي'),
	(3, 'متوقف');

-- Dumping structure for table top_data.session_students
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

-- Dumping data for table top_data.session_students: ~67 rows (approximately)
INSERT IGNORE INTO `session_students` (`session_id`, `student_id`, `session_cost`, `total_payments`, `payment_status`, `hours`) VALUES
	(4, 48, 600.00, 0.00, 'not paid', 2.00),
	(4, 55, 600.00, 0.00, 'not paid', 5.00),
	(6, 53, 400.00, 0.00, 'not paid', 0.00),
	(7, 1, 1200.00, 0.00, 'not paid', 0.00),
	(7, 2, 1200.00, 0.00, 'not paid', 0.00),
	(7, 3, 1200.00, 0.00, 'not paid', 0.00),
	(7, 54, 1200.00, 0.00, 'not paid', 0.00),
	(8, 12, 600.00, 600.00, 'paid', 0.00),
	(8, 13, 600.00, 0.00, 'not paid', 0.00),
	(8, 15, 600.00, 0.00, 'not paid', 0.00),
	(8, 22, 600.00, 0.00, 'not paid', 0.00),
	(8, 48, 600.00, 0.00, 'not paid', 0.00),
	(8, 53, 600.00, 0.00, 'not paid', 0.00),
	(9, 1, 1400.00, 0.00, 'not paid', 0.00),
	(10, 4, 1500.00, 0.00, 'not paid', 10.00),
	(10, 6, 1500.00, 0.00, 'not paid', 10.00),
	(10, 7, 1500.00, 0.00, 'not paid', 10.00),
	(13, 1, 400.00, 0.00, 'not paid', 0.00),
	(13, 2, 400.00, 0.00, 'not paid', 0.00),
	(14, 3, 900.00, 0.00, 'not paid', 0.00),
	(14, 53, 900.00, 0.00, 'not paid', 0.00),
	(14, 54, 900.00, 850.00, 'partially paid', 0.00),
	(15, 4, 1800.00, 1800.00, 'paid', 0.00),
	(15, 6, 1800.00, 0.00, 'not paid', 0.00),
	(15, 7, 1800.00, 0.00, 'not paid', 0.00),
	(16, 1, 1200.00, 0.00, 'not paid', 0.00),
	(16, 2, 1200.00, 0.00, 'not paid', 0.00),
	(17, 1, 1000.00, 0.00, 'not paid', 0.00),
	(17, 2, 1000.00, 0.00, 'not paid', 0.00),
	(18, 1, 50.00, 0.00, 'not paid', 0.00),
	(18, 2, 50.00, 0.00, 'not paid', 0.00),
	(19, 3, 40.00, 40.00, 'paid', 0.00),
	(19, 53, 40.00, 0.00, 'not paid', 0.00),
	(19, 54, 40.00, 0.00, 'not paid', 0.00),
	(20, 1, 150.00, 0.00, 'not paid', 0.00),
	(20, 2, 150.00, 0.00, 'not paid', 0.00),
	(21, 4, 300.00, 0.00, 'not paid', 0.00),
	(21, 6, 300.00, 0.00, 'not paid', 0.00),
	(24, 4, 400.00, 0.00, 'not paid', 0.00),
	(24, 6, 400.00, 0.00, 'not paid', 0.00),
	(25, 2, 300.00, 0.00, 'not paid', 0.00),
	(26, 1, 300.00, 0.00, 'not paid', 0.00),
	(26, 2, 300.00, 0.00, 'not paid', 0.00),
	(27, 12, 600.00, 1500.00, 'paid', 0.00),
	(27, 13, 600.00, 0.00, 'not paid', 0.00),
	(27, 15, 600.00, 0.00, 'not paid', 0.00),
	(27, 22, 600.00, 0.00, 'not paid', 0.00),
	(27, 48, 600.00, 1800.00, 'paid', 0.00),
	(28, 12, 600.00, 0.00, 'not paid', 0.00),
	(28, 48, 600.00, 0.00, 'not paid', 0.00),
	(29, 1, 600.00, 0.00, 'not paid', 0.00),
	(29, 2, 600.00, 0.00, 'not paid', 0.00),
	(30, 4, 100.00, 0.00, 'not paid', 0.00),
	(30, 6, 100.00, 0.00, 'not paid', 0.00),
	(30, 7, 100.00, 0.00, 'not paid', 0.00),
	(34, 4, 600.00, 0.00, 'not paid', 0.00),
	(34, 6, 600.00, 0.00, 'not paid', 0.00),
	(34, 7, 600.00, 0.00, 'not paid', 0.00),
	(35, 4, 250.00, 0.00, 'not paid', 0.00),
	(35, 6, 250.00, 0.00, 'not paid', 0.00),
	(35, 7, 250.00, 0.00, 'not paid', 0.00),
	(35, 56, 250.00, 200.00, 'partially paid', 0.00),
	(60, 12, 240.00, 0.00, 'not paid', 0.00),
	(60, 13, 240.00, 0.00, 'not paid', 0.00),
	(60, 15, 240.00, 0.00, 'not paid', 0.00),
	(60, 22, 240.00, 0.00, 'not paid', 0.00),
	(60, 48, 240.00, 0.00, 'not paid', 0.00);

-- Dumping structure for table top_data.session_teachers
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

-- Dumping data for table top_data.session_teachers: ~36 rows (approximately)
INSERT IGNORE INTO `session_teachers` (`session_id`, `teacher_id`, `session_amount`, `paid_amount`, `payment_status`, `percentage`) VALUES
	(4, 3, 0, 0, 'not paid', 0.50),
	(4, 4, 0, 0, 'not paid', 0.50),
	(6, 3, 0, 0, 'not paid', 0.50),
	(7, 4, 0, 0, 'not paid', 0.50),
	(8, 2, 0, 0, 'not paid', 0.50),
	(9, 2, 0, 0, 'not paid', 0.50),
	(10, 4, 0, 0, 'not paid', 0.50),
	(13, 3, 0, 0, 'not paid', 0.50),
	(13, 4, 0, 0, 'not paid', 0.50),
	(14, 3, 0, 0, 'not paid', 0.50),
	(14, 4, 0, 0, 'not paid', 0.50),
	(15, 2, 0, 0, 'not paid', 0.50),
	(15, 4, 0, 0, 'not paid', 0.50),
	(16, 4, 0, 0, 'not paid', 0.50),
	(17, 3, 0, 0, 'not paid', 0.50),
	(17, 4, 0, 0, 'not paid', 0.50),
	(18, 3, 0, 0, 'not paid', 0.50),
	(19, 4, 0, 0, 'not paid', 0.50),
	(20, 4, 0, 0, 'not paid', 0.50),
	(24, 4, 400, 0, 'not paid', 0.50),
	(25, 4, 225, 0, 'not paid', 0.75),
	(26, 3, 450, 0, 'not paid', 0.75),
	(26, 4, 300, 0, 'not paid', 0.50),
	(27, 3, 1500, 0, 'not paid', 0.50),
	(28, 2, 0, 0, 'not paid', 0.00),
	(28, 3, 0, 0, 'not paid', 0.00),
	(28, 4, 900, 0, 'not paid', 0.75),
	(29, 4, 600, 0, 'not paid', 0.50),
	(30, 3, 150, 0, 'not paid', 0.50),
	(30, 4, 150, 0, 'not paid', 0.50),
	(34, 3, 300, 0, 'not paid', 0.50),
	(34, 4, 600, 400, 'partially paid', 0.50),
	(35, 4, 333, 100, 'partially paid', 0.50),
	(35, 11, 0, 0, 'not paid', 0.50),
	(60, 3, 400, 0, 'not paid', 0.50),
	(60, 11, 200, 100, 'partially paid', 0.50);

-- Dumping structure for table top_data.spc
CREATE TABLE IF NOT EXISTS `spc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `class_type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_class_type` (`class_type`),
  CONSTRAINT `fk_class_type` FOREIGN KEY (`class_type`) REFERENCES `classes` (`type`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.spc: ~15 rows (approximately)
INSERT IGNORE INTO `spc` (`id`, `name`, `class_type`) VALUES
	(1, 'اللغة العربية اساسي', 1),
	(2, 'اللغة الانجليزية اساسي', 1),
	(3, 'الرياضيات اساسي', 1),
	(4, 'العلوم اساسي', 1),
	(5, 'اللغة العربية اعدادي', 2),
	(6, 'اللغة الانجليزية اعدادي', 2),
	(7, 'الرياضيات اعدادي', 2),
	(8, 'العلوم اعدادي', 2),
	(9, 'اللغة العربية ثانوي', 3),
	(10, 'اللغة الانجليزية ثانوي', 3),
	(11, 'كيمياء ثانوي', 3),
	(12, 'أحياء ثانوي', 3),
	(17, 'فرنسي', 3),
	(18, 'عبري اعدادي', 2),
	(19, 'عبري ثانوي', 3);

-- Dumping structure for table top_data.students
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
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.students: ~15 rows (approximately)
INSERT IGNORE INTO `students` (`id`, `name`, `tec_id`, `phone`, `class`, `InSess`, `att_id`, `img`, `school`) VALUES
	(1, 'محمد احمد', '4', '0565435869', 11, 0, 0, 'student.jpg', 1),
	(2, 'علي', '4', '56465498', 11, 0, 0, 'student.jpg', 1),
	(3, 'فاطمة', '4', '051', 4, 0, 0, 'student.jpg', 1),
	(4, 'ابراهيم', '1', '987654321', 12, 0, 0, 'student.jpg', 1),
	(6, 'صابرين', '2', '2147483647', 12, 0, 0, 'file-65aacebd2455e6.11884650.jpg', 1),
	(7, 'امل', '3', '854689575', 12, 0, 0, 'student.jpg', 1),
	(12, 'معتز', '4', '541651', 5, 0, 0, 'student.jpg', 1),
	(13, 'نهيل', '4', '256894', 5, 0, 0, 'student.jpg', 1),
	(15, 'ايمن', '4', '0595534652', 5, 0, 0, 'student.jpg', 1),
	(22, 'محمود', '3,4', '0522222321', 5, 0, 0, 'student.jpg', 1),
	(48, 'ايسر', '3', '0592221592', 5, 0, 0, 'student.jpg', 1),
	(53, 'سالم', '4', '123456120', 4, 0, 0, 'student.jpg', 1),
	(54, 'ماهر', '4', '0595534652', 4, 0, 0, 'student.jpg', 1),
	(55, 'نادر اسماعيل', '0', '0535534652', 7, 0, 0, 'student.jpg', 1),
	(56, 'طالب توب', '0', '9876543210', 12, 0, 0, 'student.jpg', 4);

-- Dumping structure for table top_data.teacher
CREATE TABLE IF NOT EXISTS `teacher` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `user` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `img` text NOT NULL DEFAULT 'super.jpg',
  `role` int(3) NOT NULL,
  `att_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.teacher: ~5 rows (approximately)
INSERT IGNORE INTO `teacher` (`id`, `user`, `password`, `name`, `img`, `role`, `att_id`) VALUES
	(1, 'admin@top.com', '123', 'محمد الدبس', 'super.jpg', 1, 0),
	(2, 'mohmad@top.com', '8520', 'محمد سالم', 'super.jpg', 2, 0),
	(3, 'fatima.top@top.com', '123', 'فاطمة ابو مرخية', 'super.jpg', 2, 0),
	(4, 'maher@test.com', '123123', 'ماهر سلامين', 'super.jpg', 2, 0),
	(11, 'teacher@top.ps', '123', 'معلمك توب', 'file-668cf7ce663aa0.99604417toppng', 2, 0);

-- Dumping structure for table top_data.teacher_specializations
CREATE TABLE IF NOT EXISTS `teacher_specializations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `spec` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `teacher_specializations_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table top_data.teacher_specializations: ~8 rows (approximately)
INSERT IGNORE INTO `teacher_specializations` (`id`, `teacher_id`, `spec`) VALUES
	(1, 3, 2),
	(2, 3, 1),
	(7, 4, 11),
	(8, 4, 12),
	(12, 4, 10),
	(13, 3, 4),
	(14, 3, 9),
	(15, 11, 3);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
