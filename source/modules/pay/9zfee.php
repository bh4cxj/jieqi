<?php 
/**
 * 九州网联116电话支付-提交处理
 *
 * 九州网联116电话支付-提交处理 (http://www.116.com.cn)
 * 
 * 调用模板：/modules/pay/templates/9zfee.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: 9zfee.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', '9zfee');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_loadlang('9zfee', JIEQI_MODULE_NAME);
if(!jieqi_checklogin(true)) jieqi_printfail($jieqiLang['pay']['need_login']);
jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

//接收返回
if(isset($_REQUEST['retcode']) && !empty($_REQUEST['sessionid'])){
	switch($_REQUEST['retcode']){
		case '0':
			msg_box(LANG_DO_SUCCESS, sprintf($jieqiLang['pay']['order_submit_success'], $_REQUEST['sessionid'], $jieqiPayset[JIEQI_PAY_TYPE]['phone']));
			exit;
			break;
		case '1':
			print_fail($jieqiLang['pay']['error_rand']);
			break;
		case '2':
			print_fail($jieqiLang['pay']['error_ip']);
			break;
		case '3':
			print_fail($jieqiLang['pay']['error_para_format']);
			break;
		case '4':
			print_fail($jieqiLang['pay']['error_para_num']);
			break;
		case '5':
			print_fail($jieqiLang['pay']['error_md5']);
			break;
		case '6':
			print_fail($jieqiLang['pay']['error_serial_id']);
			break;
		default:
			print_fail($jieqiLang['pay']['error_unknow']);
	}
	exit;
}

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
		$key=$jieqiPayset[JIEQI_PAY_TYPE]['paykey']; //密钥
		$cp_id=$jieqiPayset[JIEQI_PAY_TYPE]['payid']; //CP编号
		$prod_id=$jieqiPayset[JIEQI_PAY_TYPE]['prodid'];  //产品编号
		$rand_id=sprintf("%06d", $paylog->getVar('payid')); //随机数 数字，总长最大32个字符。为九州网联卡号去掉cp_id和PRICE两部分
		//建议：在确保有效期内不重复的前提下，该字段位数应尽量少，以便用户电话按键输入。
		$pay_id=$jieqiPayset[JIEQI_PAY_TYPE]['pay_id']; //支付方式码  数字，值为1，总长1个字符。
		$valid_time=$jieqiPayset[JIEQI_PAY_TYPE]['valid_time']; //订单有效期 数字，总长最大5位。(单位为小时)订单的有效期，过期订单作废，不能支付；如为-1, 则表示为永久订单，适用于提供永久卡号的cp（例如卡号与cp用户一一对应）,为-3,则表示为包月订单,- 4为包季订单，-5 为包年订单。
		$money_type=$jieqiPayset[JIEQI_PAY_TYPE]['moneytype']; //支付币种 数字，值为0，总长1个字符。
		$price=$money; //支付总额 值为数字型，总长最大7位，单位为分。
		$md5=strtoupper(md5($cp_id.$prod_id.$rand_id.$pay_id.$valid_time.$money_type.$price.$key)); //MD5码 按cp_id, prod_id, rand_id, pay_id, valid_time, money_type, price的顺序，将这7个参数的value值拼成一个无间隔的字符串，利用九州网联提供的密匙将此串加密，得到的结果即为md5码。
		$sessionid=$cp_id.substr(sprintf("%02d", intval($price / 100)), -2).$rand_id; //对于用户登录后才能支付的CP，此参数为用户该次登录对应的session id；对于不需要用户登录就能支付的CP，此参数不必传递

		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign('url_pay', $jieqiPayset[JIEQI_PAY_TYPE]['payurl']);
		$jieqiTpl->assign('buyname', $_SESSION['jieqiUserName']);
		$jieqiTpl->assign('egold', $_REQUEST['egold']);
		$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
		$jieqiTpl->assign('money', sprintf('%0.2f', $money / 100));

		$jieqiTpl->assign('cp_id', $cp_id);
		$jieqiTpl->assign('prod_id', $prod_id);
		$jieqiTpl->assign('rand_id', $rand_id);
		$jieqiTpl->assign('pay_id', $pay_id);
		$jieqiTpl->assign('valid_time', $valid_time);
		$jieqiTpl->assign('money_type', $money_type);
		$jieqiTpl->assign('price', $price);
		$jieqiTpl->assign('md5', $md5);
		$jieqiTpl->assign('sessionid', $sessionid);
		if(is_array($jieqiPayset[JIEQI_PAY_TYPE]['addvars'])){
         	foreach($jieqiPayset[JIEQI_PAY_TYPE]['addvars'] as $k=>$v) $jieqiTpl->assign($k, $v);
		}
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/9zfee.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
	}
}else{
	jieqi_printfail($jieqiLang['pay']['need_buy_type']);
}

?>