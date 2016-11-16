<?php
/**
 * 区块配置文件管理
 *
 * 可以自定义区块配置文件
 * 
 * 调用模板：/templates/admin/blocksaction.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: blocksaction.php 177 2008-11-24 08:05:10Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminblock'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');

jieqi_loadlang('blocks', JIEQI_MODULE_NAME);

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
if(empty($_REQUEST['action'])) $_REQUEST['action']='add';
if($_REQUEST['action']!='')
{
	switch ($_REQUEST['action'])
	{
	    case 'edit':
			$jieqiBlock = array();//清空原始配置参数
			$blockstr = $_REQUEST['vars'];
			$jieqiBlock = unserialize($blockstr);
			$blockSet=$jieqiBlock;
			$blockSet['side'] = $_REQUEST['side'];
			//jieqi_getconfigs($_REQUEST['modules'], $_REQUEST['file'],'jieqiBlocks');
			//if(!isset($jieqiBlocks[$_REQUEST['key']])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
			//$blockSet=$jieqiBlocks[$_REQUEST['key']];
			//$blockSet['filename']=$blockSet['filename'].'.php';
			
			$blockSet['modnamer']=jieqi_htmlstr($jieqiModules[$blockSet['module']]['caption']);
			/*可删掉*/
			$jieqiTpl->assign('modules',$_REQUEST['modules']);
			$jieqiTpl->assign('file',$_REQUEST['file']);
			$jieqiTpl->assign('name',$_REQUEST['name']);
			/**/
			$blockSet['var'] = htmlspecialchars($blockstr);
			$jieqiTpl->assign('blockSet',$blockSet);
			$jieqiTpl->assign('key',$_REQUEST['key']);//保留
			$jieqiTpl->setCaching(0);
			$jieqiTset['jieqi_contents_template'] = $jieqiModules['system']['path'].'/templates/admin/blocksaction_edit.html';
			include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
			exit;	
		case 'editajax'://处理AJAX修改
		    $jieqiBlock = array();//清空原始配置参数
			$blockstr = $_REQUEST['varcontent'];
			$jieqiBlock = unserialize($blockstr);
			$blockSet=$jieqiBlock;
			$blockSet['blockname'] = $_REQUEST['blockname'];
			$blockSet['side'] = $_REQUEST['side'];
			$blockSet['publish'] = $_REQUEST['publish'];
			$blockSet['title'] = $_REQUEST['title'];
			if($blockSet['hasvars']){
			  $blockSet['vars'] = $_REQUEST['vars'];
			  $blockSet['template'] = $_REQUEST['template'];
			}
			echo iconv("gb2312","utf-8",serialize($blockSet));
		    exit;
		case 'edited'://处理修改
			jieqi_getconfigs($_REQUEST['modules'], $_REQUEST['file'],'jieqiBlocks');exit;
			if(!isset($jieqiBlocks[$_REQUEST['key']])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
			$jieqiBlocks[$_REQUEST['key']]['blockname'] = $_REQUEST['blockname'];
			if($_REQUEST['side']) $jieqiBlocks[$_REQUEST['key']]['side'] = intval($_REQUEST['side']);
			$jieqiBlocks[$_REQUEST['key']]['publish'] = intval($_REQUEST['publish']);
			$jieqiBlocks[$_REQUEST['key']]['title'] = $_REQUEST['title'];
			if($jieqiBlocks[$_REQUEST['key']]['hasvars']>0){
			$jieqiBlocks[$_REQUEST['key']]['vars'] = $_REQUEST['vars'];
			$jieqiBlocks[$_REQUEST['key']]['template'] = $_REQUEST['template'];
			}
			jieqi_setconfigs($_REQUEST['file'],'jieqiBlocks',$jieqiBlocks,$_REQUEST['modules']);
			jieqi_jumppage('blockconfig.php?modules='.$_REQUEST['modules'].'&file='.$_REQUEST['file'].'&name='.$_REQUEST['name'],LANG_DO_SUCCESS,$jieqiLang['system']['block_update_success']);
		exit;	
		case 'add':
			$jieqiTpl->assign('modulename',$jieqiModules[$_REQUEST['modules']]['caption']);
			$mod = $_REQUEST['modules'];
			include_once(JIEQI_ROOT_PATH.'/class/blocks.php');
			$blocks_handler =& JieqiBlocksHandler::getInstance('JieqiBlocksHandler');
			$criteria=new CriteriaCompo();
			if(isset($mod) && !empty($mod)) $criteria->add(new Criteria('modname',$mod,'='));
			$criteria->setSort('modname ASC, weight');
			$criteria->setOrder('ASC');
			$blocks_handler->queryObjects($criteria);
			$blockary=array();
			$k=0;
			if(JIEQI_URL == '') $site_url='http://'.$_SERVER['HTTP_HOST'];
			else $site_url=JIEQI_URL;
			while($v = $blocks_handler->getObject()){
				$blockary[$k]['bid']=$v->getVar('bid');
				$blockary[$k]['blockname']=$v->getVar('blockname');
				$blockary[$k]['modnamer']=$jieqiModules[$v->getVar('modname', 'n')]['caption'];
				$blockary[$k]['modname']=$v->getVar('modname', 'n');
				$blockary[$k]['filename']=$v->getVar('filename', 'n');
				
				$blockary[$k]['classname']=$v->getVar('classname', 'n');
				$blockary[$k]['vars']=$v->getVar('vars', 'n');
				$blockary[$k]['template']=$v->getVar('template', 'n');
				$blockary[$k]['contenttype']=$v->getVar('contenttype', 'n');
				$blockary[$k]['custom']=$v->getVar('custom', 'n');
				$blockary[$k]['publish']=$v->getVar('publish', 'n');
				$blockary[$k]['hasvars']=$v->getVar('hasvars', 'n');
				
				$blockary[$k]['side']=$v->getVar('side', 'n');
				$blockary[$k]['title']=$v->getVar('title', 'n');
				$blockary[$k]['weight']=$v->getVar('weight');
				$blockary[$k]['publisher']=$blocks_handler->getPublish($v->getVar('publish', 'n'));
				
				$blockary[$k]['var']=htmlspecialchars(serialize(array('bid'=>$v->getVar('bid'), 'blockname'=>$v->getVar('blockname'), 'module'=>$v->getVar('modname'), 'filename'=>$v->getVar('filename', 'n'), 'classname'=>$v->getVar('classname', 'n'), 'side'=>$v->getVar('side', 'n'), 'title'=>$v->getVar('title', 'n'), 'vars'=>$v->getVar('vars', 'n'), 'template'=>$v->getVar('template', 'n'), 'contenttype'=>$v->getVar('contenttype', 'n'), 'custom'=>$v->getVar('custom', 'n'), 'publish'=>$v->getVar('publish', 'n'), 'hasvars'=>$v->getVar('hasvars', 'n'))));
				$k++;
			}
			
			$jieqiTpl->assign_by_ref('blocks', $blockary);
			
			$jieqiTset['jieqi_contents_template'] = $jieqiModules['system']['path'].'/templates/admin/blocksaction_add.html';
			include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
			exit;
		case 'addModule':
		    //提交新的配置
		    if($_REQUEST['modules'] && $_REQUEST['name'] && $_REQUEST['file']){
				$dir = JIEQI_ROOT_PATH.'/configs';
				if(!file_exists($dir)) @mkdir($dir, 0777);
				@chmod($dir, 0777);
				if($_REQUEST['modules'] != 'system'){
					$dir.='/'.$_REQUEST['modules'];
					if(!file_exists($dir)) @mkdir($dir, 0777);
					@chmod($dir, 0777);
				}
				$dir.='/'.$_REQUEST['name'].'.php';
				if(file_exists($dir)) jieqi_printfail($jieqiLang['system']['blockconfig_is_exists']);
				
				//入库
				include_once(JIEQI_ROOT_PATH.'/class/blockconfigs.php');
				$blockconfigs_handler =& JieqiBlockconfigsHandler::getInstance('JieqiBlockconfigsHandler');
				$blockconfigs = $blockconfigs_handler->create();
				$blockconfigs->setVar('modules', $_REQUEST['modules']);
				$blockconfigs->setVar('name', $_REQUEST['name']);
				$blockconfigs->setVar('file', $_REQUEST['file']);
				if(!$blockconfigs_handler->insert($blockconfigs)) jieqi_printfail($jieqiLang['system']['blockconfig_add_failure']);
				$array = array();
				$varstring="<?php\n".jieqi_extractvars('jieqiBlocks', $array)."\n?>";
		        jieqi_writefile($dir, $varstring);
				jieqi_jumppage('manageblocks.php',LANG_DO_SUCCESS,$jieqiLang['system']['blockconfig_add_success']);
			}
			$jieqiTset['jieqi_contents_template'] = $jieqiModules['system']['path'].'/templates/admin/addmodules.html';
			include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
		exit;
	}
}
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>