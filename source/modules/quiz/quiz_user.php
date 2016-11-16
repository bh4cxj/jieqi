<?php
define('JIEQI_MODULE_NAME', 'quiz');
require_once('../../global.php');

jieqi_getconfigs('quiz', 'configs');

$linkurl=$jieqiConfigs['quiz']['domainname']==""?$jieqiModules['quiz']['url']:$jieqiConfigs['quiz']['domainname'];

if(jieqi_checklogin()){jieqi_printfail($jieqiLang['quiz']['notuser']);}

$jieqiConfigs['quiz']['quizcase'] = 'action';//不使用缓存

switch ($_REQUEST['action'])
{
	case 'myproblems':
	jieqi_getconfigs('quiz', 'myproblems', 'jieqiBlocks');
	break;
	case 'myanswer':
	jieqi_getconfigs('quiz', 'myanswer', 'jieqiBlocks');
	break;
}
include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('linkurl',$linkurl); 
$jieqiTset['jieqi_contents_template'] = ''; //内容位置不赋值，全部用区块
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>