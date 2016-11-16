<?php
/**
 * SQLITE数据库类
 *
 * 定义SQLITE数据库的相关操作
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: db.php 198 2008-11-25 05:38:31Z juny $
 */


/**
 * SQLITE数据库类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiSQLiteDatabase extends JieqiObject
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
	function JieqiSQLiteDatabase($db=''){
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
		if (JIEQI_DB_PCONNECT == 1) {
			$this->conn = @sqlite_open($dbname, 0666, $sqliteerror);
		} else {
			$this->conn = @sqlite_popen($dbname, 0666, $sqliteerror);
		}
	
		if (!$this->conn)  return false;	
		else return true;
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
		return @sqlite_fetch_array($result,SQLITE_NUM);
	}

	/**
	 * 取一行（返回文字索引数组）
	 * 
	 * @param      resource     $result
	 * @access     public
	 * @return     array        返回文字索引数组
	 */
	function fetchArray($result){
		return @sqlite_fetch_array($result,SQLITE_ASSOC);
	}

	/**
	 * 取得最新插入ID
	 * 
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function getInsertId()
	{
		return sqlite_last_insert_rowid($this->conn);
	}

	/**
	 * 返回查询结果行数
	 * 
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function getRowsNum($result){
		return @sqlite_num_rows($result);
	}

	/**
	 * 返回查询影响的行数
	 * 
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function getAffectedRows(){
		return sqlite_changes($this->conn);
	}

	/**
	 * 关闭连接
	 * 
	 * @param      void
	 * @access     public
	 * @return     void
	 */
	function close(){
		@sqlite_close($this->conn);
	}

	/**
	 * 释放查询结果
	 * 
	 * @param      void
	 * @access     public
	 * @return     void
	 */
	function freeRecordSet($result){
		return true;
	}

	/**
	 * 返回错误信息
	 * 
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function error()
	{
		$errno=@sqlite_last_error($this->conn);
		if(!empty($errno)) return @sqlite_error_string($errno);
		else return '';
	}

	/**
	 * 返回错误代码
	 * 
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function errno(){
		return @sqlite_last_error($this->conn);
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
		if ( !empty($limit) ) {
			if (empty($start)) {
				$start = 0;
			}
			$sql = $sql. ' LIMIT '.(int)$start.', '.(int)$limit;
		}
		//slqite的sql里面转义 \' -> '', \" -> ", \\ -> \
		$sql=str_replace(array('\\\'', '\"', '\\\\'),array('\'\'', '"', '\\'),$sql);

		if($nobuffer) $result = sqlite_unbuffered_query($sql, $this->conn);
		else $result = sqlite_query($sql, $this->conn);

		if ( $result ) {
			if(!$result) $this->raiseError('SQL: '.$sql, JIEQI_ERROR_RETURN);
			return $result;
        } else {
        	$this->raiseError('SQL: '.$sql, JIEQI_ERROR_RETURN);
			return false;
        }
    }

    /**
	 * 列出一个数据库内所有表
	 * 
	 * @param      void
	 * @access     public
	 * @return     array
	 */
    function list_tables(){
		if (function_exists ('sqlite_list_tables')) {
			return sqlite_list_tables();
		}else{
			$tables = array ();
            $sql = "SELECT name FROM sqlite_master WHERE (type = 'table')";
            if ($res = sqlite_query ($this->conn, $sql)) {
                while (sqlite_has_more($res)) {
                   $tables[] = sqlite_fetch_single($res);
                }
            }
           return $tables;
       }
    }

     /**
	 * 判断一个表是否存在
	 * 
	 * @param      string      $table 表名
	 * @access     public
	 * @return     bool
	 */
    function table_exists($table){
        if (function_exists ('sqlite_table_exists')) {
			return sqlite_table_exists($this->conn, $table);
		}else{
            $sql = "SELECT count(name) FROM sqlite_master WHERE ((type = 'table') and (name = '$table'))";
           if ($res = sqlite_query ($this->conn, $sql)) {
               return sqlite_fetch_single($res)>0;
           } else {
               return false; 
           }
        }
    }

}
?>