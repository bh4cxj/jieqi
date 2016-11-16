ALTER TABLE `jieqi_pay_paylog` ADD `retserialno` VARCHAR( 100 ) NOT NULL AFTER `paytype` ,
ADD `retaccount` VARCHAR( 100 ) NOT NULL AFTER `retserialno` ;