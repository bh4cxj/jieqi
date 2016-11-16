<?php 
/**
 * 易付通-返回处理
 *
 * 易付通-返回处理 （http://www.xpay.cn）
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: xpayreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'xpay');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

//接收XPAY平台返回的信息
$tid=$_REQUEST["tid"];//   '商户唯一交易号）
$bid=$_REQUEST["bid"];//     '商户网站订单号
$sid=$_REQUEST["sid"];//     '易付通交易成功 流水号
$prc=$_REQUEST["prc"];//    '支付的金额
$actionCode=$_REQUEST["actioncode"];//    '交易码
$actionParameter=$_REQUEST["actionparameter"];//    '业务代码
$card=$_REQUEST["card"];//    '支付方式
$success=$_REQUEST["success"];//    '成功标志，
$bankcode=$_REQUEST["bankcode"];//   '支付银行
$remark1=$_REQUEST["remark1"];//     '备注信息
$username=$_REQUEST["username"];//  '商户网站支付用户
$md=$_REQUEST["md"];//               '32位md5加密数据
$money=intval($prc * 100);

//取得商户参数
$tid = $jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号，改成XPAY负责分配给商户的编号
$key = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];  //默认的私钥值，更改私钥后要修改这里
     
if ($success=="false") {
     jieqi_printfail($jieqiLang['pay']['pay_return_error']);
}
//'验证数据是否正确   
$ymd=md5($key . ":" . $bid . "," . $sid . "," . $prc . "," . $actionCode  ."," . $actionParameter . "," . $tid . "," . $card . "," . $success);//  '本地进行数据加密
if($md!=$ymd){  //             '验证数据是否正确
    jieqi_printfail($jieqiLang['pay']['return_checkcode_error']);
}else{
//判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$paylog=$paylog_handler->get($bid);
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
}
?>