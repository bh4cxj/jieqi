<?php 
/**
 * 章节销售统计
 *
 * 显示一本书的各章节销售统计
 * 
 * 调用模板：/modules/obook/templates/chapterstat.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chapterstat.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
if(empty($_REQUEST['oid']) || !is_numeric($_REQUEST['oid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['oid']=intval($_REQUEST['oid']);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($_REQUEST['oid']);
if(!$obook) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人电子书权限
$canedit=jieqi_checkpower($jieqiPower['obook']['manageallobook'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改电子书
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($obook->getVar('authorid')==$tmpvar || $obook->getVar('posterid')==$tmpvar || $obook->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['obook']['noper_manage_obook']);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
$ochapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');

include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('obook_static_url',$obook_static_url);
$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['oid'], '='));
$criteria->setSort('chapterorder');
$criteria->setOrder('ASC');
$ochapter_handler->queryObjects($criteria);
$ochapterrows=array();
$k=0;
while($v = $ochapter_handler->getObject()){
	$ochapterrows[$k]['ochapterid']=$v->getVar('ochapterid');  //章节序号
	$ochapterrows[$k]['obookid']=$v->getVar('obookid');  //文章序号
	$ochapterrows[$k]['obookname']=$v->getVar('obookname');  //文章名称
	$ochapterrows[$k]['chaptername']=$v->getVar('chaptername');  //章节名称
	if($jieqiConfigs['obook']['fakeinfo']==1){
		$ochapterrows[$k]['obooksubdir']=jieqi_getsubdir($v->getVar('obookid'));  //子目录
		if(!empty($jieqiConfigs['obook']['fakeprefix'])) $tmpvar='/'.$jieqiConfigs['obook']['fakeprefix'].'info';
		else $tmpvar='/files/obook/info';
		$ochapterrows[$k]['url_obookinfo']=$obook_dynamic_url.$tmpvar.$ochapterrows[$k]['obooksubdir'].'/'.$v->getVar('obookid').$jieqiConfigs['obook']['fakefile'];
	}else{
		$ochapterrows[$k]['obooksubdir']='';
		$ochapterrows[$k]['url_obookinfo']=$obook_dynamic_url.'/obookinfo.php?id='.$v->getVar('obookid');
	}
	$ochapterrows[$k]['url_chapter']=$obook_static_url.'/reader.php?oid='.$v->getVar('obookid').'&cid='.$v->getVar('ochapterid');

	$ochapterrows[$k]['posterid']=$v->getVar('posterid');  //发表者
	$ochapterrows[$k]['poster']=$v->getVar('poster');

	$ochapterrows[$k]['sortid']=$v->getVar('sortid');  //类别序号
	$ochapterrows[$k]['sort']=$jieqiSort['obook'][$v->getVar('sortid')]['caption'];  //类别

	$ochapterrows[$k]['size']=$v->getVar('size');
	$ochapterrows[$k]['size_k']=ceil($v->getVar('size')/1024);
	$ochapterrows[$k]['size_c']=ceil($v->getVar('size')/2);
	$ochapterrows[$k]['saleprice']=$v->getVar('saleprice');
	$ochapterrows[$k]['vipprice']=$v->getVar('vipprice');
	$ochapterrows[$k]['sumegold']=$v->getVar('sumegold');
	$ochapterrows[$k]['sumesilver']=$v->getVar('sumesilver');
	$ochapterrows[$k]['sumemoney']=$ochapterrows[$k]['sumegold']+$ochapterrows[$k]['sumesilver'];
	$ochapterrows[$k]['allsale']=$v->getVar('allsale');
	$ochapterrows[$k]['totalsale']=$v->getVar('totalsale');
	$ochapterrows[$k]['display']=$v->getVar('display');
	$ochapterrows[$k]['salestatus']=$v->getSalestatus();
	
	$ochapterrows[$k]['postdate']=date('y-m-d',$v->getVar('postdate')); //发布日期
	$ochapterrows[$k]['lastupdate']=date('y-m-d',$v->getVar('lastupdate')); //最后更新日期

	$k++;
}

$jieqiTpl->assign_by_ref('ochapterrows', $ochapterrows);
$jieqiTpl->assign('obookid', $_REQUEST['oid']);
$jieqiTpl->assign('obookname', $obook->getVar('obookname'));

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/chapterstat.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>