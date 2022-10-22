-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 06, 2020 at 11:17 AM
-- Server version: 10.3.26-MariaDB-1:10.3.26+maria~bionic-log
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
-- Dumping data for table `streetb`
--

INSERT INTO `streetb` (`streetid`, `name`, `qualifier`, `part`, `pd`, `households`, `electors`, `wardid`, `subwardid`, `roadgroupid`, `note`, `latitude`, `longitude`) VALUES
(520, 'Windmill Close', '', '', 'RG', 38, 68, 'CHD', 'CHD_N', 'CHD_C5', '', NULL, NULL),
(513, 'Wheel Lane', NULL, 'RL', 'RL', 19, 38, 'CHD', 'CHD_C', 'LEO_N5', 'Odds from Beacon St 19 to 61 by the former Pub', '52.689926133543416', '-1.8398022651672366'),
(508, 'Weston Road', NULL, 'RE', 'RE', 47, 0, 'CUR', 'CUR_E', 'CHD_C3', 'Odds from shops on wheel lane 149 to 59 corner of Dimbles Lane', NULL, NULL),
(509, 'Weston Road', NULL, 'RG', 'RG', 51, NULL, 'CUR', 'CUR_E', 'CHD_C5', 'Evens from 138 corner of Grange Lane to  42 corner of Dimbles Lane', NULL, NULL),
(508, 'Weston Road', NULL, 'RE', 'RE', 47, 0, 'CUR', 'CUR_E', 'CHD_C3', 'Odds from shops on wheel lane 149 to 59 corner of Dimbles Lane', NULL, NULL),
(508, 'Weston Road', NULL, 'RE', 'RE', 47, 0, 'CUR', 'CUR_E', 'CHD_C3', 'Odds from shops on wheel lane 149 to 59 corner of Dimbles Lane', NULL, NULL),
(607, 'Stafford Road', 'keep', 'XW', 'RG', 3, NULL, 'CHD', 'CHD_N', 'CHD_X1', 'Out past Police Center a few houses in Chadsmead up to the roundabout.', '52.694990415795154', '-1.8510675430297854'),
(618, 'Ryknild Street', NULL, 'RAb', 'RA', 3, NULL, 'BLY', 'BLY_E', 'BLY_E3', 'Evens fron 28/30/32 by Darnford Lane', '52.67849812811742', '-1.8063980340957644'),
(617, 'Ryknild Street', NULL, 'RAc', 'RA', 3, NULL, 'BLY', 'BLY_E', 'BLY_E1', 'Evens  60/62/64 by Birchwood Road', '52.68119426484247', '-1.8030982495880068'),
(609, 'Grange Lane', NULL, 'XW', 'RG', 7, NULL, 'CHD', 'CHD_X', 'CHD_X1', 'From Eastern Avenue up to Grange Lea Farm', '52.69609059055872', '-1.8439435958862307'),
(555, 'Gaia Lane', 'keep', 'RK', 'RK', 47, 111, 'STW', 'STW_W', 'CHD_S2', 'Lower end Odds from 39 to 97 Curborough Road, evens 60 to 116', NULL, NULL),
(555, 'Gaia Lane', 'keep', 'RK', 'RK', 47, 111, 'STW', 'STW_W', 'CHD_S2', 'Lower end Odds from 39 to 97 Curborough Road, evens 60 to 116', NULL, NULL),
(168, 'Fosseway Lane', NULL, 'RM2', 'RM2', 1, 4, 'STJ', 'STJ_W', 'STJ_W2', 'Fossway Farm  Vets dont deliver', '52.66951218928502', '-1.841239929199219'),
(147, 'Dimbles Lane', NULL, 'RG', 'RG', 6, 26, 'CUR', 'CUR_E', 'CHD_C5', 'Odds from 103  oppostite Health center  to 81 corner of Weston Road', '52.69372212439408', '-1.8319112062454226'),
(149, 'Dimbles Lane', NULL, 'RK', 'RK', 15, 21, 'CUR', 'CUR_E', 'CHD_S1', 'evens from the courner of Beechcrot no 2   to 28 by the Church', NULL, NULL),
(147, 'Dimbles Lane', NULL, 'RG', 'RG', 6, 26, 'CUR', 'CUR_E', 'CHD_C5', 'Odds from 103  oppostite Health center  to 81 corner of Weston Road', '52.69372212439408', '-1.8319112062454226'),
(149, 'Dimbles Lane', NULL, 'RK', 'RK', 15, 21, 'CUR', 'CUR_E', 'CHD_S1', 'evens from the courner of Beechcrot no 2   to 28 by the Church', NULL, NULL),
(552, 'Curborough Road', NULL, 'RJ', 'RJ', 50, NULL, 'CUR', 'CUR_C', 'CUR_C2', 'evens from 82  corner of Ponesfield to 196 Eastern avenue end', '52.69690985136909', '-1.827807426452637'),
(553, 'Curborough Road', 'keep', 'RK', 'RK', 23, 28, 'CUR', 'CUR_C', 'CHD_S2', 'Odds from corner of Gaia Lane to corner of Dimble Hill', '52.69050717729302', '-1.8256375193595888'),
(67, 'Borrowcop Lane', NULL, 'RQ', 'RQ', 2, 3, 'STJ', 'STJ_E', 'STJ_E5', 'tow houses on corner of St Johns Street', '52.674172253484855', '-1.822759509086609');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
