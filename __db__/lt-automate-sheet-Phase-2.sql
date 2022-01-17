-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2022 at 12:19 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lt-automate-sheet`
--

-- --------------------------------------------------------

--
-- Table structure for table `developer`
--

CREATE TABLE `developer` (
  `developer_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `developer_name` varchar(200) NOT NULL,
  `developer_status` smallint(6) NOT NULL DEFAULT 1,
  `dev_password` varchar(200) NOT NULL DEFAULT '10032586bc62852d2a7ed121da58d05f',
  `dev_role` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `developer`
--

INSERT INTO `developer` (`developer_id`, `project_id`, `developer_name`, `developer_status`, `dev_password`, `dev_role`) VALUES
(1, 2, 'sasharma', 1, '4005bc5a1cb74b536fe68e9ad6391cdb', 0),
(2, 2, 'hkothari', 1, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(3, 2, 'Bdavid', 1, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(4, 2, 'ppashte', 0, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(5, 2, 'akolekar', 0, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(6, 2, 'spawar', 1, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(7, 1, 'sborse', 0, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(8, 1, 'sangre', 1, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(9, 1, 'spatra', 0, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(10, 6, 'vlaghate', 1, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(11, 6, 'npatil', 1, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(12, 6, 'abhoir', 1, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(13, 5, 'sshinde', 1, '5e8edd851d2fdfbd7415232c67367cc3', 0),
(14, 5, 'jdharmadhikari', 1, '5e8edd851d2fdfbd7415232c67367cc3', 0);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` int(55) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `delivery_manager` varchar(255) NOT NULL,
  `project_manager` varchar(255) NOT NULL,
  `client_poc` varchar(255) NOT NULL,
  `client_feedback` text NOT NULL,
  `team_allocation` int(255) NOT NULL,
  `offshore_team_allocated` int(11) NOT NULL,
  `offshore_team_billable` int(11) NOT NULL,
  `onsite_team_allocated` int(11) NOT NULL,
  `onsite_team_billable` int(11) NOT NULL,
  `status_date` datetime NOT NULL DEFAULT current_timestamp(),
  `overall_status` varchar(100) NOT NULL,
  `is_sprint` smallint(6) NOT NULL DEFAULT 0,
  `developers` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`, `is_sprint`, `developers`) VALUES
(1, 'Web analytics', 'Mamatha Joshi', 'Swapnil', '', '', 0, 2, 2, 1, 0, '2021-10-27 14:37:48', 'green', 1, '8, 7, 9'),
(2, 'Consumer Web', 'Mamatha Joshi', 'Sakshee', '', '', 0, 6, 6, 2, 0, '2021-09-15 09:42:18', 'green', 0, '1, 2, 3, 4, 5, 6'),
(5, 'Snapcap', 'Mamatha Joshi', 'Jayant Dharmadhikari', '', '', 0, 2, 2, 3, 0, '2021-11-10 10:50:13', 'green', 0, '14, 13'),
(6, 'Canopy Next', 'Mamatha Joshi', 'Vishal Laghate', '', '', 0, 3, 3, 12, 0, '2021-11-15 06:25:14', 'green', 0, '12, 11, 10');

-- --------------------------------------------------------

--
-- Table structure for table `sprint_data`
--

CREATE TABLE `sprint_data` (
  `sprint_id` int(55) NOT NULL,
  `project_id` int(55) NOT NULL,
  `sprint_name` varchar(255) NOT NULL,
  `planned_story_point` int(100) NOT NULL,
  `actual_delivered` int(100) NOT NULL,
  `v2_delivered` int(100) NOT NULL,
  `lt_delivered` int(100) NOT NULL,
  `rework` int(100) NOT NULL,
  `lt_reoponed_sp` int(100) NOT NULL,
  `v2_carryover` int(100) NOT NULL,
  `lt_carryover` int(100) NOT NULL,
  `qa_passed` int(100) NOT NULL,
  `v2_reopen_percentage` int(100) NOT NULL,
  `lt_reopen_percentage` int(100) NOT NULL,
  `v2_carryover_percentage` int(100) NOT NULL,
  `lt_carryover_percentage` int(100) NOT NULL,
  `planned_vs_completed_ratio` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `sprint_goal` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sprint_data`
--

INSERT INTO `sprint_data` (`sprint_id`, `project_id`, `sprint_name`, `planned_story_point`, `actual_delivered`, `v2_delivered`, `lt_delivered`, `rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, `lt_reopen_percentage`, `v2_carryover_percentage`, `lt_carryover_percentage`, `planned_vs_completed_ratio`, `created_date`, `sprint_goal`) VALUES
(3, 2, '2021: LCW 9/23 - 10/7', 13, 2, 10, 3, 5, 0, 8, 3, 5, 50, 0, 80, 100, 15, '2021-10-27 15:28:59', ''),
(4, 2, '2021: LCW 9/9 - 9/23', 39, 8, 33, 6, 0, 3, 28, 3, 33, 0, 50, 85, 50, 21, '2021-10-27 15:31:37', ''),
(5, 2, '2021: LCW 9/9 - 9/23', 39, 8, 33, 6, 0, 0, 28, 3, 33, 0, 0, 85, 50, 21, '2021-10-29 03:44:36', ''),
(6, 2, '2021: LCW 9/9 - 9/23', 39, 8, 33, 6, 0, 3, 28, 3, 33, 0, 50, 85, 50, 21, '2021-10-29 03:58:02', ''),
(12, 6, '10.1: CAN 10.12-10.26', 88, 88, 19, 69, 3, 11, 0, 0, 16, 16, 16, 0, 0, 100, '2021-11-15 06:32:09', ''),
(15, 6, '9.2: CAN 9.28-10.12', 66, 66, 19, 47, 5, 12, 0, 0, 14, 26, 26, 0, 0, 100, '2021-11-15 06:48:06', ''),
(16, 2, '2021: LCW 10/7 -10/21', 55, 25, 34, 21, 3, 0, 15, 15, 31, 9, 0, 44, 71, 45, '2021-11-15 07:11:47', ''),
(17, 5, '2021: SC 10.1 (10.5 - 10.19)', 40, 21, 12, 28, 0, 2, 0, 19, 12, 0, 7, 0, 68, 53, '2021-11-15 09:35:56', ''),
(19, 5, '2021: SC 10.2 (10.19 - 11.2)', 38, 32, 15, 23, 2, 3, 0, 6, 13, 13, 13, 0, 26, 84, '2021-11-17 16:06:15', ''),
(58, 6, 'CAN Sprint 11.2: 11.23-12.7', 68, 50, 12, 38, 3, 11, 3, 15, 12, 20, 21, 20, 28, 74, '2022-01-17 10:05:39', ''),
(59, 6, '12.1: CAN 12.7-12.21', 50, 47, 13, 34, 8, 13, 0, 9, 13, 62, 35, 0, 24, 94, '2022-01-17 10:07:01', ''),
(71, 5, '2021: SC 11.1 (11.2 - 11.16)', 36, 17, 5, 12, 3, 8, 3, 8, 5, 38, 35, 38, 35, 47, '2022-01-17 16:23:10', ''),
(73, 1, '2021: Dec', 32, 32, 30, 2, 0, 0, 0, 0, 30, 0, 0, 0, 0, 100, '2022-01-17 16:46:14', '');

-- --------------------------------------------------------

--
-- Table structure for table `sprint_report`
--

CREATE TABLE `sprint_report` (
  `sprint_report_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `sprint_id` int(11) NOT NULL,
  `developer_id` int(11) NOT NULL,
  `issue_key` varchar(200) NOT NULL,
  `total_storypoint` int(11) NOT NULL,
  `completed_storypoint` int(11) NOT NULL,
  `carryover_storypoint` int(11) NOT NULL,
  `reopen_storypoint` int(11) NOT NULL,
  `developer_comments` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sprint_report`
--

INSERT INTO `sprint_report` (`sprint_report_id`, `project_id`, `sprint_id`, `developer_id`, `issue_key`, `total_storypoint`, `completed_storypoint`, `carryover_storypoint`, `reopen_storypoint`, `developer_comments`) VALUES
(85, 6, 58, 12, 'CAN-3166', 2, 1, 0, 0, ''),
(86, 6, 58, 12, 'CAN-3118', 3, 1, 0, 0, ''),
(87, 6, 58, 12, 'CAN-2943', 3, 1, 0, 0, ''),
(88, 6, 58, 12, 'CAN-3066', 0, 0, 0, 1, ''),
(89, 6, 58, 11, 'CAN-3065', 2, 1, 0, 0, ''),
(90, 6, 58, 11, 'CAN-3033', 3, 1, 0, 0, ''),
(91, 6, 58, 11, 'CAN-3022', 2, 1, 0, 0, ''),
(92, 6, 59, 11, 'CAN-3196', 0, 1, 0, 0, ''),
(93, 6, 59, 11, 'CAN-3033', 3, 0, 0, 1, ''),
(94, 6, 59, 12, 'CAN-3177', 2, 1, 0, 0, ''),
(95, 6, 59, 12, 'CAN-3168', 3, 1, 0, 0, ''),
(96, 6, 59, 12, 'CAN-3167', 2, 0, 0, 1, ''),
(97, 6, 59, 12, 'CAN-3066', 3, 0, 0, 1, ''),
(258, 5, 71, 14, 'SNAP-3426', 0, 1, 0, 0, ''),
(259, 5, 71, 13, 'SNAP-3414', 2, 1, 0, 0, ''),
(260, 5, 71, 13, 'SNAP-3412', 3, 0, 0, 1, ''),
(261, 5, 71, 13, 'SNAP-3405', 3, 0, 1, 0, ''),
(277, 1, 73, 8, 'DWA-1935', 2, 1, 0, 0, ''),
(278, 1, 73, 8, 'DWA-1928', 2, 1, 0, 0, ''),
(279, 1, 73, 8, 'DWA-1927', 2, 1, 0, 0, ''),
(280, 1, 73, 8, 'DWA-1920', 3, 1, 0, 0, ''),
(281, 1, 73, 8, 'DWA-1919', 3, 1, 0, 0, ''),
(282, 1, 73, 8, 'DWA-1915', 2, 1, 0, 0, ''),
(283, 1, 73, 8, 'DWA-1904', 3, 1, 0, 0, ''),
(284, 1, 73, 9, 'DWA-1922', 1, 1, 0, 0, ''),
(285, 1, 73, 9, 'DWA-1921', 1, 1, 0, 0, ''),
(286, 1, 73, 9, 'DWA-1918', 2, 1, 0, 0, ''),
(287, 1, 73, 9, 'DWA-1917', 2, 1, 0, 0, ''),
(288, 1, 73, 9, 'DWA-1913', 1, 1, 0, 0, ''),
(289, 1, 73, 9, 'DWA-1907', 1, 1, 0, 0, ''),
(290, 1, 73, 9, 'DWA-1903', 2, 1, 0, 0, ''),
(291, 1, 73, 9, 'DWA-1897', 3, 1, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` smallint(25) NOT NULL,
  `status` smallint(25) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`id`, `username`, `password`, `email`, `role`, `status`) VALUES
(1, 'test', '098f6bcd4621d373cade4e832627b4f6', 'test@test.com', 0, 1),
(2, 'admin', 'e6e061838856bf47e1de730719fb2609', 'admin@v2.com', 1, 1),
(3, 'souvik', '6e339344b1894b0ca8eec4a4014a39b4', 'souvik.patra@v2solutions.com', 0, 1),
(4, 'jayant.d@v2solutions.com', '4254c0e62628b4498e5b218d01d61a03', 'jayant.d@v2solutions.com', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `developer`
--
ALTER TABLE `developer`
  ADD PRIMARY KEY (`developer_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `sprint_data`
--
ALTER TABLE `sprint_data`
  ADD PRIMARY KEY (`sprint_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `sprint_report`
--
ALTER TABLE `sprint_report`
  ADD PRIMARY KEY (`sprint_report_id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `developer`
--
ALTER TABLE `developer`
  MODIFY `developer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sprint_data`
--
ALTER TABLE `sprint_data`
  MODIFY `sprint_id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `sprint_report`
--
ALTER TABLE `sprint_report`
  MODIFY `sprint_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
