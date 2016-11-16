<?php 
/**
 * 文章分类区块
 *
 * 文章分类
 * 
 * 调用模板：/modules/space/templates/blocks/block_spaceblogcat.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//文章分类



class BlockSpaceBlogCat extends JieqiBlock
{
	var $module = 'space';
	var $template = 'block_spaceblogcat.html';
	var $cachetime = -1;
	function setContent(){
		global $jieqiTpl;
		global $jieqiGroups;
		global $jieqiConfigs;
		global $jieqiModules;
		global $jieqi_image_type;
		global $uid;	
		global $blog_cats;
		space_get_blog_cat();
		$jieqiTpl->assign('blog_cats',$blog_cats);		
		$jieqiTpl->assign('edit_url',$jieqiModules['space']['url'].'/blogcatedit.php?uid='.$uid);	
	}

}


?>