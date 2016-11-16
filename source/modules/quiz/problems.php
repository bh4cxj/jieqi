<?php
define('JIEQI_MODULE_NAME', 'quiz');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');

if(!$jieqiLang['quiz']['list']) jieqi_loadlang('problems', JIEQI_MODULE_NAME);

jieqi_getconfigs('quiz', 'configs');

jieqi_getconfigs('quiz', 'leftlist','jieqiBlocks');

$linkurl=$jieqiConfigs['quiz']['domainname']==""?$jieqiModules['quiz']['url']:$jieqiConfigs['quiz']['domainname'];

if($_REQUEST['id']=="" | (int)$_REQUEST['id']==0)
{
	jieqi_printfail($jieqiLang['quiz']['problemscontent']);
}

include_once(JIEQI_ROOT_PATH.'/header.php');

include_once ($jieqiModules['quiz']['path'].'/include/usertype.php');
$usertype = usertype::getInstance('usertype');

	include_once($jieqiModules['quiz']['path'].'/class/problems.php');
	$problems_handler =& JieqiProblemsHandler::getInstance('JieqiProblemsHandler');
	$problem = $problems_handler -> get((int)$_REQUEST['id']);
	
	if($problem=="")
	{
		jieqi_printfail($jieqiLang['quiz']['problemscontent']);
	}

	$type=$problem->getVar('typez')==1?1:0;//ÎÊÌâ×´Ì¬
	$times=$problem->getVar('overtime')>time()?0:1;
	$jieqiTpl->assign('type',$type);
	$jieqiTpl->assign('times',$times);
	$jieqiTpl->assign('jieqiConfigs',$jieqiConfigs);
	$jieqiTpl->assign('quizid',$problem->getVar('quizid'));
	$jieqiTpl->assign('title',$problem->getVar('title'));
	$jieqiTpl->assign('addtime',date('Y/m/d',$problem->getVar('addtime')));
	$jieqiTpl->assign('overtime',date('Y/m/d',$problem->getVar('overtime')));
	$jieqiTpl->assign('score',$problem->getVar('score'));
	$jieqiTpl->assign('username',$problem->getVar('username'));
	$jieqiTpl->assign('content',editcon($problem->getVar('content')));
	$jieqiTpl->assign('jieqiuser',$usertype->get('username'));
	$jieqiTpl->assign('answerid',$problem->getVar('answer'));
	$jieqiTpl->assign('linkurl',$linkurl);
	$jieqiTpl->assign('url_checkcode', JIEQI_URL.'/checkcode.php');

	function editcon($str)//×ª»»
	{
		$str1=array('[added]','[/added]');
		$str2=array('<div class="added">','</div>');
		return str_replace($str1,$str2,$str);
	}
	jieqi_includedb();
	$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	$sql='select * from '.jieqi_dbprefix('quiz_answer').' where problemid = '.(int)$_REQUEST['id'];
	$row=$query->execute($sql);
	$answer=array();
	$k=0;
	while($res=$query->getObject($row))
	{		
		$answer[$k]=jieqi_funtoarray('jieqi_htmlstr',$res->getVars());
		$answer[$k]['content']['value']=editcon($answer[$k]['content']['value']);
		$k++;
	}
	$jieqiTpl->setCaching(0);
	$jieqiTpl->assign('answer',$answer);
	
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['quiz']['path'].'/templates/problems.html';
	$jieqiTset['jieqi_contents_cacheid']  =  $problem->getVar('quizid');
	
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>