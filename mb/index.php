<?php
define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');

jieqi_getconfigs(JIEQI_MODULE_NAME, 'blocks', 'jieqiBlocks');

include_once(JIEQI_ROOT_PATH.'/header.php');

$jieqiTpl->assign('jieqi_indexpage',1);  //设置首页标志，便于模板里面可以判断
$jieqiTset['jieqi_contents_template'] = '';  //内容位置不赋值，全部用区块
$jieqiTset['jieqi_page_template']=JIEQI_ROOT_PATH.'/52mb/52mb.html';//设置该页面的模板文件（index_1可以自定义名称）
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>