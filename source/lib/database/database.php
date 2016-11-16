<?php
/**
 * 数据库基本类定义
 *
 * 定义基本的数据库处理函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: database.php 318 2009-01-09 04:58:56Z juny $
 */

/**
 * 数据库对象类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiDatabase extends JieqiObject
{
	/**
	 * 构造函数
	 * 
	 * @param      void       
	 * @access     private
	 * @return     void
	 */
	function JieqiDatabase(){
		$this->JieqiObject();
	}
	
	/**
	 * 返回静态实例变量的引用
	 * 
	 * @param      void       
	 * @access     private
	 * @return     array
	 */
	function &retInstance(){
		static $instance = array();
		return $instance;
	}
	
	/**
	 * 返回静态实例变量的引用
	 * 
	 * @param      void       
	 * @access     private
	 * @return     array
	 */
	function close($db = NULL){
		if(is_object($db)){
			$db->close();
		}else{
			$instance =& JieqiDatabase::retInstance();
			if(!empty($instance)){
				foreach($instance as $db){
					$db->close();
				}
			}
		}
	}

	/**
	 * 根据数据库类型创建一个类
	 * 
	 * @param      string     $dbtype 数据库类型
	 * @param      string     $dbhost 数据库服务器地址
	 * @param      string     $dbuser 数据库用户名
	 * @param      string     $dbpass 数据库密码
	 * @param      string     $dbname 数据库名
	 * @param      bool       $getnew 其否强制创建新实例，默认否
	 * @access     public
	 * @return     object     返回数据库实例
	 */
	function &getInstance($dbtype='', $dbhost='', $dbuser='', $dbpass='', $dbname='', $getnew=false){
		$instance =& JieqiDatabase::retInstance();

		if (empty($dbtype)) $dbtype=JIEQI_DB_TYPE;
		if (empty($dbhost)) $dbhost=JIEQI_DB_HOST;
		if (empty($dbuser)) $dbuser=JIEQI_DB_USER;
		if (empty($dbpass)) $dbpass=JIEQI_DB_PASS;
		if (empty($dbname)) $dbname=JIEQI_DB_NAME;
		
		$inskey = md5($dbtype.','.$dbhost.','.$dbuser.','.$dbpass.','.$dbname);

		$getnew = ($dbtype ==JIEQI_DB_TYPE && $dbhost == JIEQI_DB_HOST && $dbuser == JIEQI_DB_USER && $dbpass == JIEQI_DB_PASS && $dbname == JIEQI_DB_NAME) ? false : true;

		if(!isset($instance[$inskey]) || $getnew){
			switch($dbtype) {
				case 'mysql':
					require_once('mysql/db.php');
					if($getnew) $db = new JieqiMySQLDatabase();
					else $instance[$inskey] = new JieqiMySQLDatabase();
					break;
				case 'sqlite':
					require_once('sqlite/db.php');
					if($getnew) $db = new JieqiSQLiteDatabase();
					else $instance[$inskey] = new JieqiSQLiteDatabase();
					break;
				default:
					jieqi_printfail('The database type ('.$dbtype.') is not exists!');
					return false;
			}

			if($getnew){
				if (!$db->connect($dbhost, $dbuser, $dbpass, $dbname)) {
					jieqi_printfail('Can not connect to database!<br /><br />error: '.$db->error());
					return false;
				}else{
					return $db;
				}
			}else{
				if (!$instance[$inskey]->connect($dbhost, $dbuser, $dbpass, $dbname)) {
					jieqi_printfail('Can not connect to database!<br /><br />error: '.$instance[$inskey]->error());
					return false;
				}
			}
		}
		if(!defined('JIEQI_DB_CONNECTED')) @define('JIEQI_DB_CONNECTED',true);
		return $instance[$inskey];
	}
}

