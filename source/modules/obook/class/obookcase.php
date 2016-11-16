<?php 
/**
 * 数据表类(jieqi_obook_obookcase - 电子书书架)
 *
 * 数据表类(jieqi_obook_obookcase - 电子书书架)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obookcase.php 231 2008-11-27 08:46:26Z juny $
 */

jieqi_includedb();
//用户类
class JieqiObookcase extends JieqiObjectData
{
	//构建函数
	function JieqiObookcase()
	{
		$this->JieqiObjectData();
		$this->initVar('ocaseid', JIEQI_TYPE_INT, 0, '书架序号', false, 11);
		$this->initVar('obookid', JIEQI_TYPE_INT, 0, '电子书序号', true, 11);
		$this->initVar('articleid', JIEQI_TYPE_INT, 0, '文章序号', false, 11);
		$this->initVar('obookname', JIEQI_TYPE_TXTBOX, '', '电子书名称', false, 250);
		$this->initVar('userid', JIEQI_TYPE_INT, 0, '用户序号', true, 11);
        $this->initVar('username', JIEQI_TYPE_TXTBOX, '', '用户名', false, 30);
        $this->initVar('ochapterid', JIEQI_TYPE_INT, 0, '章节序号', false, 11);
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
class JieqiObookcaseHandler extends JieqiObjectHandler
{
	function JieqiObookcaseHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='obookcase';
	    $this->autoid='ocaseid';	
	    $this->dbname='obook_obookcase';
	}
}

?>