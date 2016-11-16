<?php 
/**
 * 19pay支付-提交处理
 *
 * 19pay支付-提交处理 (http://www.19pay.com)
 * 
 * 调用模板：/modules/pay/templates/19pay.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: 19pay.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', '19pay');
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
		$amount=$money / 100;
		$version_id = $jieqiPayset[JIEQI_PAY_TYPE]['version_id']; //接口版本号
		$merchant_id = $jieqiPayset[JIEQI_PAY_TYPE]['payid'];  //商户编号
		$order_date = date('Ymd'); //订单日期
		$order_id = $paylog->getVar('payid');     //订单编号[商户网站]
		$currency = $jieqiPayset[JIEQI_PAY_TYPE]['currency'];    //货币类型 RMB为人民币
		$returl = $jieqiPayset[JIEQI_PAY_TYPE]['payreturn'];   //商家接受支付结果的URL
		$order_name = urlencode(JIEQI_EGOLD_NAME);
		$user_name = urlencode($_SESSION['jieqiUserName']);
		$user_email = $jieqiPayset[JIEQI_PAY_TYPE]['pemail'];
		$merchant_key = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];  //私钥值。即商户登录19pay快钱系统后设定的 商户密钥
		$pm_id='';
		$pc_id='';
		//注意正确的参数串拼凑顺序
		$text="version_id=".$version_id."&merchant_id=".$merchant_id."&order_date=".$order_date."&order_id=".$order_id."&amount=".$amount."&currency=".$currency."&returl=".$returl."&pm_id=".$pm_id."&pc_id=".$pc_id."&merchant_key=".$merchant_key;
		//md5加密
		$verifystring = md5($text); //对参数串进行私钥加密取得值

		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign('url_pay', $jieqiPayset[JIEQI_PAY_TYPE]['payurl']);
		$jieqiTpl->assign('buyname', $_SESSION['jieqiUserName']);
		$jieqiTpl->assign('egold', $_REQUEST['egold']);
		$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
		$jieqiTpl->assign('money', sprintf('%0.2f', $money / 100));

		$jieqiTpl->assign('version_id', $version_id);
		$jieqiTpl->assign('merchant_id', $merchant_id);
		$jieqiTpl->assign('order_date', $order_date);
		$jieqiTpl->assign('order_id', $order_id);
		$jieqiTpl->assign('amount', $amount);
		$jieqiTpl->assign('currency', $currency);
		$jieqiTpl->assign('returl', $returl);
		$jieqiTpl->assign('order_name', $order_name);
		$jieqiTpl->assign('user_name', $user_name);
		$jieqiTpl->assign('user_email', $user_email);
		$jieqiTpl->assign('verifystring', $verifystring);
		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
         	foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $jieqiTpl->assign($k, $v);
		}
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/19pay.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
	}
}else{
	jieqi_printfail($jieqiLang['pay']['need_buy_type']);
}

?>