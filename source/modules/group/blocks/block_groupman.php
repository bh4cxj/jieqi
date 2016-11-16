<?php
/**
 * 圈子设置区块
 *
 * 圈子设置导航菜单
 * 
 * 调用模板：/modules/group/templates/blocks/block_groupman.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
class BlockGroupMan extends JieqiBlock
{
	var $module = 'group';
	var $template = 'block_groupman.html';
	function setContent($isreturn=false){
		global $jieqiTpl;
		global $gid;
		$jieqiTpl->assign('basic_url',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/man.php?g='.$gid.'&set=basic');
		$jieqiTpl->assign('log_url',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/man.php?g='.$gid.'&set=log');
		$jieqiTpl->assign('setbadge_url',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/man.php?g='.$gid.'&set=badge');
		$jieqiTpl->assign('gid',$gid);
	}	

}

?>