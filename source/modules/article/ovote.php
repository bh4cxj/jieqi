<?php 
/**
 * 投原创文章月票记录
 *
 * 投原创文章月票记录
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ovote.php 322 2009-01-13 11:28:29Z juny $
 */

/**
原创月票

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'monthovotes', '每月允许原创推荐次数', '0', '', 0, 3, '', 30650, '积分设置');

INSERT INTO `jieqi_system_configs` (`cid`, `modname`, `cname`, `ctitle`, `cvalue`, `cdescription`, `cdefine`, `ctype`, `options`, `catorder`, `catname`) VALUES (0, 'article', 'scoremonthovote', '每次原创推荐增加积分', '0', '', 0, 3, '', 30660, '积分设置');

INSERT INTO `jieqi_system_right` (`rid`, `modname`, `rname`, `rtitle`, `rdescription`, `rhonors`) VALUES (0, 'article', 'monthovotes', '每月允许原创推荐次数', '', '');

ALTER TABLE `jieqi_article_article` ADD `lastovote` INT( 11 ) NOT NULL DEFAULT '0' AFTER `allvote` ,
ADD `monthovote` INT( 11 ) NOT NULL DEFAULT '0' AFTER `lastovote` ,
ADD `oldovote` INT( 11 ) NOT NULL DEFAULT '0' AFTER `monthovote` ;

 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_checklogin();
jieqi_loadlang('ovote', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);
$userset=unserialize($jieqiUsers->getVar('setting','n'));
$today=date('Y-m');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
jieqi_getconfigs('system', 'honors');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'right');
$maxvote=$jieqiConfigs['article']['monthovotes'];
$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
if($honorid && isset($jieqiRight['article']['monthovotes']['honors'][$honorid]) && is_numeric($jieqiRight['article']['monthovotes']['honors'][$honorid])) $maxvote = intval($jieqiRight['article']['monthovotes']['honors'][$honorid]);

//超过推荐次数扣积分
if(isset($jieqiConfigs['article']['ovotescore'])) $jieqiConfigs['article']['ovotescore']=intval($jieqiConfigs['article']['ovotescore']);
else $jieqiConfigs['article']['ovotescore']=0;
$voteneedscore=false;

if(isset($userset['ovotedate']) && $userset['ovotedate']==$today && (int)$userset['ovotenum']>=(int)$maxvote){
	if($jieqiConfigs['article']['ovotescore']>0){
		if($_REQUEST['confirm']==1){
			if($_SESSION['jieqiUserScore']<$jieqiConfigs['article']['ovotescore']) jieqi_printfail($jieqiLang['article']['low_ovote_score']);
			else $voteneedscore=true;
		}else{
			jieqi_msgwin(LANG_NOTICE, sprintf($jieqiLang['article']['ovote_need_score'], $maxvote, $jieqiConfigs['article']['ovotescore'], jieqi_addurlvars(array('confirm'=>1))));
		}
	}else{
		jieqi_printfail(sprintf($jieqiLang['article']['ovote_times_limit'], $maxvote));
	}
}

include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['id']);
if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);
if(is_numeric($jieqiConfigs['article']['voteminsize']) && intval($jieqiConfigs['article']['voteminsize']) > $article->getVar('size')) jieqi_printfail(sprintf($jieqiLang['article']['ovote_min_articlesize'], $jieqiConfigs['article']['voteminsize']));

//增加投票值(每日、每周、每月、合计)
$lastdate=date('Y-m-d', $article->getVar('lastovote', 'n'));
$nowdate=date('Y-m-d',  JIEQI_NOW_TIME);

$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['id']));
if(substr($nowdate,0,7)==substr($lastdate,0,7)){
	$monthovote=$article->getVar('monthovote', 'n')+$addnum;
	$article_handler->updatefields(array('lastovote'=>JIEQI_NOW_TIME, 'monthovote'=>$monthvote), $criteria);
}else{
	$oldovote=$article->getVar('monthovote', 'n');
	$monthovote=$addnum;
	$article_handler->updatefields(array('lastovote'=>JIEQI_NOW_TIME, 'monthovote'=>$monthvote, 'oldovote'=>$oldovote), $criteria);
}

//记录已经投票标志
if(isset($userset['ovotedate']) && $userset['ovotedate']==$today){
	$userset['ovotenum']=(int)$userset['ovotenum']+1;
}else{
	$userset['ovotedate']=$today;
	$userset['ovotenum']=1;
}
$jieqiUsers->setVar('setting', serialize($userset));
$jieqiUsers->saveToSession();
$users_handler->insert($jieqiUsers);

if($voteneedscore){
	//扣积分
	$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['ovotescore'], false, false);
}elseif($jieqiConfigs['article']['scoreovote']>0){
	//加积分
	$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scoreovote'], true);
}
jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['ovote_success'], $maxvote, $userset['ovotenum']));


?>