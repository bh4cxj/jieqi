<?php 
/**
 * 梦联支付-错误处理
 *
 * 梦联支付-错误处理 (http://www.nationm.com.cn)
 * 
 * 调用模板：/modules/pay/templates/nationmerr.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: nationmerr.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'nationm');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
$template=$jieqiModules['pay']['path'].'/templates/nationmerr.html';
if(file_exists($template)){
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
	if (JIEQI_USE_CACHE) $jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = $template;
	include_once(JIEQI_ROOT_PATH.'/footer.php');
}else{
	jieqi_loadlang('nationm', JIEQI_MODULE_NAME);
	jieqi_msgwin($jieqiLang['pay']['submit_failure_title'], $jieqiLang['pay']['submit_failure']);
}
?>