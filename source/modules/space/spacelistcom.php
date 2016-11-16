<?php
/**
 * 博客的推荐空间
 *
 * 博客的推荐空间
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/spacelistcom.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
define('JIEQI_MODULE_NAME','space');
require_once('../../global.php');
require_once(JIEQI_ROOT_PATH.'/header.php');
$pagenum = 20;

//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;

require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/space.php');
$space_handler = JieqiSpaceHandler::getInstance('JieqiSpaceHandler');
$criteria=new CriteriaCompo();
$criteria->add( new Criteria('sp_commend',1) );
$count = $space_handler->getCount($criteria);
$criteria->setLimit($pagenum);
$criteria->setStart(($_REQUEST['page']-1) * $pagenum);
$criteria->setSort('up_time');
$criteria->setOrder('desc');
$space_handler->queryObjects($criteria);
$k = 0;
while($v = $space_handler->getObject() ){
	$spaces[$k]['uid'] = $v->getVar('uid');
	$spaces[$k]['name'] = $v->getVar('name','s');
	$spaces[$k]['title'] = $v->getVar('title','s');
	$spaces[$k]['brief'] = $v->getVar('brief','s');
	$spaces[$k]['visit_num'] = $v->getVar('visit_num');
	$spaces[$k]['blog_num'] = $v->getVar('blog_num');
	$spaces[$k]['up_time'] = $v->getVar('up_time','s');
	$k++;
}

$jieqiTpl->assign('spaces',$spaces);
$jieqiTpl->setCaching(0);

//echo $space_handler->getCount();
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($count,$pagenum,$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/spacelistcom.html';
include(JIEQI_ROOT_PATH.'/footer.php');
?>

