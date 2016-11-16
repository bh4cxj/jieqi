<?php 
/**
 * 虚拟币转账
 *
 * 虚拟币转账
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: transfer.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
require_once('../../global.php');
jieqi_checklogin();
jieqi_loadlang('transfer', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);

jieqi_getconfigs('system', 'configs');
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'show';
switch($_REQUEST['action']) {
	case 'transfer':
	$_REQUEST['transegold']=trim($_REQUEST['transegold']);
	$_REQUEST['toname']=trim($_REQUEST['toname']);
	$_REQUEST['password']=trim($_REQUEST['password']);
	$errtext='';

	//检查金额类型
	if(empty($_REQUEST['transegold']) || !is_numeric($_REQUEST['transegold'])){
		$errtext.=$jieqiLang['pay']['gold_need_number'].'<br />';
	}else{
		$_REQUEST['transegold']=floor($_REQUEST['transegold']);
        if($users_handler->encryptPass($_REQUEST['password']) != $jieqiUsers->getVar('pass')){
			$errtext.=$jieqiLang['pay']['password_error'].'<br />';
		}
		if(!isset($_REQUEST['toname']) || $_REQUEST['toname']==''){
			$errtext.=$jieqiLang['pay']['need_to_username'].'<br />';
		}
		$cantrans=false; //是否允许这种转出类型
		$outnum=0;
		//检查转出金额
		if($_REQUEST['transtype']==1 && $jieqiConfigs['system']['egoldtransrate']>0 && $jieqiConfigs['system']['egoldtransrate']<=100){
			//虚拟币不允许转给自己
			if($_REQUEST['toname'] == $jieqiUsers->getVar('uname', 'n')){
				$errtext.=sprintf($jieqiLang['pay']['cant_to_self'], JIEQI_EGOLD_NAME).'<br />';	
			}
			$outnum=ceil($_REQUEST['transegold'] * 100 / $jieqiConfigs['system']['egoldtransrate']);
			if($outnum > $jieqiUsers->getVar('egold')){
			    $errtext.=$jieqiLang['pay']['too_egold_num'].'<br />';	
			}
			$cantrans=true;
		}elseif($_REQUEST['transtype']==2 && $jieqiConfigs['system']['creditransrate']>0 && $jieqiConfigs['system']['creditransrate']<=100){
			$outnum=ceil($_REQUEST['transegold'] * 100 / $jieqiConfigs['system']['creditransrate']);
			if($outnum > $jieqiUsers->getVar('credit')){
			    $errtext.=$jieqiLang['pay']['too_egold_num'].'<br />';	
			}
			$cantrans=true;
		}elseif($_REQUEST['transtype']==3 && $jieqiConfigs['system']['scoretransrate']>0 && $jieqiConfigs['system']['scoretransrate']<=100){
			$outnum=ceil($_REQUEST['transegold'] * 100 / $jieqiConfigs['system']['scoretransrate']);
			if($outnum > $jieqiUsers->getVar('score')){
			    $errtext.=$jieqiLang['pay']['too_egold_num'].'<br />';	
			}
			$cantrans=true;
		}
		if(!$cantrans) $errtext.=$jieqiLang['pay']['error_trans_type'].'<br />';
		if($_REQUEST['toname'] != $jieqiUsers->getVar('uname', 'n')){
		    $recuser=$users_handler->getByname($_REQUEST['toname']);
		    if(!is_object($recuser)){
			    $errtext.=$jieqiLang['pay']['no_to_user'].'<br />';
		    }
		}
	}
	if(empty($errtext)) {
		if($_REQUEST['transtype']==1){
			$jieqiUsers->setVar('egold', $jieqiUsers->getVar('egold')-$outnum);
		}elseif($_REQUEST['transtype']==2){
			$jieqiUsers->setVar('credit', $jieqiUsers->getVar('credit')-$outnum);
		}elseif($_REQUEST['transtype']==3){
			$jieqiUsers->setVar('score', $jieqiUsers->getVar('score')-$outnum);
		}
		$inflag=false;
		if($_REQUEST['toname']==$jieqiUsers->getVar('uname', 'n')){
			$jieqiUsers->setVar('egold', $jieqiUsers->getVar('egold')+$_REQUEST['transegold']);
			$outflag=$users_handler->insert($jieqiUsers);
			$inflag=$outflag;
		}else{
			$outflag=$users_handler->insert($jieqiUsers);
			if($outflag){
			  $recuser->setVar('egold', $recuser->getVar('egold')+$_REQUEST['transegold']);
			  $inflag=$users_handler->insert($recuser);
			}
		}
		//记录日志
		include_once($jieqiModules['pay']['path'].'/class/transfer.php');
		$transfer_handler=& JieqiTransferHandler::getInstance('JieqiTransferHandler');
		$transfer= $transfer_handler->create();
		$transfer->setVar('transtime', JIEQI_NOW_TIME);
		$transfer->setVar('fromid', $jieqiUsers->getVar('uid'));
		$transfer->setVar('fromname', $jieqiUsers->getVar('uname', 'n'));
		if($_REQUEST['toname']==$jieqiUsers->getVar('uname', 'n')){
			$transfer->setVar('toid', $jieqiUsers->getVar('uid'));
		    $transfer->setVar('toname', $jieqiUsers->getVar('uname', 'n'));
		}else{
			$transfer->setVar('toid', $recuser->getVar('uid'));
		    $transfer->setVar('toname', $recuser->getVar('uname', 'n'));
		}
		$transfer->setVar('translog', '');
		$transfer->setVar('transegold', $outnum);
		$transfer->setVar('receiveegold', $_REQUEST['transegold']);
		$transfer->setVar('mastertime', 0);
		$transfer->setVar('masterid', 0);
		$transfer->setVar('mastername', '');
		$transfer->setVar('masterlog', '');
		$transfer->setVar('transtype', $_REQUEST['transtype']);	
        $transfer->setVar('errflag', 0);	
        $transflag=0;
        if($outflag){
        	$jieqiUsers->saveToSession();
        	$transflag++;
        	if($inflag) $transflag++;
        }
        $transfer->setVar('transflag', $transflag);	
		$transfer_handler->insert($transfer);
		if(!$outflag){
			jieqi_printfail($jieqiLang['pay']['trans_out_failure']);
		}elseif(!$inflag){
			jieqi_printfail($jieqiLang['pay']['trans_in_failure']);
		}else{
		    jieqi_jumppage($jieqiModules['pay']['url'].'/transfer.php', LANG_DO_SUCCESS, $jieqiLang['pay']['trans_success']);	
		}
	}else{
		jieqi_printfail($errtext);
	}
	break;
	case 'show':
	default:
	include_once(JIEQI_ROOT_PATH.'/header.php');
	include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
	$trans_form = new JieqiThemeForm(sprintf($jieqiLang['pay']['trans_title'], JIEQI_EGOLD_NAME), 'transform', JIEQI_URL.'/transfer.php');
	$trans_form->addElement(new JieqiFormLabel($jieqiLang['pay']['trans_username'], $jieqiUsers->getVar('uname','s')));
	$defaulttype=0;
	if($jieqiConfigs['system']['egoldtransrate']>0 && $jieqiConfigs['system']['egoldtransrate']<=100){
		if(empty($defaulttype)) $defaulttype=1;
		$trans_form->addElement(new JieqiFormLabel(JIEQI_EGOLD_NAME, $jieqiUsers->getVar('egold').sprintf($jieqiLang['pay']['trans_rate_note'], JIEQI_EGOLD_NAME, floor($jieqiUsers->getVar('egold') * $jieqiConfigs['system']['egoldtransrate'] / 100), $jieqiConfigs['system']['egoldtransrate'])));
	}
	if($jieqiConfigs['system']['creditransrate']>0 && $jieqiConfigs['system']['creditransrate']<=100){
		if(empty($defaulttype)) $defaulttype=2;
		$trans_form->addElement(new JieqiFormLabel($jieqiLang['pay']['trans_credit'], $jieqiUsers->getVar('credit').sprintf($jieqiLang['pay']['trans_rate_note'], JIEQI_EGOLD_NAME, floor($jieqiUsers->getVar('credit') * $jieqiConfigs['system']['creditransrate'] / 100), $jieqiConfigs['system']['creditransrate'])));
	}
	if($jieqiConfigs['system']['scoretransrate']>0 && $jieqiConfigs['system']['scoretransrate']<=100){
		if(empty($defaulttype)) $defaulttype=3;
		$trans_form->addElement(new JieqiFormLabel($jieqiLang['pay']['trans_score'], $jieqiUsers->getVar('score').sprintf($jieqiLang['pay']['trans_rate_note'], JIEQI_EGOLD_NAME, floor($jieqiUsers->getVar('score') * $jieqiConfigs['system']['scoretransrate'] / 100), $jieqiConfigs['system']['scoretransrate'])));
	}
	$trans_option=new JieqiFormRadio($jieqiLang['pay']['trans_type'], 'transtype', $defaulttype);
	if($jieqiConfigs['system']['egoldtransrate']>0 && $jieqiConfigs['system']['egoldtransrate']<=100){
		$trans_option->addOption(1, JIEQI_EGOLD_NAME);
	}
	if($jieqiConfigs['system']['creditransrate']>0 && $jieqiConfigs['system']['creditransrate']<=100){
		$trans_option->addOption(2, $jieqiLang['pay']['trans_credit']);
	}
	if($jieqiConfigs['system']['scoretransrate']>0 && $jieqiConfigs['system']['scoretransrate']<=100){
		$trans_option->addOption(3, $jieqiLang['pay']['trans_score']);
	}
	$trans_form->addElement($trans_option);
	$egold_text=new JieqiFormText($jieqiLang['pay']['trans_egold'], 'transegold', 25, 30);
	$egold_text->setDescription(JIEQI_EGOLD_NAME);
	$trans_form->addElement($egold_text, true);

	$toname_text=new JieqiFormText($jieqiLang['pay']['trans_toname'], 'toname', 25, 30);
	$toname_text->setDescription($jieqiLang['pay']['trans_toname_note']);
	$trans_form->addElement($toname_text, true);

	$trans_form->addElement(new  JieqiFormPassword($jieqiLang['pay']['trans_password'], 'password', 25, 30), true);
	$trans_form->addElement(new JieqiFormHidden('action', 'transfer'));
	$trans_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));

	$jieqiTpl->setCaching(0);
	$jieqiTpl->assign('jieqi_contents', '<br />'.$trans_form->render(JIEQI_FORM_MIDDLE).'<br />');
	include_once(JIEQI_ROOT_PATH.'/footer.php');
	break;
}

?>