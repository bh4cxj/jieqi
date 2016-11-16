<?php
define('JIEQI_MODULE_NAME', 'quiz');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');

if(!$jieqiLang['quiz']['list']) jieqi_loadlang('problems', JIEQI_MODULE_NAME);

jieqi_getconfigs('quiz', 'configs');

$linkurl=$jieqiConfigs['quiz']['domainname']==""?$jieqiModules['quiz']['url']:$jieqiConfigs['quiz']['domainname'];//二级域名

if($jieqiConfigs['quiz']['usekey']=='1' & $_REQUEST['action']=='')//是否启用验证码
{
	include_once ($jieqiModules['quiz']['path'].'/include/checkcodez.php');
	$checkcode = checkcodez::getInstance('checkcodez');
	if($_REQUEST['checkcode']=='' | !$checkcode->istrue($_REQUEST['checkcode']))
	{
		jieqi_jumppage($linkurl.'/problems.php?id='.$_REQUEST['quizid'],$jieqiLang['quiz']['checkcode'],$jieqiLang['quiz']['checkcodecontent']);
	}
}

include_once './include/usertype.php';//判断用户状态
$usertype = usertype::getInstance('usertype');
if(!$usertype -> isuser()){jieqi_jumppage(JIEQI_URL.'/login.php?jumpurl='.urlencode($linkurl),$jieqiLang['quiz']['notusertitle'],$jieqiLang['quiz']['notuser']);}

jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');

if($_REQUEST['action']!='')
{
	switch ($_REQUEST['action'])
	{
		case 'close';//关闭
		$sql="update ".jieqi_dbprefix('quiz_problems')." set typez = 0 where quizid = ".intval($_REQUEST['id'])." and username = '".$usertype -> get('username')."'";
		if($query->execute($sql))
		{
			jieqi_getconfigs('quiz','update','Blocks');
			$Blocks['tagcache']=time();//更新首页最新提问的更新时间
			$Blocks['new_problems']=time();
			$Blocks['close']=time();
			jieqi_setconfigs('update','Blocks',$Blocks,'quiz');
			jieqi_jumppage($linkurl.'/problems.php?id='.$_REQUEST['id'],$jieqiLang['quiz']['answerclose'],$jieqiLang['quiz']['answerclosec']);
		}
		else
		{
			jieqi_printfail($jieqiLang['quiz']['closeerrorc']);
		}
		break;

		case 'add'://补充
		if(is_numeric($_REQUEST['answerid']))
		{
			$sql="update ".jieqi_dbprefix('quiz_answer')." set content = concat( content ,'[added]".jieqi_setslashes(sprintf($jieqiLang['quiz']['addanswer'],$_REQUEST['contents'],date('Y-m-d H:i'),time()))."[/added]') where answerid='".$_REQUEST['answerid']."'";
		}
		else
		{
			$sql="update ".jieqi_dbprefix('quiz_problems')." set content = concat( content ,'[added]".jieqi_setslashes(sprintf($jieqiLang['quiz']['addproblem'],$_REQUEST['contents'],date('Y-m-d H:i'),time()))."[/added]')  where quizid='".$_REQUEST['quizids']."'";
		}
		$query->execute($sql);
		jieqi_jumppage($linkurl.'/problems.php?id='.$_REQUEST['quizids'],$jieqiLang['quiz']['answeradd'],$jieqiLang['quiz']['answeradd'],$jieqiLang['quiz']['answeraddc']);
		break;

		case 'best';//最佳答案
		(int)$_REQUEST['id']==0?jieqi_jumppage($linkurl.'/problems.php?id='.$_REQUEST['id'],$jieqiLang['quiz']['closeerror'],$jieqiLang['quiz']['closeerrorc']):'';
		$sql="update ".jieqi_dbprefix('quiz_problems')." p,".jieqi_dbprefix('system_users')." u,".jieqi_dbprefix('quiz_answer')." a set p.answer = ".intval($_REQUEST['id']).",p.typez=2,u.score=u.score+p.score where p.quizid = ".intval($_REQUEST['quizid'])." and p.username = '".$usertype->get('username')."' and a.answerid=".intval($_REQUEST['id'])." and u.uname=a.username";
		if($query->execute($sql))
		{
			jieqi_getconfigs('quiz','update','Blocks');
			$Blocks['best']=time();//更新首页最新提问的更新时间
			$Blocks['new_problems']=time();
			jieqi_setconfigs('update','Blocks',$Blocks,'quiz');
			jieqi_jumppage($linkurl.'/problems.php?id='.$_REQUEST['quizid'],$jieqiLang['quiz']['answerbest'],$jieqiLang['quiz']['answerbestc']);
		}
		else
		{
			jieqi_printfail($jieqiLang['quiz']['closeerrorc']);
		}
		break;
	}
}

if($_REQUEST['quizid']=="" & (int)$_REQUEST['quizid']==0)
{
	jieqi_printfail($jieqiLang['quiz']['answercontent']);
}

$sql='select answerid from '.jieqi_dbprefix('quiz_answer').' where problemid = '.(int)$_REQUEST['quizid'].' AND username = \''.$usertype->username.'\'';
$row=$query->getrow($query->execute($sql));
if($row!="")
{
	jieqi_printfail($jieqiLang['quiz']['answeroncec']);
}

include_once($jieqiModules['quiz']['path'].'/class/answer.php');
$answer_handler =& JieqiAnswerHandler::getInstance('JieqiAnswerHandler');
$answer = $answer_handler -> create();
$answer -> setVar('answerid',null);
$answer -> setVar('problemid',(int)$_REQUEST['quizid']);
$answer -> setVar('content',$_REQUEST['contents']);
$answer -> setVar('username',$usertype ->username);
$answer -> setVar('addtime',time());
$answer_handler->insert($answer);

jieqi_jumppage($linkurl.'/problems.php?id='.$_REQUEST['quizid'],$jieqiLang['quiz']['answersucess'],$jieqiLang['quiz']['answersucessc']);
?>