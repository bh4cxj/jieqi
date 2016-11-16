<?php 
/**
 * 数据表类(jieqi_group_groupposts - 帖子内容表)
 *
 * 数据表类(jieqi_group_groupposts - 帖子内容表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: lee $
 * @version    $Id: groupposts.php 329 2009-02-07 01:21:38Z lee $
 */

jieqi_includedb();
include_once(JIEQI_ROOT_PATH.'/class/posts.php');
class JieqiGroupposts extends JieqiPosts
{
    //构建函数
    function JieqiGroupposts()
    {
        $this->JieqiPosts();
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiGrouppostHandler extends JieqiObjectHandler
{
	function JieqiGrouppostHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='groupposts';
	    $this->autoid='postid';	
	    $this->dbname='group_posts';
	}
}

?>