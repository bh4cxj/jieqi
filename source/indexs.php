<?php
/**
 * 生成静态网站首页
 *
 * refresh参数用于强制更新；
 * template参数用于设置模板，默认是theme.html，设置了就在templates目录下找；
 * target参数用于设置生成的文件名 默认index.html；
 * refresh强制刷新；
 * 比如刷新静态首页，用 http://www.domain.com/indexs.php?refresh=1&template=index.html&target=index.html&blocks=blocks
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: indexs.php 326 2009-02-04 00:26:22Z juny $
 */


//本模块名
define('JIEQI_MODULE_NAME', 'system');
if(empty($_REQUEST['refresh'])) @define('JIEQI_CACHE_LIFETIME','0');
require_once('global.php');

if($jieqiUsersStatus != JIEQI_GROUP_ADMIN) $_REQUEST=array();
if(JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET || $jieqiUsersStatus != JIEQI_GROUP_ADMIN){
    if(isset($_REQUEST['refresh'])) unset($_REQUEST['refresh']);  //在繁简转换的时候不生成静态首页
}

//生成的页面
if(empty($_REQUEST['target']) || strlen($_REQUEST['target']) > 30) $_REQUEST['target']='index.html';

//有缓存则直接转向静态页面
if(JIEQI_ENABLE_CACHE && empty($_REQUEST['refresh']) && file_exists(JIEQI_ROOT_PATH.'/'.$_REQUEST['target']) && (JIEQI_NOW_TIME - filemtime(JIEQI_ROOT_PATH.'/'.$_REQUEST['target'])) < JIEQI_CACHE_LIFETIME){
	if(JIEQI_USE_CACHE){
		header('Location: '.JIEQI_URL.'/'.$_REQUEST['target']);
		exit;
	}else{
        ob_start();
        include_once(JIEQI_ROOT_PATH.'/'.$_REQUEST['target']);
		$content = ob_get_contents();
		ob_end_clean();
		echo preg_replace('/\<meta[^\<\>]*content[\s]*=[\s]*(\'|")?[^\/;]*\/[^\/;]*;[\s]*charset[\s]*=[\s]*(gb2312|gbk)(\'|")?[^\<\>]*\>/is', '<meta http-equiv="Content-Type" content="text/html; charset=big5" />', $content);
		exit;
	}
}

//包含页头页尾
include_once(JIEQI_ROOT_PATH.'/header.php');

//载入语言
jieqi_loadlang('index', JIEQI_MODULE_NAME);

if(!empty($_REQUEST['refresh']) && empty($_REQUEST['confirm'])){
	jieqi_msgwin(LANG_NOTICE, sprintf($jieqiLang['system']['sindex_confirm_notice'], jieqi_addurlvars(array('confirm'=>1))));
}

//包含区块参数
if(empty($_REQUEST['blocks']) || strlen($_REQUEST['blocks']) > 30) $_REQUEST['blocks']='blocks';
jieqi_getconfigs('system', $_REQUEST['blocks'], 'jieqiBlocks');

//设置该页面的模板文件
if(!empty($_REQUEST['template']) && strlen($_REQUEST['template'])<=30) $jieqiTset['jieqi_page_template']=JIEQI_ROOT_PATH.'/templates/'.trim($_REQUEST['template']);
else $jieqiTset['jieqi_page_template']='';
if(empty($jieqiTset['jieqi_page_template']) || !is_file($jieqiTset['jieqi_page_template'])) $jieqiTset['jieqi_page_template']=JIEQI_ROOT_PATH.'/themes/'.JIEQI_THEME_NAME.'/theme.html';


$jieqiTpl->assign('jieqi_indexpage',1);
$jieqiTpl->assign('jieqi_contents','');

