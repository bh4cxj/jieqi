<?php 
/**
 * 显示电子书信息
 *
 * 显示电子书信息
 * 
 * 调用模板：/modules/obook/templates/obookinfo.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obookinfo.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');
if(empty($_REQUEST['id']) && empty($_REQUEST['aid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['id']=intval($_REQUEST['id']);
$_REQUEST['aid']=intval($_REQUEST['aid']);
jieqi_loadlang('obook', JIEQI_MODULE_NAME);
include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
if(!empty($_REQUEST['id'])){
	$obook=$obook_handler->get($_REQUEST['id']);
}else{
	$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid'], '='));
	$obook_handler->queryObjects($criteria);
	$obook=$obook_handler->getObject();
}
if(!is_object($obook) || ($obook->getVar('display') != 0 && $jieqiUsersStatus != JIEQI_GROUP_ADMIN)) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
else{
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'publisher');


	$jieqi_pagetitle=$obook->getVar('obookname');
	if($obook->getVar('lastvolume') != '') $jieqi_pagetitle.='-'.$obook->getVar('lastvolume');
	$jieqi_pagetitle.='-'.$obook->getVar('lastchapter').'-'.JIEQI_SITE_NAME;
	include_once(JIEQI_ROOT_PATH.'/header.php');

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

	$jieqiTpl->assign('obookid', $obook->getVar('obookid'));
	$jieqiTpl->assign('obookname', $obook->getVar('obookname'));
	$jieqiTpl->assign('articleid', $obook->getVar('articleid'));
	$jieqiTpl->assign('postdate', date(JIEQI_DATE_FORMAT, $obook->getVar('postdate')));
	$jieqiTpl->assign('lastupdate', date(JIEQI_DATE_FORMAT, $obook->getVar('lastupdate')));
	$jieqiTpl->assign('authorid', $obook->getVar('authorid'));
	$jieqiTpl->assign('author', $obook->getVar('author'));
	$jieqiTpl->assign('agentid', $obook->getVar('agentid'));
	$jieqiTpl->assign('agent', $obook->getVar('agent'));
	$jieqiTpl->assign('sortid', $obook->getVar('sortid'));
	$jieqiTpl->assign('sort', $jieqiSort['obook'][$obook->getVar('sortid')]['caption']);
	$jieqiTpl->assign('intro',$obook->getVar('intro'));
	$jieqiTpl->assign('notice', $obook->getVar('notice'));
	$jieqiTpl->assign('publishid', $obook->getVar('publishid'));
	if(isset($jieqiPublisher[$obook->getVar('publishid')])) $jieqiTpl->assign('publisher', jieqi_htmlstr($jieqiPublisher[$obook->getVar('publishid')]['name']));
	else $jieqiTpl->assign('publisher', '');
	$imgflag=$obook->getVar('imgflag');
	//小图
	if(($imgflag & 1)>0){
		$jieqiTpl->assign('hasimage', 1);
		$jieqiTpl->assign('url_simage', jieqi_uploadurl($jieqiConfigs['obook']['imagedir'], $jieqiConfigs['obook']['imageurl'], 'obook', $obook_static_url).jieqi_getsubdir($obook->getVar('obookid')).'/'.$obook->getVar('obookid').'/'.$obook->getVar('obookid').'s'.$jieqiConfigs['obook']['imagetype']);
	}else{
		$jieqiTpl->assign('hasimage', 0);
		$jieqiTpl->assign('url_simage','');
	}
	//大图
	if(($imgflag & 2)>0){
		$jieqiTpl->assign('url_limage', jieqi_uploadurl($jieqiConfigs['obook']['imagedir'], $jieqiConfigs['obook']['imageurl'], 'obook', $obook_static_url).jieqi_getsubdir($obook->getVar('obookid')).'/'.$obook->getVar('obookid').'/'.$obook->getVar('obookid').'l'.$jieqiConfigs['obook']['imagetype']);
	}elseif(($imgflag & 1)>0){
		$jieqiTpl->assign('url_limage', jieqi_uploadurl($jieqiConfigs['obook']['imagedir'], $jieqiConfigs['obook']['imageurl'], 'obook', $obook_static_url).jieqi_getsubdir($obook->getVar('obookid')).'/'.$obook->getVar('obookid').'/'.$obook->getVar('obookid').'s'.$jieqiConfigs['obook']['imagetype']);
	}else{
		$jieqiTpl->assign('url_limage','');
	}
	$lastchapter=$obook->getVar('lastchapter');
	if($lastchapter != ''){
		if($obook->getVar('lastvolume') != '') $lastchapter=$obook->getVar('lastvolume').' '.$lastchapter;
		$jieqiTpl->assign('url_lastchapter', $obook_static_url.'/reader.php?oid='.$obook->getVar('obookid').'&cid='.$obook->getVar('lastchapterid'));
	}else{
		$jieqiTpl->assign('url_lastchapter', '');
	}
	$jieqiTpl->assign('lastchapter', $lastchapter);
	$jieqiTpl->assign('size', $obook->getVar('size'));
	$jieqiTpl->assign('size_k', ceil($obook->getVar('size')/1024));
	$jieqiTpl->assign('size_c', ceil($obook->getVar('size')/2));
	$jieqiTpl->assign('goodnum', $obook->getVar('goodnum'));
	$jieqiTpl->assign('badnum', $obook->getVar('badnum'));
	if($obook->getVar('fullflag')==0) $jieqiTpl->assign('fullflag', $jieqiLang['obook']['obook_not_full']);
	else $jieqiTpl->assign('fullflag', $jieqiLang['obook']['obook_is_full']);
	//管理
	$jieqiTpl->assign('url_manage', $obook_static_url.'/obookmanage.php?id='.$obook->getVar('obookid'));
	//举报
	$tmpstr=sprintf($jieqiLang['obook']['obook_report_reason'], $obook_dynamic_url.'/obookinfo.php?id='.$obook->getVar('obookid'));
	$jieqiTpl->assign('url_report', $obook_dynamic_url.'/newmessage.php?tosys=1&title='.urlencode(sprintf($jieqiLang['obook']['obook_report_title'], $obook->getVar('obookname','n'))).'&content='.urlencode($tmpstr));

	//点击阅读,全文阅读
	if($obook->getVar('articleid')>0){
		if($jieqiConfigs['article']['makehtml']==0 || JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET){
			$jieqiTpl->assign('url_read', $article_static_url.'/reader.php?aid='.$obook->getVar('articleid'));
		}else{
			$jieqiTpl->assign('url_read', jieqi_uploadurl($jieqiConfigs['article']['htmldir'], $jieqiConfigs['article']['htmlurl'], 'article', $article_static_url).jieqi_getsubdir($obook->getVar('articleid')).'/'.$obook->getVar('articleid').'/index'.$jieqiConfigs['article']['htmlfile']);
		}
	}else{
		$jieqiTpl->assign('url_read', '#');
	}


	//放入书架
	$jieqiTpl->assign('url_bookcase', $obook_dynamic_url.'/addbookcase.php?oid='.$obook->getVar('obookid'));
	//推荐本书
	$jieqiTpl->assign('url_uservote', $obook_dynamic_url.'/uservote.php?id='.$obook->getVar('obookid'));
	//作家专栏
	if($obook->getVar('authorid')>0){
		$jieqiTpl->assign('url_authorpage', $article_dynamic_url.'/authorpage.php?id='.$obook->getVar('authorid'));
	}else{
		$jieqiTpl->assign('url_authorpage','#');
	}

	//电子书章节

	$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');

	$buyary=array();
	if(!empty($_SESSION['jieqiUserId'])){
		include_once($jieqiModules['obook']['path'].'/class/obuyinfo.php');
		$buyinfo_handler =& JieqiObuyinfoHandler::getInstance('JieqiObuyinfoHandler');
		$criteria=new CriteriaCompo(new Criteria('obookid', $obook->getVar('obookid')));
		$criteria->add(new Criteria('userid', $_SESSION['jieqiUserId']));
		$buyinfo_handler->queryObjects($criteria);
		while($buyinfo=$buyinfo_handler->getObject()) $buyary[]=$buyinfo->getVar('ochapterid', 'n');
		unset($criteria);
	}

	$jieqiTpl->assign('url_buyobook', $obook_dynamic_url.'/buyobook.php');
	include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
	$ochapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('obookid', $obook->getVar('obookid'), '='));
	$criteria->add(new Criteria('chaptertype', 0, '='));
	$criteria->add(new Criteria('display', 0, '='));
	$criteria->setSort('ochapterid');
	$criteria->setOrder('DESC');
	$ochapter_handler->queryObjects($criteria);
	$isvip=1;
	$ochapterrows=array();
	$k=0;
	while($v = $ochapter_handler->getObject()){
		$ochapterrows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('ochapterid').'">';  //选择框
		if(in_array($v->getVar('ochapterid'), $buyary)) $ochapterrows[$k]['isbuy']=1;
		else $ochapterrows[$k]['isbuy']=0;
		$ochapterrows[$k]['checkid']=$k;  //显示序号
		$ochapterrows[$k]['ochapterid']=$v->getVar('ochapterid');  //章节序号
		$ochapterrows[$k]['chaptername']=$v->getVar('chaptername');  //章节名称
		$ochapterrows[$k]['url_chapter']=$obook_static_url.'/reader.php?oid='.$v->getVar('obookid').'&cid='.$v->getVar('ochapterid');
		$ochapterrows[$k]['size']=$v->getVar('size');
		$ochapterrows[$k]['size_k']=ceil($v->getVar('size')/1024);
		$ochapterrows[$k]['size_c']=ceil($v->getVar('size')/2);
		$ochapterrows[$k]['saleprice']=$v->getVar('saleprice');
		$ochapterrows[$k]['vipprice']=$v->getVar('vipprice');
		$ochapterrows[$k]['postdate']=date('y-m-d',$v->getVar('postdate')); //发布日期
		$ochapterrows[$k]['lastupdate']=date('y-m-d',$v->getVar('lastupdate')); //最后更新日期
		$k++;
	}
	$jieqiTpl->assign_by_ref('ochapterrows', $ochapterrows);

	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/obookinfo.html';
	include_once(JIEQI_ROOT_PATH.'/footer.php');
}
?>