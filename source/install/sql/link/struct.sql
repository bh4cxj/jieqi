DROP TABLE IF EXISTS `jieqi_link_link`;
CREATE TABLE IF NOT EXISTS `jieqi_link_link` (
  `linkid` smallint(5) unsigned NOT NULL auto_increment,
  `linktype` tinyint(1) unsigned NOT NULL default '0',
  `namecolor` varchar(10) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `url` varchar(250) NOT NULL default '',
  `logo` varchar(250) NOT NULL default '',
  `introduce` text,
  `userid` int(11) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `mastername` varchar(50) NOT NULL default '',
  `mastertell` varchar(250) NOT NULL default '',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  `passed` tinyint(1) unsigned NOT NULL default '0',
  `addtime` int(20) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`linkid`),
  KEY `typeid` (`passed`,`listorder`,`linkid`)
) TYPE=MyISAM;