/**
 * 数据表对象类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiObjectData extends JieqiObject{

	/**
	 * 保存时新整数据还是更新数据
	 */
	var $_isNew = false;

	/**
	 * 构造函数
	 * 
	 * @param      void       
	 * @access     private
	 * @return     void
	 */
	function JieqiObjectData(){
		$this->JieqiObject();
	}

	/**
	 * 设置对象为新建状态
	 * 
	 * @param      void       
	 * @access     public
	 * @return     void
	 */
	function setNew(){
		$this->_isNew = true;
	}
	
	/**
	 * 设置对象为非新建状态
	 * 
	 * @param      void       
	 * @access     public
	 * @return     void
	 */
	function unsetNew(){
		$this->_isNew = false;
	}
	
	/**
	 * 判断对象是否为新建状态
	 * 
	 * @param      void       
	 * @access     public
	 * @return     bool
	 */
	function isNew(){
		return $this->_isNew;
	}

	/**
	* 初始化变量
	* 
	* @param      string      $key
	* @param      int         $type  设置数据类型 (如果文本需要过滤，或者其他类型则设置为 JIEQI_TYPE_OTHER)
	* @param      mixed       $value 值
	* @param      bool        $required  需要输入
	* @param      int         $maxlength  对 JIEQI_TYPE_TXTBOX 类型设置最大长度
	* @param      bool        $isdirty  数据是否被修改过
	* @access     public
	* @return     bool
	*/
	function initVar($key, $type, $value = NULL, $caption = '', $required = false, $maxlength = NULL, $isdirty=false){
		$this->vars[$key] = array('type' => $type, 'value' => $value, 'caption' => $caption, 'required' => $required, 'maxlength' => $maxlength, 'isdirty' => $isdirty, 'default'=>'', 'options'=>'');
	}
	
	/**
	* 设置可选项
	* 
	* @access     public
	* @param      string      $key 变量名
	* @param      array       $value 可选数组
	* @access     public
	* @return     bool
	*/
	function setOptions($key, $options){
		$this->vars[$key]['options'] = $options;
	}

	/**
	* 设置变量
	* 
	* @access     public
	* @param      string      $key 变量名
	* @param      mixed       $value 变量值
	* @access     public
	* @return     bool
	*/
	function setVar($key, $value, $isdirty = true){
		if (!empty($key) && isset($value)) {
			if(!isset($this->vars[$key])){
				$this->initVar($key, JIEQI_TYPE_TXTBOX);
			}
			$this->vars[$key]['value'] = $value;
			$this->vars[$key]['isdirty'] = $isdirty;
		}
	}

	/**
	* 批量设置变量
	* 
	* @param      array       $var_arr
	* @access     public
	* @return     bool
	*/
	function setVars($var_arr, $isdirty = false){
		if(is_array($var_arr)){
			foreach ($var_arr as $key => $value) {
				$this->setVar($key, $value, $isdirty);
			}
		}
	}

	/**
	* 取得变量
	* 
	* @param      void
	* @access     public
	* @return     array
	*/
	function getVars($format = ''){
		if(in_array($format, array('s', 'e', 'q', 't', 'o', 'n'))){
			$ret = array();
			foreach($this->vars as $k=>$v){
				$ret[$k] = $this->getVar($k, $fotmat);
			}
			return $ret;
		}else{
			return $this->vars;
		}
	}

	/**
	* 返回合格式化后变量
	*
	* @param      string      $key 键值
	* @param      string      $format 格式化
	* @access     public
	* @return     mixed       格式化后的值
	*/
	function getVar($key, $format = 's'){
		if (isset($this->vars[$key]['value'])) {
			if(is_string($this->vars[$key]['value'])){
				switch (strtolower($format)) {
					case 's':
						return jieqi_htmlstr($this->vars[$key]['value']);
					case 'e':
						return preg_replace("/&amp;#(\d+);/isU", "&#\\1;", htmlspecialchars($this->vars[$key]['value'], ENT_QUOTES));
					case 'q':
						return jieqi_dbslashes($this->vars[$key]['value']);
					case 't':
						return $this->vars[$key]['caption'];
					case 'o':
						return !empty($this->vars[$key]['options'][$this->vars[$key]['value']]) ? $this->vars[$key]['options'][$this->vars[$key]['value']] : '';
					case 'n':
					default:
						return $this->vars[$key]['value'];
				}
			}else return $this->vars[$key]['value'];
		}else{
			return false;
		}
	}

}


