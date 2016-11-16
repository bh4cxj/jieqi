<?php 
/**
 * 易付通-提交处理
 *
 * 易付通-提交处理 （http://www.xpay.cn）
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: xpay.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'xpay');
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
	    $tid = $jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号，改成XPAY负责分配给商户的编号
	    $key = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];  //默认的私钥值，更改私钥后要修改这里
	    $pdt = $jieqiPayset[JIEQI_PAY_TYPE]['pdt'];          //商品名称
	    $bid = $paylog->getVar('payid'); //定单号，不能重复  
	    //$prc 定单金额，单位元，小数点后保留两位
		$prc=sprintf('%0.2f', $money / 100);
		//支付方式，默认使用银行
		$cardarray=array('bank','unicom',JIEQI_PAY_TYPE,'ebilling','ibank');
		if(empty($card) || !in_array($cardarray, $card)) $card='bank';
		//开通的支付方式，可以选择一种或多种
		$scard=$jieqiPayset[JIEQI_PAY_TYPE]['scard'];  
		//交易码,用于标识交易,目前支持sell
        $actioncode=$jieqiPayset[JIEQI_PAY_TYPE]['actioncode'];
		//当 actioncode="sell" ,此参数为空
		$actionParameter=$jieqiPayset[JIEQI_PAY_TYPE]['actionParameter'];
		//版本号,当前系统请使用2.0
		$ver=$jieqiPayset[JIEQI_PAY_TYPE]['ver'];
		//商品类型，可以为空
		$type=$jieqiPayset[JIEQI_PAY_TYPE]['type'];
        //语言，目前只支持gb2312
        $lang=$jieqiPayset[JIEQI_PAY_TYPE]['lang'];
		//支付者，可以为空
		$username=urlencode($_SESSION['jieqiUserName']);
		//支付成功返回地址,改成您自己的网站地址
		$url=$jieqiPayset[JIEQI_PAY_TYPE]['payreturn'];
		//备注信息
		$remark1=$jieqiPayset[JIEQI_PAY_TYPE]['remark1'];
		//网站名称
		$sitename=$jieqiPayset[JIEQI_PAY_TYPE]['sitename'];
		//网站域名
		$siteurl=$jieqiPayset[JIEQI_PAY_TYPE]['siteurl'];

		//md为加密数据，加密顺序必须严格按此顺序
		$md=md5($key . ":" . $prc . "," . $bid . "," . $tid . "," . $card . "," . $scard . "," . $actioncode . "," . $actionParameter . "," . $ver);
        //  '测试时，请将http://pay.xpay.cn/pay.aspx改成http://pay.xpay.cn/testpay.aspx
		$redirect=$jieqiPayset[JIEQI_PAY_TYPE]['payurl']."?prc=" . $prc . "&bid=" . $bid . "&tid=" . $tid . "&card=" . $card . "&scard=" . $scard . "&actioncode=" . $actioncode . "&actionparameter=" . $actionParameter . "&ver=" . $ver . "&md=" . $md . "&username=" . $username . "&pdt=" . $pdt . "&type=" . $type . "&lang=" . $lang . "&remark1=" . $remark1 . "&url=" . $url . "&sitename=" . $sitename . "&siteurl=" . $siteurl . "";
		
		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
         	foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $redirect.='&'.urlencode($k).'='.urlencode($v);
         }

        header('Location: ' . $redirect);   
        exit;
	}
}else{
    jieqi_printfail($jieqiLang['pay']['need_buy_type']);	
}

?>