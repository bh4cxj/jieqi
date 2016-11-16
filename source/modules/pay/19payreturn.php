<?php 
/**
 * 19pay支付-返回处理
 *
 * 19pay支付-返回处理 (http://www.19pay.com)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: 19payreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', '19pay');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$mymerchant_id=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号
$merchant_key=$jieqiPayset[JIEQI_PAY_TYPE]['paykey']; //密钥

//1-----------接收回的信息--------------------------------------------------------------------
$version_id = trim($_REQUEST['version_id']);	//接口版本
$merchant_id = trim($_REQUEST['merchant_id']);	//商户编号
$verifystring = trim($_REQUEST['verifystring']); //获取安全加密串
$order_date = trim($_REQUEST['order_date']);	//交易日期
$order_id = trim($_REQUEST['order_id']);		//交易订单编号[商户网站]
$amount = trim($_REQUEST['amount']);			//交易金额
$currency = trim($_REQUEST['currency']);		//货币类型

$pay_sq = trim($_REQUEST['pay_sq']);		    //19pay流水号
$pay_date = trim($_REQUEST['pay_date']);		//支付时间 YYYYMMDDHHMMSS
$pc_id = trim($_REQUEST['pc_id']);		        //支付通道

$result = trim($_REQUEST['result']);			//交易结果，"Y"表示成功，"N"表示失败

//2-----------重新计算md5的值---------------------------------------------------------------------------
//注意正确的参数串拼凑顺序
$text="version_id=".$version_id."&merchant_id=".$merchant_id."&order_date=".$order_date."&order_id=".$order_id."&amount=".$amount."&currency=".$currency."&pay_sq=".$pay_sq."&pay_date=".$pay_date."&pc_id=".$pc_id."&result=".$result."&merchant_key=".$merchant_key;

$mac = md5($text); 

//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------

if($merchant_id != $mymerchant_id) jieqi_printfail($jieqiLang['pay']['customer_id_error']);
elseif(strtoupper($result) != 'Y') jieqi_printfail($jieqiLang['pay']['pay_return_error']);
elseif (strtoupper($mac)==strtoupper($verifystring)){     	//---------如果签名验证成功！
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$order_id=intval($order_id);
	$paylog=$paylog_handler->get($order_id);
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