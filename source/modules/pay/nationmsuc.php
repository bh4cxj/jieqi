<?php 
/**
 * JIEQI CMS
 * Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * $Id: nationmsuc.php 326 2009-02-04 00:26:22Z juny $
 */
define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'nationm');
require_once('../../global.php');
$template=$jieqiModules['pay']['path'].'/templates/nationmsuc.html';
if(file_exists($template)){
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
	if (JIEQI_USE_CACHE) $jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = $template;
	include_once(JIEQI_ROOT_PATH.'/footer.php');
}else{
	jieqi_loadlang('nationm', JIEQI_MODULE_NAME);
	jieqi_msgwin($jieqiLang['pay']['submit_success_title'], $jieqiLang['pay']['submit_success']);
}
?>