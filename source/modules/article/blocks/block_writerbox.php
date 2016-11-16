<?php 
/**
 * 作家工具导航区块
 *
 * 作家工具导航区块
 * 
 * 调用模板：/modules/article/templates/blocks/block_writerbox.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_writerbox.php 300 2008-12-26 04:36:06Z juny $
 */

class BlockArticleWriterbox extends JieqiBlock
{
    var $module = 'article';
	var $template = 'block_writerbox.html';
	
	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;

		jieqi_getconfigs('article', 'configs');
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
		
	}
}
?>