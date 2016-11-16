-- --------------------------------------------------------

-- 
-- 表的结构 `jieqi_note_note`
-- 
DROP TABLE IF EXISTS `jieqi_note_note`;
CREATE TABLE `jieqi_note_note` (
  `noteid` int(10) unsigned NOT NULL auto_increment,
  `notetitle` varchar(50) NOT NULL default '',
  `notebody` text NOT NULL,
  `postname` varchar(30) NOT NULL default '',
  `postoicq` varchar(20),
  `postphone` varchar(20),
  `postemail` varchar(50),
  `posttime` datetime NOT NULL,
  `postip` varchar(20) NOT NULL default '',
  `noteflag` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`noteid`)
) TYPE=MyISAM;


-- --------------------------------------------------------

