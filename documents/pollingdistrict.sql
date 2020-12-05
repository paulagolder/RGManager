-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 14, 2020 at 12:36 PM
-- Server version: 10.3.27-MariaDB
-- PHP Version: 7.3.6

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
-- Table structure for table `pollingdistrict`
--

CREATE TABLE `pollingdistrict` (
  `pollingdistrictid` varchar(10) DEFAULT NULL,
  `electors` int(11) DEFAULT NULL,
  `households` int(11) DEFAULT NULL,
  `kml` varchar(20) DEFAULT NULL,
  `maxlong` float DEFAULT NULL,
  `midlong` float DEFAULT NULL,
  `minlong` float DEFAULT NULL,
  `maxlat` float DEFAULT NULL,
  `midlat` float DEFAULT NULL,
  `minlat` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pollingdistrict`
--

INSERT INTO `pollingdistrict` (`pollingdistrictid`, `electors`, `households`, `kml`, `maxlong`, `midlong`, `minlong`, `maxlat`, `midlat`, `minlat`) VALUES
('RA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RB1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RF', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RG1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RJ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RM1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RM2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RN1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RN2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RQ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('RX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pollingdistrict`
--
ALTER TABLE `pollingdistrict`
  ADD UNIQUE KEY `index` (`pollingdistrictid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
