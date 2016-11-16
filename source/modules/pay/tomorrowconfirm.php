<?php 
/**
 * 明天数码-充值确认
 *
 * 明天数码-充值确认
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: tomorrowconfirm.php 312 2008-12-29 05:30:54Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'tomorrow');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_loadlang(JIEQI_PAY_TYPE, JIEQI_MODULE_NAME);
jieqi_checklogin();
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$orderid=trim($_POST['orderid']);
$buypass=strtolower(trim($_POST['buypass']));

if(!is_numeric($orderid)) jieqi_printfail($jieqiLang['pay']['error_order_id']);
elseif(empty($buypass)) jieqi_printfail($jieqiLang['pay']['need_buy_pass']);

include_once($jieqiModules['pay']['path'].'/class/paylog.php');
$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
$orderid=intval($orderid);
$paylog=$paylog_handler->get($orderid);
if(is_object($paylog)){
	$buyname=$_SESSION['jieqiUserName'];
	$buyid=$_SESSION['jieqiUserId'];
	$payflag=$paylog->getVar('payflag');
	$egold=$paylog->getVar('egold');
	if($payflag == 0){
		if($paylog->getVar('note', 'n') == $buypass){
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold]);
			if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
			else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
			$paylog->setVar('buyid', $buyid);
			$paylog->setVar('buyname', $buyname);
			$paylog->setVar('rettime', JIEQI_NOW_TIME);
			$paylog->setVar('note', $note);
			$paylog->setVar('payflag', 1);
			if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['save_paylog_failure']);
		}else{
			jieqi_printfail($jieqiLang['pay']['error_buy_pass']);
		}
		jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['pay']['buy_egold_success'], jieqi_htmlstr($buyname), JIEQI_EGOLD_NAME, $egold));
	}else{
		jieqi_printfail($jieqiLang['pay']['already_add_egold']);
	}
}else jieqi_printfail($jieqiLang['pay']['no_buy_record']);
?>