<?php 
/**
 * 执行生成静态页面
 *
 * 执行生成静态页面
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: createstatic.php 228 2008-11-27 06:44:31Z juny $
 */

define('JIEQI_USE_GZIP','0');
define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人文章权限
jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
@set_time_limit(0);
@session_write_close();
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$header_str='                                                                                                                                                                                                                                                                ';

switch($_REQUEST['action']){
	case 'makeinfo':
	if(!is_numeric($_REQUEST['infostart']) || !is_numeric($_REQUEST['infostop'])) jieqi_printfail($jieqiLang['article']['create_id_neednum']);
	$_REQUEST['infostart']=intval($_REQUEST['infostart']);
	$_REQUEST['infostop']=intval($_REQUEST['infostop']);
	if($_REQUEST['infostart']>$_REQUEST['infostop']) jieqi_printfail($jieqiLang['article']['create_id_numerror']);
	include_once($jieqiModules['article']['path'].'/include/staticmakeinfo.php');
	echo $header_str;
	echo sprintf($jieqiLang['article']['create_info_doing'], $_REQUEST['infostart'], $_REQUEST['infostop']);
	ob_flush();
flush();
	for($i=$_REQUEST['infostart'];$i<=$_REQUEST['infostop'];$i++){
		makestaticinfo($i);
		echo $i.'..';
		ob_flush();
flush();
	}
	jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['create_info_success']);
	break;
	case 'makereview':
	if(!is_numeric($_REQUEST['reviewstart']) || !is_numeric($_REQUEST['reviewstop'])) jieqi_printfail($jieqiLang['article']['create_review_idnum']);
	$_REQUEST['reviewstart']=intval($_REQUEST['reviewstart']);
	$_REQUEST['reviewstop']=intval($_REQUEST['reviewstop']);
	if($_REQUEST['reviewtart']>$_REQUEST['reviewstop']) jieqi_printfail($jieqiLang['article']['create_id_numerror']);
	include_once($jieqiModules['article']['path'].'/include/staticmakereview.php');
	echo $header_str;
	echo sprintf($jieqiLang['article']['create_review_doing'], $_REQUEST['reviewstart'], $_REQUEST['reviewstop']);
	ob_flush();
flush();
	for($i=$_REQUEST['reviewstart'];$i<=$_REQUEST['reviewstop'];$i++){
		makestaticreview($i);
		echo $i.'..';
		ob_flush();
flush();
	}
	jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['create_review_success']);
	break;
	case 'makesort':
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$_REQUEST['maxsortpage']=intval($_REQUEST['maxsortpage']);
	echo $header_str;
	echo sprintf($jieqiLang['article']['create_sort_doing'], '1', $_REQUEST['maxsortpage']);
	ob_flush();
flush();
	include_once($jieqiModules['article']['path'].'/include/staticmakeindex.php');
	$i=1;
	$pagenum=1;
	while($i<=$pagenum){
		$pagenum=makestaticindex('','',0,$i,$_REQUEST['maxsortpage']);
		echo $i.'..';
		ob_flush();
flush();
		$i++;
	}

	foreach($jieqiSort['article'] as $k=>$v){
		echo sprintf($jieqiLang['article']['create_sort_info'], $v['caption']);
		ob_flush();
flush();
		$i=1;
		$pagenum=1;
		while($i<=$pagenum){
			$pagenum=makestaticindex($k,'',0,$i,$_REQUEST['maxsortpage']);
			echo $i.'..';
			ob_flush();
flush();
			$i++;
		}
	}
	jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['create_sort_success']);
	break;
	case 'makeinitial':
	$_REQUEST['maxinitialpage']=intval($_REQUEST['maxinitialpage']);
	echo sprintf($jieqiLang['article']['create_initial_doing'], '1', $_REQUEST['maxinitialpage']);
	ob_flush();
flush();
	include_once($jieqiModules['article']['path'].'/include/staticmakeindex.php');
	$initary['1']='1';
	for($i=65; $i<=90; $i++){
		$tmpvar=chr($i);
		$initary[$tmpvar]=$tmpvar;
	}
	$initary['0']='~';
	foreach($initary as $k=>$v){
		echo sprintf($jieqiLang['article']['create_initial_info'], $v);
		ob_flush();
flush();
		$i=1;
		$pagenum=1;
		while($i<=$pagenum){
			$pagenum=makestaticindex('',$v,0,$i,$_REQUEST['maxinitialpage']);
			echo $i.'..';
			ob_flush();
flush();
			$i++;
		}
	}
	jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['create_initial_success']);
	break;
	case 'maketoplist':
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$_REQUEST['maxtoppage']=intval($_REQUEST['maxtoppage']);
	echo $header_str;
	echo sprintf($jieqiLang['article']['create_toplist_doing'], '1', $_REQUEST['maxtoppage']);
	ob_flush();
flush();
	$topary=array('allvisit'=>$jieqiLang['article']['top_allvisit'], 'monthvisit'=>$jieqiLang['article']['top_monthvisit'], 'weekvisit'=>$jieqiLang['article']['top_weekvisit'], 'dayvisit'=>$jieqiLang['article']['top_dayvisit'], 'allauthorvisit'=>$jieqiLang['article']['top_avall'], 'monthauthorvisit'=>$jieqiLang['article']['top_avmonth'], 'weekauthorvisit'=>$jieqiLang['article']['top_avweek'], 'dayauthorvisit'=>$jieqiLang['article']['top_avday'], 'allvote'=>$jieqiLang['article']['top_voteall'], 'monthvote'=>$jieqiLang['article']['top_votemonth'], 'weekvote'=>$jieqiLang['article']['top_voteweek'], 'dayvote'=>$jieqiLang['article']['top_voteday_titile'], 'postdate'=>$jieqiLang['article']['top_postdate'], 'toptime'=>$jieqiLang['article']['top_toptime'], 'goodnum'=>$jieqiLang['article']['top_goodnum'], 'size'=>$jieqiLang['article']['top_size'], 'authorupdate'=>$jieqiLang['article']['top_authorupdate'], 'masterupdate'=>$jieqiLang['article']['top_masterupdate'], 'lastupdate'=>$jieqiLang['article']['top_lastupdate']);
	include_once($jieqiModules['article']['path'].'/include/staticmaketoplist.php');
	foreach($topary as $k=>$v){
		echo sprintf($jieqiLang['article']['create_toplist_info'], $v);
		ob_flush();
flush();
		$i=1;
		$pagenum=1;
		while($i<=$pagenum){
			$pagenum=makestatictoplist($k,'',$i,$_REQUEST['maxtoppage']);
			echo $i.'..';
			ob_flush();
flush();
			$i++;
		}
	}
	jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['create_toplist_success']);
	break;
	default:
	jieqi_printfail($jieqiLang['article']['create_para_error']);
}

//取得文件保存目录
function getsubdir($dirname, $id)
{
	global $jieqiConfigs;
	$retdir='';
	if(!empty($dirname) && is_numeric($id)){
		$retdir .= jieqi_getsubdir($id);
		if (!file_exists($retdir)) jieqi_createdir($retdir);
	}
	return $retdir;
}
?>