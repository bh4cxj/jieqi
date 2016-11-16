<?php
/**
 * 博客首页
 *
 * 博客首页
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/searchspace.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
define('JIEQI_MODULE_NAME','space');
require_once('../../global.php');
jieqi_loadlang('index', JIEQI_MODULE_NAME);
require_once(JIEQI_ROOT_PATH.'/header.php');
jieqi_getconfigs('space', 'indexblocks','jieqiBlocks');
if($_REQUEST['username']){
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	$criteria = new CriteriaCompo(new Criteria('name','%'.$_REQUEST['username'].'%','like')  );
	$criteria->add(new Criteria('name','%'.$_REQUEST['username'].'%','like') ,'or');
	$users_handler->queryObjects($criteria);
	$i = 0;
	while($v=$users_handler->getObject() ){
		$user_rows[$i]['name'] = $v->getVar('name')?$v->getVar('name'):$v->getVar('name');
		$user_rows[$i]['uid'] = $v->getVar('uid');
		$i++;
	}
	$jieqiTpl->assign('user_rows',$user_rows);
	if(empty($user_rows) ){
		$have_res = 0;
		$no_search_res = $jieqiLang['space']['no_search_res'];
	}
	$jieqiTpl->assign('have_res',$have_res);
	$jieqiTpl->assign('no_search_res',$no_search_res);
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/searchspace.html';
}
include(JIEQI_ROOT_PATH.'/footer.php');
?>

