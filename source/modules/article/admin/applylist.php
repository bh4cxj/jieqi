<?php 
/**
 * 作家申请信息列表
 *
 * 作家申请信息列表
 * 
 * 调用模板：/modules/article/templates/admin/applylist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: applylist.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['article']['setwriter'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('applywriter', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');

include_once($jieqiModules['article']['path'].'/class/applywriter.php');
$apply_handler =& JieqiApplywriterHandler::getInstance('JieqiApplywriterHandler');

//处理审核、删除
if(isset($_REQUEST['action']) && !empty($_REQUEST['id'])){
	$apply=$apply_handler->get($_REQUEST['id']);
	if(!is_object($apply)) jieqi_printfail($jieqiLang['article']['apply_not_exists']);
	
	if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
	switch($_REQUEST['action']){
		case 'confirm':
			//审核通过
			$apply->setVar('authtime', JIEQI_NOW_TIME);
			$apply->setVar('authuid', $_SESSION['jieqiUserId']);
			$apply->setVar('authname', $_SESSION['jieqiUserName']);
			$apply->setVar('applyflag', 1);
			$apply_handler->insert($apply);
			//设置用户权限
			include_once(JIEQI_ROOT_PATH.'/class/groups.php');
			$key=array_search($jieqiConfigs['article']['writergroup'], $jieqiGroups);
			if($key == false) jieqi_printfail($jieqiLang['article']['no_writer_group']);
			elseif($key == JIEQI_GROUP_ADMIN) jieqi_printfail($jieqiLang['article']['no_writer_admin']);
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$jieqiUsers = $users_handler->get($apply->getVar('applyuid', 'n'));
			if(is_object($jieqiUsers)){
				$jieqiUsers->setVar('groupid', $key);
				$users_handler->insert($jieqiUsers);
			}
			//发送短信
			include_once(JIEQI_ROOT_PATH.'/include/funmessage.php');
			jieqi_sendmessage($apply->getVar('applyuid', 'n'), $apply->getVar('applyname', 'n'), $jieqiLang['article']['apply_confirm_title'], $jieqiLang['article']['apply_confirm_text']);
			break;
		case 'refuse':
			//审核拒绝
			$apply->setVar('authtime', JIEQI_NOW_TIME);
			$apply->setVar('authuid', $_SESSION['jieqiUserId']);
			$apply->setVar('authname', $_SESSION['jieqiUserName']);
			$apply->setVar('applyflag', 2);
			$apply_handler->insert($apply);
			//发送短信
			include_once(JIEQI_ROOT_PATH.'/include/funmessage.php');
			jieqi_sendmessage($apply->getVar('applyuid', 'n'), $apply->getVar('applyname', 'n'), $jieqiLang['article']['apply_refuse_title'], $jieqiLang['article']['apply_refuse_text']);
			break;
		case 'delete':
			//删除
			$apply_handler->delete($_REQUEST['id']);
			break;
	}
	unset($criteria);
}

//显示列表
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$criteria=new CriteriaCompo();
switch ($_REQUEST['display']){
	case 'ready':
		$criteria->add(new Criteria('applyflag', 0));
		break;
	case 'success':
		$criteria->add(new Criteria('applyflag', 1));
		break;
	case 'failure':
		$criteria->add(new Criteria('applyflag', 2));
		break;
}
$criteria->setSort('applyid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['article']['pagenum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['article']['pagenum']);
$apply_handler->queryObjects($criteria);
$applyrows=array();
$k=0;
while($v = $apply_handler->getObject()){
	$applyrows[$k]['applyid']=$v->getVar('applyid');  //申请序号
	$applyrows[$k]['applytime']=date('Y-m-d H:i:s', $v->getVar('applytime'));  //申请时间
	$applyrows[$k]['applyuid']=$v->getVar('applyuid');
	$applyrows[$k]['applyname']=$v->getVar('applyname');
	if($v->getVar('authtime') > 0) $applyrows[$k]['authtime']=date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $v->getVar('authtime'));
	else $applyrows[$k]['authtime']='';
	$applyrows[$k]['authuid']=$v->getVar('authuid');
	$applyrows[$k]['authname']=$v->getVar('authname');
	$applyrows[$k]['applytitle']=$v->getVar('applytitle');
	$applyrows[$k]['applysize']=$v->getVar('applysize');
	$applyrows[$k]['applysize_c']=ceil($v->getVar('applysize')/2);
	$applyrows[$k]['applysize_k']=ceil($v->getVar('applysize') / 1000);
	$applyrows[$k]['applyflag']=$v->getVar('applyflag');
	if($applyrows[$k]['applyflag']==2) $applyrows[$k]['authstatus']=$jieqiLang['article']['apply_status_failure'];
	elseif($applyrows[$k]['applyflag']==1) $applyrows[$k]['authstatus']=$jieqiLang['article']['apply_status_success'];
	else $applyrows[$k]['authstatus']=$jieqiLang['article']['apply_status_ready'];
	$k++;
}

$jieqiTpl->assign_by_ref('applyrows', $applyrows);

$jieqiTpl->assign('url_jump', jieqi_addurlvars(array()));
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($apply_handler->getCount($criteria),$jieqiConfigs['article']['pagenum'],$_REQUEST['page']);
$pagelink='';
if(!empty($_REQUEST['display'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='display='.$_REQUEST['display'];
}

if(empty($pagelink)) $pagelink.='?page=';
else $pagelink.='&page=';
$jumppage->setlink($jieqiModules['article']['url'].'/admin/applylist.php'.$pagelink, false, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/applylist.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>