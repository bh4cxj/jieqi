<?php 
/**
 * 酷币支付-返回处理
 *
 * 酷币支付-返回处理 (http://www.95cool.com)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: 95coolreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', '95cool');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$mymerchant_id=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号
$key=$jieqiPayset[JIEQI_PAY_TYPE]['paykey']; //密钥

//1-----------接收回的信息--------------------------------------------------------------------
$merchant_id =trim($_REQUEST['merchant_id']);			///获取商户编号
$orderid = trim($_REQUEST['orderid']);		///获取订单编号
$amount = trim($_REQUEST['amount']);	///获取订单金额
$date = trim($_REQUEST['date']);		///获取交易日期
$succeed = trim($_REQUEST['succeed']);	///获取交易结果,Y成功,N失败
$mac = trim($_REQUEST['mac']);		///获取安全加密串

//2-----------重新计算md5的值-------------------------------------------------------------------------

//生成加密串,注意顺序
$text=$merchant_id.$orderid.$amount.$key;
$mymac = strtoupper(md5($text));

//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------

if($merchant_id != $mymerchant_id) jieqi_printfail($jieqiLang['pay']['customer_id_error']);
elseif(strtoupper($succeed) != 'Y') jieqi_printfail($jieqiLang['pay']['pay_return_error']);
elseif (strtoupper($mac) != strtoupper($mymac)) jieqi_printfail($jieqiLang['pay']['return_checkcode_error']);
else{     	//---------如果签名验证成功！
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$orderid=intval($orderid);
	$paylog=$paylog_handler->get($orderid);
	if(is_object($paylog)){
		$buyname=$paylog->getVar('buyname');
		$buyid=$paylog->getVar('buyid');
		$payflag=$paylog->getVar('payflag');
		$egold=$paylog->getVar('egold');
		if($payflag == 0){
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold]);
			if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
			else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
			$paylog->setVar('rettime', JIEQI_NOW_TIME);
			$paylog->setVar('money', intval($amount * 100));
			$paylog->setVar('note', $note);
			$paylog->setVar('payflag', 1);
			if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['save_paylog_failure']);
			else jieqi_msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
		}else{
			jieqi_printfail($jieqiLang['pay']['already_add_egold']);
		}
	}else{
		jieqi_printfail($jieqiLang['pay']['no_buy_record']);
	}
}
?>