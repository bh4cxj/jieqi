<?php 
/**
 * 显示电子书阅读图片
 *
 * 显示电子书阅读图片
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obookimage.php 231 2008-11-27 08:46:26Z juny $
 */

$logstart = explode(' ', microtime());
define('JIEQI_MODULE_NAME', 'obook');
define('JIEQI_NOCONVERT_CHAR','1');
@ini_set('memory_limit', '64M'); //设置允许使用的内存
require_once('../../global.php');
jieqi_checklogin();
if(empty($_REQUEST['cid'])) exit;
$_REQUEST['cid']=intval($_REQUEST['cid']);

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$jieqiConfigs['obook']['obkpictxt'] = '20000'; //一张图片显示多少字节
$jieqiConfigs['obook']['obkpictxt'] = intval($jieqiConfigs['obook']['obkpictxt']);

$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
/*
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
*/

//检查阅读标记是否存在
	if(isset($_SESSION['jieqiVisitedObooks'])) $arysession=unserialize($_SESSION['jieqiVisitedObooks']);
	else $arysession=array();
	if(!is_array($arysession)) $arysession=array();
	if(!isset($arysession[$_REQUEST['cid']]) || $arysession[$_REQUEST['cid']] != 1){
		exit;
	}else{
	//	unset($arysession[$_REQUEST['cid']]);
	//	$_SESSION['jieqiVisitedObooks']=serialize($arysession);
		@session_write_close();
	}


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
	include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
	include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
	include_once(JIEQI_ROOT_PATH.'/lib/image/imagetext.php');
	$outstr=$content->getVar('ocontent', 'n');
	if($_REQUEST['pic'] > 0){
		$_REQUEST['pic']=intval($_REQUEST['pic']);
		$outstr = jieqi_substr($outstr, ($_REQUEST['pic'] - 1) * $jieqiConfigs['obook']['obkpictxt'], $jieqiConfigs['obook']['obkpictxt'], '');
	}
	if(!empty($jieqiConfigs['obook']['obookreadhead'])) $outstr = $jieqiConfigs['obook']['obookreadhead']."\r\n".$outstr;
	if(!empty($jieqiConfigs['obook']['obookreadfoot'])) $outstr .= "\r\n".$jieqiConfigs['obook']['obookreadfoot'];
	$outstr=jieqi_limitwidth($outstr,$jieqiConfigs['obook']['obklinewidth']);
	
	//文字水印
	if(isset($jieqiConfigs['obook']['obkwaterformat'])) $watertext=str_replace(array('<{$userid}>', '<{$username}>', '<{$date}>', '<{$time}>'),array($_SESSION['jieqiUserId'], $_SESSION['jieqiUserName'], date(JIEQI_DATE_FORMAT, JIEQI_NOW_TIME), date(JIEQI_TIME_FORMAT, JIEQI_NOW_TIME)), $jieqiConfigs['obook']['obkwaterformat']);
	else $watertext=$_SESSION['jieqiUserId'];
	if(strlen($watertext) < 10) $watertext=sprintf('%10s',$watertext);

	$charsetary=array('gb2312'=>'gb', 'gbk'=>'gb', 'gb'=>'gb', 'big5'=>'big5', 'utf-8'=>'utf8', 'utf8'=>'utf8');

	$fontcharset=JIEQI_SYSTEM_CHARSET;
	//段首空格问题
	if(JIEQI_SYSTEM_CHARSET=='gb2312' || JIEQI_SYSTEM_CHARSET=='gbk') $outstr=str_replace('    ', chr(161).chr(161).chr(161).chr(161), $outstr);
	elseif(JIEQI_SYSTEM_CHARSET=='big5') $outstr=str_replace('    ', chr(161).chr(64).chr(161).chr(64), $outstr);
	
	if(JIEQI_SYSTEM_CHARSET != JIEQI_CHAR_SET){
		if((JIEQI_SYSTEM_CHARSET=='gb2312' || JIEQI_SYSTEM_CHARSET=='gbk') && JIEQI_CHAR_SET=='big5'){
			if(!empty($jieqiConfigs['obook']['obkcharconvert'])){
				$outstr=jieqi_gb2big5($outstr);
				$watertext=jieqi_gb2big5($watertext);
				$fontcharset=JIEQI_CHAR_SET;
			}
		}elseif(JIEQI_SYSTEM_CHARSET=='big5' && (JIEQI_CHAR_SET=='gb2312' || JIEQI_CHAR_SET=='gbk')){
			if(!empty($jieqiConfigs['obook']['obkcharconvert'])){
				$outstr=jieqi_big52gb($outstr);
				$watertext=jieqi_big52gb($watertext);
				$fontcharset=JIEQI_CHAR_SET;
			}
		}
	}

	$changefun='';
	if(isset($charsetary[$fontcharset])) $changefun='jieqi_'.$charsetary[$fontcharset].'2utf8';
	if(function_exists($changefun)){
		$outstr = call_user_func($changefun, $outstr);
		$watertext = call_user_func($changefun, $watertext);
	}

	$img=new ImageText();
	$img->set('text', $outstr);
	$img->set('startx', $jieqiConfigs['obook']['obkstartx']);
	$img->set('starty', $jieqiConfigs['obook']['obkstarty']);
	$img->set('fontsize', $jieqiConfigs['obook']['obkfontsize']);
	if(JIEQI_CHAR_SET == 'big5'){
		$img->set('fontfile', $jieqiConfigs['obook']['obkfontft']);
	}else{
		$img->set('fontfile', $jieqiConfigs['obook']['obkfontjt']);
	}
	$img->set('angle', $jieqiConfigs['obook']['obkangle']);
	$img->set('imagecolor', $jieqiConfigs['obook']['obkimagecolor']);
	$img->set('textcolor', $jieqiConfigs['obook']['obktextcolor']);
	$img->set('shadowcolor', $jieqiConfigs['obook']['obkshadowcolor']);
	$img->set('shadowdeep', $jieqiConfigs['obook']['obkshadowdeep']);
	$img->set('imagetype', $jieqiConfigs['obook']['obkimagetype']);
	if(isset($jieqiConfigs['obook']['obkwatertext'])) $img->set('watertplace', intval($jieqiConfigs['obook']['obkwatertext']));
	else $img->set('watertplace', 2); //默认平铺，为了兼容以前程序
	$img->set('watertext', $watertext);
	$img->set('watercolor', $jieqiConfigs['obook']['obkwatercolor']);
	$img->set('watersize', $jieqiConfigs['obook']['obkwatersize']);
	$img->set('waterangle', $jieqiConfigs['obook']['obkwaterangle']);
	$img->set('waterpct', $jieqiConfigs['obook']['obkwaterpct']);
	$jieqiConfigs['obook']['jpegquality']=intval($jieqiConfigs['obook']['jpegquality']);
	if($jieqiConfigs['obook']['jpegquality']>=0 && $jieqiConfigs['obook']['jpegquality']<=100) $img->set('jpegquality', $jieqiConfigs['obook']['jpegquality']);
	//图片水印
	$jieqiConfigs['obook']['obookwater']=intval($jieqiConfigs['obook']['obookwater']);
	if($jieqiConfigs['obook']['obookwater'] > 0) $img->set('wateriplace', $jieqiConfigs['obook']['obookwater']);
	$jieqiConfigs['obook']['obookwtrans']=intval($jieqiConfigs['obook']['obookwtrans']);
	if($jieqiConfigs['obook']['obookwtrans']>=1 && $jieqiConfigs['obook']['obookwtrans']<=100) $img->set('wateritrans', $jieqiConfigs['obook']['obookwtrans']);
	
	if(!empty($jieqiConfigs['obook']['obookwimage']) && is_file($jieqiModules['obook']['path'].'/images/'.$jieqiConfigs['obook']['obookwimage'])) $img->set('waterimage', $jieqiModules['obook']['path'].'/images/'.$jieqiConfigs['obook']['obookwimage']);
	
	$img->display();
}


?>