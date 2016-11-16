<?php 
/**
 * 电子书分类导航区块
 *
 * 电子书分类导航区块
 * 
 * 调用模板：/modules/obook/templates/blocks/block_sort.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_sort.php 300 2008-12-26 04:36:06Z juny $
 */

class BlockObookSort extends JieqiBlock
{
	var $module = 'obook';
	var $template = 'block_sort.html';
	
    function setContent($isreturn=false){
		global $jieqiSort;
		global $jieqiTpl;
		global $jieqiConfigs;
		
		jieqi_getconfigs('obook', 'sort');
		jieqi_getconfigs('obook', 'configs');
		$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $GLOBALS['jieqiModules']['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
		$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $GLOBALS['jieqiModules']['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
		$jieqiTpl->assign('obook_static_url',$obook_static_url);
		$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);

		$sortrows=array();
		if($jieqiConfigs['obook']['fakesort'] == 1){
			if(!empty($jieqiConfigs['obook']['fakeprefix'])) $tmpvar='/'.$jieqiConfigs['obook']['fakeprefix'].'sort';
				else $tmpvar='/files/obook/sort';
			$tmpurl=$obook_dynamic_url.$tmpvar.'/0/1'.$jieqiConfigs['obook']['fakefile'];
		}else{
			$tmpurl=$obook_dynamic_url.'/obooklist.php?class=0';
		}
		$jieqiTpl->assign('url_obookindex',$tmpurl);
		$jieqiTpl->assign('url_obooklist',$tmpurl);
		$i=0;
		foreach($jieqiSort['obook'] as $k=>$v){
			if($jieqiConfigs['obook']['fakesort'] == 1){
				if(!empty($jieqiConfigs['obook']['fakeprefix'])) $tmpvar='/'.$jieqiConfigs['obook']['fakeprefix'].'sort';
				else $tmpvar='/files/obook/sort';
				$tmpurl=$obook_dynamic_url.$tmpvar.$k.'/0/1'.$jieqiConfigs['obook']['fakefile'];
			}else{
				$tmpurl=$obook_dynamic_url.'/obooklist.php?class='.$k;
			}
			$sortrows[$i]=array('sortid'=>$k, 'sortname'=>$v['caption'], 'url_sort'=>$tmpurl, 'sortlayer'=>$v['layer']);
			$i++;
		}
		$jieqiTpl->assign_by_ref('sortrows', $sortrows);
	}
	
}

?>