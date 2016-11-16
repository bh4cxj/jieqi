<?php
/**
 * 圈子相册
 *
 * 圈子相册列表
 * 
 * 调用模板：/modules/group/templates/album.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
$gid = intval($_REQUEST['g']);
if($gid == 0){
	header("Location: ".JIEQI_URL);
}
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');

$jieqiTpl->assign('upload_href','upload.php?g='.$gid);
$jieqiTpl->assign('manalbum_href','manalbum.php?g='.$gid);

//分页
$onepage = 15;
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;

//albums list
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/album.php');
$album_handler = JieqialbumHandler::getInstance('JieqialbumHandler');
$criteria = new CriteriaCompo(new Criteria('gid',$gid) );
$criteria->setSort('albumorder');
$criteria->setStart( ($_REQUEST[page]-1)*$onepage );
$criteria->setlimit($onepage );
$album_handler->queryObjects($criteria);
$albums = array();
$k = 1;

while( $v = $album_handler->getObject() ) {
	if( $v->getVar('lastphotoid') && $v->getVar('nums')>0 ){
		$f_dir = $groupUserfile['albumurl'].date("Ymd",$v->getVar('lastuptime') )."/re_".$v->getVar('lastphotoid').".".$v->getVar('lastpostfix');
	} else {
		$f_dir = $groupUserfile['defaultalbum'];   
	}
	$albums[$k] = array(
				'nums'=>$v->getVar('nums'),
				'lastphoto'=>$f_dir,
				'photo_href'=>'./photo.php?g='.$gid.'&albumid='.$v->getVar('albumid'),
				'albumname'=>$v->getVar('albumname')."(".$v->getVar('nums').")",
				'mark'=>$k
			);	
	$k++;		
}
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$page_rowcount = $album_handler->getCount($criteria); //总记录数
$jumppage = new JieqiPage($page_rowcount,$onepage,$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar() );


$jieqiTpl->assign('albums',$albums);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/album.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>