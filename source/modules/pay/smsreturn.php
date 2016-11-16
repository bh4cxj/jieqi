<?php 
/**
 * SMS支付-返回处理
 *
 * SMS支付-返回处理
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: smsreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'sms');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$mycorpid=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //企业编码
$mydest=$jieqiPayset[JIEQI_PAY_TYPE]['mydest']; //短信特服号
$emoney=$jieqiPayset[JIEQI_PAY_TYPE]['emoney']; //扣取的钱（分）
$egold=$jieqiPayset[JIEQI_PAY_TYPE]['egold']; //默认虚拟货币
$ptype=$jieqiPayset[JIEQI_PAY_TYPE]['ptype']; //接收方手机类型。1=移动手机，2=联通手机
$sid=$jieqiPayset[JIEQI_PAY_TYPE]['sid']; //1315 小说点播阅读 1000 免费帮助
$mtype=$jieqiPayset[JIEQI_PAY_TYPE]['mtype']; //0=免费信息，1=按条收费短信，2=正常包月短信，3=包月话单
$fmt=$jieqiPayset[JIEQI_PAY_TYPE]['fmt']; //信息编码。1=GB, 2=ASCII, 3=Binary, 4=UCS2. 默认值是GB. (Binary, UCS2暂时不支持)
$uflag=$jieqiPayset[JIEQI_PAY_TYPE]['uflag']; //0=普通信息（默认），1=注册信息，2=注销信息。对于定制业务，当用户注册使用该服务时，uflag=1; 当用户取消使用该服务时，uflag=2;其它uflag=0。点播业务uflag=0.
$corpid=trim($_REQUEST['corpid']);  //企业编码
$dest=trim($_REQUEST['dest']);  //接收号码，以企业接入码开始
$src=trim($_REQUEST['src']);  //手机号
$msg=trim($_REQUEST['msg']);  //上行短信内容（URLEncoded），用户序号
$linkid=trim($_REQUEST['linkid']);

$egold=intval($egold);
$emoney=intval($emoney);
$money=$emoney;

if($mycorpid != $corpid){
	echo 'result:-1;invalid corpid'; //企业编码错误
	exit;
}
if($mydest != $dest){
	echo 'result:-2;invalid dest';  //短信特服号错误
	exit;
}
if(!is_numeric($src)){
	echo 'result:-3;invalid src'; //手机号码错误
	exit;
}

$jieqiPayset['sms']['startmsg']=strtoupper($jieqiPayset['sms']['startmsg']);
$startlen=strlen($jieqiPayset['sms']['startmsg']);
$uid=substr($msg,$startlen);

$flagstr=strtoupper(substr($msg,0,$startlen));
if($flagstr != $jieqiPayset['sms']['startmsg']){
	echo 'result:-5;invalid msg'; //消息格式错误(起始标志错误)
	exit;
}

if(!is_numeric($uid)){
	echo 'result:-5;invalid msg'; //消息格式错误(用户ID错误)
	exit;
}

include_once($jieqiModules['pay']['path'].'/class/paylog.php');
$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
$jieqiPayset[JIEQI_PAY_TYPE]['daymsg']=intval($jieqiPayset[JIEQI_PAY_TYPE]['daymsg']);
//每天最多信息数
if(!empty($jieqiPayset[JIEQI_PAY_TYPE]['daymsg'])){
	$starttime=mktime(0,0,0,date('m'),date('d'),date('Y'));
	$criteria=new CriteriaCompo();
	$criteria->add(new Criteria('buyid', $uid, '='));
	$criteria->add(new Criteria('nuytime', $starttime, '>='));
	$msgcount=$paylog_handler->getCount($criteria);
	if($msgcount >= $jieqiPayset[JIEQI_PAY_TYPE]['daymsg']){
		echo 'result:-5;invalid msg'; //消息格式错误(起始标志错误)
		exit;
	}
}

include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$userobj=$users_handler->get($uid);

if(!is_object($userobj)){
	echo 'result:-5;invalid msg'; //消息格式错误(用户不存在)
	exit;
}


$url=$jieqiPayset['sms']['payurl'].'?corpid='.$corpid.'&dest='.$src.'&src='.$dest.'&feeid='.$src.'&ptype='.$ptype.'&sid='.$sid.'&fee='.$emoney.'&mtype='.$mtype.'&fmt=1&uflag='.$uflag.'&linkid='.$linkid;

if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
	foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $url.='&'.urlencode($k).'='.urlencode($v);
}

$paylog= $paylog_handler->create();
$paylog->setVar('siteid', JIEQI_SITE_ID);
$paylog->setVar('buytime', JIEQI_NOW_TIME);
$paylog->setVar('rettime', 0);
$paylog->setVar('buyid', $userobj->getVar('uid', 'n'));
$paylog->setVar('buyname', $userobj->getVar('uname', 'n'));
$paylog->setVar('buyinfo', '');
$paylog->setVar('moneytype', $jieqiPayset[JIEQI_PAY_TYPE]['moneytype']);
$paylog->setVar('money', $emoney);
$paylog->setVar('egoldtype', $jieqiPayset[JIEQI_PAY_TYPE]['paysilver']);
$paylog->setVar('egold', $egold);
$paylog->setVar('paytype', JIEQI_PAY_TYPE);
$paylog->setVar('retserialno', '');
$paylog->setVar('retaccount', '');
$paylog->setVar('retinfo', '');
$paylog->setVar('masterid', 0);
$paylog->setVar('mastername', '');
$paylog->setVar('masterinfo', '');
$paylog->setVar('note', $url);
$paylog->setVar('payflag', 0);
$paylog_handler->insert($paylog);

$serialno=$paylog->getVar('payid','n');
$buyid=$userobj->getVar('uid', 'n');
$buyname=$userobj->getVar('uname', 'n');
$msg=str_replace(array('<{$userid}>', '<{$username}>', '<{$egold}>', '<{$serialno}>'), array($buyid, $buyname, $egold, $serialno), $jieqiPayset[JIEQI_PAY_TYPE]['message']);
$url.='&msg='.urlencode($msg);
$ret=file_get_contents($url);
$ret=trim($ret);
$tmpary0=explode(';', $ret);
if(!empty($tmpary0[0])){
	$tmpary1=explode(':', trim($tmpary0[0]));
}
$cot=count($tmpary1);
if($cot>1 && is_numeric($tmpary1[$cot-1]) && $tmpary1[$cot-1]>=0){
	$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold]);
	if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
	else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
	$paylog->unsetNew();
	$paylog->setVar('rettime', JIEQI_NOW_TIME);
	$paylog->setVar('money', $money);
	$paylog->setVar('note', $note);
	$paylog->setVar('payflag', 1);
	$paylog_handler->insert($paylog);
	echo 'result:'.$serialno.';success';
	exit;
}else{
	$paylog->unsetNew();
	$paylog->setVar('retinfo', $ret);
	$paylog->setVar('payflag', -1);
	$paylog_handler->insert($paylog);
	echo $ret;
	exit;
}
?>