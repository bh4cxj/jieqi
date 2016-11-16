<?php 
/**
 * 埃文手机声讯-充值码确认
 *
 * 埃文手机声讯-充值码确认 (http://www.ivansms.com)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ivancallconfirm.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'ivancall');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_loadlang(JIEQI_PAY_TYPE, JIEQI_MODULE_NAME);
jieqi_checklogin();
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$phone=trim($_POST['phone']);

include_once($jieqiModules['pay']['path'].'/class/paylog.php');
$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
$criteria=new CriteriaCompo();
$criteria->add(new Criteria('buyinfo', $phone));
$criteria->add(new Criteria('payflag', -2));
$paylog_handler->queryObjects($criteria);

$payary=array();
$i=0;
while($paylog = $paylog_handler->getObject()){
	$payary[$i]['payid']=$paylog->getVar('payid');
	$payary[$i]['money']=$paylog->getVar('money');
	$payary[$i]['egold']=$paylog->getVar('egold');
	$i++;
}

if(count($payary)>0){
	$money=0;
	$egold=0;
	$pids='';
	foreach($payary as $v){
		$money += $v['money'];
		$egold += $v['egold'];
		if(!empty($pids)) $pids.=', ';
		$pids.=$v['payid'];
	}
	
	$buyname=$_SESSION['jieqiUserName'];
	$buyid=$_SESSION['jieqiUserId'];

	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	if(isset($jieqiPayset[JIEQI_PAY_TYPE]['scorerate'])) $addscore = ceil($egold * $jieqiPayset[JIEQI_PAY_TYPE]['scorerate']);
	else $addscore = 0;
	
	$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $addscore);
	
	$sql="UPDATE ".jieqi_dbprefix('pay_paylog')." SET buyid='".intval($buyid)."', buyname='".jieqi_dbslashes($buyname)."', rettime='".intval(JIEQI_NOW_TIME)."', payflag='1' WHERE payid IN (".$pids.')';
	$ret=$paylog_handler->db->query($sql);
	
	if(!$ret) jieqi_printfail($jieqiLang['pay']['save_paylog_failure']);
	jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['pay']['call_cinfirm_success'], jieqi_htmlstr($buyname), sprintf('%0.2f', $money / 100), $egold, JIEQI_EGOLD_NAME));
}else jieqi_printfail($jieqiLang['pay']['no_buy_record']);

?>