-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2024 at 03:19 PM
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
(37, 'sfsfsfs', 'adssd', '2024-09-14', '../uploads/uploads456864062_827865415993505_5703441178082039818_n.jpg', 0, 0);

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
(63, 'bj', 'Kagawad 1', '../uploads/bj.jpg', 118, 1),
(64, 'bj aquino', 'Barangay Captain', '../uploads/bj.jpg', 118, 0),
(65, 'wehhhhhhhh', 'Kagawad 1', '../uploads/uploads456864062_827865415993505_5703441178082039818_n.jpg', 118, 1),
(66, 'princess', 'Kagawad 1', '../uploads/uploads456864062_827865415993505_5703441178082039818_n.jpg', 118, 0),
(67, 'reyven', 'Kagawad 2', '../uploads/1.jpg', 118, 0);

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
(259, 'princess  parang mabait sa bahay'),
(260, 'ahdjbdjad'),
(261, 'ererer'),
(262, 'asasasasas'),
(263, 'Alarms and Scandals (Art. 155)'),
(264, 'Using False Certificates (Art. 175)'),
(265, 'Giving Assistance to Consummated Suicide (Art. 253)'),
(266, 'Abandoning a minor (a child under seven (7) years old) (Art. 276)'),
(267, 'Issuing checks without sufficient funds (B.P. 22)'),
(268, 'Special cases of malicious mischief (if the value of the damaged property does not exceed Php1,000.00 Art. 328)'),
(269, 'Responsibility of Participants in a Duel if only Physical Injuries are Inflicted or No Physical Injuries have been Inflicted (Art. 260)'),
(270, 'Fencing of stolen properties if the property involved is not more than Php50.00 (P.D. 1612)'),
(271, 'Intriguing against honor (Art. 364)'),
(272, 'Incriminating innocent persons (Art. 363)'),
(273, 'Prohibiting publication of acts referred to in the course of official proceedings (Art. 357)'),
(274, 'Threatening to publish and offer to prevent such publication for compensation (Art. 356)'),
(275, 'Acts of lasciviousness with the consent of the offended party (Art. 339)'),
(276, 'Other mischiefs (if the value of the damaged property does not exceed Php1,000.00) (Art. 329)'),
(277, 'Removal, sale or pledge of mortgaged property (Art. 319)'),
(278, 'Swindling or estafa (if the amount does not exceed Php200.00) (Art. 315)'),
(279, 'Occupation of real property or usurpation of real rights in property (Art. 312)'),
(280, 'Qualified theft (if the amount does not exceed Php500) (Art. 310)'),
(281, 'Other similar coercions (compulsory purchase of merchandise and payment of wages by means of tokens) (Art. 288)'),
(282, 'Theft (if the value of the property stolen does not exceed Php50.00) (Art. 309)'),
(283, 'Revealing secrets with abuse of authority (Art. 291)'),
(284, 'Formation, maintenance and prohibition of combination of capital or labor through violence or threats (Art. 289)'),
(285, 'Unlawful Use of Means of Publication and Unlawful Utterances (Art. 154)'),
(286, 'Using Fictitious Names and Concealing True Names (Art. 178)'),
(287, 'Illegal Use of Uniforms and Insignias (Art. 179)'),
(288, 'Less serious physical injuries (Art. 265)'),
(289, 'Slight physical injuries and maltreatment (Art. 266)'),
(290, 'Unlawful arrest (Art. 269)'),
(291, 'Inducing a minor to abandon his/her home (Art. 271)'),
(292, 'Abandonment of a person in danger and abandonment of one’s own victim (Art. 275)'),
(293, 'Abandonment of a minor by persons entrusted with his/her custody; indifference of parents (Art. 277)'),
(294, 'Qualified trespass to dwelling (without the use of violence and intimidation) (Art. 280)'),
(295, 'Other forms of trespass (Art. 281)'),
(296, 'Light threats (Art. 283)'),
(297, 'Simple seduction (Art. 338)'),
(298, 'nkaw'),
(299, 'Discovering secrets through seizure and correspondence (Art. 290)'),
(300, 'pumatay');

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
  `category_id` int(1) NOT NULL,
  `barangays_id` int(10) NOT NULL,
  `image_id` int(10) NOT NULL,
  `info_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_complaints`
--

