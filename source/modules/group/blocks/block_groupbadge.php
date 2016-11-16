<?php
/**
 * 圈子徽章区块
 *
 * 圈子徽章
 * 
 * 调用模板：/modules/group/templates/blocks/block_groupbadge.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
include_once(JIEQI_ROOT_PATH.'/modules/badge/include/badgefunction.php');
class BlockGroupBadge extends JieqiBlock
{
	var $module = 'group';
	var $template = 'block_groupbadge.html';
	var $cachetime = -1;
	function setContent($isreturn=false){
		global $jieqiTpl,$jieqiModules,$jieqi_image_type;
		global $gid;
		jieqi_getconfigs('badge', 'configs');
		global $jieqiConfigs;
		include_once($jieqiModules['badge']['path'].'/class/badge.php');
		$badge_handler =& JieqiBadgeHandler::getInstance('JieqiBadgeHandler');
		$criteria=new CriteriaCompo(new Criteria('btypeid', '2010'));
		$criteria->add(new Criteria('linkid', $gid));
		$badge_handler->queryObjects($criteria);
		$badge=$badge_handler->getObject();
		if(is_object($badge)){
			$imagefile=getbadgeurl(2010, $gid,$jieqi_image_type[$badge->getVar('imagetype','n')],false);
			$jieqiTpl->assign('gbadge',$imagefile);
		}
	}	

}

?>