INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES(0, '帖子推荐区块', 'forum', 'block_topiccommend', 'BlockForumTopiccommend', 0, '推荐帖子', '&nbsp;&nbsp;&nbsp;&nbsp;本区块根据参数里面的ID显示对应的推荐主题。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置一个参数，即推荐的主题ID。不过ID可以是多个用“|”分隔，如 12|34|56', '', '', 'block_topiccommend.html', 0, 4, 41100, 0, 0, 0, 0, 2);

ALTER TABLE `jieqi_forum_forumtopics` CHANGE `forumid` `ownerid` int(10) unsigned NOT NULL default '0';
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topictitle` `title` varchar(80) NOT NULL default '';
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topicposterid` `posterid` int(10) NOT NULL default '0';
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topicposter` `poster` varchar(30) NOT NULL default '';
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topictime` `posttime` int(10) NOT NULL default '0';
ALTER TABLE `jieqi_forum_forumtopics` ADD `replierid` int(10) NOT NULL default '0' AFTER `posttime`;
ALTER TABLE `jieqi_forum_forumtopics` ADD `replier` varchar(30) NOT NULL default '' AFTER `replierid`;
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topicviews` `views` mediumint(8) unsigned NOT NULL default '0';
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topicreplies` `replies` mediumint(8) unsigned NOT NULL default '0';
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topiclock` `islock` tinyint(1) NOT NULL default '0';
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topictop` `istop` int(11) NOT NULL default '0';
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topicgood` `isgood` tinyint(1) NOT NULL default '0';
ALTER TABLE `jieqi_forum_forumtopics` ADD `rate` tinyint(1) NOT NULL default '0' AFTER `isgood`;
ALTER TABLE `jieqi_forum_forumtopics` ADD `attachment` tinyint(1) NOT NULL default '0' AFTER `rate`;
ALTER TABLE `jieqi_forum_forumtopics` ADD `needperm` int(10) unsigned NOT NULL default '0' AFTER `attachment`;
ALTER TABLE `jieqi_forum_forumtopics` ADD `needscore` int(10) unsigned NOT NULL default '0' AFTER `needperm`;
ALTER TABLE `jieqi_forum_forumtopics` ADD `needexp` int(10) unsigned NOT NULL default '0' AFTER `needscore`;
ALTER TABLE `jieqi_forum_forumtopics` ADD `needprice` int(10) unsigned NOT NULL default '0' AFTER `needexp`;
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topictype` `sortid` tinyint(3) NOT NULL default '0' AFTER `needprice`;
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `iconid` `iconid` tinyint(3) NOT NULL default '0' AFTER `sortid`;
ALTER TABLE `jieqi_forum_forumtopics` ADD `typeid` tinyint(3) NOT NULL default '0' AFTER `iconid`;
ALTER TABLE `jieqi_forum_forumtopics` CHANGE `topiclastinfo` `lastinfo` varchar(250) NOT NULL default '';
ALTER TABLE `jieqi_forum_forumtopics` ADD `linkurl` varchar(100) NOT NULL default '' AFTER `lastinfo`;
ALTER TABLE `jieqi_forum_forumtopics` ADD `size` int(11) NOT NULL default '0' AFTER `linkurl`;

ALTER TABLE `jieqi_forum_forumtopics` DROP INDEX `forumid`;
ALTER TABLE `jieqi_forum_forumtopics` DROP INDEX `topiclock`;
ALTER TABLE `jieqi_forum_forumtopics` DROP INDEX `topictop`;
ALTER TABLE `jieqi_forum_forumtopics` DROP INDEX `topicgood`;
ALTER TABLE `jieqi_forum_forumtopics` DROP INDEX `replytime`;
ALTER TABLE `jieqi_forum_forumtopics` ADD INDEX `ownerid` (`ownerid`,`istop`,`replytime`);
ALTER TABLE `jieqi_forum_forumtopics` ADD INDEX `posterid` (`posterid`,`replytime`);



ALTER TABLE `jieqi_forum_forumposts` ADD `replypid` int(10) unsigned NOT NULL default '0' AFTER `istopic`;
ALTER TABLE `jieqi_forum_forumposts` CHANGE `forumid` `ownerid` int(10) unsigned NOT NULL default '0';
ALTER TABLE `jieqi_forum_forumposts` DROP `enablebbcode`;
ALTER TABLE `jieqi_forum_forumposts` DROP `enablehtml`;
ALTER TABLE `jieqi_forum_forumposts` DROP `enablesmilies`;
ALTER TABLE `jieqi_forum_forumposts` DROP `enablesig`;
ALTER TABLE `jieqi_forum_forumposts` ADD `editorid` int(10) NOT NULL default '0' AFTER `posterip`;
ALTER TABLE `jieqi_forum_forumposts` ADD `editor` varchar(30) NOT NULL default '' AFTER `editorid`;
ALTER TABLE `jieqi_forum_forumposts` ADD `editorip` varchar(25) NOT NULL default '' AFTER `edittime`;
ALTER TABLE `jieqi_forum_forumposts` CHANGE `editinfo` `editnote` varchar(250) NOT NULL default '';
ALTER TABLE `jieqi_forum_forumposts` CHANGE `postsubject` `subject` varchar(80) NOT NULL default '';
ALTER TABLE `jieqi_forum_forumposts` ADD `size` int(10) NOT NULL default '0' AFTER `subject`;

ALTER TABLE `jieqi_forum_forumposts` DROP INDEX `forumid`;
ALTER TABLE `jieqi_forum_forumposts` DROP INDEX `topicid`;
ALTER TABLE `jieqi_forum_forumposts` DROP INDEX `posterid`;
ALTER TABLE `jieqi_forum_forumposts` DROP INDEX `posttime`;

ALTER TABLE `jieqi_forum_forumposts` ADD INDEX `ownerid` (`ownerid`);
ALTER TABLE `jieqi_forum_forumposts` ADD INDEX `ptopicid` (`topicid`,`posttime`);



ALTER TABLE `jieqi_forum_attachs` ADD `siteid` smallint(6) unsigned NOT NULL default '0' AFTER `attachid`;
ALTER TABLE `jieqi_forum_attachs` ADD `description` varchar(100) NOT NULL default '' AFTER `name`;
ALTER TABLE `jieqi_forum_attachs` ADD `needperm` int(10) unsigned NOT NULL default '0' AFTER `hits`;
ALTER TABLE `jieqi_forum_attachs` ADD `needscore` int(10) unsigned NOT NULL default '0' AFTER `needperm`;
ALTER TABLE `jieqi_forum_attachs` ADD `needprice` int(10) unsigned NOT NULL default '0' AFTER `needexp`;
ALTER TABLE `jieqi_forum_attachs` ADD `uid` int(10) unsigned NOT NULL default '0' AFTER `uptime`;
ALTER TABLE `jieqi_forum_attachs` ADD `remote` tinyint(3) unsigned NOT NULL default '0' AFTER `uid`;

ALTER TABLE `jieqi_forum_attachs` DROP INDEX `postid`;
ALTER TABLE `jieqi_forum_attachs` ADD INDEX `postid` (`postid`, `attachid`);
ALTER TABLE `jieqi_forum_attachs` ADD INDEX `uid` (`uid`);