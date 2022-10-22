-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 12, 2020 at 04:16 PM
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
-- Table structure for table `pollingdistrict`
--

CREATE TABLE `pollingdistrict` (
  `pdid` varchar(10) DEFAULT NULL,
  `ldcward` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pollingdistrict`
--

INSERT INTO `pollingdistrict` (`pdid`, `ldcward`) VALUES
('RA', 'BLY'),
('RB', 'BLY'),
('RB1', 'BLY'),
('RC', 'BLY'),
('RD', 'STW'),
('RE', 'CHD'),
('RF', 'CHD'),
('RG', 'CHD'),
('RG1', 'CHD'),
('RH', 'CUR'),
('RJ', 'CUR'),
('RK', 'CHD'),
('RL', 'LEO'),
('RM1', 'LEO'),
('RM2', 'LEO'),
('RN1', 'LEO'),
('RN2', 'LEO'),
('RP', 'STJ'),
('RQ', 'STJ'),
('RR', 'STJ'),
('RS', 'STW'),
('RT', 'STW'),
('RW', 'STW'),
('RU', 'STW'),
('RX', 'STW');

-- --------------------------------------------------------

--
-- Table structure for table `pollingstation`
--

CREATE TABLE `pollingstation` (
  `pdid` varchar(10) DEFAULT 'NULL',
  `stationno` varchar(3) DEFAULT NULL,
  `ldcward` varchar(10) DEFAULT 'NULL',
  `address` varchar(97) DEFAULT NULL,
  `midlat` decimal(8,6) DEFAULT NULL,
  `midlong` decimal(7,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pollingstation`
--

INSERT INTO `pollingstation` (`pdid`, `stationno`, `ldcward`, `address`, `midlat`, `midlong`) VALUES
('RA', '39', 'BLY', 'Boley Park Community Hall, 7 Ryknild Street, Boley Park, Lichfield, Staffs, WS14 9XU', '52.677250', '1.802278'),
('RB', '40', 'BLY', 'Boley Park Community Hall, 7 Ryknild Street, Boley Park, Lichfield, Staffs, WS14 9XU', '52.679400', '1.809440'),
('RB1', '41', 'BLY', 'Boley Park Community Hall, 7 Ryknild Street, Boley Park, Lichfield, Staffs, WS14 9XU', '52.678190', '1.814117'),
('RC', '41a', 'BLY', NULL, '52.683134', '1.804440'),
('RD', '42', 'STW', '7th Lichfield Scout Group HQ, Ash Grove, Lichfield, Staffs, WS13 6ET', '52.684994', '1.809096'),
('RE', '43', 'CHD', 'Chadsmead Primary Academy, Infant Block, Friday Acre, Lichfield, Staffs, WS13 7HJ', '52.689442', '1.834416'),
('RF', '44', 'CHD', 'Mobile Polling Station, Land at junction of Collins Hill and Greencroft, Lichfield, WS13 7JG', '52.694592', '1.835747'),
('RG', '45', 'CHD', 'Chadsmead Primary Academy, Infant Block, Friday Acre, Lichfield, Staffs, WS13 7HJ', '52.691185', '1.838751'),
('RG1', '45a', 'CHD', NULL, '52.693409', '1.844244'),
('RH', '46', 'CUR', 'The Willows C.P. School, Anglesey Road, Lichfield, Staffs, WS13 7NU', '52.695711', '1.830339'),
('RJ', '47', 'CUR', 'Charnwood Primary Academy, Purcell Avenue, Lichfield, Staffs, WS13 7PH', '52.697661', '1.824031'),
('RJ', '48', 'CUR', 'Charnwood Primary Academy, Purcell Avenue, Lichfield, Staffs, WS13 7PH', '52.697297', '1.823044'),
('RK', '49', 'CHD', 'SS Peter & Pauls Catholic Primary, Dimbles Hill, Lichfield, Staffs, WS13 7NH', '52.690359', '1.827732'),
('RL', '50', 'LEO', 'Mobile Polling Station, Morrisons Car Park, Beacon Street, Lichfield, WS13 7BG', '52.690080', '1.841969'),
('RM1', '51', 'LEO', 'Martin Heath Hall, Leomansley Room, Christchurch Lane, Lichfield, Staffs, WS13 8AY', '52.680233', '1.842699'),
('RM2', '52', 'LEO', 'Darwin Hall, Heathcot Place, Lichfield, Staffs, WS13 6RQ', '52.675875', '1.842248'),
('RN1', '53', 'LEO', 'Darwin Hall, Heathcot Place, Lichfield, Staffs, WS13 6RQ', '52.675419', '1.836820'),
('RN2', '54', 'LEO', 'Mobile Polling Station, Staffs University West Car Park, Monks Close, Lichfield, Staffs, WS13 6QE', '52.681768', '1.830082'),
('RP', '55', 'STJ', 'St. Michael`s C. of E. School, Cherry Orchard, Lichfield, Staffs, WS14 9AW', '52.679869', '1.820340'),
('RQ', '56', 'STJ', 'Holy Cross Parish Hall, Chapel Lane, Lichfield, Staffs, WS14 9BA', '52.673221', '1.833129'),
('RR', '58', 'STJ', 'Holy Cross, Community Meeting Room, Holy Cross Church, Chapel Lane, Lichfield, WS14 9BA', '52.673468', '1.814590'),
('RS', '59', 'STW', 'Guildroom, Guildhall, Bore Street, Lichfield, Staffs, WS13 7LX', '52.683134', '1.828033'),
('RT', '60', 'STW', 'Life Church Lichfield, Netherstowe, Lichfield, Staffs, WS13 6TS', '52.691939', '1.818752'),
('RW', '60a', 'STW', NULL, '52.686789', '1.818624'),
('RU', '61', 'STW', 'Cruck House, Stowe Street, Lichfield, Staffs, WS13 6BN', '52.687218', '1.823280'),
('RX', '62', 'STW', 'Scotch Orchard C.P. School, Early Years Unit, Scotch Orchard, Lichfield, Staffs, WS13 6DE', '52.688194', '1.812015');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pollingdistrict`
--
ALTER TABLE `pollingdistrict`
  ADD UNIQUE KEY `index` (`pdid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
