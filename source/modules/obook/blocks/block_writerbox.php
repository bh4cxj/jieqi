<?php 
/**
 * 电子书作家功能导航区块
 *
 * 电子书作家功能导航区块
 * 
 * 调用模板：/modules/obook/templates/blocks/block_writerbox.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_writerbox.php 300 2008-12-26 04:36:06Z juny $
 */

class BlockObookWriterbox extends JieqiBlock
{
    var $module = 'obook';
	var $template = 'block_sort.html';

	
	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		
		jieqi_getconfigs('obook', 'configs');
		$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $GLOBALS['jieqiModules']['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
		$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $GLOBALS['jieqiModules']['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
		$jieqiTpl->assign('obook_static_url',$obook_static_url);
		$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
	}
}
?>