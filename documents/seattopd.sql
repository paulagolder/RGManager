-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 12, 2020 at 03:44 PM
-- Server version: 10.3.27-MariaDB-1:10.3.27+maria~bionic-log
-- PHP Version: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `RoadGroups`
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
('RA', 'BLY', 'LCC', '2019'),
('RA', 'BLY', 'LDC', '2019'),
('RA', 'LRN', 'SCC', '2016'),
('RB', 'BLY', 'LCC', '2019'),
('RB', 'BLY', 'LDC', '2019'),
('RB', 'LRN', 'SCC', '2016'),
('RB1', 'BLY', 'LDC', '2019'),
('RB1', 'LCS', 'SCC', '2016'),
('RB1', 'PTR', 'LCC', '2019'),
('RC', 'BLY', 'LCC', '2019'),
('RC', 'BLY', 'LDC', '2019'),
('RC', 'LRN', 'SCC', '2016'),
('RD', 'BOW', 'LCC', '2019'),
('RD', 'LRN', 'SCC', '2016'),
('RD', 'STW', 'LDC', '2019'),
('RE', 'CHD', 'LCC', '2019'),
('RE', 'CHD', 'LDC', '2019'),
('RE', 'LRN', 'SCC', '2016'),
('RF', 'CHD', 'LCC', '2019'),
('RF', 'CHD', 'LDC', '2019'),
('RF', 'LRN', 'SCC', '2016'),
('RG', 'CHD', 'LCC', '2019'),
('RG', 'CHD', 'LDC', '2019'),
('RG', 'LCN', 'SCC', '2016'),
('RG1', 'CHD', 'LDC', '2019'),
('RG1', 'GRK', 'LCC', '2019'),
('RG1', 'LCS', 'SCC', '2016'),
('RH', 'CHD', 'LCC', '2019'),
('RH', 'CHD', 'LDC', '2019'),
('RH', 'LCN', 'SCC', '2016'),
('RJ', 'CUR', 'LCC', '2019'),
('RJ', 'CUR', 'LDC', '2019'),
('RJ', 'LCN', 'SCC', '2016'),
('RK', 'CHD', 'LCC', '2019'),
('RK', 'CHD', 'LDC', '2019'),
('RK', 'LCN', 'SCC', '2016'),
('RL', 'CHD', 'LCC', '2019'),
('RL', 'CHD', 'LDC', '2019'),
('RL', 'LCS', 'SCC', '2016'),
('RM1', 'LCS', 'SCC', '2016'),
('RM1', 'LEO', 'LCC', '2019'),
('RM1', 'LEO', 'LDC', '2019'),
('RM2', 'LCS', 'SCC', '2016'),
('RM2', 'LEO', 'LCC', '2019'),
('RM2', 'LEO', 'LDC', '2019'),
('RN1', 'LCS', 'SCC', '2016'),
('RN1', 'LEO', 'LCC', '2019'),
('RN1', 'LEO', 'LDC', '2019'),
('RN2', 'LCS', 'SCC', '2016'),
('RN2', 'LEO', 'LCC', '2019'),
('RN2', 'LEO', 'LDC', '2019'),
('RP', 'LCS', 'SCC', '2016'),
('RP', 'STJ', 'LCC', '2019'),
('RP', 'STJ', 'LDC', '2019'),
('RQ', 'LCS', 'SCC', '2016'),
('RQ', 'STJ', 'LCC', '2019'),
('RQ', 'STJ', 'LDC', '2019'),
('RR', 'LCS', 'SCC', '2016'),
('RR', 'STJ', 'LCC', '2019'),
('RR', 'STJ', 'LDC', '2019'),
('RS', 'LCS', 'SCC', '2016'),
('RS', 'STJ', 'LCC', '2019'),
('RS', 'STJ', 'LDC', '2019'),
('RT', 'LCS', 'SCC', '2016'),
('RT', 'STW', 'LCC', '2019'),
('RT', 'STW', 'LDC', '2019'),
('RU', 'LCS', 'SCC', '2016'),
('RU', 'STW', 'LCC', '2019'),
('RU', 'STW', 'LDC', '2019'),
('RW', 'LCS', 'SCC', '2016'),
('RW', 'STW', 'LCC', '2019'),
('RW', 'STW', 'LDC', '2019'),
('RX', 'LCS', 'SCC', '2016'),
('RX', 'STW', 'LCC', '2019'),
('RX', 'STW', 'LDC', '2019');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `seattopd`
--
ALTER TABLE `seattopd`
  ADD UNIQUE KEY `index` (`pdid`,`seat`,`district`,`year`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
