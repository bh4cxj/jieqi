<?php 
/**
 * 用户提交报告管理
 *
 * 用户提交报告管理
 * 
 * 调用模板：/templates/admin/reportlist.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: reportlist.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminmessage'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('report', JIEQI_MODULE_NAME);
jieqi_getconfigs('system', 'configs');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'rsort', 'jieqiRsort');

//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;

include_once(JIEQI_ROOT_PATH.'/class/report.php');
$report_handler=JieqiReportHandler::getInstance('JieqiReportHandler');

//处理批量删除
if(isset($_REQUEST['checkaction']) && $_REQUEST['checkaction'] == 1 && is_array($_REQUEST['checkid']) && count($_REQUEST['checkid'])>0){
	$where='';
	foreach($_REQUEST['checkid'] as $v){
		if(is_numeric($v)){
			$v=intval($v);
			if(!empty($where)) $where.=', ';
			$where.=$v;
		}
	}
	if(!empty($where)){
		$sql='DELETE FROM '.jieqi_dbprefix('system_report').' WHERE reportid IN ('.$where.')';
		$report_handler->db->query($sql);
	}
	$_REQUEST['checkaction']=0;
}

if(isset($_GET['checkaction'])) unset($_GET['checkaction']);
if(isset($_POST['checkaction'])) unset($_POST['checkaction']);

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');
$reportrows=array();
$criteria=new CriteriaCompo();
if(!empty($_REQUEST['keyword']) && !empty($_REQUEST['keytype'])){
	switch($_REQUEST['keytype']){
		case 'reportname':
			$criteria->add(new Criteria('reportname', $_REQUEST['keyword']));
			break;
		case 'authname':
			$criteria->add(new Criteria('authname', $_REQUEST['keyword']));
			break;
		case 'reporttitle':
			$criteria->add(new Criteria('reporttitle', '%'.$_REQUEST['keyword'].'%', 'LIKE'));
			break;
	}
	$_GET['keyword']=$_REQUEST['keyword'];
	$_GET['keytype']=$_REQUEST['keytype'];
}
//$criteria->add(new Criteria('isread', 0));
$criteria->setSort('reportid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['system']['messagepnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['messagepnum']);
$report_handler->queryObjects($criteria);
$k=0;
while($v = $report_handler->getObject()){
	$reportrows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('reportid').'">';

	$reportrows[$k]['reportid']=$v->getVar('reportid');
	$reportrows[$k]['reporttime']=$v->getVar('reporttime');
	$reportrows[$k]['reportuid']=$v->getVar('reportuid');
	$reportrows[$k]['reportname']=$v->getVar('reportname');
	$reportrows[$k]['authtime']=$v->getVar('authtime');
	$reportrows[$k]['authuid']=$v->getVar('authuid');
	$reportrows[$k]['authname']=$v->getVar('authname');
	$reportrows[$k]['reporttitle']=$v->getVar('reporttitle');
	$reportrows[$k]['reporttext']=$v->getVar('reporttext');
	$reportrows[$k]['reportsize']=$v->getVar('reportsize');
	$reportrows[$k]['reportfield']=$v->getVar('reportfield');
	$reportrows[$k]['authnote']=$v->getVar('authnote');
	$reportrows[$k]['reportsort']=intval($v->getVar('reportsort'));
	$reportrows[$k]['reporttype']=intval($v->getVar('reporttype'));
	$reportrows[$k]['authflag']=$v->getVar('authflag');
	$reportrows[$k]['sortname']=$jieqiRsort[$reportrows[$k]['reportsort']]['caption'];
	if(isset($jieqiRsort[$reportrows[$k]['reportsort']]['types'][$reportrows[$k]['reporttype']])) $reportrows[$k]['typename']=$jieqiRsort[$reportrows[$k]['reportsort']]['types'][$reportrows[$k]['reporttype']];
	else $reportrows[$k]['typename']=$jieqiLang['system']['report_type_other'];
	
	$k++;
}
$jieqiTpl->assign_by_ref('reportrows', $reportrows);
$jieqiTpl->assign_by_ref('rsortrows', $jieqiRsort);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($report_handler->getCount($criteria),$jieqiConfigs['system']['messagepnum'],$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/reportlist.html';

include_once(JIEQI_ROOT_PATH.'/admin/footer.php');


?>