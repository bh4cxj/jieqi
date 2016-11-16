<?php 
/**
 * 九州网联116电话支付-返回处理
 *
 * 九州网联116电话支付-返回处理 (http://www.116.com.cn)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: 9zfeereturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', '9zfee');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_loadlang('9zfee', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$mycpid=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //CP编号
$key=$jieqiPayset[JIEQI_PAY_TYPE]['paykey']; //密钥

//1-----------接收回的信息--------------------------------------------------------------------
$cp_id=trim($_REQUEST['cp_id']);
$prod_id=trim($_REQUEST['prod_id']);
$rand_id=trim($_REQUEST['rand_id']);
$pay_id=trim($_REQUEST['pay_id']);
$valid_time=trim($_REQUEST['valid_time']);
$money_type=trim($_REQUEST['money_type']);
$price=trim($_REQUEST['price']);
$result=trim($_REQUEST['result']);
$md5=substr(trim($_REQUEST['md5']),0,32);

//2-----------重新计算md5的值---------------------------------------------------------------------------
$md5string=md5($cp_id.$prod_id.$rand_id.$pay_id.$valid_time.$money_type.$price.$result.$key);

//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------
if($mycpid != $cp_id){
	echo '<!--1-->';
	jieqi_printfail($jieqiLang['pay']['error_cp_num']);
}elseif($result != '0'){
	echo '<!--1-->';
	jieqi_printfail($jieqiLang['pay']['pay_return_error']);
}elseif(strtoupper($md5string) != strtoupper($md5)){
	echo '<!--5-->';
	jieqi_printfail($jieqiLang['pay']['return_checkcode_error']);
}else{
	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$orderid=intval($rand_id);
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
			$paylog->setVar('money', intval($price * 100));
			$paylog->setVar('note', $note);
			$paylog->setVar('payflag', 1);
			if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['save_paylog_failure']);
			else{
				echo '<!--0-->';
				jieqi_msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
			}
		}else{
			echo '<!--0-->';
			jieqi_printfail($jieqiLang['pay']['already_add_egold']);
		}
	}else{
		echo '<!--3-->';
		jieqi_printfail($jieqiLang['pay']['no_buy_record']);
	}
}
?>