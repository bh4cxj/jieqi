<?php
/**
 * 用户友情链接显示区块
 *
 * 显示某个用户的友情链接
 * 
 * 调用模板：/templates/blocks/block_ulinks.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_ulinks.php 301 2008-12-26 04:36:17Z juny $
 */

class BlockSystemUlinks extends JieqiBlock
{
	var $module='system';
	var $template='block_ulinks.html';
	
	var $exevars=array('field'=>'toptime', 'listnum'=>10, 'asc'=>0, 'uid'=>'uid', 'istop'=>0);
	//uid: 'self' = 自己, 'uid' = $_REQUEST['uid'], 0 = 所有人, >0 = 某个人
	//istop isgood islock : 0 = 不限 1 = 符合 2 = 不符合

	function BlockSystemUlinks(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(in_array($varary[0], array('toptime', 'addtime'))) $this->exevars['field']=$varary[0];
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

		include_once(JIEQI_ROOT_PATH.'/class/userlink.php');
		$userlink_handler=& JieqiUserlinkHandler::getInstance('JieqiUserlinkHandler');
		$criteria=new CriteriaCompo();
		$criteria->add(new Criteria('userid', $this->blockvars['cacheid']));

		if($this->exevars['istop']==1) $criteria->add(new Criteria('toptime', 0, '>'));
		elseif($this->exevars['istop']==2) $criteria->add(new Criteria('toptime', 0));

		$criteria->setSort($this->exevars['field']);
		if($this->exevars['asc']==1) $criteria->setOrder('ASC');
		else  $criteria->setOrder('DESC');
		$criteria->setLimit($this->exevars['listnum']);
		$criteria->setStart(0);
		$userlink_handler->queryObjects($criteria);
		$linkrows=array();
		$k=0;
		while($v = $userlink_handler->getObject()){
			$linkrows[$k]['ulid'] = $v->getVar('ulid');
			$linkrows[$k]['ultitle'] = $v->getVar('ultitle');
			$linkrows[$k]['ulurl'] = $v->getVar('ulurl');
			$linkrows[$k]['ulinfo'] = $v->getVar('ulinfo');
			$linkrows[$k]['userid'] = $v->getVar('userid');
			$linkrows[$k]['username'] = $v->getVar('username');
			$linkrows[$k]['score'] = $v->getVar('score');
			$linkrows[$k]['weight'] = $v->getVar('weight');
			$linkrows[$k]['toptime'] = $v->getVar('toptime');
			$linkrows[$k]['addtime'] = $v->getVar('addtime');
			$linkrows[$k]['allvisit'] = $v->getVar('allvisit');
			$k++;
		}
		$jieqiTpl->assign_by_ref('linkrows', $linkrows);
		$jieqiTpl->assign('ownerid', $this->blockvars['cacheid']);

		$jieqiTpl->assign('url_more', JIEQI_URL.'/userlink.php?uid='.$this->blockvars['cacheid']);
	}
}

?>