<?php
/**
 * 加入书架
 *
 * 加入书架或者书签的程序处理
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: addbookcase.php 231 2008-11-27 08:46:26Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
if(empty($_REQUEST['oid']) && empty($_REQUEST['cid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_checklogin();
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);
jieqi_loadlang('bookcase', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$maxnum=$jieqiConfigs['obook']['bookcasenum'];

include_once($jieqiModules['obook']['path'].'/class/obookcase.php');
$obookcase_handler =& JieqiObookcaseHandler::getInstance('JieqiObookcaseHandler');
$criteria=new CriteriaCompo(new Criteria('userid', $jieqiUsers->getVar('uid')));
$cot=$obookcase_handler->getCount($criteria);
unset($criteria);

if(!empty($_REQUEST['cid'])){
	//加入书签
	$obook_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	$criteria=new CriteriaCompo(new Criteria('c.ochapterid', $_REQUEST['cid']));
	$criteria->setTables(jieqi_dbprefix('obook_ochapter').' c LEFT JOIN '.jieqi_dbprefix('obook_obook').' a ON c.obookid=a.obookid');
	$obook_query->queryObjects($criteria);
	$chapter=$obook_query->getObject();
	unset($criteria);
	if(!$chapter) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
	$criteria=new CriteriaCompo(new Criteria('userid', $jieqiUsers->getVar('uid')));
	$criteria->add(new Criteria('obookid', $chapter->getVar('obookid')));
	$obookcase_handler->queryObjects($criteria);
	$obookcase=$obookcase_handler->getObject();
	if(!$obookcase){
		if($cot >= $maxnum) jieqi_printfail(sprintf($jieqiLang['obook']['bookcase_is_full'], $maxnum));
		//没有在书架
		//增加文章的收藏量
        $obook_query->execute('UPDATE '.jieqi_dbprefix('obook_obook').' SET goodnum=goodnum+1 WHERE obookid='.$chapter->getVar('obookid', 'n'));
		$obookcase=$obookcase_handler->create();
		$obookcase->setVar('joindate', JIEQI_NOW_TIME);
		$obookcase->setVar('lastvisit', JIEQI_NOW_TIME);
		$obookcase->setVar('flag', 0);
	}
	$obookcase->setVar('obookid', $chapter->getVar('obookid', 'n'));
	$obookcase->setVar('articleid', $chapter->getVar('articleid', 'n'));
	$obookcase->setVar('obookname', $chapter->getVar('obookname', 'n'));
	$obookcase->setVar('userid', $jieqiUsers->getVar('uid', 'n'));
	$obookcase->setVar('username', $jieqiUsers->getVar('uname', 'n'));
	$obookcase->setVar('ochapterid', $chapter->getVar('ochapterid', 'n'));
	$obookcase->setVar('chaptername', $chapter->getVar('chaptername', 'n'));
	$obookcase->setVar('chapterorder', $chapter->getVar('chapterorder', 'n'));
	if(!$obookcase_handler->insert($obookcase)) {
		jieqi_printfail($jieqiLang['obook']['add_chaptermark_failure']);
	}else{
		jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['obook']['add_chaptermark_success']);
	}
}elseif(!empty($_REQUEST['oid'])){
	//加入书架
	include_once($jieqiModules['obook']['path'].'/class/obook.php');
	$obook_handler =& JieqiobookHandler::getInstance('JieqiobookHandler');
	$obook=$obook_handler->get($_REQUEST['oid']);
	if(!$obook) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
	
	$criteria=new CriteriaCompo(new Criteria('userid', $jieqiUsers->getVar('uid')));
	$criteria->add(new Criteria('obookid', $obook->getVar('obookid')));
	$obookcase_handler->queryObjects($criteria);
	$obookcase=$obookcase_handler->getObject();
	if($obookcase){
		//已经在书架
		jieqi_printfail($jieqiLang['obook']['obook_has_incase']);
	}else{
		//没有在书架
		if($cot >= $maxnum) jieqi_printfail(sprintf($jieqiLang['obook']['bookcase_is_full'], $maxnum));
		//增加文章的收藏量
        $obook_handler->db->query('UPDATE '.jieqi_dbprefix('obook_obook').' SET goodnum=goodnum+1 WHERE obookid='.$_REQUEST['oid']);
		$obookcase=$obookcase_handler->create();
		$obookcase->setVar('joindate', JIEQI_NOW_TIME);
		$obookcase->setVar('lastvisit', JIEQI_NOW_TIME);
		$obookcase->setVar('flag', 0);
	}
	$obookcase->setVar('obookid', $obook->getVar('obookid', 'n'));
	$obookcase->setVar('articleid', $obook->getVar('articleid', 'n'));
	$obookcase->setVar('obookname', $obook->getVar('obookname', 'n'));
	$obookcase->setVar('userid', $jieqiUsers->getVar('uid', 'n'));
	$obookcase->setVar('username', $jieqiUsers->getVar('uname', 'n'));
	$obookcase->setVar('ochapterid', 0);
	$obookcase->setVar('chaptername', '');
	$obookcase->setVar('chapterorder', 0);
	if(!$obookcase_handler->insert($obookcase)) {
		jieqi_printfail($jieqiLang['obook']['add_obookmark_failure']);
	}else{
		jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['obook']['add_obookmark_success']);
	}
	
}else{
	jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
}

?>