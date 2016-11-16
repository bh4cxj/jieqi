<?php 
/**
 * 电子书分类列表
 *
 * 电子书分类列表
 * 
 * 调用模板：/modules/obook/templates/obooklist.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obooklist.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
jieqi_loadlang('list', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');

include_once(JIEQI_ROOT_PATH.'/header.php');

//排序方式
if(empty($_REQUEST['sort'])) $_REQUEST['sort']='lastupdate';
//文章类别
if(empty($_REQUEST['class'])) $_REQUEST['class']=0;
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;

//是否缓存
$content_used_cache=false;
$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/obooklist.html';
$jieqiTset['jieqi_contents_cacheid'] =  'obooklist_'.$_REQUEST['sort'].'_'.$_REQUEST['class'].'_'.$_REQUEST['page'];

if (JIEQI_USE_CACHE && $_REQUEST['page']<=$jieqiConfigs['obook']['topcachenum']){
	jieqi_getcachevars('obook', 'obookuplog');
	if(!is_array($jieqiObookuplog)) $jieqiObookuplog=array('obookuptime'=>0, 'chapteruptime'=>0);
	$uptime = $jieqiObookuplog['obookuptime'] > $jieqiObookuplog['chapteruptime'] ? $jieqiObookuplog['obookuptime'] : $jieqiObookuplog['chapteruptime'];
	$cachedtime = $jieqiTpl->get_cachedtime($jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_cacheid']);
	if(in_array($_REQUEST['sort'], array('lastupdate', 'postdate'))){
		if($uptime - $cachedtime < 60 && JIEQI_NOW_TIME - $cachedtime < JIEQI_CACHE_LIFETIME) $content_used_cache=true;
	}else{
		if(JIEQI_NOW_TIME - $cachedtime < JIEQI_CACHE_LIFETIME) $content_used_cache=true;
	}
	if(!$content_used_cache){
		$jieqiTpl->update_cachedtime($jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_cacheid']);
		$jieqiTpl->setCaching(2);
	}else{
		$jieqiTpl->setCaching(1);
	}
	$jieqiTpl->setCacheTime(99999999);
}else{
	$jieqiTpl->setCaching(0);
}

if(!$content_used_cache){
	$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
	$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
	$jieqiTpl->assign('obook_static_url',$obook_static_url);
	$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);

	//和文学系统关联需要
	if(jieqi_getconfigs('article', 'configs')){
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
	}

	//是否使用伪静态页面
	$jieqiTpl->assign('fakefile', $jieqiConfigs['obook']['fakefile']);
	$jieqiTpl->assign('fakeinfo', $jieqiConfigs['obook']['fakeinfo']);
	$jieqiTpl->assign('fakesort', $jieqiConfigs['obook']['fakesort']);
	$jieqiTpl->assign('fakeinitial', $jieqiConfigs['obook']['fakeinitial']);
	$jieqiTpl->assign('faketoplist', $jieqiConfigs['obook']['faketoplist']);

	include_once($jieqiModules['obook']['path'].'/class/obook.php');
	$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');

	$criteria=new CriteriaCompo(new Criteria('display','0','='));
	if(!empty($_REQUEST['class'])){
		$criteria->add(new Criteria('sortid', $_REQUEST['class'], '='));
		$classinfo=' - '.$jieqiSort['obook'][$_REQUEST['class']]['caption'];
	}else{
		$classinfo='';
	}

	$tmpvar=explode('-',date('Y-m-d',JIEQI_NOW_TIME));
	$daystart=mktime(0,0,0,(int)$tmpvar[1],(int)$tmpvar[2],(int)$tmpvar[0]);
	$monthstart=mktime(0,0,0,(int)$tmpvar[1],1,(int)$tmpvar[0]);
	$tmpvar=date('w',JIEQI_NOW_TIME);
	if($tmpvar==0) $tmpvar=7; //星期天是0，国人习惯作为作为一星期的最后一天
	$weekstart=$daystart;
	if($tmpvar>1) $weekstart-=($tmpvar-1) * 86400;
	switch($_REQUEST['sort']){
		case 'allsale':
		$criteria->setSort('allsale');
		$jieqiTpl->assign('obooktitle', sprintf($jieqiLang['obook']['top_allvisit_title'], $classinfo));
		break;
		case 'monthsale':
		$criteria->add(new Criteria('lastsale',$monthstart,'>='));
		$criteria->setSort('monthsale');
		$jieqiTpl->assign('obooktitle', sprintf($jieqiLang['obook']['top_monthvisit_title'], $classinfo));
		break;
		case 'weeksale':
		$criteria->add(new Criteria('lastsale',$weekstart,'>='));
		$criteria->setSort('weeksale');
		$jieqiTpl->assign('obooktitle', sprintf($jieqiLang['obook']['top_weekvisit_title'], $classinfo));
		break;
		case 'daysale':
		$criteria->add(new Criteria('lastsale',$daystart,'>='));
		$criteria->setSort('daysale');
		$jieqiTpl->assign('obooktitle', sprintf($jieqiLang['obook']['top_dayvisit_title'], $classinfo));
		break;
		case 'postdate':
		$criteria->setSort('postdate');
		$jieqiTpl->assign('obooktitle', sprintf($jieqiLang['obook']['top_postdate_title'], $classinfo));
		break;
		case 'toptime':
		$criteria->setSort('toptime');
		$jieqiTpl->assign('obooktitle', sprintf($jieqiLang['obook']['top_toptime_title'], $classinfo));
		break;
		case 'goodnum':
		$criteria->setSort('goodnum');
		$jieqiTpl->assign('obooktitle', sprintf($jieqiLang['obook']['top_goodnum_title'], $classinfo));
		break;
		case 'lastupdate':
		default:
		$_REQUEST['sort']='lastupdate';
		$criteria->setSort('lastupdate');
		$jieqiTpl->assign('obooktitle', sprintf($jieqiLang['obook']['top_lastupdate_title'], $classinfo));
		break;
	}
	$criteria->setOrder('DESC');
	$criteria->setLimit($jieqiConfigs['obook']['pagenum']);
	$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['obook']['pagenum']);
	$obook_handler->queryObjects($criteria);
	$obookrows=array();
	$k=0;
	while($v = $obook_handler->getObject()){
		$obookrows[$k]['checkid']=$k;  //显示序号
		$obookrows[$k]['obookid']=$v->getVar('obookid');  //文章序号
		$obookrows[$k]['obookname']=$v->getVar('obookname');  //文章名称
		$obookrows[$k]['articleid']=$v->getVar('articleid');  //文章序号
		if($jieqiConfigs['obook']['fakeinfo']==1){
			$obookrows[$k]['obooksubdir']=jieqi_getsubdir($v->getVar('obookid'));  //子目录
			if(!empty($jieqiConfigs['obook']['fakeprefix'])) $tmpvar='/'.$jieqiConfigs['obook']['fakeprefix'].'info';
			else $tmpvar='/files/obook/info';
			$obookrows[$k]['url_obookinfo']=$obook_dynamic_url.$tmpvar.$obookrows[$k]['obooksubdir'].'/'.$v->getVar('obookid').$jieqiConfigs['obook']['fakefile'];  //子目录
		}else{
			$obookrows[$k]['obooksubdir']='';
			$obookrows[$k]['url_obookinfo']=$obook_dynamic_url.'/obookinfo.php?id='.$v->getVar('obookid');  //子目录
		}
		if($v->getVar('lastchapter')==''){
			$obookrows[$k]['lastchapterid']=0;  //章节序号
			$obookrows[$k]['lastchapter']='';  //章节名称
			$obookrows[$k]['url_lastchapter']='';  //章节地址
		}else{
			$obookrows[$k]['lastchapterid']=$v->getVar('lastchapterid');
			$obookrows[$k]['lastchapter']=$v->getVar('lastchapter');
			$obookrows[$k]['url_lastchapter']=$obook_static_url.'/reader.php?aid='.$v->getVar('obookid').'&cid='.$v->getVar('lastchapterid');
		}
		//公众章节
		if($obookrows[$k]['articleid'] > 0){
			if($jieqiConfigs['article']['makehtml']==0 || JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET){
				$obookrows[$k]['url_read'] = $article_static_url.'/reader.php?aid='.$obookrows[$k]['articleid'];
			}else{
				$obookrows[$k]['url_read'] = jieqi_uploadurl($jieqiConfigs['article']['htmldir'], $jieqiConfigs['article']['htmlurl'], 'article', $article_static_url).jieqi_getsubdir($obookrows[$k]['articleid']).'/'.$obookrows[$k]['articleid'].'/index'.$jieqiConfigs['article']['htmlfile'];
			}
		}
		$obookrows[$k]['lastvolume']=$v->getVar('lastvolumeid');  //分卷序号
		$obookrows[$k]['lastvolume']=$v->getVar('lastvolume');  //分卷名称

		$obookrows[$k]['authorid']=$v->getVar('authorid');  //作者
		$obookrows[$k]['author']=$v->getVar('author');
		$obookrows[$k]['posterid']=$v->getVar('posterid');  //发表者
		$obookrows[$k]['poster']=$v->getVar('poster');
		$obookrows[$k]['agentid']=$v->getVar('agentid');  //代理者
		$obookrows[$k]['agent']=$v->getVar('agent');

		$obookrows[$k]['sortid']=$v->getVar('sortid');  //类别序号
		$obookrows[$k]['sort']=$jieqiSort['obook'][$v->getVar('sortid')]['caption'];  //类别

		$obookrows[$k]['size']=$v->getVar('size');
		$obookrows[$k]['size_k']=ceil($v->getVar('size')/1024);
		$obookrows[$k]['size_c']=ceil($v->getVar('size')/2);
		$obookrows[$k]['daysale']=$v->getVar('daysale');
		$obookrows[$k]['weeksale']=$v->getVar('weeksale');
		$obookrows[$k]['monthsale']=$v->getVar('monthsale');
		$obookrows[$k]['sumegold']=$v->getVar('sumegold');
		$obookrows[$k]['sumesilver']=$v->getVar('sumesilver');
		$obookrows[$k]['sumemoney']=$obookrows[$k]['sumegold']+$obookrows[$k]['sumesilver'];
		$obookrows[$k]['allsale']=$v->getVar('allsale');
		$obookrows[$k]['lastupdate']=date('y-m-d',$v->getVar('lastupdate')); //最后更新日期
		$obookrows[$k]['display']=$v->getVar('display');
		$k++;
	}

	$jieqiTpl->assign_by_ref('obookrows', $obookrows);

	//处理页面跳转
	include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
	$jumppage = new JieqiPage($obook_handler->getCount($criteria),$jieqiConfigs['obook']['pagenum'],$_REQUEST['page']);
	$pagelink='';
	if(!empty($_REQUEST['class'])){
		if(empty($pagelink)) $pagelink.='?';
		else $pagelink.='&';
		$pagelink.='class='.$_REQUEST['class'];
	}
	if(!empty($_REQUEST['keyword'])){
		if(empty($pagelink)) $pagelink.='?';
		else $pagelink.='&';
		$pagelink.='keyword='.$_REQUEST['keyword'];
		$pagelink.='&keytype='.$_REQUEST['keytype'];
	}
	if(empty($pagelink)) $pagelink.='?page=';
	else $pagelink.='&page=';
	$jumppage->setlink($obook_dynamic_url.'/obooklist.php'.$pagelink, false, true);
	$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
}
//$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/obooklist.html';
//$jieqiTset['jieqi_contents_cacheid'] =  'obooklist_'.$_REQUEST['sort'].'_'.$_REQUEST['class'].'_'.$_REQUEST['page'];
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>