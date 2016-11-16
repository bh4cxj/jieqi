<?php 
/**
 * 数据表类(jieqi_article_bookcase - 书架表)
 *
 * 数据表类(jieqi_article_bookcase - 书架表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: bookcase.php 230 2008-11-27 08:46:07Z juny $
 */

jieqi_includedb();
//用户类
class JieqiBookcase extends JieqiObjectData
{
	//构建函数
	function JieqiBookcase()
	{
		$this->JieqiObjectData();
		$this->initVar('caseid', JIEQI_TYPE_INT, 0, '书架序号', false, 11);
		$this->initVar('articleid', JIEQI_TYPE_INT, 0, '文章序号', true, 11);
		$this->initVar('articlename', JIEQI_TYPE_TXTBOX, '', '文章名称', false, 250);
		$this->initVar('classid', JIEQI_TYPE_INT, 0, '分类序号', false, 3);
		$this->initVar('userid', JIEQI_TYPE_INT, 0, '用户序号', true, 11);
        $this->initVar('username', JIEQI_TYPE_TXTBOX, '', '用户名', false, 30);
        $this->initVar('chapterid', JIEQI_TYPE_INT, 0, '章节序号', false, 11);
        $this->initVar('chaptername', JIEQI_TYPE_TXTBOX, '', '章节名称', false, 250);
        $this->initVar('chapterorder', JIEQI_TYPE_INT, 0, '章节次序', false, 6);
		$this->initVar('joindate', JIEQI_TYPE_INT, 0, '收藏日期', false, 11);
		$this->initVar('lastvisit', JIEQI_TYPE_INT, 0, '最后访问', false, 11);
		$this->initVar('flag', JIEQI_TYPE_INT, 0, '标志', false, 1);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiBookcaseHandler extends JieqiObjectHandler
{
	function JieqiBookcaseHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='bookcase';
	    $this->autoid='caseid';	
	    $this->dbname='article_bookcase';
	}
}

?>