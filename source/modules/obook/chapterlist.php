<?php 
/**
 * 显示一本书的章节列表
 *
 * 显示一本书的章节列表
 * 
 * 调用模板：/modules/obook/templates/ochapterlist.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chapterlist.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
jieqi_loadlang('list', JIEQI_MODULE_NAME);

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
$ochapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');

include_once(JIEQI_ROOT_PATH.'/header.php');
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
	else $criteria->add(new Criteria('obookname', $_REQUEST['keyword'], '='));
}
if(!empty($_REQUEST['class'])){
	$criteria->add(new Criteria('sortid', $_REQUEST['class'], '='));
	$obooktitle=$jieqiSort['obook'][$_REQUEST['class']]['caption'];
}else{
	$obooktitle=$jieqiLang['obook']['title_all_obook'];
}
$jieqiTpl->assign('obooktitle', $obooktitle.$jieqiLang['obook']['title_new_vip']);
$criteria->add(new Criteria('chaptertype', 0, '='));
$criteria->add(new Criteria('display', 0, '='));
$criteria->setSort('lastupdate');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['obook']['pagenum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['obook']['pagenum']);
$ochapter_handler->queryObjects($criteria);
$ochapterrows=array();
$k=0;
while($v = $ochapter_handler->getObject()){
	$ochapterrows[$k]['checkbox']='<input type="checkbox" id="checkid'.$k.'" name="checkid'.$k.'" value="'.$v->getVar('obookid').'">';  //选择框
	$ochapterrows[$k]['checkid']=$k;  //显示序号
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
	$ochapterrows[$k]['sort']=isset($jieqiSort['obook'][$v->getVar('sortid')]['caption']) ? $jieqiSort['obook'][$v->getVar('sortid')]['caption'] : '';  //类别

	$ochapterrows[$k]['size']=$v->getVar('size');
	$ochapterrows[$k]['size_k']=ceil($v->getVar('size')/1024);
	$ochapterrows[$k]['size_c']=ceil($v->getVar('size')/2);
	$ochapterrows[$k]['saleprice']=$v->getVar('saleprice');
	$ochapterrows[$k]['vipprice']=$v->getVar('vipprice');

	$ochapterrows[$k]['postdate']=date('y-m-d',$v->getVar('postdate')); //发布日期
	$ochapterrows[$k]['lastupdate']=date('y-m-d',$v->getVar('lastupdate')); //最后更新日期

	$k++;
}

$jieqiTpl->assign_by_ref('ochapterrows', $ochapterrows);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($ochapter_handler->getCount($criteria),$jieqiConfigs['obook']['pagenum'],$_REQUEST['page']);
$pagelink='';
if(!empty($_REQUEST['class'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='class='.$_REQUEST['class'];
}
if(!empty($_REQUEST['keyword'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='keyword='.$_REQUEST['keyword'];
	$pagelink.='&keytype='.$_REQUEST['keytype'];
}
if(empty($pagelink)) $pagelink.='?page=';
else $pagelink.='&page=';
$jumppage->setlink($obook_dynamic_url.'/chapterlist.php'.$pagelink, false, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/ochapterlist.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>