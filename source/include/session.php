<?php
/**
 * SESSION处理
 *
 * SESSION处理
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: session.php 243 2008-11-28 02:59:57Z juny $
 */

jieqi_includedb();
/**
 * session处理类（session保存在数据库的情况）
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiSessionHandler extends JieqiObjectHandler
{
    function open($save_path, $session_name)
	{
        return true;
    }

    function close()
	{
        return true;
    }

    function read($sess_id)
	{
        $sql = "SELECT sess_data FROM ".jieqi_dbprefix('system_session')." WHERE sess_id = '$sess_id'";
        if (false != $result = $this->db->query($sql)) {
            if (list($sess_data) = $this->db->fetchRow($result)) {
                return $sess_data;
            }
        }
        return '';
    }

    function write($sess_id, $sess_data)
	{
		list($count) = $this->db->fetchRow($this->db->query("SELECT COUNT(*) FROM ".jieqi_dbprefix('system_session')." WHERE sess_id='".$sess_id."'"));
        if ( $count > 0 ) {
			$sql = sprintf("UPDATE %s SET sess_updated = %u, sess_data = '%s' WHERE sess_id = '%s'", jieqi_dbprefix('system_session'), JIEQI_NOW_TIME, jieqi_dbslashes($sess_data), $sess_id);
        } else {
			$sql = sprintf("INSERT INTO %s (sess_id, sess_updated, sess_data) VALUES ('%s', %u, '%s')", jieqi_dbprefix('system_session'), $sess_id, JIEQI_NOW_TIME, jieqi_dbslashes($sess_data));
        }
		if (!$this->db->query($sql)) {
            return false;
        }
		return true;
    }

    function destroy($sess_id)
    {
		$sql = sprintf("DELETE FROM %s WHERE sess_id = '%s'", jieqi_dbprefix('system_session'), $sess_id);
        if ( !$result = $this->db->query($sql) ) {
            return false;
        }
        return true;
    }

    function gc($expire)
    {
        $mintime = JIEQI_NOW_TIME - intval($expire);
		$sql = sprintf("DELETE FROM %s WHERE sess_updated < %u", jieqi_dbprefix('system_session'), $mintime);
        $this->db->query($sql);
    }
}
?>