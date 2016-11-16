<?php 
/**
 * 显示一篇文章的相关下载链接
 *
 * 显示一篇文章的相关下载链接
 * 
 * 调用模板：/modules/article/templates/packzip.html; packjar.html; packumd.html; packtxtfull.html; packtxtchapter.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: packshow.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');
$_REQUEST['id'] = intval($_REQUEST['id']);
if(empty($_REQUEST['id']) && empty($_REQUEST['name'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('down', JIEQI_MODULE_NAME);
if(empty($_REQUEST['id']) && !empty($_REQUEST['name'])){
	include_once($jieqiModules['article']['path'].'/class/article.php');
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
	$criteria = new CriteriaCompo(new Criteria('articlename', $_REQUEST['name'], '='));
	$article_handler->queryObjects($criteria);
	$article=$article_handler->getObject();
	if(is_object($article)) $_REQUEST['id'] = intval($article->getVar('articleid','n'));
	else jieqi_printfail($jieqiLang['article']['article_not_exists']);
}
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');

$opf_file = jieqi_uploadpath($jieqiConfigs['article']['opfdir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/index'.$jieqi_file_postfix['opf'];
if(!is_file($opf_file)) jieqi_printfail($jieqiLang['article']['article_not_exists']);

//文章最后更新时间
$lastupdate = filemtime($opf_file);
//打包大小类型标志
$vsflags = array('0'=>1, '64'=>2, '128'=>4, '256'=>8, '512'=>16, '1024'=>32);

include_once(JIEQI_ROOT_PATH.'/lib/xml/xml.php');
$opf_xml=new XML();
$opf_xml->load($opf_file);
$opf_metas=array();
$tmpary=explode('-', $opf_xml->firstChild->attributes['unique-identifier']);
$tmpvar=count($tmpary);
if($tmpvar >= 3 && is_numeric($tmpary[$tmpvar-1]) && is_numeric($tmpary[$tmpvar-2])) $opf_metas['dc:Sortid']=$tmpary[$tmpvar-1];
$meta=$opf_xml->firstChild->firstChild->firstChild->firstChild;
while ($meta) {
	$opf_metas[$meta->nodeName]=$meta->firstChild->nodeValue;
	$meta = $meta->nextSibling;
}
unset($meta);

if(intval($opf_metas['dc:Display']) > 0) jieqi_printfail($jieqiLang['article']['article_not_exists']);

$chapter = $opf_xml->firstChild->firstChild->nextSibling->firstChild;
$opf_chapters = array();
$i = 0;
$opf_vnum = 0;
$opf_cnum = 0;
$volume = '';
while ($chapter) {
	$tmpary = $chapter->attributes;
	if(strtolower(trim($tmpary['content-type'])) == 'volume'){
		$volume = $tmpary['id'];
		$opf_vnum++;
	}else{
		$tmpint = strpos($tmpary['href'], '.');
		if($tmpint > 0) $opf_chapters[$i]['chapterid'] = intval(trim(substr($tmpary['href'], 0, $tmpint)));
		else $opf_chapters[$i]['chapterid'] = 0;
		$opf_chapters[$i]['volume'] = $volume;
		$opf_chapters[$i]['chapter'] = $tmpary['id'];
		$i++;
		$opf_cnum++;
	}
	if($firstvolume){
		$opf_chapters[$i]['fromvolume'] = $volume;
		$firstvolume = false;
	}
	$chapter = $chapter->nextSibling;
}
unset($chapter);

$opf_infos = array('chapters'=>$opf_cnum, 'volumes'=>$opf_vnum, 'fromvolume'=>$opf_chapters[0]['volume'], 'fromchapter'=>$opf_chapters[0]['chapter'], 'fromchapterid'=>$opf_chapters[0]['chapterid'], 'tovolume'=>$opf_chapters[$i-1]['volume'], 'tochapter'=>$opf_chapters[$i-1]['chapter'], 'tochapterid'=>$opf_chapters[$i-1]['chapterid']);

include_once(JIEQI_ROOT_PATH.'/header.php');

$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

$jieqiTpl->assign('articleid', $_REQUEST['id']);
$jieqiTpl->assign('articlename', jieqi_htmlstr($opf_metas['dc:Title']));
$jieqiTpl->assign('author', jieqi_htmlstr($opf_metas['dc:Creator']));
$jieqiTpl->assign('authorid', intval($opf_metas['dc:Creatorid']));
$jieqiTpl->assign('keywords', jieqi_htmlstr($opf_metas['dc:Subject']));
$jieqiTpl->assign('intro', jieqi_htmlstr($opf_metas['dc:Description']));
$jieqiTpl->assign('sortid', intval($opf_metas['dc:Sortid']));
$jieqiTpl->assign('typeid', intval($opf_metas['dc:Typeid']));
$jieqiTpl->assign('date', jieqi_htmlstr($opf_metas['dc:Date']));
$jieqiTpl->assign('lastupdate', filemtime($opf_file));
$jieqiTpl->setCaching(0);
switch($_REQUEST['type']){
	case 'zip':
		$jieqiConfigs['article']['makezip'] = intval($jieqiConfigs['article']['makezip']);
		if(empty($jieqiConfigs['article']['makezip'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		$jieqiTpl->assign('type', 'zip');
		$packsize = array();
		$jieqiTpl->assign_by_ref('packsize', $packsize);
		$jieqiTpl->assign('vsize', 0);
		$packrows =  array();
		$packrows[1] =  $opf_infos;
		$path = jieqi_uploadpath($jieqiConfigs['article']['zipdir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].$jieqi_file_postfix['zip'];
		//文件不存在或者过期自动生成
		if(!is_file($path) || filemtime($path) + 600 < $lastupdate){
			include_once(JIEQI_ROOT_PATH.'/modules/article/include/repack.php');
			article_repack($_REQUEST['id'], array('makezip'=>1), 1);
		}
		if(!is_file($path)) jieqi_printfail($jieqiLang['article']['down_file_nocreate']);
		$packrows[1]['maketime'] = filemtime($path);
		$packrows[1]['filesize'] = filesize($path);
		$jieqiTpl->assign_by_ref('packrows', $packrows);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/packzip.html';
		break;
	case 'txtfull':
		$jieqiTpl->assign('type', 'txtfull');
		$jieqiConfigs['article']['maketxtfull'] = intval($jieqiConfigs['article']['maketxtfull']);
		if(empty($jieqiConfigs['article']['maketxtfull'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		$packsize = array();
		$jieqiTpl->assign_by_ref('packsize', $packsize);
		$jieqiTpl->assign('vsize', 0);
		$packrows =  array();
		$packrows[1] =  $opf_infos;
		$path = jieqi_uploadpath($jieqiConfigs['article']['txtfulldir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].$jieqi_file_postfix['txt'];
		//文件不存在或者过期自动生成
		if(!is_file($path) || filemtime($path) + 600 < $lastupdate){
			include_once(JIEQI_ROOT_PATH.'/modules/article/include/repack.php');
			article_repack($_REQUEST['id'], array('maketxtfull'=>1), 1);
		}
		if(!is_file($path)) jieqi_printfail($jieqiLang['article']['down_file_nocreate']);
		$packrows[1]['maketime'] = filemtime($path);
		$packrows[1]['filesize'] = filesize($path);
		$jieqiTpl->assign_by_ref('packrows', $packrows);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/packtxtfull.html';
		break;
	case 'jar':
	case 'jad':
		$jieqiTpl->assign('type', 'jar');
		$jieqiConfigs['article']['makejar'] = intval($jieqiConfigs['article']['makejar']);
		if(empty($jieqiConfigs['article']['makejar'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		$packsize = array();
		if(($jieqiConfigs['article']['makejar'] & 1) > 0) $packsize[] = 0;
		if(($jieqiConfigs['article']['makejar'] & 2) > 0) $packsize[] = 64;
		if(($jieqiConfigs['article']['makejar'] & 4) > 0) $packsize[] = 128;
		if(($jieqiConfigs['article']['makejar'] & 8) > 0) $packsize[] = 256;
		if(($jieqiConfigs['article']['makejar'] & 16) > 0) $packsize[] = 512;
		if(($jieqiConfigs['article']['makejar'] & 32) > 0) $packsize[] = 1024;
		$jieqiTpl->assign_by_ref('packsize', $packsize);
		
		if(isset($_REQUEST['vsize'])) $_REQUEST['vsize'] = intval($_REQUEST['vsize']);
		else $_REQUEST['vsize'] = 0;
		if($_REQUEST['vsize'] == 1) $_REQUEST['vsize'] = 0;
		if(!in_array($_REQUEST['vsize'], $packsize)) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		if($_REQUEST['vsize'] > 1) $jieqiTpl->assign('vsize', $_REQUEST['vsize']);
		else $jieqiTpl->assign('vsize', 0);

		$packrows =  array();
		if($_REQUEST['vsize'] <= 1){
			$packxml = jieqi_uploadpath($jieqiConfigs['article']['jardir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['id'].'.xml';
		}else{
			$packxml = jieqi_uploadpath($jieqiConfigs['article']['jardir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['id'].'_'.$_REQUEST['vsize'].'.xml';
		}
		//文件不存在或者过期自动生成
		if(!is_file($packxml) || filemtime($packxml) + 600 < $lastupdate){
			include_once(JIEQI_ROOT_PATH.'/modules/article/include/repack.php');
			article_repack($_REQUEST['id'], array('makejar'=>1), 1);
		}
		if(is_file($packxml)){
			include_once(JIEQI_ROOT_PATH.'/lib/xml/xmlarray.php');
			$xmlarray = new XMLArray();
			if($_REQUEST['vsize'] <= 1) $packrows[1] = $xmlarray->xml2array(jieqi_readfile($packxml));
			else $packrows = $xmlarray->xml2array(jieqi_readfile($packxml));
		}
		$jieqiTpl->assign_by_ref('packrows', $packrows);

		$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/packjar.html';
		break;
	case 'umd':
		$jieqiTpl->assign('type', 'umd');
		$jieqiConfigs['article']['makeumd'] = intval($jieqiConfigs['article']['makeumd']);
		if(empty($jieqiConfigs['article']['makeumd'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		$packsize = array();
		if(($jieqiConfigs['article']['makeumd'] & 1) > 0) $packsize[] = 0;
		if(($jieqiConfigs['article']['makeumd'] & 2) > 0) $packsize[] = 64;
		if(($jieqiConfigs['article']['makeumd'] & 4) > 0) $packsize[] = 128;
		if(($jieqiConfigs['article']['makeumd'] & 8) > 0) $packsize[] = 256;
		if(($jieqiConfigs['article']['makeumd'] & 16) > 0) $packsize[] = 512;
		if(($jieqiConfigs['article']['makeumd'] & 32) > 0) $packsize[] = 1024;
		$jieqiTpl->assign_by_ref('packsize', $packsize);
		
		if(isset($_REQUEST['vsize'])) $_REQUEST['vsize'] = intval($_REQUEST['vsize']);
		else $_REQUEST['vsize'] = 0;
		if($_REQUEST['vsize'] == 1) $_REQUEST['vsize'] = 0;
		if(!in_array($_REQUEST['vsize'], $packsize)) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		if($_REQUEST['vsize'] > 1) $jieqiTpl->assign('vsize', $_REQUEST['vsize']);
		else $jieqiTpl->assign('vsize', 0);

		$packrows =  array();

		if($_REQUEST['vsize'] <= 1){
			$packxml = jieqi_uploadpath($jieqiConfigs['article']['umddir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['id'].'.xml';
		}else{
			$packxml = jieqi_uploadpath($jieqiConfigs['article']['umddir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$_REQUEST['id'].'_'.$_REQUEST['vsize'].'.xml';
		}
		//文件不存在或者过期自动生成
		if(!is_file($packxml) || filemtime($packxml) + 600 < $lastupdate){
			include_once(JIEQI_ROOT_PATH.'/modules/article/include/repack.php');
			article_repack($_REQUEST['id'], array('makeumd'=>1), 1);
		}
		if(is_file($packxml)){
			include_once(JIEQI_ROOT_PATH.'/lib/xml/xmlarray.php');
			$xmlarray = new XMLArray();
			if($_REQUEST['vsize'] <= 1) $packrows[1] = $xmlarray->xml2array(jieqi_readfile($packxml));
			else $packrows = $xmlarray->xml2array(jieqi_readfile($packxml));
		}
		$jieqiTpl->assign_by_ref('packrows', $packrows);

		$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/packumd.html';
		break;
	case 'txtchapter':
	default:
		$jieqiTpl->assign('type', 'txtchapter');
		$jieqiConfigs['article']['maketxt'] = intval($jieqiConfigs['article']['maketxt']);
		if(empty($jieqiConfigs['article']['maketxt'])) jieqi_printfail($jieqiLang['article']['down_file_notopen']);
		$packsize = array();
		$jieqiTpl->assign_by_ref('packsize', $packsize);
		$jieqiTpl->assign('vsize', 0);

		foreach($opf_chapters as $k=>$v){
			$path = jieqi_uploadpath($jieqiConfigs['article']['txtdir'], 'article').jieqi_getsubdir($_REQUEST['id']).'/'.$_REQUEST['id'].'/'.$v['chapterid'].$jieqi_file_postfix['txt'];
			$opf_chapters[$k]['maketime'] = intval(@filemtime($path));
			$opf_chapters[$k]['filesize'] = intval(@filesize($path));
		}

		$jieqiTpl->assign_by_ref('packrows', $opf_chapters);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/packtxtchapter.html';
		break;
}


include_once(JIEQI_ROOT_PATH.'/footer.php');
?>