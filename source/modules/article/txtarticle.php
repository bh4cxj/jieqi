<?php 
/**
 * 文章动态生成txt下载
 *
 * 文章动态生成txt下载
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: txtarticle.php 228 2008-11-27 06:44:31Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
@ini_set('memory_limit', '32M');
require_once('../../global.php');
//jieqi_checklogin();
if(JIEQI_MODULE_VTYPE == '' || JIEQI_MODULE_VTYPE == 'Free') exit; //普及版不支持
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);

//检查下载积分
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$jieqiConfigs['article']['scoretxtfulldown']=intval($jieqiConfigs['article']['scoretxtfulldown']);
if(empty($jieqiConfigs['article']['scoretxtfulldown']) && !empty($jieqiConfigs['article']['scoretxtdown'])) $jieqiConfigs['article']['scoretxtfulldown'] = intval($jieqiConfigs['article']['scoretxtdown']);
if($jieqiConfigs['article']['scoretxtfulldown']>0){
	jieqi_checklogin();
	jieqi_loadlang('down', JIEQI_MODULE_NAME);
	if($_SESSION['jieqiUserScore']<$jieqiConfigs['article']['scoretxtfulldown']) jieqi_printfail(sprintf($jieqiLang['article']['low_txtdown_score'], $jieqiConfigs['article']['scoretxtfulldown']));
}

include_once($jieqiModules['article']['path'].'/class/package.php');
$package=new JieqiPackage($_REQUEST['id']);
if($package->loadOPF()){
	header("Content-type: text/plain");
	header("Accept-Ranges: bytes");
	if($_REQUEST['fname']=='id') header("Content-Disposition: attachment; filename=".$_REQUEST['id'].".txt");
	else header("Content-Disposition: attachment; filename=".jieqi_htmlstr($package->metas['dc:Title']).".txt");
	$br="\r\n";
	if(!empty($jieqiConfigs['article']['txtarticlehead'])) echo $jieqiConfigs['article']['txtarticlehead'].$br.$br;
	echo '《'.$package->metas['dc:Title'].'》'.$br;
	$volume='';
	$txtdir=$package->getDir('txtdir', true, false);
	foreach($package->chapters as $k => $chapter){
		if($chapter['content-type']=='volume'){
			$volume=$chapter['id'];
		}else{
			echo $br.$br.$volume.' '.$chapter['id'].$br.$br;
			//echo jieqi_readfile($txtdir.'/'.$chapter['href']);
			@readfile($txtdir.'/'.$chapter['href']);
			ob_flush();
			flush();
		}
	}
	if(!empty($jieqiConfigs['article']['txtarticlefoot'])) echo $jieqiConfigs['article']['txtarticlefoot'];
	if($jieqiConfigs['article']['scoretxtfulldown']>0){
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scoretxtfulldown'], false, false);
	}
}else{
	jieqi_loadlang('article', JIEQI_MODULE_NAME);
	jieqi_printfail($jieqiLang['article']['article_not_exists']);
}

?>