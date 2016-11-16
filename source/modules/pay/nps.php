<?php 
/**
 * nps支付-提交处理
 *
 * nps支付-提交处理 (http://www.nps.cn)
 * 
 * 调用模板：/modules/pay/templates/nps.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: nps.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'nps');
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
		$m_id		=	$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商家号
		$modate		=	date('Y-m-d H:i:s');  //交易日期
		$m_orderid	=	intval($paylog->getVar('payid'));    //定单号
		$m_oamount	=	sprintf('%0.2f', $money / 100); //订单金额,单位元
		$m_ocurrency=	$jieqiPayset[JIEQI_PAY_TYPE]['MOCurrency']; //货币类型
		$m_url		=	$jieqiPayset[JIEQI_PAY_TYPE]['payreturn']; //返回路径
		$m_language	=	$jieqiPayset[JIEQI_PAY_TYPE]['M_Language']; //语言
		$s_name		=	$_SESSION['jieqiUserName'];
		$s_addr		=	$jieqiPayset[JIEQI_PAY_TYPE]['S_Address'];
		$s_postcode	=	$jieqiPayset[JIEQI_PAY_TYPE]['_PostCode']='';  //邮编
		$s_tel		=	$jieqiPayset[JIEQI_PAY_TYPE]['S_Telephone']='';  //电话
		$s_eml		=	$jieqiPayset[JIEQI_PAY_TYPE]['S_Email']='';  //Email
		$r_name		=	$jieqiPayset[JIEQI_PAY_TYPE]['R_Name']='';  //收货人姓名
		$r_addr		=	$jieqiPayset[JIEQI_PAY_TYPE]['R_Address']='';  //收货地址
		$r_postcode	=	$jieqiPayset[JIEQI_PAY_TYPE]['R_PostCode']='';  //收货邮编
		$r_tel		=	$jieqiPayset[JIEQI_PAY_TYPE]['R_Telephone']='';  //收货电话
		$r_eml		=	$jieqiPayset[JIEQI_PAY_TYPE]['R_Email']='';  //收货Email
		$m_ocomment	=	$jieqiPayset[JIEQI_PAY_TYPE]['MOComment']='';  //备注

		$m_status	= 	$jieqiPayset[JIEQI_PAY_TYPE]['State']='0';  //支付状态

		//组织订单信息
		$m_info = $m_id."|".$m_orderid."|".$m_oamount."|".$m_ocurrency."|".$m_url."|".$m_language;
		$s_info = $s_name."|".$s_addr."|".$s_postcode."|".$s_tel."|".$s_eml;
		$r_info = $r_name."|".$r_addr."|".$r_postcode."|".$r_tel."|".$r_eml."|".$m_ocomment."|".$m_status."|".$modate;

		$OrderInfo = $m_info."|".$s_info."|".$r_info;

		//订单信息先转换成HEX，然后再加密
		$key = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];

		$OrderInfo = StrToHex($OrderInfo);
		$digest = strtoupper(md5($OrderInfo.$key));

		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign('url_pay', $jieqiPayset[JIEQI_PAY_TYPE]['payurl']);
		$jieqiTpl->assign('buyname', $_SESSION['jieqiUserName']);
		$jieqiTpl->assign('egold', $_REQUEST['egold']);
		$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
		$jieqiTpl->assign('money', $m_oamount);

		$jieqiTpl->assign('OrderMessage', $OrderInfo);
		$jieqiTpl->assign('digest', $digest);
		$jieqiTpl->assign('M_ID', $m_id);

		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
			foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $jieqiTpl->assign($k, $v);
		}
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/nps.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
	}
}else{
	jieqi_printfail($jieqiLang['pay']['need_buy_type']);
}

// 公共函数定义
function StrToHex($string)
{
	$hex="";
	for ($i=0;$i<strlen($string);$i++)
	$hex.=dechex(ord($string[$i]));
	$hex=strtoupper($hex);
	return $hex;
}
?>