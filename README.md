These two tables are required.


CREATE TABLE `mysales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regionid` int(11) DEFAULT NULL,
  `stationid` int(11) DEFAULT NULL,
  `systemid` int(11) DEFAULT NULL,
  `typeid` int(11) DEFAULT NULL,
  `bid` int(11) DEFAULT NULL,
  `price` decimal(11,3) DEFAULT NULL,
  `minvolume` bigint(20) DEFAULT NULL,
  `volremain` bigint(20) DEFAULT NULL,
  `volenter` bigint(20) DEFAULT NULL,
  `issued` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `rang` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mysaleskey` (`stationid`,`typeid`,`bid`,`volenter`,`issued`,`duration`,`rang`)
);

CREATE TABLE `orderhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` int(11) DEFAULT NULL,
  `regionid` int(11) DEFAULT NULL,
  `historydate` date DEFAULT NULL,
  `orders` int(11) DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `low` decimal(10,2) DEFAULT NULL,
  `high` decimal(10,2) DEFAULT NULL,
  `average` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `typeid` (`typeid`,`regionid`,`historydate`)
);



update db.inc.php to have the correct connection details for your database, then point evemon at upload.php

