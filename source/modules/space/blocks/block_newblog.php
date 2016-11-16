<?php 
/**
 * 我的文章列表区块
 *
 * 我的文章列表
 * 
 * 调用模板：/modules/space/templates/blocks/block_newblog.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//我的文章列表(原创)(动态)
class BlockSpaceNewBlog extends JieqiObject
{
	var $module = 'space';
	var $template='block_newblog.html';
	var $cachetime = -1;
	function setContent(){
		global $jieqiTpl;
		global $jieqiConfigs;
		global $jieqiModules;
		global $space_hoster;
		global $uid;
		if(!is_object($jieqiTpl)) $jieqiTpl =& JieqiTpl::getInstance();
		space_get_blog_cat();
		include_once($jieqiModules['space']['path'].'/class/blog.php');
		$blog_handler =& JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');
		$criteria = new CriteriaCompo(new Criteria('uid',$uid) );
		$criteria->setSort('id');
		$criteria->setOrder('desc');
		$criteria->setLimit(10);
		$criteria->setOrder('desc');
		$criteria->setSort('id');
		$blog_handler->queryObjects($criteria);
		$k = 0;
		include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
		$ts=TextConvert::getInstance('TextConvert');
		while($v=$blog_handler->getObject() ){
			if($v->getVar('open') == 1 || $space_hoster == 1) {
				$blogs[$k]['title'] = $v->getVar('title');
				if(!$blogs[$k]['title']){
					$blogs[$k]['title'] = '----';
				}
				$blogs[$k]['id'] = $v->getVar('id');
				$blogs[$k]['content'] = $ts->displayTarea( jieqi_substr($v->getVar('content','n'),0,30) );
				if(function_exists('tidy_repair_string') ){
					$blogs[$k]['content'] = tidy_repair_string($blogs[$k]['content']);
				}else{
					//wait
				}
				$blogs[$k]['up_time'] =  $v->getVar('up_time');
				$blogs[$k]['cat_name']=$blog_cats[$v->getVar('cat_id')]['name'];
				$blogs[$k]['cat_id']=$v->getVar('cat_id');
				$blogs[$k]['review_num']=$v->getVar('review_num');
				$blogs[$k]['hit_num']=$v->getVar('hit_num');
				if($v->getVar('open') == 0){
					$blogs[$k]['private'] = 1;
				}
				$k++;
			}
		}

		$jieqiTpl->assign_by_ref('blogs',$blogs);
	}
}