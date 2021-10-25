-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2021 at 04:23 PM
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
  `is_sprint` smallint(6) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`, `is_sprint`) VALUES
(7, 'Web Analytics', 'Mamatha Joshi', 'Swapnil Borse', 'POC-1', '', 0, 2, 2, 1, 0, '2021-10-01 19:24:11', 'green', 1),
(8, 'Consumer Web', 'Mamatha Joshi', 'Sakshee', '-', '-', 0, 6, 6, 0, 0, '2021-10-04 19:24:22', 'amber', 0),
(10, 'Compare Cards', 'Mamatha Joshi', 'Pravin Dongare', '', '', 0, 3, 3, 0, 0, '2021-10-22 19:14:19', 'green', 0),
(12, 'Mortgage Purchase', 'Mamatha Joshi', 'Payal', '', '', 0, 3, 3, 0, 0, '2021-10-22 19:16:49', 'green', 0);

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
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sprint_data`
--

INSERT INTO `sprint_data` (`sprint_id`, `project_id`, `sprint_name`, `planned_story_point`, `actual_delivered`, `v2_delivered`, `lt_delivered`, `rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, `lt_reopen_percentage`, `v2_carryover_percentage`, `lt_carryover_percentage`, `planned_vs_completed_ratio`, `created_date`) VALUES
(11, 7, 'LCW 4/12 - 4/26', 52, 48, 3, 49, 6, 12, 14, 15, 10, 40, 40, 40, 20, 95, '2021-10-22 09:25:53'),
(12, 7, 'LCW 10/12 - 10/26', 52, 48, 3, 49, 9, 10, 20, 11, 5, 20, 25, 5, 50, 60, '2021-10-22 09:25:53'),
(13, 7, 'LCW 09/12 - 09/26', 52, 48, 3, 49, 20, 1, 2, 5, 8, 2, 18, 60, 10, 35, '2021-10-22 09:25:53'),
(14, 8, '2021: LCW 10/7 -10/21', 55, 28, 14, 41, 5, 0, 10, 17, 9, 36, 0, 71, 41, 51, '2021-10-22 09:25:53'),
(15, 8, '2021: LCW 9/23 - 10/7', 57, 15, 15, 42, 5, 0, 10, 32, 10, 33, 0, 67, 76, 26, '2021-10-22 09:25:53'),
(19, 8, '2021: LCW 10/7 -10/21', 57, 15, 15, 42, 5, 0, 10, 32, 10, 33, 0, 67, 76, 26, '2021-10-22 09:25:53'),
(20, 8, '2021: LCW 9/23 - 10/7', 52, 44, 19, 33, 5, 0, 3, 5, 14, 26, 0, 16, 15, 85, '2021-10-22 09:25:53'),
(21, 8, '2021: LCW 9/9 - 9/23', 58, 50, 40, 18, 3, 0, 3, 5, 37, 8, 0, 8, 28, 86, '2021-10-22 09:25:53'),
(22, 8, '2021: LCW 8/26 - 9/9', 48, 45, 19, 29, 0, 3, 3, 0, 19, 0, 10, 16, 0, 94, '2021-10-22 09:45:13'),
(23, 8, '2021: LCW 8/12 - 8/26', 55, 28, 14, 41, 5, 0, 10, 17, 9, 36, 0, 71, 41, 51, '2021-10-22 09:46:10'),
(24, 8, 'LCW 9/23 - 10/7', 56, 48, 44, 12, 0, 3, 3, 5, 44, 0, 25, 7, 42, 86, '2021-10-25 09:00:37'),
(25, 8, 'LCW 10/8 - 11/7', 56, 48, 44, 12, 3, 0, 3, 5, 41, 7, 0, 7, 42, 86, '2021-10-25 09:02:52'),
(26, 7, '2021: LCW 10/9 -10/21', 55, 28, 14, 41, 0, 0, 10, 17, 14, 0, 0, 71, 41, 51, '2021-10-25 19:38:20');

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
(3, 'souvik', '6e339344b1894b0ca8eec4a4014a39b4', 'souvik.patra@v2solutions.com', 0, 0);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sprint_data`
--
ALTER TABLE `sprint_data`
  MODIFY `sprint_id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
