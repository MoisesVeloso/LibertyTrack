-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2024 at 02:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `libertytrack`
--
CREATE DATABASE IF NOT EXISTS `libertytrack` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `libertytrack`;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `date`, `time`, `created_at`) VALUES
(0, 'Video Conferencing', 'asdasd', '0200-03-09', '19:27:00', '2024-12-11 11:27:02');

-- --------------------------------------------------------

--
-- Table structure for table `inmates`
--

CREATE TABLE `inmates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `case_detail` text NOT NULL,
  `date_admitted` date DEFAULT NULL,
  `date_release` date DEFAULT NULL,
  `case_number` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `image_data_path` varchar(255) DEFAULT NULL,
  `verify_image` varchar(255) DEFAULT NULL,
  `status` enum('detained','released','reviewing','transferred') NOT NULL DEFAULT 'detained',
  `suffix` varchar(10) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `arresting_officers` varchar(255) DEFAULT NULL,
  `ioc` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_time_arrested` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmates`
--

INSERT INTO `inmates` (`id`, `firstname`, `lastname`, `middlename`, `gender`, `case_detail`, `date_admitted`, `date_release`, `case_number`, `image_path`, `image_data_path`, `verify_image`, `status`, `suffix`, `birthday`, `emergency_contact`, `arresting_officers`, `ioc`, `address`, `date_time_arrested`) VALUES
(1, 'Marcus', 'Bennett', 'K.', 'Male', 'Vandalism and Property Damage', '2023-01-15', '2024-07-15', 'JV011', NULL, NULL, NULL, 'detained', NULL, '2007-06-12', '09123456789', NULL, NULL, NULL, NULL),
(2, 'Sophia', 'Parker', 'M.', 'Female', 'Shoplifting', '2024-02-01', '2024-08-01', 'JV012', NULL, NULL, NULL, 'detained', NULL, '2007-09-23', '09234567890', NULL, NULL, NULL, NULL),
(3, 'Lucas', 'Foster', 'R.', 'Male', 'Gang Activity', '2024-01-20', '2024-09-20', 'JV013', NULL, NULL, NULL, 'detained', NULL, '2006-11-05', '09345678901', NULL, NULL, NULL, NULL),
(4, 'Isabella', 'Cooper', 'L.', 'Female', 'Drug Possession', '2024-02-10', '2024-08-10', 'JV014', NULL, NULL, NULL, 'released', NULL, '2007-03-18', '09456789012', NULL, NULL, NULL, NULL),
(5, 'Ethan', 'Hayes', 'J.', 'Male', 'Assault', '2024-01-25', '2024-07-25', 'JV015', NULL, NULL, NULL, 'detained', NULL, '2006-12-30', '09567890123', NULL, NULL, NULL, NULL),
(6, 'Adrian', 'Sullivan', 'P.', 'Male', 'Cybercrime', '2024-01-05', '2025-01-05', 'YA011', NULL, NULL, NULL, 'detained', NULL, '2001-07-14', '09678901234', NULL, NULL, NULL, NULL),
(7, 'Victoria', 'Morgan', 'N.', 'Female', 'Identity Theft', '2024-01-10', '2024-09-10', 'YA012', NULL, NULL, NULL, 'detained', NULL, '2000-04-28', '09789012345', NULL, NULL, NULL, NULL),
(8, 'Cameron', 'Walsh', 'D.', 'Male', 'Armed Robbery', '2024-02-05', '2025-02-05', 'YA013', NULL, NULL, NULL, 'transferred', NULL, '2002-08-19', '09890123456', NULL, NULL, NULL, NULL),
(9, 'Olivia', 'Griffin', 'E.', 'Female', 'Drug Trafficking', '2024-01-15', '2024-10-15', 'YA014', NULL, NULL, NULL, 'detained', NULL, '2001-02-11', '09901234567', NULL, NULL, NULL, NULL),
(10, 'Nathan', 'Harrison', 'B.', 'Male', 'Assault and Battery', '2024-02-01', '2024-11-01', 'YA015', NULL, NULL, NULL, 'detained', NULL, '2000-11-07', '09012345678', NULL, NULL, NULL, NULL),
(11, 'Maxwell', 'Fletcher', 'C.', 'Male', 'Grand Theft Auto', '2024-01-05', '2025-07-05', 'AD011', NULL, NULL, NULL, 'detained', NULL, '1994-08-22', '09123456780', NULL, NULL, NULL, NULL),
(12, 'Rachel', 'Warren', 'H.', 'Female', 'Financial Fraud', '2024-01-10', '2025-01-10', 'AD012', NULL, NULL, NULL, 'detained', NULL, '1993-05-16', '09234567891', NULL, NULL, NULL, NULL),
(13, 'Vincent', 'Palmer', 'T.', 'Male', 'Drug Distribution', '2024-02-05', '2026-02-05', 'AD013', NULL, NULL, NULL, 'transferred', NULL, '1992-12-03', '09345678902', NULL, NULL, NULL, NULL),
(14, 'Samantha', 'Brooks', 'M.', 'Female', 'Embezzlement', '2024-01-15', '2025-01-15', 'AD014', NULL, NULL, NULL, 'detained', NULL, '1995-03-29', '09456789013', NULL, NULL, NULL, NULL),
(15, 'Derek', 'Spencer', 'L.', 'Male', 'Aggravated Assault', '2024-02-01', '2025-08-01', 'AD015', NULL, NULL, NULL, 'detained', NULL, '1991-09-14', '09567890124', NULL, NULL, NULL, NULL),
(16, 'Leonard', 'Blackwood', 'R.', 'Male', 'Murder Second Degree', '2024-01-05', '2034-01-05', 'MA011', NULL, NULL, NULL, 'detained', NULL, '1980-06-25', '09678901235', NULL, NULL, NULL, NULL),
(17, 'Catherine', 'Winters', 'S.', 'Female', 'Human Trafficking', '2024-01-10', '2029-01-10', 'MA012', NULL, NULL, NULL, 'detained', NULL, '1975-10-08', '09789012346', NULL, NULL, NULL, NULL),
(18, 'Gregory', 'Pearson', 'F.', 'Male', 'Armed Robbery', '2024-02-05', '2034-02-05', 'MA013', NULL, NULL, NULL, 'transferred', NULL, '1978-02-17', '09890123457', NULL, NULL, NULL, NULL),
(19, 'Marilyn', 'Crawford', 'D.', 'Female', 'Drug Manufacturing', '2024-01-15', '2029-01-15', 'MA014', NULL, NULL, NULL, 'detained', NULL, '1982-07-31', '09901234568', NULL, NULL, NULL, NULL),
(20, 'Douglas', 'Morrison', 'G.', 'Male', 'Kidnapping', '2024-02-01', '2034-02-01', 'MA015', NULL, NULL, NULL, 'detained', NULL, '1977-11-20', '09012345679', NULL, NULL, NULL, NULL),
(21, 'Walter', 'Shepherd', 'K.', 'Male', 'Corporate Fraud', '2024-01-05', '2029-01-05', 'OA011', NULL, NULL, NULL, 'detained', NULL, '1965-04-13', '09123456781', NULL, NULL, NULL, NULL),
(22, 'Virginia', 'Lambert', 'J.', 'Female', 'Money Laundering', '2024-01-10', '2027-01-10', 'OA012', NULL, NULL, NULL, 'detained', NULL, '1962-08-26', '09234567892', NULL, NULL, NULL, NULL),
(23, 'Raymond', 'Fitzgerald', 'H.', 'Male', 'Tax Evasion', '2024-02-05', '2029-02-05', 'OA013', NULL, NULL, NULL, 'reviewing', NULL, '1960-12-09', '09345678903', NULL, NULL, NULL, NULL),
(24, 'Eleanor', 'Chandler', 'M.', 'Female', 'Investment Fraud', '2024-01-15', '2027-01-15', 'OA014', NULL, NULL, NULL, 'detained', NULL, '1964-03-22', '09456789014', NULL, NULL, NULL, NULL),
(25, 'Howard', 'Montgomery', 'P.', 'Male', 'Extortion', '2024-02-01', '2029-02-01', 'OA015', NULL, NULL, NULL, 'detained', NULL, '1959-07-05', '09567890125', NULL, NULL, NULL, NULL),
(26, 'Jasmine', 'Reynolds', 'A.', 'Female', 'Drug Possession', '2024-01-20', '2024-08-20', 'JV016', NULL, NULL, NULL, 'detained', NULL, '2007-01-15', '09678901236', NULL, NULL, NULL, NULL),
(27, 'Benjamin', 'Hudson', 'C.', 'Male', 'Burglary', '2024-02-15', '2025-02-15', 'YA016', NULL, NULL, NULL, 'released', NULL, '2002-05-27', '09789012347', NULL, NULL, NULL, NULL),
(28, 'Alexandra', 'Kennedy', 'E.', 'Female', 'Credit Card Fraud', '2024-01-25', '2025-07-25', 'AD016', NULL, NULL, NULL, 'detained', NULL, '1994-10-19', '09890123458', NULL, NULL, NULL, NULL),
(29, 'Christopher', 'Bishop', 'G.', 'Male', 'Homicide', '2024-02-10', '2034-02-10', 'MA016', NULL, NULL, NULL, 'released', NULL, '1981-03-08', '09901234569', NULL, NULL, NULL, NULL),
(30, 'Margaret', 'Watkins', 'I.', 'Female', 'Insurance Fraud', '2024-01-30', '2027-01-30', 'OA016', NULL, NULL, NULL, 'detained', NULL, '1963-06-21', '09012345670', NULL, NULL, NULL, NULL),
(31, 'Tyler', 'Nash', 'B.', 'Male', 'Vandalism', '2024-01-15', '2024-07-15', 'JV017', NULL, NULL, NULL, 'detained', NULL, '2007-08-03', '09123456782', NULL, NULL, NULL, NULL),
(32, 'Emily', 'Graves', 'D.', 'Female', 'Drug Possession', '2024-02-01', '2024-08-01', 'YA017', NULL, NULL, NULL, 'detained', NULL, '2001-12-14', '09234567893', NULL, NULL, NULL, NULL),
(33, 'Brandon', 'Floyd', 'F.', 'Male', 'Armed Robbery', '2024-01-20', '2025-07-20', 'AD017', NULL, NULL, NULL, 'detained', NULL, '1993-04-25', '09345678904', NULL, NULL, NULL, NULL),
(34, 'Patricia', 'Mcdonald', 'H.', 'Female', 'Drug Trafficking', '2024-02-10', '2029-02-10', 'MA017', NULL, NULL, NULL, 'released', NULL, '1979-09-16', '09456789015', NULL, NULL, NULL, NULL),
(35, 'Richard', 'Stephens', 'J.', 'Male', 'Investment Fraud', '2024-01-25', '2027-01-25', 'OA017', NULL, NULL, NULL, 'detained', NULL, '1961-01-28', '09567890126', NULL, NULL, NULL, NULL),
(36, 'Kayla', 'Barton', 'L.', 'Female', 'Shoplifting', '2024-01-05', '2024-07-05', 'JV018', NULL, NULL, NULL, 'detained', NULL, '2007-03-11', '09678901237', NULL, NULL, NULL, NULL),
(37, 'Marcus', 'Delgado', 'N.', 'Male', 'Cybercrime', '2024-01-10', '2024-09-10', 'YA018', NULL, NULL, NULL, 'detained', NULL, '2000-07-22', '09789012348', NULL, NULL, NULL, NULL),
(38, 'Vanessa', 'Hoffman', 'P.', 'Female', 'Identity Theft', '2024-02-05', '2025-08-05', 'AD018', NULL, NULL, NULL, 'detained', NULL, '1995-11-09', '09890123459', NULL, NULL, NULL, NULL),
(39, 'Gerald', 'Weber', 'R.', 'Male', 'Murder First Degree', '2024-01-15', '2034-01-15', 'MA018', NULL, NULL, NULL, 'detained', NULL, '1978-02-18', '09901234570', NULL, NULL, NULL, NULL),
(40, 'Judith', 'Schwartz', 'T.', 'Female', 'Tax Evasion', '2024-02-01', '2027-02-01', 'OA018', NULL, NULL, NULL, 'detained', NULL, '1960-05-30', '09012345671', NULL, NULL, NULL, NULL),
(41, 'Dylan', 'Mcguire', 'V.', 'Male', 'Gang Activity', '2024-01-20', '2024-08-20', 'JV019', NULL, NULL, NULL, 'detained', NULL, '2006-10-07', '09123456783', NULL, NULL, NULL, NULL),
(42, 'Hannah', 'Frost', 'X.', 'Female', 'Drug Distribution', '2024-02-15', '2025-02-15', 'YA019', NULL, NULL, NULL, 'released', NULL, '2002-01-19', '09234567894', NULL, NULL, NULL, NULL),
(43, 'Wesley', 'Ballard', 'Z.', 'Male', 'Assault and Battery', '2024-01-25', '2025-07-25', 'AD019', NULL, NULL, NULL, 'detained', NULL, '1992-06-28', '09345678905', NULL, NULL, NULL, NULL),
(44, 'Evelyn', 'Zimmerman', 'B.', 'Female', 'Kidnapping', '2024-02-10', '2034-02-10', 'MA019', NULL, NULL, NULL, 'released', NULL, '1983-09-13', '09456789016', NULL, NULL, NULL, NULL),
(45, 'Albert', 'Malone', 'D.', 'Male', 'Corporate Fraud', '2024-01-30', '2029-01-30', 'OA019', NULL, NULL, NULL, 'detained', NULL, '1962-12-24', '09567890127', NULL, NULL, NULL, NULL),
(46, 'Sophie', 'Hendricks', 'F.', 'Female', 'Vandalism', '2024-01-15', '2024-07-15', 'JV020', NULL, NULL, NULL, 'detained', NULL, '2007-04-05', '09678901238', NULL, NULL, NULL, NULL),
(47, 'Isaac', 'Carpenter', 'H.', 'Male', 'Identity Theft', '2024-02-01', '2024-09-01', 'YA020', NULL, NULL, NULL, 'detained', NULL, '2001-08-16', '09789012349', NULL, NULL, NULL, NULL),
(48, 'Natalie', 'Singleton', 'J.', 'Female', 'Embezzlement', '2024-01-20', '2025-07-20', 'AD020', NULL, NULL, NULL, 'detained', NULL, '1994-11-27', '09890123460', NULL, NULL, NULL, NULL),
(49, 'Russell', 'Goodwin', 'L.', 'Male', 'Drug Manufacturing', '2024-02-10', '2029-02-10', 'MA020', NULL, NULL, NULL, 'released', NULL, '1980-03-09', '09901234571', NULL, NULL, NULL, NULL),
(50, 'Martha', 'Hodges', 'N.', 'Female', 'Money Laundering', '2024-01-25', '2027-01-25', 'OA020', NULL, NULL, NULL, 'detained', NULL, '1964-06-20', '09012345672', NULL, NULL, NULL, NULL),
(51, 'Moises', 'Veloso', '', 'Male', 'Phishing and Online Scams', '2024-11-21', '2024-12-25', '2', '../image_inmates/Verloso_Moises/Verloso_Moises_1.png', '../image_inmates/Verloso_Moises', NULL, 'transferred', '', '2000-03-09', '09493926811', NULL, NULL, NULL, NULL),
(52, 'Testing', 'Testing', 'A', 'Male', 'Identity Theft', '2024-11-25', '2024-12-25', '123', '../image_inmates/Testing_Testing_A_Jr/Testing_Testing_A_Jr_1.png', '../image_inmates/Testing_Testing_A_Jr', NULL, 'transferred', 'Jr', '2000-03-09', '23123123', NULL, NULL, NULL, NULL),
(53, 'Testing1', 'Test', 'A', 'Male', 'Credit Card Fraud', '2024-11-25', '2027-08-25', '123', '../image_inmates/Test_Testing1/Test_Testing1_1.png', '../image_inmates/Test_Testing1', NULL, 'released', '', '1994-07-07', '0999999999', NULL, NULL, NULL, NULL),
(54, 'Asdad', 'Asd', 'Asd', 'Male', 'Burglary', '2024-12-02', '2024-12-25', '123', '../image_inmates/Asd_Asd_Asd_Jr/Asd_Asd_Asd_Jr_1.png', '../image_inmates/Asd_Asd_Asd_Jr', NULL, 'transferred', '', '2000-03-09', '2131231232', NULL, NULL, NULL, NULL),
(55, '123', '123', '', 'Male', 'Theft', '2024-12-02', '2024-12-25', '123', '../image_inmates/123_123_III/123_123_III_4.png', '../image_inmates/123_123_III', NULL, 'transferred', 'II', '2000-03-09', '123123', '123', '123', '123', '2024-12-02 11:00:00'),
(57, 'Testing', 'Test', '', 'Male', 'Identity Theft', '2024-12-05', '2024-12-25', '123', '../image_inmates/Test_Test_Jr/Test_Test_Jr_1.png', '../image_inmates/Test_Test_Jr', NULL, 'transferred', '', '2000-03-09', 'asdasd', 'test', 'test', 'test', '2024-12-05 00:19:00'),
(58, 'Testing', 'Test', '', 'Male', 'Identity Theft', '2024-12-06', '2026-12-25', '321', '../image_inmates/Test_Test_III/Test_Test_III_1.png', '../image_inmates/Test_Test_III', NULL, 'transferred', '', '1999-01-02', '099999999', 'arresting officer', 'investigating officer', 'address of inmate', '2024-12-06 21:44:00'),
(59, 'Mica', 'Maglente', 'Aaaa', 'Female', 'Drug Use', '2024-02-03', '2026-05-06', '1231333', '../image_inmates/Maglente_Mica_Aaaaa/Maglente_Mica_Aaaaa_1.png', '../image_inmates/Maglente_Mica_Aaaaa', NULL, 'detained', '', '2002-11-11', '091123391949', 'Keano', 'Moises', '123 Tondo Manila', '2024-02-03 12:30:00'),
(60, 'Tester', 'Test', '', 'Male', 'Credit Card Fraud', '2024-12-07', '2024-12-25', '123', '../image_inmates/Test_Test_Jr/Test_Test_Jr_2.png', '../image_inmates/Test_Test_Jr', NULL, 'detained', '', '2000-03-09', '0949999999', 'test', 'test', 'test', '2024-12-07 13:32:00'),
(61, 'Keanop', 'Salvador', 'Montano', 'Male', 'Drug Use', '2024-12-08', '2028-06-13', '2024-02-22', '../image_inmates/Salvador_Keano_Montano/Salvador_Keano_Montano_1.png', '../image_inmates/Salvador_Keano_Montano', NULL, 'detained', '', '2002-12-02', '091123391949', 'Mark Sulleza', 'Moises Veloso', '318-A Cristobal Tondo Manila', '2024-12-07 15:59:00'),
(65, 'Asdasda', 'Sdasdasd', 'Asdasd', 'Male', 'Identity Theft, Bank Fraud, Insurance Fraud', '2024-12-11', '2024-12-20', '131234', '../image_inmates/Sdasdasd_Asdasda_Asdasd_Jr/Sdasdasd_Asdasda_Asdasd_Jr_1.png', '../image_inmates/Sdasdasd_Asdasda_Asdasd_Jr', NULL, 'transferred', 'Jr', '2000-03-09', '9823234', 'sasdasd', 'asdasd', 'asdad', '2024-12-11 18:39:00'),
(66, 'Asda', 'Dasda', 'Dasd', 'Male', 'Embezzlement, Drug Trafficking, Manufacturing of Drugs, Car Theft', '2024-12-11', '2024-12-26', 'asda', '../image_inmates/Dasda_Asda_Dasd_Sr/Dasda_Asda_Dasd_Sr_1.png', '../image_inmates/Dasda_Asda_Dasd_Sr', NULL, 'detained', 'Sr', '2000-03-09', '0883123123123', 'asdasdad', 'asasdasd', 'asdasd', '2024-12-11 19:21:00'),
(67, 'Aasda', 'Adsasd', '', 'Male', 'Homicide, Assault and Battery, testing', '2024-12-12', '2024-12-28', '3261', '../image_inmates/Adsasd_Aasda_Jr/Adsasd_Aasda_Jr_1.png', '../image_inmates/Adsasd_Aasda_Jr', NULL, 'transferred', 'Jr', '2000-03-09', '099887776', 'asd', 'asd', 'asd', '2024-12-12 11:48:00');

-- --------------------------------------------------------

--
-- Table structure for table `inmate_actions`
--

CREATE TABLE `inmate_actions` (
  `id` int(11) NOT NULL,
  `inmate_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmate_actions`
--

INSERT INTO `inmate_actions` (`id`, `inmate_id`, `user_id`, `action`, `action_date`) VALUES
(4, 53, 29, 'Status changed to reviewing', '2024-11-18 01:06:12'),
(5, 43, 29, 'Status changed to reviewing', '2024-11-19 23:46:32'),
(6, 46, 29, 'Status changed to reviewing', '2024-11-20 00:06:34'),
(7, 27, 29, 'Status changed to reviewing', '2024-11-20 09:08:40'),
(8, 42, 29, 'Status changed to reviewing', '2024-11-20 09:15:39'),
(9, 27, 29, 'Status changed to reviewing', '2024-11-20 09:31:24'),
(10, 42, 33, 'Status changed to reviewing', '2024-11-20 09:44:48'),
(11, 4, 33, 'Status changed to reviewing', '2024-11-20 09:44:51'),
(12, 29, 33, 'Status changed to reviewing', '2024-11-20 09:44:54'),
(13, 34, 33, 'Status changed to reviewing', '2024-11-20 09:44:57'),
(14, 44, 33, 'Status changed to reviewing', '2024-11-20 09:45:01'),
(15, 49, 33, 'Status changed to reviewing', '2024-11-20 09:45:03'),
(16, 8, 33, 'Status changed to reviewing', '2024-11-20 09:45:07'),
(17, 13, 33, 'Status changed to reviewing', '2024-11-20 09:45:09'),
(18, 18, 33, 'Status changed to reviewing', '2024-11-20 09:45:18'),
(19, 23, 33, 'Status changed to reviewing', '2024-11-20 09:45:21'),
(20, 38, 33, 'Status changed to reviewing', '2024-11-20 09:45:29'),
(21, 27, 29, 'grant', '2024-11-21 00:07:28'),
(22, 42, 29, 'decline', '2024-11-21 00:07:38'),
(23, 51, 29, 'Status changed to reviewing', '2024-11-21 02:52:09'),
(24, 53, 29, 'Status changed to reviewing', '2024-11-25 04:00:07'),
(25, 42, 1, 'Status changed to reviewing', '2024-12-06 14:14:11'),
(26, 44, 1, 'Status changed to reviewing', '2024-12-06 14:14:14'),
(27, 44, 1, 'Status changed to reviewing', '2024-12-07 02:42:16'),
(28, 49, 1, 'Status changed to reviewing', '2024-12-07 02:42:20'),
(29, 8, 1, 'Status changed to reviewing', '2024-12-07 02:42:22'),
(30, 44, 1, 'Status changed to reviewing', '2024-12-07 02:53:41'),
(31, 49, 1, 'Status changed to reviewing', '2024-12-07 02:56:48'),
(32, 49, 1, 'Status changed to reviewing', '2024-12-07 02:59:15'),
(33, 49, 1, 'Status changed to reviewing', '2024-12-07 05:30:27'),
(34, 61, 7, 'Status changed to reviewing', '2024-12-07 08:13:55'),
(35, 23, 7, 'Status changed to reviewing', '2024-12-07 08:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `inmate_logs`
--

CREATE TABLE `inmate_logs` (
  `log_id` int(11) NOT NULL,
  `inmate_id` bigint(20) UNSIGNED NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmate_logs`
