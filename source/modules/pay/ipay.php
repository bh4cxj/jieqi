<?php 
/**
 * 中国在线支付-提交处理
 *
 * 中国在线支付-提交处理 (http://www.ipay.cn)
 * 
 * 调用模板：/modules/pay/templates/ipay.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ipay.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'ipay');
require_once('../../global.php');
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
		$v_mid     = $jieqiPayset[JIEQI_PAY_TYPE]['payid'];          //商户编号，改成IPAY负责分配给商户的编号
		$v_oid     = intval($paylog->getVar('payid'));    //定单号，由商户负责产生的数字编号，一天内不能重复
		$key       = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];          //默认的私钥值，更改私钥后要修改这里
		$v_email   = empty($jieqiPayset[JIEQI_PAY_TYPE]['v_email']) ? JIEQI_CONTACT_EMAIL : $jieqiPayset[JIEQI_PAY_TYPE]['v_email'];
		$v_mobile  = $jieqiPayset[JIEQI_PAY_TYPE]['v_mobile'];
		
		//$v_amount 定单金额，单位元，小数点后保留两位，如10或12.34，如果为整数，则不需要小数点
		if(($money % 100)>0){
			$v_amount=sprintf('%0.2f', $money / 100);  //金额
		}else{
			$v_amount=sprintf('%d', $money / 100);
		}
		$md5string = md5($v_mid.$v_oid.$v_amount.$v_email.$v_mobile.$key);

		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign('url_pay', $jieqiPayset[JIEQI_PAY_TYPE]['payurl']);
		$jieqiTpl->assign('buyname', $_SESSION['jieqiUserName']);
		$jieqiTpl->assign('egold', $_REQUEST['egold']);
		$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
		$jieqiTpl->assign('money', $v_amount);
		$jieqiTpl->assign('v_mid', $v_mid);
		$jieqiTpl->assign('v_oid', $v_oid);
		$jieqiTpl->assign('v_amount', $v_amount);
		$jieqiTpl->assign('v_email', $v_email);
		$jieqiTpl->assign('v_mobile', $v_mobile);
		$jieqiTpl->assign('md5string', $md5string);
		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
         	foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $jieqiTpl->assign($k, $v);
		}
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/ipay.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
	}
}else{
	jieqi_printfail($jieqiLang['pay']['need_buy_type']);
}

?>