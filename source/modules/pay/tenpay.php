<?php 
/**
 * 腾讯财付通-提交处理
 *
 * 腾讯财付通-提交处理 (https://www.tenpay.com)
 * 
 * 调用模板：/modules/pay/templates/tenpay.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: tenpay.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'tenpay');
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
		$amount=$money / 100;
		
		$cmdno=$jieqiPayset[JIEQI_PAY_TYPE]['cmdno'];  //业务代码, 财付通支付支付接口填  1 
		$date=date('Ymd');
		$bank_type=$jieqiPayset[JIEQI_PAY_TYPE]['bank_type']; //银行类型:财付通支付填0
		$desc=JIEQI_EGOLD_NAME; //商品名称
		$purchaser_id='';  //用户(买方)的财付通帐户,可以为空
		if(!empty($_REQUEST['purchaser_id'])) $purchaser_id=$_REQUEST['purchaser_id'];
		$bargainor_id=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //商户编号
		$sp_billno=$paylog->getVar('payid'); //商家的定单号
		$transaction_id=$jieqiPayset[JIEQI_PAY_TYPE]['payid'].$date.sprintf('%010d', $sp_billno);
		//交易号(订单号)，由商户网站产生(建议顺序累加)，一对请求和应答的交易号必须相同）。transaction_id 为28位长的数值，其中前10位为商户网站编号(SPID)，由财付通统一分配；之后8位为订单产生的日期，如20050415；最后10位商户需要保证一天内不同的事务（用户订购一次商品或购买一次服务），其ID不相同
		$total_fee=$money; //总金额，单位为分
		$fee_type=$jieqiPayset[JIEQI_PAY_TYPE]['fee_type']; //现金支付币种，目前只支持人民币，1 - RMB人民币, 2 - USD美元, 3 - HKD港币
		$return_url=$jieqiPayset[JIEQI_PAY_TYPE]['payreturn']; //接收财付通返回结果的URL(推荐使用ip)
		$attach=$jieqiPayset[JIEQI_PAY_TYPE]['attach']; //商家数据包，原样返回
		$text="cmdno=".$cmdno."&date=".$date."&bargainor_id=".$bargainor_id."&transaction_id=".$transaction_id."&sp_billno=".$sp_billno."&total_fee=".$total_fee."&fee_type=".$fee_type."&return_url=".$return_url."&attach=".$attach."&key=".$jieqiPayset[JIEQI_PAY_TYPE]['paykey'];
		$sign=strtoupper(md5($text));
		
		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign('url_pay', $jieqiPayset[JIEQI_PAY_TYPE]['payurl']);
		$jieqiTpl->assign('buyname', $_SESSION['jieqiUserName']);
		$jieqiTpl->assign('egold', $_REQUEST['egold']);
		$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
		$jieqiTpl->assign('money', sprintf('%0.2f', $money / 100));

		$jieqiTpl->assign('cmdno', $cmdno);
		$jieqiTpl->assign('date', $date);
		$jieqiTpl->assign('bank_type', $bank_type);
		$jieqiTpl->assign('desc', $desc);
		$jieqiTpl->assign('purchaser_id', $purchaser_id);
		$jieqiTpl->assign('bargainor_id', $bargainor_id);
		$jieqiTpl->assign('sp_billno', $sp_billno);
		$jieqiTpl->assign('transaction_id', $transaction_id);
		$jieqiTpl->assign('total_fee', $total_fee);
		$jieqiTpl->assign('fee_type', $fee_type);
		$jieqiTpl->assign('return_url', $return_url);
		$jieqiTpl->assign('attach', $attach);
		$jieqiTpl->assign('sign', $sign);
		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
         	foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $jieqiTpl->assign($k, $v);
		}
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/tenpay.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
	}
}else{
	jieqi_printfail($jieqiLang['pay']['need_buy_type']);
}

?>