<?php 
/**
 * 文章搜索区块
 *
 * 文章搜索区块
 * 
 * 调用模板：/modules/article/templates/blocks/block_search.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_search.php 312 2008-12-29 05:30:54Z juny $
 */

class BlockArticleSearch extends JieqiBlock
{
	var $module = 'article';
	var $template = 'block_search.html';

    function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		
		jieqi_getconfigs('article', 'configs');
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
		
		$jieqiTpl->assign('url_articlesearch', $article_dynamic_url.'/search.php');
	}
	
}

?>