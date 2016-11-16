<?php
/**
 * type(提取的类别)(0,不限制,1.待解决2.已解决3.关闭4.过期)
 * typeid(提取的分类)(动漫,动画,0不限制)
 * order(排序方式)(addtime 开始时间 overtime介绍时间 score 分数)
 * desc(顺序)(desc.asc)
 * num(提取的数量)(数字)
 * catpage(是否分页)(1.0)
 * user(是否只是当前用户的信息)(1.0.3直接当前用户参与的问题)
 * typename 该分类的唯一标示名称(可以为空，但是不可以更改默认名称)
 * iscache 0 不缓存 1缓存
 */
class BlockQuizQuizList extends JieqiBlock
{
	var $blockvars=array();  //区块参数
	var $exevars=array('type'=>'0','typeid'=>'0','order'=>'addtime','desc'=>'desc','num'=>'10','catpage'=>'0','user'=>'0');//默认配置
	var $template='block_quizlist.html';
	var $cachetime=JIEQI_CACHE_LIFETIME;

	function BlockQuizQuizList($vars)
	{
		$this->JieqiBlock($vars);
		if($this->blockvars['template']!=''){$this->template=$this->blockvars['template'];}
		if(!empty($this->blockvars['vars']))
		{
			$varary=explode(',', trim($this->blockvars['vars']));
			$this->exevars['type'] = trim($varary['0']);
			$this->exevars['typeid'] = trim($varary['1']);
			$this->exevars['order'] = trim($varary['2']);
			$this->exevars['desc'] = trim($varary['3']);
			$this->exevars['num'] = trim($varary['4']);
			$this->exevars['catpage'] = trim($varary['5']);
			$this->exevars['user'] = trim($varary['6']);
			$this->exevars['typename'] = trim($varary['7']);
			$this->exevars['iscache'] = trim($varary['8']);
			$this->exevars['iscache']==''?1:$this->exevars['iscache'];

			global $jieqiTpl;
			global $jieqiConfigs;
			global $jieqiSort;
			global $Blocks;
			global $jieqiModules;
			jieqi_getconfigs('quiz','update','Blocks');
			if(!is_object($jieqiTpl)) $jieqiTpl =& JieqiTpl::getInstance();
			$cacheid=$this->blockvars['bid'];
			//$Blocks[$this->exevars['typename']];更新时间
			if($this->exevars['catpage']=='0')
			{
				$this->blockvars['overtime'] = $Blocks[$this->exevars['typename']];
				$this->blockvars['cacheid']=$this->blockvars['bid'];
				if(!empty($this->blockvars['template'])){
					$this->blockvars['template']=$this->template;
				}
				$this->blockvars['tlpfile'] = $jieqiModules['quiz']['path'].'/templates/blocks/'.$this->template;
			}
			else
			{
				$this->blockvars['overtime'] = $Blocks[$this->exevars['typename']];
				$page=(int)$_REQUEST['page'];
				$page=$page=='' | $page==0?1:$page;
				$this->blockvars['overtime'] = $Blocks[$this->exevars['typename']];
				$this->blockvars['cacheid']=$this->blockvars['bid'].$page;
				if(!empty($this->blockvars['template'])){
					$this->blockvars['template']=$this->template;
				}
				$this->blockvars['tlpfile'] = $jieqiModules['quiz']['path'].'/templates/blocks/'.$this->template;

			}
		}
	}

	function setContent()
	{
		global $jieqiTpl;
		global $jieqiConfigs;
		global $jieqiSort;

		jieqi_includedb();
		$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
		$sql='select * from '.jieqi_dbprefix('quiz_problems').' where 1 ';
		if($this->exevars['type']!=0)//组成SQL语句
		{
			if($this->exevars['type']==4)
			{
				$sql.=' and typez = 1 and overtime < '.time();
			}
			else
			{
				if($this->exevars['type']==3)
				{
					$sql.=' and typez = 0 ';
				}
				else
				{
					$sql.=' and typez = '.$this->exevars['type'];
				}
				if($this->exevars['type']==1)
				{
					$sql.=' and overtime >'.time();
				}
			}
		}
		if($this->exevars['typeid']!='0')
		{
			$sql.=' and typeid = \''.$this->exevars['typeid'].'\' ';
		}

		//echo $this->exevars['user'];exit;
		if($this->exevars['user']!=0)
		{
			@include_once(JIEQI_ROOT_PATH.'/module/quiz/include/usertype.php');
			$usertype = usertype::getInstance('usertype');
			if($this->exevars['user']==1)
			{
				if(!$usertype -> isuser()){jieqi_jumppage(JIEQI_URL.'/login.php',$jieqiLang['quiz']['notusertitle'],$jieqiLang['quiz']['notuser']);}
				$sql.=' and username = \''.$usertype->get('username').'\'';
			}
			elseif($this->exevars['user']==3)
			{
				if(!$usertype -> isuser()){jieqi_jumppage(JIEQI_URL.'/login.php',$jieqiLang['quiz']['notusertitle'],$jieqiLang['quiz']['notuser']);}
				$sql='select p.* from '.jieqi_dbprefix('quiz_answer').' a,'.jieqi_dbprefix('quiz_problems').' p where a.username= \''.$usertype->get('username').'\' and a.problemid=p.quizid';
			}
		}
		if($this->exevars['order'])
		{
			$sql.=' order by '.$this->exevars['order'].' '.$this->exevars['desc'];
		}
		if($this->exevars['catpage'])
		{
			$page=(int)$_REQUEST['page'];
			$page=$page=='' | $page==0?1:$page;
			//分页变量获取
			$newsql=str_replace('*','count(*)',$sql);
			$newrow=$query->getRow($query->execute($newsql));
			$newrow=$newrow['count(*)'];
			include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
			$jumppage = new JieqiPage($newrow,$this->exevars['num'],$page,10);
			$pages=$jumppage->whole_bar();
			$jieqiTpl->assign('pages',$pages);
			$sql.=' limit '.($page-1)*$this->exevars['num'].','.$this->exevars['num'];
		}
		else
		{
			$sql.=' limit 0 , '.$this->exevars['num'];
		}
		$row=$query->execute($sql);
		$quizarry=array();
		while ($v = $query->getRow())
		{
			$quizarry[]=$v;
		}

		$jieqiTpl->assign_by_ref('quizarry',$quizarry);
		global $linkurl;
		if($this->exevars['type']==1)//判断链接指向地址
		{
			$jieqiTpl->assign('url_more',$linkurl.'/problems_more.php');
		}
		elseif($this->exevars['type']==2)
		{
			$jieqiTpl->assign('url_more',$linkurl.'/problems_end_more.php');
		}
	}
}
?>