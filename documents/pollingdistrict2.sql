SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


TRUNCATE TABLE `pollingdistrict`;
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;