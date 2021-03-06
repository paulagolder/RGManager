-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 20, 2021 at 03:11 PM
-- Server version: 10.3.27-MariaDB
-- PHP Version: 7.3.26

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
('LCC', 'BLY', 'RA', '2020'),
('LCC', 'BLY', 'RA', '2021'),
('LCC', 'BLY', 'RB', '2020'),
('LCC', 'BLY', 'RB', '2021'),
('LCC', 'BLY', 'RC', '2020'),
('LCC', 'BLY', 'RC', '2021'),
('LCC', 'BOW', 'RD', '2020'),
('LCC', 'BOW', 'RD', '2021'),
('LCC', 'CHD', 'RE', '2020'),
('LCC', 'CHD', 'RE', '2021'),
('LCC', 'CHD', 'RF', '2020'),
('LCC', 'CHD', 'RF', '2021'),
('LCC', 'CHD', 'RG', '2020'),
('LCC', 'CHD', 'RG', '2021'),
('LCC', 'CHD', 'RH', '2020'),
('LCC', 'CHD', 'RH', '2021'),
('LCC', 'CHD', 'RK', '2020'),
('LCC', 'CHD', 'RK', '2021'),
('LCC', 'CHD', 'RL', '2020'),
('LCC', 'CHD', 'RL', '2021'),
('LCC', 'CUR', 'RJ', '2020'),
('LCC', 'CUR', 'RJ', '2021'),
('LCC', 'GRK', 'RG1', '2020'),
('LCC', 'GRK', 'RG1', '2021'),
('LCC', 'LEO', 'RM1', '2020'),
('LCC', 'LEO', 'RM1', '2021'),
('LCC', 'LEO', 'RM2', '2020'),
('LCC', 'LEO', 'RM2', '2021'),
('LCC', 'LEO', 'RN1', '2020'),
('LCC', 'LEO', 'RN1', '2021'),
('LCC', 'LEO', 'RN2', '2020'),
('LCC', 'LEO', 'RN2', '2021'),
('LCC', 'PTR', 'RB1', '2020'),
('LCC', 'PTR', 'RB1', '2021'),
('LCC', 'STJ', 'RP', '2020'),
('LCC', 'STJ', 'RP', '2021'),
('LCC', 'STJ', 'RQ', '2020'),
('LCC', 'STJ', 'RQ', '2021'),
('LCC', 'STJ', 'RR', '2020'),
('LCC', 'STJ', 'RR', '2021'),
('LCC', 'STJ', 'RS', '2020'),
('LCC', 'STJ', 'RS', '2021'),
('LCC', 'STW', 'RT', '2020'),
('LCC', 'STW', 'RT', '2021'),
('LCC', 'STW', 'RU', '2020'),
('LCC', 'STW', 'RU', '2021'),
('LCC', 'STW', 'RW', '2020'),
('LCC', 'STW', 'RW', '2021'),
('LCC', 'STW', 'RX', '2020'),
('LCC', 'STW', 'RX', '2021'),
('LCN', 'RD', 'SCC', '2021'),
('LCN', 'RE', 'SCC', '2021'),
('LDC', 'BLY', 'RA', '2020'),
('LDC', 'BLY', 'RA', '2021'),
('LDC', 'BLY', 'RB', '2020'),
('LDC', 'BLY', 'RB', '2021'),
('LDC', 'BLY', 'RB1', '2020'),
('LDC', 'BLY', 'RB1', '2021'),
('LDC', 'BLY', 'RC', '2020'),
('LDC', 'BLY', 'RC', '2021'),
('LDC', 'CHD', 'RE', '2020'),
('LDC', 'CHD', 'RE', '2021'),
('LDC', 'CHD', 'RF', '2020'),
('LDC', 'CHD', 'RF', '2021'),
('LDC', 'CHD', 'RG', '2020'),
('LDC', 'CHD', 'RG', '2021'),
('LDC', 'CHD', 'RG1', '2020'),
('LDC', 'CHD', 'RG1', '2021'),
('LDC', 'CHD', 'RH', '2020'),
('LDC', 'CHD', 'RH', '2021'),
('LDC', 'CHD', 'RK', '2020'),
('LDC', 'CHD', 'RK', '2021'),
('LDC', 'CHD', 'RL', '2020'),
('LDC', 'CHD', 'RL', '2021'),
('LDC', 'CUR', 'RJ', '2020'),
('LDC', 'CUR', 'RJ', '2021'),
('LDC', 'LEO', 'RM1', '2020'),
('LDC', 'LEO', 'RM1', '2021'),
('LDC', 'LEO', 'RM2', '2020'),
('LDC', 'LEO', 'RM2', '2021'),
('LDC', 'LEO', 'RN1', '2020'),
('LDC', 'LEO', 'RN1', '2021'),
('LDC', 'LEO', 'RN2', '2020'),
('LDC', 'LEO', 'RN2', '2021'),
('LDC', 'STJ', 'RP', '2020'),
('LDC', 'STJ', 'RP', '2021'),
('LDC', 'STJ', 'RQ', '2020'),
('LDC', 'STJ', 'RQ', '2021'),
('LDC', 'STJ', 'RR', '2020'),
('LDC', 'STJ', 'RR', '2021'),
('LDC', 'STJ', 'RS', '2020'),
('LDC', 'STJ', 'RS', '2021'),
('LDC', 'STW', 'RD', '2020'),
('LDC', 'STW', 'RD', '2021'),
('LDC', 'STW', 'RT', '2020'),
('LDC', 'STW', 'RT', '2021'),
('LDC', 'STW', 'RU', '2020'),
('LDC', 'STW', 'RU', '2021'),
('LDC', 'STW', 'RW', '2020'),
('LDC', 'STW', 'RW', '2021'),
('LDC', 'STW', 'RX', '2020'),
('LDC', 'STW', 'RX', '2021'),
('RA', 'BLY', 'LCC', '2019'),
('RA', 'BLY', 'LCC', '2021'),
('RA', 'BLY', 'LDC', '2019'),
('RA', 'BLY', 'LDC', '2021'),
('RA', 'LRN', 'SCC', '2016'),
('RA', 'LRN', 'SCC', '2021'),
('RB', 'BLY', 'LCC', '2019'),
('RB', 'BLY', 'LCC', '2021'),
('RB', 'BLY', 'LDC', '2019'),
('RB', 'BLY', 'LDC', '2021'),
('RB', 'LRN', 'SCC', '2016'),
('RB', 'LRN', 'SCC', '2021'),
('RB1', 'BLY', 'LDC', '2019'),
('RB1', 'BLY', 'LDC', '2021'),
('RB1', 'LCS', 'SCC', '2016'),
('RB1', 'LCS', 'SCC', '2021'),
('RB1', 'PTR', 'LCC', '2019'),
('RB1', 'PTR', 'LCC', '2021'),
('RC', 'BLY', 'LCC', '2019'),
('RC', 'BLY', 'LCC', '2021'),
('RC', 'BLY', 'LDC', '2019'),
('RC', 'BLY', 'LDC', '2021'),
('RC', 'LRN', 'SCC', '2016'),
('RC', 'LRN', 'SCC', '2021'),
('RD', 'BOW', 'LCC', '2019'),
('RD', 'BOW', 'LCC', '2021'),
('RD', 'LRN', 'SCC', '2016'),
('RD', 'LRN', 'SCC', '2021'),
('RD', 'STW', 'LDC', '2019'),
('RD', 'STW', 'LDC', '2021'),
('RE', 'CHD', 'LCC', '2019'),
('RE', 'CHD', 'LCC', '2021'),
('RE', 'CHD', 'LDC', '2019'),
('RE', 'CHD', 'LDC', '2021'),
('RE', 'LCN', 'SCC', '2021'),
('RE', 'LRN', 'SCC', '2016'),
('RF', 'CHD', 'LCC', '2019'),
('RF', 'CHD', 'LCC', '2021'),
('RF', 'CHD', 'LDC', '2019'),
('RF', 'CHD', 'LDC', '2021'),
('RF', 'LCN', 'SCC', '2021'),
('RF', 'LRN', 'SCC', '2016'),
('RG', 'CHD', 'LCC', '2019'),
('RG', 'CHD', 'LCC', '2021'),
('RG', 'CHD', 'LDC', '2019'),
('RG', 'CHD', 'LDC', '2021'),
('RG', 'LCN', 'SCC', '2016'),
('RG', 'LCN', 'SCC', '2021'),
('RG1', 'CHD', 'LDC', '2019'),
('RG1', 'CHD', 'LDC', '2021'),
('RG1', 'GRK', 'LCC', '2019'),
('RG1', 'GRK', 'LCC', '2021'),
('RG1', 'LCS', 'SCC', '2016'),
('RG1', 'LCS', 'SCC', '2021'),
('RH', 'CHD', 'LCC', '2019'),
('RH', 'CHD', 'LDC', '2019'),
('RH', 'CHD', 'LDC', '2021'),
('RH', 'LCN', 'SCC', '2016'),
('RH', 'LCN', 'SCC', '2021'),
('RJ', 'CUR', 'LCC', '2019'),
('RJ', 'CUR', 'LCC', '2021'),
('RJ', 'CUR', 'LDC', '2019'),
('RJ', 'CUR', 'LDC', '2021'),
('RJ', 'LCN', 'SCC', '2016'),
('RJ', 'LCN', 'SCC', '2021'),
('RK', 'CHD', 'LCC', '2019'),
('RK', 'CHD', 'LCC', '2021'),
('RK', 'CHD', 'LDC', '2019'),
('RK', 'CHD', 'LDC', '2021'),
('RK', 'LCN', 'SCC', '2016'),
('RK', 'LCN', 'SCC', '2021'),
('RL', 'CHD', 'LCC', '2019'),
('RL', 'CHD', 'LDC', '2019'),
('RL', 'CHD', 'LDC', '2021'),
('RL', 'LCS', 'SCC', '2016'),
('RL', 'LCS', 'SCC', '2021'),
('RM1', 'LCS', 'SCC', '2016'),
('RM1', 'LCS', 'SCC', '2021'),
('RM1', 'LEO', 'LCC', '2019'),
('RM1', 'LEO', 'LCC', '2021'),
('RM1', 'LEO', 'LDC', '2019'),
('RM1', 'LEO', 'LDC', '2021'),
('RM2', 'LCS', 'SCC', '2016'),
('RM2', 'LCS', 'SCC', '2021'),
('RM2', 'LEO', 'LCC', '2019'),
('RM2', 'LEO', 'LCC', '2021'),
('RM2', 'LEO', 'LDC', '2019'),
('RM2', 'LEO', 'LDC', '2021'),
('RN1', 'LCS', 'SCC', '2016'),
('RN1', 'LCS', 'SCC', '2021'),
('RN1', 'LEO', 'LCC', '2019'),
('RN1', 'LEO', 'LCC', '2021'),
('RN1', 'LEO', 'LDC', '2019'),
('RN1', 'LEO', 'LDC', '2021'),
('RN2', 'LCS', 'SCC', '2016'),
('RN2', 'LCS', 'SCC', '2021'),
('RN2', 'LEO', 'LCC', '2019'),
('RN2', 'LEO', 'LCC', '2021'),
('RN2', 'LEO', 'LDC', '2019'),
('RN2', 'LEO', 'LDC', '2021'),
('RP', 'LCS', 'SCC', '2016'),
('RP', 'LCS', 'SCC', '2021'),
('RP', 'STJ', 'LCC', '2019'),
('RP', 'STJ', 'LCC', '2021'),
('RP', 'STJ', 'LDC', '2019'),
('RP', 'STJ', 'LDC', '2021'),
('RQ', 'LCS', 'SCC', '2016'),
('RQ', 'LCS', 'SCC', '2021'),
('RQ', 'STJ', 'LCC', '2019'),
('RQ', 'STJ', 'LCC', '2021'),
('RQ', 'STJ', 'LDC', '2019'),
('RQ', 'STJ', 'LDC', '2021'),
('RR', 'LCS', 'SCC', '2016'),
('RR', 'LCS', 'SCC', '2021'),
('RR', 'STJ', 'LCC', '2019'),
('RR', 'STJ', 'LCC', '2021'),
('RR', 'STJ', 'LDC', '2019'),
('RR', 'STJ', 'LDC', '2021'),
('RS', 'LCN', 'SCC', '2021'),
('RS', 'LCS', 'SCC', '2016'),
('RS', 'STJ', 'LCC', '2019'),
('RS', 'STJ', 'LCC', '2021'),
('RS', 'STJ', 'LDC', '2019'),
('RS', 'STJ', 'LDC', '2021'),
('RT', 'LCN', 'SCC', '2021'),
('RT', 'LCS', 'SCC', '2016'),
('RT', 'STW', 'LCC', '2019'),
('RT', 'STW', 'LCC', '2021'),
('RT', 'STW', 'LDC', '2019'),
('RT', 'STW', 'LDC', '2021'),
('RU', 'LCN', 'SCC', '2021'),
('RU', 'LCS', 'SCC', '2016'),
('RU', 'STW', 'LCC', '2019'),
('RU', 'STW', 'LCC', '2021'),
('RU', 'STW', 'LDC', '2019'),
('RU', 'STW', 'LDC', '2021'),
('RW', 'LCN', 'SCC', ''),
('RW', 'LCN', 'SCC', '2021'),
('RW', 'LCS', 'SCC', '2016'),
('RW', 'STW', 'LCC', '2019'),
('RW', 'STW', 'LCC', '2021'),
('RW', 'STW', 'LDC', '2019'),
('RW', 'STW', 'LDC', '2021'),
('RX', 'LCN', 'SCC', '2021'),
('RX', 'LCS', 'SCC', '2016'),
('RX', 'STW', 'LCC', '2019'),
('RX', 'STW', 'LCC', '2021'),
('RX', 'STW', 'LDC', '2019'),
('RX', 'STW', 'LDC', '2021'),
('SCC', 'LCN', 'RG', '2020'),
('SCC', 'LCN', 'RH', '2020'),
('SCC', 'LCN', 'RJ', '2020'),
('SCC', 'LCN', 'RK', '2020'),
('SCC', 'LCS', 'RB1', '2020'),
('SCC', 'LCS', 'RB1', '2021'),
('SCC', 'LCS', 'RG1', '2020'),
('SCC', 'LCS', 'RG1', '2021'),
('SCC', 'LCS', 'RL', '2020'),
('SCC', 'LCS', 'RL', '2021'),
('SCC', 'LCS', 'RM1', '2020'),
('SCC', 'LCS', 'RM1', '2021'),
('SCC', 'LCS', 'RM2', '2020'),
('SCC', 'LCS', 'RM2', '2021'),
('SCC', 'LCS', 'RN1', '2020'),
('SCC', 'LCS', 'RN1', '2021'),
('SCC', 'LCS', 'RN2', '2020'),
('SCC', 'LCS', 'RN2', '2021'),
('SCC', 'LCS', 'RP', '2020'),
('SCC', 'LCS', 'RP', '2021'),
('SCC', 'LCS', 'RQ', '2020'),
('SCC', 'LCS', 'RQ', '2021'),
('SCC', 'LCS', 'RR', '2020'),
('SCC', 'LCS', 'RR', '2021'),
('SCC', 'LCS', 'RS', '2020'),
('SCC', 'LCS', 'RS', '2021'),
('SCC', 'LCS', 'RT', '2020'),
('SCC', 'LCS', 'RT', '2021'),
('SCC', 'LCS', 'RU', '2020'),
('SCC', 'LCS', 'RU', '2021'),
('SCC', 'LCS', 'RW', '2020'),
('SCC', 'LCS', 'RW', '2021'),
('SCC', 'LCS', 'RX', '2020'),
('SCC', 'LCS', 'RX', '2021'),
('SCC', 'LRN', 'RA', '2020'),
('SCC', 'LRN', 'RA', '2021'),
('SCC', 'LRN', 'RB', '2020'),
('SCC', 'LRN', 'RB', '2021'),
('SCC', 'LRN', 'RC', '2020'),
('SCC', 'LRN', 'RC', '2021'),
('SCC', 'LRN', 'RD', '2020'),
('SCC', 'LRN', 'RD', '2021'),
('SCC', 'LRN', 'RE', '2020'),
('SCC', 'LRN', 'RE', '2021'),
('SCC', 'LRN', 'RF', '2020'),
('SCC', 'LRN', 'RF', '2021');

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
