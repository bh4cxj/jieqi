<?php
/**
 * 生成静态书评
 *
 * 生成静态书评
 * 
 * 调用模板：/modules/article/templates/staticreview.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: staticmakereview.php 328 2009-02-06 09:24:29Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;
include_once($GLOBALS['jieqiModules']['article']['path'].'/class/review.php');
include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
function makestaticreview($article_id){
	global $jieqiConfigs;
	global $jieqiTpl;
	global $article_dynamic_url;
	global $article_static_url;
	if(!is_object($jieqiTpl)) $jieqiTpl =& JieqiTpl::getInstance();
	if(empty($article_id) || !is_numeric($article_id)) return false;
	$review_handler =& JieqiReviewHandler::getInstance('JieqiReviewHandler');
	$criteria=new CriteriaCompo(new Criteria('ownerid', $article_id));
	$criteria->setSort('topflag DESC, topicid');
	$criteria->setOrder('DESC');
	$criteria->setLimit($jieqiConfigs['article']['reviewnew']);
	$criteria->setStart(0);
	$review_handler->queryObjects($criteria);
	$reviewrows=array();
	$k=0;
	
	while($v = $review_handler->getObject()){
		$start=3;
		if($v->getVar('topflag')==1) {
			$reviewrows[$k]['topflag']=1;
			$start+=4;
		}else{
			$reviewrows[$k]['topflag']=0;
		}
		if($v->getVar('goodflag')==1) {
			$reviewrows[$k]['goodflag']=1;
			$start+=4;
		}else{
			$reviewrows[$k]['goodflag']=0;
		}
		if($jieqiConfigs['article']['reviewenter']=='0'){
			$reviewrows[$k]['content']=jieqi_htmlstr(jieqi_limitwidth(str_replace(array("\r", "\n"), array('', ' '), $v->getVar('reviewtext', 'n')), $jieqiConfigs['article']['reviewwidth'], $start));
		}else{
			$reviewrows[$k]['content']=jieqi_htmlstr(jieqi_limitwidth($v->getVar('reviewtext', 'n'), $jieqiConfigs['article']['reviewwidth'], $start));
		}
		$reviewrows[$k]['postdate']=date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $v->getVar('postdate'));
		$reviewrows[$k]['userid']=$v->getVar('userid');
		$reviewrows[$k]['username']=$v->getVar('username');
		$k++;
	}
	$jieqiTpl->assign_by_ref('reviewrows', $reviewrows);
	$jieqiTpl->assign('url_goodreview', $article_dynamic_url.'/review.php?aid='.$article_id.'&type=good');
	$jieqiTpl->assign('url_allreview', $article_dynamic_url.'/review.php?aid='.$article_id.'&type=all');
	$jieqiTpl->assign('url_review', $article_dynamic_url.'/review.php?aid='.$article_id);

	$jieqiTpl->setCaching(0);
	$reviewjs=$jieqiTpl->fetch($GLOBALS['jieqiModules']['article']['path'].'/templates/staticreview.html');
	$reviewjs="document.write('".jieqi_setslashes(str_replace(array("\n","\r"),"",$reviewjs),'"')."');";
	if(!empty($jieqiConfigs['article']['fakeprefix']))	$dirname=JIEQI_ROOT_PATH.'/'.$jieqiConfigs['article']['fakeprefix'].'info';
	else $dirname=JIEQI_ROOT_PATH.'/files/article/info';
	if(!file_exists($dirname)) jieqi_createdir($dirname);
	$dirname=$dirname.jieqi_getsubdir($article_id);
	if (!file_exists($dirname)) jieqi_createdir($dirname);
	$dirname.='/'.$article_id.'r.js';
	jieqi_writefile($dirname, $reviewjs);
	return true;
}
?>