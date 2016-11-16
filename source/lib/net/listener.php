<?php
/**
 * HTTP监听类
 *
 * HTTP监听类
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: listener.php 312 2008-12-29 05:30:54Z juny $
 */

class JieqiRequest_Listener extends JieqiObject { 

    var $_id;

    function JieqiRequest_Listener()
    {
		$this->JieqiObject();
        $this->_id = md5(uniqid('http_request_', 1));
    }


    //获得id
    function getId()
    {
        return $this->_id;
    }


    //触发的监听事件
    function update(&$subject, $event, $data = NULL)
    {
        echo "Notified of event: '$event'\n";
        if (NULL !== $data) {
            echo "Additional data: ";
            var_dump($data);
        }
    }
}
?>