RENAME TABLE `jieqi_link_review` TO `jieqi_link_link`;
ALTER TABLE `jieqi_link_link` ADD `userid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `introduce`;
ALTER TABLE `jieqi_link_link` CHANGE `mastertell` `mastertell` VARCHAR( 250 ) NOT NULL;