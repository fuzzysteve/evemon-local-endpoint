This code allows you to store market data from utilities like EVEMon in a database.
For testing purposes, this branch is configured to use sqlite3.
To test on your own hardware:
	Install sqlite3.
	Install php.
	Create the database as outlined below.
	From a command prompt:
		cd to this directory.
		Run `php -S localhost:1337 -t .`
	Configure EVEMon to send data:
		Go to Tools->Options->Market Unified Uploader
		Click Add.
		Change the number to "1337" and give it a name.
		Click Save.
		Click OK

To create the database, first run: `sqlite3 EVE.sdb`
Then Copy and Paste these two blocks.
Type `.exit`

CREATE TABLE `mysales` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `regionid` int(11) DEFAULT NULL,
  `stationid` int(11) DEFAULT NULL,
  `systemid` int(11) DEFAULT NULL,
  `typeid` int(11) DEFAULT NULL,
  `bid` int(11) DEFAULT NULL,
  `price` decimal(11,3) DEFAULT NULL,
  `minvolume` bigint(20) DEFAULT NULL,
  `volremain` bigint(20) DEFAULT NULL,
  `volenter` bigint(20) DEFAULT NULL,
  `issued` INTEGER  DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `rang` varchar(10) DEFAULT NULL,
  UNIQUE (`stationid`,`typeid`,`bid`,`volenter`,`issued`,`duration`,`rang`) ON CONFLICT REPLACE
);

CREATE TABLE `orderhistory` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `typeid` int(11) DEFAULT NULL,
  `regionid` int(11) DEFAULT NULL,
  `historydate` INTEGER DEFAULT NULL,
  `orders` int(11) DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `low` decimal(10,2) DEFAULT NULL,
  `high` decimal(10,2) DEFAULT NULL,
  `average` decimal(10,2) DEFAULT NULL,
  UNIQUE(`typeid`,`regionid`,`historydate`)
);

update db.inc.php to have the correct connection details for your database, then point evemon at upload.php

The only things that can change in an order are the remaining volume (volremain) and price (price).  Stationid is tied to a regionid and systemid, so they don't need to be in the UNIQUE list.  I'm honetsly not sure about (`minvolume`).  Can it change?

Changes from mysql -> sqlite3:
	Replacement of Unique items is done automatically.
	Date and DateTime are replaced with INTERGER unix epoch time.
