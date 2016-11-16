<?php
/**
 * 修改博文
 *
 * 修改博文
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/editblog.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
require_once('../../global.php');
jieqi_loadlang('createblog',JIEQI_MODULE_NAME);
include_once($jieqiModules['space']['path'].'/spaceheader.php');
jieqi_loadlang('editblog',JIEQI_MODULE_NAME);

//include hoster's blog cats
space_get_blog_cat();
//check power
if(!$space_hoster){
	jieqi_printfail($jieqiLang['space']['no_right']);
}

$id = intval($_REQUEST['id']);
include_once($jieqiModules['space']['path'].'/class/blog.php');
$blog_handler = &JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');
$blog = $blog_handler->get($id);

switch($_REQUEST['action']){
	case 'doedit':
		$old_cat_id = $blog->getVar('cat_id');
		$cat_id = intval($_REQUEST['cat_id']);
		$blog->setVar('`title`',$_REQUEST['title']);
		$blog->setVar('`content`',$_REQUEST['content']);
		$blog->setVar('`cat_id`',$cat_id );
		$blog->setVar('`uid`',$uid);
		$blog->setVar('`ar_open`',intval($_REQUEST['ar_open']) );
		$blog->setVar('`allow_com`',intval($_REQUEST['allow_com']) );
		if($blog_handler->insert($blog) ){
			if($old_cat_id != $cat_id){
				include_once($jieqiModules['space']['path'].'/class/blogcat.php');
				$blog_cat_handler = JieqiSpaceBlogCatHandler::getInstance('JieqiSpaceBlogCatHandler');
				$criteria = new Criteria('id',$cat_id);
				$blog_cat_handler->updatefields('`num`=`num`+1',$criteria);
				unset($criteria);
				$criteria = new Criteria('id',$old_cat_id);
				$blog_cat_handler->updatefields('`num`=`num`-1',$criteria);
				unset($criteria);
				space_make_blog_cat();
			}
			jieqi_jumppage('./blog.php?uid='.$uid,$jieqiLang['space']['edit_success'],$jieqiLang['space']['edit_blog_finished']);
		}else{
			jieqi_printfail($jieqiLang['space']['create_blog_fail']);
		}
		break;
	default:
		$jieqiTpl->assign('id',$id);
		$jieqiTpl->assign('blog_cats',$blog_cats);
		if($cat_id = $blog->getVar('cat_id') ){
			$jieqiTpl->assign('default_id',$cat_id );
		}
		$jieqiTpl->assign('title',$blog->getVar('title') );
		$jieqiTpl->assign('content',$blog->getVar('content','e') );
		$jieqiTpl->assign('ar_open',$blog->getVar('ar_open') );
		$jieqiTpl->assign('allow_com',$blog->getVar('allow_com') );
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/editblog.html';
		break;
}

include_once($jieqiModules['space']['path'].'/spacefooter.php');
?>