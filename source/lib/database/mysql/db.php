<?php
/**
 * MYSQL数据库类
 *
 * 定义MYSQL数据库的相关操作
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: db.php 324 2009-01-20 04:47:10Z juny $
 */

/**
 * MYSQL数据库类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiMySQLDatabase extends JieqiObject
{

	/**
	 * 数据库连接资源
	 *
	 * @var resource
	 */
	var $conn;

	/**
	 * 构造函数
	 * 
	 * @param      void
	 * @access     private
	 * @return     void
	 */
	function JieqiMySQLDatabase($db=''){
		$this->JieqiObject();
	}

	/**
	 * 连接数据库
	 * 
	 * @param      string     $dbhost 数据库服务器地址
	 * @param      string     $dbuser 数据库用户名
	 * @param      string     $dbpass 数据库密码
	 * @param      string     $dbname 数据库名
	 * @param      bool       $selectdb 是否选中当前数据库
	 * @access     public
	 * @return     bool
	 */
	function connect($dbhost='', $dbuser='', $dbpass='', $dbname='', $selectdb = true){
		if (JIEQI_DB_PCONNECT == 1) $this->conn = @mysql_pconnect($dbhost, $dbuser, $dbpass);
		else $this->conn = @mysql_connect($dbhost, $dbuser, $dbpass);
		if (!$this->conn) return false;
		$this->connectcharset();
		if($selectdb != false){
			if (!mysql_select_db($dbname))  return false;
		}

		return true;
	}
	
	/**
	 * 重新连接数据库
	 * 
	 * @param      void       
	 * @access     public
	 * @return     bool
	 */
	function reconnect(){
		$ret=mysql_ping($this->conn);
		$this->connectcharset();
		return $ret;
	}
	
	/**
	 * 设置连接字符集
	 * 
	 * @param      void       
	 * @access     public
	 * @return     void
	 */
	function connectcharset(){
		$mysql_version = mysql_get_server_info();
		if($mysql_version > '4.1'){
			if(defined('JIEQI_DB_CHARSET')){
				if(JIEQI_DB_CHARSET != 'default') @mysql_query("SET character_set_connection=".JIEQI_DB_CHARSET.", character_set_results=".JIEQI_DB_CHARSET.", character_set_client=binary", $this->conn);
			}else{
				@mysql_query("SET character_set_connection=".JIEQI_SYSTEM_CHARSET.", character_set_results=".JIEQI_SYSTEM_CHARSET.", character_set_client=binary", $this->conn);
			}
		}
		if($mysql_version > '5.0') @mysql_query("SET sql_mode=''", $this->conn);
	}


	/**
	 * 取得下一个id
	 * 
	 * @param      string      $sequence       
	 * @access     public
	 * @return     int
	 */
	function genId($sequence=''){
		return 0;
	}

	/**
	 * 取一行（返回数字索引数组）
	 * 
	 * @param      resource     $result
	 * @access     public
	 * @return     array        返回数字索引数组
	 */
	function fetchRow($result){
		return @mysql_fetch_row($result);
	}

	/**
	 * 取一行（返回文字索引数组）
	 * 
	 * @param      resource     $result
	 * @access     public
	 * @return     array        返回文字索引数组
	 */
	function fetchArray($result){
		return @mysql_fetch_array($result,MYSQL_ASSOC);
	}

	/**
	 * 取得最新插入ID
	 * 
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function getInsertId(){
		return mysql_insert_id($this->conn);
	}

	/**
	 * 返回查询结果行数
	 * 
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function getRowsNum($result){
		return @mysql_num_rows($result);
	}

	/**
	 * 返回查询影响的行数
	 * 
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function getAffectedRows(){
		return mysql_affected_rows($this->conn);
	}

	/**
	 * 关闭连接
	 * 
	 * @param      void
	 * @access     public
	 * @return     void
	 */
	function close(){
		@mysql_close();
		//if(defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0) $this->sqllog('show');
	}

	/**
	 * 释放查询结果
	 * 
	 * @param      void
	 * @access     public
	 * @return     void
	 */
	function freeRecordSet($result){
		return mysql_free_result($result);
	}

	/**
	 * 返回错误信息
	 * 
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function error(){
		return @mysql_error();
	}

	/**
	 * 返回错误代码
	 * 
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function errno(){
		return @mysql_errno();
	}

	/**
	 * 查询字符串特殊字符替换
	 * 
	 * @param      string      $str
	 * @access     public
	 * @return     $str
	 */
	function quoteString($str){
		return "'".jieqi_dbslashes($str)."'";
	}
	
	function sqllog($do = 'add', $sql = ''){
		static $sqllog = array();
		switch($do){
			case 'add':
			if(!empty($sql)) $sqllog[] = $sql;
			break;
			case 'ret':
			return $sqllog;
			break;
			case 'count':
			return count($sqllog);
			break;
			case 'show':
			echo '<br />queries: '.count($sqllog);
			foreach($sqllog as $sql) echo '<br />'.jieqi_htmlstr($sql);
			break;
		}
	}

	/**
	 * 执行一个查询语句
	 * 
	 * @param      string      $sql 查询的SQL
	 * @param      int         $limit 返回行数
	 * @param      int         $start 开始行数
	 * @param      bool        $nobuffer 是否启用nobuffer查询
	 * @access     public
	 * @return     bool
	 */
	function query($sql, $limit=0, $start=0, $nobuffer=false){
		
		if (!empty($limit)) {
			if(empty($start)) $start = 0;
			$sql.=' LIMIT '.(int)$start.', '.(int)$limit;
		}
		/*
		if(preg_match('/(char|outfile|load_file)/is', $sql)){
			$sqllog = "Time: ".date('Y-m-d H:i:s')."\r\nUrl: ";
			$sqllog .= !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
			$sqllog .= "\r\nSql: ".$sql."\r\n\r\n";
			jieqi_checkdir(JIEQI_COMPILED_PATH.'/templates', true);
			jieqi_writefile(JIEQI_COMPILED_PATH.'/templates/sqllog.txt', $sqllog, 'ab');
		}
		*/
		if(defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0) $this->sqllog('add', $sql);
		if($nobuffer) $result = mysql_unbuffered_query($sql, $this->conn);
		else $result = mysql_query($sql, $this->conn);
		if ($result) return $result;
		else{
			//如果是因为时间长了断开连接，自动重连并重新查询
			if(mysql_errno($this->conn) == 2013){
				$this->reconnect();
				if($nobuffer) $result = mysql_unbuffered_query($sql, $this->conn);
				else $result = mysql_query($sql, $this->conn);
				if ($result) return $result;
			}
			if(defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0){
				jieqi_printfail('SQL: '.jieqi_htmlstr($sql).'<br /><br />ERROR: '.mysql_error($this->conn).'('.mysql_errno($this->conn).')');
			}
			return false;
		}
	}
}
?>