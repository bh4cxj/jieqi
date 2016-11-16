<?php 
/**
 * 阅读页面最后章节的下一页跳转
 *
 * 阅读页面最后章节的下一页跳转，默认跳转到目录页，或者vip章节页，也可以修改模板显示模板内容。
 * 
 * 调用模板：/modules/article/templates/lastchapter.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: lastchapter.php 337 2009-03-07 00:51:05Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['aid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['aid']=intval($_REQUEST['aid']);
$jumpurl='';
$hasobook=0;
$hasebook=0;
$hastbook=0;
//如果有关联电子书的处理
if(file_exists(JIEQI_ROOT_PATH.'/files/obook/articlelink')){
	$linkfile=JIEQI_ROOT_PATH.'/files/obook/articlelink'.jieqi_getsubdir($_REQUEST['aid']).'/'.$_REQUEST['aid'].'.php';
	if(file_exists($linkfile)){
		include_once($linkfile);
		jieqi_getconfigs('obook', 'configs');
		$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
		$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
		if(!empty($jieqiObookdata['ochapter']['0']['ochapterid'])) $jumpurl=$obook_static_url.'/modules/obook/reader.php?aid='.intval($jieqiObookdata['obook']['obookid']).'&cid='.intval($jieqiObookdata['ochapter']['0']['ochapterid']);
		else $jumpurl=$obook_static_url.'/modules/obook/obookread.php?oid='.intval($jieqiObookdata['obook']['obookid']).'&page=index';
		$hasobook=1;
	}
}
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
	$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
	$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
if(empty($jumpurl)){
	if($_REQUEST['dynamic']){
		$jumpurl=$article_static_url.'/reader.php?aid='.$_REQUEST['aid'];
	}else{
		$jumpurl=jieqi_uploadurl($jieqiConfigs['article']['htmldir'], $jieqiConfigs['article']['htmlurl'], 'article', $article_static_url).jieqi_getsubdir($_REQUEST['aid']).'/'.$_REQUEST['aid'].'/index'.$jieqiConfigs['article']['htmlfile'];
	}
}
if(file_exists($jieqiModules['article']['path'].'/templates/lastchapter.html')){
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('articleid',$_REQUEST['aid']);
	$jieqiTpl->assign('dynamic',$_REQUEST['dynamic']);
	$jieqiTpl->assign('hasebook',$hasebook);
	$jieqiTpl->assign('hasobook',$hasobook);
	$jieqiTpl->assign('hastbook',$hastbook);
	$jieqiTpl->assign('article_static_url',$article_static_url);
	$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
	$jieqiTpl->assign('jumpurl',$jumpurl);
	$jieqiTset['jieqi_page_template']=$jieqiModules['article']['path'].'/templates/lastchapter.html';
	include_once(JIEQI_ROOT_PATH.'/footer.php');
}else{
	header('Location: '.$jumpurl);
}
?>