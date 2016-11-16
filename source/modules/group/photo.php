<?php
/**
 * 圈子照片列表
 *
 * 圈子照片列表
 * 
 * 调用模板：/modules/group/templates/photo.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_loadlang('photo',JIEQI_MODULE_NAME);
$gid = intval($_REQUEST['g']);
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');
include_once("include/functions.php");
setpower($gid);
$albumid = intval( $_REQUEST['albumid'] );
include_once($groupUserfile['albumscat']);
$jieqiTpl->assign('albumname',$albumscat[$albumid] );

//photos list
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/photo.php');
$photo_handler = JieqiphotoHandler::getInstance('JieqiphotoHandler');

if( ($delid = intval($_REQUEST['delid']) ) &&($v = $photo_handler->get($delid))){
	if($_SESSION['jieqiUserId'] == $v->getVar('posterid') ) {
		$pic_owner= 1;
	}
	$allowmanalbum = ($allowmanalbum || $pic_owner);
	if(!$allowmanalbum){
	   exit($jieqiLang['g']['no_delpower'].'<script>setTimeout("top.location.reload();",600);</script>');
	}
	//删除文件
	$dir = $groupUserfile['albumdir'].date('Ymd',$v->getVar('uptime') ); 
	@unlink($dir.'/re_'.$delid.'.'.$v->getVar('postfix') );
	@unlink($dir.'/'.$delid.'.'.$v->getVar('postfix') );
	$photo_handler->delete($delid);

	//更新相册图片统计
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/album.php');
	$album_handler = JieqialbumHandler::getInstance('JieqialbumHandler');
	$album = $album_handler->get($albumid);
	$album->setVar('nums',( ($album->getVar('nums')) - 1) );
	include_once("./include/functions.php");
	update_ginfo('gpics=gpics-1',$gid);
	if( $delid == $album->getVar('lastphotoid') ){
		$max_handler = JieqiQueryHandler::getInstance('JieqiQueryHandler') ;	
		$sql = "select max(photoid) as photoid,uptime,postfix from ".jieqi_dbprefix("group_photo")." where albumid=$albumid group by photoid desc";
		$max_handler->execute($sql);
		if( $v = $max_handler->getObject() ) {
			$album->setVar('lastphotoid',$v->getVar('photoid') );
			$album->setVar('lastuptime',$v->getVar('uptime') );
			$album->setVar('lastpostfix',$v->getVar('postfix') );
		} else {
			$album->setVar('nums',0);
		}
	}
	$album_handler->insert($album); 
	exit($jieqiLang['g']['del_success'].'<script>setTimeout("top.location.reload();",600);</script>');
}


$criteria = new CriteriaCompo(new Criteria('albumid',$albumid) );
$count =  $photo_handler->getCount( $criteria );

//分页
$onepage = 15;
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;


$criteria->setStart( ($_REQUEST[page]-1)*$onepage );
$criteria->setlimit($onepage );
$photo_handler->queryObjects($criteria);
$photos = array();
$k = 1;
while( $v = $photo_handler->getObject() ) {
	$vpage = ( $_REQUEST['page']-1 )*$onepage+$k;

	
	if($_SESSION['jieqiUserId'] == $v->getVar('posterid') ) {
		$pic_owner= 1;
	}

	$allowmanalbum = ($allowmanalbum || $pic_owner);

	if( $allowmanalbum ) { 
		$man_href = "<a href=ephoto.php?g=$gid&albumid=$albumid&photoid=".$v->getVar('photoid')."&page=$_REQUEST[page]>".$jieqiLang['g']['edit']."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='?g=$gid&albumid=$albumid&page=$_REQUEST[page]&delid=".$v->getVar('photoid')."' onclick=\"javascript:if(confirm('".$jieqiLang['g']['query_confirm_del']."') ){}else{ return false; };\";  >".$jieqiLang['g']['del']."</a>"; 		
	}else{
	    $man_href = "";
	}

	$photos[$k] = array(
			'photo'=>$groupUserfile['albumurl'].date("Ymd",$v->getVar('uptime') )."/re_".$v->getVar('photoid').".".$v->getVar('postfix'),
			'intro'=>$v->getVar('intro'),
			'mark'=>$k,
			'allowmanalbum'=>$allowmanalbum,
			'albumid'=>$v->getVar('albumid'),
			'vpage'=>$vpage,
			'photoid'=>$v->getVar('photoid'),
			'vphoto_href'=>"./vphoto.php?g=$gid&albumid=$albumid&page=$vpage",
			'man_href'=>$man_href
			);	
	$k++;
}
$jieqiTpl->assign('image_cats',$photos);

//处理页面跳转
$page_rowcount = $photo_handler->getCount($criteria); //总记录数
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($page_rowcount, $onepage, $_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->assign('page',$_REQUEST['page']);
$jieqiTpl->assign('upload_href','upload.php?g='.$gid."&albumid=$albumid");
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/photo.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>