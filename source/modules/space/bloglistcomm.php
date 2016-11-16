<?php 
/**
 * 博客的推荐文章显示
 *
 * 博客的推荐文章显示
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/bloglistcomm.html
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
include_once($jieqiModules['space']['path'].'/class/blog.php');
$blog_handler =& JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');

//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$criteria=new CriteriaCompo();

$criteria->add(new Criteria('ar_commend',1) );
$criteria->add(new Criteria('ar_open',1) );

$pagenum = 20;
$count=$blog_handler->getCount($criteria);
$criteria->setSort('up_time');
$criteria->setOrder('desc');
$criteria->setLimit($pagenum);
$criteria->setStart(($_REQUEST['page']-1) * $pagenum);
$blog_handler->queryObjects($criteria,false);
$k = 0;
while($v = $blog_handler->getObject() ){
	$blogs[$k]['id'] = $v->getVar('id');
	$blogs[$k]['uid'] = $v->getVar('uid');
	$blogs[$k]['name'] = $v->getVar('name','s');
	$blogs[$k]['title'] = $v->getVar('title','s');
	$blogs[$k]['review_num'] = $v->getVar('review_num');
	$blogs[$k]['hit_num'] = $v->getVar('hit_num');
	$blogs[$k]['time'] = $v->getVar('up_time','s');
	$k++;
}
$jieqiTpl->assign('blogs',$blogs);
$jieqiTpl->assign('jieqi_space_url',$jieqiModules['space']['url']);


//处理页面跳转
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($count,$pagenum,$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/bloglistcomm.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>