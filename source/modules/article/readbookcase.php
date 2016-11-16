<?php 
/**
 * 阅读书架中的文章
 *
 * 点击书架中文章后记录阅读标志，并跳转到阅读页面
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: readbookcase.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['aid']) && empty($_REQUEST['oid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['aid']=intval($_REQUEST['aid']);
//更新书架的最后访问
if(!empty($_REQUEST['bid'])){
	include_once($jieqiModules['article']['path'].'/class/bookcase.php');
	$bookcase_handler =& JieqiBookcaseHandler::getInstance('JieqiBookcaseHandler');
	$bookcase_handler->db->query('UPDATE '.jieqi_dbprefix('article_bookcase').' SET lastvisit='.JIEQI_NOW_TIME.' WHERE caseid='.$_REQUEST['bid']);
}
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

if(!empty($_REQUEST['oid'])){
	if(!empty($_REQUEST['ocid'])) $gourl = '/reader.php?aid='.$_REQUEST['oid'].'&cid='.$_REQUEST['ocid'];
	else $gourl = '/obookread.php?oid='.$_REQUEST['oid'].'&page=indexaid=';
	if(!empty($_REQUEST['rooturl'])) $gourl=$_REQUEST['rooturl'].$gourl;
	else $gourl=$jieqiModules['obook']['url'].$gourl;
	header('Location: '.$gourl);
	jieqi_upbookcasevisit();
	exit;
}

if(!empty($_REQUEST['cid'])){
	include_once($jieqiModules['article']['path'].'/class/chapter.php');
	$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
	$criteria=new CriteriaCompo(new Criteria('chapterid', $_REQUEST['cid']));
	$criteria->add(new Criteria('articleid', $_REQUEST['aid']));
	$rowsnum = $chapter_handler->getCount($criteria);
	if($rowsnum>0){
		header('Location: '.jieqi_geturl('article', 'chapter', $_REQUEST['cid'], $_REQUEST['aid']));
		jieqi_upbookcasevisit();
		exit;
	}else{
		$_REQUEST['indexflag']=1;
	}
}
if(empty($_REQUEST['indexflag'])){
	header('Location: '.jieqi_geturl('article', 'article', $_REQUEST['aid'], 'info'));
}else{
	header('Location: '.jieqi_geturl('article', 'article', $_REQUEST['aid'], 'index'));
}
jieqi_upbookcasevisit();

//更新书架的最后访问
function jieqi_upbookcasevisit(){
	if(!empty($_REQUEST['bid'])){
		include_once($jieqiModules['article']['path'].'/class/bookcase.php');
		$bookcase_handler =& JieqiBookcaseHandler::getInstance('JieqiBookcaseHandler');
		$bookcase_handler->db->query('UPDATE '.jieqi_dbprefix('article_bookcase').' SET lastvisit='.JIEQI_NOW_TIME.' WHERE caseid='.$_REQUEST['bid']);
	}
}

?>