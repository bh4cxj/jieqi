<?php
define('JIEQI_MODULE_NAME', 'quiz');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');

if(!$jieqiLang['quiz']['list']) jieqi_loadlang('problems', JIEQI_MODULE_NAME);

jieqi_getconfigs('quiz', 'configs');
jieqi_getconfigs('quiz', 'sort');
jieqi_getconfigs('quiz', 'leftlist','jieqiBlocks');

$linkurl=$jieqiConfigs['quiz']['domainname']==""?$jieqiModules['quiz']['url']:$jieqiConfigs['quiz']['domainname'];

include_once(JIEQI_ROOT_PATH.'/header.php');

include_once ($jieqiModules['quiz']['path'].'/include/usertype.php');
$usertype = usertype::getInstance('usertype');
$usertype->isuser();

if($_REQUEST['action']=="add")//添加问题
{
	if($_REQUEST['select']=="" | $_REQUEST['select']=="0")
	{
		jieqi_printfail($jieqiLang['quiz']['typeerror']);
	}

	$score=(int)$_REQUEST['score'];
	if($score==0)
	{
		jieqi_printfail($jieqiLang['quiz']['score']);
	}
	else
	{
		if(!$usertype ->editscore($score))
		{
			jieqi_printfail($jieqiLang['quiz']['scoreless']);
		}
	}

	include_once($jieqiModules['quiz']['path'].'/class/problems.php');
	$problems_handler =& JieqiProblemsHandler::getInstance('JieqiProblemsHandler');
	$problems = $problems_handler -> create();
	$problems -> setVar('quizid',null);
	$problems -> setVar('title',$_REQUEST['title']);
	$problems -> setVar('content',nl2br(htmlspecialchars($_REQUEST['contents'])));
	$problems -> setVar('username',$usertype->get('username'));
	$problems -> setVar('tags',$_REQUEST['tag']);
	$problems -> setVar('score',$score);
	$problems -> setVar('typez',1);
	$problems -> setVar('typeid',$_REQUEST['select']);
	$problems -> setVar('readz',1);
	$problems -> setVar('addtime',time());
	$problems -> setVar('overtime',time()+$jieqiConfigs['quiz']['time']*86400);
	$problems_handler->insert($problems);

	if(trim($_REQUEST['tag'])!="")
	{
		include_once ($jieqiModules['quiz']['path'].'/include/tag.php');
		$tags = tags::getInstance('tags',$_REQUEST['tag']);
		$tags -> tags_str();
		@$tags -> addtags($problems -> getVar('quizid'),$_REQUEST['select']);
	}

	jieqi_getconfigs('quiz','update','Blocks');
	$Blocks['new_problems']=time();//更新首页最新提问的更新时间
	jieqi_setconfigs('update','Blocks',$Blocks,'quiz');

	jieqi_jumppage($linkurl.'/problems.php?id='.$problems -> getVar('quizid'),$jieqiLang['quiz']['sucess'],sprintf($jieqiLang['quiz']['sucesscontent'],$jieqiConfigs['quiz']['time']));
}

if(jieqi_checklogin()){jieqi_printfail($jieqiLang['quiz']['notuser']);}

$jieqiTpl->assign('jieqiSort',$jieqiSort);
$jieqiTpl->assign('linkurl',$linkurl); 
$jieqiTpl->assign('url_checkcode', JIEQI_URL.'/checkcode.php');
$jieqiTpl->assign('jieqiConfigs',$jieqiConfigs);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['quiz']['path'].'/templates/ask.html';

include_once(JIEQI_ROOT_PATH.'/footer.php');
?>