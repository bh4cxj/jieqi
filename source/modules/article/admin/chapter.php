<?php 
/**
 * 最近更新章节列表
 *
 * 最近更新章节列表
 * 
 * 调用模板：/modules/article/templates/admin/chapterlist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chapter.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['article']['viewuplog'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('list', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

include_once($jieqiModules['article']['path'].'/class/chapter.php');
$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$criteria=new CriteriaCompo();
if(!empty($_REQUEST['keyword'])){
	$_REQUEST['keyword']=trim($_REQUEST['keyword']);
	if($_REQUEST['keytype']==1) $criteria->add(new Criteria('poster', $_REQUEST['keyword'], '='));
	else $criteria->add(new Criteria('articlename', $_REQUEST['keyword'], '='));
}

$jieqiTpl->assign('articletitle', $jieqiLang['article']['chapter_update_list']);

$jieqiTpl->assign('url_chapter', $article_dynamic_url.'/admin/chapter.php');
$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');

//$criteria->setSort('lastupdate DESC, chapterid');
$criteria->setSort('chapterid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['article']['pagenum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['article']['pagenum']);
$chapter_handler->queryObjects($criteria);
$articlerows=array();
$k=0;
while($v = $chapter_handler->getObject()){
	$articlerows[$k]['checkbox']='<input type="checkbox" id="checkid'.$k.'" name="checkid'.$k.'" value="'.$v->getVar('articleid').'">';  //选择框
	$articlerows[$k]['checkid']=$k;  //显示序号
	$articlerows[$k]['chapterid']=$v->getVar('chapterid');  //文章序号
    $articlerows[$k]['articleid']=$v->getVar('articleid');  //文章序号
	$articlerows[$k]['articlename']=$v->getVar('articlename');  //文章名称
	$articlerows[$k]['chaptername']=$v->getVar('chaptername');  //章节名称
	$articlerows[$k]['chapterorder']=$v->getVar('chapterorder');  //章节排序
	$articlerows[$k]['posterid']=$v->getVar('posterid');  //发表者序号
	$articlerows[$k]['poster']=$v->getVar('poster');  //发表者
	$articlerows[$k]['postdate']=date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT,$v->getVar('postdate'));  //发表时间
	$articlerows[$k]['lastupdate']=date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT,$v->getVar('lastupdate'));  //更新时间
    $articlerows[$k]['size']=$v->getVar('size');  //字数
    $articlerows[$k]['size_k']=ceil($v->getVar('size')/1024);  //字数
    $articlerows[$k]['size_c']=ceil($v->getVar('size')/2);  //字数
	$articlerows[$k]['chaptertype']=$v->getVar('chaptertype');  //章节类型
	$articlerows[$k]['url_articleinfo']=jieqi_geturl('article', 'article', $v->getVar('articleid'), 'info');  //子目录
	if($articlerows[$k]['chaptertype']==0){
		 $articlerows[$k]['url_lastchapter']=jieqi_geturl('article', 'chapter', $v->getVar('chapterid'), $v->getVar('articleid'));
		$articlerows[$k]['typename']=$jieqiLang['article']['chapter_name'];
	}else{
        $articlerows[$k]['url_lastchapter']='#';
		$articlerows[$k]['typename']=$jieqiLang['article']['volume_name'];
	}
	$k++;
}

$jieqiTpl->assign_by_ref('articlerows', $articlerows);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($chapter_handler->getCount($criteria),$jieqiConfigs['article']['pagenum'],$_REQUEST['page']);
$pagelink='';
if(!empty($_REQUEST['keyword'])){
    if(empty($pagelink)) $pagelink.='?';
    else $pagelink.='&';
    $pagelink.='keyword='.$_REQUEST['keyword'];
    $pagelink.='&keytype='.$_REQUEST['keytype'];
}
if(empty($pagelink)) $pagelink.='?page=';
else $pagelink.='&page=';
$jumppage->setlink($article_dynamic_url.'/admin/chapter.php'.$pagelink, false, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/chapterlist.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>