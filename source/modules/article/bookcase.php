<?php 
/**
 * 用户书架
 *
 * 书架页面，支持分类收藏
 * 
 * 调用模板：/modules/article/templates/bookcase.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: bookcase.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_checklogin();
jieqi_loadlang('bookcase', JIEQI_MODULE_NAME);

include_once(JIEQI_ROOT_PATH.'/header.php');
jieqi_getconfigs('article', 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

//处理删除
if(!empty($_REQUEST['delid'])){
	include_once($jieqiModules['article']['path'].'/class/bookcase.php');
	$bookcase_handler =& JieqiBookcaseHandler::getInstance('JieqiBookcaseHandler');
	$bookcase=$bookcase_handler->get($_REQUEST['delid']);
	if(is_object($bookcase)){
		if($bookcase->getVar('userid')==$_SESSION['jieqiUserId']){
			include_once($jieqiModules['article']['path'].'/class/article.php');
			$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
			$article_handler->db->query('UPDATE '.jieqi_dbprefix('article_article').' SET goodnum=goodnum-1 WHERE articleid='.$bookcase->getVar('articleid', 'n'));
			$bookcase_handler->delete($_REQUEST['delid']);
		}
	}
	unset($bookcase);
}
//处理改变分类
if(!empty($_REQUEST['checkid']) && isset($_REQUEST['newclassid'])){
	$_REQUEST['newclassid']=intval($_REQUEST['newclassid']);
	$checkids='';
	foreach($_REQUEST['checkid'] as $v){
		if(is_numeric($v)){
			$v=intval($v);
			if(!empty($checkids)) $checkids.=', ';
			$checkids.=$v;
		}
	}
	jieqi_includedb();
	$bookcase_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	if($_REQUEST['newclassid'] >= 0){
		//书架移动
		$sql="UPDATE ".jieqi_dbprefix('article_bookcase')." SET classid=".$_REQUEST['newclassid']." WHERE userid=".$_SESSION['jieqiUserId']." AND caseid IN (".$checkids.")";
		$bookcase_query->execute($sql);
	}else{
		//书架批量移除
		$sql="SELECT caseid, articleid FROM ".jieqi_dbprefix('article_bookcase')." WHERE userid=".$_SESSION['jieqiUserId']." AND caseid IN (".$checkids.")";
		$bookcase_query->execute($sql);
		$caseids='';
		$articleids='';
		while($crow = $bookcase_query -> getRow()){
			if(!empty($caseids)) $caseids.=', ';
			$caseids.=intval($crow['caseid']);
			if(!empty($articleids)) $articleids.=', ';
			$articleids.=intval($crow['articleid']);
		}
		
		$sql="DELETE FROM ".jieqi_dbprefix('article_bookcase')." WHERE caseid IN (".$caseids.")";
		$bookcase_query->execute($sql);
		$sql="UPDATE ".jieqi_dbprefix('article_article')." SET goodnum=goodnum-1 WHERE articleid IN (".$articleids.")";
		$bookcase_query->execute($sql);
	}
}
//分类设置
$_REQUEST['classid']=intval($_REQUEST['classid']);
if(empty($_REQUEST['classid'])) $_REQUEST['classid']=0;
$jieqiConfigs['article']['maxmarkclass']=intval($jieqiConfigs['article']['maxmarkclass']);
$jieqiTpl->assign('maxmarkclass', $jieqiConfigs['article']['maxmarkclass']);
$markclassrows=array();
for($i=1;$i<=$jieqiConfigs['article']['maxmarkclass'];$i++){
	$markclassrows[]['classid']=$i;
}
$jieqiTpl->assign_by_ref('markclassrows', $markclassrows);
//最大收藏数
jieqi_getconfigs('system', 'honors');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'right');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
$maxnum=$jieqiConfigs['article']['maxbookmarks'];
$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
if($honorid && isset($jieqiRight['article']['maxbookmarks']['honors'][$honorid]) && is_numeric($jieqiRight['article']['maxbookmarks']['honors'][$honorid])) $maxnum = intval($jieqiRight['article']['maxbookmarks']['honors'][$honorid]);

$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');
jieqi_includedb();
$bookcase_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');

$criteria=new CriteriaCompo(new Criteria('userid', $_SESSION['jieqiUserId']));
$criteria->setTables(jieqi_dbprefix('article_bookcase'));
$jieqiTpl->assign('nowbookcase', $bookcase_query->getCount($criteria));
unset($criteria);
$criteria=new CriteriaCompo(new Criteria('c.userid', $_SESSION['jieqiUserId']));
if(is_numeric($_REQUEST['classid'])) $criteria->add(new Criteria('c.classid', $_REQUEST['classid']));
if($jieqiModules['obook']['publish']){
	//有电子书时候查询电子书最新章节
	$criteria->setTables(jieqi_dbprefix('article_bookcase').' c LEFT JOIN '.jieqi_dbprefix('article_article').' a ON c.articleid=a.articleid LEFT JOIN '.jieqi_dbprefix('obook_obook').' o ON a.articleid=o.articleid');
	$criteria->setFields('c.*, a.articleid, a.lastupdate, a.articlename, a.lastchapterid, a.lastchapter, o.obookid, o.lastvolume as obookvolume, o.lastvolumeid as obookvolumeid, o.lastchapter as obookchapter, o.lastchapterid as obookchapterid, o.lastupdate as obookupdate, o.size as obooksize, o.publishid as obookpublishid');
	$criteria->setSort('o.lastupdate DESC, a.lastupdate');
	$criteria->setOrder('DESC');
}else{
	$criteria->setTables(jieqi_dbprefix('article_bookcase').' c LEFT JOIN '.jieqi_dbprefix('article_article').' a ON c.articleid=a.articleid');
	$criteria->setFields('c.*, a.articleid, a.lastupdate, a.articlename, a.authorid, a.author, a.sortid, a.typeid, a.lastchapterid, a.lastchapter');
	$criteria->setSort('a.lastupdate');
	$criteria->setOrder('DESC');
}
$bookcase_query->queryObjects($criteria);
unset($criteria);
$bookcaserows=array();
$k=0;
while($v = $bookcase_query->getObject()){
	//电子书部分信息
	if($jieqiModules['obook']['publish']){
		$bookcaserows[$k]['obookid']=$v->getVar('obookid');
		$bookcaserows[$k]['obookvolume']=$v->getVar('obookvolume');
		$bookcaserows[$k]['obookvolumeid']=$v->getVar('obookvolumeid');
		$bookcaserows[$k]['obookchapter']=$v->getVar('obookchapter');
		$bookcaserows[$k]['obookchapterid']=$v->getVar('obookchapterid');
		$bookcaserows[$k]['obookupdate']=$v->getVar('obookupdate');
		$bookcaserows[$k]['obooksize']=$v->getVar('obooksize');
		$bookcaserows[$k]['obookpublishid']=$v->getVar('obookpublishid');
		$bookcaserows[$k]['lastobookdate']=date(JIEQI_DATE_FORMAT, $v->getVar('obookupdate'));
	}
	$bookcaserows[$k]['caseid']=$v->getVar('caseid');
	$bookcaserows[$k]['articleid']=$v->getVar('articleid');
	$bookcaserows[$k]['lastchapterid']=$v->getVar('lastchapterid');
	$bookcaserows[$k]['chapterid']=$v->getVar('chapterid');
	$bookcaserows[$k]['sortid']=$v->getVar('sortid');
	$bookcaserows[$k]['typeid']=$v->getVar('typeid');
	$bookcaserows[$k]['sort']=$jieqiSort['article'][$v->getVar('sortid')]['shortname'];
	$bookcaserows[$k]['type']=$jieqiSort['article'][$v->getVar('sortid')]['types'][$v->getVar('typeid')];
	$bookcaserows[$k]['authorid']=$v->getVar('authorid');
	$bookcaserows[$k]['author']=$v->getVar('author');

	$bookcaserows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('caseid').'">';
	$tmpvar=$v->getVar('articlename');
	if(!empty($tmpvar)) {
		$bookcaserows[$k]['url_articleinfo']=$article_dynamic_url.'/readbookcase.php?aid='.$v->getVar('articleid').'&bid='.$v->getVar('caseid');
		$bookcaserows[$k]['url_index']=$bookcaserows[$k]['url_articleinfo'].'&indexflag=1';
		$bookcaserows[$k]['articlename']=$v->getVar('articlename');
	}else{
		$bookcaserows[$k]['url_articleinfo']='#';
		$bookcaserows[$k]['url_index']='#';
		$bookcaserows[$k]['articlename']=$jieqiLang['article']['articlemark_has_deleted'];
	}

	if($v->getVar('lastchapter')==''){
		$bookcaserows[$k]['lastchapter']='';
		$bookcaserows[$k]['url_lastchapter']='#';
	}else{
		$bookcaserows[$k]['lastchapter']=$v->getVar('lastchapter');
		$bookcaserows[$k]['url_lastchapter']=$article_dynamic_url.'/readbookcase.php?aid='.$v->getVar('articleid').'&bid='.$v->getVar('caseid').'&cid='.$v->getVar('lastchapterid');
	}
	if($v->getVar('lastupdate')>$v->getVar('lastvisit')) $bookcaserows[$k]['hasnew']=1;
	else $bookcaserows[$k]['hasnew']=0;

	if($v->getVar('chaptername')==''){
		$bookcaserows[$k]['articlemark']='';
		$bookcaserows[$k]['url_articlemark']='#';
	}else{
		$bookcaserows[$k]['articlemark']=$v->getVar('chaptername');
		$bookcaserows[$k]['url_articlemark']=$article_dynamic_url.'/readbookcase.php?aid='.$v->getVar('articleid').'&bid='.$v->getVar('caseid').'&cid='.$v->getVar('chapterid');
	}
	$bookcaserows[$k]['lastupdate']=$v->getVar('lastupdate');
	$bookcaserows[$k]['url_delete']=jieqi_addurlvars(array('delid'=>$v->getVar('caseid')));
	$k++;
}
$jieqiTpl->assign_by_ref('bookcaserows', $bookcaserows);
$jieqiTpl->assign('maxbookcase', $maxnum);
$jieqiTpl->assign('classbookcase', count($bookcaserows));
$jieqiTpl->assign('classid', $_REQUEST['classid']);

//<!--jieqi insert license check-->

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/bookcase.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>