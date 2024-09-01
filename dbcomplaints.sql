-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2024 at 01:37 AM
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
(43, 'trisha', 'Barangay Captain', '../uploads/fvdfv(50).jpg', 122, 1),
(44, 'princess Rosario', 'Kagawad 1', '../uploads/457368489_528714303027535_3753519382645185170_n.jpg', 122, 0),
(45, 'bj', 'Barangay Captain', '../uploads/bj.jpg', 122, 0),
(46, 'reyven', 'Kagawad 4', '../uploads/profile_66b58b8079dfe.jpg', 122, 0),
(47, 'trisha', 'Kagawad 3', '../uploads/454161426_422473144170475_7745676165870487336_n.jpg', 122, 0),
(48, 'princess', 'Barangay Captain', '../uploads/457368489_528714303027535_3753519382645185170_n.jpg', 121, 0),
(49, 'bj', 'Kagawad 1', '../uploads/bj.jpg', 121, 0),
(50, 'reyven', 'Kagawad 3', '../uploads/profile_66cc76ae2ecec.jpg', 121, 0),
(51, 'excel', 'Kagawad 2', '../uploads/Screenshot 2024-06-20 153343.png', 121, 0);

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
(250, 'Rape'),
(251, 'magnanakaw'),
(252, 'punching bag'),
(253, 'booksing'),
(254, 'ket ana ngay'),
(255, 'hays'),
(256, 'holdaper'),
(257, 'Physical Injuries Inflicted in a Tumultuous Affray (Art. 252)'),
(258, 'Other'),
(259, 'princess  parang mabait sa bahay');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaints`
--

CREATE TABLE `tbl_complaints` (
  `complaints_id` int(15) NOT NULL,
  `complaint_name` varchar(255) NOT NULL,
  `cp_number` varchar(10) NOT NULL,
  `complaints_person` varchar(15) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '''Inprogress''',
  `complaints` text NOT NULL,
  `responds` enum('barangay','pnp') NOT NULL,
  `date_filed` date NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `category_id` int(1) NOT NULL,
  `barangays_id` int(10) NOT NULL,
  `image_id` int(10) NOT NULL,
  `info_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hearing_date` date DEFAULT NULL,
  `hearing_time` varchar(255) DEFAULT NULL,
  `hearing_type` enum('First Hearing','Second Hearing','Third Hearing') DEFAULT NULL,
  `hearing_status` varchar(255) NOT NULL DEFAULT '''In Progress''',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_complaints`
--

