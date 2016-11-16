<?php 
/**
 * 数据表类(jieqi_article_avote - 文章投票调查表)
 *
 * 数据表类(jieqi_article_avote - 文章投票调查表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: avote.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();
//书评
class JieqiAvote extends JieqiObjectData
{
    //构建函数
    function JieqiAvote()
    {
        $this->JieqiObjectData();
        $this->initVar('voteid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('articleid', JIEQI_TYPE_INT, 0, '文章序号', true, 11);
        $this->initVar('posterid', JIEQI_TYPE_INT, 0, '发布人序号', false, 11);
        $this->initVar('poster', JIEQI_TYPE_TXTBOX, '', '发布人', false, 30);    
        $this->initVar('posttime', JIEQI_TYPE_INT, 0, '发表时间', false, 11);
        $this->initVar('title', JIEQI_TYPE_TXTBOX, '', '投票标题', true, 100);
        $this->initVar('item1', JIEQI_TYPE_TXTBOX, '', '选项1', false, 100);
        $this->initVar('item2', JIEQI_TYPE_TXTBOX, '', '选项2', false, 100);
        $this->initVar('item3', JIEQI_TYPE_TXTBOX, '', '选项3', false, 100);
        $this->initVar('item4', JIEQI_TYPE_TXTBOX, '', '选项4', false, 100);
        $this->initVar('item5', JIEQI_TYPE_TXTBOX, '', '选项5', false, 100);
        $this->initVar('item6', JIEQI_TYPE_TXTBOX, '', '选项6', false, 100);
        $this->initVar('item7', JIEQI_TYPE_TXTBOX, '', '选项7', false, 100);
        $this->initVar('item8', JIEQI_TYPE_TXTBOX, '', '选项8', false, 100);
        $this->initVar('item9', JIEQI_TYPE_TXTBOX, '', '选项9', false, 100);
        $this->initVar('item10', JIEQI_TYPE_TXTBOX, '', '选项10', false, 100);
        $this->initVar('useitem', JIEQI_TYPE_INT, 0, '有效选项', false, 2);
        $this->initVar('description', JIEQI_TYPE_TXTAREA, '', '描述', false, NULL);
        $this->initVar('ispublish', JIEQI_TYPE_INT, 0, '是否发布', false, 1);
        $this->initVar('mulselect', JIEQI_TYPE_INT, 0, '是否允许多选', false, 1);
        $this->initVar('timelimit', JIEQI_TYPE_INT, 0, '是否限制时间', false, 1);
        $this->initVar('needlogin', JIEQI_TYPE_INT, 0, '是否需要登录', false, 1);
        $this->initVar('starttime', JIEQI_TYPE_INT, 0, '起始时间', false, 11);
        $this->initVar('endtime', JIEQI_TYPE_INT, 0, '结束时间', false, 11);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiAvoteHandler extends JieqiObjectHandler
{
	function JieqiAvoteHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='avote';
	    $this->autoid='voteid';	
	    $this->dbname='article_avote';
	}
}

?>