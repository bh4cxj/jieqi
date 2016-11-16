<?php
/**
 * 圈子简介区块
 *
 * 圈子简介区块
 * 
 * 调用模板：/modules/group/templates/blocks/block_groupcat.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */


class Blockgroupcat extends JieqiBlock
{
	var $module = 'group';
	var $template = 'block_groupcat.html';
	var $cachetime = -1;

	function setContent($isreturn=false){
		global $jieqiTpl,$gcats;
		include_once(JIEQI_ROOT_PATH.'/configs/group/gcats.php');
		$array = array();
		foreach($gcats as $k=>$v){
		   $array[] = array('catid'=>$k,'catname'=>$v);
		}
		$jieqiTpl->assign('gcats',$array);
	}	

}

?>