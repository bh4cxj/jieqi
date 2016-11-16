<?php 
/**
 * 网盈一号通-返回处理
 *
 * 网盈一号通-返回处理 （http://www.vnetone.com）
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: vnetonereturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'vnetone');
require_once('../../global.php');
//jieqi_checklogin();
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$spid=$jieqiPayset[JIEQI_PAY_TYPE]['payid'];  //商户SP号码 5位
$sppwd=$jieqiPayset[JIEQI_PAY_TYPE]['paykey'];//商户SP密钥 18位
$rtmd5=$_GET['v1'];//'V币服务器MD5 
$trka=$_GET['v2'];// 'V币号码15位
$rtmi=$_GET['v3'];//'V币密码6位 （可能为空 老V币没有密码）
$rtmz=$_GET['v4'];//'面值 1-999 整数面值
$rtlx=$_GET['v5'];//'卡的类型1，2，3 。  1:正式卡 2：测试卡 3 ：促销卡
$rtoid=$_GET['v6'];//盈华服务器的订单
$rtcoid=$_GET['v7'];//客户端订单
$rtuserid=$_GET['v8'];//用户ID
$rtcustom=$_GET['v9'];//商户自定义字段
$rtflag=$_GET['v10'];//'返回状态. 1为正常发送回来 2为补单发送回来

$get_key=strtoupper(md5($trka.$rtmi.$rtoid.$spid.$sppwd.$rtcoid.$rtflag.$rtmz));
//'卡+密+vpay的订单 + 5位spid+ 18位SP密码+客户订单+rtflag返回类型1或2+支付金额

if($rtflag != 1 && $rtflag != 2){
	jieqi_printfail(sprintf($jieqiLang['pay']['pay_failure_message'],''));
}elseif($rtmd5 != $get_key){
	jieqi_printfail($jieqiLang['pay']['return_checkcode_error']);
}else{
	header("Data-Received:ok_vpay8");  //给用户加点成功后发送此消息给盈华服务器。不能去掉此句。
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$orderid=intval($rtcoid);
	$paylog=$paylog_handler->get($orderid);
	if(is_object($paylog)){
		$buyname=$paylog->getVar('buyname');
		$buyid=$paylog->getVar('buyid');
		$payflag=$paylog->getVar('payflag');
		$egold=$paylog->getVar('egold');
		
		//可能返回的金额跟提交的不同
		$moneyary=array_flip($jieqiPayset[JIEQI_PAY_TYPE]['paylimit']);
		if(isset($moneyary[$rtmz])) $egold = $moneyary[$rtmz];
		
		if($payflag == 0){
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$uservip=1; //默认的vip等级
			$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold], $uservip);
			if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
			else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
			$paylog->setVar('rettime', JIEQI_NOW_TIME);
			$paylog->setVar('retserialno', $rtoid);
			$paylog->setVar('retaccount', $trka);
			$paylog->setVar('retinfo', $rtmi);
			$paylog->setVar('egold', $egold);
			$paylog->setVar('money', $rtmz);
			$paylog->setVar('note', $note);
			$paylog->setVar('payflag', 1);
			if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['save_paylog_failure']);
			else jieqi_msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
		}else{
			jieqi_msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
		}
	}else{
		jieqi_printfail($jieqiLang['pay']['no_buy_record']);
	}
}

?>