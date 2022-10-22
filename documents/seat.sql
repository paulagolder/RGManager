-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 30, 2020 at 01:55 PM
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

--
-- Truncate table before insert `seat`
--

TRUNCATE TABLE `seat`;
--
-- Dumping data for table `seat`
--

INSERT INTO `seat` (`districtid`, `seatid`, `name`, `date`, `electorate`, `households`, `seats`) VALUES
('LCC', 'BLY', 'Boley Park', 20190503, 2953, NULL, 3),
('LCC', 'BOW', 'Burton Old Road', 20190503, 894, NULL, 1),
('LCC', 'CHD', 'Chadsmead', 20190503, 3059, NULL, 4),
('LCC', 'CUR', 'Curborough', 20190503, 3350, NULL, 3),
('LCC', 'GRK', 'Garrick Road', 20190503, 308, NULL, 1),
('LCC', 'LEO', 'Leomansley', 20190503, 5259, NULL, 5),
('LCC', 'PTR', 'Pentire Road', 20190503, 496, NULL, 1),
('LCC', 'STJ', 'St Johns', 20190503, 4603, NULL, 6),
('LCC', 'STW', 'Stowe', 20190503, 4101, NULL, 4),
('LDC', 'BLY', 'Boley Park', 20190503, 3354, NULL, 2),
('LDC', 'CHD', 'Chadsmead', 20190503, 3397, NULL, 2),
('LDC', 'CUR', 'Curborough', 20190503, 3352, NULL, 2),
('LDC', 'LEO', 'Leomansley', 20190503, 5270, NULL, 3),
('LDC', 'STJ', 'St Johns', 20190503, 4610, NULL, 3),
('LDC', 'STW', 'Stowe', 20190503, 5000, NULL, 3),
('SCC', 'LCN', 'Lichfield City North', 20170504, 10270, NULL, 1),
('SCC', 'LCS', 'Lichfield City South', 20170504, 10453, NULL, 1),
('SCC', 'LRN', 'Lichfield Rural North', 2017, NULL, NULL, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
