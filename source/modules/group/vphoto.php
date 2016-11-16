<?php
/**
 * 显示圈子照片
 *
 * 显示圈子照片
 * 
 * 调用模板：/modules/group/templates/vphoto.html
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

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');

include_once("include/functions.php");
setpower($gid);

$albumid = intval( $_REQUEST['albumid'] );
include_once($groupUserfile['albumscat']);
$jieqiTpl->assign('albumname',$albumscat[$albumid] );
$jieqiTpl->assign('albumid',$albumid);


//photos list
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/photo.php');
$photo_handler = JieqiphotoHandler::getInstance('JieqiphotoHandler');



$criteria = new CriteriaCompo(new Criteria('albumid',$albumid) );
$count =  $photo_handler->getCount( $criteria );

//分页
$onepage = 1;
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($count,$onepage,$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar() );

$criteria->setStart( ($_REQUEST[page]-1)*$onepage );
$criteria->setlimit($onepage );

$photo_handler->queryObjects($criteria);
$v = $photo_handler->getObject();

$jieqiTpl->assign('src',  ($groupUserfile['albumurl'].date("Ymd",$v->getVar('uptime') )."/".$v->getVar('photoid').".".$v->getVar('postfix') )    );
$jieqiTpl->assign('intro',$v->getVar('intro') );

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/vphoto.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>