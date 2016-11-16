<?php 
/**
 * 点卡充值-账号确认
 *
 * 点卡充值-账号确认
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: paycardconfirm.php 312 2008-12-29 05:30:54Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'paycard');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_loadlang('paycard', JIEQI_MODULE_NAME);
jieqi_checklogin();
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$cardno=trim($_POST['cardno']);
$cardpass=strtolower(trim($_POST['cardpass']));

if(strlen($cardno)==0) jieqi_printfail($jieqiLang['pay']['need_card_no']);
elseif(strlen($cardpass)==0) jieqi_printfail($jieqiLang['pay']['need_card_pass']);

include_once($jieqiModules['pay']['path'].'/class/paycard.php');
$paycard_handler =& JieqiPaycardHandler::getInstance('JieqiPaycardHandler');
$criteria=new Criteria('cardno', $cardno);
$paycard_handler->queryObjects($criteria);
if($paycard = $paycard_handler->getObject()){
	//检查密码，和是否被冲值过
	if($paycard->getVar('cardpass', 'n') != $cardpass) jieqi_printfail($jieqiLang['pay']['error_card_pass']);
	elseif($paycard->getVar('ispay', 'n') != 0) jieqi_printfail($jieqiLang['pay']['card_is_pay']);
	//加入购买记录
	$buyname=$_SESSION['jieqiUserName'];
	$buyid=$_SESSION['jieqiUserId'];
	$egold=$paycard->getVar('payemoney', 'n');
	if(isset($jieqiPayset[JIEQI_PAY_TYPE][$egold])) $money=$jieqiPayset[JIEQI_PAY_TYPE][$egold] * 100;
	else $money=0;
	
	//设置冲值卡标记
	$paycard->setVar('ispay', 1);
	$paycard->setVar('paytime', JIEQI_NOW_TIME);
	$paycard->setVar('payuid', $buyid);
	$paycard->setVar('payname', $buyname);
	if(!$paycard_handler->insert($paycard)) jieqi_printfail($jieqiLang['pay']['update_paycard_failure']);
	
	//用户增加虚拟币
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold]);
	if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
	else{
		//增加虚拟币失败，卡号标记设置回去
		$paycard->setVar('ispay', 0);
		$paycard_handler->insert($paycard);
		jieqi_printfail($jieqiLang['pay']['income_egold_failure']);
	}
	//else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
			
	//增加交易记录
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$paylog= $paylog_handler->create();
	$paylog->setVar('siteid', JIEQI_SITE_ID);
	$paylog->setVar('buytime', JIEQI_NOW_TIME);
	$paylog->setVar('rettime', JIEQI_NOW_TIME);
	$paylog->setVar('buyid', $buyid);
	$paylog->setVar('buyname', $buyname);
	$paylog->setVar('buyinfo', '');
	$paylog->setVar('moneytype', $jieqiPayset[JIEQI_PAY_TYPE]['moneytype']);
	$paylog->setVar('money', $money);
	$paylog->setVar('egoldtype', $jieqiPayset[JIEQI_PAY_TYPE]['paysilver']);
	$paylog->setVar('egold', $egold);
	$paylog->setVar('paytype', JIEQI_PAY_TYPE);
	$paylog->setVar('retserialno', '');
	$paylog->setVar('retaccount', '');
	$paylog->setVar('retinfo', '');
	$paylog->setVar('masterid', 0);
	$paylog->setVar('mastername', '');
	$paylog->setVar('masterinfo', '');
	$paylog->setVar('note', $note);
	$paylog->setVar('payflag', 1);
	if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['save_paylog_failure']);
	jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['pay']['buy_egold_success'], jieqi_htmlstr($buyname), JIEQI_EGOLD_NAME, $egold));
}else{
	jieqi_printfail($jieqiLang['pay']['card_nor_exists']);
}
?>