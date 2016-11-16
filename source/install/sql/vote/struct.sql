-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_vote_option`
-- 
DROP TABLE IF EXISTS `jieqi_vote_option`;
CREATE TABLE `jieqi_vote_option` (
  `optionid` smallint(5) unsigned NOT NULL auto_increment,
  `optionname` varchar(50) NOT NULL default '',
  `optioncount` int(11) unsigned NOT NULL default '0',
  `optionorder` tinyint(3) unsigned NOT NULL default '0',
  `topicid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`optionid`),
  KEY `topicid` (`topicid`)
) TYPE=MyISAM;


-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_vote_topic`
-- 
DROP TABLE IF EXISTS `jieqi_vote_topic`;
CREATE TABLE `jieqi_vote_topic` (
  `topicid` smallint(5) unsigned NOT NULL auto_increment,
  `topicname` varchar(50) NOT NULL default '',
  `votedate` date NOT NULL,
  `votetime` smallint(5) unsigned NOT NULL default '0',
  `votetype` tinyint(3) unsigned NOT NULL default '0',
  `voteresult` tinyint(1) unsigned NOT NULL default '0',
  `votepublic` tinyint(1) unsigned NOT NULL default '0',
  `voterepeat` tinyint(1) unsigned NOT NULL default '0',
  `postname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`topicid`)
)TYPE=MyISAM;