<?php
/**
 * 添加新的博文
 *
 * 添加新的博文
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/createblog.html
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
jieqi_loadlang('createblog',JIEQI_MODULE_NAME);
//include hoster's blog cats
space_get_blog_cat();
//check power
if(!$space_hoster){
	jieqi_printfail($jieqiLang['space']['no_right']);
}

switch($_REQUEST['action']){
	case 'docreate':
		include_once($jieqiModules['space']['path'].'/class/blog.php');
		$blog_handler = &JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');
		$blog = $blog_handler->create();
		$cat_id = intval($_REQUEST['cat_id']);
		$blog->setVar('`title`',$_REQUEST['title']);
		$blog->setVar('`content`',$_REQUEST['content']);
		$blog->setVar('`cat_id`',$cat_id );
		$blog->setVar('`up_time`',JIEQI_NOW_TIME);
		$blog->setVar('`uid`',$uid);
		$blog->setVar('`name`',$_SESSION['jieqiUserName']);
		$blog->setVar('`ar_open`',intval($_REQUEST['ar_open']) );
		$blog->setVar('`allow_com`',intval($_REQUEST['allow_com']) );
		if($blog_handler->insert($blog) ){
			include_once($jieqiModules['space']['path'].'/class/space.php');
			$space_handler = JieqiSpaceHandler::getInstance('JieqiSpaceHandler');
			$criteria = new Criteria('uid',$uid);
			$space_handler->updatefields("`blog_num`=`blog_num`+1",$criteria);
			space_update_time();
			include_once($jieqiModules['space']['path'].'/class/blogcat.php');
			$blog_cat_handler = JieqiSpaceBlogCatHandler::getInstance('JieqiSpaceBlogCatHandler');
			$criteria = new Criteria('id',$cat_id);
			$blog_cat_handler->updatefields('`num`=`num`+1',$criteria);
			unset($criteria);
			space_make_blog_cat();
			jieqi_jumppage('./blog.php?uid='.$uid,$jieqiLang['space']['add_article_success'],$jieqiLang['space']['article_have_added'] );
		}else{
			jieqi_printfail($jieqiLang['space']['create_blog_fail']);
		}
		break;
	default:
		$jieqiTpl->assign('blog_cats',$blog_cats);
		if($cat_id = intval($_REQUEST['cat_id']) ){
			$jieqiTpl->assign('default_id',$cat_id );
		}
		$jieqiTpl->assign('create_blog_url',$jieqiModules['space']['url'].'/createblog.php?uid='.$uid );
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/createblog.html';
		break;
}

include_once($jieqiModules['space']['path'].'/spacefooter.php');
?>