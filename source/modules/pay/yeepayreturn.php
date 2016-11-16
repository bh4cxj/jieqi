<?php 
/**
 * 易宝支付-返回处理
 *
 * 易宝支付-返回处理 （ http://www.yeepay.com）
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: yeepayreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'yeepay');
require_once('../../global.php');
require_once('yeepaycommon.php'); //易宝支付接口公共函数
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$merchantId = $jieqiPayset[JIEQI_PAY_TYPE]['payid'];  //商户编号
$keyValue = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];
$paytype=JIEQI_PAY_TYPE;
if(isset($_REQUEST['rb_BankId'])) $_REQUEST['rb_BankId']=trim($_REQUEST['rb_BankId']);
if(!empty($_REQUEST['rb_BankId']) && isset($jieqiPayset[JIEQI_PAY_TYPE]['paytype'][$_REQUEST['rb_BankId']])) $paytype=$jieqiPayset[JIEQI_PAY_TYPE]['paytype'][$_REQUEST['rb_BankId']];
	
/* 返回参数
$sCmd = trim($_REQUEST['sCmd']);
$sErrorCode = trim($_REQUEST['sErrorCode']);
$sTrxId = trim($_REQUEST['sTrxId']);
$amount = trim($_REQUEST['amount']);
$cur = trim($_REQUEST['cur']);
$productId = trim($_REQUEST['productId']);
$orderId = trim($_REQUEST['orderId']);
$userId = trim($_REQUEST['userId']);
$MP = trim($_REQUEST['MP']);
$bType = trim($_REQUEST['bType']);
$svrHmac = trim($_REQUEST['svrHmac']);
*/
#以下代码和变量不需要修改
#解析返回参数
$return = getCallBackValue($sCmd,$sErrorCode,$sTrxId,$amount,$cur,$productId,$orderId,$userId,$MP,$bType,$svrHmac);

#判断返回签名是否正确（True/False）
$bRet = CheckHmac($sCmd,$sErrorCode,$sTrxId,$orderId,$amount,$cur,$productId,$userId,$MP,$bType,$svrHmac);
#以上代码和变量不需要修改

#校验码正确
if($bRet){
	if($sErrorCode=='1'){
		#需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功
		#并且需要对返回的处理进行事务控制，进行记录的排它性处理，防止对同一条交易重复发货的情况发生

		include_once($jieqiModules['pay']['path'].'/class/paylog.php');
		$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
		$orderid=intval($orderId);
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
				$paylog->setVar('paytype', $paytype);
				$paylog->setVar('note', $note);
				$paylog->setVar('payflag', 1);
				if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['save_paylog_failure']);
			}
			if($bType=="2") echo "success";
			jieqi_msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
		}
	}else{
		jieqi_printfail($jieqiLang['pay']['pay_return_error']);
	}
}else{
	jieqi_printfail($jieqiLang['pay']['return_checkcode_error']);

}
?>