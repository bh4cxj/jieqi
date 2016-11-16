<?php 
/**
 * 增加章节内部处理流程
 *
 * 增加章节内部处理流程
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: addchapter.php 231 2008-11-27 08:46:26Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;
include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
$chapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
$newChapter = $chapter_handler->create();
$chaptercount=$obook->getVar('chapters');
if(empty($_POST['volumeid'])) $_POST['volumeid']=$chaptercount+1;
//如果是插入章节，则原来章节的序号加一位
if($_POST['volumeid']<=$chaptercount){
	$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['aid']));
	$criteria->add(new Criteria('chapterorder', $_POST['volumeid'], '>='));
	$chapter_handler->updatefields('chapterorder=chapterorder+1', $criteria);
	unset($criteria);
}

if(!empty($_POST['chaptercontent'])){
	$chaptersize=strlen(str_replace(array(" ","　","\r","\n"),'',$_POST['chaptercontent']));
	if(trim($_POST['saleprice']) != '' && jieqi_checkpower($jieqiPower['obook']['customprice'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
		$_POST['saleprice']=intval($_POST['saleprice']);
	}elseif(is_numeric($jieqiConfigs['obook']['wordsperegold']) && $jieqiConfigs['obook']['wordsperegold']>0){
		$jieqiConfigs['obook']['wordsperegold']=ceil($jieqiConfigs['obook']['wordsperegold']) * 2;
		if($jieqiConfigs['obook']['priceround']==1) $_POST['saleprice']=floor($chaptersize / $jieqiConfigs['obook']['wordsperegold']);
		elseif($jieqiConfigs['obook']['priceround']==2) $_POST['saleprice']=ceil($chaptersize / $jieqiConfigs['obook']['wordsperegold']);
		else $_POST['saleprice']=round($chaptersize / $jieqiConfigs['obook']['wordsperegold']);
	}else{
		$_POST['saleprice']=0;
	}
}else{
	$chaptersize=0;
	$_POST['saleprice']=0;
}
//$chaptersize=strlen($_POST['chaptercontent']);
$newChapter->setVar('siteid', JIEQI_SITE_ID);
$newChapter->setVar('obookid', $obook->getVar('obookid', 'n'));
$newChapter->setVar('postdate', JIEQI_NOW_TIME);
$newChapter->setVar('lastupdate', JIEQI_NOW_TIME);
$newChapter->setVar('buytime', 0);
$newChapter->setVar('obookname', $obook->getVar('obookname', 'n'));
$newChapter->setVar('chaptername', $_POST['chaptername']);
$newChapter->setVar('chapterorder', $_POST['volumeid']);
$newChapter->setVar('ointro', '');
$newChapter->setVar('size', $chaptersize);
$newChapter->setVar('volumeid', 0);
if(!empty($_SESSION['jieqiUserId'])){
	$newChapter->setVar('posterid', $_SESSION['jieqiUserId']);
	$newChapter->setVar('poster', $_SESSION['jieqiUserName']);
}else{
	$newChapter->setVar('posterid', 0);
	$newChapter->setVar('poster', '');
}
$newChapter->setVar('toptime', 0);
$newChapter->setVar('picflag', 0);
if($chaptertype==2){
	$newChapter->setVar('chaptertype', 1);
	$newChapter->setVar('saleprice', 0);
    $newChapter->setVar('vipprice', 0);
}else{
	$newChapter->setVar('chaptertype', 0);
	$newChapter->setVar('saleprice', intval($_POST['saleprice']));
    $newChapter->setVar('vipprice', intval($_POST['saleprice']));
}
$newChapter->setVar('sumegold', 0);
$newChapter->setVar('sumesilver', 0);
$newChapter->setVar('normalsale', 0);
$newChapter->setVar('vipsale', 0);
$newChapter->setVar('freesale', 0);
$newChapter->setVar('bespsale', 0);
$newChapter->setVar('totalsale', 0);
$newChapter->setVar('daysale', 0);
$newChapter->setVar('weeksale', 0);
$newChapter->setVar('monthsale', 0);
$newChapter->setVar('allsale', 0);
$newChapter->setVar('lastsale', 0);
$newChapter->setVar('canvip', 0);
$newChapter->setVar('canfree', 0);
$newChapter->setVar('canbesp', 0);
$newChapter->setVar('state', 0);
$newChapter->setVar('flag', 0);
$newChapter->setVar('display', 0);
if (!$chapter_handler->insert($newChapter)) jieqi_printfail($jieqiLang['obook']['add_chapter_failure']);
else {
	if($chaptertype != 2){
		//增加或插入章节，最新卷可能也会变化
		//暂时默认插入的章节就是本卷最后章节，否则最新章节可能不是插入的章节
		$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['aid']));
		$criteria->add(new Criteria('chapterorder', $_POST['volumeid'], '<'));
		$criteria->add(new Criteria('chaptertype', 1, '='));
		$criteria->setSort('chapterorder');
		$criteria->setOrder('DESC');
		$criteria->setLimit(1);
		$chapter_handler->queryObjects($criteria);
		$tmpchapter=$chapter_handler->getObject();
		if(is_object($tmpchapter)){
			$lastvolume=$tmpchapter->getVar('chaptername', 'n');
			$lastvolumeid=$tmpchapter->getVar('ochapterid', 'n');
		}else{
			$lastvolume='';
			$lastvolumeid=0;
		}
		unset($tmpchapter);
		unset($criteria);

		$obook->setVar('lastchapter', $_POST['chaptername']);
		$obook->setVar('lastchapterid', $newChapter->getVar('ochapterid', 'n'));
		//插入章节时，卷也可能变化
		if($obook->getVar('lastvolumeid') != $lastvolumeid){
			$obook->setVar('lastvolume', $lastvolume);
			$obook->setVar('lastvolumeid', $lastvolumeid);
		}
		//保存章节内容
		include_once($jieqiModules['obook']['path'].'/class/ocontent.php');
        $content_handler =& JieqiOcontentHandler::getInstance('JieqiOcontentHandler');
		$newContent = $content_handler->create();
		$newContent->setVar('ochapterid', $newChapter->getVar('ochapterid', 'n'));
		$newContent->setVar('ocontent', $_POST['chaptercontent']);
		$content_handler->insert($newContent);
	}else{
		//增加分卷，最新卷可能也会变化
		$criteria=new CriteriaCompo(new Criteria('obookid', $_REQUEST['aid']));
		$criteria->add(new Criteria('chapterorder', $_POST['volumeid'], '>'));
		$criteria->add(new Criteria('chaptertype', 0, '='));
		$criteria->setSort('chapterorder');
		$criteria->setOrder('DESC');
		$criteria->setLimit(1);
		$chapter_handler->queryObjects($criteria);
		$tmpchapter=$chapter_handler->getObject();
		if(is_object($tmpchapter)){
			if($tmpchapter->getVar('ochapterid', 'n') == $obook->getVar('lastchapterid', 'n')){
				$obook->setVar('lastvolume', $_POST['chaptername']);
				$obook->setVar('lastvolumeid', $newChapter->getVar('ochapterid', 'n'));
			}
		}
		unset($tmpchapter);
		unset($criteria);
	}
	$obook->setVar('chapters', $obook->getVar('chapters')+1);
	$obook->setVar('size', $obook->getVar('size')+$chaptersize);
	if($chaptertype==1){
		$obook->setVar('fullflag', 1);
	}
	$obook->setVar('lastupdate', JIEQI_NOW_TIME);
	$obook_handler->insert($obook);
	//更新最新文章
	if($chaptertype != 2 && $obook->getVar('display')=='0'){
		jieqi_getcachevars('obook', 'obookuplog');
		if(!is_array($jieqiObookuplog)) $jieqiObookuplog=array('obookuptime'=>0, 'chapteruptime'=>0);
		$jieqiObookuplog['chapteruptime']=JIEQI_NOW_TIME;
		jieqi_setcachevars('obookuplog', 'jieqiObookuplog', $jieqiObookuplog, 'obook');
	}
	if($from_draft) $draft_handler->delete($delid);
	//增加章节积分
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
	$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
	$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

	if(!empty($jieqiConfigs['obook']['scorechapter'])){
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['obook']['scorechapter'], true);
	}

	jieqi_jumppage($obook_static_url.'/obookmanage.php?id='.$_REQUEST['aid'], LANG_DO_SUCCESS, $jieqiLang['obook']['add_chapter_success']);
}

?>