INSERT INTO `tbl_complaints` (`complaints_id`, `complaint_name`, `cp_number`, `complaints_person`, `status`, `complaints`, `responds`, `date_filed`, `category_id`, `barangays_id`, `image_id`, `info_id`, `user_id`) VALUES
(288, 'reyven ojadas pili ', '0909850347', 'bj', 'settled_in_barangay', 'dsdsd', 'barangay', '2024-01-21', 250, 121, 306, 218, 0),
(289, 'excel nnn preza ', '0927142858', 'eso', 'Filed in the court', 'sdsdsds', 'pnp', '2024-01-21', 250, 124, 307, 219, 0),
(290, 'reyven ojadas pili ', '0909850347', 'bjsd', 'Completed', 'fxgdfgdgd', 'barangay', '2024-08-22', 250, 121, 308, 220, 0),
(291, 'denver nnnn gorospe ', '0909850347', 'bj', 'Filed in the court', 'sdsdsd', 'pnp', '2024-08-24', 250, 122, 309, 221, 0),
(292, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'asasas', 'pnp', '2024-08-25', 250, 121, 310, 222, 0),
(293, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'dsd', 'barangay', '2024-08-25', 250, 121, 311, 223, 0),
(295, 'brayan villanueva Aquino ', '0909850347', 'GIN', 'Read', 'sdsdsd', 'barangay', '2024-08-26', 250, 121, 313, 225, 0),
(296, 'brayan villanueva Aquino ', '0909850347', 'eso', 'Read', 'sndjsdnsd', 'pnp', '2022-03-26', 250, 121, 314, 226, 0),
(297, 'brayan villanueva Aquino ', '0927142858', 'GINffsfdfdfdf', 'Read', 'asasas', 'barangay', '2024-08-26', 250, 121, 315, 227, 0),
(298, 'trisha Nicole Yaranon ', '0909850347', 'bj', 'Approved', 'asasa', 'barangay', '2024-08-26', 250, 125, 316, 228, 0),
(299, 'brayan villanueva Aquino ', '0909850347', 'sxss', 'Read', 'Sasasa', 'barangay', '2024-08-27', 250, 121, 317, 229, 0),
(300, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Filed in the court', 'sdsdsd', 'pnp', '2024-04-27', 250, 121, 318, 230, 0),
(302, 'leslie Pascual Rigor ', '0909850347', 'GIN', 'Filed in the court', 'sdsds', 'pnp', '2024-08-28', 250, 127, 320, 232, 0),
(303, 'leslie Pascual Rigor ', '0909850347', 'GIN', 'Settled', 'sdsds', 'pnp', '2024-05-28', 250, 127, 321, 233, 0),
(304, 'leslie Pascual Rigor ', '0909850347', 'sdsds', 'settled_in_barangay', 'ddcdcd', 'barangay', '2024-08-28', 250, 127, 322, 234, 0),
(305, 'brayan villanueva Aquino ', '0909850347', 'eso', 'Filed in the court', 'cxcxc', 'pnp', '2024-06-28', 250, 121, 323, 235, 0),
(306, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'sdsdsd', 'barangay', '2024-06-28', 250, 121, 324, 236, 0),
(307, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'vdfvxv', 'barangay', '2024-08-28', 250, 121, 325, 237, 0),
(308, 'brayan villanueva Aquino ', 'dssdsdsdsd', 'bj', 'Read', 'cdsfsfsff', 'barangay', '2024-08-28', 250, 121, 326, 238, 0),
(309, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'dsds', 'barangay', '2024-08-29', 250, 121, 327, 239, 0),
(310, 'brayan villanueva Aquino ', '0927142858', 'eeeeeeeeeeeeeee', 'Read', 'xzxz', 'barangay', '2024-08-29', 251, 121, 328, 240, 0),
(311, 'brayan villanueva Aquino ', '0909850347', 'bungol', 'Read', 'tangina', 'barangay', '2024-08-29', 252, 121, 329, 241, 0),
(312, 'bungol kha bhou ', '0909850347', 'monks', 'Filed in the court', 'diko alam bigla nalang nila ako sinagasahan', 'pnp', '2024-08-29', 253, 137, 330, 242, 0),
(313, 'bungol kha bhou ', '0927142858', 'aneluv', 'Rejected', 'dik ammo', 'barangay', '2009-08-29', 254, 137, 331, 243, 0),
(314, 'bungol kha bhou ', '0927142858', 'kabbo', 'Rejected', 'legit fr fr ', 'barangay', '2008-08-29', 255, 137, 332, 244, 0),
(315, 'bungol kha bhou ', '0909850347', 'rubirt', 'Rejected', 'ukiribit', 'barangay', '2024-08-29', 250, 137, 333, 245, 0),
(316, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'dssd', 'barangay', '2024-08-29', 256, 121, 334, 246, 0),
(317, 'din da xy ', '0978544696', 'harel', 'Unresolved', 'nyametin', 'barangay', '2024-08-29', 250, 138, 335, 248, 0),
(318, 'din da xy ', '0967544693', 'harel', 'Inprogress', 'uhrieur3rj3rj', 'barangay', '2024-08-29', 250, 138, 336, 252, 0),
(319, 'din da xy ', '0909850347', 'bungal', 'Inprogress', 'huhuhuhuhuhu', 'barangay', '2024-08-29', 257, 138, 337, 253, 0),
(320, 'brayan villanueva Aquino ', '0909850347', 'bbnbn', 'Read', 'sdfsfs', 'barangay', '2024-08-29', 258, 121, 338, 254, 0),
(321, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'dada', 'barangay', '2024-08-29', 258, 121, 339, 255, 0),
(322, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'dsdsd', 'barangay', '2024-08-29', 251, 121, 340, 256, 0),
(323, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'dssrssr', 'barangay', '2024-08-29', 259, 121, 341, 257, 0),
(324, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'ahdjbdjad', 'pnp', '2024-09-05', 260, 121, 342, 258, 0),
(325, 'brayan villanueva Aquino ', '0909850347', 'derere', 'Read', 'kbjbj', '', '2024-09-05', 261, 121, 343, 259, 0),
(326, 'brayan villanueva Aquino ', '0909850347', 'bj', 'Read', 'asasas', 'pnp', '2024-09-05', 262, 121, 344, 260, 0),
(327, 'din da xy ', '0902025050', 'harel', 'inprogress', 'he harassed me inside the campus', '', '2024-09-05', 263, 140, 345, 261, 0),
(328, 'brayan villanueva Aquino ', '0902025050', 'bayan', 'Read', 'nag gulo ni bayan', '', '2024-09-05', 264, 121, 346, 262, 0),
(329, 'pough tah hays ', '0902025050', 'bugos', 'inprogress', 'ndsuhcdhcndzuxcjnduichskuhx', '', '2024-09-05', 265, 141, 347, 263, 0),
(330, 'jay ar gumabon ', '0902025050', 'marc', 'inprogress', 'hays', '', '2024-09-05', 266, 142, 348, 264, 0),
(331, 'rus sel imel ', '0967544693', 'mes', 'Rejected', 'dssasaszx', '', '2024-09-05', 267, 143, 349, 265, 0),
(332, 'ja mes lems ', '0978544693', 'dabo', 'inprogress', 'indecisive', '', '2024-09-05', 268, 144, 350, 266, 0),
(333, 'kat lin salbag ', '0902025050', 'wala', 'inprogress', 'makauma', '', '2024-09-05', 269, 145, 351, 267, 0),
(334, 'ba yan jan ', '0902025050', 'ji ar', 'inprogress', 'fsd aja aknak', '', '2024-09-05', 270, 146, 352, 268, 0),
(335, 'sesa me seed ', '0967544693', 'harel', 'Approved', 'wan', '', '2024-09-05', 270, 147, 353, 269, 0),
(336, 'mar ky san ', '0978544696', 'erwin', 'inprogress', 'too', '', '2024-09-05', 267, 148, 354, 270, 0),
(337, 'de puuuuuuu tah ', '0902025050', 'ryan', 'inprogress', 'tre', '', '2024-09-05', 271, 149, 355, 271, 0),
(338, 'jan mi lloyd ', '0978544693', 'ji ', 'inprogress', 'for', '', '2024-09-05', 272, 150, 356, 272, 0),
(339, 'yra lei ira ', '0902025050', 'mallon', 'inprogress', 'fayb', '', '2024-09-05', 273, 151, 357, 273, 0),
(340, 'ha mil ton ', '0902025050', 'harel', 'inprogress', 'seks', '', '2024-09-05', 274, 152, 358, 274, 0),
(341, 'an gel babs ', '0978511193', 'pence', 'inprogress', 'seben', '', '2024-09-05', 275, 153, 359, 275, 0),
(342, 'ha ril konsiption ', '0978544696', 'harel', 'inprogress', 'eyt', '', '2024-09-05', 276, 154, 360, 276, 0),
(343, 'cha rot lang ', '0902025050', 'iko', 'inprogress', 'nayn', '', '2024-09-05', 268, 155, 361, 277, 0),
(344, 'ya me te ', '0978544696', 'bugos', 'inprogress', 'ten', '', '2024-09-05', 277, 156, 362, 278, 0),
(345, 'mar ki ban ', '0902025050', 'ji ar', 'inprogress', 'yung?\r\n', '', '2024-09-05', 277, 157, 363, 279, 0),
(346, 'flo rie mae ', '0902025050', 'lems', 'inprogress', 'wait', '', '2024-09-05', 278, 158, 364, 280, 0),
(347, 'but choy blaire ', '0978511193', 'Brayan ', 'inprogress', 'wait', '', '2024-09-05', 279, 159, 365, 281, 0),
(348, 'jhea mae jords ', '0978511193', 'guy', 'inprogress', 'bahay kubo', '', '2024-09-05', 280, 160, 366, 282, 0),
(349, 'pa noy penoy ', '0902025050', 'baw', 'inprogress', 'dik ammo man nag gulo', '', '2024-09-05', 281, 161, 367, 283, 0),
(350, 'jan lloyd ventura ', '0902025050', 'Taming', 'inprogress', 'nagko code', '', '2024-09-05', 282, 162, 368, 284, 0),
(351, 'ben jie lyn ', '0978544693', 'marcial', 'inprogress', 'nag iba', '', '2024-09-05', 283, 163, 369, 285, 0),
(352, 'mar cial lim ', '0978544696', 'lyn', 'inprogress', 'mahal ko sia', '', '2024-09-05', 284, 164, 370, 286, 0),
(353, 'ol lit ros ', '0978544696', 'harel', 'inprogress', 'kimmot', '', '2024-09-05', 285, 165, 371, 287, 0),
(354, 'san dok yum ', '0978544696', 'Brayan ', 'inprogress', 'gerdgrgre', '', '2024-09-05', 263, 166, 372, 288, 0),
(355, 'san mig guel ', '0978544693', 'ji ar', 'inprogress', 'ergdgrdg', '', '2024-09-05', 264, 167, 373, 289, 0),
(356, 'san juan sia ', '0902025050', 'harel', 'inprogress', 'regdgdgd', '', '2024-09-05', 286, 168, 374, 290, 0),
(357, 'fe li pe ', '0967544693', 'harel', 'inprogress', 'rgdfdfvfdbbbefbgfvrddddddddddd', '', '2024-09-05', 287, 169, 375, 291, 0),
(358, 'san car los ', '0902025050', 'lems', 'inprogress', 'carlossssssssss', '', '2024-09-05', 265, 170, 376, 292, 0),
(359, 'san an ton ', '0978544696', 'Brayan ', 'inprogress', 'hahahahhaha', '', '2024-09-05', 269, 171, 377, 293, 0),
(360, 'uu gad yo ', '0902025050', 'yra', 'inprogress', 'hehehe', '', '2010-09-05', 269, 172, 378, 294, 0),
(361, 'sal bag ka ', '0978544693', 'yraaaaa', 'inprogress', 'hihihi', '', '2024-09-05', 288, 173, 379, 295, 0),
(362, 'sa lay xy ', '0978544696', 'Brayan ', 'inprogress', 'hohoho', '', '2024-09-05', 289, 174, 380, 296, 0),
(363, 'ru mang ay ', '0902025050', 'harel', 'inprogress', 'huhuhu', '', '2010-09-05', 290, 175, 381, 297, 0),
(364, 'pa ngal sur ', '0978511193', 'harel', 'inprogress', 'kakakakakakaklbo', '', '2024-09-05', 291, 176, 382, 298, 0),
(365, 'pa ngal norte ', '0978544693', 'Brayan ', 'inprogress', 'kekekeke', '', '2024-09-05', 292, 177, 383, 299, 0),
(366, 'pag asa baaa ', '0978544693', 'Brayan ', 'inprogress', 'kikiki', '', '2024-09-05', 266, 178, 384, 300, 0),
(367, 'br oo kk ', '0902025050', 'bugos', 'inprogress', 'kokoko', '', '2024-09-05', 293, 179, 385, 301, 0),
(368, 'nag ba ra ', '0902025050', 'lems', 'inprogress', 'kukuku', '', '2024-09-05', 294, 180, 386, 302, 0),
(369, 'ma li tao ', '0978544696', 'denver', 'inprogress', 'nyamet', '', '2024-09-05', 295, 181, 387, 303, 0),
(370, 'zoro my loves ', '0978544696', 'nami', 'Filed in the court', 'zorooooo loves me', 'pnp', '2024-09-05', 296, 182, 388, 304, 0),
(371, 'na mi swan ', '0978544693', 'sanji', 'inprogress', 'rese', '', '2024-09-05', 281, 183, 389, 305, 0),
(372, 'san ji tog ', '0902025050', 'bun', 'inprogress', 'iyay', '', '2024-09-05', 279, 184, 390, 306, 0),
(373, 'luf fy baby ', '0978511193', 'sibayan', 'inprogress', 'gugugugugug', '', '2024-09-05', 268, 185, 391, 307, 0),
(374, 'lloyd cabagan manuel ', '0909850347', 'brayan', 'Filed in the court', 'nakaw', 'pnp', '2024-09-05', 263, 187, 392, 308, 0),
(375, 'lloyd cabagan manuel ', '0909850347', 'eso', 'inprogress', 'nakaw', '', '2024-09-05', 297, 187, 393, 309, 0),
(376, 'zoro my loves ', '0909850347', 'bj', 'Rejected', 'takaw aso', '', '2024-09-05', 298, 182, 394, 310, 0),
(377, 'brayan villanueva Aquino ', 'fefefef', 'bj', 'Read', 'dvdsvs', 'pnp', '2024-09-06', 263, 121, 395, 311, 0),
(378, 'brayan villanueva Aquino ', '0927142858', 'eso', 'Read', 'csfs', '', '2024-09-06', 285, 121, 396, 312, 0),
(379, 'brayan villanueva Aquino ', '0927142858', 'bj', 'Read', 'xcxcxc', '', '2024-09-06', 263, 121, 397, 313, 0),
(380, 'brayan villanueva Aquino ', '0927142858', 'bj', 'settled_in_barangay', 'ccsfs', 'barangay', '2024-09-06', 285, 121, 398, 314, 0),
(381, 'sesa me seed ', '0927142858', 'bj', 'settled_in_barangay', 'asasasa', 'barangay', '2024-09-06', 264, 147, 399, 315, 0),
(382, 'brayan villanueva Aquino ', 'dfdfd', 'eso', 'settled_in_barangay', 'dfdfd', 'barangay', '2024-09-07', 286, 121, 400, 316, 0),
(383, 'rus sel imel ', '0987876876', 'bayan', 'settled_in_barangay', 'ukerebet', 'barangay', '2024-09-07', 264, 143, 401, 317, 0),
(385, 'din do aeroxy ', '0902025050', 'harel', 'settled_in_barangay', 'ttrhtrhytrhty', 'barangay', '2024-09-08', 286, 196, 403, 319, 0),
(386, 'din do aeroxy ', '0925647412', 'sdsd', 'Filed in the court', 'cscs', 'pnp', '2024-09-08', 290, 196, 404, 320, 0),
(387, 'din do aeroxy ', '0967544693', 'rubirt', 'Filed in the court', 'kingina anong oras na', 'pnp', '2024-09-08', 273, 196, 405, 321, 0),
(388, 'din do aeroxy ', 'rerer', 'ererere', 'Filed in the court', 'terterte', 'pnp', '2010-10-08', 285, 196, 406, 322, 0),
(389, 'din do aeroxy ', '0978544693', 'Brayan ', 'Rejected', 'gdfvdf', '', '2024-09-08', 291, 196, 407, 323, 0),
(390, 'excel N Preza ', '0927142858', 'John Lloyd Manu', 'Filed in the court', 'Nag takaw manok', 'pnp', '2010-09-10', 285, 196, 408, 324, 0),
(391, 'excel N Preza ', '0927142858', 'Haryl concepcio', 'settled_in_barangay', 'nag vape', 'barangay', '2024-09-10', 299, 196, 409, 325, 0),
(392, 'excel N Preza ', '0927142858', 'bj', 'Filed in the court', 'scssx', 'pnp', '2024-09-12', 263, 196, 410, 326, 0),
(393, 'excel N Preza ', '0927142858', 'dinda', 'settled_in_barangay', 'nag nakaw sa bahay', 'barangay', '2024-09-12', 282, 99, 411, 327, 0),
(394, 'Princess Del Valle Moon ', '0927142858', 'Bayan Jan Abino', 'pnp', 'nag nakaw sa kantina namin', 'pnp', '2024-09-12', 282, 204, 412, 328, 0),
(395, 'Princess Del Valle Moon ', '0927142858', 'jabi', 'Rejected', 'sinuntok ba naman ako', '', '2024-09-12', 257, 99, 413, 329, 0),
(396, 'excel N Preza ', '0927142858', 'bj', 'settled_in_barangay', 'scsfsfsf', 'barangay', '2024-05-14', 285, 99, 414, 330, 0),
(397, 'lance gantec lazaro ', '0927142858', 'bj', 'pnp', 'sfgfsfsf', 'pnp', '2024-09-17', 258, 205, 415, 331, 0),
(398, 'excel N Preza ', 'sdsdsds', 'bj', 'Filed in the court', 'dsdfsdfsdfs', 'pnp', '2024-09-21', 286, 99, 416, 332, 0),
(399, 'excel N Preza ', '0987876876', 'bayan', 'pnp', 'sdsdsdsd', 'pnp', '2024-09-21', 300, 99, 417, 333, 0),
(400, 'reyzon Bascos Mabini ', '0927142858', 'olzen Melendrez', 'Filed in the court', 'sfdfdfd', 'pnp', '2024-09-22', 286, 207, 418, 334, 0),
(401, 'excel N Preza ', '0909850347', 'bshsjs', 'Filed in the court', 'hdhshsh', 'pnp', '2024-09-22', 287, 99, 419, 335, 0);

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
(0, 323, '../uploads/Screenshot (10).png', '2024-08-29'),
(0, 324, '../uploads/a05f7b98-6076-4ee8-824f-387507e042bf.mp4', '2024-09-05'),
(0, 325, '../uploads/Leadersip styles and skills.pptx', '2024-09-05'),
(0, 326, '../uploads/457368489_528714303027535_3753519382645185170_n.jpg', '2024-09-05'),
(0, 327, '../uploads/_70352850_180803406.jpg', '2024-09-05'),
(0, 328, '../uploads/Bunny.png', '2024-09-05'),
(0, 329, '../uploads/Graham Balls 2.jpg', '2024-09-05'),
(0, 330, '../uploads/corruption-in-africa-causes-and-solutions.jpg', '2024-09-05'),
(0, 331, '../uploads/my-first-easy-to-make-graham-balls.jpg', '2024-09-05'),
(0, 332, '../uploads/my-first-easy-to-make-graham-balls.jpg', '2024-09-05'),
(0, 333, '../uploads/1.jpg', '2024-09-05'),
(0, 334, '../uploads/3.jpg', '2024-09-05'),
(0, 335, '../uploads/1.jpg', '2024-09-05'),
(0, 336, '../uploads/5.jpg', '2024-09-05'),
(0, 337, '../uploads/6.jfif', '2024-09-05'),
(0, 338, '../uploads/6.jfif', '2024-09-05'),
(0, 339, '../uploads/7.jfif', '2024-09-05'),
(0, 340, '../uploads/14.jpg', '2024-09-05'),
(0, 341, '../uploads/14.jpg', '2024-09-05'),
(0, 342, '../uploads/8.jpg', '2024-09-05'),
(0, 343, '../uploads/14.jpg', '2024-09-05'),
(0, 344, '../uploads/Screenshot (57).png', '2024-09-05'),
(0, 345, '../uploads/Screenshot (59).png', '2024-09-05'),
(0, 346, '../uploads/Screenshot (63).png', '2024-09-05'),
(0, 347, '../uploads/Screenshot (67).png', '2024-09-05'),
(0, 348, '../uploads/15.jpg', '2024-09-05'),
(0, 349, '../uploads/16.png', '2024-09-05'),
(0, 350, '../uploads/Lauffey.mkv', '2024-09-05'),
(0, 351, '../uploads/19.jpg', '2024-09-05'),
(0, 352, '../uploads/20.avif', '2024-09-05'),
(0, 353, '../uploads/6.jfif', '2024-09-05'),
(0, 354, '../uploads/8.jpg', '2024-09-05'),
(0, 355, '../uploads/21.jpg', '2024-09-05'),
(0, 356, '../uploads/22.jpg', '2024-09-05'),
(0, 357, '../uploads/24.jpg', '2024-09-05'),
(0, 358, '../uploads/25.jpg', '2024-09-05'),
(0, 359, '../uploads/26.jpg', '2024-09-05'),
(0, 360, '../uploads/27.png', '2024-09-05'),
(0, 361, '../uploads/28.jpg', '2024-09-05'),
(0, 362, '../uploads/28.jpg', '2024-09-05'),
(0, 363, '../uploads/29.jpg', '2024-09-05'),
(0, 364, '../uploads/30.jpg', '2024-09-05'),
(0, 365, '../uploads/grahamball.jpg', '2024-09-05'),
(0, 366, '../uploads/32.avif', '2024-09-05'),
(0, 367, '../uploads/33.jpg', '2024-09-05'),
(0, 368, '../uploads/17', '2024-09-05'),
(0, 369, '../uploads/34', '2024-09-05'),
(0, 370, '../uploads/36.jpg.crdownload', '2024-09-05'),
(0, 371, '../uploads/38.png', '2024-09-05'),
(0, 372, '../uploads/39.jfif', '2024-09-05'),
(0, 373, '../uploads/40.png', '2024-09-05'),
(0, 374, '../uploads/profile_66ce93730f232.png', '2024-09-05'),
(0, 375, '../uploads/1.jpg', '2024-09-05'),
(0, 376, '../uploads/7.jfif', '2024-09-05'),
(0, 377, '../uploads/10.jpg', '2024-09-06'),
(0, 378, '../uploads/9.jpg', '2024-09-06'),
(0, 379, '../uploads/1.png', '2024-09-06'),
(0, 380, '../uploads/pnp interview.jpg', '2024-09-06'),
(0, 381, '../uploads/profile_66cea6e841a1a.jpg', '2024-09-06'),
(0, 382, '../uploads/9.jpg', '2024-09-07'),
(0, 383, '../uploads/40.png', '2024-09-07'),
(0, 385, '../uploads/8.jpg', '2024-09-08'),
(0, 386, '../uploads/8.jpg', '2024-09-08'),
(0, 387, '../uploads/30.jpg', '2024-09-08'),
(0, 388, '../uploads/13.png', '2024-09-08'),
(0, 389, '../uploads/6.jfif', '2024-09-08'),
(0, 390, '../uploads/Screenshot 2024-09-10 220439.png', '2024-09-10'),
(0, 391, '../uploads/459081664_8258910247531104_2432168630201406601_n.mp4', '2024-09-10'),
(0, 392, '../uploads/uploadsthumb-1920-987256.png', '2024-09-12'),
(0, 393, '../uploads/9.jpg', '2024-09-12'),
(0, 394, '../uploads/_70352850_180803406.jpg', '2024-09-12'),
(0, 395, '../uploads/1.jpg', '2024-09-12'),
(0, 396, '../uploads/uploads456864062_827865415993505_5703441178082039818_n.jpg', '2024-09-14'),
(0, 397, '../uploads/Screenshot (9).png', '2024-09-17'),
(0, 398, '../uploads/Screenshot (10).png', '2024-09-21'),
(0, 399, '../uploads/8.jpg', '2024-09-21'),
(0, 400, '../uploads/Screenshot (10).png', '2024-09-22'),
(0, 401, '../uploads/17269998275437695422075937934174.jpg', '2024-09-22');

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

--
-- Dumping data for table `tbl_hearing_history`
--

INSERT INTO `tbl_hearing_history` (`id`, `complaints_id`, `hearing_date`, `hearing_time`, `hearing_type`, `hearing_status`, `created_at`) VALUES
(19, 385, '2024-09-19', '12:36:00', 'First Hearing', '', '2024-09-08 13:34:35'),
(20, 385, '2024-09-19', '12:36:00', 'Second Hearing', 'Not Resolved', '2024-09-08 13:39:39'),
(21, 385, '2024-09-19', '12:36:00', 'Third Hearing', 'Not Attended', '2024-09-08 13:47:31'),
(22, 386, '2024-09-20', '01:19:00', 'First Hearing', 'Not Resolved', '2024-09-08 14:19:30'),
(23, 387, '2024-08-28', '05:30:00', 'First Hearing', '', '2024-09-08 14:57:21'),
(24, 388, '2024-09-21', '02:05:00', 'First Hearing', '', '2024-09-08 15:03:21'),
(25, 387, '2024-08-28', '05:30:00', 'Second Hearing', 'Not Attended', '2024-09-08 15:31:40'),
(26, 388, '2024-09-21', '02:05:00', 'Second Hearing', 'Not Resolved', '2024-09-08 15:36:16'),
(27, 387, '2024-08-28', '05:30:00', 'Third Hearing', 'Not Resolved', '2024-09-09 11:26:02'),
(28, 390, '2024-09-27', '10:25:00', 'First Hearing', 'Attended', '2024-09-10 11:22:27'),
(29, 390, '2024-09-27', '10:25:00', 'Second Hearing', 'Not Attended', '2024-09-10 11:27:21'),
(30, 390, '2024-09-27', '10:25:00', 'Third Hearing', 'Not Resolved', '2024-09-10 11:50:11'),
(31, 393, '2024-09-27', '10:56:00 AM', 'First Hearing', '', '2024-09-12 21:56:17'),
(32, 393, '2024-09-27', '02:56:00 PM', 'Second Hearing', '', '2024-09-12 22:03:20'),
(33, 394, '2024-09-21', '04:00:00 PM', 'First Hearing', 'Not Resolved', '2024-09-14 05:57:27'),
(34, 396, '2024-09-21', '11:34:00 AM', 'First Hearing', '', '2024-09-14 12:30:18'),
(35, 398, '2024-10-12', '04:36:00 PM', 'First Hearing', '', '2024-09-21 02:36:29'),
(36, 400, '2024-09-23', '02:00:00 PM', 'First Hearing', '', '2024-09-21 22:35:29');

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
(341, 0, 'ID', '../uploads/Screenshot 2024-06-20 153520.png', '2024-08-29 17:06:47.000000'),
(342, 0, 'ID', '../uploads/profile_66c3bedba896e.png', '2024-09-05 01:03:46.000000'),
(343, 0, 'ID', '../uploads/Leadersip styles and skills.pptx', '2024-09-05 01:05:35.000000'),
(344, 0, 'ID', '../uploads/457368489_528714303027535_3753519382645185170_n.jpg', '2024-09-05 07:08:20.000000'),
(345, 0, 'ID', '../uploads/288677-One_Piece-Monkey_D._Luffy-Nico_Robin-Tony_Tony_Chopper-Brook-Roronoa_Zoro-Sanji-Usopp-Franky-Nami.jpg', '2024-09-05 14:12:34.000000'),
(346, 0, 'ID', '../uploads/_70352850_180803406.jpg', '2024-09-05 14:26:49.000000'),
(347, 0, 'ID', '../uploads/lutong-pinoy-graham-balls-recipe.jpg', '2024-09-05 14:33:34.000000'),
(348, 0, 'ID', '../uploads/_70352850_180803406.jpg', '2024-09-05 14:36:52.000000'),
(349, 0, 'ID', '../uploads/cab.jpg', '2024-09-05 14:40:34.000000'),
(350, 0, 'ID', '../uploads/IMG_20160119_005137.jpg', '2024-09-05 14:51:22.000000'),
(351, 0, 'ID', '../uploads/my-first-easy-to-make-graham-balls.jpg', '2024-09-05 14:55:33.000000'),
(352, 0, 'ID', '../uploads/1.jpg', '2024-09-05 14:58:14.000000'),
(353, 0, 'ID', '../uploads/3.jpg', '2024-09-05 15:11:21.000000'),
(354, 0, 'ID', '../uploads/1.jpg', '2024-09-05 15:15:02.000000'),
(355, 0, 'ID', '../uploads/my-first-easy-to-make-graham-balls.jpg', '2024-09-05 15:17:47.000000'),
(356, 0, 'ID', '../uploads/Graham.jpg', '2024-09-05 15:21:27.000000'),
(357, 0, 'ID', '../uploads/my-first-easy-to-make-graham-balls.jpg', '2024-09-05 15:24:27.000000'),
(358, 0, 'ID', '../uploads/8.jpg', '2024-09-05 15:27:39.000000'),
(359, 0, 'ID', '../uploads/5.jpg', '2024-09-05 15:30:05.000000'),
(360, 0, 'ID', '../uploads/Graham Balls 2.jpg', '2024-09-05 15:36:22.000000'),
(361, 0, 'ID', '../uploads/_70352850_180803406.jpg', '2024-09-05 15:41:14.000000'),
(362, 0, 'ID', '../uploads/Screenshot (57).png', '2024-09-05 15:54:36.000000'),
(363, 0, 'ID', '../uploads/7.jfif', '2024-09-05 15:59:21.000000'),
(364, 0, 'ID', '../uploads/_70352850_180803406.jpg', '2024-09-05 16:07:17.000000'),
(365, 0, 'ID', '../uploads/Screenshot (57).png', '2024-09-05 16:19:14.000000'),
(366, 0, 'ID', '../uploads/15.jpg', '2024-09-05 16:24:20.000000'),
(367, 0, 'ID', '../uploads/3.jpg', '2024-09-05 16:36:32.000000'),
(368, 0, 'ID', '../uploads/_70352850_180803406.jpg', '2024-09-05 16:39:38.000000'),
(369, 0, 'ID', '../uploads/Graham Balls 2.jpg', '2024-09-05 16:44:11.000000'),
(370, 0, 'ID', '../uploads/my-first-easy-to-make-graham-balls.jpg', '2024-09-05 16:49:09.000000'),
(371, 0, 'ID', '../uploads/9.jpg', '2024-09-05 16:51:17.000000'),
(372, 0, 'ID', '../uploads/9.jpg', '2024-09-05 16:52:51.000000'),
(373, 0, 'ID', '../uploads/1.jpg', '2024-09-05 16:55:45.000000'),
(374, 0, 'ID', '../uploads/5.jpg', '2024-09-05 16:57:20.000000'),
(375, 0, 'ID', '../uploads/Graham.jpg', '2024-09-05 17:29:24.000000'),
(376, 0, 'ID', '../uploads/Graham Balls 2.jpg', '2024-09-05 17:31:50.000000'),
(377, 0, 'ID', '../uploads/Screenshot (63).png', '2024-09-05 17:36:55.000000'),
(378, 0, 'ID', '../uploads/my-first-easy-to-make-graham-balls.jpg', '2024-09-05 17:39:07.000000'),
(379, 0, 'ID', '../uploads/5.jpg', '2024-09-05 17:40:48.000000'),
(380, 0, 'ID', '../uploads/10.jpg', '2024-09-05 17:43:20.000000'),
(381, 0, 'ID', '../uploads/my-first-easy-to-make-graham-balls.jpg', '2024-09-05 17:44:52.000000'),
(382, 0, 'ID', '../uploads/Graham.jpg', '2024-09-05 17:46:54.000000'),
(383, 0, 'ID', '../uploads/25.jpg', '2024-09-05 17:50:18.000000'),
(384, 0, 'ID', '../uploads/my-first-easy-to-make-graham-balls.jpg', '2024-09-05 17:53:25.000000'),
(385, 0, 'ID', '../uploads/1.jpg', '2024-09-05 17:56:13.000000'),
(386, 0, 'ID', '../uploads/9.jpg', '2024-09-05 17:58:10.000000'),
(387, 0, 'ID', '../uploads/23.jfif', '2024-09-05 18:00:22.000000'),
(388, 0, 'ID', '../uploads/1.jpg', '2024-09-05 18:04:25.000000'),
(389, 0, 'ID', '../uploads/8.jpg', '2024-09-05 18:06:04.000000'),
(390, 0, 'ID', '../uploads/_70352850_180803406.jpg', '2024-09-05 18:09:05.000000'),
(391, 0, 'ID', '../uploads/12.jfif', '2024-09-05 18:11:44.000000'),
(392, 0, 'ID', '../uploads/34', '2024-09-05 22:48:51.000000'),
(393, 0, 'ID', '../uploads/9.jpg', '2024-09-05 23:05:34.000000'),
(394, 0, 'ID', '../uploads/10.jpg', '2024-09-05 23:11:52.000000'),
(395, 0, 'ID', '../uploads/10.jpg', '2024-09-06 06:18:08.000000'),
(396, 0, 'ID', '../uploads/9.jpg', '2024-09-06 06:52:50.000000'),
(397, 0, 'ID', '../uploads/_70352850_180803406.jpg', '2024-09-06 06:55:50.000000'),
(398, 0, 'ID', '../uploads/10.jpg', '2024-09-06 07:03:19.000000'),
(399, 0, 'ID', '../uploads/10.jpg', '2024-09-06 15:05:15.000000'),
(400, 0, 'ID', '../uploads/10.jpg', '2024-09-07 02:02:06.000000'),
(401, 0, 'ID', '../uploads/10.jpg', '2024-09-07 13:26:46.000000'),
(402, 0, 'ID', '../uploads/28.jpg', '2024-09-08 14:27:35.000000'),
(403, 0, 'ID', '../uploads/22.jpg', '2024-09-08 15:33:44.000000'),
(404, 0, 'ID', '../uploads/7.jfif', '2024-09-08 16:17:36.000000'),
(405, 0, 'ID', '../uploads/9.jpg', '2024-09-08 16:50:24.000000'),
(406, 0, 'ID', '../uploads/15.jpg', '2024-09-08 16:58:47.000000'),
(407, 0, 'ID', '../uploads/28.jpg', '2024-09-08 17:03:44.000000'),
(408, 0, 'ID', '../uploads/1007-scaled-e1648706208427-1024x538-removebg-preview.png', '2024-09-10 13:08:34.000000'),
(409, 0, 'ID', '../uploads/1007-scaled-e1648706208427-1024x538-removebg-preview.png', '2024-09-10 14:16:34.000000'),
(410, 0, 'ID', '../uploads/uploadsdownload-removebg-preview.png', '2024-09-12 11:43:21.000000'),
(411, 0, 'ID', '../uploads/_70352850_180803406.jpg', '2024-09-12 23:00:41.000000'),
(412, 0, 'ID', '../uploads/15.jpg', '2024-09-12 23:15:26.000000'),
(413, 0, 'ID', '../uploads/10.jpg', '2024-09-12 23:47:59.000000'),
(414, 0, 'ID', '../uploads/uploads456864062_827865415993505_5703441178082039818_n.jpg', '2024-09-14 14:24:50.000000'),
(415, 0, 'ID', '../uploads/Screenshot (10).png', '2024-09-17 09:37:01.000000'),
(416, 0, 'ID', '../uploads/Screenshot (11).png', '2024-09-21 04:35:41.000000'),
(417, 0, 'ID', '../uploads/10.jpg', '2024-09-21 11:03:48.000000'),
(418, 0, 'ID', '../uploads/Screenshot (10).png', '2024-09-22 00:34:23.000000'),
(419, 0, 'ID', '../uploads/17269999498581739142557838625480.jpg', '2024-09-22 09:12:31.000000');

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
(257, 24, 'Male', '2000-02-02', 'sa rantay', 'Single', 'Primary'),
(258, 24, 'Male', '2000-02-03', 'SA TABI ng daan', 'Single', 'Primary'),
(259, 24, 'Male', '2000-02-03', 'sa damo', 'Married', 'Primary'),
(260, 24, 'Male', '2000-02-03', 'SA TABI ng daan', 'Single', 'Primary'),
(261, 23, 'Male', '2001-08-28', 'fugawers', 'Single', 'Primary'),
(262, 15, 'Female', '2009-03-22', 'sa daan', 'Single', 'Secondary'),
(263, 25, 'Male', '1998-10-22', 'kjxksxiusxn', 'Married', 'Tertiary'),
(264, 62, 'Male', '1962-02-12', 'fugawers', 'Divorced', 'Secondary'),
(265, 25, 'Female', '1999-05-10', 'gedli', 'Divorced', 'Secondary'),
(266, 43, 'Female', '1981-01-01', 'sa daan', 'Widowed', 'Primary'),
(267, 24, 'Male', '1999-09-12', 'jxksxiusxn', 'Divorced', 'Secondary'),
(268, 20, 'Male', '2003-12-08', ']sxiusxn', 'Married', 'Secondary'),
(269, 22, 'Female', '2002-02-02', ']sxiusxn', 'Single', 'Tertiary'),
(270, 21, 'Male', '2003-03-03', 'fugawers', 'Married', 'Secondary'),
(271, 30, 'Female', '1994-04-04', 'kjxksxiusxn', 'Single', 'Secondary'),
(272, 29, 'Female', '1995-05-05', 'fugawers', 'Single', 'Secondary'),
(273, 28, 'Male', '1996-06-06', 'sa daan', 'Single', 'Primary'),
(274, 17, 'Male', '2007-07-07', 'kjxksxiusxn', 'Single', 'Primary'),
(275, 18, 'Female', '2006-06-06', 'gedli', 'Single', 'Secondary'),
(276, 27, 'Male', '1997-07-07', ']sxiusxn', 'Single', 'Primary'),
(277, 36, 'Male', '1988-08-08', 'gedli', 'Divorced', 'Primary'),
(278, 13, 'Male', '2010-10-11', 'sa damo', 'Single', 'Primary'),
(279, 112, 'Male', '1911-11-11', 'semento', 'Single', 'Primary'),
(280, 31, 'Male', '1992-12-12', 'ilog', 'Single', 'Secondary'),
(281, 30, 'Male', '1994-05-15', 'waig', 'Single', 'Primary'),
(282, 47, 'Female', '1977-07-27', 'bakko', 'Single', 'Primary'),
(283, 36, 'Male', '1988-02-12', ']sxiusxn', 'Single', 'Tertiary'),
(284, 68, 'Female', '1956-03-12', 'fugawers', 'Married', 'Secondary'),
(285, 23, 'Female', '2001-08-12', 'fugawers', 'Married', 'Secondary'),
(286, 59, 'Male', '1965-08-08', ']sxiusxn', 'Single', 'Tertiary'),
(287, 47, 'Female', '1976-11-11', 'fugawers', 'Single', 'Secondary'),
(288, 35, 'Female', '1988-11-11', 'sa daan', 'Single', 'Secondary'),
(289, 37, 'Male', '1987-05-13', 'takki', 'Single', 'Primary'),
(290, 25, 'Female', '1999-03-12', 'fugawers', 'Single', 'Primary'),
(291, 37, 'Female', '1987-08-08', 'fugawers', 'Single', 'Primary'),
(292, 47, 'Male', '1977-07-07', 'sa daan', 'Single', 'Secondary'),
(293, 102, 'Female', '1922-02-01', 'fugawers', 'Widowed', 'Secondary'),
(294, 80, 'Female', '1944-04-04', 'kammo', 'Divorced', 'Tertiary'),
(295, 112, 'Female', '1911-09-10', 'fugawers', 'Single', 'Tertiary'),
(296, 24, 'Female', '1999-12-12', ']sxiusxn', 'Single', 'Secondary'),
(297, 35, 'Female', '1988-11-11', 'sa daan', 'Single', 'Primary'),
(298, 80, 'Female', '1944-04-04', 'sa daan', 'Married', 'Secondary'),
(299, 36, 'Female', '1988-08-08', 'fugawers', 'Single', 'Secondary'),
(300, 69, 'Female', '1955-05-05', 'fugawers', 'Married', 'Secondary'),
(301, 46, 'Male', '1978-04-04', 'lagaue', 'Single', 'Secondary'),
(302, 22, 'Female', '2002-08-08', 'sa daan', 'Single', 'Primary'),
(303, 35, 'Female', '1988-12-12', 'vas', 'Single', 'Primary'),
(304, 13, 'Female', '2011-03-12', 'water 7', 'Married', 'Secondary'),
(305, 107, 'Male', '1916-11-11', 'gedli', 'Single', 'Primary'),
(306, 102, 'Male', '1922-02-22', 'jan lang', 'Single', 'Secondary'),
(307, 105, 'Female', '1918-12-10', 'gedli', 'Single', 'Primary'),
(308, 22, 'Male', '2001-12-07', 'gamis', 'Single', 'Tertiary'),
(309, 23, 'Female', '2000-09-09', 'SA TABI', 'Single', 'Tertiary'),
(310, 23, 'Female', '2001-03-31', 'SA TABI ng daan', 'Married', 'Tertiary'),
(311, 24, 'Male', '2000-02-03', 'gamis', 'Single', 'Primary'),
(312, 24, 'Female', '2000-02-03', 'gamis', 'Single', 'Tertiary'),
(313, 24, 'Male', '2000-02-02', 'gamis', 'Single', 'Primary'),
(314, 24, 'Female', '2000-01-22', 'gamis', 'Single', 'Primary'),
(315, 23, 'Male', '2001-02-20', 'gamis', 'Single', 'Primary'),
(316, 24, 'Male', '2000-02-02', 'gamis', 'Single', 'Primary'),
(317, 36, 'Male', '1988-08-28', 'sa daan', 'Divorced', 'Primary'),
(318, 36, 'Male', '1988-08-20', 'gedli', 'Married', 'Secondary'),
(319, 36, 'Male', '1988-08-22', 'fugawers', 'Divorced', 'Secondary'),
(320, 23, 'Female', '2000-09-20', 'ddfdfdf', 'Single', 'Primary'),
(321, 23, 'Male', '2000-09-28', 'sa daan', 'Single', 'Secondary'),
(322, -1, 'Male', '2024-09-20', 'ererere', 'Single', 'Primary'),
(323, 35, 'Female', '1988-09-28', 'sa daan', 'Single', 'Primary'),
(324, 23, 'Female', '2001-03-27', 'Gamu Isabela', 'Single', 'Tertiary'),
(325, 23, 'Male', '2001-06-27', 'Gamu Isabela', 'Single', 'Tertiary'),
(326, 24, 'Male', '2000-02-22', 'SA TABI', 'Single', 'Primary'),
(327, 21, 'Male', '2003-08-28', 'Bohol', 'Married', 'Primary'),
(328, 36, 'Female', '1988-09-12', 'Bohol', 'Married', 'Tertiary'),
(329, 36, 'Female', '1988-08-29', 'sa rantay', 'Single', 'Secondary'),
(330, 24, 'Female', '2000-02-03', 'sa rantay', 'Married', 'Primary'),
(331, 24, 'Male', '2000-02-03', 'Bohol', 'Single', 'Primary'),
(332, 22, 'Male', '2002-03-21', 'sa rantay', 'Single', 'Secondary'),
(333, 24, 'Female', '2000-03-27', 'Gamu Isabela', 'Single', 'Tertiary'),
(334, 22, 'Male', '2001-10-18', 'Gundaway Cabarroguis Quirino', 'Single', 'Tertiary'),
(335, 19, 'Female', '2004-11-09', 'maligaya', 'Single', 'Primary');

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
(1, 163, '2024-09-17 11:50:37.000000'),
(2, 91, '2024-09-17 11:54:11.000000'),
(3, 91, '2024-09-17 12:16:19.000000'),
(4, 88, '2024-09-17 12:26:06.000000'),
(5, 90, '2024-09-17 12:32:18.000000'),
(6, 163, '2024-09-17 16:31:36.000000'),
(7, 163, '2024-09-17 18:30:01.000000'),
(8, 168, '2024-09-17 18:35:45.000000'),
(9, 88, '2024-09-17 18:37:43.000000'),
(10, 163, '2024-09-17 23:19:08.000000'),
(11, 163, '2024-09-17 23:21:13.000000'),
(12, 90, '2024-09-17 23:30:12.000000'),
(13, 88, '2024-09-17 23:30:38.000000'),
(14, 90, '2024-09-17 23:32:04.000000'),
(15, 88, '2024-09-17 23:34:05.000000'),
(16, 163, '2024-09-17 23:34:59.000000'),
(17, 90, '2024-09-18 08:46:58.000000'),
(18, 163, '2024-09-18 10:04:59.000000'),
(19, 163, '2024-09-18 10:51:54.000000'),
(20, 163, '2024-09-18 20:38:10.000000'),
(21, 163, '2024-09-18 20:42:30.000000'),
(22, 163, '2024-09-18 20:43:48.000000'),
(23, 163, '2024-09-18 20:45:35.000000'),
(24, 163, '2024-09-18 20:45:50.000000'),
(25, 90, '2024-09-18 21:53:10.000000'),
(26, 88, '2024-09-20 18:14:03.000000'),
(27, 90, '2024-09-20 18:14:26.000000'),
(28, 88, '2024-09-20 18:34:38.000000'),
(29, 163, '2024-09-21 13:35:06.000000'),
(30, 88, '2024-09-21 13:36:05.000000'),
(31, 91, '2024-09-21 13:43:44.000000'),
(32, 163, '2024-09-21 14:40:12.000000'),
(33, 169, '2024-09-21 14:44:22.000000'),
(34, 90, '2024-09-21 16:44:37.000000'),
(35, 163, '2024-09-21 17:13:27.000000'),
(36, 88, '2024-09-21 17:13:37.000000'),
(37, 90, '2024-09-21 17:28:27.000000'),
(38, 90, '2024-09-21 19:39:58.000000'),
(39, 163, '2024-09-21 20:01:54.000000'),
(40, 88, '2024-09-21 20:04:36.000000'),
(41, 88, '2024-09-21 22:58:15.000000'),
(42, 90, '2024-09-21 22:58:32.000000'),
(43, 90, '2024-09-22 07:26:41.000000'),
(44, 90, '2024-09-22 09:22:49.000000'),
(45, 88, '2024-09-22 09:32:40.000000'),
(46, 90, '2024-09-22 17:29:11.000000'),
(47, 88, '2024-09-22 17:52:43.000000'),
(48, 163, '2024-09-22 18:10:11.000000'),
(49, 97, '2024-09-22 20:27:10.000000'),
(50, 163, '2024-09-22 21:01:48.000000'),
(51, 88, '2024-09-22 21:03:04.000000'),
(52, 88, '2024-09-22 21:29:51.000000');

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
  `barangays_id` int(11) DEFAULT NULL,
  `pic_data` varchar(255) NOT NULL,
  `security_question_1` varchar(255) NOT NULL,
  `security_answer_1` varchar(255) NOT NULL,
  `security_question_2` varchar(255) NOT NULL,
  `security_answer_2` varchar(255) NOT NULL,
  `security_question_3` varchar(255) NOT NULL,
  `security_answer_3` varchar(255) NOT NULL,
  `verification_token` varchar(32) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `otp` varchar(6) DEFAULT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `lockout_time` datetime DEFAULT NULL,
  `announcement_id` int(255) NOT NULL,
  `read_status` varchar(255) NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `email`, `password`, `accountType`, `barangays_id`, `pic_data`, `security_question_1`, `security_answer_1`, `security_question_2`, `security_answer_2`, `security_question_3`, `security_answer_3`, `verification_token`, `is_verified`, `otp`, `login_attempts`, `lockout_time`, `announcement_id`, `read_status`) VALUES
