<?php
/**
 * 区块拖动配置
 *
 * 区块拖动配置
 * 
 * 调用模板：/templates/admin/blockconfig.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: blocks.php 177 2008-11-24 08:05:10Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminblock'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
include_once(JIEQI_ROOT_PATH.'/header.php');
/*
$jieqiTset['jieqi_blocks_module'] = 'system';
$jieqiTset['jieqi_blocks_config'] = 'blocks';
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_page_template'] = JIEQI_ROOT_PATH.'/templates/admin/blockconfig.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');
*/
jieqi_getconfigs($_REQUEST['modules'], $_REQUEST['file'], 'jieqiBlocks');

if($_REQUEST['action']!='')
{
    $blocks = array();
	$extstr = array();
	switch ($_REQUEST['action'])
	{
		case 'edit'://修改配置文件
		    $jieqiBlocks = array();//清空原始配置参数
		    $blockstr = $_REQUEST['blockstr'];
			$splitstr = explode('|',$blockstr);
			$i=0 ;
			foreach($splitstr as $row){
			   if($row){
				  $extstr = explode('--',$row);
				  if($extstr[2]){
			         $i++;
					  $blocks = unserialize($extstr[2]);
					  $blocks['side'] = $extstr[1];
					  $jieqiBlocks[$i] = $blocks;
				  }
			   }
			   unset($blocks);
			   unset($extstr);
			}
			if($_REQUEST['mod']=='doando'){
				$jieqiTset['jieqi_blocks_module'] = 'system';
				$jieqiTset['jieqi_blocks_config'] = 'blocks';
				$jieqiTpl->assign('mod',$_REQUEST['mod']);
				$jieqiTpl->setCaching(0);
				//$jieqiTset['jieqi_page_template'] = JIEQI_ROOT_PATH.'/templates/admin/blockconfig.html';
				include_once(JIEQI_ROOT_PATH.'/footer.php');
				exit;
			}
			//更新配置文件
			jieqi_setconfigs($_REQUEST['file'],'jieqiBlocks',$jieqiBlocks,$_REQUEST['modules']);
			//include_once(JIEQI_ROOT_PATH.'/footer.php');
			//exit;
		break;
	}
}


//区块处理
if(!isset($jieqi_showlblock)) $jieqi_showlblock = false;
if(!isset($jieqi_showcblock)) $jieqi_showcblock = false;
if(!isset($jieqi_showrblock)) $jieqi_showrblock = false;
if(!isset($jieqi_showtblock)) $jieqi_showtblock = false;
if(!isset($jieqi_showbblock)) $jieqi_showbblock = false;

//如果包含区块显示参数则显示
if (isset($jieqiBlocks) && is_array($jieqiBlocks)){
	reset($jieqiBlocks);
	//遍历所有区块
	while(list($i) = each($jieqiBlocks)){
		$blockindex = (empty($jieqiBlocks[$i]['bid'])) ? 'bid'.$i : 'bid'.$jieqiBlocks[$i]['bid'];
		switch ($jieqiBlocks[$i]['side']) {
			case JIEQI_SIDEBLOCK_LEFT:
				$jieqi_blockside = 'jieqi_lblocks';
				$jieqi_showlblock = true;
				break;
			case JIEQI_CENTERBLOCK_LEFT:
				$jieqi_blockside = 'jieqi_clblocks';
				$jieqi_showcblock = true;
				break;
			case JIEQI_CENTERBLOCK_RIGHT:
				$jieqi_blockside = 'jieqi_crblocks';
				$jieqi_showcblock = true;
				break;
			case JIEQI_CENTERBLOCK_TOP:
				$jieqi_blockside = 'jieqi_ctblocks';
				$jieqi_showcblock = true;
				break;
			case JIEQI_CENTERBLOCK_MIDDLE:
				$jieqi_blockside = 'jieqi_cmblocks';
				$jieqi_showcblock = true;
				break;
			case JIEQI_CENTERBLOCK_BOTTOM:
				$jieqi_blockside = 'jieqi_cbblocks';
				$jieqi_showcblock = true;
				break;
			case JIEQI_SIDEBLOCK_RIGHT:
				$jieqi_blockside = 'jieqi_rblocks';
				$jieqi_showrblock = true;
				break;
			case JIEQI_TOPBLOCK_ALL:
				$jieqi_blockside = 'jieqi_tblocks';
				$jieqi_showtblock = true;
				break;
			case JIEQI_BOTTOMBLOCK_ALL:
				$jieqi_blockside = 'jieqi_bblocks';
				$jieqi_showbblock = true;
				break;
			default:
				$jieqi_blockside = 'jieqi_oblocks';
				break;
		}
		$blockvalue = $jieqiBlocks[$i];
		//$blockvalue['id'] = $i;
		//$str = '';
		//foreach($blockvalue as $k=>$val){
		//   $str.=$k.'='.$val.',';
		//}
		//$blockvalue['vars'] = substr($str,0,strlen($str)-1);
		$blockvalue['var'] = htmlspecialchars(serialize($blockvalue));
		$blockvalue['id'] = $i;
		//$blockvalue['title'] = $jieqiBlocks[$i]['title'];
		$blockvalue['content'] = '<div id="block_content_'.$i.'"></div><script language="javascript" type="text/javascript">Ajax.Update(\'/blockshow.php?bid='.urlencode($jieqiBlocks[$i]['bid']).'&module='.urlencode($jieqiBlocks[$i]['module']).'&filename='.urlencode($jieqiBlocks[$i]['filename']).'&classname='.urlencode($jieqiBlocks[$i]['classname']).'&vars='.urlencode($jieqiBlocks[$i]['vars']).'&template='.urlencode($jieqiBlocks[$i]['template']).'&contenttype='.urlencode($jieqiBlocks[$i]['contenttype']).'&custom='.urlencode($jieqiBlocks[$i]['custom']).'&publish=3&hasvars='.urlencode($jieqiBlocks[$i]['hasvars']).'\', {outid:\'block_content_'.$i.'\', tipid:\'block_content_'.$i.'\', onLoading:\'正在载入...\'})</script>';
		if(!empty($blockvalue)){
			$jieqi_pageblocks[$blockindex] = $blockvalue;
			${$jieqi_blockside}[] = &$jieqi_pageblocks[$blockindex];
		}

	}
	$blockKEY = count($jieqiBlocks);
	unset($blockindex);
	unset($blockvalue);
	unset($jieqiBlocks);
}

