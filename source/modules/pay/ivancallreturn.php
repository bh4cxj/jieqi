<?php 
/**
 * 埃文手机声讯-返回处理
 *
 * 埃文手机声讯-返回处理 (http://www.ivansms.com)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ivancallreturn.php 234 2008-11-28 01:53:06Z juny $
 */

//手机打电话，接收验证码，用户在网页上输入验证码

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'ivancall');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$logflag = 0; //是否记录日志

//ret.php?calling=13912345678&called=-1-2-5-9-0467422&stime=2008-05-22%2014:35:36&etime=2008-05-22%2014:40:39&fee=500

$calling=trim($_REQUEST['calling']); //用户上行手机号
$fee=intval(trim($_REQUEST['fee'])); //支付金额（分）
$stime=trim($_REQUEST['stime']); //通话开始时间
$etime=trim($_REQUEST['etime']); //通话结束时间
$called=trim($_REQUEST['called']); //未知参数

$pwd=trim($_REQUEST['pwd']); //提交MD5加密后的数据密钥 防止非法URL提交数据


//**********************************************************
//记录日志
if($logflag){
	$tmpvar=print_r($_REQUEST, true);
	jieqi_writefile(JIEQI_ROOT_PATH.'/cache/ivancallrecv.txt',$tmpvar,'ab');
}

//分发中心常规检测,此处无需修改
if($calling == '13000000000'){
	echo 'OK';
	exit;
}

//信息校验
if(strtolower($pwd) != md5($calling.$jieqiPayset[JIEQI_PAY_TYPE]['paykey'])){
	if($logflag){
		$tmpvar='check error: called='.$called.'; pwd='.$pwd.'; check='.md5($calling.$jieqiPayset[JIEQI_PAY_TYPE]['paykey'].$called)."\r\n";
		jieqi_writefile(JIEQI_ROOT_PATH.'/cache/ivancallerr.txt',$tmpvar,'ab');
	}
	echo 'dberror';
	exit;
}

//禁止的手机号码
if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['denyphone']) && in_array($calling, $jieqiPayset[JIEQI_PAY_TYPE]['denyphone'])){
	if($logflag){
		$tmpvar='phone number denied: Usernumber='.$calling.";\r\n";
		jieqi_writefile(JIEQI_ROOT_PATH.'/cache/ivancallerr.txt',$tmpvar,'ab');
	}
	echo 'OK';
	exit;
}


//根据支付金额计算虚拟币
if(!isset($jieqiPayset[JIEQI_PAY_TYPE]['egoldrate'])) $jieqiPayset[JIEQI_PAY_TYPE]['egoldrate']=0;
$egold = ceil($fee * $jieqiPayset[JIEQI_PAY_TYPE]['egoldrate']);
$note = $stime.' '.$etime;

include_once(JIEQI_ROOT_PATH.'/modules/pay/class/paylog.php');
$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');

$criteria=new CriteriaCompo();
$criteria->add(new Criteria('buyinfo', $phone));
$criteria->add(new Criteria('note', $note));
$criteria->add(new Criteria('paytype', JIEQI_PAY_TYPE));
$paylog_handler->queryObjects($criteria);
$paylog = $paylog_handler->getObject();
if(is_object($paylog)){
	echo 'OK';
	exit;
}

$paylog= $paylog_handler->create();
$paylog->setVar('siteid', JIEQI_SITE_ID);
$paylog->setVar('buytime', JIEQI_NOW_TIME);
$paylog->setVar('rettime', 0);
$paylog->setVar('buyid', 0);
$paylog->setVar('buyname', '');
$paylog->setVar('buyinfo', $calling);
$paylog->setVar('moneytype', $jieqiPayset[JIEQI_PAY_TYPE]['moneytype']);
$paylog->setVar('money', $fee);
$paylog->setVar('egoldtype', $jieqiPayset[JIEQI_PAY_TYPE]['paysilver']);
$paylog->setVar('egold', $egold);
$paylog->setVar('paytype', JIEQI_PAY_TYPE);
$paylog->setVar('retserialno', '');
$paylog->setVar('retaccount', $called);
$paylog->setVar('retinfo', $called);
$paylog->setVar('masterid', 0);
$paylog->setVar('mastername', '');
$paylog->setVar('masterinfo', '');
$paylog->setVar('note', $note);
$paylog->setVar('payflag', -2);
$paylog_handler->insert($paylog);

$serialno=$paylog->getVar('payid', 'n');
$retstr='serialno:'.$serialno.' egold:'.$egold.' called:'.$called;
if($logflag){
	$tmpvar=$retstr."\r\n";
	jieqi_writefile(JIEQI_ROOT_PATH.'/cache/ivancallret.txt',$tmpvar,'ab');
}
echo 'OK';
exit;

?>