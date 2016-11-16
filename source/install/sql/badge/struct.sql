
-- --------------------------------------------------------

--
-- 表的结构 `jieqi_badge_award`
--

DROP TABLE IF EXISTS `jieqi_badge_award`;
CREATE TABLE `jieqi_badge_award` (
  `awardid` int(11) unsigned NOT NULL auto_increment,
  `addtime` int(11) NOT NULL default '0',
  `fromid` int(11) unsigned NOT NULL default '0',
  `fromname` varchar(30) binary NOT NULL default '',
  `toid` int(11) unsigned NOT NULL default '0',
  `toname` varchar(30) binary NOT NULL default '',
  `badgeid` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`awardid`),
  KEY `toid` (`toid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_badge_badge`
--

DROP TABLE IF EXISTS `jieqi_badge_badge`;
CREATE TABLE `jieqi_badge_badge` (
  `badgeid` int(11) unsigned NOT NULL auto_increment,
  `btypeid` int(11) unsigned NOT NULL default '0',
  `caption` varchar(100) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `linkid` int(11) NOT NULL default '0',
  `imagetype` tinyint(3) NOT NULL default '0',
  `maxnum` int(11) NOT NULL default '0',
  `usenum` int(11) NOT NULL default '0',
  `uptime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`badgeid`),
  UNIQUE KEY `btypeid` (`btypeid`,`linkid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_badge_btype`
--

DROP TABLE IF EXISTS `jieqi_badge_btype`;
CREATE TABLE `jieqi_badge_btype` (
  `btypeid` int(11) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `sysflag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`btypeid`)
) TYPE=MyISAM;
