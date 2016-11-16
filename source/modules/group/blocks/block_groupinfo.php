<?php
/**
 * 圈子导航区块
 *
 * 圈子导航菜单
 * 
 * 调用模板：/modules/group/templates/blocks/block_groupinfo.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
class BlockGroupInfo extends JieqiBlock
{
	var $module = 'group';
	var $template = 'block_groupinfo.html';
	var $cachetime = -1;
	function BlockGroupInfo(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				$this->exevars['field']=$varary[0];
			}
			
			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1]) && $varary[1]>0) $this->exevars['listnum']=intval($varary[1]);
			}
			
			if($arynum>2){
				$varary[2]=trim($varary[2]);
				if(in_array($varary[2], array('0', '1'))) $this->exevars['asc']=$varary[2];
			}
		}
	}

	function getTitle(){
		global $gname;
		return $gname;
	}

	function setContent($isreturn=false){
		global $jieqiTpl,$jieqiModules;
		global $gid,$groupUserfile;
		include($groupUserfile['info']);
		$jieqiTpl->assign('gname',$gname);
		$jieqiTpl->assign('guname',$guname);
		$jieqiTpl->assign('gtime',date('Y-m-d',$gtime) );
		if(file_exists($groupUserfile['picdir'].'face.jpg')){
		   $picurl = $groupUserfile['picurl'].'face.jpg';
		}else {
		   $picurl = $groupUserfile['defaultpic'];
		};
		//判断用户权限
		 $showpower = array();
		 //基本设置
			 include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME."/include/functions.php");
			 $showpower['setbasic'] = false;
			 setpower($gid);
			 global $allowmanbasic;
			 if($allowmanbasic) $showpower['setbasic'] = true;
		 //用户退出键
		     $showpower['setout'] = false;
			 include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME."/class/member.php");
		     $member_handler = JieqimemberHandler::getInstance('JIeqimemberHandler');
			 $criteria = new  CriteriaCompo(new Criteria('uid',$_SESSION['jieqiUserId']) ) ;
			 $criteria->add(new Criteria('gid',$gid) );
			 $member_handler->queryObjects($criteria);
		     if($member_handler->getObject() ){
			   $showpower['setout'] = true;
			 }
		$jieqiTpl->assign('gpic',$picurl);
		$jieqiTpl->assign('showpower',$showpower);
		return $tmpvar;
	}	

}

?>