-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 16, 2022 at 10:38 AM
-- Server version: 10.5.15-MariaDB-1:10.5.15+maria~focal
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
  `pdid` varchar(5) NOT NULL,
  `seat` varchar(10) NOT NULL,
  `district` varchar(20) NOT NULL,
  `year` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `seattopd`
--

INSERT INTO `seattopd` (`pdid`, `seat`, `district`, `year`) VALUES
('RA', 'BLY', 'LCC', '2021'),
('RA', 'BLY', 'LDC', '2021'),
('RA', 'LPC', 'UKP', '2021'),
('RA', 'LRN', 'SCC', '2021'),
('RB', 'BLY', 'LCC', '2021'),
('RB', 'BLY', 'LDC', '2021'),
('RB', 'LPC', 'UKP', '2021'),
('RB', 'LRN', 'SCC', '2021'),
('RB1', 'BLY', 'LDC', '2021'),
('RB1', 'LCS', 'SCC', '2021'),
('RB1', 'LPC', 'UKP', '2021'),
('RB1', 'PTR', 'LCC', '2021'),
('RC', 'BLY', 'LCC', '2021'),
('RC', 'BLY', 'LDC', '2021'),
('RC', 'LPC', 'UKP', '2021'),
('RC', 'LRN', 'SCC', '2021'),
('RD', 'BOW', 'LCC', '2021'),
('RD', 'LPC', 'UKP', '2021'),
('RD', 'LRN', 'SCC', '2021'),
('RD', 'STW', 'LDC', '2021'),
('RE', 'CHD', 'LCC', '2021'),
('RE', 'CHD', 'LDC', '2021'),
('RE', 'LCN', 'SCC', '2021'),
('RE', 'LPC', 'UKP', '2021'),
('RF', 'CHD', 'LCC', '2021');

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
