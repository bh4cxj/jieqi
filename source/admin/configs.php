<?php
/**
 * 管理配置参数
 *
 * 配置参数从数据库取出显示，保存修改后更新数据库和配置文件，应用程序里面调用参数是调配置文件，而不是直接取数据库。
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: configs.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
if(empty($_REQUEST['mod'])) $_REQUEST['mod']='system';
elseif(!isset($jieqiModules[$_REQUEST['mod']])) jieqi_printfail(LANG_ERROR_PARAMETER);
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars($_REQUEST['mod']);
jieqi_checkpower($jieqiPower[$_REQUEST['mod']]['adminconfig'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
//载入语言
jieqi_loadlang('configs', JIEQI_MODULE_NAME);
//取得设置
include_once(JIEQI_ROOT_PATH.'/class/configs.php');
$configs_handler =& JieqiConfigsHandler::getInstance('JieqiConfigsHandler');
$criteria=new CriteriaCompo(new Criteria('modname',$_REQUEST['mod'],'='));
if(!isset($_REQUEST['define']) || $_REQUEST['define'] != 1){
	$_REQUEST['define']=0;
}
$criteria->add(new Criteria('cdefine', $_REQUEST['define'], '='));

$criteria->setSort('catorder ASC, cid');
$criteria->setOrder('ASC');
$configs_handler->queryObjects($criteria);
$v = $configs_handler->getObject();
if($v){
	if(isset($_POST['action']) && $_POST['action']=='update'){
		//更新参数
		$cfgarray=array(); //数组变量
		$cfgdefine='';  //定义变量
		
		do{
			$tmpkey=$v->getVar('cname','n');
			switch($v->getVar('ctype')){
				case JIEQI_TYPE_TXTBOX:
				case JIEQI_TYPE_TXTAREA:
				case JIEQI_TYPE_HIDDEN:
					if(!isset($_POST[$tmpkey])) $tmpval=$v->getVar('cvalue');
					else $tmpval=$_POST[$tmpkey];
					break;
				case JIEQI_TYPE_INT:
					if(!isset($_POST[$tmpkey]) || !is_numeric($_POST[$tmpkey])) $tmpval=$v->getVar('cvalue');
					else $tmpval=$_POST[$tmpkey];
					$tmpval = intval($tmpval);
					break;
				case JIEQI_TYPE_NUM:
					if(!isset($_POST[$tmpkey]) || !is_numeric($_POST[$tmpkey])) $tmpval=$v->getVar('cvalue');
					else $tmpval=$_POST[$tmpkey];
					break;
				case JIEQI_TYPE_PASSWORD:
					if(!isset($_POST[$tmpkey]) || strlen($_POST[$tmpkey])==0) $tmpval=$v->getVar('cvalue');
					else $tmpval=$_POST[$tmpkey];
					break;
				case JIEQI_TYPE_SELECT:
				case JIEQI_TYPE_RADIO:
					$selectary=@unserialize($v->getVar('options', 'n'));
					if(!is_array($selectary)) $selectary=array();
					if(!isset($_POST[$tmpkey]) || !isset($selectary[$_POST[$tmpkey]])) $tmpval=$v->getVar('cvalue');
					else $tmpval=$_POST[$tmpkey];
					break;
				case JIEQI_TYPE_MULSELECT:
				case JIEQI_TYPE_CHECKBOX:
					$selectary=@unserialize($v->getVar('options', 'n'));
					if(!is_array($selectary)) $selectary=array();
					$tmparray = is_array($_POST[$tmpkey]) ? $_POST[$tmpkey] : array();
					$tmpval = 0;
					foreach($tmparray as $tmpv){
						if(isset($selectary[$tmpv])) $tmpval = $tmpval | intval($tmpv);
					}
					break;
				default:
					if(!isset($_POST[$tmpkey])) $tmpval=$v->getVar('cvalue');
					else $tmpval=$_POST[$tmpkey];
					break;
					break;
			}
			//参数改变了，需要改变数据库
			if($tmpval != $v->getVar('cvalue','n')){
				$v->setVar('cvalue', $tmpval);
				$configs_handler->insert($v);
			}

			if($v->getVar('cdefine')=='1'){
				$cfgdefine.="@define('".$tmpkey."','".jieqi_setslashes($tmpval, '"')."');\n";
			}else{
				$cfgarray[$_REQUEST['mod']][$tmpkey]=$tmpval;
			}
		}while($v = $configs_handler->getObject());
		if(count($cfgarray)>0) jieqi_setconfigs('configs', 'jieqiConfigs', $cfgarray, $_REQUEST['mod']);
		if(!empty($cfgdefine)){
			$dir=JIEQI_ROOT_PATH.'/configs';
			if(!file_exists($dir)) @mkdir($dir, 0777);
			@chmod($dir, 0777);
			if($_REQUEST['mod'] != 'system'){
				$dir.='/'.$_REQUEST['mod'];
				if(!file_exists($dir)) @mkdir($dir, 0777);
				@chmod($dir, 0777);
			}
			$dir.='/system.php';
			if(file_exists($dir)) @chmod($dir, 0777);
			$cfgdefine="<?php\n".$cfgdefine."\n?>";
			jieqi_writefile($dir, $cfgdefine);
			$publicdata=str_replace('?><?php', '', $cfgdefine.jieqi_readfile(JIEQI_ROOT_PATH.'/lang/lang_system.php').jieqi_readfile(JIEQI_ROOT_PATH.'/configs/groups.php'));
			jieqi_writefile(JIEQI_ROOT_PATH.'/configs/define.php', $publicdata);
		}
		jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['edit_config_success']);
	}else{
		//显示参数
		include_once(JIEQI_ROOT_PATH.'/admin/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$config_form = new JieqiThemeForm($jieqiLang['system']['edit_config'], 'config', JIEQI_URL.'/admin/configs.php');
		$catname='';
		$catorder=0;
		$catlink='';
		do{
			$tmpvar=$v->getVar('catname');
			if($catname != $tmpvar){
				$catname=$tmpvar;
				$catorder++;
				${'catele'.$catorder}=new JieqiFormLabel('', '<a name="catorder'.$catorder.'">'.$catname.'</a>');
				$config_form->addElement(${'catele'.$catorder}, false);
				if(!empty($catlink)) $catlink.='&nbsp;&nbsp;';
				$catlink.='[<a href="#catorder'.$catorder.'">'.$catname.'</a>]';
			}
			switch($v->getVar('ctype')){
				case JIEQI_TYPE_INT:
				case JIEQI_TYPE_NUM:
				$tmpvar=$v->getVar('cname');
				${$tmpvar}=new JieqiFormText($v->getVar('ctitle'), $v->getVar('cname'), 25, 100, $v->getVar('cvalue','e'));
				${$tmpvar}->setDescription($v->getVar('cdescription'));
				$config_form->addElement(${$tmpvar}, false);
				break;
				case JIEQI_TYPE_TXTAREA:
				$tmpvar=$v->getVar('cname');
				${$tmpvar}=new JieqiFormTextArea($v->getVar('ctitle'), $v->getVar('cname'), $v->getVar('cvalue','e'), 5, 50);
				${$tmpvar}->setDescription($v->getVar('cdescription'));
				$config_form->addElement(${$tmpvar}, false);
				break;
				case JIEQI_TYPE_SELECT:
				$tmpvar=$v->getVar('cname');
				${$tmpvar}=new JieqiFormSelect($v->getVar('ctitle'), $v->getVar('cname'), $v->getVar('cvalue','e'));
				${$tmpvar}->setDescription($v->getVar('cdescription'));
				$selectary=@unserialize($v->getVar('options', 'n'));
				if(!is_array($selectary)) $selectary=array();
				foreach($selectary as $val=>$cap){
					${$tmpvar}->addOption($val,$cap);
				}
				$config_form->addElement(${$tmpvar}, false);
				break;
				case JIEQI_TYPE_RADIO:
				$tmpvar=$v->getVar('cname');
				${$tmpvar}=new JieqiFormRadio($v->getVar('ctitle'), $v->getVar('cname'), $v->getVar('cvalue','e'));
				${$tmpvar}->setDescription($v->getVar('cdescription'));
				$selectary=@unserialize($v->getVar('options', 'n'));
				if(!is_array($selectary)) $selectary=array();
				foreach($selectary as $val=>$cap){
					${$tmpvar}->addOption($val,$cap);
				}
				$config_form->addElement(${$tmpvar}, false);
				break;
				case JIEQI_TYPE_CHECKBOX:
				$tmpvar=$v->getVar('cname');
				$tmpvalue = decbin(intval($v->getVar('cvalue','n')));
				$tmplen = strlen($tmpvalue);
				$tmparray = array();
				$tmpnum = 1;
				for($p=$tmplen-1; $p>=0; $p--){
					if($tmpvalue[$p] == '1') $tmparray[] = $tmpnum;
					$tmpnum *= 2;
				}
				${$tmpvar}=new JieqiFormCheckBox($v->getVar('ctitle'), $v->getVar('cname'), $tmparray);
				${$tmpvar}->setDescription($v->getVar('cdescription'));
				$selectary=@unserialize($v->getVar('options', 'n'));
				if(!is_array($selectary)) $selectary=array();
				foreach($selectary as $val=>$cap){
					${$tmpvar}->addOption($val,$cap);
				}
				$config_form->addElement(${$tmpvar}, false);
				break;
				case JIEQI_TYPE_LABEL:
				$tmpvar=$v->getVar('cname');
				${$tmpvar}=new JieqiFormLabel($v->getVar('ctitle'),  $v->getVar('cvalue'));
				${$tmpvar}->setDescription($v->getVar('cdescription'));
				$config_form->addElement(${$tmpvar}, false);
				break;
				case JIEQI_TYPE_PASSWORD:
				$tmpvar=$v->getVar('cname');
				${$tmpvar}=new JieqiFormPassword($v->getVar('ctitle'), $v->getVar('cname'), 25, 30, '');
				${$tmpvar}->setDescription($v->getVar('cdescription'));
				$config_form->addElement(${$tmpvar}, false);
				break;
				case JIEQI_TYPE_TXTBOX:
				default:
				$tmpvar=$v->getVar('cname');
				${$tmpvar}=new JieqiFormText($v->getVar('ctitle'), $v->getVar('cname'), 25, 100, $v->getVar('cvalue','e'));
				${$tmpvar}->setDescription($v->getVar('cdescription'));
				$config_form->addElement(${$tmpvar}, false);
				break;
			}
		}while($v = $configs_handler->getObject());
		$config_form->addElement(new JieqiFormHidden('mod', $_REQUEST['mod']));
		$config_form->addElement(new JieqiFormHidden('define', $_REQUEST['define']));
		$config_form->addElement(new JieqiFormHidden('action', 'update'));
		$config_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['save_config'], 'submit'));
		$jieqiTpl->assign('jieqi_contents', '<div style="text-align:center;"><span style="line-height:200%">'.$catlink.'</span></div>'.$config_form->render(JIEQI_FORM_MIDDLE).'<br />');
		include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
	}
}else{
	jieqi_msgwin(LANG_NOTICE, $jieqiLang['system']['no_usage_config']);
}

?>