/**
 * 数据库查询句柄类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiQueryHandler extends JieqiObject{
	/**
	 * 数据库对象
	 *
	 * @var object
	 */
	var $db;
	/**
	 * 查询结果资源
	 *
	 * @var resource
	 */
	var $sqlres;
	
	/**
	 * 构造函数
	 *
	 * @param      object      $db 数据库对象
	 * @access     private
	 * @return     void
	 */
	function JieqiQueryHandler($db=''){
		$this->JieqiObject();
		if (empty($db) || !is_object($db)) {
			$this->db =& JieqiDatabase::getInstance();
		} else {
			$this->db = &$db;
		}
	}

	/**
	 * 设置数据库
	 *
	 * @param      object      $db 数据库对象
	 * @access     public
	 * @return     void
	 */
	function setdb($db){
		$this->db = &$db;
	}

	/**
	 * 取得当前数据库对象
	 *
	 * @param      void
	 * @access     public
	 * @return     object
	 */
	function getdb(){
		return 	$this->db;
	}

	/**
	 * 执行一个查询
	 *
	 * @param      mixed       $criteria 查询字符串或者查询对象
	 * @param      bool        $full criteria->getSql()是否返回完整查询字符串
	 * @param      bool        $nobuffer 查询是否启用$nobuffer选项
	 * @access     public
	 * @return     mixed        执行成功返回数据库连接资源，否则返回false
	 */
	function execute($criteria=NULL, $full=false, $nobuffer=false){
		if(is_object($criteria)){
			$sql=$criteria->getSql();
			if(!$full) $sql.= ' '.$criteria->renderWhere();
			$this->sqlres = $this->db->query($sql, 0, 0, $nobuffer);
			return $this->sqlres;
		}elseif(!empty($criteria)){
			$this->sqlres = $this->db->query($criteria, 0, 0, $nobuffer);
			return $this->sqlres;
		}
		return false;
	}

	/**
	 * 执行一个基于查询类的查询
	 *
	 * @param      object      $criteria 查询对象
	 * @param      bool        $nobuffer 查询是否启用$nobuffer选项
	 * @access     public
	 * @return     mixed        执行成功返回数据库连接资源，否则返回false
	 */
	function queryObjects($criteria = NULL, $nobuffer=false){
		$limit = $start = 0;
		$sql = 'SELECT '.$criteria->getFields().' FROM '.$criteria->getTables().' '.$criteria->renderWhere();
		if ($criteria->getGroupby() != ''){
			$sql .= ' GROUP BY '.$criteria->getGroupby();
		}

		if ($criteria->getSort() != '') {
			$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
		}
		$limit = $criteria->getLimit();
		$start = $criteria->getStart();
		$this->sqlres = $this->db->query($sql, $limit, $start, $nobuffer);
		return $this->sqlres;
	}

	/**
	 * 获取下一个查询结果
	 *
	 * @param      resource    $result 数据库连接资源
	 * @access     public
	 * @return     object      数据表对象
	 */
	function getObject($result=''){
		if($result=='') $result=$this->sqlres;
		if(!$result) return false;
		else{
			$myrow = $this->db->fetchArray($result);
			if(!$myrow) return false;
			else{
				$dbrowobj = new JieqiObjectData();
				$dbrowobj->setVars($myrow);
				return $dbrowobj;
			}
		}
	}

	/**
	 * 获取下一个查询结果
	 *
	 * @param      resource    $result 数据库连接资源
	 * @access     public
	 * @return     array       数据库行数组
	 */
	function getRow($result=''){
		if($result=='') $result=$this->sqlres;
		if(!$result) return false;
		else{
			$myrow = $this->db->fetchArray($result);
			if(!$myrow) return false;
			else return $myrow;
		}
	}

	/**
	 * 获取查询结果行数
	 *
	 * @param      object      $criteria 查询类
	 * @access     public
	 * @return     int         结果行数
	 */
	function getCount($criteria = NULL){
		if(is_object($criteria)){
			if ($criteria->getGroupby() == ''){
				$sql = 'SELECT COUNT(*) FROM '.$criteria->getTables().' '.$criteria->renderWhere();
				$nobuffer=true;
			}else{
				$sql = 'SELECT COUNT('.$criteria->getGroupby().') FROM '.$criteria->getTables().' '.$criteria->renderWhere().' GROUP BY '.$criteria->getGroupby();
				$nobuffer=false;
			}
			$result = $this->db->query($sql, 0, 0, $nobuffer);
			if (!$result) return 0;
			if ($criteria->getGroupby() == ''){
				list($count) = $this->db->fetchRow($result);
			}else{
				$count = $this->db->getRowsNum($result);
			}
			return $count;
		}
		return 0;
	}

	/**
	 * 批量更新数据
	 *
	 * @param      string      $table 数据表名
	 * @param      mixed       $fields 要更新的字段，字符串或者数组
	 * @param      object      $criteria 查询对象
	 * @access     public
	 * @return     bool
	 */
	function updatefields($table, $fields, $criteria = NULL)
	{
		$sql = 'UPDATE '.$table.' SET ';
		$start=true;
		if(is_array($fields)){
			foreach($fields as $k=>$v){
				if(!$start){
					$sql.=', ';
				}else{
					$start=false;
				}
				if(is_numeric($v)){
					$sql.=$k.'='.$this->db->quoteString($v);
				}else{
					$sql.=$k.'='.$this->db->quoteString($v);
				}
			}
		}else{
			$sql.=$fields;
		}
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
}

