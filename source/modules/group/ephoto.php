<?php
/**
 * 修改圈子照片
 *
 * 修改圈子照片
 * 
 * 调用模板：/modules/group/templates/ephoto.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');

jieqi_loadlang('ephoto',JIEQI_MODULE_NAME);
$gid = intval($_REQUEST['g']);
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');

include_once("include/functions.php");
setpower($gid);

$albumid = intval( $_REQUEST['albumid'] );
include_once($groupUserfile['albumscat']);
$jieqiTpl->assign('albumname',$albumscat[$albumid] );
$jieqiTpl->assign('albumid',$albumid);
$photoid = intval($_REQUEST['photoid']);

$i=0;
foreach($albumscat as $albumid1=>$albumname ){
	$image_cats[$i]= array('id'=>$albumid1,'name'=>$albumname);
	$i++;
}
$jieqiTpl->assign('image_cats',$image_cats );
//photo  handler
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/photo.php');
$photo_handler = JieqiphotoHandler::getInstance('JieqiphotoHandler');

setpower($gid);
if( $_REQUEST['doedit'] ) {
	$photo = $photo_handler->get($photoid);
	if($_SESSION['jieqiUserId'] == $photo->getVar('posterid') ){
		$pic_owner = 1;
	}	
	
	if( !($allowmanalbum || $pic_owner) ){
		jieqi_printfail("hack? error!");
	}

	$photo->setVar('intro',$_REQUEST['intro'] );
	if( (  $newalbumid = intval($_REQUEST['newalbumid']) ) != $albumid   ) {
		$photo->setVar('albumid',$newalbumid);
		$photo_handler->insert($photo);

		//更改相关相册的最新信息
		//album  handler
		include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/album.php');
		$album_handler = JieqialbumHandler::getInstance('JieqialbumHandler');
		
		//from album
		$max_handler = JieqiQueryHandler::getInstance('JieqiQueryHandler') ;	
		$sql= "select photoid,uptime,postfix from ".jieqi_dbprefix("group_photo")." where albumid=$albumid order by photoid desc limit 1";
		$max_handler->execute($sql);
		$criteria = new Criteria('albumid',$albumid );
		if( $v_from = $max_handler->getObject() ){
			$lastphotoid = $v_from->getVar('photoid') ;
			$lastuptime  = $v_from->getVar('uptime') ;
			$lastpostfix = $v_from->getVar('postfix') ;
			$album_handler->updatefields("lastphotoid='$lastphotoid',lastuptime='$lastuptime',lastpostfix='$lastpostfix',nums=nums-1 ",$criteria);
		
		} else {
			$lastphotid = $lastuptime = $lastpostfix = '';
			$album_handler->updatefields("lastphotoid='0',lastuptime='0',lastpostfix=' ',nums='0' ",$criteria);
		} 

		//to album
		$max_handler = JieqiQueryHandler::getInstance('JieqiQueryHandler') ;	
		$sql_to = "select photoid,uptime,postfix from ".jieqi_dbprefix("group_photo")." where albumid=$newalbumid order by photoid desc limit 1";
		$max_handler->execute($sql_to);
		if($v_to = $max_handler->getObject() ) {
			$lastphotoid = $v_to->getVar('photoid') ;
			$lastuptime  = $v_to->getVar('uptime') ;
			$lastpostfix = $v_to->getVar('postfix') ;
			$criteria = new Criteria('albumid',$newalbumid );
			$album_handler->updatefields("lastphotoid='$lastphotoid',lastuptime='$lastuptime',lastpostfix='".jieqi_dbslashes($lastpostfix)."',nums=nums+1 ",$criteria);
		}
	} else {
		$photo_handler->insert($photo);
	}

	//jieqi_jumppage("photo.php?g=$gid&albumid=$albumid&page=$_RQUEST[page] ",LANG_DO_SUCCESS,$jieqiLang['g']['edit_success']);
	exit($jieqiLang['g']['edit_success'].'<script>setTimeout("top.location.reload();",600);</script>');
}


$criteria = new CriteriaCompo(new Criteria('albumid',$albumid) );
$criteria->add(new Criteria( 'photoid',$photoid )  );
$count =  $photo_handler->getCount( $criteria );

$photo_handler->queryObjects($criteria);
$v = $photo_handler->getObject();

$jieqiTpl->assign('src',  ($groupUserfile['albumurl'].date("Ymd",$v->getVar('uptime') )."/re_".$v->getVar('photoid').".".$v->getVar('postfix') )    );
$jieqiTpl->assign('intro',$v->getVar('intro') );

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/ephoto.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>