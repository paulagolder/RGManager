-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 07, 2021 at 03:24 PM
-- Server version: 10.3.27-MariaDB-1:10.3.27+maria~bionic-log
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lerot_rgmanager`
--

-- --------------------------------------------------------

--
-- Table structure for table `seattopd`
--

CREATE TABLE `seattopd` (
  `pdid` varchar(5) CHARACTER SET utf8 NOT NULL,
  `seat` varchar(10) CHARACTER SET utf8 NOT NULL,
  `district` varchar(20) CHARACTER SET utf8 NOT NULL,
  `year` varchar(8) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `seattopd`
--

INSERT INTO `seattopd` (`pdid`, `seat`, `district`, `year`) VALUES
('RB1', 'LCS', 'SCC', '2021'),
('RG1', 'LCS', 'SCC', '2021'),
('RL', 'LCS', 'SCC', '2021'),
('RM1', 'LCS', 'SCC', '2021'),
('RM2', 'LCS', 'SCC', '2021'),
('RN1', 'LCS', 'SCC', '2021'),
('RN2', 'LCS', 'SCC', '2021'),
('RP', 'LCS', 'SCC', '2021'),
('RQ', 'LCS', 'SCC', '2021'),
('RR', 'LCS', 'SCC', '2021');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `seattopd`
--
ALTER TABLE `seattopd`
  ADD UNIQUE KEY `index` (`pdid`,`seat`,`district`,`year`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
