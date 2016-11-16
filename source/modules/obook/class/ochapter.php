<?php 
/**
 * 数据表类(jieqi_obook_ochapter - 电子书章节)
 *
 * 数据表类(jieqi_obook_ochapter - 电子书章节)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ochapter.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();

class JieqiOchapter extends JieqiObjectData
{
    //构建函数
    function JieqiOchapter()
    {
        $this->JieqiObjectData();
        $this->initVar('ochapterid', JIEQI_TYPE_INT, 0, '章节序号', false, 11);
        $this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
        $this->initVar('obookid', JIEQI_TYPE_INT, 0, '电子书序号', false, 11);
        $this->initVar('postdate', JIEQI_TYPE_INT, 0, '加入日期', false, 11);
        $this->initVar('lastupdate', JIEQI_TYPE_INT, 0, '更新日期', false, 11);
        $this->initVar('buytime', JIEQI_TYPE_INT, 0, '最后购买日期', false, 11);
        $this->initVar('obookname', JIEQI_TYPE_TXTBOX, '', '电子书名', true, 100);
        $this->initVar('chaptername', JIEQI_TYPE_TXTBOX, '', '章节名', true, 100);
        $this->initVar('chaptertype', JIEQI_TYPE_INT, 0, '章节类型', false, 1);
        $this->initVar('chapterorder', JIEQI_TYPE_INT, 0, '章节排序', false, 6);
        $this->initVar('volumeid', JIEQI_TYPE_INT, 0, '分卷序号', false, 11);
        $this->initVar('ointro', JIEQI_TYPE_TXTAREA, '', '内容简介', false, NULL);
        $this->initVar('size', JIEQI_TYPE_INT, 0, '字数', false, 11);
        $this->initVar('posterid', JIEQI_TYPE_INT, 0, '发表者序号', false, 11);
        $this->initVar('poster', JIEQI_TYPE_TXTBOX, '', '发表者', false, 50);
        $this->initVar('toptime', JIEQI_TYPE_INT, 0, '置顶时间', false, 11);
        $this->initVar('picflag', JIEQI_TYPE_INT, 0, '图片标志', false, 1);      
        $this->initVar('saleprice', JIEQI_TYPE_INT, 0, '销售价格', false, 11);
        $this->initVar('vipprice', JIEQI_TYPE_INT, 0, '优惠价格', false, 11);
        $this->initVar('sumegold', JIEQI_TYPE_INT, 0, '金币总销售额', false, 11);
        $this->initVar('sumesilver', JIEQI_TYPE_INT, 0, '银币总销售额', false, 11);
        $this->initVar('normalsale', JIEQI_TYPE_INT, 0, '普通价格销售量', false, 11);
        $this->initVar('vipsale', JIEQI_TYPE_INT, 0, 'VIP价格销售量', false, 11);
        $this->initVar('freesale', JIEQI_TYPE_INT, 0, '免费阅读销售量', false, 11);
        $this->initVar('bespsale', JIEQI_TYPE_INT, 0, '包月阅读销售量', false, 11);
        $this->initVar('totalsale', JIEQI_TYPE_INT, 0, '合计销售量', false, 11);
        $this->initVar('daysale', JIEQI_TYPE_INT, 0, '本日销售量', false, 11);
        $this->initVar('weeksale', JIEQI_TYPE_INT, 0, '本周销售量', false, 11);
        $this->initVar('monthsale', JIEQI_TYPE_INT, 0, '本月销售量', false, 11);
        $this->initVar('allsale', JIEQI_TYPE_INT, 0, '总销售量', false, 11);
        $this->initVar('lastsale', JIEQI_TYPE_INT, 0, '最后销售时间', false, 11);
        $this->initVar('canvip', JIEQI_TYPE_INT, 0, '是否允许VIP阅读', false, 1);
        $this->initVar('canfree', JIEQI_TYPE_INT, 0, '是否允许免费阅读', false, 1);
        $this->initVar('canbesp', JIEQI_TYPE_INT, 0, '是否允许包月阅读', false, 1);
        $this->initVar('state', JIEQI_TYPE_INT, 0, '状态', false, 1);
        $this->initVar('flag', JIEQI_TYPE_INT, 0, '标志', false, 1);
        $this->initVar('display', JIEQI_TYPE_INT, 0, '是否显示', false, 1);
    }
    
    function getSalestatus($display=''){
    	global $jieqiLang;
    	jieqi_loadlang('obook', 'obook');
    	if($display=='') $display=$this->getVar('display', 'n');
    	switch($display){
    		case 1:
    		return $jieqiLang['obook']['obook_status_noauth'];
    		case 2:
    		return $jieqiLang['obook']['obook_status_unsale'];
    		case 0:
    		default:
    		return $jieqiLang['obook']['obook_status_sale'];
    	}
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiOchapterHandler extends JieqiObjectHandler
{
	
	function JieqiOchapterHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='ochapter';
	    $this->autoid='ochapterid';	
	    $this->dbname='obook_ochapter';
	}
}

?>