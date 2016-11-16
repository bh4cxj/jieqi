<?php
/**
 * 更新论坛分类配置文件
 *
 * 更新论坛分类配置文件
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: upforumcatset.php 253 2008-11-28 03:21:13Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;
//更新设置文件
if(!is_object($forumcat_handler)){
	include_once($jieqiModules['forum']['path'].'/class/forumcat.php');
	$forumcat_handler=JieqiForumcatHandler::getInstance('JieqiForumcatHandler');	
}
$criteria=new CriteriaCompo();
$criteria->setSort('catorder');
$criteria->setOrder('ASC');
$jieqiForumForumcat=array();
$forumcat_handler->queryObjects($criteria);
while($v = $forumcat_handler->getObject()){
	$jieqiForumForumcat[]=array('catid'=>$v->getVar('catid'), 'cattitle'=>$v->getVar('cattitle'), 'catorder'=>$v->getVar('catorder'));
}
jieqi_setconfigs('forumcatset', 'jieqiForumForumcat', $jieqiForumForumcat, 'forum');
?>