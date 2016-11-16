<?php 
/**
 * 章节调整排序
 *
 * 章节调整排序
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chaptersort.php 231 2008-11-27 08:46:26Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
if(empty($_REQUEST['aid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($_REQUEST['aid']);
if(!$obook) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);

jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人文章权限
$canedit=jieqi_checkpower($jieqiPower['obook']['manageallobook'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改电子书
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($obook->getVar('authorid')==$tmpvar || $obook->getVar('posterid')==$tmpvar || $obook->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['obook']['noper_manage_obook']);

$chaptercount=$obook->getVar('chapters');
if(!isset($_REQUEST['fromid']) || $_REQUEST['fromid']<1 || $_REQUEST['fromid']>$chaptercount || !isset($_REQUEST['toid']) || $_REQUEST['toid']<0 || $_REQUEST['toid']>$chaptercount){
	jieqi_printfail($jieqiLang['obook']['sort_par_error']);
}

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

//无需排序
if($_REQUEST['fromid']==$_REQUEST['toid'] || $_REQUEST['fromid']==$_REQUEST['toid']+1){
	jieqi_jumppage($obook_static_url.'/obookmanage.php?id='.$_REQUEST['aid'], LANG_DO_SUCCESS, $jieqiLang['obook']['chapter_sort_success']);
}else{
	include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
	$chapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
	$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['aid']));
	$criteria->add(new Criteria('chapterorder', $_REQUEST['fromid']));
	$chapter_handler->queryObjects($criteria);
	$chapter=$chapter_handler->getObject();
	unset($criteria);
	if($chapter){
		$cid=$chapter->getVar('ochapterid');
		if($_REQUEST['fromid']<$_REQUEST['toid']){
			$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['aid'], '='));
			$criteria->add(new Criteria('chapterorder', $_REQUEST['fromid'], '>'));
			$criteria->add(new Criteria('chapterorder', $_REQUEST['toid'], '<='));
			$chapter_handler->updatefields('chapterorder=chapterorder-1', $criteria);
			unset($criteria);
			$criteria=new CriteriaCompo(new Criteria('ochapterid', $cid, '='));
			$chapter_handler->updatefields('chapterorder='.$_REQUEST['toid'], $criteria);
			unset($criteria);
		}else{
			$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['aid'], '='));
			$criteria->add(new Criteria('chapterorder', $_REQUEST['fromid'], '<'));
			$criteria->add(new Criteria('chapterorder', $_REQUEST['toid'], '>'));
			$chapter_handler->updatefields('chapterorder=chapterorder+1', $criteria);
			unset($criteria);
			$criteria=new CriteriaCompo(new Criteria('ochapterid', $cid, '='));
			$chapter_handler->updatefields('chapterorder='.($_REQUEST['toid']+1), $criteria);
			unset($criteria);
		}

		//检查最新卷和最新章节
		$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['aid'], '='));
		$criteria->setSort('chapterorder');
		$criteria->setOrder('DESC');
		$chapter_handler->queryObjects($criteria);
		$v=$chapter_handler->getObject();
		if($v){
			$nolastchapter=true;
			$nolastvolume=true;
			$lastchapter='';
			$lastchapterid=0;
			$lastvolume='';
			$lastvolumeid=0;
			do{
				if(!$nolastchapter && !$nolastvolume) break;
				if($v->getVar('chaptertype')==1){
					if($nolastvolume){
						$lastvolume=$v->getVar('chaptername', 'n');
						$lastvolumeid=$v->getVar('ochapterid', 'n');
						$nolastvolume=false;
					}
				}else{
					if($nolastchapter){
						$lastchapter=$v->getVar('chaptername', 'n');
						$lastchapterid=$v->getVar('ochapterid', 'n');
						$nolastchapter=false;
					}
				}
			}while($v = $chapter_handler->getObject());
			$updateblock=false;
			if($obook->getVar('lastchapterid') != $lastchapterid){
				$obook->setVar('lastchapterid', $lastchapterid);
				$obook->setVar('lastchapter', $lastchapter);
				$updateblock=true;
			}
			if($obook->getVar('lastvolumeid') != $lastvolumeid){
				$obook->setVar('lastvolumeid', $lastvolumeid);
				$obook->setVar('lastvolume', $lastvolume);
				$updateblock=true;
			}
			if($updateblock){
				$obook_handler->insert($obook);
				//更新最新文章
				if($obook->getVar('display')=='0'){
					jieqi_getcachevars('obook', 'obookuplog');
	                if(!is_array($jieqiObookuplog)) $jieqiObookuplog=array('obookuptime'=>0, 'chapteruptime'=>0);
	                $jieqiObookuplog['chapteruptime']=JIEQI_NOW_TIME;
	                jieqi_setcachevars('obookuplog', 'jieqiObookuplog', $jieqiObookuplog, 'obook');
				}
			}
		}
		jieqi_jumppage($obook_static_url.'/obookmanage.php?id='.$_REQUEST['aid'], LANG_DO_SUCCESS, $jieqiLang['obook']['chapter_sort_success']);
	}else jieqi_printfail($jieqiLang['obook']['not_find_chapter']);
	
}
?>