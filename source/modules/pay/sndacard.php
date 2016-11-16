<?php 
/**
 * 盛大卡支付-提交处理
 *
 * 盛大卡支付-提交处理 (http://www.snda.com.cn)
 * 
 * 调用模板：/modules/pay/templates/sndacard.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: sndacard.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'sndacard');
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
		$amount = sprintf('%0.2f', $money / 100); //金额。必须为两位小数
		$action = $jieqiPayset[JIEQI_PAY_TYPE]['payurl']; //盛大卡支付地址
		$merid = $jieqiPayset[JIEQI_PAY_TYPE]['payid'];  //商户编号
		$sdorderno = $paylog->getVar('payid'); //定单号，<=16位 
		$bizcode = $jieqiPayset[JIEQI_PAY_TYPE]['bizcode']; //点卡支付方式
		$orderdate = date("Ymd"); //订单日期
		$callbackaddr = $jieqiPayset[JIEQI_PAY_TYPE]['payreturn'];  //返回地址
		$callbacktype = $jieqiPayset[JIEQI_PAY_TYPE]['callbacktype']; //返回地址样式 01：URL  02：Web Service  03：Socket
		$ex1 = $jieqiPayset[JIEQI_PAY_TYPE]['ex1']; //备注1
		$ex2 = $jieqiPayset[JIEQI_PAY_TYPE]['ex2']; //备注2
		//校验字符串
		$signstr=$sdorderno."||".$amount."||".$merid."||".$bizcode."||".$orderdate."||".$callbackaddr."||".$callbacktype."||".$ex1."||".$ex2;
		$signmsg=trim(file_get_contents($jieqiPayset[JIEQI_PAY_TYPE]['signurl'].'?signstr='.urlencode($signstr).'&checkstr='.$jieqiPayset[JIEQI_PAY_TYPE]['checkstr']));
		
		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign('url_pay', $jieqiPayset[JIEQI_PAY_TYPE]['payurl']);
		$jieqiTpl->assign('buyname', $_SESSION['jieqiUserName']);
		$jieqiTpl->assign('egold', $_REQUEST['egold']);
		$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
		$jieqiTpl->assign('money', sprintf('%0.2f', $money / 100));

		$jieqiTpl->assign('sdorderno', $sdorderno);
		$jieqiTpl->assign('amount', $amount);
		$jieqiTpl->assign('merid', $merid);
		$jieqiTpl->assign('bizcode', $bizcode);
		$jieqiTpl->assign('orderdate', $orderdate);
		$jieqiTpl->assign('callbackaddr', $callbackaddr);
		$jieqiTpl->assign('callbacktype', $callbacktype);
		$jieqiTpl->assign('signmsg', $signmsg);
		$jieqiTpl->assign('ex1', $ex1);
		$jieqiTpl->assign('ex2', $ex2);
		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
         	foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $jieqiTpl->assign($k, $v);
		}
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/sndacard.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
	}
}else{
	jieqi_printfail($jieqiLang['pay']['need_buy_type']);
}

?>