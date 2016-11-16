<?php 
/**
 * 电子书阅读目录
 *
 * 电子书阅读目录
 * 
 * 调用模板：/modules/obook/templates/obookindex.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obookread.php 231 2008-11-27 08:46:26Z juny $
 */

$logstart = explode(' ', microtime());
define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
if(empty($_REQUEST['oid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_checklogin();
jieqi_loadlang('obook', JIEQI_MODULE_NAME);
$_REQUEST['oid']=intval($_REQUEST['oid']);
if(isset($_REQUEST['cid'])) $_REQUEST['cid']=intval($_REQUEST['cid']);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
$ochapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['oid']));
$criteria->add(new Criteria('display', 0));
$criteria->setSort('chapterorder');
$criteria->setOrder('ASC');
$ochapter_handler->queryObjects($criteria);
unset($criteria);
$ochapterary=array();
$i=0;
$previewid=0;
$nextid=0;
$tempid=0;
$findcid=false;
while($ochapter=$ochapter_handler->getObject()){
	$ochapterary[$i]['ochapterid']=$ochapter->getVar('ochapterid');
	$ochapterary[$i]['chaptername']=$ochapter->getVar('chaptername');
	$ochapterary[$i]['chaptertype']=$ochapter->getVar('chaptertype');
	$ochapterary[$i]['lastupdate']=date(JIEQI_DATE_FORMAT, $ochapter->getVar('lastupdate'));
	$ochapterary[$i]['size']=$ochapter->getVar('size');
	$ochapterary[$i]['saleprice']=$ochapter->getVar('saleprice');
	
	if($ochapterary[$i]['chaptertype']==0){
		if($_REQUEST['cid']==$ochapterary[$i]['ochapterid']){
			$findcid=true;
			$previewid=$tempid;
		}else{
			if($findcid==true){
				$nextid=$ochapterary[$i]['ochapterid'];
				$findcid=false;
			}
		}
		$tempid=$ochapterary[$i]['ochapterid'];
	}
	$i++;
}

if(!empty($_REQUEST['cid']) && ($_REQUEST['page']=='preview' || $_REQUEST['page']=='next')){
	$newcid=0;
	if($_REQUEST['page']=='preview' && !empty($previewid)) $newcid=$previewid;
	elseif($_REQUEST['page']=='next' && !empty($nextid)) $newcid=$nextid;
	if($newcid>0){
		header('Location: '.$obook_static_url.'/reader.php?cid='.$newcid);
		exit;
	}
}

//显示目录
include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($_REQUEST['oid']);
if(!$obook) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
if($obook->getVar('display') != 0) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
include_once(JIEQI_ROOT_PATH.'/header.php');

$jieqiTpl->assign('static_url', $obook_static_url);
$jieqiTpl->assign('dynamic_url', $obook_dynamic_url);
$jieqiTpl->assign('url_obookroom', $obook_dynamic_url.'/');
$jieqiTpl->assign('url_obookinfo', $obook_dynamic_url.'/obookinfo.php?id='.$obook->getVar('obookid'));
	
$jieqiTpl->assign('copy_info',JIEQI_META_COPYRIGHT);
$jieqiTpl->assign('obookid',$obook->getVar('obookid'));
$jieqiTpl->assign('obookname',$obook->getVar('obookname'));
$jieqiTpl->assign('lastupdate', date(JIEQI_DATE_FORMAT, $obook->getVar('lastupdate')));
$jieqiTpl->assign('articleid',$obook->getVar('articleid'));
$jieqiTpl->assign('sortid',$obook->getVar('sortid'));
$jieqiTpl->assign('authorid',$obook->getVar('authorid'));
$jieqiTpl->assign('author',$obook->getVar('author'));
$jieqiTpl->assign('publishid',$obook->getVar('publishid'));

if(isset($jieqiConfigs['obook']['indexcols']) && $jieqiConfigs['obook']['indexcols']>0) $cols=intval($jieqiConfigs['obook']['indexcols']);
else $cols=4;
$i=0;
$k=0;
$ochapterrows=array();
foreach($ochapterary as $v){
	if($v['chaptertype']==1){
		$ochapterrows[$i]['chaptertype']='volume';
		$ochapterrows[$i]['vname']=$v['chaptername'];
		$ochapterrows[$i]['vid']=$v['ochapterid'];
		$k=0;
		$i++;
	}else{
		$k++;
		$ochapterrows[$i]['chaptertype']='chapter';
		$ochapterrows[$i]['cname'.$k]=$v['chaptername'];
		$ochapterrows[$i]['cid'.$k]=$v['ochapterid'];
		$ochapterrows[$i]['curl'.$k]=$obook_static_url.'/reader.php?cid='.$v['ochapterid'];
		$ochapterrows[$i]['cprice'.$k]=$v['saleprice'];
		$ochapterrows[$i]['csize'.$k]=$v['size'];
		$ochapterrows[$i]['clastupdate'.$k]=$v['lastupdate'];
		if($k>=$cols){
			$k=0;
			$i++;
		}
	}
}
$jieqiTpl->assign_by_ref('ochapterrows', $ochapterrows);
$jieqiTpl->setCaching(0);
$jieqiTpl->display($jieqiModules['obook']['path'].'/templates/obookindex.html');
?>