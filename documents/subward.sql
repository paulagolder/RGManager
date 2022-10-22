
CREATE TABLE `subward` (
  `subwardid` varchar(20) DEFAULT 'NULL',
  `subward` varchar(20) DEFAULT NULL,
   `wardid` varchar(20) DEFAULT 'NULL',
  `roads` int(11) DEFAULT NULL,
  `roadgroups` int(11) DEFAULT NULL,
  `households` int(11) DEFAULT NULL,
  `electors` int(11) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

CREATE TABLE `roadgroup` (
   `roadgroupid` varchar(20) DEFAULT 'NULL',
  `label` varchar(20) DEFAULT NULL,
    `subwardid` varchar(20) DEFAULT 'NULL',
   `wardid` varchar(20) DEFAULT 'NULL',
  `roads` int(11) DEFAULT NULL,
  `roadgroups` int(11) DEFAULT NULL,
  `households` int(11) DEFAULT NULL,
  `electors` int(11) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
