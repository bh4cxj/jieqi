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
//获取催更数据
$result = mysql_query("SELECT sum(nums) as t FROM `jieqi_go123_cuigeng` WHERE `articleid` = '$_REQUEST[id]' AND `STATUS` = '1'");
while ($cgzs = mysql_fetch_array($result,1)) {
	$zongshu = (INT)$cgzs['t']*0.7;
}
$jieqiTpl->assign('zongshu', $zongshu?$zongshu:0);//催更总数
//获取今日催更票
$year = date("Y");$month = date("m");$day = date("d");
$nowtime = time();
$begintime = mktime(0,0,0,$month,$day,$year);
$endtime = mktime(23,59,59,$month,$day,$year);
$results = mysql_query("SELECT * FROM `jieqi_go123_cuigeng` WHERE `articleid` = '$_REQUEST[id]' AND `dateline` > '$begintime' AND `dateline` < '$endtime';");
$todaynums = '0';
while ($xijie = mysql_fetch_array($results,1)) {
	$cuigengpiao[] = $xijie;
	$todaynums += $xijie['nums'];
}
$jieqiTpl->assign('cuigengpiao', $cuigengpiao);//今日催更
$jieqiTpl->assign('todaynums', $todaynums);//今日催更

$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/cuigengmanage.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');