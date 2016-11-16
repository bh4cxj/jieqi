<?php 
/**
 * 易充支付-返回处理
 *
 * 易充支付-返回处理 (http://www.123bill.cn)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: 123billreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', '123bill');
require_once('../../global.php');

require_once("123bill/keyfile.php");
require_once("123bill/sign.php");

jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$myunitid=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号
//$key=$jieqiPayset[JIEQI_PAY_TYPE]['paykey']; //密钥

//1-----------接收回的信息--------------------------------------------------------------------
$unitid = trim($_REQUEST['unitid']);	//商户编号
$paycode = trim($_REQUEST['paycode']);	//支付结果（0-未支付，1-已支付，2-撤销 3-支付平台拒绝）

$mesg = trim($_REQUEST['mesg']);		//显示的支付结果描述信息
$transid = trim($_REQUEST['transid']);	//交易流水号（合作网站生成，网站内唯一）
$signData = trim($_REQUEST['sign']);	//支付平台的签名
$retcode = trim($_REQUEST['retcode']); 
/*
retcode       ＝0 ：成功；无错误。
			  ＝1：请求参数缺少关键字段
			  ＝2：参数内容不合法
			  ＝3：加载签名失败，商家unitid失效或到期。建议和支付平台联系重新申请
			  ＝4：非法签名数据。验证失败，无效身份
			  ＝5：服务器验证签名异常
			  ＝7：支付流水号产生失败，有可能是交易编码重复提交。
			  ＝6：包月支付失败。
			  ＝8：支付已失效
			  ＝9：支付金额，不能小于0.01元
			  ＝10：服务器异常，加载页面失败。
			  ＝11：目前客户所在地区没有开通支付热线
			  ＝12：已经支付。
			  ＝13：已经撤销。
			  ＝14：交易记录不存在
          	  ＝15：操作失败
			  ＝16：请求被拒绝
			  ＝17：密钥为激活
			  ＝255：未定义错误，请参考Mesg内容。

支付平台返回已经支付（retcode=12）或者支付结果成功（paycode=1 、retcode=0），只表示支付平台库里该订单处于已支付状态
*/

//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------

if($unitid != $myunitid) jieqi_printfail($jieqiLang['pay']['customer_id_error']);
if($retcode == 12 || ($paycode == 1 && $retcode == 0)){
	$query_string = $_SERVER['QUERY_STRING'];
	$pos = strpos($query_string, "&sign=");
	if ($pos == false) jieqi_printfail($jieqiLang['pay']['return_checkcode_error']);
	else $param = substr($query_string, 0, $pos);


	$filename = JIEQI_ROOT_PATH.'/configs/pay/'.$jieqiPayset[JIEQI_PAY_TYPE]['pubkeyfile'];
	$xmlKey = new KeyFile($filename);
	$xmlKey->getPublicKey($modulus, $exp);
	$sign = new Sign();
	$sign->setPublicKeyFromXML($exp, $modulus);
	if (!$sign->VerifySign($param, $signData)) jieqi_printfail($jieqiLang['pay']['return_checkcode_error']);
	else{
		include_once($jieqiModules['pay']['path'].'/class/paylog.php');
		$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
		$orderid=intval($transid);
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
}else{
	jieqi_printfail($jieqiLang['pay']['pay_return_error'].'<br />'.$mesg);
}
?>