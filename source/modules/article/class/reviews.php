<?php 
jieqi_includedb();
include_once(JIEQI_ROOT_PATH.'/class/topics.php');
//书评
class JieqiReviews extends JieqiTopics
{
    //构建函数
    function JieqiReviews()
    {
        $this->JieqiTopics();
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiReviewsHandler extends JieqiObjectHandler
{
	function JieqiReviewsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='reviews';
	    $this->autoid='topicid';	
	    $this->dbname='article_reviews';
	}
}

?>