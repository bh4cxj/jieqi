<?php
/**
 * 在线支付记录
 *
 * 在线支付记录
 * 
 * 调用模板：/modules/pay/templates/admin/paylog.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: paylog.php 326 2009-02-04 00:26:22Z juny $
 */


define('JIEQI_MODULE_NAME', 'pay');
require_once('../../../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('pay');
jieqi_checkpower($jieqiPower['pay']['adminpaylog'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
//处理手工确认和删除
include_once($jieqiModules['pay']['path'].'/class/paylog.php');
$paylog_handler=JieqiPaylogHandler::getInstance('JieqiPaylogHandler');
if(!empty($_REQUEST['action']) && !empty($_REQUEST['id'])){
	switch($_REQUEST['action']){
		case 'confirm':
			$tmplog=$paylog_handler->get($_REQUEST['id']);
			if(is_object($tmplog) && $tmplog->getVar('payflag')==0){
				$buyid=$tmplog->getVar('buyid');
				$buyname=$tmplog->getVar('buyname');
				$egoldtype=$tmplog->getVar('egoldtype');
				$egold=$tmplog->getVar('egold');

				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
				$ret=$users_handler->income($buyid, $egold, $egoldtype);
				if($ret) $note=sprintf($jieqiLang['pay']['add_egold_success'], $buyname, JIEQI_EGOLD_NAME, $egold);
				else $note=sprintf($jieqiLang['pay']['add_egold_failure'], $buyid, $buyname, JIEQI_EGOLD_NAME, $egold);

				$tmplog->setVar('rettime', JIEQI_NOW_TIME);
				$tmplog->setVar('note', $note);
				if(!empty($_SESSION['jieqiUserId'])){
					$tmplog->setVar('masterid', $_SESSION['jieqiUserId']);
					$tmplog->setVar('mastername', $_SESSION['jieqiUserName']);
				}
				$tmplog->setVar('payflag', 2);
				$paylog_handler->insert($tmplog);
			}
			break;
		case 'del':
			$criteria=new CriteriaCompo(new Criteria('payid', $_REQUEST['id'], '='));
			$criteria->add(new Criteria('payflag', '0'));
			$paylog_handler->delete($criteria);
			unset($criteria);
			break;
	}
}

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
include_once(JIEQI_ROOT_PATH.'/admin/header.php');

$criteria=new CriteriaCompo();
if(!empty($_REQUEST['keyword'])){
	switch($_REQUEST['keytype']){
		case 1:
		case 'buyname':
			$criteria->add(new Criteria('buyname', $_REQUEST['keyword'], '='));
			break;
		case 2:
		case 'payflag':
			switch($_REQUEST['keyword']){
				case $jieqiLang['pay']['state_unconfirm']:
					$payflag=0;
					break;
				case $jieqiLang['pay']['state_paysuccess']:
					$payflag=1;
					break;
				case $jieqiLang['pay']['state_handconfirm']:
					$payflag=2;
					break;
				default:
					$payflag=-1;
					break;
			}
			if($payflag>=0) $criteria->add(new Criteria('payflag', $payflag, '='));
			break;
		case 3:
		case 'buyinfo':
			$criteria->add(new Criteria('buyinfo', $_REQUEST['keyword'], '='));
			break;
		default:
			$criteria->add(new Criteria('payid', intval($_REQUEST['keyword']), '='));
			break;
	}
}
$criteria->setSort('payid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['pay']['paylogpnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['pay']['paylogpnum']);
$paylog_handler->queryObjects($criteria);
$payrows=array();
$k=0;

jieqi_getconfigs(JIEQI_MODULE_NAME, 'paytype');

while($v = $paylog_handler->getObject()){
	$payrows[$k]['date']=date(JIEQI_DATE_FORMAT, $v->getVar('buytime'));
	$payrows[$k]['time']=date(JIEQI_TIME_FORMAT, $v->getVar('buytime'));
	$payrows[$k]['buyid']=$v->getVar('buyid');
	$payrows[$k]['buyname']=$v->getVar('buyname');
	$payrows[$k]['egold']=$v->getVar('egold');
	if(isset($jieqiPaytype[$v->getVar('paytype','n')])) $payrows[$k]['paytype']=$jieqiPaytype[$v->getVar('paytype','n')]['shortname'];
	else $payrows[$k]['paytype']=$jieqiLang['pay']['paytype_unknow'];
	switch($v->getVar('payflag')){
		case 0:
			$payrows[$k]['payinfo']=$jieqiLang['pay']['state_unconfirm'];
			break;
		case 1:
			$payrows[$k]['payinfo']=$jieqiLang['pay']['state_paysuccess'];
			break;
		case 2:
			$payrows[$k]['payinfo']=$jieqiLang['pay']['state_handconfirm'];
			break;
		default:
			$payrows[$k]['payinfo']=$jieqiLang['pay']['state_unknow'];
			break;
	}
	$payrows[$k]['payflag']=$v->getVar('payflag');
	$payrows[$k]['payid']=$v->getVar('payid');
	
	$payrows[$k]['retserialno']=$v->getVar('retserialno');
	$payrows[$k]['retaccount']=$v->getVar('retaccount');
	$payrows[$k]['retinfo']=$v->getVar('retinfo');
	$payrows[$k]['buyinfo']=$v->getVar('buyinfo');
	$payrows[$k]['note']=$v->getVar('note');

	if($v->getVar('payflag')==0){
		$payrows[$k]['action']='<a href="javascript:if(confirm(\''.$jieqiLang['pay']['hand_confirm_confirm'].'\')) document.location=\''.JIEQI_URL.'/moaules/pay/admin/paylog.php?action=confirm&id='.$v->getVar('payid').'\';">'.$jieqiLang['pay']['hand_confirm'].'</a> | <a href="javascript:if(confirm(\''.$jieqiLang['pay']['delete_pay_confirm'].'？\')) document.location=\''.$jieqiModules['pay']['url'].'/admin/paylog.php?action=del&id='.$v->getVar('payid').'\';">'.$jieqiLang['pay']['delete_pay'].'</a>';
	}else{
		$payrows[$k]['action']='';
	}
	$k++;
}
$jieqiTpl->assign_by_ref('payrows', $payrows);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($paylog_handler->getCount($criteria),$jieqiConfigs['pay']['paylogpnum'],$_REQUEST['page']);
$jumppage->setlink('', true, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/admin/paylog.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>