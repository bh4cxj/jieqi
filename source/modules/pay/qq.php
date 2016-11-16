<?php 
/**
 * QQ支付-提交处理
 *
 * QQ支付-提交处理 (http://www.qq.com)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: qq.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'qq');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
if(!jieqi_checklogin(true)) jieqi_printfail($jieqiLang['pay']['need_login']);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

if(isset($_REQUEST['egold']) && is_numeric($_REQUEST['egold']) && $_REQUEST['egold']>0){
	$_REQUEST['egold']=intval($_REQUEST['egold']);
	if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'])){
		if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$_REQUEST['egold']])) $money=intval($jieqiPayset[JIEQI_PAY_TYPE]['paylimit'][$_REQUEST['egold']] * 100);
		else jieqi_printfail($jieqiLang['pay']['buy_type_error']);
	}else{
		$money=intval($_REQUEST['egold']);
	}
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$paylog= $paylog_handler->create();
	$paylog->setVar('siteid', JIEQI_SITE_ID);
	$paylog->setVar('buytime', JIEQI_NOW_TIME);
	$paylog->setVar('rettime', 0);
	$paylog->setVar('buyid', $_SESSION['jieqiUserId']);
	$paylog->setVar('buyname', $_SESSION['jieqiUserName']);
	$paylog->setVar('buyinfo', '');
	$paylog->setVar('moneytype', $jieqiPayset[JIEQI_PAY_TYPE]['moneytype']);
	$paylog->setVar('money', $money);
	$paylog->setVar('egoldtype', $jieqiPayset[JIEQI_PAY_TYPE]['paysilver']);
	$paylog->setVar('egold', $_REQUEST['egold']);
	$paylog->setVar('paytype', JIEQI_PAY_TYPE);
	$paylog->setVar('retserialno', '');
	$paylog->setVar('retaccount', '');
	$paylog->setVar('retinfo', '');
	$paylog->setVar('masterid', 0);
	$paylog->setVar('mastername', '');
	$paylog->setVar('masterinfo', '');
	$paylog->setVar('note', '');
	$paylog->setVar('payflag', 0);
	if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['add_paylog_error']);
	else{
		$cpid = $jieqiPayset[JIEQI_PAY_TYPE]['payid'];  //服务商标识
		$service_id = $jieqiPayset[JIEQI_PAY_TYPE]['service_id']; //业务代码
		$user_id = $jieqiPayset[JIEQI_PAY_TYPE]['user_id']; //业务承载号码（可选参数）
		$user_type = $jieqiPayset[JIEQI_PAY_TYPE]['user_type']; //业务承载号码的类型，1－qq号码2－财付通帐号100－cp自己的号码
		$pay_type = $jieqiPayset[JIEQI_PAY_TYPE]['pay_type'];  //支付号码类型，用户在CP的网站上选择1－q币支付2－财付通支付
		$fee = $money; //金额，仅当service_id为免鉴权业务代码时，CP传过来的Fee有效
		$source = $jieqiPayset[JIEQI_PAY_TYPE]['source']; //请求来源1－cp网站2－财付空间portal
		$from = $jieqiPayset[JIEQI_PAY_TYPE]['from']; //外部请求来源（用于统计推广网站对财付空间的推广作用）
		$time = time(); //CP生成的一个当前时间戳（unix timestamp）
		$ret_url = $jieqiPayset[JIEQI_PAY_TYPE]['payreturn']; //定购成功时，回跳的url
		$ret_para = $paylog->getVar('payid'); //回调CP的Url时，附在后面的参数（可选参数）
		$key = ''; //上述参数的rsa签名

		$post_url = $jieqiPayset[JIEQI_PAY_TYPE]['payurl'];   //提交的URL
		$cmd_line=$jieqiPayset[JIEQI_PAY_TYPE]['cmd_line'];  //生成验证码程序路径

		$post_para='cpid='.$cpid.'&service_id='.$service_id.'&user_id='.$user_id.'&user_type='.$user_type.'&pay_type='.$pay_type.'&fee='.$fee.'&source='.$source.'&from='.$from.'&time='.$time.'&ret_url='.$ret_url.'&ret_para='.$ret_para;

		$cmd =  $cmd_line. ' s "' . $post_para .'"';

		$fp = popen($cmd, "r");
		$sign='';
		while(!feof($fp)){
			$sign .= fgets($fp,1024);
		}
		pclose($fp);
		$sign=trim($sign);

		$post_url.='?'.$post_para.'&key='.$sign;
		
		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
			foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $post_url.='&'.urlencode($k).'='.urlencode($v);
		}

		header('Location: '.$post_url);
		exit;
	}
}else{
	jieqi_printfail($jieqiLang['pay']['need_buy_type']);
}

?>