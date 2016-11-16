<?php 
/**
 * 用户排行榜
 *
 * 按照积分、加入时间等方式排行
 * 
 * 调用模板：/templates/admin/topuser.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: topuser.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminuser'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('users', JIEQI_MODULE_NAME);
//包含配置参数
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;

include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$criteria=new CriteriaCompo();
switch($_REQUEST['sort']){
	case 'experience':
	$jieqiTpl->assign('sort', 'experience');
	$jieqiTpl->assign('usertitle', $jieqiLang['system']['top_user_experience']);
	$criteria->setSort('experience');
	$criteria->setOrder('DESC');
	break;
	case 'score':
	$jieqiTpl->assign('sort', 'score');
	$jieqiTpl->assign('usertitle', $jieqiLang['system']['top_user_score']);
	$criteria->setSort('score');
	$criteria->setOrder('DESC');
	break;
	case 'monthscore':
	$monthstart=mktime(0,0,0,intval(date('m',JIEQI_NOW_TIME)),1,intval(date('Y',JIEQI_NOW_TIME)));
	$criteria->add(new Criteria('lastlogin', $monthstart, '>='));
	$jieqiTpl->assign('sort', 'monthscore');
	$jieqiTpl->assign('usertitle', $jieqiLang['system']['top_user_monthscore']);
	$criteria->setSort('monthscore');
	$criteria->setOrder('DESC');
	break;
	case 'regdate':
	default:
	$jieqiTpl->assign('sort', 'regdate');
	$jieqiTpl->assign('usertitle', $jieqiLang['system']['top_user_join']);
	$criteria->setSort('regdate');
	$criteria->setOrder('DESC');
	break;
}

$criteria->setLimit($jieqiConfigs['system']['topuserpnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['topuserpnum']);
$users_handler->queryObjects($criteria);
$userrows=array();
$k=0;
while($v = $users_handler->getObject()){
	$userrows[$k]['uid']=$v->getVar('uid');
	$userrows[$k]['uname']=$v->getVar('uname');
	$userrows[$k]['regdate']=date(JIEQI_DATE_FORMAT, $v->getVar('regdate'));
	$userrows[$k]['group']=$v->getGroup();
	$userrows[$k]['sex']=$v->getSex();
	$userrows[$k]['email']=$v->getVar('email');
	$userrows[$k]['url']=$v->getVar('url');
	$userrows[$k]['qq']=$v->getVar('qq');
	$userrows[$k]['msn']=$v->getVar('msn');
	$userrows[$k]['experience']=$v->getVar('experience');
	$userrows[$k]['monthscore']=$v->getVar('monthscore');
	$userrows[$k]['score']=$v->getVar('score');
	$userrows[$k]['egold']=$v->getVar('egold');
	$userrows[$k]['esilver']=$v->getVar('esilver');
	$userrows[$k]['emoney']=$userrows[$k]['egold']+$userrows[$k]['esilver'];
	$userrows[$k]['lastlogin']=date(JIEQI_DATE_FORMAT, $v->getVar('lastlogin'));
	$k++;
}
$jieqiTpl->assign_by_ref('userrows', $userrows);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($users_handler->getCount($criteria),$jieqiConfigs['system']['topuserpnum'],$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/topuser.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>