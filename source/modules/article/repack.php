<?php 
/**
 * 文章重新生成
 *
 * 执行一篇文章的重新生成阅读格式功能
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: repack.php 313 2008-12-31 09:03:22Z juny $
 */

define('JIEQI_USE_GZIP','0');
define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
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
@set_time_limit(3600);
@session_write_close();
if(!is_array($_REQUEST['packflag']) || count($_REQUEST['packflag'])<1){
    jieqi_printfail($jieqiLang['article']['need_repack_option']);	
}else{
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
	$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
    $article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
	include_once($jieqiModules['article']['path'].'/include/repack.php');
	
	$ptypes=array();
	foreach($_REQUEST['packflag'] as $v) $ptypes[$v]=1;
	echo '                                                                                                                                                                                                                                                                ';
	echo $jieqiLang['article']['wait_to_execute'];
	ob_flush();
	flush();
	$ret=article_repack($_REQUEST['id'], $ptypes);
	
	jieqi_jumppage($article_static_url.'/articlemanage.php?id='.$_REQUEST['id'], LANG_DO_SUCCESS, $jieqiLang['article']['article_repack_success']);
	
}

?>