/**
 * 数据表句柄类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiObjectHandler extends JieqiQueryHandler{
	/**
	 * 类的基本名称
	 *
	 * @var string
	 */
	var $basename;
	/**
	 * 唯一序号字段
	 *
	 * @var string
	 */
	var $autoid;
	/**
	 * 数据表表名
	 *
	 * @var string
	 */
	var $dbname;
	/**
	 * 是否完整的表名，默认“否”，要用dbprefix获得完整表名
	 *
	 * @var bool
	 */
	var $fullname=false; 


	/**
	 * 构造函数
	 *
	 * @param      object      $db 数据库对象
	 * @access     private
	 * @return     void
	 */
	function JieqiObjectHandler($db='')
	{
		$this->JieqiQueryHandler($db);
	}

	/**
	 * 新建数据表对象
	 *
	 * @param      bool        $isNew 是否设置为新建状态
	 * @access     public
	 * @return     object      数据表对象
	 */
	function create($isNew = true){
		$tmpvar='Jieqi'.ucfirst($this->basename);
		${$this->basename} = new $tmpvar();
		if ($isNew) {
			${$this->basename}->setNew();
		}
		return ${$this->basename};
	}

	/**
	 * 根据id取得一个对象
	 *
	 * @param      int         $id 数据表唯一id
	 * @access     public
	 * @return     object      数据表对象
	 */
	function get($id){
		if (is_numeric($id) && intval($id) > 0) {
			$id=intval($id);
			$sql = 'SELECT * FROM '.jieqi_dbprefix($this->dbname, $this->fullname).' WHERE '.$this->autoid.'='.$id;
			if (!$result = $this->db->query($sql, 0, 0, true)) {
				return false;
			}
			$datarow=$this->db->fetchArray($result);
			if (is_array($datarow)) {
				$tmpvar='Jieqi'.ucfirst($this->basename);
				${$this->basename} = new $tmpvar();
				${$this->basename}->setVars($datarow);
				return ${$this->basename};
			}
		}
		return false;
	}

	/**
	 * 插入或更新一行数据
	 *
	 * @param      object      $baseobj 数据表对象
	 * @access     public
	 * @return     bool
	 */
	function insert(&$baseobj){
		if (strcasecmp(get_class($baseobj), 'jieqi'.$this->basename) != 0) {
			return false;
		}
		if ($baseobj->isNew()) {
			//插入记录
			if(is_numeric($baseobj->getVar($this->autoid,'n'))){
				${$this->autoid}=intval($baseobj->getVar($this->autoid,'n'));
			}else{
				${$this->autoid} = $this->db->genId($this->dbname.'_'.$this->autoid.'_seq');
			}
			$sql='INSERT INTO '.jieqi_dbprefix($this->dbname, $this->fullname).' (';
			$values=') VALUES (';
			$start=true;

			foreach ($baseobj->vars as $k => $v) {
				if(!$start){
					$sql.=', ';
					$values.=', ';
				}else{
					$start=false;
				}
				$sql.=$k;
				if($v['type']==JIEQI_TYPE_INT){
					if($k != $this->autoid){
						$values.=$this->db->quoteString($v['value']);
					}else{
						$values.=${$this->autoid};
					}
				}else{
					$values.=$this->db->quoteString($v['value']);
				}
			}
			$sql.=$values.')';
			unset($values);

		}else{
			//更新记录
			$sql='UPDATE '.jieqi_dbprefix($this->dbname, $this->fullname).' SET ';
			$start=true;
			foreach($baseobj->vars as $k => $v){
				if($k != $this->autoid && $v['isdirty']){
					if(!$start){
						$sql.=', ';
					}else{
						$start=false;
					}
					if($v['type']==JIEQI_TYPE_INT){
						$sql.=$k.'='.$this->db->quoteString($v['value']);
					}else{
						$sql.=$k.'='.$this->db->quoteString($v['value']);
					}
				}
			}
			if($start) return true;
			$sql.=' WHERE '.$this->autoid.'='.intval($baseobj->vars[$this->autoid]['value']);
		}
		$result = $this->db->query($sql);
		if (!$result) {
			return false;
		}
		if ($baseobj->isNew()) {
			$baseobj->setVar($this->autoid,$this->db->getInsertId());
		}
		return true;
	}

	/**
	 * 按id或查询对象删除
	 *
	 * @param      mixed       $criteria 数据表唯一id或者数据表对象
	 * @access     public
	 * @return     bool
	 */
	function delete($criteria = 0){
		$sql='';
		if(is_numeric($criteria)){
			$criteria=intval($criteria);
			$sql='DELETE FROM '.jieqi_dbprefix($this->dbname, $this->fullname).' WHERE '.$this->autoid.'='.$criteria;
		}elseif (is_object($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$tmpstr=$criteria->renderWhere();
			if(!empty($tmpstr))  $sql= 'DELETE FROM '.jieqi_dbprefix($this->dbname, $this->fullname).' '.$tmpstr;
		}
		if(empty($sql)) return false;
		$result = $this->db->query($sql);
		if (!$result) {
			return false;
		}
		return true;
	}

	/**
	 * 根据查询对象执行一个查询
	 *
	 * @param      object      $criteria 查询对象
	 * @param      bool        $nobuffer 查询是否启用$nobuffer选项
	 * @access     public
	 * @return     resource     数据库连接资源
	 */
	function queryObjects($criteria = NULL, $nobuffer=false){
		$limit = $start = 0;
		$sql = 'SELECT '.$criteria->getFields().' FROM '.jieqi_dbprefix($this->dbname, $this->fullname);
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			if ($criteria->getGroupby() != ''){
				$sql .= ' GROUP BY '.$criteria->getGroupby();
			}
			if ($criteria->getSort() != '') {
				$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$this->sqlres = $this->db->query($sql, $limit, $start, $nobuffer);
		return $this->sqlres;
	}

	/**
	 * 获取下一个查询结果
	 *
	 * @param      resource    $result 数据库连接资源
	 * @access     public
	 * @return     object      数据表对象
	 */
	function getObject($result=''){
		if($result=='') $result=$this->sqlres;
		if(!$result) return false;
		else{
			$tmpvar='Jieqi'.ucfirst($this->basename);
			$myrow = $this->db->fetchArray($result);
			if(!$myrow) return false;
			else{
				$dbrowobj = new $tmpvar();
				$dbrowobj->setVars($myrow);
				return $dbrowobj;
			}
		}
	}

	/**
	 * 获取查询结果返回行数
	 *
	 * @param      object      $criteria 查询对象
	 * @access     public
	 * @return     int         返回行数
	 */
	function getCount($criteria = NULL){
		$sql = 'SELECT COUNT(*) FROM '.jieqi_dbprefix($this->dbname, $this->fullname);
		$nobuffer=true;
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			if ($criteria->getGroupby() != ''){
				$sql = 'SELECT COUNT('.$criteria->getGroupby().') FROM '.jieqi_dbprefix($this->dbname, $this->fullname).' '.$criteria->renderWhere().' GROUP BY '.$criteria->getGroupby();
				$nobuffer=false;
			}
		}
		$result = $this->db->query($sql, 0, 0, $nobuffer);
		if (!$result) return 0;
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement') && $criteria->getGroupby() != ''){
			$count = $this->db->getRowsNum($result);
		}else{

			list($count) = $this->db->fetchRow($result);
		}

		return $count;
	}

	/**
	 * 批量更新数据
	 *
	 * @param      mixed       $fields 更新的字段，字符串或者数组
	 * @param      object      $criteria 查询对象
	 * @access     public
	 * @return     bool
	 */
	function updatefields($fields, $criteria = NULL){
		$sql = 'UPDATE '.jieqi_dbprefix($this->dbname, $this->fullname).' SET ';
		$start=true;
		if(is_array($fields)){
			foreach($fields as $k=>$v){
				if(!$start){
					$sql.=', ';
				}else{
					$start=false;
				}
				if(is_numeric($v)){
					$sql.=$k.'='.$this->db->quoteString($v);
				}else{
					$sql.=$k.'='.$this->db->quoteString($v);
				}
			}
		}else{
			$sql.=$fields;
		}
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
}


