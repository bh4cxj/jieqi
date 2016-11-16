<?php
/**
 * 区块配置文件管理
 *
 * 区块配置文件管理
 * 
 * 调用模板：/templates/admin/blocks.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: blocks.php 177 2008-11-24 08:05:10Z juny $
 */

/*
* 管理配置参数
*/
define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminblock'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
if(empty($_REQUEST['module']) || empty($_REQUEST['fname'])) jieqi_printfail(LANG_ERROR_PARAMETER);

//载入语言
jieqi_loadlang('blocks', JIEQI_MODULE_NAME);

//包含区块参数(定制)
jieqi_getconfigs($_REQUEST['module'], $_REQUEST['fname'], 'jieqiBlocks');
if(empty($jieqiBlocks)) jieqi_printfail($jieqiLang['system']['block_empty_blockfile']);


//取得设置
include_once(JIEQI_ROOT_PATH.'/class/blocks.php');
$blocks_handler =& JieqiBlocksHandler::getInstance('JieqiBlocksHandler');
//处理增加、修改、删除
$updatefile=false;
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
	switch($_REQUEST['action']){
		case 'new':
			$_POST['blockname']=trim($_POST['blockname']);
			$_POST['modname']=trim($_POST['modname']);
			$errtext='';
			if (strlen($_POST['blockname'])==0) $errtext .= $jieqiLang['system']['need_block_name'].'<br />';
			if (strlen($_POST['modname'])==0) $errtext .= $jieqiLang['system']['need_block_modname'].'<br />';
			//if (strlen($_POST['content'])==0) $errtext .= $jieqiLang['system']['need_block_content'].'<br />';
			if(empty($errtext)) {
				$newblock = $blocks_handler->create();
				$newblock->setVar('blockname', $_POST['blockname']);
				$newblock->setVar('modname', $_POST['modname']);
				$newblock->setVar('filename', '');
				$newblock->setVar('classname', 'BlockSystemCustom');
				$newblock->setVar('side', $_POST['side']);
				$newblock->setVar('title', $_POST['title']);
				$newblock->setVar('description', '');
				$newblock->setVar('content', $_POST['content']);
				$newblock->setVar('vars', '');
				$newblock->setVar('template', '');
				$newblock->setVar('cachetime', 0);
				$newblock->setVar('contenttype', JIEQI_CONTENT_HTML);
				$newblock->setVar('weight', $_POST['weight']);
				$newblock->setVar('showstatus', 0);
				$newblock->setVar('custom', 1);
				$newblock->setVar('canedit', 1);
				$newblock->setVar('publish', $_POST['publish']);
				$newblock->setVar('hasvars', 0);
				if(!$blocks_handler->insert($newblock)) jieqi_printfail($jieqiLang['system']['block_add_failure']);
				$blocks_handler->saveContent($newblock->getVar('bid'), $_POST['modname'], JIEQI_CONTENT_HTML, $_POST['content']);
				$updatefile=true;
			}else{
				jieqi_printfail($errtext);
			}
			break;
		case 'update':
			if(empty($_REQUEST['id'])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
			$block=$blocks_handler->get($_REQUEST['id']);
			if(is_object($block)){
				$block->setVar('side', $_POST['side']);
				$block->setVar('title', $_POST['title']);
				$stype=0;
				$block->setVar('weight', $_POST['weight']);
				$block->setVar('publish', $_POST['publish']);
				$_POST['blockname']=trim($_POST['blockname']);
				if(!empty($_POST['blockname'])) $block->setVar('blockname', $_POST['blockname']);
				//自定义内容
				if($block->getVar('custom')==1){
					$modename=trim($_POST['modname']);
					if(!empty($_POST['modname'])) $block->setVar('modname', $_POST['modname']);
					$block->setVar('contenttype', JIEQI_CONTENT_HTML);
				}
				//可编辑内容
				if($block->getVar('canedit')==1){
					$block->setVar('content', $_POST['content']);
				}
				//可设置参数的区块
				if($block->getVar('hasvars') > 0){
					$block->setVar('vars', trim($_POST['blockvars']));
					$block->setVar('template', trim($_POST['blocktemplate']));
					//增加区块
					if($_POST['savetype']==1){
						$block->setNew();
						$block->setVar('showstatus', 0);
						$block->setVar('bid', 0);
					}
				}
				if(!$blocks_handler->insert($block)) jieqi_printfail($jieqiLang['system']['block_edit_failure']);
				//更新自定义区块的缓存
				if($block->getVar('custom')==1) $blocks_handler->saveContent($block->getVar('bid'), $block->getVar('modname'), JIEQI_CONTENT_HTML, $_POST['content']);
				//更新参数设置区块的缓存
				if($_POST['cacheupdate']==1){
					$modname=$block->getVar('modname', 'n');
					if($modname=='system'){
						include(JIEQI_ROOT_PATH.'/blocks/'.$block->getVar('filename', 'n').'.php');
					}else{
						include($jieqiModules[$modname]['path'].'/blocks/'.$block->getVar('filename', 'n').'.php');
					}
					$classname=$block->getVar('classname', 'n');
					include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
					$jieqiTpl =& JieqiTpl::getInstance();
					$vars=array('bid'=>$block->getVar('bid'), 'blockname'=>$block->getVar('blockname'), 'module'=>$block->getVar('modname'), 'filename'=>$block->getVar('filename', 'n'), 'classname'=>$block->getVar('classname', 'n'), 'side'=>$block->getVar('side', 'n'), 'title'=>$block->getVar('title', 'n'), 'vars'=>$block->getVar('vars', 'n'), 'template'=>$block->getVar('template', 'n'), 'contenttype'=>$block->getVar('contenttype', 'n'), 'custom'=>$block->getVar('custom', 'n'), 'publish'=>$block->getVar('publish', 'n'), 'hasvars'=>$block->getVar('hasvars', 'n'));
					$cblock=new $classname($vars);
					$cblock->updateContent();
					unset($jieqiTpl);
					unset($cblock);
					unset($vars);
				}
				$updatefile=true;
			}else{
				jieqi_printfail($jieqiLang['system']['block_not_exists']);
			}
			break;
		case 'delete':
			if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
				$block=$blocks_handler->get($_REQUEST['id']);
				if(is_object($block)){
					if($block->getVar('custom') == 1){
						//用户自定义区块直接删除
						if($blocks_handler->delete($_REQUEST['id'])) $updatefile=true;
					}elseif($block->getVar('hasvars') > 0){
						//有参数的系统区块，至少保留一个
						$criteria=new CriteriaCompo(new Criteria('modname', $block->getVar('modname', 'n')));
						$criteria->add(new Criteria('classname', $block->getVar('classname', 'n')));
						if($blocks_handler->getCount($criteria) > 1){
							if($blocks_handler->delete($_REQUEST['id'])) $updatefile=true;
						}else{
							jieqi_printfail($jieqiLang['system']['block_less_one']);
						}
						unset($criteria);
					}
				}
			}
			break;
	}
}

