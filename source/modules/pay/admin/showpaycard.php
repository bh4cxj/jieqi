<?php 
/**
 * 显示充值点卡信息
 *
 * 显示充值点卡信息
 * 
 * 调用模板：/modules/pay/templates/admin/showpaycard.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: showpaycard.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_USE_GZIP','0');
define('JIEQI_MODULE_NAME', 'pay');
require_once('../../../global.php');
//检查权限
jieqi_checklogin();
if($jieqiUsersStatus != JIEQI_GROUP_ADMIN) jieqi_printfail(LANG_NEED_ADMIN);
@set_time_limit(3600);
@session_write_close();
jieqi_loadlang('paycard', JIEQI_MODULE_NAME);
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$pagenumrows=1000;
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$cardrows=array();
$totalrows=0;
$_REQUEST['keyword']=trim($_REQUEST['keyword']);
if(!empty($_REQUEST['keyword'])){
	include_once($jieqiModules['pay']['path'].'/class/paycard.php');
	$paycard_handler =& JieqiPaycardHandler::getInstance('JieqiPaycardHandler');
	$criteria=new CriteriaCompo();
	$criteria->add(new Criteria('batchno', $_REQUEST['keyword']));
	if($_REQUEST['paytype']==1) $criteria->add(new Criteria('ispay', 1));
	elseif($_REQUEST['paytype']==2) $criteria->add(new Criteria('ispay', 0));
	$criteria->setStart(($_REQUEST['page']-1) * $pagenumrows);
	$criteria->setLimit($pagenumrows);
	$paycard_handler->queryObjects($criteria);
	$k=0;
	while($v = $paycard_handler->getObject()){
		$cardrows[$k]['order']=($_REQUEST['page']-1) * $pagenumrows + $k + 1;
		$cardrows[$k]['batchno']=$v->getVar('batchno');
		$cardrows[$k]['cardno']=$v->getVar('cardno');
		$cardrows[$k]['cardpass']=$v->getVar('cardpass');
		$cardrows[$k]['cardtype']=$v->getVar('cardtype');
		$cardrows[$k]['payemoney']=$v->getVar('payemoney');
		$cardrows[$k]['ispay']=$v->getVar('ispay');
		if($v->getVar('paytime') > 0) $cardrows[$k]['paytime']=date('Y-m-d H:i:s', $v->getVar('paytime'));
		else $cardrows[$k]['paytime']='';
		$cardrows[$k]['payuid']=$v->getVar('payuid');
		$cardrows[$k]['payname']=$v->getVar('payname');
		$k++;
	}
	$totalrows=$paycard_handler->getCount($criteria);
}
$jieqiTpl->assign_by_ref('cardrows',$cardrows);
$jieqiTpl->assign('totalrows', $totalrows);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($totalrows,$pagenumrows,$_REQUEST['page']);
$pagelink='';
if(!empty($_REQUEST['keyword'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='keyword='.$_REQUEST['keyword'];
	$pagelink.='&paytype='.$_REQUEST['paytype'];
}
if(empty($pagelink)) $pagelink.='?page=';
else $pagelink.='&page=';
$jumppage->setlink($jieqiModules['pay']['url'].'/admin/showpaycard.php'.$pagelink, false, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['pay']['path'].'/templates/admin/showpaycard.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>