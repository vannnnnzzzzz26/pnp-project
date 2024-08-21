-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2024 at 02:44 PM
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
(7, 'princess', 'mahilig sa kape at idol niya akoccxx', '2024-07-20', NULL, 0, 0),
(8, 'excel', 'crush ko', '2024-07-21', 'uploads/profile_669af75c84736.jpg', 1, 0),
(9, 'pnp logo', 'try lang', '2024-07-21', 'uploads/pnplogo.png', 0, 0),
(10, 'Fiesta Caravan in one piece Night', 'The Fista caravan will be on August 14, 2024', '2024-07-23', 'uploads/wampis.jpg', 0, 0),
(11, 'princess', 'csscssds', '2024-07-24', '', 0, 5),
(12, 'esodsdsds', 'eyyyyyyyy', '2024-07-27', '', 0, 0),
(13, 'weeeeeeeeeeeeeeeeeeeee', 'jbjbbhbn ', '2024-07-28', '', 1, 0),
(14, 'dfdfd', 'redfdfgftgfgdfgfd', '2024-08-01', 'uploads/441799097_1205342757295168_7001114824101829619_n.jpg', 0, 0);

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
(11, 'bjaquinoxzxz', 'captainnnzxzc', '', 99, 1),
(12, 'excel', 'captain', 'uploads/excel.jpg', 100, 1),
(13, 'aneluv', 'Kagawad 2', 'uploads/448479791_1824460291626598_2998626942841780961_n.jpg', 100, 1),
(14, 'haryl', 'Kagawad 3', 'uploads/pnplogo.png', 99, 1),
(15, 'bjsax', 'Kagawad 3', 'uploads/unnamed.png', 100, 1),
(16, 'bjsax', 'captain', 'uploads/pnplogo.png', 100, 1),
(17, 'bj', 'aso ko si princessjbb', 'uploads/435559638_928560769060280_7584294412764526119_n.jpg', 100, 1),
(18, 'excel', 'Kagawad 1', 'uploads/excel.jpg', 99, 1),
(19, 'excel  preza', 'Kagawad 2', 'uploads/excel.jpg', 99, 1),
(20, 'bj Aquino', 'captain', 'uploads/profile_6697a4c20e901.jpg', 99, 1),
(21, 'reyven', 'captain', 'uploads/435559638_928560769060280_7584294412764526119_n.jpg', 102, 1),
(22, 'bj', 'kapitan1', 'uploads/profile_6697a2b8e17de.jpg', 100, 1),
(23, 'excel', 'kagawad 1', 'uploads/profile_669af75c84736.jpg', 100, 0),
(24, 'bj', 'kagawad2', 'uploads/unnamed.png', 100, 0),
(25, 'princess', 'kapitan', 'uploads/441799097_1205342757295168_7001114824101829619_n.jpg', 104, 1),
(26, 'bj', 'kapitan', 'uploads/435559638_928560769060280_7584294412764526119_n.jpg', 104, 0),
(27, 'reyven', 'kapitan', 'uploads/profile_66b58bc469f54.jpg', 122, 1),
(28, 'princesss', 'kapitan1', 'uploads/profile_66a4c0952f9c7.jpg', 122, 0);

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
(250, 'Rape');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaints`
--

CREATE TABLE `tbl_complaints` (
  `complaints_id` int(15) NOT NULL,
  `complaint_name` varchar(255) NOT NULL,
  `cp_number` varchar(10) NOT NULL,
  `complaints_person` varchar(15) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '''unresolved''',
  `complaints` text NOT NULL,
  `responds` enum('barangay','pnp') NOT NULL,
  `date_filed` date NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `category_id` int(1) NOT NULL,
  `barangays_id` int(10) NOT NULL,
  `image_id` int(10) NOT NULL,
  `info_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hearing_date` date NOT NULL,
  `hearing_time` time(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_complaints`
--

