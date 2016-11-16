<?php 
/**
 * 15173支付-数据校验
 *
 * 15173支付-数据校验 (http://www.15173.com)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: 15173notify.php 234 2008-11-28 01:53:06Z juny $
 */

$paytype_config = trim($_REQUEST['attach']);  //根据返回的attach参数确定调用哪一个配置文件
define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', $paytype_config);
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');
//print_r($_REQUEST);
$mymerchant_id=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号
$key=$jieqiPayset[JIEQI_PAY_TYPE]['paykey']; //密钥

//1-----------接收回的信息--------------------------------------------------------------------
$merchant_id = trim($_REQUEST['bargainor_id']);	//商户编号
$orderid = trim($_REQUEST['sp_billno']);			//交易订单编号[商户网站]
$amount = trim($_REQUEST['total_fee']);				//交易金额
$date = trim($_REQUEST['date']);					//交易日期
$succeed = trim($_REQUEST['pay_result']);			//交易结果，0支付成功，3支付失败，4支付不符
$mymac = trim($_REQUEST['sign']);               //获取安全加密串
$attach = trim($_REQUEST['attach']);

//2-----------重新计算md5的值---------------------------------------------------------------------------
//注意正确的参数串拼凑顺序
$text="pay_result=".$succeed."&bargainor_id=".$merchant_id."&sp_billno=".$orderid."&total_fee=" . $amount ."&attach=" . $attach ."&key=".$key;
$mac = md5($text);
//echo $text."<br>";
//echo $mymac." | ".$mac;
//3-----------判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理----------------------------

if($merchant_id != $mymerchant_id) jieqi_printfail($jieqiLang['pay']['customer_id_error']);
elseif(strtoupper($succeed) != 0) jieqi_printfail($jieqiLang['pay']['pay_return_error']);
elseif (strtoupper($mac)==strtoupper($mymac)){     	//---------如果签名验证成功！
//	exit('Happy!');
	include_once(JIEQI_ROOT_PATH.'/modules/pay/class/paylog.php');
	$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
	$orderid=intval($orderid);
	$paylog=$paylog_handler->get($orderid);
	if(is_object($paylog)){
		$buyname=$paylog->getVar('buyname');
		$buyid=$paylog->getVar('buyid');
		$payflag=$paylog->getVar('payflag');
		$egold=$paylog->getVar('egold');
		$money=$paylog->getVar('money');  //获取用户最初选择的金额
		if($payflag == 0){
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$uservip=1; //默认的vip等级

			//统计用户总的购买虚拟币，确认vip等级
			/*
			jieqi_getconfigs('system', 'vips', 'jieqiVips');
			if(!empty($jieqiVips)){
				$sql="SELECT SUM(saleprice) as sumegold FROM ".jieqi_dbprefix('obook_osale')." WHERE accountid=".$buyid;
				$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
				$query->execute($sql);
				$res=$query->getObject();
				if(is_object($res)) $sumegold=intval($res->getVar('sumegold', 'n'));
				else $sumegold=0;
				$sumegold+=$egold;
				foreach($jieqiVips as $k=>$v){
					$k=intval($k);
					if($sumegold >= $v['minegold'] && $k > $uservip) $uservip = $k;
				}
			}
			*/

            $yuan = intval($amount);
            if($money != $yuan*100)  //实际返回的金额和用户最初选择的金额不符
            {
                $temparr = array_flip($jieqiPayset[JIEQI_PAY_TYPE]['paylimit']);
                if(isset($temparr[$yuan])) $egold = intval($temparr[$yuan]);
                else jieqi_printfail($jieqiLang['pay']['pay_return_error']);
            }
			$ret=$users_handler->income($buyid, $egold, $jieqiPayset[JIEQI_PAY_TYPE]['paysilver'], $jieqiPayset[JIEQI_PAY_TYPE]['payscore'][$egold], $uservip);
			if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
			else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);
			$paylog->setVar('rettime', JIEQI_NOW_TIME);
			$paylog->setVar('money', intval($amount * 100));
			$paylog->setVar('egold', $egold);
			$paylog->setVar('note', $note);
			$paylog->setVar('payflag', 1);
			if(!$paylog_handler->insert($paylog)) jieqi_printfail($jieqiLang['pay']['save_paylog_failure']);
			else echo "OK";//jieqi_msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold));
		}else{
			echo "OK";//jieqi_printfail($jieqiLang['pay']['already_add_egold']);
		}
	}else{
		jieqi_printfail($jieqiLang['pay']['no_buy_record']);
	}
}
?>