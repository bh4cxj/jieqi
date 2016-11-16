<?php
/**
 * 照片上传
 *
 * 照片上传
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/upalbum.html
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
jieqi_loadlang('header',JIEQI_MODULE_NAME);
jieqi_getconfigs('space', 'blogblocks','jieqiBlocks');
//check power
jieqi_checklogin();
if(!$space_hoster){
	jieqi_printfail($jieqiLang['space']['no_right']);
}
//验证相册开始
        $_REQUEST['catid']=intval($_REQUEST['catid']);
		space_get_blog_cat('image');
		if($_REQUEST['catid']){
		   $catid = $_REQUEST['catid'];
		   $default_cat_id = $catid;
		}else {
		   $catid = $default_cat_id;
		}
		if(!array_key_exists($catid,$blog_cats)) jieqi_printfail($jieqiLang['space']['no_this_user']);
//验证结束
		$jieqiTpl->assign('image_cats',$blog_cats);
		$jieqiTpl->assign('catid',$catid);
		$jieqiTpl->assign('default_cat_id',$default_cat_id);
		$jieqiTpl->assign('maximagesize',$jieqiConfigs['space']['maximagesize']);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/upalbum.html';
		include_once($jieqiModules['space']['path'].'/spacefooter.php');

?>