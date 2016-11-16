<?php
/**
 * 编辑区块
 *
 * 编辑区块相关参数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: blockedit.php 174 2008-11-24 07:58:26Z juny $
 */

if(empty($_GET['mod'])) $_GET['mod']='system';
define('JIEQI_MODULE_NAME', $_GET['mod']);
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars($_GET['mod']);
jieqi_checkpower($jieqiPower[$_GET['mod']]['adminblock'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
//载入语言
jieqi_loadlang('blocks', JIEQI_MODULE_NAME);
//取得设置
if(empty($_REQUEST['id'])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
include_once(JIEQI_ROOT_PATH.'/class/blocks.php');
$blocks_handler =& JieqiBlocksHandler::getInstance('JieqiBlocksHandler');
$block= $blocks_handler->get($_REQUEST['id']);
if(!is_object($block)) jieqi_printfail($jieqiLang['system']['block_not_exists']);
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
//区块名称
include_once JIEQI_ROOT_PATH.'/class/modules.php';
$modules_handler =& JieqiModulesHandler::getInstance('JieqiModulesHandler');
$criteria=new CriteriaCompo(new Criteria('publish',1,'='));
$criteria->setSort('weight');
$criteria->setOrder('ASC');
$modules_handler->queryObjects($criteria);
unset($criteria);
$modules=array();
while($v = $modules_handler->getObject()){
	$modules[$v->getVar('name','n')]=$v->getVar('caption', 'n');
}
$modules['system'] = LANG_MODULE_SYSTEM;

//编辑区块
include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
if($block->getVar('custom')==1){
	$blocks_form = new JieqiThemeForm($jieqiLang['system']['edit_custom_block'], 'blockedit', JIEQI_URL.'/admin/blocks.php');
	$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['table_blocks_blockname'], 'blockname', 30, 50, $block->getVar('blockname', 'e')), true);
	//模块选择
	$modselect=new JieqiFormSelect($jieqiLang['system']['table_blocks_modname'],'modname', $block->getVar('modname', 'e'));
	$modselect->addOptionArray($modules);
	$blocks_form->addElement($modselect);
}else{
	$blocks_form = new JieqiThemeForm($jieqiLang['system']['edit_system_block'], 'blockedit', JIEQI_URL.'/admin/blocks.php');
	$blockfile=$block->getVar('filename').'.php';
	$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_filename'], $blockfile));
	if(isset($modules[$block->getVar('modname')]))	$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_modname'], $modules[$block->getVar('modname')]));
	else $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_modname'], LANG_UNKNOWN));
	$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['table_blocks_blockname'], 'blockname', 30, 50, $block->getVar('blockname', 'e')), true);
}
//显示位置
$sideary=$blocks_handler->getSideary();
$sideselect=new JieqiFormSelect($jieqiLang['system']['table_blocks_side'],'side', $block->getVar('side','e'));
$sideselect->addOptionArray($sideary);
$blocks_form->addElement($sideselect);
//排列序号
$eleweight=new JieqiFormText($jieqiLang['system']['table_blocks_weight'], 'weight', 8, 8, $block->getVar('weight', 'e'));
$eleweight->setDescription($jieqiLang['system']['note_block_weight']);
$blocks_form->addElement($eleweight);
//是否显示
$showradio=new JieqiFormRadio($jieqiLang['system']['table_blocks_publish'], 'publish', $block->getVar('publish', 'e'));
$showradio->addOption(0, $jieqiLang['system']['block_show_no']);
$showradio->addOption(1, $jieqiLang['system']['block_show_logout']);
$showradio->addOption(2, $jieqiLang['system']['block_show_login']);
$showradio->addOption(3, $jieqiLang['system']['block_show_both']);
$blocks_form->addElement($showradio);
//区块标题
$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_title'], 'title', $block->getVar('title', 'e'), 3, 60));
//内容类型
if($block->getVar('custom')==1){
    $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], 'HTML'));	
}else{
    $tmpary=$blocks_handler->getContentary();
    if(isset($tmpary[$block->getVar('contenttype')]))	$blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], $tmpary[$block->getVar('contenttype')]));
	else $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_contenttype'], LANG_UNKNOWN));	
}
//区块内容
if($block->getVar('canedit')==1){
    $blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_content'], 'content', $block->getVar('content','e'), 10, 60));
}else{
	//区块描述
	$blockdesc=trim($block->getVar('description', 'n'));
	if(!empty($blockdesc)) $blocks_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_blocks_description'], $blockdesc));
}

//参数设置
if($block->getVar('hasvars')){
	$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_blockvars'], 'blockvars', $block->getVar('vars', 'e'), 3, 60));
	$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['block_template_file'], 'blocktemplate', 30, 50, $block->getVar('template', 'e')));
	$saveradio=new JieqiFormRadio($jieqiLang['system']['block_save_type'], 'savetype', 0);
    $saveradio->addOptionArray(array('0'=>$jieqiLang['system']['block_save_self'], '1'=>$jieqiLang['system']['block_save_another']));
    $blocks_form->addElement($saveradio);
    if($block->getVar('hasvars')==2) $blocks_form->addElement(new JieqiFormHidden('cacheupdate', '1'));
}

$blocks_form->addElement(new JieqiFormHidden('action', 'update'));
$blocks_form->addElement(new JieqiFormHidden('id', $block->getVar('bid')));
$blocks_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['save_block'], 'submit'));

$jieqiTpl->assign('jieqi_contents', '<br />'.$blocks_form->render(JIEQI_FORM_MIDDLE).'<br />');
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>