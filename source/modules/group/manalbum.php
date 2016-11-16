<?php
/**
 * 修改圈子相册
 *
 * 修改圈子相册
 * 
 * 调用模板：/modules/group/templates/manalbum.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');

jieqi_checklogin();
jieqi_loadlang('manalbum',JIEQI_MODULE_NAME);
$gid = intval($_REQUEST['g']);
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/album.php');
$album_handler = JieqialbumHandler::getInstance('JieqialbumHandler');

//显示数据
$criteria = new CriteriaCompo(new Criteria('gid',$gid) );
$criteria->add( new Criteria('defaultflag',0) );
$criteria->setSort('albumorder');
$criteria->setOrder('asc');
$album_handler->queryObjects($criteria);
$albums = array();
while($v = $album_handler->getObject() ){
	$albums[] = array(
			'albumid'=>$v->getVar('albumid'),
			'albumname'=>$v->getVar('albumname'),
			'albumorder'=>$v->getVar('albumorder')
			);
}
$jieqiTpl->assign_by_ref('albums',$albums);

include(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
setpower($gid);
if($allowmanalbum!=1) {
	jieqi_printfail($jieqiLang['g']['no_power_man_album']);
}


//删除，增加，修改数据
if( $_REQUEST['action'] ){
	require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/photo.php');
	$photo_handler = JieqiphotoHandler::getInstance('JieqiphotoHandler');

	if(!empty($_REQUEST['albumsname']) ){
		foreach($_REQUEST['albumsname'] as $id=>$v){
			if($_REQUEST['delete'][$id]){
				$criteria = new CriteriaCompo(new Criteria('albumid',$id) );
				$criteria->add(new Criteria('defaultflag',1,'!=')  );
				if ($album_handler->delete($criteria) ) {
					//delete file
					$criteria = new Criteria('albumid',$id);
					$photo_handler->queryObjects($criteria);
					while( $v = $photo_handler->getObject() ){
						if( file_exists($file = $groupUserfile['albumdir'].date("Ymd",$v->getVar('uptime') )."/".$v->getVar('photoid').".".$v->getVar('postfix') )  ) {
							unlink($file);
						}		
					}
					$photo_handler->delete($criteria);

				}
			}else{
				$album = $album_handler->get($id);
				$album->setVar('albumname',$_REQUEST['albumsname'][$id] );
				$album->setVar('albumorder',intval($_REQUEST['albumsorder'][$id] ) );
				$album_handler->insert($album);
			}
		}
	}

	if(!empty($_REQUEST['newalbum']) ){
		$newalbum = $album_handler->create();
		$newalbum->setVar('albumname',$_REQUEST['newalbum'] );
		$newalbum->setVar('albumorder',intval($_REQUEST['albumorder'])  );
		$newalbum->setVar('gid',$gid);
		$album_handler->insert($newalbum);
	}

	//相册分类文件
	$criteria = new CriteriaCompo(new Criteria('gid',$gid) );
	$criteria->setSort('albumorder');
	$criteria->setOrder('asc');
	$album_handler->queryObjects($criteria);
	$albums = array();
	while($v = $album_handler->getObject() ){
		$albums[$v->getVar('albumid')] = $v->getVar('albumname'); 
	}
	
	$string = "<?php  \n \$albumscat = ".var_export($albums,true)."; \n  ?>";
	
	jieqi_writefile($groupUserfile['albumscat'],$string);

	jieqi_jumppage("./album.php?g=$gid",LANG_DO_SUCCESS,$jieqiLang['g']['edit_album_success'] );
}

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/manalbum.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>