<?php 
Header("Content-type:text/html;charset=GBK");//加入头，防止乱码

//晕，它chache了很多次，让我烦
header('Expires: Wed, 23 Dec 1970 00:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT' );
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
define(JIEQI_MODULE_NAME,'group');
jieqi_loadlang('selectgname',JIEQI_MODULE_NAME);
$referUrl=$_SERVER['HTTP_REFERER'];//取得上一页面地址
$referHost=$_SERVER['HTTP_HOST'];//取得当前主机名
$referFile=explode("/",$referUrl);//取得上一前面的主机名$referFile[2]

if($referFile[2]!=$referHost)//如果上一页面与本服务端程序不在同一主机则禁止执行
{
	exit;
}

//必用数据句柄初始化
require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');
$criteria = new Criteria(gname,trim($_REQUEST['gname']),'=' );
$group_handler->queryObjects($criteria);
$v = $group_handler->getObject();
if($v ){
	echo '<font color=red>'.$jieqiLang['g']['name_already_been_registed'].'</font>';
}
?>