UPDATE `jieqi_system_modules` SET version=150 WHERE name='forum';
ALTER TABLE `jieqi_forum_forums` ADD `forumtype` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `forumorder`;