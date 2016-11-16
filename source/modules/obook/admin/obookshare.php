<?php 
/**
 * 电子书共享设置
 *
 * 电子书共享设置
 * 
 * 调用模板：/modules/obook/templates/admin/obookshare.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obookshare.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['obook']['manageallobook'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('list', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
//处理审核、删除
if(isset($_REQUEST['action']) && !empty($_REQUEST['id'])){
	$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['id']));
	jieqi_getcachevars('obook', 'obookuplog');
	if(!is_array($jieqiObookuplog)) $jieqiObookuplog=array('obookuptime'=>0, 'chapteruptime'=>0);
	switch($_REQUEST['action']){
		case 'share':
		$obook_handler->updatefields(array('state'=>1), $criteria);
		break;
		case 'unshare':
		$obook_handler->updatefields(array('state'=>0), $criteria);
		break;
	}
	unset($criteria);
}

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTpl->assign('obook_static_url',$obook_static_url);
$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
jieqi_getconfigs('obook', 'sort');
//文章类别
if(empty($_REQUEST['class'])) $_REQUEST['class']=0;
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$criteria=new CriteriaCompo();
if(!empty($_REQUEST['keyword'])){
	$_REQUEST['keyword']=trim($_REQUEST['keyword']);
	if($_REQUEST['keytype']==1) $criteria->add(new Criteria('author', $_REQUEST['keyword'], '='));
	elseif($_REQUEST['keytype']==2) $criteria->add(new Criteria('poster', $_REQUEST['keyword'], '='));
	elseif($_REQUEST['keytype']==3) $criteria->add(new Criteria('agent', $_REQUEST['keyword'], '='));
	else $criteria->add(new Criteria('obookname', $_REQUEST['keyword'], '='));
}
if(!empty($_REQUEST['class'])){
	$criteria->add(new Criteria('sortid', $_REQUEST['class'], '='));
	$obooktitle=$jieqiSort['obook'][$_REQUEST['class']]['caption'];
}else{
	$obooktitle=$jieqiLang['obook']['all_obook_title'];
	switch($_REQUEST['display']){
		case 'share':
			$criteria->add(new Criteria('state', 1, '='));
			$obooktitle=$jieqiLang['obook']['share_obook_title'];
			break;
		case 'unshare':
			$criteria->add(new Criteria('state', 0, '='));
			$obooktitle=$jieqiLang['obook']['unshare_obook_title'];
			break;
		case 'self':
			$criteria->add(new Criteria('publishid', 0, '='));
			$obooktitle=$jieqiLang['obook']['local_obook_title'];
			break;
	}
}
$jieqiTpl->assign('obooktitle', $obooktitle);
$jieqiTpl->assign('display', $_REQUEST['display']);

$jieqiTpl->assign('url_obook', $obook_dynamic_url.'/admin/obookshare.php');
$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');

$criteria->setSort('lastupdate');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['obook']['pagenum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['obook']['pagenum']);
$obook_handler->queryObjects($criteria);
$obookrows=array();
$k=0;
while($v = $obook_handler->getObject()){
	$obookrows[$k]['checkbox']='<input type="checkbox" id="checkid'.$k.'" name="checkid'.$k.'" value="'.$v->getVar('obookid').'">';  //选择框

	$obookrows[$k]['checkid']=$k;  //显示序号
	$obookrows[$k]['obookid']=$v->getVar('obookid');  //文章序号
	$obookrows[$k]['obookname']=$v->getVar('obookname');  //文章名称
	if($jieqiConfigs['obook']['fakeinfo']==1){
		$obookrows[$k]['obooksubdir']=jieqi_getsubdir($v->getVar('obookid'));  //子目录
		if(!empty($jieqiConfigs['obook']['fakeprefix'])) $tmpvar='/'.$jieqiConfigs['obook']['fakeprefix'].'info';
		else $tmpvar='/files/obook/info';
		$obookrows[$k]['url_obookinfo']=$obook_dynamic_url.$tmpvar.$obookrows[$k]['obooksubdir'].'/'.$v->getVar('obookid').$jieqiConfigs['obook']['fakefile'];  //子目录
	}else{
		$obookrows[$k]['obooksubdir']='';
		$obookrows[$k]['url_obookinfo']=$obook_dynamic_url.'/obookinfo.php?id='.$v->getVar('obookid');  //子目录
	}
	if($v->getVar('lastchapter')==''){
		$obookrows[$k]['lastchapterid']=0;  //章节序号
		$obookrows[$k]['lastchapter']='';  //章节名称
		$obookrows[$k]['url_lastchapter']='';  //章节地址
	}else{
		$obookrows[$k]['lastchapterid']=$v->getVar('lastchapterid');
		$obookrows[$k]['lastchapter']=$v->getVar('lastchapter');
		$obookrows[$k]['url_lastchapter']=$obook_static_url.'/reader.php?aid='.$v->getVar('obookid').'&cid='.$v->getVar('lastchapterid');
	}

	$obookrows[$k]['lastvolume']=$v->getVar('lastvolumeid');  //分卷序号
	$obookrows[$k]['lastvolume']=$v->getVar('lastvolume');  //分卷名称

	$obookrows[$k]['authorid']=$v->getVar('authorid');  //作者
	$obookrows[$k]['author']=$v->getVar('author');
	$obookrows[$k]['posterid']=$v->getVar('posterid');  //发表者
	$obookrows[$k]['poster']=$v->getVar('poster');
	$obookrows[$k]['agentid']=$v->getVar('agentid');  //代理者
	$obookrows[$k]['agent']=$v->getVar('agent');

	$obookrows[$k]['sortid']=$v->getVar('sortid');  //类别序号
	$obookrows[$k]['sort']=$jieqiSort['obook'][$v->getVar('sortid')]['caption'];  //类别

	$obookrows[$k]['size']=$v->getVar('size');
	$obookrows[$k]['size_k']=ceil($v->getVar('size')/1024);
	$obookrows[$k]['size_c']=ceil($v->getVar('size')/2);
	$obookrows[$k]['daysale']=$v->getVar('daysale');
	$obookrows[$k]['weeksale']=$v->getVar('weeksale');
	$obookrows[$k]['monthsale']=$v->getVar('monthsale');
	$obookrows[$k]['sumegold']=$v->getVar('sumegold');
	$obookrows[$k]['sumesilver']=$v->getVar('sumesilver');
	$obookrows[$k]['sumemoney']=$obookrows[$k]['sumegold']+$obookrows[$k]['sumesilver'];
	$obookrows[$k]['payprice']=$v->getVar('payprice');
	$obookrows[$k]['allsale']=$v->getVar('allsale');
	$obookrows[$k]['lastupdate']=date('y-m-d',$v->getVar('lastupdate')); //最后更新日期
	$obookrows[$k]['display']=$v->getVar('display');
	$obookrows[$k]['state']=$v->getVar('state');
	$k++;
}

$jieqiTpl->assign_by_ref('obookrows', $obookrows);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($obook_handler->getCount($criteria),$jieqiConfigs['obook']['pagenum'],$_REQUEST['page']);
$pagelink='';
if(!empty($_REQUEST['class'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='class='.$_REQUEST['class'];
}elseif(!empty($_REQUEST['display'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='display='.$_REQUEST['display'];
}
if(!empty($_REQUEST['keyword'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='keyword='.$_REQUEST['keyword'];
	$pagelink.='&keytype='.$_REQUEST['keytype'];
}
if(empty($pagelink)) $pagelink.='?page=';
else $pagelink.='&page=';
$jumppage->setlink($obook_dynamic_url.'/admin/obookshare.php'.$pagelink, false, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/admin/obookshare.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>