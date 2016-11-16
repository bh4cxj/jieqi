<?php
/**
 * 后台程序尾包含文件
 *
 * 处理后台页面的最终显示
 * 
 * 调用模板：/templates/admin/main.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: footer.php 326 2009-02-04 00:26:22Z juny $
 */

if(!empty($jieqiTset['jieqi_contents_template'])){
	if(!isset($jieqiTset['jieqi_contents_cacheid'])) $jieqiTset['jieqi_contents_cacheid']=NULL;
	if(!isset($jieqiTset['jieqi_contents_compileid'])) $jieqiTset['jieqi_contents_compileid']=NULL;
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
$jieqiTpl->setCaching(0);
$jieqiTpl->display(JIEQI_ROOT_PATH.'/templates/admin/main.html');
//结束数据库连接
jieqi_freeresource();
?>