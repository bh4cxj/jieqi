<?php
/**
 * 我的圈子区块
 *
 * 我的圈子区块
 * 
 * 调用模板：/modules/group/templates/blocks/block_mygroup.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
class Blockmygroup extends JieqiBlock
{
	var $module = 'group';
	var $exevars=array('listnum'=>10);  //执行配置
	var $template='block_mygroup.html';
	var $cachetime = -1;
	function Blockmygroup(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$this->exevars['listnum']=intval($this->blockvars['vars']);
		}
	}

	function setContent($isreturn=false){
		global $jieqiTpl,$gcats;
		include_once(JIEQI_ROOT_PATH.'/configs/group/gcats.php');
		//查询当前用户
		require_once(JIEQI_ROOT_PATH.'/modules/group/class/member.php');
		$member_handler = JieqimemberHandler::getInstance('JieqimemberHandler');
		jieqi_includedb();
		$member_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
		$criteria = new CriteriaCompo(new Criteria('uid',$_SESSION['jieqiUserId']) );
		$criteria->setTables(jieqi_dbprefix('group_member').' m left join '.jieqi_dbprefix('group_group').' u on m.gid=u.gid'  );
		$countmembers = $member_query->getCount($criteria);
		$grouprows=array();
		$k=0;
		$member_query->queryObjects($criteria);
		while($v = $member_query->getObject()){
			$grouprows[$k]['gid']=$v->getVar('gid');
			$grouprows[$k]['gname'] = $v->getVar('gname');
			$grouprows[$k]['topicnum'] = $v->getVar('topicnum');
			$grouprows[$k]['gmembers'] = $v->getVar('gmembers');
			$grouprows[$k]['guname'] = $v->getVar('guname');
			$grouprows[$k]['catname'] = $gcats[$v->getVar('gcatid')];
			$k++;
		}

		$jieqiTpl->assign_by_ref('grouprows', $grouprows);
		$jieqiTpl->assign('sort', $this->exevars['field']);

		$jieqiTpl->assign('url_more', JIEQI_URL.'/modules/group/mangroup.php');
		$jieqiTpl->setCaching(0);

	}
}

?>