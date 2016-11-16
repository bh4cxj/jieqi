<?php 
/**
 * 埃文手机短信-返回处理
 *
 * 埃文手机短信-返回处理 (http://www.ivansms.com)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ivansmsreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'ivansms');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

$logflag = 0; //是否记录日志

$Command=trim($_REQUEST['Command']); //Command = DELIVER 表示用户上行指令提交 Command = REPORT 表示用户计费状态报告指令提交
//pwd是分发中心和您之间的密钥，你在后台里配好密钥后，我们会将密钥连同串提交给您。您通过判断密钥是否合法来进行处理
$Pwd=trim($_REQUEST['pwd']); //提交MD5加密后的数据密钥 防止非法URL提交数据
$Usernumber=trim($_REQUEST['usernumber']); //用户上行手机号
$Spnumber=trim($_REQUEST['spnumber']); //用户上行指令
$gatename=trim($_REQUEST['gatename']); //网关名称，gatename=mobile表示移动,gamename=unicom表示联通
$linkid=trim($_REQUEST['linkid']); //运营商业务对帐IDlinkid,linkid由移动、联通生成并下发。数字类型。唯一不重复。
$report=intval(trim($_REQUEST['report'])); //计费状态报告，report=1表示计费成功,其它表示失败

//**********************************************************
//记录日志
if($logflag){
	$tmpvar=print_r($_REQUEST, true);
	jieqi_writefile(JIEQI_ROOT_PATH.'/cache/ivansmsrecv.txt',$tmpvar,'ab');
}

//分发中心常规检测,此处无需修改
if($Usernumber == '13000000000'){
	echo 'Success';
	exit;
}

//信息校验
if(strtolower($Command) != 'report' && $Pwd != md5($Usernumber.$jieqiPayset[JIEQI_PAY_TYPE]['paykey'].$linkid)){
	if($logflag){
		$tmpvar='check error: linkid='.$linkid.'; pwd='.$Pwd.'; check='.md5($Usernumber.$jieqiPayset[JIEQI_PAY_TYPE]['paykey'].$linkid)."\r\n";
		jieqi_writefile(JIEQI_ROOT_PATH.'/cache/ivansmserr.txt',$tmpvar,'ab');
	}
	echo 'error';
	exit;
}

//禁止的手机号码
if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['denyphone']) && in_array($Usernumber, $jieqiPayset[JIEQI_PAY_TYPE]['denyphone'])){
	if($logflag){
		$tmpvar='phone number denied: Usernumber='.$Usernumber.";\r\n";
		jieqi_writefile(JIEQI_ROOT_PATH.'/cache/ivansmserr.txt',$tmpvar,'ab');
	}
	echo 'error';
	exit;
}

//检查支付类型
$paytypeid=-1;
foreach($jieqiPayset[JIEQI_PAY_TYPE]['paytype'] as $k=>$v){
	if(strtolower($v['spnumber'])==strtolower($Spnumber)){
		$paytypeid=$k;
		break;
	}
}
if($paytypeid < 0) $paytypeid=0;


//分发中心每次计费流程会有二次数据提交到您的URL.对应不同的业务流程  第一次提交用户上行内容.第二次提交计费状态报告。都是以Get方式提交。
if(strtolower($Command) == 'deliver' && $linkid != ''){
	//第一次提交给您用户上行内容.表示用户交费短信已发送。

	//生成随机数
	$jieqiPayset[JIEQI_PAY_TYPE]['passlen']=intval($jieqiPayset[JIEQI_PAY_TYPE]['passlen']);
	if($jieqiPayset[JIEQI_PAY_TYPE]['passlen']<4 || $jieqiPayset[JIEQI_PAY_TYPE]['passlen']>32) $jieqiPayset[JIEQI_PAY_TYPE]['passlen']=8;
	$jieqiPayset[JIEQI_PAY_TYPE]['passtype']=intval($jieqiPayset[JIEQI_PAY_TYPE]['passtype']);
	if($jieqiPayset[JIEQI_PAY_TYPE]['passtype']<1 || $jieqiPayset[JIEQI_PAY_TYPE]['passtype']>3) $jieqiPayset[JIEQI_PAY_TYPE]['passtype']=3;
	$randstr=jieqi_makerand($jieqiPayset[JIEQI_PAY_TYPE]['passlen'],$jieqiPayset[JIEQI_PAY_TYPE]['passtype']);


	include_once($jieqiModules['pay']['path'].'/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$paylog= $paylog_handler->create();
	$paylog->setVar('siteid', JIEQI_SITE_ID);
	$paylog->setVar('buytime', JIEQI_NOW_TIME);
	$paylog->setVar('rettime', 0);
	$paylog->setVar('buyid', 0);
	$paylog->setVar('buyname', '');
	$paylog->setVar('buyinfo', $Usernumber);
	$paylog->setVar('moneytype', $jieqiPayset[JIEQI_PAY_TYPE]['moneytype']);
	$paylog->setVar('money', intval($jieqiPayset[JIEQI_PAY_TYPE]['paytype'][$paytypeid]['emoney']));
	$paylog->setVar('egoldtype', $jieqiPayset[JIEQI_PAY_TYPE]['paysilver']);
	$paylog->setVar('egold', intval($jieqiPayset[JIEQI_PAY_TYPE]['paytype'][$paytypeid]['egold']));
	$paylog->setVar('paytype', JIEQI_PAY_TYPE);
	$paylog->setVar('retserialno', '');
	$paylog->setVar('retaccount', $linkid);
	$paylog->setVar('retinfo', $linkid);
	$paylog->setVar('masterid', 0);
	$paylog->setVar('mastername', '');
	$paylog->setVar('masterinfo', '');
	$paylog->setVar('note', $randstr);
	$paylog->setVar('payflag', -1);
	$paylog_handler->insert($paylog);

	$serialno=$paylog->getVar('payid', 'n');
	
	$retstr=str_replace(array('<{$egold}>', '<{$serialno}>', '<{$randpass}>'), array($jieqiPayset[JIEQI_PAY_TYPE]['paytype'][$paytypeid]['egold'], $serialno, $randstr), $jieqiPayset[JIEQI_PAY_TYPE]['paytype'][$paytypeid]['message']);
	
	if($logflag){
		$tmpvar=$retstr."\r\n";
		jieqi_writefile(JIEQI_ROOT_PATH.'/cache/ivansmsret.txt',$tmpvar,'ab');
	}
	echo $retstr;
	exit;

}elseif(strtolower($Command) == 'report' && $linkid != '' && $report==1){
	//第二次提交运营商返回的状态报告.表示用户是否已计费成功。
	jieqi_includedb();
	$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	$query->execute("UPDATE ".jieqi_dbprefix('pay_paylog')." SET payflag=0 WHERE retinfo='".jieqi_dbslashes($linkid)."'");

	if($logflag){
		$tmpvar="UPDATE ".jieqi_dbprefix('pay_paylog')." SET payflag=0 WHERE retinfo='".jieqi_dbslashes($linkid)."'\r\n";
		jieqi_writefile(JIEQI_ROOT_PATH.'/cache/ivansmsret1.txt',$tmpvar,'ab');
	}
	
}else{
	if($logflag){
		$tmpvar='command error: linkid='.$linkid.'; Command='.$Command.'; report='.$report;
		jieqi_writefile(JIEQI_ROOT_PATH.'/cache/ivansmserr.txt',$tmpvar,'ab');
	}
	echo 'error';
	exit;
}

//生成随机代码 $mode 1-数字，2-小写字母, 3-数字和小写字母
function jieqi_makerand($length = 8, $mode = 1){
	$str1 = '1234567890';
	$str2 = 'abcdefghijklmnopqrstuvwxyz';
	$str3 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$str4 = '_';
	$str5 = '`~!@#$%^&*()-+=\\|{}[];:\'",./?';
	$str = '';
	$mode = intval($mode);
	if (($mode & 1)>0) $str.=$str1;
	if (($mode & 2)>0) $str.=$str2;
	if (($mode & 4)>0) $str.=$str3;
	if (($mode & 8)>0) $str.=$str4;
	if (($mode & 16)>0) $str.=$str5;
	$result = '';
	$l = strlen($str)-1;
	srand((double) microtime() * 1000000);
	for($i = 0;$i < $length; $i++){
		$num = rand(0, $l);
		$result .= $str[$num];
	}
	return $result;
}

?>