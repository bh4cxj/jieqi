<?php 
/**
 * 用户书架区块
 *
 * 显示某个用户的书架
 * 
 * 调用模板：/modules/article/templates/blocks/block_ubookcase.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_ubookcase.php 300 2008-12-26 04:36:06Z juny $
 */

class BlockArticleUbookcase extends JieqiBlock
{
	var $module = 'article';
	var $template = 'block_ubookcase.html';
	var $exevars=array('field'=>'lastupdate', 'listnum'=>10, 'asc'=>0, 'uid'=>'uid', 'flag'=>0);
	//uid: 'self' = 自己, 'uid' = $_REQUEST['uid'], 0 = 所有人, >0 = 某个人
	//flag : 0 = 不限 1 = 符合 2 = 不符合

	function BlockArticleUbookcase(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(in_array($varary[0], array('articleid', 'lastupdate', 'caseid', 'joindate', 'lastvisit'))) $this->exevars['field']=$varary[0];
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
				if(in_array($varary[4], array('0', '1', '2'))) $this->exevars['flag']=$varary[4];
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
		global $jieqiConfigs;

		jieqi_getconfigs('article', 'configs');
		jieqi_getconfigs('article', 'sort');
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

		$this->blockvars['cacheid']=0;
		
		if(strval($this->exevars['uid']) != '0'){
			if($this->exevars['uid'] == 'self'){
				$this->blockvars['cacheid']=$_SESSION['jieqiUserId'];
			}elseif(is_numeric($this->exevars['uid'])){
				$this->blockvars['cacheid']=intval($this->exevars['uid']);
			}else{
				$this->blockvars['cacheid']=intval($_REQUEST[$this->exevars['uid']]);
			}
		}
		
		jieqi_includedb();
		$bookcase_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
		$criteria=new CriteriaCompo();
		$criteria->add(new Criteria('c.userid', intval($this->blockvars['cacheid'])));

		if($this->exevars['flag']==1) $criteria->add(new Criteria('flag', 1));
		elseif($this->exevars['flag']==2) $criteria->add(new Criteria('flag', 0));

		$criteria->setTables(jieqi_dbprefix('article_bookcase').' c LEFT JOIN '.jieqi_dbprefix('article_article').' a ON c.articleid=a.articleid');
		$criteria->setFields('c.*, a.articleid, a.lastupdate, a.articlename, a.authorid, a.author, a.sortid, a.typeid, a.lastchapterid, a.lastchapter');
		$tmpary=array('articleid'=>'a.articleid', 'lastupdate'=>'a.lastupdate', 'caseid'=>'c.caseid', 'joindate'=>'c.joindate', 'lastvisit'=>'c.lastvisit');
		$criteria->setSort($tmpary[$this->exevars['field']]);
		if($this->exevars['asc']==1) $criteria->setOrder('ASC');
		else  $criteria->setOrder('DESC');
		$criteria->setLimit($this->exevars['listnum']);
		$criteria->setStart(0);
		$bookcase_query->queryObjects($criteria);
		unset($criteria);
		$bookcaserows=array();
		$k=0;
		while($v = $bookcase_query->getObject()){
			$bookcaserows[$k]['caseid']=$v->getVar('caseid');
			$bookcaserows[$k]['articleid']=$v->getVar('articleid');
			$bookcaserows[$k]['lastchapterid']=$v->getVar('lastchapterid');
			$bookcaserows[$k]['chapterid']=$v->getVar('chapterid');
			$bookcaserows[$k]['sortid']=$v->getVar('sortid');
			$bookcaserows[$k]['typeid']=$v->getVar('typeid');
			$bookcaserows[$k]['sort']=$jieqiSort['article'][$v->getVar('sortid')]['shortname'];
			$bookcaserows[$k]['type']=$bookcaserows[$k]['sort'];
			//$bookcaserows[$k]['type']=$jieqiSort['article'][$v->getVar('sortid')]['types'][$v->getVar('typeid')];
			$bookcaserows[$k]['authorid']=$v->getVar('authorid');
			$bookcaserows[$k]['author']=$v->getVar('author');

			$bookcaserows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('caseid').'">';
			$tmpvar=$v->getVar('articlename');
			if(!empty($tmpvar)) {
				$bookcaserows[$k]['url_articleinfo']=$article_dynamic_url.'/readbookcase.php?aid='.$v->getVar('articleid').'&bid='.$v->getVar('caseid');
				$bookcaserows[$k]['url_index']=$bookcaserows[$k]['url_articleinfo'].'&indexflag=1';
				$bookcaserows[$k]['articlename']=$v->getVar('articlename');
			}else{
				$bookcaserows[$k]['url_articleinfo']='#';
				$bookcaserows[$k]['url_index']='#';
				$bookcaserows[$k]['articlename']=$jieqiLang['article']['articlemark_has_deleted'];
			}

			if($v->getVar('lastchapter')==''){
				$bookcaserows[$k]['lastchapter']='';
				$bookcaserows[$k]['url_lastchapter']='#';
			}else{
				$bookcaserows[$k]['lastchapter']=$v->getVar('lastchapter');
				$bookcaserows[$k]['url_lastchapter']=$article_dynamic_url.'/readbookcase.php?aid='.$v->getVar('articleid').'&bid='.$v->getVar('caseid').'&cid='.$v->getVar('lastchapterid');
			}
			if($v->getVar('lastupdate')>$v->getVar('lastvisit')) $bookcaserows[$k]['hasnew']=1;
			else $bookcaserows[$k]['hasnew']=0;

			if($v->getVar('chaptername')==''){
				$bookcaserows[$k]['articlemark']='';
				$bookcaserows[$k]['url_articlemark']='#';
			}else{
				$bookcaserows[$k]['articlemark']=$v->getVar('chaptername');
				$bookcaserows[$k]['url_articlemark']=$article_dynamic_url.'/readbookcase.php?aid='.$v->getVar('articleid').'&bid='.$v->getVar('caseid').'&cid='.$v->getVar('chapterid');
			}
			$bookcaserows[$k]['lastupdate']=$v->getVar('lastupdate');
			$bookcaserows[$k]['url_delete']=jieqi_addurlvars(array('delid'=>$v->getVar('caseid')));
			$k++;
		}
		$jieqiTpl->assign_by_ref('bookcaserows', $bookcaserows);
		$jieqiTpl->assign('ownerid', $this->blockvars['cacheid']);
	}

}