--

INSERT INTO `inmate_logs` (`log_id`, `inmate_id`, `activity_type`, `description`, `duration`, `location`, `documents`, `created_at`) VALUES
(1, 1, 'hearing', 'madadagdagan ng taon ng kulong 10 years', '', 'dun parin', NULL, '2024-11-14 01:11:31'),
(2, 54, 'Trail', 'Trail', '2 hours', 'Manila ', NULL, '2024-11-18 03:18:42'),
(3, 46, 'dasd', 'asdasd', 'sdasd', 'asdasd', NULL, '2024-11-19 11:31:13'),
(4, 46, 'dasd', 'dasd', 'sdads', 'ddad', NULL, '2024-11-19 12:07:14'),
(5, 46, 'asd', 'sdasd', 'dasd', 'asdasd', NULL, '2024-11-19 12:07:29'),
(6, 47, 'asdasd', 'sdasd', 'asdasd', 'sdasd', NULL, '2024-11-20 02:21:33'),
(7, 47, 'dasd', 'sdasd', 'asdasda', 'dasdasd', NULL, '2024-11-20 02:42:22'),
(8, 51, 'asda', 'asdad', 'sdadasdasd', 'asd', NULL, '2024-11-21 02:50:27'),
(9, 53, 'Trial', 'Trial ', '2 - 3 hours', 'Manila City / Court', NULL, '2024-11-25 03:59:28'),
(10, 58, 'Trial', 'Descript ', '2 - 3 hrs', 'Location', NULL, '2024-12-06 13:45:57'),
(11, 58, 'test', 'description', '1 - 2 hrs', 'location', NULL, '2024-12-06 13:47:06'),
(12, 60, 'Trial', 'Description', '1 - 2 hrs', 'Manila City Court', NULL, '2024-12-07 05:35:38'),
(13, 61, 'Trial', 'Court Trial ', '5 hours', 'Manila City Hall', NULL, '2024-12-07 08:11:34');

