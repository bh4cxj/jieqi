<?php
define('JIEQI_MODULE_NAME', 'quiz');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');

if(!$jieqiLang['quiz']['list']) jieqi_loadlang('problems', JIEQI_MODULE_NAME);

jieqi_getconfigs('quiz', 'configs');
jieqi_getconfigs('quiz', 'problems_list','jieqiBlocks');

$linkurl=$jieqiConfigs['quiz']['domainname']==""?$jieqiModules['quiz']['url']:$jieqiConfigs['quiz']['domainname'];

include_once(JIEQI_ROOT_PATH.'/header.php');

$jieqiTpl->assign('linkurl',$linkurl);
$jieqiTset['jieqi_contents_template'] = '';

include_once(JIEQI_ROOT_PATH.'/footer.php');
?>