(88, 'bj', 'villanueva', 'Aquino', '', 'bjaquino@gmail.com', '$2y$10$zIuH.Hf3SqI0tF7LuU1ep.59VjW.mBFloZw5It0LEllb0G4un6Ese', 'Barangay Official', 118, '../uploads/435559638_928560769060280_7584294412764526119_n.jpg', '', '', '', '', '', '', 'b3fb01a3e82c4acc34b4c4c34243ae98', 1, NULL, 0, NULL, 0, 'unread'),
(89, 'Excel', 'nnnn', 'Preza', '', 'excel27@gmail.com', '$2y$10$/otNt0hA5d3b3V1xX5nzCOThzRA0.tGdwBq.mBrdvYJCW8AaQdRPW', 'Resident', 119, 'uploads/profile_66b35dfc81120.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(90, 'princess', 'Cadiente', 'Rosario', '', 'immajvadhing@gmail.com', '$2y$10$zIuH.Hf3SqI0tF7LuU1ep.59VjW.mBFloZw5It0LEllb0G4un6Ese', 'PNP Officer', 120, '../uploads/uploads456864062_827865415993505_5703441178082039818_n.jpg', '', '', '', '', '', '', NULL, 1, NULL, 0, NULL, 1, 'unread'),
(91, 'brayan', 'villanueva', 'Aquino', '', 'resident@gmail.com', '$2y$10$eDd76Og1S4TLos2N4J4Y1eLSobv5n5MkSRywhcFUhdFnA4Rc0HhjG', 'Resident', 121, '../uploads/excel.jpg', '', '', '', '', '', '', NULL, 1, NULL, 0, NULL, 0, 'unread'),
(92, 'excel', 'nnnn', 'preza', '', 'barangay@gmail.com', '$2y$10$1AtIP9iYRzjHMlsCEmSAqOI6m7moC3sWnPP1p/7k3V1mnVD1rwE/W', 'Barangay Official', 122, '../uploads/profile_669f988fa9760.jpg', '', '', '', '', '', '', NULL, 1, NULL, 0, NULL, 0, 'unread'),
(93, 'Desiray', 'Domael', 'Naya', 'Sr.', 'desiray.d.nayga@isu.edu.ph', '$2y$10$LmMrEMdaLDuLc3Lk3GIey.vNaaMqWfTz0Ozlyafwidp.fypnXkspe', 'Resident', 123, 'uploads/profile_66c3bedba896e.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(94, 'excel', 'nnn', 'preza', '', 'excel@gmail.com', '$2y$10$dmxu96P0WEASLgJMGL9pOuRxpJabK6jwdVo.8cAQB4hot3lZowS.m', 'Resident', 124, 'uploads/profile_66c5c31494af3.jpg', '', '', '', '', '', '', NULL, 0, NULL, 2, NULL, 0, 'unread'),
(95, 'trisha', 'Nicole', 'Yaranon', '', 'tien@gmail.com', '$2y$10$KnQWks8rK02R/qfLFtnNsegU/IyHYnfvetaWVLKEZvzObHqKdN2ky', 'Resident', 125, 'uploads/profile_66cc76ae2ecec.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(96, 'Din', 'Dax', 'Xhy', '', 'dindaxy@gmail.com', '$2y$10$9b.lK3kNa7A40HVZBedcBeO5jncGOZ7QiZxvc9GU2/JEfJhag5cSa', 'Barangay Official', 126, 'uploads/profile_66cc76b5658e5.jpeg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(97, 'leslie', 'Pascual', 'Rigor', '', 'leslie05@gmail.com', '$2y$10$MV7WniHlIWdVo8ieNdWdSOCwimXVu7VhpPPDOYT8OTsd6LqlbZdQG', 'Resident', 127, '../uploads6f82caab-9d12-4df9-bc91-742bb7cb9bf3.jpg', '', '', '', '', '', '', NULL, 0, '839435', 0, NULL, 0, 'unread'),
(98, 'ddd', 'Mondragon', 'Aquino', '', 'eso@gmail.com', '$2y$10$d6DNp3zHZCykQltVZx4aCecMjvsCO8LkL5X5sRGsO3FKANJN9Hk7C', 'Resident', 129, '../uploads/profile_66cc76ae2ecec.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(99, 'laymar', 'versosa', 'mina', '', 'laymar@gmail.com', '$2y$10$bfbvgTbaiANpL1OO/MIog.6KKxjWlMRpIVjzbdFtvaeebpscklSFC', 'Barangay Official', 130, '../uploads/1.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(100, 'excel', 'nnn', 'preza', '', 'lex@gmail.com', '$2y$10$FAvUBwgHohiKj37AT1IwXe7qgS1bQdFgbSW1Gp3C4z2ZSRJqH9jxS', 'Barangay Official', 131, 'uploads/profile_66cea6e841a1a.jpg', '', '', '', '', '', '', NULL, 0, NULL, 1, NULL, 0, 'unread'),
(101, 'allen', 'esteban', 'serrano', '', 'allen@gmail.com', '$2y$10$yjcNd3nKwwpxhB265dCQFOCNPq28QwBMUfU4WrELypLJFJ.lJhkVq', 'Barangay Official', 132, 'uploads/profile_66cea934ad628.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(102, 'Princess', 'Cadiente', 'Rosario', '', 'cess@gmail.com', '$2y$10$/KbWqX4vMkcprx.tiMvTl.QX4VDt0vDXYF9XgyKr1gp.y33E2QOoS', 'Barangay Official', 133, './uploads/profile_66cea9a314694.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(103, 'excel', 'Cadiente', 'preza', '', 'mlgaming142@gmail.com', '$2y$10$OV/KIBgAxwxfqZSOM/b2hebTveBi3BV/FK.Qd.3/vqqt9GmRQs.WS', 'Resident', 134, '../uploads/441799097_1205342757295168_7001114824101829619_n.jpg', '', '', '', '', '', '', NULL, 0, '826404', 1, '2024-09-21 14:31:09', 0, 'unread'),
(104, 'eso', 'villanueva', 'Rosario', '', 'tienxxx@gmail.com', '$2y$10$rkNcO9Cp2u/DdcbRdrk.X.mIMv48DvdFynIDzp64KNlEcIKTYPJfe', 'Barangay Official', 136, '../uploads/profile_66ceaf48b9e1c.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(105, 'bungol', 'kha', 'bhou', '', 'bungol@gmail.com', '$2y$10$mXFbXS.tFrXURu3m2cODR.CzpBeDaJ35dKTB/2f7dY8Qd5g9NNYFK', 'Resident', 137, '../uploads/profile_66cfd6ffeb536.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(106, 'din', 'da', 'xy', '', 'risidint@gmail.com', '$2y$10$AVEVUDQV7pE8TmUQ7ZOx9e8nTRp2Sjic9IJyhcn18vC9nN4fpQldq', 'Resident', 138, '../uploads/profile_66d0638aa9715.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(107, 'haryl', 'Balla', 'conceptoin', '', 'h@gmail.com', '$2y$10$gnKdPhTiwwMkZrVuixAcUe7Pi/K3tcZUWkRpLwZ.Izf2MQdEG6jMW', 'Barangay Official', 139, '../uploads/profile_66d066dee6955.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(108, 'din', 'da', 'xy', 'jr', 'fugu@gmail.com', '$2y$10$s/RyATM8KMk/FmG7GIddW.6GN8ERegE/L4c7lqmbGHV/X1jXCQ/5G', 'Resident', 140, '../uploads/profile_66d99e303d356.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(109, 'pough', 'tah', 'hays', '', 'villaysmael@gmail.com', '$2y$10$fV5QYwZr67QXNo4HghNGE.DJJivQdfB3//MV3tnNzhJGBuTV.zVQO', 'Resident', 141, '../uploads/profile_66d9a4b75c3be.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(110, 'jay', 'ar', 'gumabon', '', 'villavicenta@gmail.com', '$2y$10$GEsBMNpbrhmKptpJd7Oh8e7lTpATyyzNU/9jYnRO28IY.GK1WAWdm', 'Resident', 142, '../uploads/profile_66d9a576d329e.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(111, 'rus', 'sel', 'imel', '', 'villaverde@gmail.com', '$2y$10$zuEVm2UkcZLLnyH9R27IVOjzi1ood9sF4TteDX7pssHXoNXUs4hgi', 'Resident', 143, '../uploads/profile_66d9a6655b718.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(112, 'ja', 'mes', 'lems', '', 'villatanza@gmail.com', '$2y$10$0zxikt2s2pbMGJ/d9eUjVeRErkd75o6x9r0i3s3l6bVec/3.zkZJC', 'Resident', 144, '../uploads/profile_66d9a8751917a.jfif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(113, 'kat', 'lin', 'salbag', '', 'villaserafica@gmail.com', '$2y$10$.REmYoh.hKAIBI.d2.FmceU4YBICkDQS6MNlFGT3pCpLqsHcAvP6y', 'Resident', 145, '../uploads/profile_66d9a9e8cf811.jfif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(114, 'ba', 'yan', 'jan', '', 'villaremedios@gmail.com', '$2y$10$17I.oijzKb6dNJsx2dqNguBryoT4yRXp.uEdb4uE503nGm/.AflPW', 'Resident', 146, '../uploads/profile_66d9aa96212cc.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(115, 'sesa', 'me', 'seed', '', 'villaquirino@gmail.com', '$2y$10$MqxBK6UC0RqkDM5ceX6KgeXQKV5PWoft4/d6FMcP/g0r7eVde5q26', 'Resident', 147, '../uploads/profile_66d9ac8e0dd17.jpg', '', '', '', '', '', '', NULL, 1, NULL, 0, NULL, 0, 'unread'),
(116, 'mar', 'ky', 'san', '', 'villapereda@gmail.com', '$2y$10$O7zOWXQqaAipeHYvIfPSi.IP1BNtyOLxidqnqZ2LqVBbZ70JmoOZu', 'Resident', 148, '../uploads/profile_66d9ae95d3103.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(117, 'de', 'puuuuuuu', 'tah', '', 'villapadian@gmail.com', '$2y$10$thi2/iRL03ZEQxiTHSk4EuWcrklTEiC7Q5OftzKTji6E0JfQSCN7a', 'Resident', 149, '../uploads/profile_66d9af35d4d27.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(118, 'jan', 'mi', 'lloyd', '', 'villanuesa@gmail.com', '$2y$10$Y6OotCv0/dkr7hnqecfdPeOo0zrysloR1MtOhYm9cLpT2LS7.A2o2', 'Resident', 150, '../uploads/profile_66d9b01e7d344.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(119, 'yra', 'lei', 'ira', '', 'villagomez@gmail.com', '$2y$10$ttdmmtvepA9t2ty6onGXLe0edS0BAc/nzkCt9HSK6BAganFo.srya', 'Resident', 151, '../uploads/profile_66d9b09e984ce.jfif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(120, 'ha', 'mil', 'ton', '', 'villafabia@gmail.com', '$2y$10$we5dk3tqyaXb4oZ3ou6VlOUlYGLzN5VQatJPqIEzNNkuka0rxeq6G', 'Resident', 152, '../uploads/profile_66d9b1777edcb.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(121, 'an', 'gel', 'babs', '', 'villacruz@gmail.com', '$2y$10$V9LQuJlmuvQF2wc35UKWcuP1VhqiIqqObjGawPjT9zLvgadyLUjAS', 'Resident', 153, '../uploads/profile_66d9b2159c87b.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(122, 'ha', 'ril', 'konsiption', '', 'villaconception@gmail.com', '$2y$10$xhW2c6zdyNbmVplKW36Uduln67EXeZb9KwPveb75Nycj3VrI4lZ0G', 'Resident', 154, '../uploads/profile_66d9b2d690e71.jfif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(123, 'cha', 'rot', 'lang', '', 'villaagullana@gmail.com', '$2y$10$ncpo6YPGLgB2Hl7dmEHdtuAIPSaF3gwaANxgYcjo0BasUkr5s5TES', 'Resident', 155, '../uploads/profile_66d9b4848fc83.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(124, 'ya', 'me', 'te', '', 'taggappan@ghmail.com', '$2y$10$U0MBSUkT95l2qWiR/IXxIedVGUYyMsziL45TpbSGt5PjpvjmWgZ0m', 'Resident', 156, '../uploads/profile_66d9b7b36d5a9.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(125, 'mar', 'ki', 'ban', '', 'soyung@gmail.com', '$2y$10$vxM5.R1zEgzEyKrD4LFTMuZtcRaVJuJhcZA0jZ6Bn/c34ncAklpXy', 'Resident', 157, '../uploads/profile_66d9b8f0a2789.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(126, 'flo', 'rie', 'mae', '', 'sinabbaran@gmail.com', '$2y$10$nQcEWZKWfYshSAZ72BH60OfLe9cTjlQNkRNqD3nrgq20w9eoXh54C', 'Resident', 158, '../uploads/profile_66d9bad4ad3a2.jfif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(127, 'but', 'choy', 'blaire', '', 'silauannorte@gmail.com', '$2y$10$nwFYIVTT6I0vKGtjuRI5B.KkVxFP6mvimCX9UIymCCav4fMgq/dt.', 'Resident', 159, '../uploads/profile_66d9bda481e56.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(128, 'jhea', 'mae', 'jords', '', 'silauansur@gmail.com', '$2y$10$pXjaaTIbVDEAsnA2Y0y9lefQC902m/0rPW35KDVlG7d2eitiRsSde', 'Resident', 160, '../uploads/profile_66d9beb6d5cb0.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(129, 'pa', 'noy', 'penoy', '', 'santodomingo@gmail.com', '$2y$10$mV5b4qi4EzNGLZBTk6kDiuVAZ8toPADO90R5NuCN8.1r.oBdCENa2', 'Resident', 161, '../uploads/profile_66d9c0967c831.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(130, 'jan', 'lloyd', 'ventura', 'jr', 'santamonica@gmail.com', '$2y$10$OVGwdp0FZW2tIH/amQVnQ.CFhJcWw1Rvjvn31Nkoc1RDqNG.Kpymi', 'Resident', 162, '../uploads/profile_66d9c24132877.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(131, 'ben', 'jie', 'lyn', '', 'santamaria@gmail.com', '$2y$10$ft45l61.TYx0oXh1L.C.QOKqXQZGIyPgZp79ULj8n0g/ocxmJ.YyS', 'Resident', 163, '../uploads/profile_66d9c36eef592.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(132, 'mar', 'cial', 'lim', '', 'santacruz@gmail.com', '$2y$10$qhSz8NpkhlI1avmmB/G3h.rp2WPOlujqCw6uwhXYF/wOLXNHEG8Mm', 'Resident', 164, '../uploads/profile_66d9c474bd584.avif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(133, 'ol', 'lit', 'ros', 'jr', 'santaana@gmail.com', '$2y$10$9aKTxuZj5kFHJublUlhPRePtIo2oSvKIcUhSxFOpr/jcwzVxt8mUO', 'Resident', 165, '../uploads/profile_66d9c52cae727.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(134, 'san', 'dok', 'yum', '', 'sansalvador@gmail.com', '$2y$10$raqzbQ0TIJiVq3svzrqiUuIDlZmOWTIpDvKBrYKmYahDSOuCA9gxi', 'Resident', 166, '../uploads/profile_66d9c5a10a932.avif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(135, 'san', 'mig', 'guel', '', 'sanmiguel@gmail.com', '$2y$10$CtV80mZ79ZlME3TGm1VUcOqOE1g61Ez.R7ZJmoqt7pRK4JB5Vcq1G', 'Resident', 167, '../uploads/profile_66d9c63c7f435.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(136, 'san', 'juan', 'sia', '', 'sanjuan@gmail.com', '$2y$10$CQL7Ec0Dyzglg030oMafLuJYetmrOVMkDSo2zm8sOK5cDV7APo7tq', 'Resident', 168, '../uploads/profile_66d9c6a922a41.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(137, 'fe', 'li', 'pe', '', 'sanfelipe@gmail.com', '$2y$10$ySjki2KJn2HMU2QZSNwbZ.QHJNJZFrAPUWuspONeuCdvGLF72IOQG', 'Resident', 169, '../uploads/profile_66d9c72fdc81d.jfif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(138, 'san', 'car', 'los', 'jr', 'sancarlos@gmail.com', '$2y$10$b.LN.Dzy8dtREBm.zkLRm.6TnuVxxBF16mrbfrr5k5yMfOLxtYqlW', 'Resident', 170, '../uploads/profile_66d9cea4a3602.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(139, 'san', 'an', 'ton', '', 'SanAntonioMinit@gmail.com', '$2y$10$02WLdHyhmG3EZP28npEFIOI.SS.Noi3DVlV5db11GmUUf24foJkiK', 'Resident', 171, '../uploads/profile_66d9cfd2ef3c1.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(140, 'uu', 'gad', 'yo', '', 'SanAntonioUgad@gmail.com', '$2y$10$5JHPJ03TqMzFb7mQ2d.eW.scKPIfvSG6n/4Bjh0n3XRZZ1dgFsn/G', 'Resident', 172, '../uploads/profile_66d9d058b95b8.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(141, 'sal', 'bag', 'ka', '', 'salvacion@gmail.com', '$2y$10$Pxk/cn4HUGGI2Oqw9Ritd.3NOFJblsodlogSuj94aWCRJDvBFzjIO', 'Resident', 173, '../uploads/profile_66d9d0d489605.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(142, 'sa', 'lay', 'xy', '', 'salay@gmail.com', '$2y$10$3KIIwK/Vy8xxsfSEFxtNd.JmXAlq5X.bWPADHtO7UYBXLZtk6ihmK', 'Resident', 174, '../uploads/profile_66d9d1612ad5d.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(143, 'ru', 'mang', 'ay', '', 'rumangay@gmail.com', '$2y$10$3jivJSxtwXQh16MIRrGwtuQZJ0LorgAbPebtaHPtu9o/m4IW0bNom', 'Resident', 175, '../uploads/profile_66d9d1c9855ff.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(144, 'pa', 'ngal', 'sur', '', 'pangalsur@gmail.com', '$2y$10$QnFpGmJk4gJOY/v5cg23puOceIHPKZwHUL5UmFD6qnVCLK24F0bgq', 'Resident', 176, '../uploads/profile_66d9d2343f155.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(145, 'pa', 'ngal', 'norte', '', 'pangalnorte@gmail.com', '$2y$10$8ytvrHjj6G2TdrR82bDnt.IhTzAiw.oVBTvberxHIZP33q0vIOUWy', 'Resident', 177, '../uploads/profile_66d9d30c25ab8.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(146, 'pag', 'asa', 'baaa', '', 'pagasa@gmail.com', '$2y$10$/.l2Lkf8UsAz1d6jgGhTieviszUkkr6W7kPL9OuhPCQMm3RsTG6JS', 'Resident', 178, '../uploads/profile_66d9d3ca60a5b.avif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(147, 'br', 'oo', 'kk', '', 'nilumisu@gmail.com', '$2y$10$u7.uN1RDtpJ3Q7SqGoWCG.vgFjDJSST9i9n.gzf5cHZGUX7V.7Fm2', 'Resident', 179, '../uploads/profile_66d9d46a1fbcf.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(148, 'nag', 'ba', 'ra', '', 'narra@gmail.com', '$2y$10$z6LNunMmiY8fBxVyot9APO048YxF8imarrU7bI82AIwQgSKgWEP4y', 'Resident', 180, '../uploads/profile_66d9d4dc98bf1.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(149, 'ma', 'li', 'tao', '', 'malitao@gmail.com', '$2y$10$aZ877dRXihLBu4pcSm0AZeys7HEGzNZYoS6r0Pf/NwCgVcUCFWUsi', 'Resident', 181, '../uploads/profile_66d9d55a02025.jfif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(150, 'zoro', 'my', 'loves', '', 'maligaya@gmail.com', '$2y$10$49usekRjmCdhVTD0kPN6T.973evWx9XhTfLiTtG.iQENS.jEXMfHi', 'Resident', 182, '../uploads/profile_66d9d6493db44.jfif', '', '', '', '', '', '', NULL, 0, '468819', 0, NULL, 0, 'unread'),
(151, 'na', 'mi', 'swan', '', 'malibago@gmail.com', '$2y$10$qbWWP93h5iTf3TdzVajZvOkEDzVMo5tQH2bQlfaymp9l4y1NpnHjy', 'Resident', 183, '../uploads/profile_66d9d6bb5ded5.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(152, 'san', 'ji', 'tog', '', 'magleticia@gmail.com', '$2y$10$HBc0bIe2/EaDLZ2.kUPG4uX9X/DI5YobqR5YUdVIVRHYhTDE/biAG', 'Resident', 184, '../uploads/profile_66d9d75574b1c.jfif', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(153, 'luf', 'fy', 'baby', '', 'madadamian@gmail.com', '$2y$10$ba7W5WyrLNZlFmdpDAv.Bu9FA0egdkPX2/Cks5e7uKbyDbR0jqKjK', 'Resident', 185, '../uploads/profile_66d9d7f1839a0.png', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(154, 'brayan', 'villanueva', 'Aquino', '', 'bja@gmail.com', '$2y$10$apiOV8HlRvjgfHHhP3JhtuINDddOgUU1XPYZZy1nwiE7AskV0KEhC', 'Resident', 186, '../uploads/profile_66da182f7583c.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(155, 'lloyd', 'cabagan', 'manuel', '', 'lloyd@gmail.com', '$2y$10$BNE5T7gBaGtwdja.58iP3e347wwHexP/bDr.9/czqHTH81RdkXuXG', 'Resident', 187, '../uploads/profile_66da18833768f.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(156, 'ben', 'Mondragon', 'Aquino', '', 'ben@gmail.com', '$2y$10$5uP5oF4X95fWpAF3otQYt.VIKp5zQZ/yRwZdJVe2GXP8Jp0lXimaS', 'Barangay Official', 188, '../uploads/profile_66da19889e220.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(157, 'eso', 'villanueva', 'pili', '', 'barangay2@gmail.com', '$2y$10$PdSm6a0qmcrrsbxBAL/CreAwVRuNXmP8ZRfOGfoDKaE8S1SmSA.OO', 'Barangay Official', 189, '../uploads/profile_66dae92084d29.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(158, 'xfsfs', 'aadad', 'aadd', '', 'maligaya1@gmail.com', '$2y$10$kdWWcCY55p6YnOdLEi482.HAqRrZa6ji/doG9fP52NZpZftq0tBlW', 'Barangay Official', 190, '../uploads/profile_66dc346a9ecdf.jpg', '', '', '', '', '', '', NULL, 0, '749854', 0, NULL, 0, 'unread'),
(159, 'ap', 'dul', 'jakusi', '', 'villaverde1@gmail.com', '$2y$10$Bwhfh7u0.YqRqbQaVVRuYOP5AMFPHAjP0agy7wk1yZ8R/sydmOniC', 'Barangay Official', 191, '../uploads/profile_66dc380c6b9ae.jpg', '', '', '', '', '', '', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(161, 'xfsfs', 'aadad', 'aadd', '', 'd@gmail.com', '$2y$10$yiBZ5AzmvECAo7eTRdywD.wYmbezQsfs2odvd0i.PCgH4B5x0vHAC', 'Barangay Official', 195, '../uploads/profile_66dd0f330bc94.jpg', 'What was your childhood nickname?', '$2y$10$jAMjBos9uEUbYzqYh27B/uaV5YIZbR//TF.HSD6q3q2.1WBWYKT7O', 'In what city were you born?', '$2y$10$MZxMT4WI2nYQGPSg5zDX.uPHTH5UkKmqEF5MD60lxeaAqML1XQa86', 'What was your high school mascot?', '$2y$10$uxOyr7FYsLH/2hpo3CJhPeeET2Yy5NchYSEsFBjuS2bSwVmVLCyOe', NULL, 0, NULL, 0, NULL, 0, 'unread'),
(163, 'excel', 'N', 'Preza', '', 'maligaya164@gmail.com', '$2y$10$MqxBK6UC0RqkDM5ceX6KgeXQKV5PWoft4/d6FMcP/g0r7eVde5q26', 'Resident', 99, '../uploads/profile_66dd94cb579b6.jpg', 'What was your childhood nickname?', '$2y$10$0he/z3Qzo3Rzv82BR3XDSu5/1lCA5XAKYKaoPKpIJ4KZdnzpFnG8S', 'What was the name of your elementary school?', '$2y$10$gBE9PJw4aJSWsxVBwMQ7PODN5UDGSZuGErkC9us77Q04XNR7ACEuC', 'What is your mother’s maiden name?', '$2y$10$.ZTiTjZwIagmT2qCnNrGqe9qvxfbFtd.zNQWmi6iAOpkPwbDd5Zri', NULL, 1, NULL, 0, NULL, 0, 'unread'),
(164, 'aldrin', 'Domael', 'damance', '', 'a@gmail.com', '$2y$10$P/PcqT.1Ca3NKdkZUi12EuiF4tZgXhF5PMSLZks1Xfc3/tQ471fnq', 'Barangay Official', 197, '../uploads/profile_66e2baa6e7dca.png', 'What was your childhood nickname?', '$2y$10$34B6/QfDK.SxnjSNuZuw5u2kVgvmopqFxuxfruf80S6xeLU82bw36', 'In what city were you born?', '$2y$10$VhETvfWZGxvV3JEHSFriueJqLTXEepyzBEMha5mnTtPpNrG/DwZkK', 'What was your high school mascot?', '$2y$10$DrPOwguyLW2Z3bXHg.4SzubWBRqL/b.Ob3QuSKAE30O8YERibB/EO', NULL, 1, NULL, 1, NULL, 0, 'unread'),
(165, 'excel', 'Mondragon', 'Aquino', '', 'reyvenpili04@gmail.com', '$2y$10$QWr8jkGU3Zq9hfxd6bX2zOWyPwOMKYlmWjcjmkmd0rH65lr0/ITKW', 'Barangay Official', 198, '../uploads/profile_66e2bb14863c0.jpg', 'What was your childhood nickname?', '$2y$10$uTIpejp4/EWwh20pn3WhPu5Xga7T5njHVEWn6F3hQgjzGWr1LKGoy', 'In what city were you born?', '$2y$10$.vr9H9hBOdXGUqvgld3sAO59tffQztpb7j.1MqDes07iSlbdqZNh2', 'What is your mother’s maiden name?', '$2y$10$jPc9Ml3yXGhYRZ7pVN3Ovu3/k5JTkjqO5Qu01xXOeQ7iz4d618KCe', NULL, 0, '380186', 0, NULL, 0, 'unread'),
(166, 'excel', 'Nicole', 'Aquino', '', 'ketnana101@gmail.com', '$2y$10$0C6hscCrDkc9V6l6b5zChuyNldE2NJUd7kgkFhW/5aV0aPiXi82hW', 'Barangay Official', 203, '../uploads/profile_66e2bd4210ce0.jpg', 'What was your childhood nickname?', '$2y$10$AzKh1aOqnKTLwojB0jqaqeKfRPtPpiF2fdlEltMQGdqtD1jGufyAK', 'In what city were you born?', '$2y$10$HhfFsEO0Zb3AJSeiuhOhfuLMcGVor9396.F5n7F96aazBYQxO2cVG', 'What was your high school mascot?', '$2y$10$EjF6kHKNLR3acpIrX69BG.Jou8xo6rUQccTQTkm0sI6ktyzVOg.c2', NULL, 0, '519497', 0, NULL, 0, 'unread'),
(167, 'Princess', 'Del Valle', 'Moon', '', 'moon@gmail.com', '$2y$10$B2mimNgyFHyTBqwU40K0be12G6X0hkCQUOFhz8HqrDsH48CxGgtwW', 'Resident', 99, '../uploads/profile_66e358edace8d.jfif', 'What was your childhood nickname?', '$2y$10$hzXVvVNVvnGVkU9/3DCvEOFlScUORk4oyOZsx6wo3F8edzYnHjQJy', 'In what city were you born?', '$2y$10$AQ1gGkD2Ml7No99Aul1B2uKgpFoo90HbU3yVxFaRc/qfdmYLHN6wK', 'What is your mother’s maiden name?', '$2y$10$WhqesGCnaMZlaNLhqFuMtuD8st1x2K7P7acYjP1oR61WBsg9G20z6', NULL, 1, NULL, 0, NULL, 0, 'unread'),
(168, 'lance', 'gantec', 'lazaro', '', 'lancephilip.lazaro@gmail.com', '$2y$10$LSButk5iWTHQXrN112FThecNjVz5UV/flHslOYz6BmoZyYmBpDksS', 'Resident', 205, '../uploads/profile_66e6cb32b7f1b.jpg', 'What was your childhood nickname?', '$2y$10$XcaZtCKskLlMZdlQCgwpTOIAiDZP73tqtWdeJsfy3U5xMUauNBpE6', 'In what city were you born?', '$2y$10$BC1gm1EfI5Lx58rO.z9w4O2exFYH3nj3h.hx0SZdJx.3XqaJkoyve', 'What is your mother’s maiden name?', '$2y$10$re99YrbwJ30XMaEAqc.5oOeqZ207uV9DVOuESDvA/81ZQWtOb6QYC', NULL, 1, NULL, 0, NULL, 0, 'unread'),
(169, 'aldrin', 'Domael', 'pili', '', 'al@gmail.com', '$2y$10$EF5NhB1De4kvVZ6fwkvIqOowEnh0W1Gi.258bd1RT6QYoyGodlB5K', 'Resident', 206, '../uploads/profile_66ee409e5f4e7.png', 'What was your childhood nickname?', '$2y$10$/mV1NfoIDl2Odle8mfnCBO4WnL0TjgspskQGdmj3Rh5Jm0hlqi.uK', 'In what city were you born?', '$2y$10$6OQVOQJy2bBQHA6mYEXOhe.ekAKZztxzjRQVhEZ0OWBSLKbsZ63cq', 'What is your mother’s maiden name?', '$2y$10$KVw/JfwTt8Lj.W8vr0jZcOHarYZeKBWk.M4KN95WhTcflV94ANBq.', NULL, 1, NULL, 0, NULL, 0, 'unread'),
(170, 'reyzon', 'Bascos', 'Mabini', '', 'b09969185842@gmail.com', '$2y$10$ru5tr9QdwdoCbTuIAKJqquwJJ5SbdlulyBeA8ipO4uOZsHgmOT.MO', 'Resident', 207, '../uploads/profile_66ef4904d570a.png', 'What was your childhood nickname?', '$2y$10$Wq0QNcKCO94d1jt5Xr71i.9DQ1Xga/Bmp6JgVKgtPqrDHE9zB1ay2', 'In what city were you born?', '$2y$10$7NBybhTWV/Bt0cbsUYRju.FZkg7nVSdxk2O81hQO/wmQoq/L2vVmG', 'What was your high school mascot?', '$2y$10$WR8sU3SOJb2gXBER3OBZ9.slzXb5qb78vkjHq.DZacNB3G2TlouBS', NULL, 1, NULL, 0, NULL, 0, 'unread');

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
(99, 'Maligaya'),
(100, 'Diasan'),
(101, 'Aromin'),
(102, 'Aromin'),
(103, 'Gucab'),
(104, 'Gucab'),
(105, 'Gucab'),
(106, 'Gucab'),
(107, 'Fugu'),
(108, 'Angoluan'),
(109, 'Angoluan'),
(110, 'Gucab'),
(111, 'Gucab'),
(112, 'Buneg'),
(113, 'Babaran'),
(114, 'Angoluan'),
(115, 'Angoluan'),
(116, 'Angoluan'),
(117, 'Angoluan'),
(118, 'Maligaya'),
(119, 'Angoluan'),
(120, 'Angoluan'),
(121, 'San Fabian'),
(122, 'San Fabian'),
(123, 'San Fabian'),
(124, 'San Fabian'),
(125, 'Fugu'),
(126, 'Fugu'),
(127, 'Maligaya'),
(128, 'Angoluan'),
(129, 'Babaran'),
(130, 'San Fabian'),
(131, 'San Fabian'),
(132, 'San Fabian'),
(133, 'Garit Norte'),
(134, 'San Juan'),
(135, 'Angoluan'),
(136, 'Angoluan'),
(137, 'San Fabian'),
(138, 'Fugu'),
(139, 'Fugu'),
(140, 'Fugu'),
(141, 'Villa Ysmael (formerly T. Belen)'),
(142, 'Villa Vicenta'),
(143, 'Villa Verde'),
(144, 'Villa Tanza'),
(145, 'Villa Serafica'),
(146, 'Villa Remedios'),
(147, 'Villa Quirino'),
(148, 'Villa Pereda'),
(149, 'Villa Padian'),
(150, 'Villa Nuesa'),
(151, 'Villa Gomez'),
(152, 'Villa Fabia'),
(153, 'Villa Cruz'),
(154, 'Villa Concepcion'),
(155, 'Villa Agullana'),
(156, 'Taggappan (Poblacion)'),
(157, 'Soyung (Poblacion)'),
(158, 'Sinabbaran'),
(159, 'Silauan Norte (Poblacion)'),
(160, 'Silauan Sur (Poblacion)'),
(161, 'Santo Domingo'),
(162, 'Santa Monica'),
(163, 'Santa Maria'),
(164, 'Santa Cruz'),
(165, 'Santa Ana'),
(166, 'San Salvador'),
(167, 'San Miguel'),
(168, 'San Juan'),
(169, 'San Felipe'),
(170, 'San Carlos'),
(171, 'San Antonio Minit'),
(172, 'San Antonio Ugad'),
(173, 'Salvacion'),
(174, 'Salay'),
(175, 'Rumang-ay'),
(176, 'Pangal Sur'),
(177, 'Pangal Norte'),
(178, 'Pag-asa'),
(179, 'Nilumisu'),
(180, 'Narra'),
(181, 'Malitao'),
(182, 'Maligaya'),
(183, 'Malibago'),
(184, 'Magleticia'),
(185, 'Madadamian'),
(186, 'Busilelao'),
(187, 'Dammang East'),
(188, 'Dammang East'),
(189, 'Villa Quirino'),
(190, 'Maligaya'),
(191, 'Villa Verde'),
(192, 'Angoluan'),
(193, 'Angoluan'),
(194, 'Angoluan'),
(195, 'Angoluan'),
(196, 'Maligaya'),
(197, 'Angoluan'),
(198, 'Angoluan'),
(199, 'Angoluan'),
(200, 'Angoluan'),
(201, 'Angoluan'),
(202, 'Angoluan'),
(203, 'Angoluan'),
(204, 'Maligaya'),
(205, 'Maligaya'),
(206, 'Maligaya'),
(207, 'Maligaya');

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
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `tbl_complaintcategories`
--
ALTER TABLE `tbl_complaintcategories`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  MODIFY `complaints_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=402;

--
-- AUTO_INCREMENT for table `tbl_hearing_history`
--
ALTER TABLE `tbl_hearing_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_image`
--
ALTER TABLE `tbl_image`
  MODIFY `image_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=420;

--
-- AUTO_INCREMENT for table `tbl_info`
--
ALTER TABLE `tbl_info`
  MODIFY `info_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT for table `tbl_login_logs`
--
ALTER TABLE `tbl_login_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `tbl_users_barangay`
--
ALTER TABLE `tbl_users_barangay`
  MODIFY `barangays_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

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
-- Constraints for table `tbl_hearing_history`
--
ALTER TABLE `tbl_hearing_history`
  ADD CONSTRAINT `tbl_hearing_history_ibfk_1` FOREIGN KEY (`complaints_id`) REFERENCES `tbl_complaints` (`complaints_id`);

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
