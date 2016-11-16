<?php
/**
 * 用户推荐区块
 *
 * 显示参数里面推荐的几个用户
 * 
 * 调用模板：/templates/blocks/block_usercommend.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_commend.php 278 2008-12-11 09:24:42Z juny $
 */

class BlockSystemUsercommend extends JieqiBlock
{
	var $module = 'system';
	var $template = 'block_usercommend.html';

	var $exevars=array();  //执行配置

	function BlockSystemUsercommend(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$tmpary=explode('|', trim($this->blockvars['vars']));
			foreach($tmpary as $v){
				$v=trim($v);
				if(is_numeric($v)) $this->exevars[]=intval($v);
			}
			$this->exevars=array_unique($this->exevars);
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);
	}

	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiModules;
		$userrows=array();
		if(count($this->exevars) > 0){
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$criteria=new CriteriaCompo();
			$criteria->add(new Criteria('uid', '(0,'.implode(',', $this->exevars).')', 'IN'));
			$criteria->setLimit(100);
			$criteria->setStart(0);
			$users_handler->queryObjects($criteria);

			$k=0;
			while($v = $users_handler->getObject()){
				$userrows[$k]['uid']=$v->getVar('uid');
				$userrows[$k]['uname']=$v->getVar('uname');
				$userrows[$k]['name']=$v->getVar('name');
				if(empty($userrows[$k]['name'])) $userrows[$k]['name']=$v->getVar('uname');
				$userrows[$k]['groupid']=$v->getVar('groupid');
				$userrows[$k]['group']=$v->getGroup();
				$userrows[$k]['sex']=$v->getSex();


				if($v->getVar('viewemail')==1){
					$userrows[$k]['viewemail']=1;
					$userrows[$k]['email']=$v->getVar('email');
				}else{
					$userrows[$k]['viewemail']=0;
					$userrows[$k]['email']='';
				}
				$userrows[$k]['qq']=$v->getVar('qq');
				$userrows[$k]['icq']=$v->getVar('icq');
				$userrows[$k]['msn']=$v->getVar('msn');
				$userrows[$k]['url']=$v->getVar('url');
				$userrows[$k]['regdate']=$v->getVar('regdate');
				$userrows[$k]['experience']=$v->getVar('experience');
				$userrows[$k]['score']=$v->getVar('score');
				$userrows[$k]['monthscore']=$v->getVar('monthscore');
				$userrows[$k]['weekscore']=$v->getVar('weekscore');
				$userrows[$k]['dayscore']=$v->getVar('dayscore');
				$userrows[$k]['credit']=$v->getVar('credit');
				$userrows[$k]['viptype']=$v->getViptype();

				jieqi_getconfigs('system', 'honors');
				$honorid=jieqi_gethonorid($v->getVar('score'), $jieqiHonors);
				$userrows[$k]['honor']=$jieqiHonors[$honorid]['name'][intval($v->getVar('workid'))];
				$egold=$v->getVar('egold');
				$esilver=$v->getVar('esilver');
				$emoney=$egold+$esilver;
				$userrows[$k]['egold']=$egold;
				$userrows[$k]['esilver']=$esilver;
				$userrows[$k]['emoney']=$emoney;

				$userrows[$k]['sign']=$v->getVar('sign');
				//$userrows[$k]['intro']=$v->getVar('intro');

				//头像
				$avatar = $v->getVar('avatar', 'n');
				$userrows[$k]['avatar']=$avatar;
				//徽章
				if(!empty($jieqiModules['badge']['publish']) && is_file($jieqiModules['badge']['path'].'/include/badgefunction.php')){
					include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
					//等级徽章
					$userrows[$k]['url_group']=getbadgeurl(1, $v->getVar('groupid'), 0, true);
					//头衔徽章
					$userrows[$k]['url_honor']=getbadgeurl(2, $honorid, 0, true);
					//自定义徽章
					$jieqi_badgerows=array();
					$badgeary=unserialize($v->getVar('badges', 'n'));
					if(is_array($badgeary) && count($badgeary)>0){
						$k=0;
						foreach($badgeary as $badge){
							$jieqi_badgerows[$k]['imageurl']=getbadgeurl($badge['btypeid'], $badge['linkid'], $badge['imagetype']);
							$jieqi_badgerows[$k]['caption']=jieqi_htmlstr($badge['caption']);
							$k++;
						}
					}
					$userrows[$k]['badgerows']=$jieqi_badgerows;
					$userrows[$k]['use_badge']=1;
				}else{
					$userrows[$k]['use_badge']=0;
				}

				$k++;
			}
		}
		$jieqiTpl->assign_by_ref('userrows', $userrows);
	}
}

?>