INSERT INTO `tbl_complaints` (`complaints_id`, `complaint_name`, `cp_number`, `complaints_person`, `status`, `complaints`, `responds`, `date_filed`, `approved`, `category_id`, `barangays_id`, `image_id`, `info_id`, `user_id`, `hearing_date`, `hearing_time`) VALUES
(288, 'reyven ojadas pili ', '0909850347', 'bj', 'Approved', 'dsdsd', 'barangay', '2024-08-21', 0, 250, 121, 306, 218, 0, '2024-08-30', '21:34:00.000000'),
(289, 'excel nnn preza ', '0927142858', 'eso', 'Approved', 'sdsdsds', 'barangay', '2024-08-21', 0, 250, 124, 307, 219, 0, '2024-08-09', '23:54:00.000000');

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
(0, 288, 'uploads/454540442_524839373339693_8307751333637920231_n.jpg', '2024-08-21'),
(0, 289, 'uploads/454161426_422473144170475_7745676165870487336_n.jpg', '2024-08-21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_image`
--

CREATE TABLE `tbl_image` (
  `image_id` int(10) NOT NULL,
  `complaint_id` int(10) NOT NULL,
  `image_type` enum('ID') NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `date_uploaded` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_image`
--

INSERT INTO `tbl_image` (`image_id`, `complaint_id`, `image_type`, `image_path`, `date_uploaded`) VALUES
(260, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-20 01:29:04.000000'),
(261, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-21 12:41:27.000000'),
(262, 0, 'ID', 'uploads/profile_6697a2b8e17de.jpg', '2024-07-21 12:53:02.000000'),
(263, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-21 13:29:46.000000'),
(264, 0, 'ID', 'uploads/Screenshot 2024-06-13 004000.png', '2024-07-22 04:58:36.000000'),
(265, 0, 'ID', 'uploads/Screenshot (11).png', '2024-07-23 01:51:44.000000'),
(266, 0, 'ID', 'uploads/Screenshot (11).png', '2024-07-23 01:52:10.000000'),
(267, 0, 'ID', 'uploads/Screenshot (15).png', '2024-07-23 01:54:28.000000'),
(268, 0, 'ID', 'uploads/wanpepet.gif', '2024-07-23 13:41:40.000000'),
(269, 0, 'ID', 'uploads/corruption.png', '2024-07-23 13:59:42.000000'),
(270, 0, 'ID', 'uploads/1.png', '2024-07-23 14:04:44.000000'),
(275, 0, 'ID', 'uploads/441799097_1205342757295168_7001114824101829619_n.jpg', '2024-07-23 18:38:01.000000'),
(276, 0, 'ID', 'uploads/441799097_1205342757295168_7001114824101829619_n.jpg', '2024-07-23 18:39:28.000000'),
(277, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-23 18:41:11.000000'),
(278, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-23 18:43:49.000000'),
(279, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-23 19:09:16.000000'),
(280, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-27 11:50:39.000000'),
(281, 0, 'ID', 'uploads/profile_66a0f423cdce2.jpg', '2024-07-30 12:37:19.000000'),
(282, 0, 'ID', 'uploads/profile_66a0f423cdce2.jpg', '2024-07-30 12:50:19.000000'),
(283, 0, 'ID', 'uploads/profile_66a0f423cdce2.jpg', '2024-07-30 12:52:05.000000'),
(284, 0, 'ID', 'uploads/profile_66a0f423cdce2.jpg', '2024-07-30 12:53:27.000000'),
(287, 0, 'ID', 'uploads/452564300_511082684645186_6631106394661847474_n.jpg', '2024-07-30 13:54:34.000000'),
(288, 0, 'ID', 'uploads/452564300_511082684645186_6631106394661847474_n.jpg', '2024-07-30 14:07:14.000000'),
(289, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-30 14:54:44.000000'),
(290, 0, 'ID', 'uploads/bj.jpg', '2024-07-30 15:07:34.000000'),
(291, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-30 17:57:32.000000'),
(292, 0, 'ID', 'uploads/pnplogo.png', '2024-07-30 18:15:07.000000'),
(293, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-31 11:31:55.000000'),
(294, 0, 'ID', 'uploads/452564300_511082684645186_6631106394661847474_n.jpg', '2024-07-31 11:32:58.000000'),
(295, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-31 12:08:39.000000'),
(296, 0, 'ID', 'uploads/excel.jpg', '2024-07-31 16:15:02.000000'),
(297, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-08-07 13:44:52.000000'),
(298, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-08-07 16:56:54.000000'),
(299, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-08-07 16:58:15.000000'),
(300, 0, 'ID', 'uploads/excel.jpg', '2024-08-07 17:01:56.000000'),
(301, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-08-09 05:25:12.000000'),
(302, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-08-12 11:26:38.000000'),
(303, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-08-14 12:18:16.000000'),
(304, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-08-18 03:59:41.000000'),
(305, 0, 'ID', 'uploads/452564300_511082684645186_6631106394661847474_n.jpg', '2024-08-19 23:57:47.000000'),
(306, 0, 'ID', 'uploads/png-transparent-mikrotik-routerboard-hex-rb750gr3-mikrotik-routeros-peripherals-computer-network-electronics-electronic-device.png', '2024-08-21 12:30:49.000000'),
(307, 0, 'ID', 'uploads/png-clipart-crime-scene-tape-police-tape-barricade-illustration.png', '2024-08-21 12:36:57.000000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_info`
--

CREATE TABLE `tbl_info` (
  `info_id` int(10) NOT NULL,
  `age` int(10) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `birth_date` date NOT NULL,
  `place_of_birth` varchar(50) NOT NULL,
  `civil_status` enum('Single','Married','Divorced','Widowed') NOT NULL,
  `educational_background` enum('Primary','Secondary','Tertiary') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_info`
--

INSERT INTO `tbl_info` (`info_id`, `age`, `gender`, `birth_date`, `place_of_birth`, `civil_status`, `educational_background`) VALUES
(218, 23, 'Male', '2001-02-20', 'gamis', 'Single', 'Tertiary'),
(219, 24, 'Male', '2000-05-05', 'gamis', 'Single', 'Tertiary');

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
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `accountType` enum('Resident','Barangay Official','PNP Officer') NOT NULL,
  `barangays_id` int(11) NOT NULL,
  `pic_data` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `email`, `password`, `accountType`, `barangays_id`, `pic_data`) VALUES
(88, 'bj', 'villanueva', 'Aquino', '', 'mlgaming143@gmail.com', '$2y$10$Zzc0DtBO4RPmA8RgBOoiueAv86p9Sc69r8PpiKRdiCfaPWutYn1L.', 'Barangay Official', 118, 'uploads/profile_66b1054a599f2.jpg'),
(89, 'Excel', 'nnnn', 'Preza', '', 'excel27@gmail.com', '$2y$10$/otNt0hA5d3b3V1xX5nzCOThzRA0.tGdwBq.mBrdvYJCW8AaQdRPW', 'Resident', 119, 'uploads/profile_66b35dfc81120.jpg'),
(90, 'princess', 'Cadiente', 'Rosario', '', 'pinsesa@gmail.com', '$2y$10$zIuH.Hf3SqI0tF7LuU1ep.59VjW.mBFloZw5It0LEllb0G4un6Ese', 'PNP Officer', 120, 'uploads/profile_66b38bf935899.jpg'),
(91, 'reyven', 'ojadas', 'pili', '', 'resident@gmail.com', '$2y$10$eDd76Og1S4TLos2N4J4Y1eLSobv5n5MkSRywhcFUhdFnA4Rc0HhjG', 'Resident', 121, 'uploads/profile_66b58bc469f54.jpg'),
(92, 'denver', 'nnnn', 'gorospe', '', 'barangay@gmail.com', '$2y$10$1AtIP9iYRzjHMlsCEmSAqOI6m7moC3sWnPP1p/7k3V1mnVD1rwE/W', 'Barangay Official', 122, 'uploads/profile_66b58bc469f54.jpg'),
(93, 'Desiray', 'Domael', 'Naya', 'Sr.', 'desiray.d.nayga@isu.edu.ph', '$2y$10$LmMrEMdaLDuLc3Lk3GIey.vNaaMqWfTz0Ozlyafwidp.fypnXkspe', 'Resident', 123, 'uploads/profile_66c3bedba896e.png'),
(94, 'excel', 'nnn', 'preza', '', 'excel@gmail.com', '$2y$10$dmxu96P0WEASLgJMGL9pOuRxpJabK6jwdVo.8cAQB4hot3lZowS.m', 'Resident', 124, 'uploads/profile_66c5c31494af3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_barangay`
--

CREATE TABLE `tbl_users_barangay` (
  `barangays_id` int(11) NOT NULL,
  `barangay_name` varchar(255) NOT NULL,
  `official_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users_barangay`
--

INSERT INTO `tbl_users_barangay` (`barangays_id`, `barangay_name`, `official_id`) VALUES
(99, 'Angoluan', 0),
(100, 'Diasan', 0),
(101, 'Aromin', 0),
(102, 'Aromin', 0),
(103, 'Gucab', 0),
(104, 'Gucab', 0),
(105, 'Gucab', 0),
(106, 'Gucab', 0),
(107, 'Fugu', 0),
(108, 'Angoluan', 0),
(109, 'Angoluan', 0),
(110, 'Gucab', 0),
(111, 'Gucab', 0),
(112, 'Buneg', 0),
(113, 'Babaran', 0),
(114, 'Angoluan', 0),
(115, 'Angoluan', 0),
(116, 'Angoluan', 0),
(117, 'Angoluan', 0),
(118, 'Angoluan', 0),
(119, 'Angoluan', 0),
(120, 'Angoluan', 0),
(121, 'San Fabian', 0),
(122, 'San Fabian', 0),
(123, 'San Fabian', 0),
(124, 'San Fabian', 0);

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
-- Indexes for table `tbl_image`
--
ALTER TABLE `tbl_image`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `tbl_info`
--
ALTER TABLE `tbl_info`
  ADD PRIMARY KEY (`info_id`);

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
  ADD PRIMARY KEY (`barangays_id`),
  ADD KEY `official_id` (`official_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_announcement`
--
ALTER TABLE `tbl_announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_brg_official`
--
ALTER TABLE `tbl_brg_official`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_complaintcategories`
--
ALTER TABLE `tbl_complaintcategories`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  MODIFY `complaints_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=290;

--
-- AUTO_INCREMENT for table `tbl_image`
--
ALTER TABLE `tbl_image`
  MODIFY `image_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=308;

--
-- AUTO_INCREMENT for table `tbl_info`
--
ALTER TABLE `tbl_info`
  MODIFY `info_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `tbl_users_barangay`
--
ALTER TABLE `tbl_users_barangay`
  MODIFY `barangays_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_brg_official`
--
ALTER TABLE `tbl_brg_official`
  ADD CONSTRAINT `tbl_brg_official_ibfk_1` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  ADD CONSTRAINT `tbl_complaints_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `tbl_complaintcategories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_complaints_ibfk_3` FOREIGN KEY (`image_id`) REFERENCES `tbl_image` (`image_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_complaints_ibfk_4` FOREIGN KEY (`info_id`) REFERENCES `tbl_info` (`info_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_complaints_ibfk_5` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_evidence`
--
ALTER TABLE `tbl_evidence`
  ADD CONSTRAINT `tbl_evidence_ibfk_1` FOREIGN KEY (`complaints_id`) REFERENCES `tbl_complaints` (`complaints_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_2` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
