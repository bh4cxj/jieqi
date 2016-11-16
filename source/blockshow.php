<?php 
/**
 * 显示一个区块
 *
 * 根据区块参数显示一个区块，可以是html代码直接输出或者输出成javascript代码
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: blockshow.php 332 2009-02-23 09:15:08Z juny $
 */
define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');

if(JIEQI_VERSION_TYPE == '' || JIEQI_VERSION_TYPE == 'Free' || JIEQI_VERSION_TYPE == 'Popular') exit('//error version type='.JIEQI_VERSION_TYPE); //普及版不支持

if(!isset($_GET['module']) || !preg_match('/^\w+$/', $_GET['module']) || (isset($_GET['filename']) && !preg_match('/^\w*$/', $_GET['filename'])) || !isset($_GET['classname']) || !preg_match('/^\w+$/', $_GET['classname']) || (isset($_GET['template']) && !preg_match('/^[\w\.]*$/', $_GET['template']))) exit('//error parameter');

if(!isset($_GET['bid']) || !is_numeric($_GET['bid'])) $_GET['bid']=0;
if(!isset($_GET['custom'])) $_GET['custom']=0;
if(!isset($_GET['filename'])){
	$_GET['filename']='block_custom';
	$_GET['custom']=1;
}
if(!isset($_GET['hasvars'])) $_GET['hasvars']=0;
if(!isset($_GET['vars'])) $_GET['vars']='';
if(!isset($_GET['publish'])) $_GET['publish']=3;
if(!isset($_GET['contenttype'])) $_GET['contenttype']=JIEQI_CONTENT_PHP;

include_once(JIEQI_ROOT_PATH.'/header.php');
$blockdata = jieqi_get_block($_GET, 1);

if(!empty($_REQUEST['ajax_request'])){
	$_GET['showtype']='html';
	header('Content-Type:text/html; charset='.JIEQI_CHAR_SET);
	header("Cache-Control:no-cache");
}

if($_GET['showtype']=='html') echo $blockdata;
else echo "document.write('".jieqi_setslashes(str_replace(array("\r","\n"),'',$blockdata),'"')."');";
?>