-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2021 at 09:01 AM
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
  `overall_status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`) VALUES
(7, 'Lending Tree', 'Test Manager', 'Test Manager', 'POC-1', 'Great', 8, 0, 0, 8, 8, '2021-10-11 09:43:24', 'green');

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
  `planned_vs_completed_ratio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sprint_data`
--

INSERT INTO `sprint_data` (`sprint_id`, `project_id`, `sprint_name`, `planned_story_point`, `actual_delivered`, `v2_delivered`, `lt_delivered`, `rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, `lt_reopen_percentage`, `v2_carryover_percentage`, `lt_carryover_percentage`, `planned_vs_completed_ratio`) VALUES
(10, 7, 'LCW 3/12 - 3/26', 52, 48, 3, 49, 3, 9, 3, 1, 0, 100, 18, 100, 2, 92);

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
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@v2.com', 1, 1),
(3, 'souvik', '6e339344b1894b0ca8eec4a4014a39b4', 'souvik.patra@v2solutions.com', 0, 1);

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
  MODIFY `project_id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sprint_data`
--
ALTER TABLE `sprint_data`
  MODIFY `sprint_id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
