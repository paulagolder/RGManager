-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 10, 2021 at 11:18 AM
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

--
-- Truncate table before insert `rggroup`
--

TRUNCATE TABLE `rggroup`;
--
-- Dumping data for table `rggroup`
--

INSERT INTO `rggroup` (`rggroupid`, `name`, `kml`, `roads`, `roadgroups`, `households`, `electors`, `latitude`, `longitude`) VALUES
('RG_BLY', 'Boley Park', NULL, NULL, NULL, 1815, NULL, NULL, NULL),
('RG_CHD', 'Chadsmead', NULL, NULL, NULL, 2010, NULL, NULL, NULL),
('RG_CUR', 'Curborough', NULL, NULL, NULL, 1871, NULL, NULL, NULL),
('RG_LEO', 'Leomansley', NULL, NULL, NULL, 2941, NULL, NULL, NULL),
('RG_STJ', 'St Johns', NULL, NULL, NULL, 2431, NULL, NULL, NULL),
('RG_STW', 'Stowe ', NULL, NULL, NULL, 3300, 3086, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