//开始footer.php
//预处理主内容模板
if(!empty($jieqiTset['jieqi_contents_template'])){
	if(!isset($jieqiTset['jieqi_contents_cacheid'])) $jieqiTset['jieqi_contents_cacheid']=NULL;
	if(!isset($jieqiTset['jieqi_contents_compileid'])) $jieqiTset['jieqi_contents_compileid']=NULL;
	$jieqiTpl->include_compiled_inc($jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_compileid']);
}

//根据模板包含区块配置文件
if(!empty($jieqiTset['jieqi_blocks_config'])){
	if(!empty($jieqiTset['jieqi_blocks_module'])) jieqi_getconfigs($jieqiTset['jieqi_blocks_module'], $jieqiTset['jieqi_blocks_config'], 'jieqiBlocks');
	else jieqi_getconfigs(JIEQI_MODULE_NAME, $jieqiTset['jieqi_blocks_config'], 'jieqiBlocks');
}

//区块处理
if(!isset($jieqi_showlblock)) $jieqi_showlblock = false;
if(!isset($jieqi_showcblock)) $jieqi_showcblock = false;
if(!isset($jieqi_showrblock)) $jieqi_showrblock = false;
if(!isset($jieqi_showtblock)) $jieqi_showtblock = false;
if(!isset($jieqi_showbblock)) $jieqi_showbblock = false;

//如果包含区块显示参数则显示
if (isset($jieqiBlocks) && is_array($jieqiBlocks)){
	reset($jieqiBlocks);
	//遍历所有区块
	while(list($i) = each($jieqiBlocks)){
		$blockindex = (empty($jieqiBlocks[$i]['bid'])) ? 'bid'.$i : 'bid'.$jieqiBlocks[$i]['bid'];
		$blockvalue = jieqi_get_block($jieqiBlocks[$i]);
		if(!empty($blockvalue)){
			$jieqi_pageblocks[$blockindex] = $blockvalue;
			${$jieqi_blockside}[] = &$jieqi_pageblocks[$blockindex];
		}
		
	}
	unset($blockindex);
	unset($blockvalue);
	unset($jieqiBlocks);
}

$jieqi_showblock=$jieqi_showlblock | $jieqi_showcblock | $jieqi_showrblock | $jieqi_showtblock | $jieqi_showbblock;

$jieqiTpl->assign('jieqi_showblock',intval($jieqi_showblock));
if(isset($jieqi_pageblocks)) $jieqiTpl->assign_by_ref('jieqi_pageblocks', $jieqi_pageblocks);
if($jieqi_showlblock){
	$jieqiTpl->assign('jieqi_showlblock',1);
	if(isset($jieqi_lblocks) && is_array($jieqi_lblocks)) $jieqiTpl->assign_by_ref('jieqi_lblocks', $jieqi_lblocks);
}else{
	$jieqiTpl->assign('jieqi_showlblock',0);
}
if($jieqi_showcblock){
	$jieqiTpl->assign('jieqi_showcblock',1);
	if(isset($jieqi_clblocks) && is_array($jieqi_clblocks)) $jieqiTpl->assign_by_ref('jieqi_clblocks', $jieqi_clblocks);
	if(isset($jieqi_crblocks) && is_array($jieqi_crblocks)) $jieqiTpl->assign_by_ref('jieqi_crblocks', $jieqi_crblocks);
	if(isset($jieqi_ctblocks) && is_array($jieqi_ctblocks)) $jieqiTpl->assign_by_ref('jieqi_ctblocks', $jieqi_ctblocks);
	if(isset($jieqi_cmblocks) && is_array($jieqi_cmblocks)) $jieqiTpl->assign_by_ref('jieqi_cmblocks', $jieqi_cmblocks);
	if(isset($jieqi_cbblocks) && is_array($jieqi_cbblocks)) $jieqiTpl->assign_by_ref('jieqi_cbblocks', $jieqi_cbblocks);
}else{
	$jieqiTpl->assign('jieqi_showcblock',0);
}
if($jieqi_showrblock){
	$jieqiTpl->assign('jieqi_showrblock',1);
	if(isset($jieqi_rblocks) && is_array($jieqi_rblocks)) $jieqiTpl->assign_by_ref('jieqi_rblocks', $jieqi_rblocks);
}else{
	$jieqiTpl->assign('jieqi_showrblock',0);
}
if($jieqi_showtblock){
	$jieqiTpl->assign('jieqi_showtblock',1);
	if(isset($jieqi_tblocks) && is_array($jieqi_tblocks)) $jieqiTpl->assign_by_ref('jieqi_tblocks', $jieqi_tblocks);
}else{
	$jieqiTpl->assign('jieqi_showtblock',0);
}
if($jieqi_showbblock){
	$jieqiTpl->assign('jieqi_showbblock',1);
	if(isset($jieqi_bblocks) && is_array($jieqi_bblocks)) $jieqiTpl->assign_by_ref('jieqi_bblocks', $jieqi_bblocks);
}else{
	$jieqiTpl->assign('jieqi_showbblock',0);
}
//区块处理完成
//赋值主内容模板
if(!empty($jieqiTset['jieqi_contents_template'])){
	$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_cacheid'], $jieqiTset['jieqi_contents_compileid']));
}

//使用ajax获取某个变量直接输出
if(!empty($_REQUEST['ajax_request']) && !empty($_REQUEST['ajax_gets'])){
	header('Content-Type:text/html; charset='.JIEQI_CHAR_SET); 
	header("Cache-Control:no-cache");
	if(is_array($_REQUEST['ajax_gets'])){
		$out_var=array();
		foreach($_REQUEST['ajax_gets'] as $v) if(isset($jieqiTpl->_tpl_vars[$v])) $out_var[$v]=&$jieqiTpl->_tpl_vars[$v];
	}else{
		if(isset($jieqiTpl->_tpl_vars[$_REQUEST['ajax_gets']])) $out_var=&$jieqiTpl->_tpl_vars[$_REQUEST['ajax_gets']];
		else $out_var='';
	}
	if(is_array($out_var)) echo serialize($out_var);
	echo $out_var;
	exit;
}

$tmpvar = explode(' ', microtime());
$jieqiTpl->assign('jieqi_exetime', round($tmpvar[1] + $tmpvar[0] - JIEQI_START_TIME, 6));

$jieqiTpl->setCaching(0);
$pagecontent = &$jieqiTpl->fetch($jieqiTset['jieqi_page_template']);

if(empty($_REQUEST['refresh'])){
	echo $pagecontent;
	if(JIEQI_ENABLE_CACHE && JIEQI_CHAR_SET == JIEQI_SYSTEM_CHARSET) jieqi_writefile(JIEQI_ROOT_PATH.'/'.$_REQUEST['target'], $pagecontent);
}else{
	if(JIEQI_ENABLE_CACHE && JIEQI_CHAR_SET == JIEQI_SYSTEM_CHARSET){
		$ret=jieqi_writefile(JIEQI_ROOT_PATH.'/'.$_REQUEST['target'], $pagecontent);
		//if($ret) jieqi_jumppage(JIEQI_URL.'/'.$_REQUEST['target'], LANG_DO_SUCCESS, $jieqiLang['system']['make_sindex_success']);
		if($ret) jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['system']['make_static_success'], JIEQI_URL.'/'.$_REQUEST['target']));
		else jieqi_printfail(sprintf($jieqiLang['system']['make_static_failure'], $_REQUEST['target']));
	}else{
		if(!JIEQI_ENABLE_CACHE) jieqi_printfail($jieqiLang['system']['sindex_need_cache']);
		else jieqi_printfail(sprintf($jieqiLang['system']['sindex_need_charset'], JIEQI_CHAR_SET));
	}
}

//结束数据库连接
jieqi_freeresource();
?>