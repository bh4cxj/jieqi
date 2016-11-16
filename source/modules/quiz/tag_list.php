<?php
define('JIEQI_MODULE_NAME', 'quiz');
require_once('../../global.php');

jieqi_getconfigs('quiz', 'configs');
jieqi_getconfigs('quiz', 'tag_list_more','jieqiBlocks');
$linkurl=$jieqiConfigs['quiz']['domainname']==""?$jieqiModules['quiz']['url']:$jieqiConfigs['quiz']['domainname'];//判断二级域名

include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('linkurl',$linkurl); 
$jieqiTset['jieqi_contents_template'] = '';  //内容位置不赋值，全部用区块
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>