<?php 
/**
 * 环迅支付-返回处理
 *
 * 环迅支付-返回处理 (http://www.ipay.cn)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ipsreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'ips');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

//1-----------接收回的信息--------------------------------------------------------------------
$billno=trim($_GET['billno']);  //订单编号
$amount=trim($_GET['amount']);  //金额
$date=trim($_GET['date']);  //日期
$succ=trim($_GET['succ']);  //Y-成功 N-失败
$msg=trim($_GET['msg']);  //返回结果提示
$attach=trim($_GET['attach']);  //跟提交一样返回
$ipsbillno=trim($_GET['ipsbillno']);  //ips的订单号
$retencodetype=trim($_GET['retencodetype']);  //返回校验方式  0-老接口 1-MD5WithRSA 2-MD5
$signature=trim($_GET['signature']);  //数字签名

$money=intval($amount * 100);
$attachary=explode('|', $attach);
if($attachary[1] == '02'){
	$Mer_code = $jieqiPayset[JIEQI_PAY_TYPE]['foreignpayid']; //商户编号
	$key = $jieqiPayset[JIEQI_PAY_TYPE]['foreignpaykey'];  //密钥值
}else{
	$Mer_code = $jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号
	$key = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];  //密钥值
}

//2-----------重新计算md5的值---------------------------------------------------------------------------
$md5string=strtolower(md5($billno.$amount.$date.$succ.$ipsbillno.$key));

//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------
if($succ=='Y' && $signature==$md5string){  
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$orderid=substr($billno,-6);
	if(!empty($attachary[0]) && strlen($attachary[0])>6) $orderid=$attachary[0];
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
			$paylog->setVar('money', $money);
			$paylog->setVar('note', $note);
			$paylog->setVar('payflag', 1);
			if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['save_paylog_failure']);
			else jieqi_msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
		}else{
			jieqi_printfail($jieqiLang['pay']['already_add_egold']);
		}
	}else jieqi_printfail($jieqiLang['pay']['no_buy_record']);
}else jieqi_printfail(sprintf($jieqiLang['pay']['pay_failure_message'], jieqi_htmlstr($msg)));
?>