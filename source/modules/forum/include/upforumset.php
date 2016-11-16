<?php
/**
 * 更新论坛版块配置文件
 *
 * 更新论坛版块配置文件
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: upforumset.php 253 2008-11-28 03:21:13Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;
//更新设置文件
if(!is_object($forums_handler)){
	include_once($jieqiModules['forum']['path'].'/class/forums.php');
	$forums_handler=JieqiForumsHandler::getInstance('JieqiForumsHandler');
}
$criteria=new CriteriaCompo();
$criteria->setSort('forumorder');
$criteria->setOrder('ASC');
$jieqiForumForums=array();
$forums_handler->queryObjects($criteria);
while($v = $forums_handler->getObject()){
	$jieqiForumForums[]=array('forumid'=>$v->getVar('forumid'), 'catid'=>$v->getVar('catid'), 'forumname'=>$v->getVar('forumname'), 'forumorder'=>$v->getVar('forumorder'));
}
jieqi_setconfigs('forumsset', 'jieqiForumForums', $jieqiForumForums, 'forum');
?>