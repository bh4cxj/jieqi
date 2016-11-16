<?php 
/**
 * 梦联支付-返回处理
 *
 * 梦联支付-返回处理 (http://www.nationm.com.cn)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: nationmreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'nationm');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

//1-----------接收返回的信息--------------------------------------------------------------------
$merchantID=trim($_GET['merchantID']);  //商户号    
$orderNum=trim($_GET['orderNum']);   //订单号   
$amount=trim($_GET['amount']);  //订单总金额    
$tranDateTime=trim($_GET['tranDateTime']);   //交易日期时间    
$orderSerialNum=trim($_GET['orderSerialNum']);    //订单流水号码 
$sucMark=trim($_GET['sucMark']);     //成功失败标志 0 成功
$comment=trim($_GET['comment']);     //失败原因
$currentType =trim($_GET['currentType']);    //币种
$noticeType=trim($_GET['noticeType']);    //信息发送类型
$md5Info=trim($_GET['md5Info']);   //MD5消息摘要  

$my_merchantID = $jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号
$key = $jieqiPayset[JIEQI_PAY_TYPE]['receivekey'];  //接收的密钥
                           
$money=intval($amount);

//2-----------重新计算md5的值---------------------------------------------------------------------------
$md5string=strtoupper(md5($merchantID.$orderNum.$amount.$tranDateTime.$sucMark.$noticeType.$key));

//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------
if($sucMark=="0" && $md5Info==$md5string){  
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$orderid=intval($orderNum);
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