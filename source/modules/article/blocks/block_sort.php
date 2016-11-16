<?php 
/**
 * 文章分类导航区块
 *
 * 文章分类导航区块
 * 
 * 调用模板：/modules/article/templates/blocks/block_sort.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_sort.php 300 2008-12-26 04:36:06Z juny $
 */

class BlockArticleSort extends JieqiBlock
{
	var $module = 'article';
	var $template = 'block_sort.html';

    function setContent($isreturn=false){
		global $jieqiSort;
		global $jieqiTpl;
		global $jieqiConfigs;

		jieqi_getconfigs('article', 'sort');
		jieqi_getconfigs('article', 'configs');
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

		$sortrows=array();
		$jieqiTpl->assign('url_articlelist',jieqi_geturl('article', 'articlelist', 1, 0));
		$i=0;
		foreach($jieqiSort['article'] as $k=>$v){
			$sortrows[$i]=array('sortid'=>$k, 'sortname'=>$v['caption'], 'url_sort'=>jieqi_geturl('article', 'articlelist', 1, $k), 'sortlayer'=>$v['layer']);
			$i++;
		}
		$jieqiTpl->assign_by_ref('sortrows', $sortrows);
	}
	
}

?>