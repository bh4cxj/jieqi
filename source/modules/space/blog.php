<?php
/**
 * 显示博客的文章列表
 *
 * 显示博客的文章列表
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/blog.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
define('JIEQI_MODULE_NAME','space');
require_once('../../global.php');
jieqi_loadlang('blog', JIEQI_MODULE_NAME);
jieqi_getconfigs('space', 'blogblocks','jieqiBlocks');
include_once($jieqiModules['space']['path'].'/spaceheader.php');


space_get_blog_cat();
if($cat_id = intval($_REQUEST['cat_id']) ){
	$tmp_var = '&cat_id='.$cat_id;
}

$jieqiTpl->assign('create_blog_url',$jieqiModules['space']['url'].'/createblog.php?uid='.$uid.$tmp_var );
unset($tmp_var);

include_once($jieqiModules['space']['path'].'/class/blog.php');
$blog_handler =& JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');

switch($_REQUEST['act']){
	case 'del':
		if($space_hoster == 1){
			$id =  intval($_REQUEST['id']);
			$blog = $blog_handler->get($id);
			$cat_id = $blog->getVar('cat_id');
			$blog_handler->delete($id);
			include_once($jieqiModules['space']['path'].'/class/blogcat.php');
			$blog_cat_handler = JieqiSpaceBlogCatHandler::getInstance('JieqiSpaceBlogCatHandler');
			$blog_cat_handler->db->query("update ".jieqi_dbprefix('space_blogcat')." set `num`=`num`-1 where id=".$cat_id);
			space_make_blog_cat();
			include_once($jieqiModules['space']['path'].'/class/space.php');
			$space_handler = JieqiSpaceHandler::getInstance('JieqiSpaceHandler');
			$criteria = new Criteria('uid',$uid);
			$space_handler->updatefields("`blog_num`=`blog_num`-1",$criteria);
			//del reviews
			$criteria = new CriteriaCompo(new Criteria('uid',$uid) );
			$criteria->add(new Criteria('blog_id',$id) );
			include_once($jieqiModules['space']['path'].'/class/blogreview.php');
			$blog_review_handler = & JieqiSpaceBlogReviewHandler::getInstance('JieqiSpaceBlogReviewHandler');
			$blog_review_handler->delete($criteria);
			jieqi_jumppage($jieqiModules['space']['url'].'/blog.php?uid='.$uid.'&cat_id='.intval($_REQUEST['cat_id']),$jieqiLang['space']['del_success'],$jieqiLang['space']['article_had_del']);
		}
	default :
		$page_num=isset($jieqiConfigs['space']['blog_page_num'])?$jieqiConfigs['space']['blog_page_num']:3;
		$criteria = new CriteriaCompo(new Criteria('uid',$uid) );
		$_REQUEST['page']=is_numeric($_REQUEST['page'])?$_REQUEST['page']:1;
		$criteria->setSort('id');
		$criteria->setOrder('desc');
		$criteria->setStart( ($_REQUEST['page']-1)*$page_num );
		$criteria->setLimit($page_num );
		if($cat_id){
			$criteria->add(new Criteria('cat_id',$cat_id) );
		}
		$criteria->setOrder('desc');
		$criteria->setSort('id');
		$blog_handler->queryObjects($criteria);
		$k = 0;
		include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
		$ts=TextConvert::getInstance('TextConvert');
		while($v=$blog_handler->getObject() ){
			if($v->getVar('ar_open') == 1 || $space_hoster == 1) {
				$blogs[$k]['title'] = $v->getVar('title');
				if(!$blogs[$k]['title']){
					$blogs[$k]['title'] = '----';
				}
				$blogs[$k]['id'] = $v->getVar('id');
				$blogs[$k]['content'] = jieqi_substr($v->getVar('content','n'),0,250);
				if(function_exists('tidy_repair_string') ){
					$blogs[$k]['content'] = $ts->displayTarea(tidy_repair_string($blogs[$k]['content']) );
				}else{
					//wait for tidy functon made by php
					$blogs[$k]['content'] = $ts->displayTarea($blogs[$k]['content'] );
				}
				$blogs[$k]['up_time'] = $v->getVar('up_time');
				$blogs[$k]['cat_name']=$blog_cats[$v->getVar('cat_id')]['name'];
				$blogs[$k]['cat_id']=$v->getVar('cat_id');
				$blogs[$k]['ar_open']=$v->getVar('ar_open');
				$blogs[$k]['review_num']=$v->getVar('review_num');
				$blogs[$k]['hit_num']=$v->getVar('hit_num');
				if($v->getVar('open') == 0){
					$blogs[$k]['private'] = 1;
				}
				$k++;
			}
		}
		$jieqiTpl->assign('blogs',$blogs);
		include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($blog_handler->getCount($criteria),$page_num,$_REQUEST['page']);
		$jumppage->setlink('', true, true);
		$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
		break;
}

$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/blog.html';
include_once($jieqiModules['space']['path'].'/spacefooter.php');
?>