//显示参数
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
//区块名称

$modules=array();
while($v = $modules_handler->getObject()){
	$modules[$v->getVar('name','n')]=$v->getVar('caption', 'n');
}
$modules['system'] = LANG_MODULE_SYSTEM;

//显示区块列表
$blockary=array();
$jieqinewBlocks=array();
$sortary=array();
$k=0;
$point=0;
if(JIEQI_URL == '') $site_url='http://'.$_SERVER['HTTP_HOST'];
else $site_url=JIEQI_URL;

$jieqiBlocks[]=array('bid'=>66, 'blockname'=>'新闻公告', 'module'=>'article', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>4, 'title'=>'新闻公告', 'vars'=>'', 'template'=>'', 'contenttype'=>1, 'custom'=>1, 'publish'=>3, 'hasvars'=>0);

foreach($jieqiBlocks as $v){
	$blockary[$k]['bid']=$v['bid'];
	$blockary[$k]['blockname']=$v['blockname'];
	$blockary[$k]['modname']=$v['module'];
	$blockary[$k]['side']=$blocks_handler->getSide($v['module']);
	//$blockary[$k]['weight']=$v->getVar('weight');
	$blockary[$k]['publish']=$v['publish'];
	$blockary[$k]['action']='<a href="'.JIEQI_URL.'/admin/blockedit.php?id='.$v['bid'].'" target="_self">'.$jieqiLang['system']['block_action_edit'].'</a>';
	if($v->getVar('custom')==1){
		$blockary[$k]['action'].=' <a href="javascript:if(confirm(\''.$jieqiLang['system']['block_delete_cofirm'].'\')) document.location=\''.JIEQI_URL.'/admin/blocks.php?action=delete&id='.$v->getVar('bid').'\';" target="_self">'.$jieqiLang['system']['block_action_delete'].'</a>';
	}else{
		$blockary[$k]['action'].=' <a href="'.JIEQI_URL.'/admin/blockupdate.php?id='.$v->getVar('bid').'" target="_blank">'.$jieqiLang['system']['block_action_refresh'].'</a>';
		if($v->getVar('hasvars')) $blockary[$k]['action'].=' <a href="javascript:if(confirm(\''.$jieqiLang['system']['block_delete_cofirm'].'\')) document.location=\''.JIEQI_URL.'/admin/blocks.php?action=delete&id='.$v->getVar('bid').'\';" target="_self">'.$jieqiLang['system']['block_action_delete'].'</a>';
	}

	$blockary[$k]['configtext']=htmlspecialchars('$jieqiBlocks[]=array(\'bid\'=>'.$v->getVar('bid').', \'blockname\'=>\''.$v->getVar('blockname').'\', \'module\'=>\''.$v->getVar('modname','n').'\', \'filename\'=>\''.$v->getVar('filename', 'n').'\', \'classname\'=>\''.$v->getVar('classname', 'n').'\', \'side\'=>'.$v->getVar('side', 'n').', \'title\'=>\''.$v->getVar('title', 'n').'\', \'vars\'=>\''.$v->getVar('vars', 'n').'\', \'template\'=>\''.$v->getVar('template', 'n').'\', \'contenttype\'=>'.$v->getVar('contenttype', 'n').', \'custom\'=>'.$v->getVar('custom', 'n').', \'publish\'=>3, \'hasvars\'=>'.$v->getVar('hasvars', 'n').');');
	
	$blockary[$k]['jstext']=htmlspecialchars('<script language="javascript" type="text/javascript" src="'.$site_url.'/blockshow.php?bid='.urlencode($v->getVar('bid')).'&module='.urlencode($v->getVar('modname','n')).'&filename='.urlencode($v->getVar('filename', 'n')).'&classname='.urlencode($v->getVar('classname', 'n')).'&vars='.urlencode($v->getVar('vars', 'n')).'&template='.urlencode($v->getVar('template', 'n')).'&contenttype='.urlencode($v->getVar('contenttype', 'n')).'&custom='.$v->getVar('custom', 'n').'&publish=3&hasvars='.urlencode($v->getVar('hasvars', 'n')).'"></script>');
	
	if($updatefile && $v->getVar('publish')>0){
		$jieqinewBlocks[$point]=array('bid'=>$v->getVar('bid'), 'blockname'=>$v->getVar('blockname'), 'module'=>$v->getVar('modname'), 'filename'=>$v->getVar('filename', 'n'), 'classname'=>$v->getVar('classname', 'n'), 'side'=>$v->getVar('side', 'n'), 'title'=>$v->getVar('title', 'n'), 'vars'=>$v->getVar('vars', 'n'), 'template'=>$v->getVar('template', 'n'), 'contenttype'=>$v->getVar('contenttype', 'n'), 'custom'=>$v->getVar('custom', 'n'), 'publish'=>$v->getVar('publish', 'n'), 'hasvars'=>$v->getVar('hasvars', 'n'));
		$sortary[$point]=$v->getVar('weight','n');
		$point++;
	}
	$k++;
}

