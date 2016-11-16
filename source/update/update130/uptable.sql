CREATE TABLE `jieqi_system_friends` (
  `friendsid` int(11) unsigned NOT NULL auto_increment,
  `adddate` int(11) NOT NULL default '0',
  `myid` int(11) unsigned NOT NULL default '0',
  `myname` varchar(30) binary NOT NULL default '',
  `yourid` int(11) unsigned NOT NULL default '0',
  `yourname` varchar(30) binary NOT NULL default '',
  `teamid` int(11) unsigned NOT NULL default '0',
  `team` varchar(50) NOT NULL default '',
  `fset` text NOT NULL,
  `state` tinyint(1) NOT NULL default '0',
  `flag` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`friendsid`),
  UNIQUE KEY `myid` (`myid`,`yourid`),
  KEY `teamid` (`teamid`)
) TYPE=MyISAM;

CREATE TABLE `jieqi_system_honors` (
  `honorid` smallint(5) unsigned NOT NULL auto_increment,
  `caption` varchar(50) NOT NULL default '',
  `minscore` int(11) NOT NULL default '0',
  `maxscore` int(11) NOT NULL default '0',
  `setting` text NOT NULL,
  `honortype` tinyint(1) NOT NULL default '0',
  PRIMARY KEY `honorid`  (`honorid`),
  KEY `minscore` (`minscore`)
) TYPE=MyISAM;

CREATE TABLE `jieqi_system_right` (
  `rid` int(11) unsigned NOT NULL auto_increment,
  `modname` varchar(50) NOT NULL default '',
  `rname` varchar(50) NOT NULL default '',
  `rtitle` varchar(50) NOT NULL default '',
  `rdescription` text NOT NULL,
  `rhonors` text NOT NULL,
  PRIMARY KEY `rid` (`rid`),
  UNIQUE KEY `modname` (`modname`,`rname`)
) TYPE=MyISAM;

CREATE TABLE `jieqi_article_attachs` (
  `attachid` int(11) unsigned NOT NULL auto_increment,
  `articleid` int(11) unsigned NOT NULL default '0',
  `chapterid` int(11) unsigned NOT NULL default '0',
  `name` varchar(80) NOT NULL default '',
  `class` varchar(30) NOT NULL default '',
  `postfix` varchar(30) NOT NULL default '',
  `size` int(11) unsigned NOT NULL default '0',
  `hits` int(11) unsigned NOT NULL default '0',
  `needexp` int(11) unsigned NOT NULL default '0',
  `uptime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`attachid`),
  KEY `articleid` (`articleid`),
  KEY `chapterid` (`chapterid`)
) TYPE=MyISAM;

CREATE TABLE jieqi_article_searchcache (
   cacheid         int(11) unsigned NOT NULL auto_increment,
   searchtime      int(11) unsigned NOT NULL default '0',
   hashid          char(32) NOT NULL default '0',
   keywords        varchar(60) BINARY NOT NULL default '',
   searchtype      tinyint(1) NOT NULL default '0',
   results         int(11) unsigned NOT NULL default '0',
   aids            text NOT NULL,
   PRIMARY KEY  (cacheid),
   UNIQUE KEY (hashid)
) TYPE=MyISAM;

DROP TABLE `jieqi_system_transfer`;
DROP TABLE `jieqi_system_paylog`;
DROP TABLE `jieqi_system_balance`;