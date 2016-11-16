<?php 
/**
 * nps支付-返回处理
 *
 * nps支付-返回处理 (http://www.nps.cn)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: npsreturn.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'nps');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_loadlang('nps', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');
$v_mid = $jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号，改成IPAY负责分配给商户的编号
$key = $jieqiPayset[JIEQI_PAY_TYPE]['paykey'];  //默认的私钥值，更改私钥后要修改这里

//1-----------接收返回的信息--------------------------------------------------------------------
$m_id		= 	$_POST['m_id'];					//商家号
$m_orderid	= 	$_POST['m_orderid'];			//商家订单号
$m_oamount	= 	$_POST['m_oamount'];			//支付金额
$m_ocurrency= 	$_POST['m_ocurrency'];			//币种
$m_language	= 	$_POST['m_language'];			//语言选择
$s_name		= 	$_POST['s_name'];				//消费者姓名
$s_addr		= 	$_POST['s_addr'];				//消费者住址
$s_postcode	= 	$_POST['s_postcode'];			//邮政编码
$s_tel		= 	$_POST['s_tel'];				//消费者联系电话
$s_eml		= 	$_POST['s_eml'];				//消费者邮件地址
$s_name		= 	$_POST['r_name'];				//消费者姓名
$r_addr		= 	$_POST['r_addr'];				//收货人住址
$r_postcode	= 	$_POST['r_postcode'];			//收货人邮政编码
$r_tel		= 	$_POST['r_tel'];				//收货人联系电话
$r_eml		= 	$_POST['r_eml'];				//收货人电子地址
$m_ocomment	= 	$_POST['m_ocomment'];			//备注
$State		=	$_POST['m_status'];				//支付状态2成功,3失败
$modate		=	$_POST['modate'];				//返回日期
//接收组件的加密
$OrderInfo	=	$_POST['OrderMessage'];			//订单加密信息
$signMsg 	=	$_POST['Digest'];				//密匙
//接收新的md5加密认证
$newmd5info	=	$_POST['newmd5info'];



//2-----------重新计算md5的值---------------------------------------------------------------------------
//检查签名
$digest = strtoupper(md5($OrderInfo.$key));

//新的整合md5加密
$newtext = $m_id.$m_orderid.$m_oamount.$key.$State;
$newMd5digest = strtoupper(md5($newtext));

//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------
if ($digest == $signMsg){
	$OrderInfo = HexToStr($OrderInfo);
	if ($newmd5info == $newMd5digest){
		if ($State == 2){
			include_once($jieqiModules['pay']['path'].'/class/paylog.php');
			$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
			$m_orderid=intval($m_orderid);
			$paylog=$paylog_handler->get($m_orderid);
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
					$paylog->setVar('money', intval($m_oamount * 100));
					$paylog->setVar('note', $note);
					$paylog->setVar('payflag', 1);
					if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['save_paylog_failure']);
					else jieqi_msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
				}else{
					jieqi_printfail($jieqiLang['pay']['already_add_egold']);
				}
			}else jieqi_printfail($jieqiLang['pay']['no_buy_record']);
		}else{
			jieqi_printfail($jieqiLang['pay']['return_state_failure']);
		}
	}else{
		jieqi_printfail($jieqiLang['pay']['key_check_failure']);
	}
}else{
	jieqi_printfail($jieqiLang['pay']['sign_check_failure']);
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