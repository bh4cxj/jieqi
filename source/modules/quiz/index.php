<?php
define('JIEQI_MODULE_NAME', 'quiz');
require_once('../../global.php');

jieqi_getconfigs('quiz', 'configs');
jieqi_getconfigs('quiz', 'blocks');
$linkurl=$jieqiConfigs['quiz']['domainname']==""?$jieqiModules['quiz']['url']:$jieqiConfigs['quiz']['domainname'];//ÅÐ¶Ï¶þ¼¶ÓòÃû

include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('linkurl',$linkurl); 
$jieqiTset['jieqi_contents_template'] = '';
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>