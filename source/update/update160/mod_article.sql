UPDATE `jieqi_system_configs` SET ctype=10, cdescription='全部不选表示不生成jar', options='a:4:{i:1;s:7:"全本jar";i:2;s:7:"64K分卷";i:4;s:8:"128K分卷";i:16;s:8:"512K分卷";}' WHERE modname='article' AND cname='makejar';

UPDATE `jieqi_system_configs` SET ctype=10, cdescription='全部不选表示不生成umd', options='a:4:{i:1;s:7:"全本umd";i:2;s:7:"64K分卷";i:4;s:8:"128K分卷";i:16;s:8:"512K分卷";}' WHERE modname='article' AND cname='makeumd';

DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='hidereviewwords';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='minreviewtime';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='checkreviewrubbish';

DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='txtfile';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='opffile';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='rssfile';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='makerss';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='rssdir';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='rssurl';

DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='zipfile';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='txtfullfile';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='umdfile';
DELETE FROM `jieqi_system_configs` WHERE modname='article' AND cname='jarfile';

UPDATE `jieqi_system_configs` SET ctype=7, options='a:2:{s:5:".html";s:5:".html";s:6:".shtml";s:6:".shtml";}' WHERE modname='article' AND cname='htmlfile';

UPDATE `jieqi_system_configs` SET ctype=7, options='a:3:{s:4:".htm";s:4:".htm";s:5:".html";s:5:".html";s:4:".php";s:4:".php";}' WHERE modname='article' AND cname='fakefile';

UPDATE `jieqi_system_configs` SET ctitle='封面图片保存目录' WHERE modname='article' AND cname='imagedir';

UPDATE `jieqi_system_configs` SET ctype=7, options='a:4:{s:4:".jpg";s:4:".jpg";s:5:".jpeg";s:5:".jpeg";s:4:".gif";s:4:".gif";s:4:".png";s:4:".png";}', ctitle='封面图片文件后缀' WHERE modname='article' AND cname='imagetype';

UPDATE `jieqi_system_configs` SET ctitle='访问封面图片的URL' WHERE modname='article' AND cname='imageurl';


INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES(0, '用户文章', 'article', 'block_uarticles', 'BlockArticleUarticles', 6, '我的文章', '&nbsp;&nbsp;&nbsp;&nbsp;本区块显示某一用户的原创文章<br>&nbsp;&nbsp;&nbsp;&nbsp;允许设置五个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是排序字段，允许设置成“lastupdate”-更新时间，“postdate”-发表时间，“articleid”-文章ID，“allvisit”-总点击，“monthvisit”-月点击，“weekvisit”-周点击，“allvote”-总推荐，“monthvote”-月推荐，“weekvote”-周推荐，“size”-字数，“goodnum”-收藏数<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是显示几条记录，默认10<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是排序方式：0-从大到小，1-从小到大<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是显示哪个用户的友情链接，允许设置成“self”-当前用户，“uid”-url参数里面uid值对应的用户，“0”-所有用户，设置成大于0的一个整数，表示指定这个uid的用户<br>&nbsp;&nbsp;&nbsp;&nbsp;参数五全本标志，0-都显示，1-显示全本，2-显示非全本', '', 'lastupdate,10,0,uid,0', 'block_uarticles.html', 0, 4, 25100, 0, 0, 0, 0, 1);

INSERT INTO `jieqi_system_blocks` (`bid`, `blockname`, `modname`, `filename`, `classname`, `side`, `title`, `description`, `content`, `vars`, `template`, `cachetime`, `contenttype`, `weight`, `showstatus`, `custom`, `canedit`, `publish`, `hasvars`) VALUES(0, '用户书架', 'article', 'block_ubookcase', 'BlockArticleUbookcase', 6, '我的书架', '&nbsp;&nbsp;&nbsp;&nbsp;本区块显示某一用户的书架文章<br>&nbsp;&nbsp;&nbsp;&nbsp;允许设置四个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是排序字段，允许设置成“lastupdate”-更新时间，“joindate”-加入时间，“articleid”-文章ID，“caseid”-书架ID，“lastvisit”-最后访问时间<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是显示几条记录，默认10<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是排序方式：0-从大到小，1-从小到大<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是显示哪个用户的友情链接，允许设置成“self”-当前用户，“uid”-url参数里面uid值对应的用户，“0”-所有用户，设置成大于0的一个整数，表示指定这个uid的用户', '', 'lastupdate,10,0,uid', 'block_ubookcase.html', 0, 4, 25200, 0, 0, 0, 0, 1);


