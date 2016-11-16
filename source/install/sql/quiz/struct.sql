

--
-- 表的结构 `jieqi_quiz_answer`
--

DROP TABLE IF EXISTS `jieqi_quiz_answer`;
CREATE TABLE `jieqi_quiz_answer` (
  `answerid` int(11) NOT NULL auto_increment,
  `problemid` int(11) NOT NULL,
  `content` text NOT NULL,
  `username` varchar(20) NOT NULL,
  `addtime` varchar(10) NOT NULL,
  PRIMARY KEY  (`answerid`),
  KEY `problemid` (`problemid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_quiz_problems`
--

DROP TABLE IF EXISTS `jieqi_quiz_problems`;
CREATE TABLE `jieqi_quiz_problems` (
  `quizid` int(11) NOT NULL auto_increment,
  `tags` varchar(20) NOT NULL,
  `typeid` varchar(15) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `username` varchar(20) NOT NULL,
  `score` smallint(6) NOT NULL,
  `addtime` varchar(10) NOT NULL,
  `overtime` varchar(10) NOT NULL,
  `typez` smallint(6) NOT NULL default '1',
  `answer` int(11) NOT NULL default '0',
  `readz` int(11) NOT NULL default '1',
  PRIMARY KEY  (`quizid`),
  KEY `typeid` (`typeid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `jieqi_quiz_tag`
--

DROP TABLE IF EXISTS `jieqi_quiz_tag`;
CREATE TABLE `jieqi_quiz_tag` (
  `tagid` int(11) NOT NULL auto_increment,
  `tagname` varchar(20) NOT NULL,
  `tagcontent` mediumtext NOT NULL,
  `tagtype` varchar(20) NOT NULL,
  `num` int(11) NOT NULL default '1',
  `click` int(11) NOT NULL default '1',
  PRIMARY KEY  (`tagid`),
  KEY `tagname` (`tagname`)
) TYPE=MyISAM;
;