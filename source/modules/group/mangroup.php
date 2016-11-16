<?php
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

/**
 *  网站首页
 */

//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');


jieqi_loadlang('mangroup',JIEQI_MODULE_NAME);

include_once(JIEQI_ROOT_PATH.'/header.php');


//包含区块参数
jieqi_getconfigs('group', 'createblocks','jieqiBlocks');
jieqi_checklogin();

//The groups i created
require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');
$criteria = new Criteria('guid',$_SESSION['jieqiUserId']);
$criteria->setSort('gid');
$criteria->setOrder('DESC');
$group_handler->queryObjects($criteria);
$mygs =array();
$k = 0;
include_once(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/gcats.php');
while($v = $group_handler->getObject() ){
	$mygs[$k]['gname'] = "<a target=_blank href=".JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME."/topiclist.php?g=".$v->getVar('gid').">".$v->getVar('gname').'</a>';
	$mygs[$k]['uid'] =$v->getVar('uid');
	$mygs[$k]['gcatname'] = $gcats[$v->getVar('gcatid') ];
	$mygs[$k]['gid'] =$v->getVar('gid');
	$mygs[$k]['gtime'] =date('Y-m-d H:s' ,$v->getVar('gtime'));
	$k++;
}
$jieqiTpl->assign('mygs',$mygs);

//The groups i have joined
//查询当前用户
require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/member.php');
$member_handler = JieqimemberHandler::getInstance('JieqimemberHandler');
jieqi_includedb();
$member_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria = new CriteriaCompo(new Criteria('uid',$_SESSION['jieqiUserId']) );
$criteria->setTables(jieqi_dbprefix('group_member').' m left join '.jieqi_dbprefix('group_group').' u on m.gid=u.gid'  );
$countmembers = $member_query->getCount($criteria);
$joings = array();
$j = 0;
$member_query->queryObjects($criteria);
while($v = $member_query->getObject() ){
	$joings[$j]['gname'] = $v->getVar('gname');
	$joings[$j]['uid'] =$v->getVar('uid');
	$joings[$j]['gid'] =$v->getVar('gid');
	$joings[$j]['guid'] =$v->getVar('guid');
	$joings[$j]['guname'] =$v->getVar('guname');
	$joings[$j]['gcatname'] = $gcats[$v->getVar('gcatid') ];
	$joings[$j]['gtime'] =date('Y-m-d H:s' ,$v->getVar('gtime'));
	$j++;
}
$jieqiTpl->assign('joings',$joings);
$jieqiTpl->setCaching(0);
$jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/templates/mangroup.html') );
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>