ALTER TABLE `jieqi_article_reviews` CHANGE `reviewid` `topicid` mediumint(8) unsigned NOT NULL auto_increment;
ALTER TABLE `jieqi_article_reviews` CHANGE `articleid` `ownerid` int(10) unsigned NOT NULL default '0';
ALTER TABLE `jieqi_article_reviews` CHANGE `title` `title` varchar(80) NOT NULL default '';
ALTER TABLE `jieqi_article_reviews` ADD `replierid` int(10) NOT NULL default '0' AFTER `posttime`;
ALTER TABLE `jieqi_article_reviews` ADD `replier` varchar(30) NOT NULL default '' AFTER `replierid`;
ALTER TABLE `jieqi_article_reviews` ADD `rate` tinyint(1) NOT NULL default '0' AFTER `isgood`;
ALTER TABLE `jieqi_article_reviews` ADD `attachment` tinyint(1) NOT NULL default '0' AFTER `rate`;
ALTER TABLE `jieqi_article_reviews` ADD `needperm` int(10) unsigned NOT NULL default '0' AFTER `attachment`;
ALTER TABLE `jieqi_article_reviews` ADD `needscore` int(10) unsigned NOT NULL default '0' AFTER `needperm`;
ALTER TABLE `jieqi_article_reviews` ADD `needexp` int(10) unsigned NOT NULL default '0' AFTER `needscore`;
ALTER TABLE `jieqi_article_reviews` ADD `needprice` int(10) unsigned NOT NULL default '0' AFTER `needexp`;
ALTER TABLE `jieqi_article_reviews` CHANGE `topictype` `sortid` tinyint(3) NOT NULL default '0' AFTER `needprice`;
ALTER TABLE `jieqi_article_reviews` ADD `iconid` tinyint(3) NOT NULL default '0' AFTER `sortid`;
ALTER TABLE `jieqi_article_reviews` ADD `typeid` tinyint(3) NOT NULL default '0' AFTER `iconid`;
ALTER TABLE `jieqi_article_reviews` ADD `linkurl` varchar(100) NOT NULL default '' AFTER `lastinfo`;

ALTER TABLE `jieqi_article_reviews` DROP INDEX `articleid`;
ALTER TABLE `jieqi_article_reviews` ADD INDEX `posterid` (`posterid`,`replytime`);
ALTER TABLE `jieqi_article_reviews` ADD INDEX `ownerid` ( `ownerid` , `istop` , `replytime` );

ALTER TABLE `jieqi_article_replies` CHANGE `replyid` `postid` int(10) unsigned NOT NULL auto_increment;
ALTER TABLE `jieqi_article_replies` CHANGE `reviewid` `topicid` int(10) unsigned NOT NULL default '0';
ALTER TABLE `jieqi_article_replies` ADD `replypid` int(10) unsigned NOT NULL default '0' AFTER `istopic`;
ALTER TABLE `jieqi_article_replies` CHANGE `articleid` `ownerid` int(10) unsigned NOT NULL default '0';

ALTER TABLE `jieqi_article_replies` ADD `editorid` int(10) NOT NULL default '0' AFTER `posterip`;
ALTER TABLE `jieqi_article_replies` ADD `editor` varchar(30) NOT NULL default '' AFTER `editorid`;
ALTER TABLE `jieqi_article_replies` ADD `edittime` int(10) NOT NULL default '0' AFTER `editor`;
ALTER TABLE `jieqi_article_replies` ADD `editorip` varchar(25) NOT NULL default '' AFTER `edittime`;
ALTER TABLE `jieqi_article_replies` ADD `editnote` varchar(250) NOT NULL default '' AFTER `editorip`;
ALTER TABLE `jieqi_article_replies` ADD `iconid` tinyint(3) NOT NULL default '0' AFTER `editnote`;
ALTER TABLE `jieqi_article_replies` ADD `attachment` text NOT NULL AFTER `iconid`;
ALTER TABLE `jieqi_article_replies` CHANGE `subject` `subject` varchar(80) NOT NULL default '';

ALTER TABLE `jieqi_article_replies` DROP INDEX `articleid`;
ALTER TABLE `jieqi_article_replies` DROP INDEX `reviewid`;
ALTER TABLE `jieqi_article_replies` ADD INDEX `ownerid` (`ownerid`);
ALTER TABLE `jieqi_article_replies` ADD INDEX `ptopicid` (`topicid`,`posttime`);

