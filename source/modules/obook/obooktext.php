<?php 
/**
 * 电子书章节阅读，文本格式内容
 *
 * 电子书章节阅读，文本格式内容
 * 
 * 调用模板：/modules/obook/templates/obooktext.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obooktext.php 331 2009-02-13 09:07:14Z juny $
 */

$logstart = explode(' ', microtime());
define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
jieqi_checklogin();
if(empty($_REQUEST['cid'])) exit;
$_REQUEST['cid']=intval($_REQUEST['cid']);

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if($jieqiConfigs['obook']['obkimagetype'] != 'txt') exit;
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
include_once($jieqiModules['obook']['path'].'/class/obuyinfo.php');
$buyinfo_handler =& JieqiObuyinfoHandler::getInstance('JieqiObuyinfoHandler');
$criteria=new CriteriaCompo(new Criteria('ochapterid', $_REQUEST['cid']));
$criteria->add(new Criteria('userid', $_SESSION['jieqiUserId']));
$criteria->setLimit(1);
$buyinfo_handler->queryObjects($criteria);
unset($criteria);
$buyinfo=$buyinfo_handler->getObject();
if(!is_object($buyinfo)){
	$freeread=false;
	//没有购买，看看是不是可以免费阅读
	$obook_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	$criteria=new CriteriaCompo(new Criteria('c.ochapterid', $_REQUEST['cid']));
	$criteria->setTables(jieqi_dbprefix('obook_ochapter').' c LEFT JOIN '.jieqi_dbprefix('obook_obook').' a ON c.obookid=a.obookid');
	$obook_query->queryObjects($criteria);
	$ochapter=$obook_query->getObject();
	if(is_object($ochapter)){
		if(!empty($_SESSION['jieqiUserId']) && ($_SESSION['jieqiUserId']==$ochapter->getVar('authorid') || $_SESSION['jieqiUserId']==$ochapter->getVar('agentid') || $_SESSION['jieqiUserId']==$ochapter->getVar('posterid'))) $freeread=true;
		else{
			jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
			if(isset($jieqiPower['obook']['freeread'])) $freeread=jieqi_checkpower($jieqiPower['obook']['freeread'], $jieqiUsersStatus, $jieqiUsersGroup, true);
		}
	}
	if(!$freeread) exit;
}
include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
include_once($jieqiModules['obook']['path'].'/class/ocontent.php');
$content_handler =& JieqiOcontentHandler::getInstance('JieqiOcontentHandler');
$criteria=new CriteriaCompo(new Criteria('ochapterid', $_REQUEST['cid']));
$criteria->setLimit(1);
$content_handler->queryObjects($criteria);
unset($criteria);
$content=$content_handler->getObject();
if(!is_object($content)){
	exit;
}else{
	//检查阅读标记是否存在
	if(isset($_SESSION['jieqiVisitedObooks'])) $arysession=unserialize($_SESSION['jieqiVisitedObooks']);
	else $arysession=array();
	if(!is_array($arysession)) $arysession=array();
	if(!isset($arysession[$_REQUEST['cid']]) || $arysession[$_REQUEST['cid']] != 1){
		exit;
	}else{
		unset($arysession[$_REQUEST['cid']]);
		$_SESSION['jieqiVisitedObooks']=serialize($arysession);
		@session_write_close();
	}
	
	include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
	$ts=TextConvert::getInstance('TextConvert');

	$outstr=$content->getVar('ocontent');
	if(!empty($jieqiConfigs['obook']['obookreadhead'])) $outstr = jieqi_htmlstr($jieqiConfigs['obook']['obookreadhead'])."<br />".$outstr;
	if(!empty($jieqiConfigs['obook']['obookreadfoot'])) $outstr .= "<br />".jieqi_htmlstr($jieqiConfigs['obook']['obookreadfoot']);
	$outstr = $ts->makeClickable($outstr);
	$outstr=str_replace(array("\r","\n"),"",$outstr);
	$contentrows=explode('<br /><br />', $outstr);
	$arynum=count($contentrows);
	//加入校验码
	if($freeread !== true && jieqi_getconfigs(JIEQI_MODULE_NAME, 'readcode', 'jieqiConfigs')){
		if(is_numeric($jieqiConfigs['obook']['codegroupnum']) && is_numeric($jieqiConfigs['obook']['codecharnum']) && is_string($jieqiConfigs['obook']['codechars'])) $addcode=true;
		else $addcode=false;
	}else{
		$addcode=false;
	}
	$k=0;
	for($i=0; $i<$arynum; $i++){
		if($contentrows[$i]==''){
			unset($contentrows[$i]);
		}else{
			$k++;
			if($addcode && $k == $jieqiConfigs['obook']['codegroupnum']){
				$tmpstr=jieqi_textstr($contentrows[$i]);
				$strlen=strlen($tmpstr);
				$m=0;
				$n=0;
				while($m<$strlen && $n<$jieqiConfigs['obook']['codecharnum']){
					if(ord($tmpstr[$m]) > 0x80){
						$m++;
						$n++;
					}else{
						if($tmpstr[$m] != ' ') $n++;
					}
					$m++;
				}
				if($m<=$strlen && $n==$jieqiConfigs['obook']['codecharnum']){
					$checkcode=$buyinfo->getVar('checkcode', 'n');
					if(empty($checkcode)){
						$codenum=floor(strlen($jieqiConfigs['obook']['codechars'])/2);
						$codepoint=rand(0,$codenum-1)*2;
						$checkcode=substr($jieqiConfigs['obook']['codechars'],$codepoint,2);
						$buyinfo->setVar('checkcode', $checkcode);
						$buyinfo_handler->insert($buyinfo);
					}
					$contentrows[$i]=jieqi_htmlstr(substr($tmpstr,0,$m).$checkcode.substr($tmpstr,$m));
				}
		
			}
			$contentrows[$i]='<p>'.$contentrows[$i].'</p>';
			
		}
	}
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign_by_ref('contentrows',$contentrows);
	$jieqiTpl->setCaching(0);
	$jieqiTpl->display($jieqiModules['obook']['path'].'/templates/obooktext.html');
	jieqi_freeresource();
}


?>