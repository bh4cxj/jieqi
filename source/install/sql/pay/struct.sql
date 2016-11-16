-- --------------------------------------------------------

--
-- 表的结构 `jieqi_pay_balance`
--

DROP TABLE IF EXISTS `jieqi_pay_balance`;
CREATE TABLE `jieqi_pay_balance` (
  `balid` int(11) unsigned NOT NULL auto_increment,
  `baltime` int(11) unsigned NOT NULL default '0',
  `fromid` int(11) unsigned NOT NULL default '0',
  `fromname` varchar(30) binary NOT NULL default '',
  `toid` int(11) unsigned NOT NULL default '0',
  `toname` varchar(30) binary NOT NULL default '',
  `baltype` varchar(255) NOT NULL default '',
  `ballog` varchar(255) NOT NULL default '',
  `balegold` int(11) NOT NULL default '0',
  `moneytype` tinyint(3) NOT NULL default '0',
  `balmoney` int(11) NOT NULL default '0',
  `balflag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`balid`),
  KEY `fromid` (`fromid`),
  KEY `fromname` (`fromname`),
  KEY `toid` (`toid`),
  KEY `toname` (`toname`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_pay_paylog`
--

DROP TABLE IF EXISTS `jieqi_pay_paylog`;
CREATE TABLE `jieqi_pay_paylog` (
  `payid` int(11) unsigned NOT NULL auto_increment,
  `siteid` smallint(6) unsigned NOT NULL default '0',
  `buytime` int(11) unsigned NOT NULL default '0',
  `rettime` int(11) unsigned NOT NULL default '0',
  `buyid` int(11) unsigned NOT NULL default '0',
  `buyname` varchar(30) binary NOT NULL default '',
  `buyinfo` varchar(255) NOT NULL default '',
  `moneytype` tinyint(3) NOT NULL default '0',
  `money` int(11) NOT NULL default '0',
  `egoldtype` tinyint(1) NOT NULL default '0',
  `egold` int(11) NOT NULL default '0',
  `paytype` varchar(30) NOT NULL default '',
  `retserialno` varchar(100) NOT NULL default '',
  `retaccount` varchar(100) NOT NULL default '',
  `retinfo` varchar(100) NOT NULL default '',
  `masterid` int(11) unsigned NOT NULL default '0',
  `mastername` varchar(30) binary NOT NULL default '',
  `masterinfo` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  `payflag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`payid`),
  KEY `flag` (`payflag`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_pay_transfer`
--

DROP TABLE IF EXISTS `jieqi_pay_transfer`;
CREATE TABLE `jieqi_pay_transfer` (
  `transid` int(11) unsigned NOT NULL auto_increment,
  `transtime` int(11) unsigned NOT NULL default '0',
  `fromid` int(11) unsigned NOT NULL default '0',
  `fromname` varchar(30) binary NOT NULL default '',
  `toid` int(11) unsigned NOT NULL default '0',
  `toname` varchar(30) binary NOT NULL default '',
  `translog` varchar(255) NOT NULL default '',
  `transegold` int(11) NOT NULL default '0',
  `receiveegold` int(11) NOT NULL default '0',
  `mastertime` int(11) unsigned NOT NULL default '0',
  `masterid` int(11) unsigned NOT NULL default '0',
  `mastername` varchar(30) binary NOT NULL default '',
  `masterlog` varchar(255) NOT NULL default '',
  `transtype` tinyint(1) NOT NULL default '0',
  `errflag` tinyint(1) NOT NULL default '0',
  `transflag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`transid`),
  KEY `fromid` (`fromid`),
  KEY `fromname` (`fromname`),
  KEY `toid` (`toid`),
  KEY `toname` (`toname`),
  KEY `transtype` (`transtype`),
  KEY `transflag` (`transflag`)
) TYPE=MyISAM;