<?php 
/**
 * 数据表类(jieqi_article_searchcache - 搜索缓存)
 *
 * 数据表类(jieqi_article_searchcache - 搜索缓存)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: searchcache.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();
//用户类
class JieqiSearchcache extends JieqiObjectData
{
	//构建函数
	function JieqiSearchcache()
	{
		$this->JieqiObjectData();
		$this->initVar('cacheid', JIEQI_TYPE_INT, 0, '序号', false, 11);
		$this->initVar('searchtime', JIEQI_TYPE_INT, 0, '搜索时间', false, 11);
		$this->initVar('hashid', JIEQI_TYPE_TXTBOX, '', '搜索序号', false, 32);
        $this->initVar('keywords', JIEQI_TYPE_TXTBOX, '', '搜索关键字', false, 60);
		$this->initVar('searchtype', JIEQI_TYPE_INT, 0, '搜索方式', false, 1);
		$this->initVar('results', JIEQI_TYPE_INT, 0, '搜索结果数', false, 11);
		$this->initVar('aids', JIEQI_TYPE_TXTAREA, '', '搜索结果文章id', false, NULL);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiSearchcacheHandler extends JieqiObjectHandler
{
	function JieqiSearchcacheHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='searchcache';
	    $this->autoid='cacheid';	
	    $this->dbname='article_searchcache';
	}
}

?>