INSERT INTO `tbl_complaints` (`complaints_id`, `complaint_name`, `cp_number`, `complaints_person`, `status`, `complaints`, `responds`, `date_filed`, `approved`, `category_id`, `barangays_id`, `image_id`, `info_id`, `user_id`, `hearing_date`, `hearing_time`, `hearing_type`, `hearing_status`, `updated_at`) VALUES
(288, 'reyven ojadas pili ', '0909850347', 'bj', 'settled_in_barangay', 'dsdsd', 'barangay', '2024-08-21', 0, 250, 121, 306, 218, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(289, 'excel nnn preza ', '0927142858', 'eso', 'Settled in PNP', 'sdsdsds', 'pnp', '2024-08-21', 0, 250, 124, 307, 219, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(290, 'reyven ojadas pili ', '0909850347', 'bjsd', 'Completed', 'fxgdfgdgd', 'barangay', '2024-08-22', 0, 250, 121, 308, 220, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(291, 'denver nnnn gorospe ', '0909850347', 'bj', 'Filed in the court', 'sdsdsd', 'pnp', '2024-08-24', 0, 250, 122, 309, 221, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(292, 'brayan villanueva Aquino ', '0909850347', 'bj', 'pnp', 'asasas', 'pnp', '2024-08-25', 0, 250, 121, 310, 222, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(293, 'brayan villanueva Aquino ', '0909850347', 'bj', 'settled_in_barangay', 'dsd', 'barangay', '2024-08-25', 0, 250, 121, 311, 223, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(295, 'brayan villanueva Aquino ', '0909850347', 'GIN', 'settled_in_barangay', 'sdsdsd', 'barangay', '2024-08-26', 0, 250, 121, 313, 225, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(296, 'brayan villanueva Aquino ', '0909850347', 'eso', 'Settled in PNP', 'sndjsdnsd', 'pnp', '2024-08-26', 0, 250, 121, 314, 226, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(297, 'brayan villanueva Aquino ', '0927142858', 'GINffsfdfdfdf', 'settled_in_barangay', 'asasas', 'barangay', '2024-08-26', 0, 250, 121, 315, 227, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(298, 'trisha Nicole Yaranon ', '0909850347', 'bj', 'Approved', 'asasa', 'barangay', '2024-08-26', 0, 250, 125, 316, 228, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(299, 'brayan villanueva Aquino ', '0909850347', 'sxss', 'settled_in_barangay', 'Sasasa', 'barangay', '2024-08-27', 0, 250, 121, 317, 229, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(300, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Filed in the court', 'sdsdsd', 'pnp', '2024-08-27', 0, 250, 121, 318, 230, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(302, 'leslie Pascual Rigor ', '0909850347', 'GIN', 'Filed in the court', 'sdsds', 'pnp', '2024-08-28', 0, 250, 127, 320, 232, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(303, 'leslie Pascual Rigor ', '0909850347', 'GIN', 'Settled', 'sdsds', 'pnp', '2024-08-28', 0, 250, 127, 321, 233, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(304, 'leslie Pascual Rigor ', '0909850347', 'sdsds', 'settled_in_barangay', 'ddcdcd', 'barangay', '2024-08-28', 0, 250, 127, 322, 234, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(305, 'brayan villanueva Aquino ', '0909850347', 'eso', 'Settled in PNP', 'cxcxc', 'pnp', '2024-08-28', 0, 250, 121, 323, 235, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(306, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Rejected', 'sdsdsd', 'barangay', '2024-08-28', 0, 250, 121, 324, 236, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(307, 'brayan villanueva Aquino ', '0909850347', 'bj', 'settled_in_barangay', 'vdfvxv', 'barangay', '2024-08-28', 0, 250, 121, 325, 237, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(308, 'brayan villanueva Aquino ', 'dssdsdsdsd', 'bj', 'Rejected', 'cdsfsfsff', 'barangay', '2024-08-28', 0, 250, 121, 326, 238, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(309, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Rejected', 'dsds', 'barangay', '2024-08-29', 0, 250, 121, 327, 239, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(310, 'brayan villanueva Aquino ', '0927142858', 'eeeeeeeeeeeeeee', 'settled_in_barangay', 'xzxz', 'barangay', '2024-08-29', 0, 251, 121, 328, 240, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(311, 'brayan villanueva Aquino ', '0909850347', 'bungol', 'Rejected', 'tangina', 'barangay', '2024-08-29', 0, 252, 121, 329, 241, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(312, 'bungol kha bhou ', '0909850347', 'monks', 'Filed in the court', 'diko alam bigla nalang nila ako sinagasahan', 'pnp', '2024-08-29', 0, 253, 137, 330, 242, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(313, 'bungol kha bhou ', '0927142858', 'aneluv', 'Rejected', 'dik ammo', 'barangay', '2024-08-29', 0, 254, 137, 331, 243, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(314, 'bungol kha bhou ', '0927142858', 'kabbo', 'Rejected', 'legit fr fr ', 'barangay', '2024-08-29', 0, 255, 137, 332, 244, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(315, 'bungol kha bhou ', '0909850347', 'rubirt', 'Rejected', 'ukiribit', 'barangay', '2024-08-29', 0, 250, 137, 333, 245, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(316, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Unresolved', 'dssd', 'barangay', '2024-08-29', 0, 256, 121, 334, 246, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(317, 'din da xy ', '0978544696', 'harel', 'Unresolved', 'nyametin', 'barangay', '2024-08-29', 0, 250, 138, 335, 248, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(318, 'din da xy ', '0967544693', 'harel', 'Inprogress', 'uhrieur3rj3rj', 'barangay', '2024-08-29', 0, 250, 138, 336, 252, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(319, 'din da xy ', '0909850347', 'bungal', 'Inprogress', 'huhuhuhuhuhu', 'barangay', '2024-08-29', 0, 257, 138, 337, 253, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(320, 'brayan villanueva Aquino ', '0909850347', 'bbnbn', 'Unresolved', 'sdfsfs', 'barangay', '2024-08-29', 0, 258, 121, 338, 254, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26'),
(321, 'brayan villanueva Aquino ', '0909850347', 'bj', 'settled_in_barangay', 'dada', 'barangay', '2024-08-29', 0, 258, 121, 339, 255, 0, NULL, NULL, NULL, '', '2024-08-31 06:06:03'),
(322, 'brayan villanueva Aquino ', '0909850347', 'bj', 'settled_in_barangay', 'dsdsd', 'barangay', '2024-08-29', 0, 251, 121, 340, 256, 0, '2024-09-07', '01:40:00 PM', 'First Hearing', '', '2024-08-31 06:05:10'),
(323, 'brayan villanueva Aquino ', '0909850347', 'bj', 'settled_in_barangay', 'dssrssr', 'barangay', '2024-08-29', 0, 259, 121, 341, 257, 0, NULL, NULL, NULL, '', '2024-08-31 02:26:26');

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
(0, 289, 'uploads/454161426_422473144170475_7745676165870487336_n.jpg', '2024-08-21'),
(0, 290, 'uploads/a05f7b98-6076-4ee8-824f-387507e042bf.mp4', '2024-08-22'),
(0, 291, 'uploads/bj.jpg', '2024-08-24'),
(0, 292, 'uploads/bj.jpg', '2024-08-25'),
(0, 293, 'uploads/bj.jpg', '2024-08-25'),
(0, 295, 'uploads/435559638_928560769060280_7584294412764526119_n.jpg', '2024-08-26'),
(0, 296, 'uploads/bj.jpg', '2024-08-26'),
(0, 297, 'uploads/435559638_928560769060280_7584294412764526119_n.jpg', '2024-08-26'),
(0, 298, 'uploads/profile_669dc9bcee9df.jpg', '2024-08-26'),
(0, 299, 'uploads/Screenshot (9).png', '2024-08-27'),
(0, 300, 'uploads/Screenshot (9).png', '2024-08-27'),
(0, 302, 'uploads/Blue, Yellow and White Elegant Graduation Party Invitation Landscape.png', '2024-08-28'),
(0, 303, '../uploads/Blue, Yellow and White Elegant Graduation Party Invitation Landscape.png', '2024-08-28'),
(0, 304, '../uploads/png-transparent-mikrotik-routerboard-hex-rb750gr3-mikrotik-routeros-peripherals-computer-network-electronics-electronic-device.png', '2024-08-28'),
(0, 305, '../uploads/complaint.jpg', '2024-08-28'),
(0, 306, '../uploads/poles.jpg', '2024-08-28'),
(0, 307, '../uploads/poles.jpg', '2024-08-28'),
(0, 308, '../uploads/Screenshot (10).png', '2024-08-28'),
(0, 309, '../uploads/Screenshot (9).png', '2024-08-29'),
(0, 310, '../uploads/Screenshot (10).png', '2024-08-29'),
(0, 311, '../uploads/Screenshot 2024-06-26 131524.png', '2024-08-29'),
(0, 312, '../uploads/Screenshot 2024-07-05 193743.png', '2024-08-29'),
(0, 313, '../uploads/princess taba.jpg', '2024-08-29'),
(0, 314, '../uploads/Screenshot (41).png', '2024-08-29'),
(0, 315, '../uploads/Screenshot (19).png', '2024-08-29'),
(0, 316, '../uploads/Screenshot (5).png', '2024-08-29'),
(0, 317, '../uploads/pnp interview.jpg', '2024-08-29'),
(0, 318, '../uploads/Screenshot (47).png', '2024-08-29'),
(0, 319, '../uploads/Screenshot (6).png', '2024-08-29'),
(0, 320, '../uploads/Screenshot (10).png', '2024-08-29'),
(0, 321, '../uploads/Screenshot (10).png', '2024-08-29'),
(0, 322, '../uploads/Screenshot (10).png', '2024-08-29'),
(0, 323, '../uploads/Screenshot (10).png', '2024-08-29');

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
(307, 0, 'ID', 'uploads/png-clipart-crime-scene-tape-police-tape-barricade-illustration.png', '2024-08-21 12:36:57.000000'),
(308, 0, 'ID', 'uploads/profile_66c5c31494af3.jpg', '2024-08-22 13:20:31.000000'),
(309, 0, 'ID', 'uploads/corruption.png', '2024-08-24 15:12:34.000000'),
(310, 0, 'ID', 'uploads/bj.jpg', '2024-08-25 08:26:49.000000'),
(311, 0, 'ID', 'uploads/corruption.png', '2024-08-25 08:38:03.000000'),
(312, 0, 'ID', 'uploads/Screenshot 2024-06-20 153343.png', '2024-08-26 11:31:57.000000'),
(313, 0, 'ID', 'uploads/profile_66c3bedba896e.png', '2024-08-26 11:59:58.000000'),
(314, 0, 'ID', 'uploads/corruption.png', '2024-08-26 12:02:49.000000'),
(315, 0, 'ID', 'uploads/bj.jpg', '2024-08-26 12:12:48.000000'),
(316, 0, 'ID', 'uploads/profile_669f97e4e72ba.png', '2024-08-26 14:36:51.000000'),
(317, 0, 'ID', 'uploads/Screenshot 2024-06-20 153343.png', '2024-08-27 14:54:28.000000'),
(318, 0, 'ID', 'uploads/Screenshot 2024-06-20 153343.png', '2024-08-27 14:59:25.000000'),
(319, 0, 'ID', 'uploads/grad.png', '2024-08-28 02:03:36.000000'),
(320, 0, 'ID', 'uploads/download-removebg-preview.png', '2024-08-28 02:06:13.000000'),
(321, 0, 'ID', '../uploads/download-removebg-preview.png', '2024-08-28 02:36:44.000000'),
(322, 0, 'ID', '../uploads/delete.png', '2024-08-28 02:37:45.000000'),
(323, 0, 'ID', '../uploads/poles.jpg', '2024-08-28 08:09:16.000000'),
(324, 0, 'ID', '../uploads/excel.jpg', '2024-08-28 09:14:46.000000'),
(325, 0, 'ID', '../uploads/poles.jpg', '2024-08-28 13:08:03.000000'),
(326, 0, 'ID', '../uploads/Screenshot 2024-06-20 153520.png', '2024-08-28 13:50:04.000000'),
(327, 0, 'ID', '../uploads/Screenshot (10).png', '2024-08-29 02:15:37.000000'),
(328, 0, 'ID', '../uploads/Screenshot 2024-06-20 153520.png', '2024-08-29 03:19:58.000000'),
(329, 0, 'ID', '../uploads/bj.jpg', '2024-08-29 03:59:07.000000'),
(330, 0, 'ID', '../uploads/Screenshot (31).png', '2024-08-29 04:10:32.000000'),
(331, 0, 'ID', '../uploads/Screenshot (27).png', '2024-08-29 04:14:41.000000'),
(332, 0, 'ID', '../uploads/Screenshot (3).png', '2024-08-29 04:46:54.000000'),
(333, 0, 'ID', '../uploads/Screenshot (41).png', '2024-08-29 05:10:52.000000'),
(334, 0, 'ID', '../uploads/Screenshot (11).png', '2024-08-29 13:58:09.000000'),
(335, 0, 'ID', '../uploads/Screenshot (44).png', '2024-08-29 14:05:43.000000'),
(336, 0, 'ID', '../uploads/Screenshot (44).png', '2024-08-29 14:10:38.000000'),
(337, 0, 'ID', '../uploads/Screenshot 2024-06-20 153520.png', '2024-08-29 16:09:31.000000'),
(338, 0, 'ID', '../uploads/Screenshot 2024-06-20 153520.png', '2024-08-29 16:51:27.000000'),
(339, 0, 'ID', '../uploads/Screenshot 2024-06-20 153343.png', '2024-08-29 16:54:34.000000'),
(340, 0, 'ID', '../uploads/Screenshot 2024-06-20 153520.png', '2024-08-29 17:04:16.000000'),
(341, 0, 'ID', '../uploads/Screenshot 2024-06-20 153520.png', '2024-08-29 17:06:47.000000');

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
(219, 24, 'Male', '2000-05-05', 'gamis', 'Single', 'Tertiary'),
(220, 23, 'Male', '2001-06-22', 'gamis', 'Single', 'Tertiary'),
(221, 21, 'Male', '2003-02-02', 'Bohol', 'Widowed', 'Tertiary'),
(222, 24, 'Male', '2000-02-02', 'Bohol', 'Married', 'Tertiary'),
(223, 24, 'Male', '2000-02-02', 'SA TABI', 'Single', 'Tertiary'),
(224, 23, 'Male', '2001-06-03', 'gamis', 'Single', 'Secondary'),
(225, 23, 'Male', '2001-06-01', 'gamis', 'Single', 'Tertiary'),
(226, 22, 'Male', '2001-08-31', 'gamis', 'Single', 'Primary'),
(227, 23, 'Male', '2001-07-26', 'Bohol', 'Single', 'Tertiary'),
(228, 23, 'Male', '2001-02-02', 'gamis', 'Single', 'Tertiary'),
(229, 24, 'Male', '2000-02-03', 'SA TABI', 'Single', 'Tertiary'),
(230, 24, 'Male', '2000-03-03', 'gamis', 'Single', 'Tertiary'),
(231, 24, 'Male', '2000-02-06', 'gamis', 'Single', 'Tertiary'),
(232, 24, 'Male', '2000-02-03', 'gamis', 'Single', 'Tertiary'),
(233, 0, 'Male', '2000-02-03', 'gamis', 'Single', 'Tertiary'),
(234, 24, 'Male', '2000-02-03', 'gamis', 'Single', 'Tertiary'),
(235, 24, 'Male', '2000-02-03', 'gamis', 'Single', 'Tertiary'),
(236, 23, 'Male', '2001-02-20', 'gamis', 'Single', 'Primary'),
(237, 24, 'Male', '2000-02-02', 'SA TABI', 'Married', 'Secondary'),
(238, 24, 'Male', '2000-01-31', 'gamis', 'Single', 'Primary'),
(239, 23, 'Female', '2001-02-03', 'SA TABI', 'Single', 'Primary'),
(240, -17976, 'Female', '0000-00-00', 'SA TABI ng daan', 'Single', 'Tertiary'),
(241, 35, 'Female', '1989-03-23', 'sa rantay', 'Married', 'Secondary'),
(242, 42, 'Male', '1982-02-22', 'sa damo', 'Divorced', 'Secondary'),
(243, 50, 'Female', '1973-09-28', 'sa tabiiiiii', 'Widowed', 'Secondary'),
(244, 45, 'Male', '1978-12-22', 'dko ammo', 'Married', 'Primary'),
(245, 21, 'Male', '2003-08-28', 'sa damo', 'Widowed', 'Secondary'),
(246, 24, 'Male', '2000-02-02', 'sa rantay', 'Married', 'Secondary'),
(248, 79, 'Male', '1945-08-22', 'fugawers', 'Married', 'Secondary'),
(252, 35, 'Male', '1988-09-22', 'fugawers', 'Married', 'Secondary'),
(253, 25, 'Male', '1999-03-22', 'sa rangtay', 'Married', 'Secondary'),
(254, 23, 'Male', '2001-02-03', 'sa damo', 'Single', 'Primary'),
(255, 24, 'Male', '2000-02-03', 'sa rantay', 'Single', 'Primary'),
(256, 24, 'Male', '2000-02-03', 'sa damo', 'Single', 'Primary'),
(257, 24, 'Male', '2000-02-02', 'sa rantay', 'Single', 'Primary');

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
  `pic_data` varchar(255) NOT NULL,
  `security_question1` varchar(255) NOT NULL,
  `security_answer1` varchar(255) NOT NULL,
  `security_question2` varchar(255) NOT NULL,
  `security_answer2` varchar(255) NOT NULL,
  `security_question3` varchar(255) NOT NULL,
  `security_answer3` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `email`, `password`, `accountType`, `barangays_id`, `pic_data`, `security_question1`, `security_answer1`, `security_question2`, `security_answer2`, `security_question3`, `security_answer3`) VALUES
(88, 'bj', 'villanueva', 'Aquino', '', 'mlgaming143@gmail.com', '$2y$10$Zzc0DtBO4RPmA8RgBOoiueAv86p9Sc69r8PpiKRdiCfaPWutYn1L.', 'Barangay Official', 118, 'uploads/profile_66b1054a599f2.jpg', '', '', '', '', '', ''),
(89, 'Excel', 'nnnn', 'Preza', '', 'excel27@gmail.com', '$2y$10$/otNt0hA5d3b3V1xX5nzCOThzRA0.tGdwBq.mBrdvYJCW8AaQdRPW', 'Resident', 119, 'uploads/profile_66b35dfc81120.jpg', '', '', '', '', '', ''),
(90, 'princess', 'Cadiente', 'Rosario', '', 'pinsesa@gmail.com', '$2y$10$zIuH.Hf3SqI0tF7LuU1ep.59VjW.mBFloZw5It0LEllb0G4un6Ese', 'PNP Officer', 120, 'uploads/441799097_1205342757295168_7001114824101829619_n.jpg', '', '', '', '', '', ''),
(91, 'brayan', 'villanueva', 'Aquino', '', 'resident@gmail.com', '$2y$10$eDd76Og1S4TLos2N4J4Y1eLSobv5n5MkSRywhcFUhdFnA4Rc0HhjG', 'Resident', 121, '../uploads/excel.jpg', '', '', '', '', '', ''),
(92, 'excel', 'nnnn', 'preza', '', 'barangay@gmail.com', '$2y$10$1AtIP9iYRzjHMlsCEmSAqOI6m7moC3sWnPP1p/7k3V1mnVD1rwE/W', 'Barangay Official', 122, '../uploads/profile_669f988fa9760.jpg', '', '', '', '', '', ''),
(93, 'Desiray', 'Domael', 'Naya', 'Sr.', 'desiray.d.nayga@isu.edu.ph', '$2y$10$LmMrEMdaLDuLc3Lk3GIey.vNaaMqWfTz0Ozlyafwidp.fypnXkspe', 'Resident', 123, 'uploads/profile_66c3bedba896e.png', '', '', '', '', '', ''),
(94, 'excel', 'nnn', 'preza', '', 'excel@gmail.com', '$2y$10$dmxu96P0WEASLgJMGL9pOuRxpJabK6jwdVo.8cAQB4hot3lZowS.m', 'Resident', 124, 'uploads/profile_66c5c31494af3.jpg', '', '', '', '', '', ''),
(95, 'trisha', 'Nicole', 'Yaranon', '', 'tien@gmail.com', '$2y$10$KnQWks8rK02R/qfLFtnNsegU/IyHYnfvetaWVLKEZvzObHqKdN2ky', 'Resident', 125, 'uploads/profile_66cc76ae2ecec.jpg', '', '', '', '', '', ''),
(96, 'Din', 'Dax', 'Xhy', '', 'dindaxy@gmail.com', '$2y$10$9b.lK3kNa7A40HVZBedcBeO5jncGOZ7QiZxvc9GU2/JEfJhag5cSa', 'Barangay Official', 126, 'uploads/profile_66cc76b5658e5.jpeg', '', '', '', '', '', ''),
(97, 'leslie', 'Pascual', 'Rigor', '', 'leslie05@gmail.com', '$2y$10$MV7WniHlIWdVo8ieNdWdSOCwimXVu7VhpPPDOYT8OTsd6LqlbZdQG', 'Resident', 127, '../uploads6f82caab-9d12-4df9-bc91-742bb7cb9bf3.jpg', '', '', '', '', '', ''),
(98, 'ddd', 'Mondragon', 'Aquino', '', 'eso@gmail.com', '$2y$10$d6DNp3zHZCykQltVZx4aCecMjvsCO8LkL5X5sRGsO3FKANJN9Hk7C', 'Resident', 129, '../uploads/profile_66cc76ae2ecec.jpg', '', '', '', '', '', ''),
(99, 'laymar', 'versosa', 'mina', '', 'laymar@gmail.com', '$2y$10$bfbvgTbaiANpL1OO/MIog.6KKxjWlMRpIVjzbdFtvaeebpscklSFC', 'Barangay Official', 130, '../uploads/1.png', '', '', '', '', '', ''),
(100, 'excel', 'nnn', 'preza', '', 'lex@gmail.com', '$2y$10$FAvUBwgHohiKj37AT1IwXe7qgS1bQdFgbSW1Gp3C4z2ZSRJqH9jxS', 'Barangay Official', 131, 'uploads/profile_66cea6e841a1a.jpg', '', '', '', '', '', ''),
(101, 'allen', 'esteban', 'serrano', '', 'allen@gmail.com', '$2y$10$yjcNd3nKwwpxhB265dCQFOCNPq28QwBMUfU4WrELypLJFJ.lJhkVq', 'Barangay Official', 132, 'uploads/profile_66cea934ad628.jpg', '', '', '', '', '', ''),
(102, 'Princess', 'Cadiente', 'Rosario', '', 'cess@gmail.com', '$2y$10$/KbWqX4vMkcprx.tiMvTl.QX4VDt0vDXYF9XgyKr1gp.y33E2QOoS', 'Barangay Official', 133, './uploads/profile_66cea9a314694.jpg', '', '', '', '', '', ''),
(103, 'excel', 'Cadiente', 'preza', '', 'mlgaming142@gmail.com', '$2y$10$OV/KIBgAxwxfqZSOM/b2hebTveBi3BV/FK.Qd.3/vqqt9GmRQs.WS', 'Resident', 134, '../uploads/441799097_1205342757295168_7001114824101829619_n.jpg', '', '', '', '', '', ''),
(104, 'eso', 'villanueva', 'Rosario', '', 'tienxxx@gmail.com', '$2y$10$rkNcO9Cp2u/DdcbRdrk.X.mIMv48DvdFynIDzp64KNlEcIKTYPJfe', 'Barangay Official', 136, '../uploads/profile_66ceaf48b9e1c.jpg', '', '', '', '', '', ''),
(105, 'bungol', 'kha', 'bhou', '', 'bungol@gmail.com', '$2y$10$mXFbXS.tFrXURu3m2cODR.CzpBeDaJ35dKTB/2f7dY8Qd5g9NNYFK', 'Resident', 137, '../uploads/profile_66cfd6ffeb536.png', '', '', '', '', '', ''),
(106, 'din', 'da', 'xy', '', 'risidint@gmail.com', '$2y$10$AVEVUDQV7pE8TmUQ7ZOx9e8nTRp2Sjic9IJyhcn18vC9nN4fpQldq', 'Resident', 138, '../uploads/profile_66d0638aa9715.png', '', '', '', '', '', ''),
(107, 'haryl', 'Balla', 'conceptoin', '', 'h@gmail.com', '$2y$10$gnKdPhTiwwMkZrVuixAcUe7Pi/K3tcZUWkRpLwZ.Izf2MQdEG6jMW', 'Barangay Official', 139, '../uploads/profile_66d066dee6955.jpg', '', '', '', '', '', '');

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
(124, 'San Fabian', 0),
(125, 'Fugu', 0),
(126, 'Fugu', 0),
(127, 'San Fabian', 0),
(128, 'Angoluan', 0),
(129, 'Babaran', 0),
(130, 'San Fabian', 0),
(131, 'San Fabian', 0),
(132, 'San Fabian', 0),
(133, 'Garit Norte', 0),
(134, 'San Juan', 0),
(135, 'Angoluan', 0),
(136, 'Angoluan', 0),
(137, 'San Fabian', 0),
(138, 'Fugu', 0),
(139, 'Fugu', 0);

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
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `tbl_complaintcategories`
--
ALTER TABLE `tbl_complaintcategories`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- AUTO_INCREMENT for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  MODIFY `complaints_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=324;

--
-- AUTO_INCREMENT for table `tbl_image`
--
ALTER TABLE `tbl_image`
  MODIFY `image_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;

--
-- AUTO_INCREMENT for table `tbl_info`
--
ALTER TABLE `tbl_info`
  MODIFY `info_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `tbl_users_barangay`
--
ALTER TABLE `tbl_users_barangay`
  MODIFY `barangays_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

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
