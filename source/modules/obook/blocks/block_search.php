<?php 
/**
 * 电子书搜索区块
 *
 * 显示搜索框的区块
 * 
 * 调用模板：/modules/obook/templates/blocks/block_search.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_search.php 312 2008-12-29 05:30:54Z juny $
 */

class BlockObookSearch extends JieqiBlock
{
	var $module = 'obook';
	var $template = 'block_search.html';


    function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		
		jieqi_getconfigs('obook', 'configs');
		$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $GLOBALS['jieqiModules']['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
		$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $GLOBALS['jieqiModules']['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
		$jieqiTpl->assign('obook_static_url',$obook_static_url);
		$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
		
		$jieqiTpl->assign('url_obooksearch', $obook_dynamic_url.'/search.php');
	}
	
}

?>