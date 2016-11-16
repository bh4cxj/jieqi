<?php 
/**
 * 腾讯财付通-返回处理
 *
 * 腾讯财付通-返回处理 (https://www.tenpay.com)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: tenpayreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'tenpay');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$payid=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号

//1-----------接收回的信息--------------------------------------------------------------------
$cmdno = trim($_REQUEST['cmdno']); //任务代码 1
$pay_result = trim($_REQUEST['pay_result']); //支付结果，详见“返回值编码定义”, 0—成功
$pay_info = trim($_REQUEST['pay_info']); //支付结果信息，支付成功时为空
$date = trim($_REQUEST['date']); //商户日期
$bargainor_id = trim($_REQUEST['bargainor_id']);  //卖方账号（商户spid）
$transaction_id = trim($_REQUEST['transaction_id']); //财付通交易号(订单号)
$sp_billno = trim($_REQUEST['sp_billno']); //商户系统内部的定单号，此参数仅在对账时提供。 
$total_fee = trim($_REQUEST['total_fee']); //订单总金额，以分为单位
$fee_type = trim($_REQUEST['fee_type']); //现金支付币种
$attac = trim($_REQUEST['attac']); //商家数据包，原样返回
$sign = trim($_REQUEST['sign']); //签名

//2-----------重新计算md5的值---------------------------------------------------------------------------
//注意正确的参数串拼凑顺序
$text="cmdno=".$cmdno."&pay_result=".$pay_result."&date=".$date."&transaction_id=".$transaction_id."&sp_billno=".$sp_billno."&total_fee=".$total_fee."&fee_type=".$fee_type."&attach=".$attach."&key=".$jieqiPayset[JIEQI_PAY_TYPE]['paykey']; 
$mac = md5($text); 

//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------

if($bargainor_id != $jieqiPayset[JIEQI_PAY_TYPE]['payid']) jieqi_printfail($jieqiLang['pay']['customer_id_error']);
elseif($pay_result != '0') jieqi_printfail($jieqiLang['pay']['pay_return_error']);
elseif (strtoupper($mac) != strtoupper($sign)) jieqi_printfail($jieqiLang['pay']['return_checkcode_error']);
else{
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$orderid=intval($sp_billno);
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
			else{
				echo '<meta name="TENCENT_ONELINE_PAYMENT" content="China TENCENT">
<html>
<script language=javascript>
window.location.href=\''.sprintf($jieqiPayset[JIEQI_PAY_TYPE]['payresult'], $orderid, $egold, $buyid, urlencode($buyname)).'\';
</script>
</html>';
			}
		}else{
			jieqi_printfail($jieqiLang['pay']['already_add_egold']);
		}
	}else{
		jieqi_printfail($jieqiLang['pay']['no_buy_record']);
	}
}
?>