<?php
/**
 * 用户好友列表区块
 *
 * 显示某个用户的好友列表
 * 
 * 调用模板：/templates/blocks/block_ufriends.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_ufriends.php 301 2008-12-26 04:36:17Z juny $
 */

class BlockSystemUfriends extends JieqiBlock
{
	var $module='system';
	var $template='block_ufriends.html';
	var $exevars=array('field'=>'friendsid', 'listnum'=>10, 'asc'=>0, 'uid'=>'uid', 'state'=>0, 'flag'=>0);
	//uid: 'self' = 自己, 'uid' = $_REQUEST['uid'], 0 = 所有人, >0 = 某个人
	//istop isgood islock : 0 = 不限 1 = 符合 2 = 不符合
	
	function BlockSystemUfriends(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(in_array($varary[0], array('friendsid', 'adddate'))) $this->exevars['field']=$varary[0];
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1]) && $varary[1]>0) $this->exevars['listnum']=intval($varary[1]);
			}

			if($arynum>2){
				$varary[2]=trim($varary[2]);
				if(in_array($varary[2], array('0', '1'))) $this->exevars['asc']=$varary[2];
			}

			if($arynum>3){
				$varary[3]=trim($varary[3]);
				if(strlen($varary[3]) > 0) $this->exevars['uid']=$varary[3];
			}

			if($arynum>4){
				$varary[4]=trim($varary[4]);
				if(is_numeric($varary[4])) $this->exevars['state']=$varary[4];
			}

			if($arynum>5){
				$varary[5]=trim($varary[5]);
				if(is_numeric($varary[5])) $this->exevars['flag']=$varary[5];
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
		if(empty($this->blockvars['cacheid'])) return '';
		
		include_once(JIEQI_ROOT_PATH.'/class/friends.php');
		$friends_handler =& JieqiFriendsHandler::getInstance('JieqiFriendsHandler');
		$criteria=new CriteriaCompo();
		$criteria->add(new Criteria('myid', $this->blockvars['cacheid']));

		if($this->exevars['state']==1) $criteria->add(new Criteria('state', 1));
		elseif($this->exevars['state']==2) $criteria->add(new Criteria('state', 0));

		if($this->exevars['flag']==1) $criteria->add(new Criteria('flag', 1));
		elseif($this->exevars['flag']==2) $criteria->add(new Criteria('flag', 0));

		$criteria->setSort($this->exevars['field']);
		if($this->exevars['asc']==1) $criteria->setOrder('ASC');
		else  $criteria->setOrder('DESC');
		$criteria->setLimit($this->exevars['listnum']);
		$criteria->setStart(0);
		$friends_handler->queryObjects($criteria);
		$friendrows=array();
		$k=0;
		while($v = $friends_handler->getObject()){
			$friendrows[$k]['friendsid']=$v->getVar('friendsid');
			$friendrows[$k]['adddate']=$v->getVar('adddate');
			$friendrows[$k]['myid']=$v->getVar('myid');
			$friendrows[$k]['myname']=$v->getVar('myname');
			$friendrows[$k]['yourid']=$v->getVar('yourid');
			$friendrows[$k]['yourname']=$v->getVar('yourname');
			$friendrows[$k]['teamid']=$v->getVar('teamid');
			$friendrows[$k]['team']=$v->getVar('team');
			$friendrows[$k]['fset']=$v->getVar('fset');
			$friendrows[$k]['state']=$v->getVar('state');
			$friendrows[$k]['flag']=$v->getVar('flag');
			$k++;
		}
		$jieqiTpl->assign_by_ref('friendrows', $friendrows);
		$jieqiTpl->assign('ownerid', $this->blockvars['cacheid']);

		$jieqiTpl->assign('url_more', JIEQI_URL.'/userfriends?uid='.$this->blockvars['cacheid']);

	}
}

?>