$jieqiTpl->assign_by_ref('blocks', $blockary);
//保存的配置文件
if($updatefile){
	asort($sortary);
	$jieqisaveBlocks=array();
	$i=0;
	foreach($sortary as $k=>$v){
		$jieqisaveBlocks[$i]=&$jieqinewBlocks[$k];
		$i++;
	}
	jieqi_setconfigs('blocks', 'jieqiBlocks', $jieqisaveBlocks, 'system');
}
//增加自定义区块
include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
$blocks_form = new JieqiThemeForm($jieqiLang['system']['add_custom_block'], 'blocksnew', JIEQI_URL.'/admin/blocks.php');
$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['table_blocks_blockname'], 'blockname', 30, 50, ''), true);
//模块选择
$modselect=new JieqiFormSelect($jieqiLang['system']['table_blocks_modname'],'modname');
$modselect->addOptionArray($modules);
$blocks_form->addElement($modselect);
//显示位置
$sideary=$blocks_handler->getSideary();
$sideselect=new JieqiFormSelect($jieqiLang['system']['table_blocks_side'],'side');
$sideselect->addOptionArray($sideary);
$blocks_form->addElement($sideselect);
//排列序号
$blocks_form->addElement(new JieqiFormText($jieqiLang['system']['table_blocks_weight'], 'weight', 8, 8, '0'));
//是否显示
$showradio=new JieqiFormRadio($jieqiLang['system']['table_blocks_publish'], 'publish', 3);
$showradio->addOption(0, $jieqiLang['system']['block_show_no']);
$showradio->addOption(1, $jieqiLang['system']['block_show_logout']);
$showradio->addOption(2, $jieqiLang['system']['block_show_login']);
$showradio->addOption(3, $jieqiLang['system']['block_show_both']);
$blocks_form->addElement($showradio);
//区块标题
$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_title'].'(HTML)', 'title', '', 3, 60));
//区块内容
$blocks_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_blocks_content'].'(HTML格式)', 'content', '', 10, 60));

$blocks_form->addElement(new JieqiFormHidden('action', 'new'));
$blocks_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['add_block'], 'submit'));
$jieqiTpl->assign('form_addblock', '<br />'.$blocks_form->render(JIEQI_FORM_MIDDLE).'<br />');
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/blocks.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>