<?php 
/**
 * 快钱支付第二版-提交处理
 *
 * 快钱支付第二版-提交处理 (http://www.99billv2.com)
 * 
 * 调用模板：/modules/pay/templates/99bill.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: 99billv2.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', '99billv2');
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
	
	//vip等级奖励虚拟币
	/*
	$extraegold=0;
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	$userobj=$users_handler->get($_SESSION['jieqiUserid']);
	if(is_object($userobj)) $uservip=intval($userobj->getVar('isvip','n'));
	else $uservip=0;
	if($uservip>0){
		jieqi_getconfigs('system', 'vips', 'jieqiVips');
		if(!empty($jieqiVips[$uservip]['extraegold']) && !empty($jieqiVips[$uservip]['extradiv'])){
			$extraegold=floor($_REQUEST['egold'] * $jieqiVips[$uservip]['extraegold'] / $jieqiVips[$uservip]['extradiv']);
		}
	}
	$_REQUEST['egold']+=$extraegold;
	*/
			
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
		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign('url_pay', $jieqiPayset[JIEQI_PAY_TYPE]['payurl']);
		$jieqiTpl->assign('buyname', $_SESSION['jieqiUserName']);
		$jieqiTpl->assign('egold', $_REQUEST['egold']);
		$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
		$jieqiTpl->assign('money', sprintf('%0.2f', $money / 100));
		
		$parmary=array();
		$parmary['inputCharset']=$jieqiPayset[JIEQI_PAY_TYPE]['inputCharset'];
		$parmary['pageUrl']=$jieqiPayset[JIEQI_PAY_TYPE]['payreturn'];
		$parmary['version']=$jieqiPayset[JIEQI_PAY_TYPE]['version'];
		$parmary['language']=$jieqiPayset[JIEQI_PAY_TYPE]['language'];
		$parmary['signType']=$jieqiPayset[JIEQI_PAY_TYPE]['signType'];
		$parmary['merchantAcctId']=$jieqiPayset[JIEQI_PAY_TYPE]['payid'];
		$parmary['payerName']=urlencode(JIEQI_SITE_NAME);
		$parmary['orderId']=$paylog->getVar('payid');
		$parmary['orderAmount']=$money;
		$parmary['payType']=$jieqiPayset[JIEQI_PAY_TYPE]['payType'];
		$parmary['fullAmountFlag']=$jieqiPayset[JIEQI_PAY_TYPE]['fullAmountFlag'];
		$parmary['orderTime']=date('YmdHis');
		$parmary['productName']=urlencode(JIEQI_EGOLD_NAME);
		$parmary['ext1']=urlencode($jieqiPayset[JIEQI_PAY_TYPE]['ext1']);
		$parmary['ext2']=urlencode($jieqiPayset[JIEQI_PAY_TYPE]['ext2']);
		
		$txtmac='';
		foreach($parmary as $k => $v){
			$jieqiTpl->assign($k, $v);
			if($v != ''){
				if($txtmac != '') $txtmac .= '&';
				$txtmac .= $k.'='.$v;
			}
		}
		if($txtmac != '') $txtmac .= '&';
		$txtmac .= 'key='.$jieqiPayset[JIEQI_PAY_TYPE]['paykey'];
		$signMsg = strtoupper(md5($txtmac));
		$jieqiTpl->assign('signMsg', $signMsg);
	
		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
         	foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $jieqiTpl->assign($k, $v);
		}
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/99billv2.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
	}
}else{
	jieqi_printfail($jieqiLang['pay']['need_buy_type']);
}

?>