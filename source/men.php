<?php
define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'blocks');//包含区块参数，没有区块的话可以注释掉，1.6版本可以直接在模版里调用区块，下面有具体说明
include_once(JIEQI_ROOT_PATH.'/header.php'); //包含页头处理
$jieqiTset['jieqi_page_template']=JIEQI_ROOT_PATH.'/themes/chaoliu/men.html';//设置该页面的模板文件（index_1可以自定义名称）
$jieqiTpl->assign('jieqi_indexpage',1);//设置首页标志，不是首页请注释本语句，便于模板里面可以判断，给模板其他参数赋值也用这个方法
include_once(JIEQI_ROOT_PATH.'/footer.php');//包含页尾处理
?>