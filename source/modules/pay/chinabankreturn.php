<?php 
/**
 * 网银在线-返回处理
 *
 * 网银在线-返回处理 (http://www.chinabank.com.cn)
 * 
 * 调用模板：/modules/pay/templates/chinabank.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chinabankreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'chinabank');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

//1-----------接收返回的信息--------------------------------------------------------------------
$v_oid        = trim($_POST['v_oid']); //商户发送的v_oid定单编号
$v_pmode      = trim($_POST['v_pmode']);  //支付银行，例如工商银行
$v_pstatus    = trim($_POST['v_pstatus']);  //20（表示支付成功）30（表示支付失败）
$v_pstring    = trim($_POST['v_pstring']);  //支付成功 支付失败
$v_amount     = trim($_POST['v_amount']); //订单实际支付金额
$v_moneytype  = trim($_POST['v_moneytype']); //订单实际支付币种
$remark1      = trim($_POST['remark1' ]);
$remark2      = trim($_POST['remark2' ]);
$v_md5str     = trim($_POST['v_md5str' ]);

$money=intval($v_amount * 100);
if($v_moneytype==1){
	$v_mid = $jieqiPayset[JIEQI_PAY_TYPE]['foreignpayid']; //商户编号
	$key = $jieqiPayset[JIEQI_PAY_TYPE]['foreignpaykey'];  //密钥值
}else{
	$v_mid = $jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号
	$key = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];  //密钥值
}


//2-----------重新计算md5的值---------------------------------------------------------------------------
$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));


//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------
if($v_pstatus=="20" && $v_md5str==$md5string){
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$void_ary=explode('-', $v_oid);
	$orderid=intval($void_ary[2]);
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
}else jieqi_printfail($jieqiLang['pay']['pay_return_error']);
?>