/**
 * 查询基础类，标准化SQL语句
 * 
 * @category   jieqicms
 * @package    system
 */
class CriteriaElement extends JieqiObject{
	var $order = 'ASC';
	var $sort = '';
	var $limit = 0;
	var $start = 0;
	var $groupby = '';
	var $sql = '';
	var $fields='*';
	var $tables='';

	/**
	 * 构造函数
	 *
	 * @param      void
	 * @access     private
	 * @return     void
	 */
	function CriteriaElement(){
		$this->JieqiObject();
	}

	/**
	 * 设置查询语句
	 *
	 * @param      string      $sql
	 * @access     public
	 * @return     void
	 */
	function setSql($sql){
		$this->sql = $sql;
	}

	/**
	 * 获取查询语句
	 *
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getSql(){
		return $this->sql;
	}

	/**
	 * 设置查询的字段
	 *
	 * @param      string      $fields
	 * @access     public
	 * @return     void
	 */
	function setFields($fields){
		$this->fields = $fields;
	}

	/**
	 * 获取查询的字段
	 *
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getFields(){
		return $this->fields;
	}

	/**
	 * 设置查询的数据表
	 *
	 * @param      string      $tables
	 * @access     public
	 * @return     void
	 */
	function setTables($tables){
		$this->tables = $tables;
	}

