<?php
/**
 * 文章列表区块
 *
 * 文章列表
 * 
 * 调用模板：/modules/space/templates/blocks/block_bloglist.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//文章列表
class BlockBlogList extends JieqiBlock
{
	var $exevars=array('field'=>'uid', 'listnum'=>10,'asc'=>0);  //执行配置
	var $module = 'space';
	var $template='block_bloglist.html';
	var $cachetime = -1;
	function BlockBlogList(&$vars){
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
        $this->blockvars['cacheid'] = $this->template.'|'.$this->blockvars['bid'].'|'.$this->exevars['field'].'|'.$this->exevars['listnum'].'|'.$this->exevars['asc'];
	}

	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiModules;
		require_once($jieqiModules['space']['path'].'/class/blog.php');
		$blog_handler = JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');
		$criteria=new CriteriaCompo();
		$criteria->add(new Criteria('ar_open',1) );
		$criteria->setSort($this->exevars['field']);
		if($this->exevars['asc']==1) $criteria->setOrder('ASC');
		else  $criteria->setOrder('DESC');
		if($this->blockvars['bid'] == 6){
			$criteria->add(new Criteria('ar_commend',1) );
		}
		$criteria->setLimit($this->exevars['listnum']);
		$criteria->setStart(0);
		$blog_handler->queryObjects($criteria);
		$blogs=array();
		$k=0;
		while($v = $blog_handler->getObject()){
			$blogs[$k]['id']=$v->getVar('id');
			$blogs[$k]['uid']=$v->getVar('uid');
			$blogs[$k]['up_time']=$v->getVar('up_time','s');
			$blogs[$k]['name']=$v->getVar('name','s');
			$blogs[$k]['title']=jieqi_substr($v->getVar('title','s'),0,50);
			if(!$blogs[$k]['title']){
				$blogs[$k]['title'] = '----';
			}
			$blogs[$k]['hit_num'] = $v->getVar('hit_num');
			$blogs[$k]['review_num'] = $v->getVar('review_num');
			$k++;
		}
		if($this->blockvars['bid'] == 6){
			$sort = 'bloglistcomm.php';
		}elseif($this->blockvars['bid'] == 7){
			$sort = 'bloglisttime.php';
		}elseif($this->blockvars['bid'] == 8){
			$sort = 'bloglisthit.php';
		}elseif($this->blockvars['bid'] == 9){
			$sort = 'bloglistreview.php';
		}

		$jieqiTpl->assign('url_more',$jieqiModules['space']['url'].'/'.$sort);

     	$jieqiTpl->assign_by_ref('blogs', $blogs);
	}
}

?>