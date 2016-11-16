INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'system', 'JIEQI_USE_BADGE', '是否启用徽章功能', '0', '', 1, 7, 'a:2:{i:1;s:2:"是";i:0;s:2:"否";}', 31350, '显示控制');


CREATE TABLE `jieqi_badge_award` (
  `awardid` int(10) unsigned NOT NULL auto_increment,
  `addtime` int(11) NOT NULL default '0',
  `fromid` int(11) unsigned NOT NULL default '0',
  `fromname` varchar(30) binary NOT NULL default '',
  `toid` int(11) unsigned NOT NULL default '0',
  `toname` varchar(30) binary NOT NULL default '',
  `badgeid` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`awardid`),
  KEY `toid` (`toid`)
) TYPE=MyISAM;

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

CREATE TABLE `jieqi_badge_btype` (
  `btypeid` int(10) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `sysflag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`btypeid`)
) TYPE=MyISAM;

INSERT INTO `jieqi_badge_btype` (`btypeid`, `title`, `sysflag`) VALUES (1, '等级徽章', 1);
INSERT INTO `jieqi_badge_btype` (`btypeid`, `title`, `sysflag`) VALUES (2, '头衔徽章', 1);