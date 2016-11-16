<?php
/**
 * 空间基本设置
 *
 * 空间基本设置
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/set.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
define('JIEQI_MODULE_NAME','space');
require_once('../../global.php');
jieqi_checklogin();
include_once($jieqiModules['space']['path'].'/spaceheader.php');
jieqi_loadlang('set',JIEQI_MODULE_NAME);
if(!$space_hoster){
	jieqi_printfail($jieqiLang['space']['not_your_space']);
}
jieqi_getconfigs('space', 'setblocks','jieqiBlocks');

switch ($_REQUEST['action']) {
	case 'doset':
		include_once($jieqiModules['space']['path'].'/class/space.php');
		$space_handler =& JieqiSpaceHandler::getInstance('JieqiSpaceHandler');
		$space = $space_handler->get($uid);
		$space->setVar('title', $_REQUEST['title']  );
		$space->setVar('brief', $_REQUEST['brief']  );
		$space_handler->insert($space);
		//make space config
		space_make_config();
		//refresh for getting the lastest
		jieqi_jumppage($jieqiModules['space']['url'].'/set.php?uid='.$uid,$jieqiLang['space']['set_finish'],$jieqiLang['space']['basic_set_finish']);
		break;
	default:
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['space']['path'].'/templates/set.html';
		$jieqiTpl->assign('set_spacebrief', $brief );
		$jieqiTpl->assign('set_spacetitle',$title);		
		$jieqiTpl->assign('action_url',$jieqiModules['space']['url'].'/set.php?uid='.$uid );
		$jieqiTpl->assign('themes',$themes);
		break;
}

include_once($jieqiModules['space']['path'].'/spacefooter.php');
?>
