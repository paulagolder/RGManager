-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 28, 2020 at 02:54 PM
-- Server version: 10.3.25-MariaDB-1:10.3.25+maria~bionic-log
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
-- Table structure for table `seat`
--

CREATE TABLE IF NOT EXISTS `seat` (
  `districtid` varchar(20) NOT NULL,
  `seatid` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` int(9) DEFAULT NULL,
  `electorate` int(11) DEFAULT NULL,
  `seats` int(11) NOT NULL,
  UNIQUE KEY `index` (`districtid`,`seatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `seat`
--

INSERT INTO `seat` (`districtid`, `seatid`, `name`, `date`, `electorate`, `seats`) VALUES
('LCC', 'BLY', 'Boley Park', 20190503, 2953, 3),
('LCC', 'BOW', 'Burton Old Road', 20190503, 894, 1),
('LCC', 'CHD', 'Chadsmead', 20190503, 3059, 4),
('LCC', 'CUR', 'Curborough', 20190503, 3350, 3),
('LCC', 'GRK', 'Garrick Road', 20190503, 308, 1),
('LCC', 'LEO', 'Leomansley', 20190503, 5259, 5),
('LCC', 'PTR', 'Pentire Road', 20190503, 496, 1),
('LCC', 'STJ', 'St Johns', 20190503, 4603, 6),
('LCC', 'STW', 'Stowe', 20190503, 4101, 4);

-- --------------------------------------------------------

--
-- Table structure for table `seattoroadgroup`
--

CREATE TABLE IF NOT EXISTS `seattoroadgroup` (
  `roadgroupid` varchar(10) NOT NULL,
  `seatid` varchar(10) NOT NULL,
  `districtid` varchar(10) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  UNIQUE KEY `roadgroupid` (`roadgroupid`,`seatid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `seattoroadgroup`
--

INSERT INTO `seattoroadgroup` (`roadgroupid`, `seatid`, `districtid`, `date`) VALUES
('BLY_C1', 'BLY', 'LCC', 2019000),
('BLY_C2', 'BLY', 'LCC', 2019000),
('BLY_C3', 'BLY', 'LCC', 2019000),
('BLY_E1', 'BLY', 'LCC', 2019000),
('BLY_E2', 'BLY', 'LCC', 2019000),
('BLY_E3', 'BLY', 'LCC', 2019000),
('BLY_E4', 'BLY', 'LCC', 2019000),
('BLY_E5', 'BLY', 'LCC', 2019000),
('BLY_N1', 'BLY', 'LCC', 2019000),
('BLY_N2', 'BLY', 'LCC', 2019000),
('BLY_W1', 'BLY', 'LCC', 2019000),
('CHD_C1', 'CHD', 'LCC', 2019000),
('CHD_C2', 'CHD', 'LCC', 2019000),
('CHD_C3', 'CHD', 'LCC', 2019000),
('CHD_C4', 'CHD', 'LCC', 2019000),
('CHD_N1', 'GRK', 'LCC', 2019000),
('CHD_N2', 'CHD', 'LCC', 2019000),
('CHD_N3', 'CHD', 'LCC', 2019000),
('CHD_N4', 'CHD', 'LCC', 2019000),
('CHD_S1', 'CHD', 'LCC', 2019000),
('CHD_S2', 'CHD', 'LCC', 2019000),
('CHD_S3', 'CHD', 'LCC', 2019000),
('CHD_S4', 'CHD', 'LCC', 2019000),
('CUR_C1', 'CUR', 'LCC', 2019000),
('CUR_C2', 'CUR', 'LCC', 2019000),
('CUR_C3', 'CUR', 'LCC', 2019000),
('CUR_E1', 'CUR', 'LCC', 2019000),
('CUR_E2', 'CUR', 'LCC', 2019000),
('CUR_E3', 'CUR', 'LCC', 2019000),
('CUR_E4', 'CUR', 'LCC', 2019000),
('CUR_N1', 'CUR', 'LCC', 2019000),
('CUR_N2', 'CUR', 'LCC', 2019000),
('CUR_N3', 'CUR', 'LCC', 2019000),
('CUR_S1', 'CUR', 'LCC', 2019000),
('CUR_S2', 'CUR', 'LCC', 2019000),
('CUR_S3', 'CUR', 'LCC', 2019000),
('LEO_C1', 'LEO', 'LCC', 2019000),
('LEO_C2', 'LEO', 'LCC', 2019000),
('LEO_C3', 'LEO', 'LCC', 2019000),
('LEO_C4', 'LEO', 'LCC', 2019000),
('LEO_C5', 'LEO', 'LCC', 2019000),
('LEO_C6', 'LEO', 'LCC', 2019000),
('LEO_C7', 'LEO', 'LCC', 2019000),
('LEO_C8', 'LEO', 'LCC', 2019000),
('LEO_C9', 'LEO', 'LCC', 2019000),
('LEO_N1', 'LEO', 'LCC', 2019000),
('LEO_N2', 'LEO', 'LCC', 2019000),
('LEO_N3', 'LEO', 'LCC', 2019000),
('LEO_N4', 'LEO', 'LCC', 2019000),
('LEO_N5', 'LEO', 'LCC', 2019000),
('LEO_W1', 'LEO', 'LCC', 2019000),
('LEO_W2', 'LEO', 'LCC', 2019000),
('LEO_W3', 'LEO', 'LCC', 2019000),
('STJ_C1', 'STJ', 'LCC', 2019000),
('STJ_C2', 'STJ', 'LCC', 2019000),
('STJ_C3', 'STJ', 'LCC', 2019000),
('STJ_C4', 'STJ', 'LCC', 2019000),
('STJ_C5', 'STJ', 'LCC', 2019000),
('STJ_C6', 'STJ', 'LCC', 2019000),
('STJ_E1', 'STJ', 'LCC', 2019000),
('STJ_E2', 'STJ', 'LCC', 2019000),
('STJ_E3', 'STJ', 'LCC', 2019000),
('STJ_E4', 'STJ', 'LCC', 2019000),
('STJ_E5', 'STJ', 'LCC', 2019000),
('STJ_E6', 'STJ', 'LCC', 2019000),
('STJ_E7', 'STJ', 'LCC', 2019000),
('STJ_E8', 'STJ', 'LCC', 2019000),
('STJ_W1', 'STJ', 'LCC', 2019000),
('STJ_W2', 'STJ', 'LCC', 2019000),
('STW_C1', 'STW', 'LCC', 2019000),
('STW_C2', 'STW', 'LCC', 2019000),
('STW_C3', 'STW', 'LCC', 2019000),
('STW_E1', 'STW', 'LCC', 2019000),
('STW_E2', 'STW', 'LCC', 2019000),
('STW_E3', 'STW', 'LCC', 2019000),
('STW_E4', 'STW', 'LCC', 2019000),
('STW_E5', 'STW', 'LCC', 2019000),
('STW_E6', 'STW', 'LCC', 2019000),
('STW_N1', 'STW', 'LCC', 2019000),
('STW_N2', 'STW', 'LCC', 2019000),
('STW_N3', 'STW', 'LCC', 2019000),
('STW_N4', 'STW', 'LCC', 2019000),
('STW_S1', 'STW', 'LCC', 2019000),
('STW_S2', 'STW', 'LCC', 2019000),
('STW_S3', 'STW', 'LCC', 2019000),
('STW_W1', 'STW', 'LCC', 2019000),
('STW_W2', 'STW', 'LCC', 2019000),
('STW_W3', 'STW', 'LCC', 2019000),
('CHD_XW', 'CHD', 'LCC', 2019000),
('LEO_A1', 'LEO', 'LCC', 2019000),
('LEO_X1', 'LEO', 'LCC', 2019000);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
