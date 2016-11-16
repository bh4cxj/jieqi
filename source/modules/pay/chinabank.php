<?php 
/**
 * 网银在线-提交处理
 *
 * 网银在线-提交处理 (http://www.chinabank.com.cn)
 * 
 * 调用模板：/modules/pay/templates/chinabank.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chinabank.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'chinabank');
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
		if($_REQUEST['moneytype'] == 2){ 
			$v_moneytype = 1; //0为人民币，1为美元
			$cardinfo="美元";
			$v_mid=$jieqiPayset[JIEQI_PAY_TYPE]['foreignpayid'];          //商户编号
			$key=$jieqiPayset[JIEQI_PAY_TYPE]['foreignpaykey'];          //默认的私钥值，更改私钥后要修改这里
		}else{
			$v_moneytype = 0; //0为人民币，1为美元
			$cardinfo="人民币";
			$v_mid=$jieqiPayset[JIEQI_PAY_TYPE]['payid'];          //商户编号
			$key=$jieqiPayset[JIEQI_PAY_TYPE]['paykey'];          //默认的私钥值，更改私钥后要修改这里
		}
		
		
		$v_oid=date('Ymd').'-'.$jieqiPayset[JIEQI_PAY_TYPE]['payid'].'-'.intval($paylog->getVar('payid'));    //定单号，订单编号标准格式为：订单生成日期(yyyymmdd)-商户编号-商户流水号。
		$v_amount=sprintf('%0.2f', $money / 100); //定单金额，不可为空，单位：元，小数点后保留两位。
		$v_url=$jieqiPayset[JIEQI_PAY_TYPE]['payreturn']; //消费者完成购物后返回的商户页面，URL参数是以http://开头的完整URL地址
		
		$v_md5info=strtoupper(md5($v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key));  //校验字符串
		$style=$jieqiPayset[JIEQI_PAY_TYPE]['style'];//网关模式0(普通列表)，1(银行列表中带外卡)
		$remark1=$jieqiPayset[JIEQI_PAY_TYPE]['remark1'];//备注字段1
		$remark2=$jieqiPayset[JIEQI_PAY_TYPE]['remark2'];//备注字段2

		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign('url_pay', $jieqiPayset[JIEQI_PAY_TYPE]['payurl']);
		$jieqiTpl->assign('buyname', $_SESSION['jieqiUserName']);
		$jieqiTpl->assign('egold', $_REQUEST['egold']);
		$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
		$jieqiTpl->assign('money', $v_amount);
		
		$jieqiTpl->assign('v_mid', $v_mid);
		$jieqiTpl->assign('v_oid', $v_oid);
		$jieqiTpl->assign('v_amount', $v_amount);
		$jieqiTpl->assign('v_moneytype', $v_moneytype);
		$jieqiTpl->assign('v_url', $v_url);
		$jieqiTpl->assign('v_md5info', $v_md5info);
		$jieqiTpl->assign('style', $style);
		$jieqiTpl->assign('remark1', $remark1);
		$jieqiTpl->assign('remark2', $remark2);
		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
         	foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $jieqiTpl->assign($k, $v);
		}
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/chinabank.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
	}
}else{
	jieqi_printfail($jieqiLang['pay']['need_buy_type']);
}

?>