<?php 
/**
 * 电子书推荐区块
 *
 * 根据id推荐电子书
 * 
 * 调用模板：/modules/obook/templates/blocks/block_obookcommend.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_obooklist.php 231 2008-11-27 08:46:26Z juny $
 */

class BlockObookObookcommend extends JieqiBlock
{
	var $module = 'obook';
	var $template = 'block_obookcommend.html';

	var $exevars=array();  //执行配置

	function BlockObookObookcommend(&$vars){
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
		global $jieqiConfigs;
		global $jieqiSort;
		$obookrows=array();
		if(count($this->exevars) > 0){
			include_once($GLOBALS['jieqiModules']['obook']['path'].'/class/obook.php');
			jieqi_getconfigs('obook', 'configs');
			$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $GLOBALS['jieqiModules']['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
			$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $GLOBALS['jieqiModules']['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
			$jieqiTpl->assign('obook_static_url',$obook_static_url);
			$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);

			$tmpvar=explode('-',date('Y-m-d',JIEQI_NOW_TIME));
			$daystart=mktime(0,0,0,(int)$tmpvar[1],(int)$tmpvar[2],(int)$tmpvar[0]);
			$monthstart=mktime(0,0,0,(int)$tmpvar[1],1,(int)$tmpvar[0]);
			$tmpvar=date('w',JIEQI_NOW_TIME);
			if($tmpvar==0) $tmpvar=7; //星期天是0，国人习惯作为作为一星期的最后一天
			$weekstart=$daystart;
			if($tmpvar>1) $weekstart-=($tmpvar-1) * 86400;

			$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
			$criteria=new CriteriaCompo();
			$criteria->add(new Criteria('uid', '(0,'.implode(',', $this->exevars).')', 'IN'));
			$criteria->add(new Criteria('display', 0, '='));
			$criteria->add(new Criteria('size', 0, '>'));
			$criteria->setLimit(100);
			$criteria->setStart(0);
			$obook_handler->queryObjects($criteria);
			jieqi_getconfigs('obook', 'sort');
			$k=0;
			while($v = $obook_handler->getObject()){
				$obookrows[$k]['order']=$k+1;
				$obookrows[$k]['obookid']=$v->getVar('obookid');  //文章序号
				$obookrows[$k]['obookname']=$v->getVar('obookname');  //文章名称
				$obookrows[$k]['articleid']=$v->getVar('articleid');  //文章序号
				if($jieqiConfigs['obook']['fakeinfo']==1){
					$obookrows[$k]['obooksubdir']=jieqi_getsubdir($v->getVar('obookid'));  //子目录
					if(!empty($jieqiConfigs['obook']['fakeprefix'])) $tmpvar='/'.$jieqiConfigs['obook']['fakeprefix'].'info';
					else $tmpvar='/files/obook/info';
					$obookrows[$k]['url_obookinfo']=$obook_dynamic_url.$tmpvar.$obookrows[$k]['obooksubdir'].'/'.$v->getVar('obookid').$jieqiConfigs['obook']['fakefile'];  //子目录
				}else{
					$obookrows[$k]['obooksubdir']='';
					$obookrows[$k]['url_obookinfo']=$obook_dynamic_url.'/obookinfo.php?id='.$v->getVar('obookid');  //子目录
				}
				if($v->getVar('lastchapter')==''){
					$obookrows[$k]['lastchapterid']=0;  //章节序号
					$obookrows[$k]['lastchapter']='';  //章节名称
					$obookrows[$k]['url_lastchapter']='';  //章节地址
				}else{
					$obookrows[$k]['lastchapterid']=$v->getVar('lastchapterid');
					$obookrows[$k]['lastchapter']=$v->getVar('lastchapter');
					$obookrows[$k]['url_lastchapter']=$obook_static_url.'/reader.php?aid='.$v->getVar('obookid').'&cid='.$v->getVar('lastchapterid');
				}
				//公众章节
				if($obookrows[$k]['articleid'] > 0){
					if($jieqiConfigs['article']['makehtml']==0 || JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET){
						$obookrows[$k]['url_read'] = $article_static_url.'/reader.php?aid='.$obookrows[$k]['articleid'];
					}else{
						$obookrows[$k]['url_read'] = jieqi_uploadurl($jieqiConfigs['article']['htmldir'], $jieqiConfigs['article']['htmlurl'], 'article', $article_static_url).jieqi_getsubdir($obookrows[$k]['articleid']).'/'.$obookrows[$k]['articleid'].'/index'.$jieqiConfigs['article']['htmlfile'];
					}
				}
				$obookrows[$k]['lastvolume']=$v->getVar('lastvolumeid');  //分卷序号
				$obookrows[$k]['lastvolume']=$v->getVar('lastvolume');  //分卷名称

				$obookrows[$k]['authorid']=$v->getVar('authorid');  //作者
				$obookrows[$k]['author']=$v->getVar('author');
				$obookrows[$k]['posterid']=$v->getVar('posterid');  //发表者
				$obookrows[$k]['poster']=$v->getVar('poster');
				$obookrows[$k]['agentid']=$v->getVar('agentid');  //代理者
				$obookrows[$k]['agent']=$v->getVar('agent');

				$obookrows[$k]['sortid']=$v->getVar('sortid');  //类别序号
				$obookrows[$k]['sort']=$jieqiSort['obook'][$v->getVar('sortid')]['shortname'];  //类别

				$obookrows[$k]['size']=$v->getVar('size');
				$obookrows[$k]['size_k']=ceil($v->getVar('size')/1024);
				$obookrows[$k]['size_c']=ceil($v->getVar('size')/2);
				$obookrows[$k]['daysale']=$v->getVar('daysale');
				$obookrows[$k]['weeksale']=$v->getVar('weeksale');
				$obookrows[$k]['monthsale']=$v->getVar('monthsale');
				$obookrows[$k]['sumegold']=$v->getVar('sumegold');
				$obookrows[$k]['sumesilver']=$v->getVar('sumesilver');
				$obookrows[$k]['sumemoney']=$obookrows[$k]['sumegold']+$obookrows[$k]['sumesilver'];
				$obookrows[$k]['payprice']=$v->getVar('payprice');
				$obookrows[$k]['allsale']=$v->getVar('allsale');
				$obookrows[$k]['lastupdate']=date('y-m-d',$v->getVar('lastupdate')); //最后更新日期
				$obookrows[$k]['update']=date('m-d',$v->getVar('lastupdate')); //最后更新日期
				$obookrows[$k]['display']=$v->getVar('display');
				$obookrows[$k]['url_image']=jieqi_uploadurl($jieqiConfigs['obook']['imagedir'], $jieqiConfigs['obook']['imageurl'], 'obook', $obook_static_url).jieqi_getsubdir($v->getVar('obookid')).'/'.$v->getVar('obookid').'/'.$v->getVar('obookid').'s'.$jieqiConfigs['obook']['imagetype'];
				$k++;
			}
		}
		$jieqiTpl->assign_by_ref('obookrows', $obookrows);
	}
}
?>