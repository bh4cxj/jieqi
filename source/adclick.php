<?php 
/**
 * 点击广告加积分
 *
 * 点击广告后增加积分，如果有url参数则跳转到广告页面，同一个广告重复点击不计分，仅对登录用户有效。
 * 
 * 可设置每天最多有效点击次数，超过后当天不计分。
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: adclick.php 129 2008-11-19 15:03:17Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');

//如果是登陆用户增加积分
if($jieqiUsersGroup != JIEQI_GROUP_GUEST && is_numeric($_REQUEST['id'])){
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
	if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);
	$userset=unserialize($jieqiUsers->getVar('setting','n'));
	$today=date('Y-m-d');
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
	$adclickscore=intval($jieqiConfigs['system']['adclickscore']);  //每次点击积分
	$maxadclick=intval($jieqiConfigs['system']['maxadclick']);  //每天最多点击次数

	if(isset($userset['addate']) && $userset['addate']==$today && (int)$userset['adnum']>=(int)$maxadclick){
		//超过最大计分次数了
	}else{
		//判断是否已经点击过的
		$rightclick=true;
		$_REQUEST['id']=intval($_REQUEST['id']);
		if(empty($_SESSION['jieqiAdClick']) || strlen($_SESSION['jieqiAdClick'])>1024){
			$_SESSION['jieqiAdClick']=$_REQUEST['id'];
		}else{
			if(strpos($_SESSION['jieqiAdClick'], strval($_REQUEST['id'])) === false) $_SESSION['jieqiAdClick'].='|'.$_REQUEST['id'];
			else $rightclick=false;
		}

		//增加点击积分
		if($rightclick){
			if(isset($userset['addate']) && $userset['addate']==$today){
				$userset['adnum']=(int)$userset['adnum']+1;
			}else{
				$userset['addate']=$today;
				$userset['adnum']=1;
			}

			$jieqiUsers->setVar('setting', serialize($userset));
			$jieqiUsers->setVar('score', $jieqiUsers->getVar('score', 'n')+$adclickscore);
			$jieqiUsers->setVar('monthscore', $jieqiUsers->getVar('monthscore', 'n')+$adclickscore);
			$jieqiUsers->setVar('experience', $jieqiUsers->getVar('experience', 'n')+$adclickscore);
			$jieqiUsers->saveToSession();
			$users_handler->insert($jieqiUsers);
		}
	}
}
//如果有url参数，跳转到这个广告url
if(!empty($_REQUEST['url'])) header('Location: '.$_REQUEST['url']);

?>