<?php 
/**
 * 文章管理页面
 *
 * 文章管理页面
 * 
 * 调用模板：/modules/article/templates/articlemanage.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: articlemanage.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_checklogin();
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['id']);
if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人文章权限
$canedit=jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改文章
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($article->getVar('authorid')==$tmpvar || $article->getVar('posterid')==$tmpvar || $article->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['article']['noper_manage_article']);
//包含区块参数
jieqi_getconfigs('article', 'authorblocks', 'jieqiBlocks');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
include_once(JIEQI_ROOT_PATH.'/header.php');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

//采集
$setting=unserialize($article->getVar('setting', 'n'));	$url_collect=$article_static_url.'/admin/collect.php?toid='.$article->getVar('articleid', 'n');
if(is_numeric($setting['fromarticle'])) $url_collect.='&fromid='.$setting['fromarticle'];
if(is_numeric($setting['fromsite'])) $url_collect.='&siteid='.$setting['fromsite'];
$jieqiTpl->assign('url_collect', $url_collect);

//文章属性
$jieqiTpl->assign('articleid', $article->getVar('articleid'));
$jieqiTpl->assign('articlename', $article->getVar('articlename'));
$jieqiTpl->assign('authorid', $article->getVar('authorid'));
$jieqiTpl->assign('author', $article->getVar('author'));
$jieqiTpl->assign('url_articleinfo', jieqi_geturl('article', 'article', $article->getVar('articleid'), 'info'));
$jieqiTpl->assign('url_articleindex', jieqi_geturl('article', 'article', $article->getVar('articleid'), 'index'));

if(!is_numeric($jieqiConfigs['article']['articlevote'])) $jieqiConfigs['article']['articlevote']=0;
else $jieqiConfigs['article']['articlevote']=intval($jieqiConfigs['article']['articlevote']);
$jieqiTpl->assign('articlevote', $jieqiConfigs['article']['articlevote']);


//章节属性
include_once($jieqiModules['article']['path'].'/class/chapter.php');
$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['id'], '='));
$criteria->setSort('chapterorder');
$criteria->setOrder('ASC');
$chapter_handler->queryObjects($criteria);
$i=0;
$chapterrows=array();
$k=0;
while($chapter = $chapter_handler->getObject()){
	$chapterrows[$k]['chapterid'] = $chapter->getVar('chapterid');
	$chapterrows[$k]['chaptertype'] = $chapter->getVar('chaptertype');
	$chapterrows[$k]['chaptername'] = $chapter->getVar('chaptername');
	$chapterrows[$k]['chapterorder'] = $chapter->getVar('chapterorder');
	
	$chapterrows[$k]['url_chapterread'] = $article_static_url.'/reader.php?aid='.$article->getVar('articleid').'&cid='.$chapter->getVar('chapterid');
	if($chapter->getVar('chaptertype')==0){
		$chapterrows[$k]['url_chapterread'] = jieqi_geturl('article', 'chapter', $chapter->getVar('chapterid'), $article->getVar('articleid'));
		
		$chapterrows[$k]['url_chapteredit'] = $article_static_url.'/chapteredit.php?id='.$chapter->getVar('chapterid').'&chaptertype=0';
		$chapterrows[$k]['url_chapterdelete'] = $article_static_url.'/chapterdel.php?id='.$chapter->getVar('chapterid').'&chaptertype=0';
	}else{
		$chapterrows[$k]['url_chapterread'] = $article_static_url.'/showvolume.php?aid='.$article->getVar('articleid').'&vid='.$chapter->getVar('chapterid');
		$chapterrows[$k]['url_chapteredit'] = $article_static_url.'/chapteredit.php?id='.$chapter->getVar('chapterid').'&chaptertype=1';
		$chapterrows[$k]['url_chapterdelete'] = $article_static_url.'/chapterdel.php?id='.$chapter->getVar('chapterid').'&chaptertype=1';
	}
	$k++;
}
$jieqiTpl->assign_by_ref('chapterrows', $chapterrows);

//功能属性
$jieqiTpl->assign('url_chaptersort', $article_static_url.'/chaptersort.php?do=submit');
$jieqiTpl->assign('url_chaptersdel', $article_static_url.'/chaptersdel.php?do=submit');
$jieqiTpl->assign('url_repack', $article_static_url.'/repack.php?do=submit');

$packflag = array();
if($jieqiConfigs['article']['makeopf']) $packflag[] = array('value'=>'makeopf', 'title'=>$jieqiLang['article']['repack_opf']);
if($jieqiConfigs['article']['makehtml']) $packflag[] = array('value'=>'makehtml', 'title'=>$jieqiLang['article']['repack_html']);
if($jieqiConfigs['article']['makezip']) $packflag[] = array('value'=>'makezip', 'title'=>$jieqiLang['article']['repack_zip']);
if($jieqiConfigs['article']['makefull']) $packflag[] = array('value'=>'makefull', 'title'=>$jieqiLang['article']['repack_fullpage']);
if($jieqiConfigs['article']['maketxtfull']) $packflag[] = array('value'=>'maketxtfull', 'title'=>$jieqiLang['article']['repack_txtfullpage']);
if($jieqiConfigs['article']['makeumd']) $packflag[] = array('value'=>'makeumd', 'title'=>$jieqiLang['article']['repack_umdpage']);
if($jieqiConfigs['article']['makejar']) $packflag[] = array('value'=>'makejar', 'title'=>$jieqiLang['article']['repack_jarpage']);
$jieqiTpl->assign_by_ref('packflag', $packflag);

$jieqiTpl->assign('authorarea', 1);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/articlemanage.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>