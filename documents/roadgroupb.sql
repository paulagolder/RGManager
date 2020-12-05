-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 06, 2020 at 10:24 AM
-- Server version: 10.3.26-MariaDB
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

--
-- Dumping data for table `roadgroup`
--

INSERT INTO `roadgroup` (`roadgroupid`, `wardid`, `subwardid`, `name`, `households`, `electors`, `distance`, `priority`, `prioritygroup`, `divisionid`, `kml`, `minlat`, `maxlat`, `minlong`, `maxlong`, `midlat`, `midlong`, `note`) VALUES
('BLY_C1', 'BLY', 'BLY_C', 'Abbotsford Rd', 219, 365, NULL, 'M', '80', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_C2', 'BLY', 'BLY_C', 'Baskeyfield Cl', 130, 249, NULL, 'H', '56', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_C3', 'BLY', 'BLY_C', 'Haymoor', 199, 374, NULL, 'M', '106', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_E1', 'BLY', 'BLY_E', 'Birchwood Rd', 153, 314, 1.19, 'L', '162', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_E2', 'BLY', 'BLY_E', 'Curlew Cl', 117, 224, 1.46, 'M', '119', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_E3', 'BLY', 'BLY_E', 'Gable Croft', 164, 337, 1.93, 'L', '182', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_E4', 'BLY', 'BLY_E', 'Hartslade', 136, 278, 1.26, 'L', '158', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_E5', 'BLY', 'BLY_E', 'Bracken Cl', 88, 174, 0.78, 'L', '179', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_N1', 'BLY', 'BLY_N', 'Cornfield', 223, 339, NULL, 'H', '18', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_N2', 'BLY', 'BLY_N', 'Yew Tree', 149, 270, NULL, 'M', '122', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_W1', 'BLY', 'BLY_W', 'Broadlands', 237, 484, NULL, 'L', '185', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C1', 'CHD', 'CHD_C', 'Oakenfield', 209, 379, NULL, 'L', '181', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C2', 'CHD', 'CHD_C', 'Swallow Croft', 166, 273, NULL, 'M', '102', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C3', 'CHD', 'CHD_C', 'Leasowe', 124, 225, NULL, 'L', '176', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C4', 'CHD', 'CHD_C', 'Dimbles Lane', 131, 213, NULL, 'L', '163', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_N1', 'CHD', 'CHD_N', 'Garrick Road', 154, 328, 1.22, 'H', '28.5', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_N2', 'CHD', 'CHD_N', 'Grange Lane', 162, 295, NULL, 'H', '35', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_N3', 'CHD', 'CHD_N', 'Pauls Walk', 269, 472, NULL, 'L', '175', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_N4', 'CHD', 'CHD_N', 'Windwill Lane', 201, 377, NULL, 'L', '173', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_S1', 'CHD', 'CHD_S', 'Charters', 94, 193, NULL, 'L', '184', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_S2', 'CHD', 'CHD_S', 'Gaia Lane', 107, 206, NULL, 'M', '136', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_S3', 'CHD', 'CHD_S', 'Anson Ave', 119, 202, NULL, 'M', '133', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_S4', 'CHD', 'CHD_S', 'Beacon St', 116, 203, NULL, 'M', '111', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_C1', 'CUR', 'CUR_C', 'Curborough', 171, 347, NULL, 'M', '88', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_C2', 'CUR', 'CUR_C', 'Meadowbrook', 194, 353, NULL, 'H', '67', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_C3', 'CUR', 'CUR_C', 'Fallowfield', 118, 176, NULL, 'L', '160', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_E1', 'CUR', 'CUR_E', 'Giles', 132, 237, NULL, 'M', '116', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_E2', 'CUR', 'CUR_E', 'Field', 170, 330, NULL, 'H', '66', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_E3', 'CUR', 'CUR_E', 'David Garrick Gardens', 153, 226, NULL, 'M', '134', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_E4', 'CUR', 'CUR_E', 'Dimbles Lane', 158, 281, NULL, 'M', '128.5', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '\"+chadsmead?\"'),
('CUR_N1', 'CUR', 'CUR_N', 'Norwich', 149, 306, NULL, 'M', '95', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_N2', 'CUR', 'CUR_N', 'Lincoln', 181, 322, 0.71, 'H', '21', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_N3', 'CUR', 'CUR_N', 'Saxon Gate', 181, 322, 0.71, '?', '?', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_S1', 'CUR', 'CUR_S', 'Leyfields', 112, 205, 8.86, 'M', '141', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_S2', 'CUR', 'CUR_S', 'Ponesfield', 174, 363, 1.26, 'L', '183', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_S3', 'CUR', 'CUR_S', 'Purcell Ave', 159, 267, 1.25, 'L', '147', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C1', 'LEO', 'LEO_C', 'Walsall Rd', 226, 444, NULL, 'M', '98', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C2', 'LEO', 'LEO_C', 'Christchurch Ln', 249, 459, NULL, 'M', '76', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C3', 'LEO', 'LEO_C', 'Walnut Walk', 181, 368, NULL, 'M', '101', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C4', 'LEO', 'LEO_C', 'Whithouse Dr', 163, 296, NULL, 'H', '9', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C5', 'LEO', 'LEO_C', 'Blakeman Way', 132, 264, NULL, 'H', '20', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C6', 'LEO', 'LEO_C', 'St Foy Ave', 126, 204, NULL, 'H', '1', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C7', 'LEO', 'LEO_C', 'Deykin Rd (part) ', 176, 311, NULL, 'H', '3', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C8', 'LEO', 'LEO_C', 'Sandfield Meadow', 188, 342, NULL, 'H', '8', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C9', 'LEO', 'LEO_C', 'Lawrence Way', 103, 231, NULL, 'M', '91', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_N1', 'LEO', 'LEO_N', 'Beacon St', 168, 287, NULL, 'M', '82', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_N2', 'LEO', 'LEO_N', 'Harrington Walk', 212, 393, NULL, 'M', '93', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_N3', 'LEO', 'LEO_N', 'Parkside Ct', 106, 159, NULL, 'M', '84', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_N4', 'LEO', 'LEO_N', 'Mary Slater', 90, 172, 1.75, 'H', '69', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_N5', 'LEO', 'LEO_N', 'Ferndale Rd', 201, 380, NULL, 'M', '110', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_W1', 'LEO', 'LEO_W', 'Bham Rd (part)', 197, 315, NULL, 'M', '74', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_W2', 'LEO', 'LEO_W', 'Swan Rd', 186, 261, NULL, 'H', '6', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_W3', 'LEO', 'LEO_W', 'Lower Sandford St', 237, 310, NULL, 'M', '105', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C1', 'STJ', 'STJ_C', 'Scott Close', 155, 274, NULL, 'M', '124', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C2', 'STJ', 'STJ_C', 'Chapel Lane', 126, 226, NULL, 'M', '114', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C3', 'STJ', 'STJ_C', 'Dovehouse Fields', 102, 196, NULL, 'M', '100', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C4', 'STJ', 'STJ_C', 'Trafalgar Way', 163, 291, NULL, 'H', '4', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C5', 'STJ', 'STJ_C', 'Shortbutts Lane', 183, 352, NULL, 'M', '85', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C6', 'STJ', 'STJ_C', 'Bham Rd', 121, 213, NULL, 'H', '7', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E1', 'STJ', 'STJ_E', 'Upper St John St', 133, 216, NULL, 'H', '40', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E2', 'STJ', 'STJ_E', 'Cherry Orchard', 210, 395, NULL, 'H', '5', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E3', 'STJ', 'STJ_E', 'Beech Gds', 202, 388, NULL, 'M', '109', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E4', 'STJ', 'STJ_E', 'Tamworth Road', 105, 183, NULL, 'M', '77', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E5', 'STJ', 'STJ_E', 'Borrowcop', 161, 349, NULL, 'L', '167', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E6', 'STJ', 'STJ_E', 'Wentworth Dr', 144, 302, NULL, 'L', '170', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E7', 'STJ', 'STJ_E', 'Manor Rise', 107, 228, NULL, 'L', '146', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E8', 'STJ', 'STJ_E', 'Longbridge', 148, 305, NULL, 'L', '166', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_W1', 'STJ', 'STJ_W', 'Chesterfield Rd', 164, 323, NULL, 'H', '68', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_W2', 'STJ', 'STJ_W', 'Agincourt', 207, 389, NULL, 'H', '11', 'LCS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_C1', 'STW', 'STW_C', 'Stowe St', 140, 209, NULL, 'M', '132', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_C2', 'STW', 'STW_C', 'St Michael Road', 149, 263, NULL, 'M', '94', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_C3', 'STW', 'STW_C', 'Wissage Rd', 100, 292, NULL, 'H', '64.5', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '\"+Lichfield City North\"'),
('STW_E1', 'STW', 'STW_S', 'Lower Trent Valley Road', 189, 352, NULL, 'H', '30.5', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E2', 'STW', 'STW_E', 'Rocklands Cres', 140, 253, NULL, 'M', '127', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E3', 'STW', 'STW_E', 'Valley Lane', 145, 257, NULL, 'M', '103', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E4', 'STW', 'STW_E', 'Hobs Road', 154, 229, NULL, 'H', '52', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E5', 'STW', 'STW_E', 'Scotch Orchard', 138, 271, NULL, 'M', '104', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E6', 'STW', 'STW_E', 'Eastern Ave', 110, 211, NULL, 'H', '59', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_N1', 'STW', 'STW_N', 'Gilbert Road', 205, 352, NULL, 'L', '145', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_N2', 'STW', 'STW_N', 'Chadswell Heights', 141, 261, NULL, 'H', '43', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_N3', 'STW', 'STW_N', 'York Close', 132, 254, NULL, 'M', '75', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_N4', 'STW', 'STW_N', 'Fecknam Way', 168, 313, NULL, 'L', '165', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_S1', 'STW', 'STW_S', 'Maxwell Close', 139, 230, NULL, 'H', '49.5', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '\"+Lichfield Rural North\"'),
('STW_S2', 'STW', 'STW_S', 'Maryvale Court', 121, 205, NULL, 'L', '155', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_S3', 'STW', 'STW_S', 'BORW', 188, 381, NULL, 'M', '79', 'LRN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_W1', 'STW', 'STW_W', 'Close', 114, 177, NULL, 'H', '32', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_W2', 'STW', 'STW_W', 'Bird St', 244, 338, NULL, 'H', '10', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_W3', 'STW', 'STW_W', 'Wade Street', 130, 166, NULL, 'H', '12', 'LCN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_A1', 'LEO', 'LEO_A', 'Hallam Park', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_A1', 'LEO', 'LEO_A', 'Hallam park', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_X1', 'LEO', 'LEO_X', 'Walsall Rd extension', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_X1', 'CHD', 'CHD_X', 'Outside City', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C5', 'CHD', 'CHD_C', 'Weston Rd', 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C5', 'CHD', 'CHD_C', 'Weston Road', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
