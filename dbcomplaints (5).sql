-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2024 at 09:50 AM
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
-- Database: `dbcomplaints`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_announcement`
--

CREATE TABLE `tbl_announcement` (
  `announcement_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `date_posted` date NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `share_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_announcement`
--

INSERT INTO `tbl_announcement` (`announcement_id`, `title`, `content`, `date_posted`, `image_path`, `deleted`, `share_count`) VALUES
(1, 'ddsdsmd', 'excel', '2024-07-19', 'uploads/profile_6697a2b8e17de.jpg', 1, 0),
(2, 'ddsdsmd', 'xnzm xzmxz', '2024-07-19', '', 1, 0),
(4, 'pnp logo', 'newxzxzxx', '2024-07-19', NULL, 1, 0),
(7, 'princess', 'mahilig sa kape at idol niya akoccxx', '2024-07-20', NULL, 1, 0),
(8, 'excel', 'crush ko', '2024-07-21', 'uploads/profile_669af75c84736.jpg', 1, 0),
(9, 'pnp logo', 'try lang', '2024-07-21', 'uploads/pnplogo.png', 1, 0),
(10, 'Fiesta Caravan in one piece Night', 'The Fista caravan will be on August 14, 2024', '2024-07-23', 'uploads/wampis.jpg', 1, 0),
(11, 'kior centrum', 'edit time', '2024-07-24', 'uploads/kior centrum.jpg', 1, 5),
(12, 'esodsdsds', 'eyyyyyyyy', '2024-07-27', 'uploads/kior centrum.jpg', 1, 0),
(13, 'weeeeeeeeeeeeeeeeeeeee', 'jbjbbhbn ', '2024-07-28', '', 1, 0),
(14, 'dfdfd', 'redfdfgftgfgdfgfd', '2024-08-01', 'uploads/441799097_1205342757295168_7001114824101829619_n.jpg', 1, 0),
(15, 'Interview', 'Interview for valifation and adding of features', '2024-09-03', 'uploads/458491959_1003199601607500_4565557339938371083_n.jpg', 1, 0),
(16, 'interview', 'nag interview\r\n', '2024-09-05', '../uploadspnp interview.jpg', 1, 0),
(17, 'princess', 'laging tulog walang ambag', '2024-09-08', '../uploadsuploads456864062_827865415993505_5703441178082039818_n.jpg', 1, 0),
(18, 'interview', 'pnp', '2024-09-12', '../uploadsuploadspnp interview.jpg', 1, 0),
(19, 'interview', 'pnp', '2024-09-12', '../uploadsuploads458491959_1003199601607500_4565557339938371083_n.jpg', 1, 0),
(26, 'xcxc', 'xcxcxc', '2024-09-12', '', 1, 0),
(27, ' z z  ', ' x z z', '2024-09-12', '', 1, 0),
(28, 'ssccsc', 'zz', '2024-09-12', '', 1, 0),
(29, ' z z z ', 'zz zz', '2024-09-12', '', 1, 0),
(30, 'x xx ', 'xcxcxcc', '2024-09-12', '', 1, 0),
(31, 'scscc', 'scsscs', '2024-09-12', '', 1, 0),
(32, 'Interview', 'PNP', '2024-09-12', '../uploads/uploads458491959_1003199601607500_4565557339938371083_n.jpg', 0, 0),
(33, 'Brain Storming', 'Centrum', '2024-09-12', '../uploads/kior centrum.jpg', 0, 0),
(34, 'edewdw', 'deqdq', '2024-09-12', '../uploads/8.jpg', 1, 0),
(35, 'Wanpis', 'We, the pirate group', '2024-09-12', '../uploads/3.jpg', 0, 0),
(36, 'To be continue', 'Ang pagbabalik', '2024-09-12', '../uploads/Screenshot (47).png', 0, 0),
(37, 'sfsfsfs', 'adssd', '2024-09-14', '../uploads/uploads456864062_827865415993505_5703441178082039818_n.jpg', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brg_official`
--

CREATE TABLE `tbl_brg_official` (
  `official_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `position` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `barangays_id` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_brg_official`
--

INSERT INTO `tbl_brg_official` (`official_id`, `name`, `position`, `image`, `barangays_id`, `is_deleted`) VALUES
(71, 'Brayan John V Aquino', 'Barangay Captain', '../uploads/profile_66b58b8079dfe.jpg', 293, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaintcategories`
--

CREATE TABLE `tbl_complaintcategories` (
  `category_id` int(10) NOT NULL,
  `complaints_category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_complaintcategories`
--

INSERT INTO `tbl_complaintcategories` (`category_id`, `complaints_category`) VALUES
(387, 'Alarms and Scandals (Art. 155)'),
(388, 'Illegal Use of Uniforms and Insignias (Art. 179)'),
(389, 'Using Fictitious Names and Concealing True Names (Art. 178)'),
(399, 'Using False Certificates (Art. 175)'),
(401, 'Slight physical injuries and maltreatment (Art. 266)'),
(402, 'Unlawful Use of Means of Publication and Unlawful Utterances (Art. 154)');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaints`
--

CREATE TABLE `tbl_complaints` (
  `complaints_id` int(15) NOT NULL,
  `complaint_name` varchar(255) NOT NULL,
  `complaints_person` varchar(15) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '''Inprogress''',
  `complaints` text NOT NULL,
  `responds` enum('barangay','pnp') NOT NULL,
  `date_filed` date NOT NULL,
  `category_id` int(1) NOT NULL,
  `barangays_id` int(10) NOT NULL,
  `image_id` int(10) NOT NULL,
  `info_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ano` varchar(255) DEFAULT NULL,
  `saan` varchar(255) DEFAULT NULL,
  `kailan` datetime(6) DEFAULT NULL,
  `paano` text DEFAULT NULL,
  `bakit` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_complaints`
--

INSERT INTO `tbl_complaints` (`complaints_id`, `complaint_name`, `complaints_person`, `status`, `complaints`, `responds`, `date_filed`, `category_id`, `barangays_id`, `image_id`, `info_id`, `user_id`, `ano`, `saan`, `kailan`, `paano`, `bakit`) VALUES
(529, 'excel Nicole Aquino ', 'wewew', 'settled_in_barangay', 'wewdxsdsd', 'barangay', '2024-10-03', 399, 291, 602, 471, 249, 'wew', 'wewe', '2024-10-12 23:06:00.000000', 'wewe', 'wdw'),
(530, 'excel Nicole Aquino ', 'cvcv', 'inprogress', 'dgvfcvc', '', '2024-10-04', 387, 291, 609, 472, 249, 'vcvc', 'cvc', '2024-10-19 09:30:00.000000', 'cvcv', 'cvcvc'),
(531, 'Brayan John Villanueva Aquino ', 'xcxc', 'inprogress', 'cxc', '', '2024-10-04', 399, 291, 610, 473, 249, 'xcxc', 'xcxc', '2024-10-12 20:55:00.000000', 'xcx', 'xcxc'),
(532, 'Brayan John Villanueva Aquino ', 'cvcvcvcvc', 'inprogress', 'vdcvcc', '', '2024-10-04', 401, 291, 611, 474, 249, 'dfdf', 'df', '2024-10-19 21:06:00.000000', 'dfdf', 'dfdf'),
(533, 'Brayan John Villanueva Aquino ', 'xcx', 'inprogress', 'xcx', '', '2024-10-04', 387, 291, 612, 475, 249, 'xcx', 'cx', '2024-10-26 21:07:00.000000', 'cxcx', 'xcxc'),
(534, 'Brayan John Villanueva Aquino ', 'dfdfdf', 'inprogress', 'dfdf', '', '2024-10-04', 402, 291, 613, 476, 249, 'fdfd', 'dfd', '2024-10-17 21:10:00.000000', 'dfdf', 'dfdf'),
(535, 'Brayan John Villanueva Aquino ', 'fgfgf', 'inprogress', 'fdgfg', '', '2024-10-04', 387, 291, 614, 477, 249, 'fgf', 'fgf', '2024-10-19 21:11:00.000000', 'gffg', 'fgfg'),
(536, 'Brayan John Villanueva Aquino ', 'reyven pili', 'inprogress', 'tamad si reyven', '', '2024-10-05', 402, 291, 615, 478, 249, 'ayaw mag code', 'sa gamis', '2024-10-24 20:57:00.000000', 'tinatamad', 'tamad mag code'),
(537, 'Brayan John Villanueva Aquino ', 'dfdf', 'inprogress', 'dfd', '', '2024-10-05', 399, 291, 616, 479, 249, 'dfd', 'dfd', '2024-10-05 23:03:00.000000', 'dfd', 'dfdf'),
(539, 'fdfd dfd dfdf ', 'SDS', 'inprogress', 'SDFSDS', '', '2024-10-07', 387, 300, 0, 0, 254, 'SDS', 'SD', '2024-10-07 20:02:00.000000', 'SDS', 'SDSD'),
(540, 'fdfd dfd dfdf ', 'fgfgfgf', 'Approved', 'fgbfg', '', '2024-10-07', 389, 300, 0, 0, 254, 'fgf', 'fgfgfgfgf', '2024-10-07 20:35:00.000000', 'fgfg', 'fgfg'),
(541, 'Brayan John Villanueva Aquino ', 'fgfg', 'inprogress', 'fgfg', '', '2024-10-07', 389, 291, 0, 0, 249, 'fgfgf', 'fgf', '2024-10-23 04:32:00.000000', 'fgfg', 'fgfgf'),
(542, 'Brayan John Villanueva Aquino ', 'fgfgf', 'inprogress', 'fgf', '', '2024-10-07', 387, 291, 0, 0, 249, 'fgf', 'fgf', '2024-10-24 03:32:00.000000', 'fgf', 'fgfg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_evidence`
--

CREATE TABLE `tbl_evidence` (
  `evidence_id` int(20) NOT NULL,
  `complaints_id` int(20) NOT NULL,
  `evidence_path` varchar(255) NOT NULL,
  `date_uploaded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_evidence`
--

INSERT INTO `tbl_evidence` (`evidence_id`, `complaints_id`, `evidence_path`, `date_uploaded`) VALUES
(0, 529, '../uploads/8.jpg', '2024-10-03'),
(0, 530, '../uploads/8.jpg', '2024-10-04'),
(0, 531, '../uploads/6.jfif', '2024-10-04'),
(0, 532, '../uploads/6.jfif', '2024-10-04'),
(0, 533, '../uploads/7.jfif', '2024-10-04'),
(0, 534, '../uploads/9.jpg', '2024-10-04'),
(0, 535, '../uploads/8.jpg', '2024-10-04'),
(0, 536, '../uploads/19.jpg', '2024-10-05'),
(0, 537, '../uploads/8.jpg', '2024-10-05'),
(0, 539, '../uploads/8.jpg', '2024-10-07'),
(0, 540, '../uploads/7.jfif', '2024-10-07'),
(0, 541, '../uploads/16.png', '2024-10-07'),
(0, 542, '../uploads/7.jfif', '2024-10-07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hearing_history`
--

CREATE TABLE `tbl_hearing_history` (
  `id` int(11) NOT NULL,
  `complaints_id` int(11) NOT NULL,
  `hearing_date` date NOT NULL,
  `hearing_time` varchar(255) NOT NULL,
  `hearing_type` varchar(50) NOT NULL,
  `hearing_status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login_logs`
--

CREATE TABLE `tbl_login_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `login_time` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_login_logs`
--

INSERT INTO `tbl_login_logs` (`log_id`, `user_id`, `login_time`) VALUES
(129, 249, '2024-10-02 13:22:42.000000'),
(130, 249, '2024-10-02 13:58:59.000000'),
(133, 249, '2024-10-02 21:43:58.000000'),
(134, 249, '2024-10-02 21:45:16.000000'),
(135, 249, '2024-10-02 21:53:52.000000'),
(136, 249, '2024-10-02 22:20:00.000000'),
(137, 251, '2024-10-02 23:14:05.000000'),
(138, 249, '2024-10-03 09:38:46.000000'),
(139, 249, '2024-10-03 09:50:44.000000'),
(140, 249, '2024-10-03 15:38:00.000000'),
(141, 249, '2024-10-03 21:29:23.000000'),
(142, 251, '2024-10-03 21:34:31.000000'),
(143, 249, '2024-10-03 23:02:05.000000'),
(144, 249, '2024-10-03 23:53:15.000000'),
(145, 251, '2024-10-04 00:09:50.000000'),
(146, 251, '2024-10-04 00:10:08.000000'),
(147, 249, '2024-10-04 09:10:50.000000'),
(148, 249, '2024-10-04 09:27:15.000000'),
(149, 249, '2024-10-04 18:46:03.000000'),
(150, 249, '2024-10-04 20:24:26.000000'),
(151, 249, '2024-10-04 20:55:07.000000'),
(152, 249, '2024-10-04 21:08:20.000000'),
(153, 249, '2024-10-04 21:17:44.000000'),
(154, 249, '2024-10-04 21:18:10.000000'),
(155, 249, '2024-10-05 18:45:56.000000'),
(156, 249, '2024-10-06 10:54:26.000000'),
(157, 249, '2024-10-06 13:09:32.000000'),
(158, 249, '2024-10-06 14:57:54.000000'),
(159, 249, '2024-10-06 15:07:16.000000'),
(160, 251, '2024-10-06 16:04:33.000000'),
(161, 249, '2024-10-07 19:07:51.000000'),
(162, 254, '2024-10-07 19:52:26.000000'),
(163, 251, '2024-10-07 20:03:29.000000'),
(164, 254, '2024-10-07 20:31:10.000000'),
(165, 251, '2024-10-07 22:17:58.000000'),
(166, 249, '2024-10-07 22:34:11.000000'),
(167, 249, '2024-10-07 22:34:47.000000'),
(168, 249, '2024-10-08 11:23:54.000000'),
(169, 251, '2024-10-08 11:24:59.000000'),
(170, 249, '2024-10-08 12:48:39.000000'),
(171, 251, '2024-10-08 12:49:15.000000'),
(172, 251, '2024-10-08 20:35:46.000000'),
(173, 251, '2024-10-08 21:26:31.000000'),
(174, 249, '2024-10-08 21:26:56.000000'),
(175, 251, '2024-10-08 22:22:51.000000'),
(176, 249, '2024-10-09 08:14:31.000000'),
(177, 251, '2024-10-09 08:15:43.000000'),
(178, 251, '2024-10-09 08:16:25.000000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(15) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `extension_name` varchar(255) DEFAULT NULL,
  `gender` varchar(255) NOT NULL,
  `age` int(255) NOT NULL,
  `birth_date` date NOT NULL,
  `selfie_path` varchar(255) NOT NULL,
  `image_type` enum('ID') NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `date_uploaded` datetime(6) NOT NULL,
  `cp_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `accountType` enum('Resident','Barangay Official','PNP Officer') NOT NULL,
  `barangays_id` int(11) DEFAULT NULL,
  `place_of_birth` varchar(255) NOT NULL,
  `purok` varchar(255) NOT NULL,
  `civil_status` enum('Single','Married','Separated','Live-in','Divorced','Widowed') NOT NULL,
  `educational_background` enum('No Formal Education','Elementary','Highschool','College','Post Graduate') NOT NULL,
  `nationality` varchar(255) NOT NULL,
  `pic_data` varchar(255) NOT NULL,
  `security_question` varchar(255) NOT NULL,
  `security_answer` varchar(255) NOT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `lockout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `gender`, `age`, `birth_date`, `selfie_path`, `image_type`, `image_path`, `date_uploaded`, `cp_number`, `password`, `accountType`, `barangays_id`, `place_of_birth`, `purok`, `civil_status`, `educational_background`, `nationality`, `pic_data`, `security_question`, `security_answer`, `login_attempts`, `lockout_time`) VALUES
(249, 'Brayan John', 'Villanueva', 'Aquino', '', 'Female', 24, '2000-03-05', '', 'ID', '', '0000-00-00 00:00:00.000000', '09098503472', '$2y$10$pJCab/Fdm4HZzkc7E82UM.EPrz.wRjSP3SmX49.fXWQjcuWE8SVWW', 'Resident', 291, 'gamis', 'Purok 2', 'Single', 'No Formal Education', 'pilipino', '../uploads/435559638_928560769060280_7584294412764526119_n.jpg', 'What was your childhood nickname?', '$2y$10$roLU1Fz8Q3obKS0QVyRMvOFXTWq7oO/pVAZmmPh38TmUJkJJaWMky', 0, NULL),
(251, 'Princess', 'Cadiente', 'Rosario', '', 'Male', 24, '2000-02-01', '', 'ID', '', '0000-00-00 00:00:00.000000', '09123456789', '$2y$10$uqq4iiGjXhrFk8idVtm8ke8kg7NlC9pm0wqKfTAPGV3xS3gdNDPbK', 'Barangay Official', 293, 'dsadsdsdss', 'Purok 6', 'Single', 'No Formal Education', 'dfdfdfdf', '../uploads/profile_66fd38f9190f1.png', 'What was your childhood nickname?', '$2y$10$wR.kQ0QEf3IIBSGTzxOELuEqQClhiXRb0J2ajMPcm/n.jxHSVGPy6', 0, NULL),
(252, 'trisha', 'Dulnuan', 'Yaranon', '', 'Male', 22, '2002-03-31', '', 'ID', '', '0000-00-00 00:00:00.000000', '09638236744', '$2y$10$AyaycGFXqmf0VjcNQfJvw.WWQQ7AFAQZqwELb1Ehdo.XYUqjHMGLG', 'Resident', 295, 'dsdsds', 'Purok 6', 'Single', 'College', 'dgdgdvd', '../uploads/profile_66fe933fde1bf.jpg', 'What was your childhood nickname?', '$2y$10$N4d/gvre0M/Nvdz1Zbrq/uHFv5TeWCo5AXAjxlcKixqf3WWByuaui', 0, NULL),
(253, 'leslie', 'Pascual', 'Rigor', '', 'Male', 34, '1990-02-08', '', 'ID', '', '0000-00-00 00:00:00.000000', '90987654321', '$2y$10$dh.E5b5OGFd2j9OkIRs10uL4pzJHl6pEftCdzH9UNzJE3uq6/lsDq', 'Resident', 296, 'dfcddf', 'Purok 1', 'Single', 'No Formal Education', 'sdsds', '../uploads/profile_66fe94f6b0c13.jpg', 'What was your childhood nickname?', '$2y$10$UjQnhkgu3BoGD.XOjRHhm.TYPqzgRHGCf6PjJsF7dBzJNeCmYWjhi', 0, NULL),
(254, 'fdfd', 'dfd', 'dfdf', 'dfd', 'Male', 24, '2000-02-09', '../uploads/selfie_6703a0fc7967f_9.jpg', 'ID', '', '0000-00-00 00:00:00.000000', '09858555555', '$2y$10$8hu2Ploj9qCpEL9xpRIYYOAuFAaBZny6K/Pg7QbOuxMPO8PClNzvC', 'Resident', 300, 'sdsds', 'Purok 1', 'Single', 'No Formal Education', 'cvcvc', '../uploads/profile_6703a0fc79422.jpg', 'What was your childhood nickname?', '$2y$10$h0Vo5MRkU5oZplsCjcxtUepBcUA5qiUBlL/OO6gjHZq8nDfevI1Qu', 0, NULL),
(255, 'ffgf', 'gfgf', 'fgfgf', 'fggfgf', 'Male', 23, '2001-04-08', '../uploads/selfie_67063288beb8b_7.jfif', 'ID', '', '0000-00-00 00:00:00.000000', '09774747448', '$2y$10$QG5MXNt0SPSz41gIiNd2se3O2no5Ss7aqotOIimyjUacJZXeTbJPK', 'Resident', 305, 'fgfgfg', 'Purok 4', 'Single', 'No Formal Education', 'ffbfgf', '../uploads/profile_67063288be946.jpg', 'What was your childhood nickname?', '$2y$10$xbUuaAqIdGmtQV2r6zCxZeKvrEuLRkaJCv99rI7wGXXJX5IXnqwya', 0, NULL),
(256, 'dssds', 'sdsd', 'sdsds', '', 'Male', 24, '2000-02-22', '../uploads/selfie_67063528355bb_7.jfif', 'ID', '', '0000-00-00 00:00:00.000000', '09737437434', '$2y$10$CaFhkndy1hCV2hyW/g1v7uwTyW6FBA71wj/8ldkiqV7tYi.d0VO9C', 'Barangay Official', 306, 'sa damo', 'Purok 3', 'Single', 'No Formal Education', 'dfdfdfd', '../uploads/profile_6706352835252.jpg', 'What was your childhood nickname?', '$2y$10$goApTSX/vDONez4ICziRn.weuvttHL9OMuUAJkqjjO7A5rTRTX3ka', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_barangay`
--

CREATE TABLE `tbl_users_barangay` (
  `barangays_id` int(11) NOT NULL,
  `barangay_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users_barangay`
--

INSERT INTO `tbl_users_barangay` (`barangays_id`, `barangay_name`) VALUES
(290, 'Angoluan'),
(291, 'Angoluan'),
(292, 'Angoluan'),
(293, 'Angoluan'),
(294, 'Aromin'),
(295, 'Angoluan'),
(296, 'Angoluan'),
(297, 'Angoluan'),
(298, 'Angoluan'),
(299, 'Angoluan'),
(300, 'Angoluan'),
(301, 'Angoluan'),
(302, 'Angoluan'),
(303, 'Angoluan'),
(304, 'Angoluan'),
(305, 'Angoluan'),
(306, 'Angoluan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_announcement`
--
ALTER TABLE `tbl_announcement`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `tbl_brg_official`
--
ALTER TABLE `tbl_brg_official`
  ADD PRIMARY KEY (`official_id`),
  ADD KEY `tbl_brg_official_ibfk_1` (`barangays_id`);

--
-- Indexes for table `tbl_complaintcategories`
--
ALTER TABLE `tbl_complaintcategories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  ADD PRIMARY KEY (`complaints_id`),
  ADD KEY `tbl_complaints_ibfk_2` (`category_id`),
  ADD KEY `tbl_complaints_ibfk_3` (`image_id`),
  ADD KEY `tbl_complaints_ibfk_4` (`info_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tbl_complaints_ibfk_5` (`barangays_id`);

--
-- Indexes for table `tbl_evidence`
--
ALTER TABLE `tbl_evidence`
  ADD KEY `tbl_evidence_ibfk_1` (`complaints_id`);

--
-- Indexes for table `tbl_hearing_history`
--
ALTER TABLE `tbl_hearing_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_id` (`complaints_id`);

--
-- Indexes for table `tbl_login_logs`
--
ALTER TABLE `tbl_login_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `tbl_users_ibfk_2` (`barangays_id`),
  ADD KEY `tbl_users_ibfk_3` (`pic_data`);

--
-- Indexes for table `tbl_users_barangay`
--
ALTER TABLE `tbl_users_barangay`
  ADD PRIMARY KEY (`barangays_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_announcement`
--
ALTER TABLE `tbl_announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_brg_official`
--
ALTER TABLE `tbl_brg_official`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `tbl_complaintcategories`
--
ALTER TABLE `tbl_complaintcategories`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=403;

--
-- AUTO_INCREMENT for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  MODIFY `complaints_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=543;

--
-- AUTO_INCREMENT for table `tbl_hearing_history`
--
ALTER TABLE `tbl_hearing_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tbl_login_logs`
--
ALTER TABLE `tbl_login_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- AUTO_INCREMENT for table `tbl_users_barangay`
--
ALTER TABLE `tbl_users_barangay`
  MODIFY `barangays_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=307;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_brg_official`
--
ALTER TABLE `tbl_brg_official`
  ADD CONSTRAINT `tbl_brg_official_ibfk_1` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  ADD CONSTRAINT `tbl_complaints_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `tbl_complaintcategories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_complaints_ibfk_5` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_complaints_ibfk_6` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_evidence`
--
ALTER TABLE `tbl_evidence`
  ADD CONSTRAINT `tbl_evidence_ibfk_1` FOREIGN KEY (`complaints_id`) REFERENCES `tbl_complaints` (`complaints_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_hearing_history`
--
ALTER TABLE `tbl_hearing_history`
  ADD CONSTRAINT `tbl_hearing_history_ibfk_1` FOREIGN KEY (`complaints_id`) REFERENCES `tbl_complaints` (`complaints_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_login_logs`
--
ALTER TABLE `tbl_login_logs`
  ADD CONSTRAINT `tbl_login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_2` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