	/**
	 * 获取查询的数据表
	 *
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getTables(){
		return $this->tables;
	}

	/**
	 * 设置查询排序字段
	 * 
	 * @param      string      $sort
	 * @access     public
	 * @return     void
	 */
	function setSort($sort){
		$this->sort = $sort;
	}

	/**
	 * 获取查询排序字段
	 *
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getSort(){
		return $this->sort;
	}

	/**
	 * 设置查询排序方式
	 *
	 * @param      string      $order
	 * @access     public
	 * @return     void
	 */
	function setOrder($order){
		if ('DESC' == strtoupper($order)) {
			$this->order = 'DESC';
		}
	}

	/**
	 * 获取查询排序方式
	 *
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getOrder(){
		return $this->order;
	}

	/**
	 * 设置查询返回条数
	 *
	 * @param      int         $limit
	 * @access     public
	 * @return     void
	 */
	function setLimit($limit=0){
		if(isset($limit) && is_numeric($limit)) $this->limit = intval($limit);
		else $this->limit = 1;
	}

	/**
	 * 获取查询返回条数
	 *
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function getLimit(){
		return $this->limit;
	}

	/**
	 * 设置查询开始条数
	 *
	 * @param      int         $start
	 * @access     public
	 * @return     void
	 */
	function setStart($start=0){
		$this->start = intval($start);
	}

