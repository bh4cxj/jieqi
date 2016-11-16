<?php 
/**
 * 数据表类(jieqi_article_avstat - 文章投票结果表)
 *
 * 数据表类(jieqi_article_avstat - 文章投票结果表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: avstat.php 230 2008-11-27 08:46:07Z juny $
 */

jieqi_includedb();
//书评
class JieqiAvstat extends JieqiObjectData
{
    //构建函数
    function JieqiAvstat()
    {
        $this->JieqiObjectData();
        $this->initVar('statid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('voteid', JIEQI_TYPE_INT, 0, '投票序号', false, 11);
        $this->initVar('statall', JIEQI_TYPE_INT, 0, '总票数', false, 11);
        $this->initVar('stat1', JIEQI_TYPE_INT, 0, '得票1', false, 11);
        $this->initVar('stat2', JIEQI_TYPE_INT, 0, '得票2', false, 11);
        $this->initVar('stat3', JIEQI_TYPE_INT, 0, '得票3', false, 11);
        $this->initVar('stat4', JIEQI_TYPE_INT, 0, '得票4', false, 11);
        $this->initVar('stat5', JIEQI_TYPE_INT, 0, '得票5', false, 11);
        $this->initVar('stat6', JIEQI_TYPE_INT, 0, '得票6', false, 11);
        $this->initVar('stat7', JIEQI_TYPE_INT, 0, '得票7', false, 11);
        $this->initVar('stat8', JIEQI_TYPE_INT, 0, '得票8', false, 11);
        $this->initVar('stat9', JIEQI_TYPE_INT, 0, '得票9', false, 11);
        $this->initVar('stat10', JIEQI_TYPE_INT, 0, '得票10', false, 11);
        $this->initVar('canstat', JIEQI_TYPE_INT, 0, '是否统计', false, 1);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiAvstatHandler extends JieqiObjectHandler
{
	function JieqiAvstatHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='avstat';
	    $this->autoid='voteid';	
	    $this->dbname='article_avstat';
	}
}

?>