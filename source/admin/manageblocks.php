<?php
/**
 * 区块配置文件管理
 *
 * 可以自定义区块配置文件
 * 
 * 调用模板：/templates/admin/manageblocks.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: blocks.php 177 2008-11-24 08:05:10Z juny $
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
if(empty($_REQUEST['action'])) $_REQUEST['action']='listconfig';

if($_REQUEST['action']!='')
{
	switch ($_REQUEST['action'])
	{
		case 'listconfig'://配置文件分类类表
		if(!empty($_REQUEST['modules'])) $sql='select * from '.jieqi_dbprefix('system_blockconfigs').' where modules = \''.jieqi_dbslashes($_REQUEST['modules']).'\' order by id';
		else $sql='select * from '.jieqi_dbprefix('system_blockconfigs').' where 1 order by modules, id';
		$res=$query->execute($sql);
		$blockconfigs=array();
		$k=0;
		while($resz=$query->getObject($res))
		{
			$blockconfigs[$k]['id']=$resz->getVar('id');
			$blockconfigs[$k]['modules']=$resz->getVar('modules');
			$blockconfigs[$k]['modname']= isset($jieqiModules[$blockconfigs[$k]['modules']]['caption']) ? $jieqiModules[$blockconfigs[$k]['modules']]['caption'] : $blockconfigs[$k]['modules'];
			$blockconfigs[$k]['name']=$resz->getVar('name');
			$blockconfigs[$k]['file']=$resz->getVar('file');
			$k++;
		}
		$jieqiTpl->assign_by_ref('blockconfigs',$blockconfigs);
		$jieqiTpl->assign('modules',$_REQUEST['modules']);
		$jieqiTpl->assign('modname',$jieqiModules[$_REQUEST['modules']]['caption']);
		//$jieqiTset['jieqi_contents_template'] = $jieqiTpl->fetch($jieqiModules['system']['path'].'/templates/admin/blockconfigs.html';
		//include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
		//exit;
		break;
		case 'listblock'://区块列表
		$_REQUEST['configid']=intval($_REQUEST['configid']);
		$sql='select * from '.jieqi_dbprefix('system_blockconfigs').' where id = \''.jieqi_dbslashes($_REQUEST['configid']).'\'';
		$res=$query->execute($sql);
		$modconfig = $query->getObject($res);
		if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
		unset($jieqiBlocks);
		jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
		if(is_array($jieqiBlocks))
		{
			foreach($jieqiBlocks as $i => $value)
			{
				$jieqiBlocks[$i]['modname']=$jieqiModules[$value['module']]['caption'];
				$jieqiBlocks[$i]['side']=intval($jieqiBlocks[$i]['side']);
				$jieqiBlocks[$i]['contenttype']=intval($jieqiBlocks[$i]['contenttype']);
				$jieqiBlocks[$i]['showtype']=intval($jieqiBlocks[$i]['showtype']);
				$jieqiBlocks[$i]['custom']=intval($jieqiBlocks[$i]['custom']);
				$jieqiBlocks[$i]['publish']=intval($jieqiBlocks[$i]['publish']);
				$jieqiBlocks[$i]['hasvars']=intval($jieqiBlocks[$i]['hasvars']);
				$jieqiBlocks[$i]['bid']=intval($jieqiBlocks[$i]['bid']);
				if(empty($jieqiBlocks[$i]['bid'])) $jieqiBlocks[$i]['bid']=$_REQUEST['configid'] * 10000 + $i;
			}
		}
		$jieqiTpl->assign('configid',$modconfig->getVar('id'));
		$jieqiTpl->assign('configname',$modconfig->getVar('name'));
		$jieqiTpl->assign('filename',$modconfig->getVar('file'));
		$jieqiTpl->assign('modules',$modconfig->getVar('modules'));
		$modname = isset($jieqiModules[$modconfig->getVar('modules','n')]['caption']) ? $jieqiModules[$modconfig->getVar('modules','n')]['caption'] : $modconfig->getVar('modules','n');
		$jieqiTpl->assign('modname', jieqi_htmlstr($modname));

		$jieqiTpl->assign('blocks',$jieqiBlocks);

		$jieqiTset['jieqi_contents_template'] = $jieqiModules['system']['path'].'/templates/admin/blocklist.html';
		include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
		exit;
		case 'delete':
			$_REQUEST['configid']=intval($_REQUEST['configid']);
			$sql='select * from '.jieqi_dbprefix('system_blockconfigs').' where id = \''.jieqi_dbslashes($_REQUEST['configid']).'\'';
			$res=$query->execute($sql);
			$modconfig = $query->getObject($res);
			if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
			unset($jieqiBlocks);
			jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
			unset($jieqiBlocks[$_REQUEST['key']]);
			jieqi_setconfigs($modconfig->getVar('file', 'n'),'jieqiBlocks',$jieqiBlocks,$modconfig->getVar('modules', 'n'));
			jieqi_jumppage('?action=listblock&configid='.$_REQUEST['configid'],LANG_DO_SUCCESS,$jieqiLang['system']['block_delete_success']);
			exit;
		case 'edit':
			$_REQUEST['configid']=intval($_REQUEST['configid']);
			$sql='select * from '.jieqi_dbprefix('system_blockconfigs').' where id = \''.jieqi_dbslashes($_REQUEST['configid']).'\'';
			$res=$query->execute($sql);
			$modconfig = $query->getObject($res);
			if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
			unset($jieqiBlocks);
			jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
			if(!isset($jieqiBlocks[$_REQUEST['key']])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
			$blockSet=$jieqiBlocks[$_REQUEST['key']];

			//编辑区块
			include_once(JIEQI_ROOT_PATH.'/class/blocks.php');
			$blocks_handler =& JieqiBlocksHandler::getInstance('JieqiBlocksHandler');
			include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
			if($blockSet['custom']==1){
				$block= $blocks_handler->get(intval($blockSet['bid']));
				if(is_object($block)){
					$blockSet['content']=$block->getVar('content','n');
				}
				$blocks_form = new JieqiThemeForm($jieqiLang['system']['edit_custom_block'], 'blockedit', JIEQI_URL.'/admin/manageblocks.php?action=edited&configid='.$_REQUEST['configid'].'&key='.$_REQUEST['key']);
				$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['table_blocks_blockname'], 'blockname', 30, 50, htmlspecialchars($blockSet['blockname'], ENT_QUOTES)), true);
				//模块选择
				$modselect=new JieqiFormSelect($jieqiLang['system']['table_blocks_modname'],'modname', htmlspecialchars($blockSet['module'], ENT_QUOTES));
				foreach($jieqiModules as $k=>$v){
					$modselect->addOption($k, htmlspecialchars($v['caption'], ENT_QUOTES));
				}
				$blocks_form->addElement($modselect);
			}else{
				$criteria=new CriteriaCompo(new Criteria('modname', $blockSet['module']));
				$criteria->add(new Criteria('classname', $blockSet['classname']));
				$blocks_handler->queryObjects($criteria);
				$block= $blocks_handler->getObject();
				if(is_object($block)){
					$blockSet['description']=$block->getVar('description','n');
				}
				$blocks_form = new JieqiThemeForm($jieqiLang['system']['edit_system_block'], 'blockedit', JIEQI_URL.'/admin/manageblocks.php?action=edited&configid='.$_REQUEST['configid'].'&key='.$_REQUEST['key']);
				$blockfile=$blockSet['filename'].'.php';
				$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_filename'], jieqi_htmlstr($blockfile)));
				if(isset($jieqiModules[$blockSet['module']])) $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_modname'], jieqi_htmlstr($jieqiModules[$blockSet['module']]['caption'])));
				else $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_modname'], LANG_UNKNOWN));
				$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['table_blocks_blockname'], 'blockname', 30, 50, htmlspecialchars($blockSet['blockname'], ENT_QUOTES)), true);
			}
			//显示位置
			$sideary=$blocks_handler->getSideary();
			$sideselect=new JieqiFormSelect($jieqiLang['system']['table_blocks_side'],'side',  htmlspecialchars($blockSet['side'], ENT_QUOTES));
			$sideselect->addOptionArray($sideary);
			$blocks_form->addElement($sideselect);
			//排列序号
			/*
			$eleweight=new JieqiFormText($jieqiLang['system']['table_blocks_weight'], 'weight', 8, 8,  htmlspecialchars($_REQUEST['key'], ENT_QUOTES));
			$eleweight->setDescription($jieqiLang['system']['note_block_weight']);
			$blocks_form->addElement($eleweight);
			*/
			//是否显示
			$showradio=new JieqiFormRadio($jieqiLang['system']['table_blocks_publish'], 'publish',  htmlspecialchars($blockSet['publish'], ENT_QUOTES));
			$showradio->addOption(0, $jieqiLang['system']['block_show_no']);
			$showradio->addOption(1, $jieqiLang['system']['block_show_logout']);
			$showradio->addOption(2, $jieqiLang['system']['block_show_login']);
			$showradio->addOption(3, $jieqiLang['system']['block_show_both']);
			$blocks_form->addElement($showradio);
			//区块标题
			$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_title'], 'title',  htmlspecialchars($blockSet['title'], ENT_QUOTES), 3, 60));
			//内容类型
			if($blockSet['custom']==1){
				$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], 'HTML'));
			}else{
				$tmpary=$blocks_handler->getContentary();
				if(isset($tmpary[$blockSet['contenttype']]))	$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], $tmpary[$blockSet['contenttype']]));
				else $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], LANG_UNKNOWN));
			}
			//区块内容
			if($blockSet['custom']==1){
				$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_content'], 'content', htmlspecialchars($blockSet['content'], ENT_QUOTES), 10, 60));
			}else{
				//区块描述
				$blockdesc=trim($blockSet['description']);
				if(!empty($blockdesc)) $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_description'], $blockdesc));
			}

			//参数设置
			if($blockSet['hasvars']){
				$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_blockvars'], 'blockvars', htmlspecialchars($blockSet['vars'], ENT_QUOTES), 3, 60));
				$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['block_template_file'], 'blocktemplate', 30, 50, htmlspecialchars($blockSet['template'], ENT_QUOTES)), true);
				//$saveradio=new JieqiFormRadio($jieqiLang['system']['block_save_type'], 'savetype', 0);
				//$saveradio->addOptionArray(array('0'=>$jieqiLang['system']['block_save_self'], '1'=>$jieqiLang['system']['block_save_another']));
				//$blocks_form->addElement($saveradio);
				if($blockSet['hasvars']==2) $blocks_form->addElement(new JieqiFormHidden('cacheupdate', '1'));
			}

			//$blocks_form->addElement(new JieqiFormHidden('action', 'update'));
			//$blocks_form->addElement(new JieqiFormHidden('id', $blockSet['bid']));
			$blocks_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['save_block'], 'submit'));

			$jieqiTpl->assign('jieqi_contents', '<br />'.$blocks_form->render(JIEQI_FORM_MIDDLE).'<br />');
			include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
			exit;
		case 'edited'://处理修改
		$_REQUEST['configid']=intval($_REQUEST['configid']);
		$sql='select * from '.jieqi_dbprefix('system_blockconfigs').' where id = \''.jieqi_dbslashes($_REQUEST['configid']).'\'';
		$res=$query->execute($sql);
		$modconfig = $query->getObject($res);
		if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
		unset($jieqiBlocks);
		jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
		if(!isset($jieqiBlocks[$_REQUEST['key']])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
		$blockSet=$jieqiBlocks[$_REQUEST['key']];
		$blockSet['blockname']=$_POST['blockname'];
		$blockSet['side']=$_POST['side'];
		$blockSet['publish']=$_POST['publish'];
		$blockSet['title']=$_POST['title'];
		if($blockSet['hasvars']){
			$blockSet['vars']=$_POST['blockvars'];
			$blockSet['template']=$_POST['blocktemplate'];
		}
		if($blockSet['custom']==1 && isset($_POST['content'])){
			$sql='UPDATE '.jieqi_dbprefix('system_blocks')." SET content='".jieqi_dbslashes($_POST['content'])."' WHERE bid=".intval($blockSet['bid']);
			$query->execute($sql);
			include_once(JIEQI_ROOT_PATH.'/class/blocks.php');
			$blocks_handler =& JieqiBlocksHandler::getInstance('JieqiBlocksHandler');
			$blocks_handler->saveContent($blockSet['bid'], $blockSet['module'], JIEQI_CONTENT_HTML, $_POST['content']);
		}

		$jieqiBlocks[$_REQUEST['key']]=$blockSet;
		//更新配置文件
		jieqi_setconfigs($modconfig->getVar('file', 'n'),'jieqiBlocks',$jieqiBlocks,$modconfig->getVar('modules', 'n'));

		//更新参数设置区块的缓存
		if($_POST['cacheupdate']==1){
			$modname=$blockSet['module'];
			if($modname=='system'){
				include(JIEQI_ROOT_PATH.'/blocks/'.$blockSet['filename'].'.php');
			}else{
				include($jieqiModules[$modname]['path'].'/blocks/'.$blockSet['filename'].'.php');
			}
			$classname=$blockSet['classname'];
			include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
			$jieqiTpl =& JieqiTpl::getInstance();
			$cblock=new $classname($blockSet);
			$cblock->updateContent();
			unset($jieqiTpl);
			unset($cblock);
			unset($vars);
		}

		jieqi_jumppage('?action=listblock&configid='.$_REQUEST['configid'],LANG_DO_SUCCESS,$jieqiLang['system']['block_update_success']);
		exit;
		case 'editlist':
			$_REQUEST['configid']=intval($_REQUEST['configid']);
			$sql='select * from '.jieqi_dbprefix('system_blockconfigs').' where id = \''.jieqi_dbslashes($_REQUEST['configid']).'\'';
			$res=$query->execute($sql);
			$modconfig = $query->getObject($res);
			if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
			unset($jieqiBlocks);
			jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
			asort($_REQUEST['key']);
			$newBlocks=array();
			foreach($_REQUEST['key'] as $a => $value)
			{
				$newBlocks[intval($value)]=$jieqiBlocks[$a];
			}
			foreach($newBlocks as $i => $value)
			{
				$newBlocks[$i]['side']=intval($newBlocks[$i]['side']);
				$newBlocks[$i]['contenttype']=intval($newBlocks[$i]['contenttype']);
				$newBlocks[$i]['showtype']=intval($newBlocks[$i]['showtype']);
				$newBlocks[$i]['custom']=intval($newBlocks[$i]['custom']);
				$newBlocks[$i]['publish']=intval($newBlocks[$i]['publish']);
				$newBlocks[$i]['hasvars']=intval($newBlocks[$i]['hasvars']);
				$newBlocks[$i]['bid']=intval($newBlocks[$i]['bid']);
				if(empty($newBlocks[$i]['bid'])) $newBlocks[$i]['bid']=$_REQUEST['configid'] * 1000 + $i;
			}

			jieqi_setconfigs($modconfig->getVar('file', 'n'),'jieqiBlocks',$newBlocks,$modconfig->getVar('modules', 'n'));
			jieqi_jumppage('?action=listblock&configid='.$_REQUEST['configid'],LANG_DO_SUCCESS,$jieqiLang['system']['block_update_success']);
			exit;
		case 'addbconfig';
		if(!$_REQUEST['dosubmit'])
		{
			$sql='select bid,blockname,modname,filename,side,template from '.jieqi_dbprefix('system_blocks');
			$res=$query->execute($sql);
			$modules=array();
			$k=0;
			while($resz=$query->getObject($res))
			{
				$modules[]=$resz->getVars();
			}
			jieqi_getconfigs('system', 'adminmenu');
			//print_r($jieqiModules);
			//print_r($modules);
			$jieqiTpl->assign('jieqiModules',$jieqiModules);
			$jieqiTpl->assign('name',$_REQUEST['name']);
			$jieqiTpl->assign('mod',$_REQUEST['modules']);
			$jieqiTpl->assign('modules',$modules);
			$jieqiTset['jieqi_contents_template'] = $jieqiModules['system']['path'].'/templates/admin/addmodules.html';
			include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
			exit;
		}
		$sql='select * from '.jieqi_dbprefix('system_blockconfigs').' where id = \''.jieqi_dbslashes($_REQUEST['modules']).'\'';
		$res=$query->getObject($query->execute($sql));
		$res=$res->getvars($res);
		jieqi_getconfigs($res['modules']['value'], $res['file']['value'],'jieqiBlocks');
		$sql='select bid,blockname,modname,filename,classname,side,title,vars,template,contenttype,custom,publish,hasvars from '.jieqi_dbprefix('system_blocks').' where bid = '.jieqi_dbslashes($_REQUEST['id']);
		$resz=$query->getObject($query->execute($sql));
		$resz=$resz->getvars($res);
		foreach ($resz as $i => $value)
		{
			$resz[$i]=$value['value'];
		}
		$resz['module']=$resz['modname'];
		unset($resz['modname']);
		if($jieqiBlocks=="")
		{
			$jieqiBlocks[]=$resz;
		}
		else
		{
			array_push($jieqiBlocks,$resz);
		}
		jieqi_setconfigs($res['file']['value'],'jieqiBlocks',$jieqiBlocks,$res['modules']['value']);
		jieqi_jumppage('?action=listconfig&modules='.$res['modules']['value'],LANG_DO_SUCCESS,$jieqiLang['system']['block_add_success']);
		exit;
		case 'addblockdo':
			if(!$dosubmit)
			{
				$jieqiTpl->assign('uname',$_REQUEST['name']);
				$jieqiTpl->assign('module',$_REQUEST['module']);
				$jieqiTset['jieqi_contents_template'] = $jieqiModules['system']['path'].'/templates/admin/addblock.html';
				include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
				exit;
			}
			$root=$_REQUEST['moduleas']=='system'?JIEQI_ROOT_PATH.'/configs':JIEQI_ROOT_PATH.'/configs/'.$_REQUEST['modules'];
			$root.='/'.$_REQUEST['file'].'.php';
			if(file_exists($root))
			{
				jieqi_printfail($jieqiLang['system']['block_newconfig_failure']);
			}
			$date='';
			if(!jieqi_writefile($root,$date))
			{
				$sql='insert into '.jieqi_dbprefix('system_blockconfigs').'(modules,name,file) values (\''.jieqi_dbslashes($_REQUEST['modules']).'\',\''.jieqi_dbslashes($_REQUEST['title']).'\',\''.jieqi_dbslashes($_REQUEST['file']).'\')';
				$query->execute($sql);
				jieqi_jumppage('?action=listconfig&modules='.$_REQUEST['modules'],LANG_DO_SUCCESS,$jieqiLang['system']['block_newconfig_success']);
			}
			else
			{
				jieqi_jumppage('?action=listconfig&modules='.$_REQUEST['modules'],LANG_DO_SUCCESS,$jieqiLang['system']['block_newconfig_failure']);
			}
			exit;
	}
}
$jieqiTset['jieqi_contents_template'] = $jieqiModules['system']['path'].'/templates/admin/manageblocks.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>