$jieqi_showblock=$jieqi_showlblock | $jieqi_showcblock | $jieqi_showrblock | $jieqi_showtblock | $jieqi_showbblock;

$jieqiTpl->assign('jieqi_showblock',intval($jieqi_showblock));
if(isset($jieqi_pageblocks)) $jieqiTpl->assign_by_ref('jieqi_pageblocks', $jieqi_pageblocks);
if($jieqi_showlblock){
	$jieqiTpl->assign('jieqi_showlblock',1);
	if(isset($jieqi_lblocks) && is_array($jieqi_lblocks)) $jieqiTpl->assign_by_ref('jieqi_lblocks', $jieqi_lblocks);
}else{
	$jieqiTpl->assign('jieqi_showlblock',0);
}
if($jieqi_showcblock){
	$jieqiTpl->assign('jieqi_showcblock',1);
	if(isset($jieqi_clblocks) && is_array($jieqi_clblocks)) $jieqiTpl->assign_by_ref('jieqi_clblocks', $jieqi_clblocks);
	if(isset($jieqi_crblocks) && is_array($jieqi_crblocks)) $jieqiTpl->assign_by_ref('jieqi_crblocks', $jieqi_crblocks);
	if(isset($jieqi_ctblocks) && is_array($jieqi_ctblocks)) $jieqiTpl->assign_by_ref('jieqi_ctblocks', $jieqi_ctblocks);
	if(isset($jieqi_cmblocks) && is_array($jieqi_cmblocks)) $jieqiTpl->assign_by_ref('jieqi_cmblocks', $jieqi_cmblocks);
	if(isset($jieqi_cbblocks) && is_array($jieqi_cbblocks)) $jieqiTpl->assign_by_ref('jieqi_cbblocks', $jieqi_cbblocks);
}else{
	$jieqiTpl->assign('jieqi_showcblock',0);
}
if($jieqi_showrblock){
	$jieqiTpl->assign('jieqi_showrblock',1);
	if(isset($jieqi_rblocks) && is_array($jieqi_rblocks)) $jieqiTpl->assign_by_ref('jieqi_rblocks', $jieqi_rblocks);
}else{
	$jieqiTpl->assign('jieqi_showrblock',0);
}
if($jieqi_showtblock){
	$jieqiTpl->assign('jieqi_showtblock',1);
	if(isset($jieqi_tblocks) && is_array($jieqi_tblocks)) $jieqiTpl->assign_by_ref('jieqi_tblocks', $jieqi_tblocks);
}else{
	$jieqiTpl->assign('jieqi_showtblock',0);
}
if($jieqi_showbblock){
	$jieqiTpl->assign('jieqi_showbblock',1);
	if(isset($jieqi_bblocks) && is_array($jieqi_bblocks)) $jieqiTpl->assign_by_ref('jieqi_bblocks', $jieqi_bblocks);
}else{
	$jieqiTpl->assign('jieqi_showbblock',0);
}
$jieqiTpl->assign('name',$_REQUEST['name']);
$jieqiTpl->assign('modules',$_REQUEST['modules']);
$jieqiTpl->assign('file',$_REQUEST['file']);
$jieqiTpl->assign('blockKEY',$blockKEY);
//读取模块

$jieqiTpl->setCaching(0);
$jieqiTpl->display(JIEQI_ROOT_PATH.'/templates/admin/blockconfig.html');

?>