-- --------------------------------------------------------

--
-- Table structure for table `inmate_release_logs`
--

CREATE TABLE `inmate_release_logs` (
  `id` int(11) NOT NULL,
  `inmate_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` enum('released','detained','reviewing','transferred') NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmate_release_logs`
--

INSERT INTO `inmate_release_logs` (`id`, `inmate_id`, `user_id`, `action`, `timestamp`) VALUES
(5, 23, 7, 'reviewing', '2024-12-07 08:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `inmate_transfers`
--

CREATE TABLE `inmate_transfers` (
  `transfer_id` int(11) NOT NULL,
  `inmate_id` bigint(20) UNSIGNED NOT NULL,
  `transfer_location` varchar(255) NOT NULL,
  `transfer_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inmate_transfers`
--

INSERT INTO `inmate_transfers` (`transfer_id`, `inmate_id`, `transfer_location`, `transfer_date`, `user_id`) VALUES
(1, 54, 'asd', '2024-12-02 02:04:27', 29),
(2, 55, 'test', '2024-12-02 02:10:14', 29),
(3, 52, 'Manila City Jail', '2024-12-02 03:44:23', 29),
(4, 51, 'Manila CIty Jail', '2024-12-02 03:50:29', 29),
(5, 57, 'Location', '2024-12-04 16:35:18', 1),
(6, 58, 'Manila', '2024-12-06 14:08:48', 1),
(7, 8, 'Manila', '2024-12-07 05:32:00', 1),
(8, 13, 'BJMP City Jail', '2024-12-07 08:15:15', 7),
(9, 18, 'BJMP City Jail', '2024-12-07 08:15:43', 7),
(10, 67, 'Manila', '2024-12-12 04:21:13', 1),
(11, 65, 'test', '2024-12-12 04:21:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `title`, `content`, `image_path`, `created_at`, `updated_at`) VALUES
(4, 29, 'Testing', 'asdasd', 'uploads/673d2ba513389.png', '2024-11-20 00:21:57', '2024-11-20 00:21:57'),
(5, 29, 'asdad', 'dasd', 'uploads/673d2bf1861f0.jpg', '2024-11-20 00:23:13', '2024-11-20 00:23:13'),
(6, 29, 'asd', 'sdasd', 'uploads/673d30ea0ee2a.jpg', '2024-11-20 00:44:26', '2024-11-20 00:44:26'),
(7, 29, 'asda', 'sdasd', NULL, '2024-11-20 00:44:57', '2024-11-20 00:44:57'),
(8, 29, 'Flag Raising Ceremony', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi et interdum lacus, quis dignissim tellus. Suspendisse potenti. Cras scelerisque neque quis lobortis bibendum. Fusce luctus porta elit sed aliquet. Sed posuere eu tellus a accumsan. Nunc aliquet nulla non diam sodales, eu rhoncus ipsum bibendum. Donec vitae mattis lectus, at bibendum leo. Nam magna nisl, varius in feugiat eget, hendrerit nec purus. Ut commodo erat a dapibus mollis. In ultricies lacinia nunc, non interdum orci eleifend a. Aliquam non mi vitae sapien iaculis finibus sit amet vel magna. Suspendisse varius, tortor quis molestie cursus, tellus est consectetur metus, a vestibulum eros erat ac lectus.\r\n\r\nNam eget purus suscipit, tincidunt ligula ut, pharetra justo. Mauris mattis orci odio, eget feugiat ipsum venenatis sit amet. Vivamus finibus dolor id nisl ullamcorper, egestas euismod leo eleifend. Sed dapibus libero at ullamcorper tempus. Mauris a dolor laoreet, facilisis ante a, dignissim orci. Cras et leo dignissim, sagittis sem sed, dictum lorem. Etiam laoreet nec lectus ut posuere. Maecenas accumsan convallis tellus sed faucibus. Nulla cursus at quam ut pretium.\r\n\r\nSed hendrerit tempor felis. Curabitur ante libero, pretium quis cursus eu, euismod a dolor. Suspendisse quis orci vel purus luctus iaculis vitae et nunc. Nullam in imperdiet metus, quis fermentum purus. Duis et viverra purus. Nullam in tincidunt risus. Nulla sollicitudin eros elit, sit amet molestie tortor auctor eget. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla gravida vestibulum magna, ac tristique elit scelerisque ac. Proin a tellus at ligula vestibulum porttitor. Ut vestibulum molestie libero, finibus ullamcorper lacus pellentesque in. Aenean non libero dictum tellus ultrices pharetra. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla posuere nulla ut neque lobortis imperdiet. Ut ac convallis lectus.', NULL, '2024-11-20 07:30:38', '2024-11-20 07:30:38'),
(9, 29, 'asdasd', '     Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean maximus molestie justo, at mattis tellus scelerisque et. Suspendisse mattis suscipit vestibulum. Suspendisse vitae suscipit justo, non commodo ante. Nullam aliquet commodo nibh nec volutpat. Maecenas nec pharetra tellus. Sed in blandit massa. Suspendisse potenti. Nunc eget ultrices nibh. Aliquam porta pharetra eros, ut efficitur orci bibendum in. Sed consequat at est eu eleifend. In id sapien sed diam pharetra finibus.\n\n     Maecenas finibus nulla elit, ac varius purus venenatis vitae. Proin elit augue, accumsan ac efficitur eu, fermentum sed nibh. Fusce elit ligula, rutrum laoreet gravida vitae, venenatis quis erat. Suspendisse mattis vel massa sed euismod. Maecenas in nisi rutrum, semper nunc ac, ornare nulla. Donec mauris leo, feugiat eu mollis convallis, dictum eu nunc. Proin eget tortor velit. Integer at vehicula sapien, eu laoreet ligula. Donec suscipit magna eu rhoncus imperdiet. In fermentum mauris est, tincidunt tristique enim porttitor vel. Phasellus rutrum placerat metus, at malesuada eros tristique sed. Integer placerat risus mattis libero scelerisque tempor. Fusce varius sapien quis est porta, a ornare nisl hendrerit.\n\n     Integer vitae justo nisl. Integer tincidunt quam tortor, eu lobortis libero eleifend consectetur. Pellentesque mauris nisi, congue vel mollis ut, eleifend in ante. Quisque leo elit, faucibus at condimentum eget, placerat vel odio. Morbi pretium varius eleifend. Praesent facilisis at nulla non efficitur. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut ultricies finibus nisi eget gravida.', NULL, '2024-11-20 07:40:09', '2024-11-20 07:41:15'),
(10, 29, 'Testing', 'Testing Testing Testing', NULL, '2024-11-25 04:26:14', '2024-11-25 04:26:14'),
(11, 1, 'Testing', 'Post', NULL, '2024-12-06 14:11:09', '2024-12-06 14:11:09'),
(12, 1, 'Flag Raising', 'description', 'uploads/6753dcbd75d70.jpg', '2024-12-07 05:27:25', '2024-12-07 05:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hashed` varchar(255) NOT NULL,
  `role` enum('Admin','User') NOT NULL,
  `verification_code` varchar(6) DEFAULT NULL,
  `status` enum('Pending','Verified','Suspended','Reviewing') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `middle_name`, `email`, `username`, `password_hashed`, `role`, `verification_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Admin', '', 'velosomoises09@gmail.com', 'admin', '$2y$10$LSnyQRhYq8QVvFGAJO8Jhe1ffIr9p8Q2JhDzB/l042jMNi9W7GNL.', 'Admin', '784866', 'Verified', '2024-12-04 01:17:37', '2024-12-16 00:55:40'),
(6, 'Moises', 'Veloso', 'Montano', 'markgillsulleza3@gmail.com', 'Moises2', '$2y$10$ZWkWWirHzg8bXDCXJA5E2OleVmWEOMx8rAG7YsoPX6rMHgK4IY5dO', 'User', '878247', 'Pending', '2024-12-07 07:45:40', '2024-12-07 07:45:40'),
(7, 'Keano', 'Salvador', 'Montano', 'keanosalvador@gmail.com', 'keano1202', '$2y$10$//J0BdiL5cx1/Iqe29TPD.dYdmxJuCDnfVMmLyX2N8a976vqHhYzO', 'User', '992300', 'Verified', '2024-12-07 07:49:03', '2024-12-07 07:51:40');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `visitor_name` varchar(255) NOT NULL,
  `inmate_id` bigint(20) UNSIGNED NOT NULL,
  `inmate_name` varchar(255) NOT NULL,
  `relationship` varchar(100) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `visit_date` date NOT NULL,
  `visit_time` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `visitor_name`, `inmate_id`, `inmate_name`, `relationship`, `purpose`, `visit_date`, `visit_time`, `image_path`) VALUES
(13, 'testing', 15, 'Derek Spencer', 'Mother', 'Purpose', '2024-12-06', '10:07 PM', 'visitors/testing3.png'),
(14, 'test', 60, 'Tester Test', 'Father', 'purpose', '2024-12-07', '1:36 PM', 'visitors/test7.png'),
(15, 'Maria Denise', 61, 'Kean Salvador', 'Brother', 'Visit', '2024-12-07', '4:12 PM', 'visitors/MariaDenise1.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inmates`
--
ALTER TABLE `inmates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inmate_actions`
--
ALTER TABLE `inmate_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inmate_id` (`inmate_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `inmate_logs`
--
ALTER TABLE `inmate_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `inmate_id` (`inmate_id`);

--
-- Indexes for table `inmate_release_logs`
--
ALTER TABLE `inmate_release_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inmate_id` (`inmate_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `inmate_transfers`
--
ALTER TABLE `inmate_transfers`
  ADD PRIMARY KEY (`transfer_id`),
  ADD KEY `inmate_id` (`inmate_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inmate_id` (`inmate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inmates`
--
ALTER TABLE `inmates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `inmate_actions`
--
ALTER TABLE `inmate_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `inmate_logs`
--
ALTER TABLE `inmate_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `inmate_release_logs`
--
ALTER TABLE `inmate_release_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inmate_transfers`
--
ALTER TABLE `inmate_transfers`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inmate_actions`
--
ALTER TABLE `inmate_actions`
  ADD CONSTRAINT `inmate_actions_ibfk_1` FOREIGN KEY (`inmate_id`) REFERENCES `inmates` (`id`),
  ADD CONSTRAINT `inmate_actions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `inmate_logs`
--
ALTER TABLE `inmate_logs`
  ADD CONSTRAINT `inmate_logs_ibfk_1` FOREIGN KEY (`inmate_id`) REFERENCES `inmates` (`id`);

--
-- Constraints for table `inmate_release_logs`
--
ALTER TABLE `inmate_release_logs`
  ADD CONSTRAINT `inmate_release_logs_ibfk_1` FOREIGN KEY (`inmate_id`) REFERENCES `inmates` (`id`),
  ADD CONSTRAINT `inmate_release_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `inmate_transfers`
--
ALTER TABLE `inmate_transfers`
  ADD CONSTRAINT `inmate_transfers_ibfk_1` FOREIGN KEY (`inmate_id`) REFERENCES `inmates` (`id`),
  ADD CONSTRAINT `inmate_transfers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `visitors`
--
ALTER TABLE `visitors`
  ADD CONSTRAINT `visitors_ibfk_1` FOREIGN KEY (`inmate_id`) REFERENCES `inmates` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
