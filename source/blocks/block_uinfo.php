<?php
/**
 * 用户信息显示区块
 *
 * 显示某个用户的基本信息
 * 
 * 调用模板：/templates/blocks/block_uinfo.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_uinfo.php 332 2009-02-23 09:15:08Z juny $
 */

class BlockSystemUinfo extends JieqiBlock
{
	var $module='system';
	var $template='block_uinfo.html';
	var $exevars=array('uid'=>'uid');
	//uid: 'self' = 自己, 'uid' = $_REQUEST['uid'], 0 = 所有人, >0 = 某个人

	function BlockSystemUinfo(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(strlen($varary[0]) > 0) $this->exevars['uid']=$varary[0];
			}
		}
		$this->blockvars['cacheid']=0;
		if(strval($this->exevars['uid']) != '0'){
			if($this->exevars['uid'] == 'self') $this->blockvars['cacheid'] = intval($_SESSION['jieqiUserId']);
			elseif(is_numeric($this->exevars['uid'])) $this->blockvars['cacheid']=intval($this->exevars['uid']);
			else $this->blockvars['cacheid']=intval($_REQUEST[$this->exevars['uid']]);
		}
	}

	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiGroups;
		global $jieqiConfigs;
		global $jieqiHonors;
		global $jieqi_image_type;
		global $jieqiModules;

		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		$userobj=$users_handler->get($this->blockvars['cacheid']);
		if(!is_object($userobj)) return false;

		$jieqiTpl->assign('uid', $userobj->getVar('uid'));
		$jieqiTpl->assign('uname', $userobj->getVar('uname'));
		$tmpvar = strlen($userobj->getVar('name')) > 0 ? $userobj->getVar('name') : $userobj->getVar('uname');
		$jieqiTpl->assign('name', $tmpvar);
		$jieqiTpl->assign('groupid', $userobj->getVar('groupid'));
		$jieqiTpl->assign('group', $userobj->getGroup());
		$jieqiTpl->assign('sex', $userobj->getSex());

		if($userobj->getVar('viewemail')==1){
			$jieqiTpl->assign('viewemail', 1);
			$jieqiTpl->assign('email', $userobj->getVar('email'));
		}else{
			$jieqiTpl->assign('viewemail', 0);
			$jieqiTpl->assign('email', '');
		}
		$jieqiTpl->assign('qq', $userobj->getVar('qq'));
		$jieqiTpl->assign('icq', $userobj->getVar('icq'));
		$jieqiTpl->assign('msn', $userobj->getVar('msn'));
		$jieqiTpl->assign('url', $userobj->getVar('url'));

		$jieqiTpl->assign('regdate', $userobj->getVar('regdate'));
		$jieqiTpl->assign('experience', $userobj->getVar('experience'));
		$jieqiTpl->assign('score', $userobj->getVar('score'));
		$jieqiTpl->assign('monthscore', $userobj->getVar('monthscore'));
		$jieqiTpl->assign('weekscore', $userobj->getVar('weekscore'));
		$jieqiTpl->assign('dayscore', $userobj->getVar('dayscore'));
		$jieqiTpl->assign('credit', $userobj->getVar('credit'));
		$jieqiTpl->assign('viptype', $userobj->getViptype());
		$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);

		jieqi_getconfigs('system', 'honors');
		$honorid=jieqi_gethonorid($userobj->getVar('score'), $jieqiHonors);
		$jieqiTpl->assign('honor', $jieqiHonors[$honorid]['name'][intval($userobj->getVar('workid'))]);

		$egold=$userobj->getVar('egold');
		$esilver=$userobj->getVar('esilver');
		$emoney=$egold+$esilver;
		$jieqiTpl->assign('egold', $egold);
		$jieqiTpl->assign('esilver', $esilver);
		$jieqiTpl->assign('emoney', $emoney);

		$jieqiTpl->assign('sign', $userobj->getVar('sign'));
		$jieqiTpl->assign('intro', $userobj->getVar('intro'));

		//头像
		$avatar = $userobj->getVar('avatar', 'n');
		$jieqiTpl->assign('avatar', $avatar);
		//徽章
		if(!empty($jieqiModules['badge']['publish']) && is_file($jieqiModules['badge']['path'].'/include/badgefunction.php')){
			include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
			//等级徽章
			$jieqiTpl->assign('url_group', getbadgeurl(1, $userobj->getVar('groupid'), 0, true));
			//头衔徽章
			$jieqiTpl->assign('url_honor', getbadgeurl(2, $honorid, 0, true));
			//自定义徽章
			$jieqi_badgerows=array();
			$badgeary=unserialize($userobj->getVar('badges', 'n'));
			if(is_array($badgeary) && count($badgeary)>0){
				$k=0;
				foreach($badgeary as $badge){
					$jieqi_badgerows[$k]['imageurl']=getbadgeurl($badge['btypeid'], $badge['linkid'], $badge['imagetype']);
					$jieqi_badgerows[$k]['caption']=jieqi_htmlstr($badge['caption']);
					$k++;
				}
			}
			$jieqiTpl->assign_by_ref('badgerows', $jieqi_badgerows);
			$jieqiTpl->assign('use_badge', 1);
		}else{
			$jieqiTpl->assign('use_badge', 0);
		}
		$jieqiTpl->assign('ownerid', $this->blockvars['cacheid']);
	}
}

?>