	/**
	 * 获取查询开始条数
	 *
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function getStart(){
		return $this->start;
	}

	/**
	 * 设置查询分组条件
	 *
	 * @param      string      $group
	 * @access     public
	 * @return     void
	 */
	function setGroupby($group){
		$this->groupby = $group;
	}

	/**
	 * 获取查询分组条件
	 *
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getGroupby(){
		return $this->groupby;
	}
}

/**
 * 多个条件组合查询类
 * 
 * @category   jieqicms
 * @package    system
 */
class CriteriaCompo extends CriteriaElement{

	var $criteriaElements = array();
	var $conditions = array();

	/**
	 * 构造函数
	 *
	 * @param      object      $ele 单个条件查询对象
	 * @param      string      $condition 条件合并方式
	 * @access     private
	 * @return     void
	 */
	function CriteriaCompo($ele=NULL, $condition='AND'){
		if (isset($ele) && is_object($ele)) {
			$this->add($ele, $condition);
		}
	}

	/**
	 * 增加一个查询条件
	 *
	 * @param      object      $criteriaElement 单个条件查询对象
	 * @param      string      $condition 条件合并方式
	 * @access     public
	 * @return     object
	 */
	function add(&$criteriaElement, $condition='AND'){
		$this->criteriaElements[] =& $criteriaElement;
		$this->conditions[] = $condition;
		return $this;
	}

	/**
	 * 生成查询SQL
	 *
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function render(){
		$ret = '';
		$count = count($this->criteriaElements);
		if ($count > 0) {
			$ret = '('. $this->criteriaElements[0]->render();
			for ($i = 1; $i < $count; $i++) {
				$ret .= ' '.$this->conditions[$i].' '.$this->criteriaElements[$i]->render();
			}
			$ret .= ')';
		}
		return $ret;
	}

	/**
	 * 生成查询SQL的条件部分
	 *
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function renderWhere(){
		$ret = $this->render();
		$ret = ($ret != '') ? 'WHERE ' . $ret : $ret;
		return $ret;
	}
}


/**
 * 单个条件查询类
 * 
 * @category   jieqicms
 * @package    system
 */
class Criteria extends CriteriaElement{

	var $column;
	var $operator;
	var $value;

	/**
	 * 构造函数
	 *
	 * @param      string      $column 数据表字段名
	 * @param      string      $value 条件的值
	 * @param      string      $operator 条件的比较方式
	 * @access     private
	 * @return     void
	 */
	function Criteria($column, $value='', $operator='='){
		$this->column = $column;
		$this->value = $value;
		$this->operator = $operator;
	}

	/**
	 * 生成查询SQL
	 *
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function render(){
		if (!empty($this->column)) $clause = $this->column.' '.$this->operator;
		else $clause='';
		if (isset($this->value)) {
			if ($this->column == '' && $this->operator == '') {
				// 如果 $column 和 operator 都为空,则假定 value 为自定义查询条件
				$clause .= " ".trim($this->value);
			} elseif (strtoupper($this->operator) == 'IN') {
				$clause .= ' '.$this->value;
			} else {
				$clause .= " '".jieqi_dbslashes(trim($this->value))."'";
			}
		}
		return $clause;

	}

	/**
	 * 生成查询SQL的条件部分
	 *
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function renderWhere(){
		$ret = $this->render();
		$ret = ($ret != '') ? 'WHERE ' . $ret : $ret;
		return $ret;
	}
}
?>