<?php
/**
 * 相册列表
 *
 * 相册列表
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/album.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
define('JIEQI_MODULE_NAME','space');
require_once('../../global.php');
include_once($jieqiModules['space']['path'].'/spaceheader.php');
jieqi_loadlang('setblogcat',JIEQI_MODULE_NAME);
//jieqi_getconfigs('space', 'blogblocks','jieqiBlocks');
//check power
if(empty($_REQUEST['uid'])) {
	jieqi_checklogin();
	$_REQUEST['uid'] = $_SESSION['jieqiUserId'];
}
		include_once($jieqiModules['space']['path'].'/spaceheader.php');
		space_get_blog_cat('image');
		$jieqiTpl->assign('image_cats',$blog_cats);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/album.html';
		include_once($jieqiModules['space']['path'].'/spacefooter.php');

?>