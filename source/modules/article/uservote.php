<?php 
/**
 * 记录文章推荐值
 *
 * 记录文章推荐值
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: uservote.php 322 2009-01-13 11:28:29Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_checklogin();
jieqi_loadlang('vote', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);
$userset=unserialize($jieqiUsers->getVar('setting','n'));
$today=date('Y-m-d');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
jieqi_getconfigs('system', 'honors');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'right');
$maxvote=$jieqiConfigs['article']['dayvotes'];
$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
if($honorid && isset($jieqiRight['article']['dayvotes']['honors'][$honorid]) && is_numeric($jieqiRight['article']['dayvotes']['honors'][$honorid])) $maxvote = intval($jieqiRight['article']['dayvotes']['honors'][$honorid]);

//超过推荐次数扣积分
if(isset($jieqiConfigs['article']['uservotescore'])) $jieqiConfigs['article']['uservotescore']=intval($jieqiConfigs['article']['uservotescore']);
else $jieqiConfigs['article']['uservotescore']=0;
$voteneedscore=false;

if(isset($userset['polldate']) && $userset['polldate']==$today && (int)$userset['pollnum']>=(int)$maxvote){
	if($jieqiConfigs['article']['uservotescore']>0){
		if($_REQUEST['confirm']==1){
			if($_SESSION['jieqiUserScore']<$jieqiConfigs['article']['uservotescore']) jieqi_printfail($jieqiLang['article']['low_vote_score']);
			else $voteneedscore=true;
		}else{
			jieqi_msgwin(LANG_NOTICE, sprintf($jieqiLang['article']['vote_need_score'], $maxvote, $jieqiConfigs['article']['uservotescore'], jieqi_addurlvars(array('confirm'=>1))));
		}
	}else{
		jieqi_printfail(sprintf($jieqiLang['article']['vote_times_limit'], $maxvote));
	}
}

include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['id']);
if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);
if(is_numeric($jieqiConfigs['article']['voteminsize']) && intval($jieqiConfigs['article']['voteminsize']) > $article->getVar('size')) jieqi_printfail(sprintf($jieqiLang['article']['vote_min_articlesize'], $jieqiConfigs['article']['voteminsize']));
//增加投票值(每日、每周、每月、合计)
$lastdate=date('Y-m-d', $article->getVar('lastvote', 'n'));
$nowdate=date('Y-m-d',  JIEQI_NOW_TIME);
$nowweek=date('w');
$addnum=1;
if($nowdate==$lastdate){
	$dayvote=$article->getVar('dayvote','n')+$addnum;
	$weekvote=$article->getVar('weekvote', 'n')+$addnum;
	$monthvote=$article->getVar('monthvote', 'n')+$addnum;
	$allvote=$article->getVar('allvote', 'n')+$addnum;
}else{
	$dayvote=$addnum;
	if($nowweek==1){
		$weekvote=$addnum;
	}else{
		$weekvote=$article->getVar('weekvote', 'n')+$addnum;
	}
	if(substr($nowdate,0,7)==substr($lastdate,0,7)){
		$monthvote=$article->getVar('monthvote', 'n')+$addnum;
	}else{
		$monthvote=$addnum;
	}
	$allvote=$article->getVar('allvote', 'n')+$addnum;
}
$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['id']));
$article_handler->updatefields(array('lastvote'=>JIEQI_NOW_TIME, 'dayvote'=>$dayvote, 'weekvote'=>$weekvote, 'monthvote'=>$monthvote, 'allvote'=>$allvote), $criteria);
//记录已经投票标志
if(isset($userset['polldate']) && $userset['polldate']==$today){
	$userset['pollnum']=(int)$userset['pollnum']+1;
}else{
	$userset['polldate']=$today;
	$userset['pollnum']=1;
}
$jieqiUsers->setVar('setting', serialize($userset));
$jieqiUsers->saveToSession();
$users_handler->insert($jieqiUsers);

if($voteneedscore){
	//扣积分
	$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['uservotescore'], false, false);
}elseif($jieqiConfigs['article']['scoreuservote']>0){
	//加积分
	$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scoreuservote'], true);
}
jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['vote_success'], $maxvote, $userset['pollnum']));


?>