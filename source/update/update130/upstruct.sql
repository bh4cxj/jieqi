ALTER TABLE `jieqi_system_configs` DROP INDEX `modname`;
ALTER TABLE `jieqi_system_configs` ADD UNIQUE `modname` ( `modname` , `cname` );

ALTER TABLE `jieqi_system_configs` CHANGE `catorder` `catorder` INT( 10 ) NOT NULL DEFAULT '0';

ALTER TABLE `jieqi_system_power` DROP INDEX `modname`;
ALTER TABLE `jieqi_system_power` ADD UNIQUE `modname` ( `modname` , `pname` );


ALTER TABLE `jieqi_system_users` CHANGE `payment` `esilver` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `jieqi_system_users` CHANGE `mouthscore` `monthscore` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `jieqi_system_users` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `uid` ;

ALTER TABLE `jieqi_system_modules` ADD `vtype` VARCHAR( 30 ) NOT NULL DEFAULT '' AFTER `version` ;
ALTER TABLE `jieqi_system_modules` DROP INDEX `name` ;
ALTER TABLE `jieqi_system_modules` ADD UNIQUE (`name`);

ALTER TABLE `jieqi_system_message` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `messageid` ;
ALTER TABLE `jieqi_system_userlog` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `logid` ;

ALTER TABLE `jieqi_system_online` ADD `pass` VARCHAR( 32 ) NOT NULL AFTER `uname`;
ALTER TABLE `jieqi_system_online` ADD `email` VARCHAR( 60 ) NOT NULL AFTER `pass` ;
ALTER TABLE `jieqi_system_online` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `uid` ;



ALTER TABLE `jieqi_article_article` DROP INDEX `lastvisit`;
ALTER TABLE `jieqi_article_article` DROP INDEX `display`;  
ALTER TABLE `jieqi_article_article` DROP INDEX `size`;  
ALTER TABLE `jieqi_article_article` DROP INDEX `authorid`; 
ALTER TABLE `jieqi_article_article` ADD INDEX ( `author` ); 
ALTER TABLE `jieqi_article_article` CHANGE `mouthvisit` `monthvisit` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `jieqi_article_article` CHANGE `mouthvote` `monthvote` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `jieqi_article_article` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `articleid` ;
ALTER TABLE `jieqi_article_article` ADD `typeid` SMALLINT( 3 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `sortid` ;
ALTER TABLE `jieqi_article_article` DROP INDEX `sortid` ,ADD INDEX `sortid` ( `sortid` , `typeid` );

ALTER TABLE `jieqi_article_chapter` DROP INDEX `lastupdate_2`;
ALTER TABLE `jieqi_article_chapter` ADD `attachment` TEXT NOT NULL AFTER `totalcost` ;
ALTER TABLE `jieqi_article_chapter` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `chapterid` ;

ALTER TABLE `jieqi_article_review` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `reviewid` ;



ALTER TABLE `jieqi_forum_forumtopics` DROP INDEX `replytime_2`;
ALTER TABLE `jieqi_forum_forumtopics` DROP INDEX `replytime_3`;
ALTER TABLE `jieqi_forum_forumtopics` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `topicid` ;

ALTER TABLE `jieqi_forum_forumposts` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `postid` ;


ALTER TABLE `jieqi_obook_obuyinfo` ADD `checkcode` VARCHAR( 10 ) NOT NULL DEFAULT '' AFTER `readnum` ;
ALTER TABLE `jieqi_obook_obook` CHANGE `mouthsale` `monthsale` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `jieqi_obook_ochapter` CHANGE `mouthsale` `monthsale` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `jieqi_obook_obook` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `obookid` ;
ALTER TABLE `jieqi_obook_ochapter` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `ochapterid` ;
ALTER TABLE `jieqi_obook_osale` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `osaleid` ;
ALTER TABLE `jieqi_obook_obuyinfo` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `obuyinfoid` ;

ALTER TABLE `jieqi_pay_paylog` ADD `siteid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `payid` ;