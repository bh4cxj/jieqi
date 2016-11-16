<?php 
/**
 * 盛大卡支付-返回处理
 *
 * 盛大卡支付-返回处理 (http://www.snda.com.cn)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: sndacardreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'sndacard');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_loadlang('snda', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$mymerid=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号
$key=$jieqiPayset[JIEQI_PAY_TYPE]['paykey']; //密钥

//1-----------接收回的信息--------------------------------------------------------------------
$sdorderno=trim($_REQUEST['sdorderno']);
$amount=trim($_REQUEST['amount']);
$status=trim($_REQUEST['status']);
$merid=trim($_REQUEST['merid']);
$bizcode=trim($_REQUEST['bizcode']);
$transdate=trim($_REQUEST['transdate']);
$cardbalance=trim($_REQUEST['cardbalance']);
$ex1=trim($_REQUEST['ex1']);
$ex2=trim($_REQUEST['ex2']);
$signmsg=trim($_REQUEST['signmsg']);

if($status != '01'){
    if($status == '-1002') $retstr=$jieqiLang['pay']['error_card_password'];
	elseif($status == '-1003') $retstr=$jieqiLang['pay']['error_card_used'];
	elseif($status == '-2004') $retstr=$jieqiLang['pay']['error_card_cant'];
	elseif($status == '-1') $retstr=$jieqiLang['pay']['error_system'];
	else $retstr=$jieqiLang['pay']['error_unknow'];
	
	echo $jieqiPayset[JIEQI_PAY_TYPE]['showurl'].'?retcode='.$status;
	exit;
}

if($mymerid != $merid){
	echo $jieqiPayset[JIEQI_PAY_TYPE]['showurl'].'?retcode=-101';
	exit;
}

//2-----------重新计算md5的值---------------------------------------------------------------------------
$signstr=$sdorderno.'||'.$amount.'||'.$status.'||'.$merid.'||'.$bizcode.'||'.$transdate.'||'.$cardbalance.'||'.$ex1.'||'.$ex2;

$checkcode=trim(file_get_contents($jieqiPayset[JIEQI_PAY_TYPE]['verifyurl'].'?signstr='.urlencode($signstr).'&signmsg='.urlencode($signmsg).'&checkstr='.$jieqiPayset[JIEQI_PAY_TYPE]['checkstr']));



//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------
if($checkcode != "1"){
	echo $jieqiPayset[JIEQI_PAY_TYPE]['showurl'].'?retcode=-102';
	exit;
}else{     	//---------如果签名验证成功！
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$orderid=intval($sdorderno);
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
			if(!$paylog_handler->insert($paylog)){
				echo $jieqiPayset[JIEQI_PAY_TYPE]['showurl'].'?retcode=-104';
	    		exit;
			}
		}
		echo $jieqiPayset[JIEQI_PAY_TYPE]['showurl'].'?retcode=1&buyname='.urlencode($buyname).'&egold='.urlencode($egold);
	    exit;
	}else{
		echo $jieqiPayset[JIEQI_PAY_TYPE]['showurl'].'?retcode=-103';
	    exit;
	}
}
?>