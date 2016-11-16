<?php
/**
 * 数据表类(jieqi_system_users - 用户信息表)
 *
 * 数据表类(jieqi_system_users - 用户信息表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: users.php 326 2009-02-04 00:26:22Z juny $
 */

jieqi_includedb();

//字段对应关系
global $system_users_fields;
$system_users_fields['uid']=array('name'=>'uid', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'序号', 'required'=>false, 'maxlength'=>11);
$system_users_fields['siteid']=array('name'=>'siteid', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'网站序号', 'required'=>false, 'maxlength'=>6);
$system_users_fields['uname']=array('name'=>'uname', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'用户名', 'required'=>true, 'maxlength'=>30);
$system_users_fields['name']=array('name'=>'name', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'真实姓名', 'required'=>false, 'maxlength'=>60);
$system_users_fields['pass']=array('name'=>'pass', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'密码', 'required'=>false, 'maxlength'=>32);
$system_users_fields['groupid']=array('name'=>'groupid', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'用户组序号', 'required'=>false, 'maxlength'=>3);
$system_users_fields['regdate']=array('name'=>'regdate', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'注册日期', 'required'=>false, 'maxlength'=>11);
$system_users_fields['initial']=array('name'=>'initial', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'用户名首字母', 'required'=>false, 'maxlength'=>1);
$system_users_fields['sex']=array('name'=>'sex', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'性别', 'required'=>false, 'maxlength'=>1);
$system_users_fields['email']=array('name'=>'email', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'Email', 'required'=>true, 'maxlength'=>60);
$system_users_fields['url']=array('name'=>'url', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'网站', 'required'=>false, 'maxlength'=>100);
$system_users_fields['avatar']=array('name'=>'avatar', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'头像', 'required'=>false, 'maxlength'=>11);
$system_users_fields['workid']=array('name'=>'workid', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'职业', 'required'=>false, 'maxlength'=>11);
$system_users_fields['qq']=array('name'=>'qq', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'QQ', 'required'=>false, 'maxlength'=>15);
$system_users_fields['icq']=array('name'=>'icq', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'ICQ', 'required'=>false, 'maxlength'=>15);
$system_users_fields['msn']=array('name'=>'msn', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'MSN', 'required'=>false, 'maxlength'=>60);
$system_users_fields['mobile']=array('name'=>'mobile', 'type'=>JIEQI_TYPE_TXTBOX, 'value'=>'', 'caption'=>'手机', 'required'=>false, 'maxlength'=>20);
$system_users_fields['sign']=array('name'=>'sign', 'type'=>JIEQI_TYPE_TXTAREA, 'value'=>'', 'caption'=>'签名', 'required'=>false, 'maxlength'=>NULL);
$system_users_fields['intro']=array('name'=>'intro', 'type'=>JIEQI_TYPE_TXTAREA, 'value'=>'', 'caption'=>'个人简介', 'required'=>false, 'maxlength'=>NULL);
$system_users_fields['setting']=array('name'=>'setting', 'type'=>JIEQI_TYPE_TXTAREA, 'value'=>'', 'caption'=>'用户设置', 'required'=>false, 'maxlength'=>NULL);
$system_users_fields['badges']=array('name'=>'badges', 'type'=>JIEQI_TYPE_TXTAREA, 'value'=>'', 'caption'=>'其他信息', 'required'=>false, 'maxlength'=>NULL);
$system_users_fields['lastlogin']=array('name'=>'lastlogin', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'最后登录', 'required'=>false, 'maxlength'=>10);
$system_users_fields['showsign']=array('name'=>'showsign', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'显示签名', 'required'=>false, 'maxlength'=>1);
$system_users_fields['viewemail']=array('name'=>'viewemail', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'公开Email', 'required'=>false, 'maxlength'=>1);
$system_users_fields['notifymode']=array('name'=>'notifymode', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'通知方式', 'required'=>false, 'maxlength'=>1);
$system_users_fields['adminemail']=array('name'=>'adminemail', 'type'=>JIEQI_TYPE_INT, 'value'=>1, 'caption'=>'接受管理员Email', 'required'=>false, 'maxlength'=>1);
$system_users_fields['monthscore']=array('name'=>'monthscore', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'本月积分', 'required'=>false, 'maxlength'=>11);
$system_users_fields['weekscore']=array('name'=>'weekscore', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'本周积分', 'required'=>false, 'maxlength'=>11);
$system_users_fields['dayscore']=array('name'=>'dayscore', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'本日积分', 'required'=>false, 'maxlength'=>11);
$system_users_fields['lastscore']=array('name'=>'lastscore', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'最后积分', 'required'=>false, 'maxlength'=>11);
$system_users_fields['experience']=array('name'=>'experience', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'经验值', 'required'=>false, 'maxlength'=>11);
$system_users_fields['score']=array('name'=>'score', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'积分', 'required'=>false, 'maxlength'=>11);
$system_users_fields['egold']=array('name'=>'egold', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'虚拟货币', 'required'=>false, 'maxlength'=>11);
$system_users_fields['esilver']=array('name'=>'esilver', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'银币', 'required'=>false, 'maxlength'=>11);
$system_users_fields['credit']=array('name'=>'credit', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'信用度', 'required'=>false, 'maxlength'=>11);
$system_users_fields['goodnum']=array('name'=>'goodnum', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'好评', 'required'=>false, 'maxlength'=>11);
$system_users_fields['badnum']=array('name'=>'badnum', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'恶评', 'required'=>false, 'maxlength'=>11);
$system_users_fields['isvip']=array('name'=>'isvip', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'是否VIP会员', 'required'=>false, 'maxlength'=>1);
$system_users_fields['overtime']=array('name'=>'overtime', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'过期时间', 'required'=>false, 'maxlength'=>11);
$system_users_fields['state']=array('name'=>'state', 'type'=>JIEQI_TYPE_INT, 'value'=>0, 'caption'=>'用户状态', 'required'=>false, 'maxlength'=>1);

//用户类
class JieqiUsers extends JieqiObjectData
{
	//字段对应表
	var $tableFields=array();

	//构建函数
	function JieqiUsers()
	{
		global $system_users_fields;
		$this->JieqiObjectData();
		$this->tableFields=&$system_users_fields;
		foreach($this->tableFields as $k=>$v){
			$this->initVar($k, $v['type'], $v['value'], $v['caption'], $v['required'], $v['maxlength']);
		}
	}


	//获得性别
	function getSex() {
		global $jieqiLang;
		jieqi_loadlang('users', 'system');
		switch($this->getVar('sex')) {
			case 1:
				return $jieqiLang['system']['sex_man'];
			case 2:
				return $jieqiLang['system']['sex_woman'];
			default:
				return $jieqiLang['system']['sex_unset'];
		}
	}

	//获得用户组
	function getGroup() {
		global $jieqiGroups;
		return $jieqiGroups[$this->getVar('groupid')];
	}

	//取得VIP状态
	function getViptype() {
		global $jieqiLang;
		jieqi_loadlang('users', 'system');
		$vipflag = $this->getVar('isvip');
		if($vipflag == 0) return $jieqiLang['system']['user_no_vip'];
		elseif($vipflag == 1) return $jieqiLang['system']['user_is_vip'];
		elseif($vipflag > 1) return $jieqiLang['system']['user_super_vip'];
	}

	//获取状态
	function getStatus() {
		//会员状态 0－游客 1－登录用户 2－登录管理员
		switch($this->getVar('groupid')) {
			case JIEQI_GROUP_GUEST:
				return JIEQI_GROUP_GUEST;
				break;
			case JIEQI_GROUP_ADMIN:
				return JIEQI_GROUP_ADMIN;
				break;
			default:
				return JIEQI_GROUP_USER;
				break;
		}
	}

	//保存用户信息到session
	function saveToSession() {
		if($_SESSION['jieqiUserId'] == $this->getVar('uid')){
			$_SESSION['jieqiUserScore'] = $this->getVar('score', 'n');
			$_SESSION['jieqiUserExperience'] = $this->getVar('experience', 'n');
		}
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//用户句柄
class JieqiUsersHandler extends JieqiObjectHandler
{
	var $tableFields=array(); //序号对应数据库字段
	var $tableFieldid=array();  //数据库字段对应序号

	function JieqiUsersHandler($db=''){
		global $system_users_fields;
		$this->JieqiObjectHandler($db);
		$this->tableFields=&$system_users_fields;
		$this->basename='users';
		$this->autoid='uid';
		$this->dbname=jieqi_dbprefix('system_users');
		$this->fullname=true;
		foreach($this->tableFields as $k=>$v){
			$this->tableFieldid[$v['name']]=$k;
		}
	}

	//密码加密函数
	function encryptPass($pass){
		return md5($pass);
	}

	//根据用户名查询用户
	//flag 1-仅查询uname，2-仅查询name， 3-同时查询uname和name，存在name的话以此优先 
	function getByname($name,$flag=1){
		if (!empty($name)) {
			$name=jieqi_dbslashes($name);
			if($flag==3) $sql = "SELECT * FROM ".jieqi_dbprefix($this->dbname, $this->fullname)." WHERE ".$this->tableFields['uname']['name']."='".$name."' OR ".$this->tableFields['name']['name']."='".$name."' ORDER BY name DESC";
			elseif($flag==2) $sql = "SELECT * FROM ".jieqi_dbprefix($this->dbname, $this->fullname)." WHERE ".$this->tableFields['name']['name']."='".$name."'";
			else $sql = "SELECT * FROM ".jieqi_dbprefix($this->dbname, $this->fullname)." WHERE ".$this->tableFields['uname']['name']."='".$name."'";
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows >= 1) {
				$tmpvar='Jieqi'.ucfirst($this->basename);
				${$this->basename} = new $tmpvar();
				${$this->basename}->setVars($this->db->fetchArray($result));
				return ${$this->basename};
			}
		}
		return false;
	}
	
	//改变贡献值
	function changeCredit($uid, $credit, $isadd=true){
		if(empty($credit) || !is_numeric($credit) || empty($uid) || !is_numeric($uid)) return false;
		if($isadd){
			$sql="UPDATE ".jieqi_dbprefix($this->dbname, $this->fullname)." SET credit=credit+".$credit." WHERE ".$this->tableFields['uid']['name']."=".$uid;
		}else{
			$sql="UPDATE ".jieqi_dbprefix($this->dbname, $this->fullname)." SET credit=credit-".$credit." WHERE ".$this->tableFields['uid']['name']."=".$uid;
		}
		$this->db->query($sql);
		return true;
	}

	//改变积分（同时改变经验值）
	function changeScore($uid, $score, $isadd=true, $delexperience=true)
	{
		if(empty($score) || !is_numeric($score) || empty($uid) || !is_numeric($uid)) return false;

		if($isadd){
			//按照月周天，增加积分
			$tmpuser=$this->get($uid);
			if(!is_object($tmpuser)) return false;

			$oldscore=$tmpuser->getVar('lastscore', 'n');
			$lastdate=date('Y-m-d', $oldscore);
			$lasttime=JIEQI_NOW_TIME;
			$nowdate=date('Y-m-d',  $lasttime);
			$nowweek=date('w', $lasttime);

			$sql="UPDATE ".jieqi_dbprefix($this->dbname, $this->fullname)." SET experience=experience+".$score.", score=score+".$score;
			if($nowdate==$lastdate){
				$sql.=", monthscore=monthscore+".$score.", weekscore=weekscore+".$score.", dayscore=dayscore+".$score;
			}else{
				if(substr($nowdate,0,7)==substr($lastdate,0,7)){
					$sql.=", monthscore=monthscore+".$score;
				}else{
					$sql.=", monthscore=".$score;
				}
				if($nowweek==1){
					$sql.=", weekscore=".$score;
				}else{
					$sql.=", weekscore=weekscore+".$score;
				}
				$sql.=", dayscore=".$score;
			}
			$sql.=" WHERE ".$this->tableFields['uid']['name']."=".$uid;
		}else{
			if($delexperience) $sql="UPDATE ".jieqi_dbprefix($this->dbname, $this->fullname)." SET experience=experience-".$score.", score=score-".$score.", monthscore=monthscore-".$score." WHERE ".$this->tableFields['uid']['name']."=".$uid;
			else $sql="UPDATE ".jieqi_dbprefix($this->dbname, $this->fullname)." SET score=score-".$score.", monthscore=monthscore-".$score." WHERE ".$this->tableFields['uid']['name']."=".$uid;
		}
		$this->db->query($sql);
		//处理SESSION
		if($_SESSION['jieqiUserId'] == $uid){
			if($isadd){
				$_SESSION['jieqiUserScore'] = $_SESSION['jieqiUserScore'] + $score;
				$_SESSION['jieqiUserExperience'] = $_SESSION['jieqiUserExperience'] + $score;
			}else{
				$_SESSION['jieqiUserScore'] = $_SESSION['jieqiUserScore'] - $score;
				if($delexperience) $_SESSION['jieqiUserExperience'] = $_SESSION['jieqiUserExperience'] - $score;
			}
		}
		return true;
	}


	//支出虚拟币(默认支付金币，没有的话支付银币)
	function payout($uid, $emoney)
	{
		if(empty($emoney) || !is_numeric($emoney) || empty($uid) || !is_numeric($uid)) return false;
		$tmpuser=$this->get($uid);
		if(!is_object($tmpuser)) return false;
		$useregold=$tmpuser->getVar('egold', 'n');
		$useresilver=$tmpuser->getVar('esilver', 'n');
		$useremoney=$useregold+$useresilver;
		if($useremoney < $emoney) return false;

		if($useregold >= $emoney){
			$tmpuser->setVar('egold', $useregold-$emoney);
		}elseif($useresilver >= $emoney){
			$tmpuser->setVar('esilver', $useresilver-$emoney);
		}else{
			$tmpuser->setVar('egold', 0);
			$tmpuser->setVar('esilver', $useresilver+$useregold-$emoney);
		}
		if(!empty($_SESSION['jieqiUserId']) && $uid == $_SESSION['jieqiUserId']) $tmpuser->saveToSession();
		$this->insert($tmpuser);
	}

	//在线支付后增加虚拟货币
	function income($uid, $emoney, $usesliver=0, $addscore=0, $updatevip=1)
	{
		$tmpuser=$this->get($uid);
		if(is_object($tmpuser)){
			//增加虚拟币
			if($usesliver==1) $tmpuser->setVar('esilver', $tmpuser->getVar('esilver')+$emoney);
			else $tmpuser->setVar('egold', $tmpuser->getVar('egold')+$emoney);
			//修改vip标志
			$updatevip=intval($updatevip);
			if($updatevip>0 && $tmpuser->getVar('isvip')<$updatevip) $tmpuser->setVar('isvip',$updatevip);
			//增加积分
			$addscore=intval($addscore);
			if($addscore>0){
				$tmpuser->setVar('score',$tmpuser->getVar('score')+$addscore);
			}
			if(!empty($_SESSION['jieqiUserId']) && $uid == $_SESSION['jieqiUserId']) $tmpuser->saveToSession();
			$this->insert($tmpuser);
			return true;
		}
		return false;
	}

	//*******************************************************************
	//修改默认的处理函数，使之能支持设置名称和数据库字段名称不一样的情况
	//*******************************************************************
	//根据id取得一个实例
	function get($id){
		if (is_numeric($id) && intval($id) > 0) {
			$id=intval($id);
			$sql = 'SELECT * FROM '.jieqi_dbprefix($this->dbname, $this->fullname).' WHERE '.$this->tableFields[$this->autoid]['name'].'='.$id;
			if (!$result = $this->db->query($sql, 0, 0, true)) {
				return false;
			}
			$datarow=$this->db->fetchArray($result);
			if (is_array($datarow)) {
				$tmpvar='Jieqi'.ucfirst($this->basename);
				${$this->basename} = new $tmpvar();
				foreach($datarow as $k=>$v){
					if(isset($this->tableFieldid[$k])) ${$this->basename}->setVar($this->tableFieldid[$k], $v, true, false);
					else ${$this->basename}->setVar($k, $v, true, false);
				}
				return ${$this->basename};
			}
		}
		return false;
	}

	//插入或更新
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
				if(isset($this->tableFields[$k]['name'])) $sql.=$this->tableFields[$k]['name'];
				else $sql.=$k;
				if($v['type']==JIEQI_TYPE_INT){
					if($k != $this->autoid){
						$values.=$v['value'];
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
					if(isset($this->tableFields[$k]['name'])) $k = $this->tableFields[$k]['name'];
					if($v['type']==JIEQI_TYPE_INT){
						$sql.=$k.'='.$v['value'];
					}else{
						$sql.=$k.'='.$this->db->quoteString($v['value']);
					}
				}
			}
			if($start) return true;
			$sql.=' WHERE '.$this->tableFields[$this->autoid]['name'].'='.intval($baseobj->vars[$this->autoid]['value']);
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

	//按id或查询删除
	function delete($criteria = 0){
		$sql='';
		if(is_numeric($criteria)){
			$criteria=intval($criteria);
			$sql='DELETE FROM '.jieqi_dbprefix($this->dbname, $this->fullname).' WHERE '.$this->tableFields[$this->autoid]['name'].'='.$criteria;
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

	//执行选择查询
	function queryObjects($criteria = NULL, $nobuffer=false){
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.jieqi_dbprefix($this->dbname, $this->fullname);
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

	//获取下一个查询结果
	function getObject($result=''){
		static $dbrowobj;
		if($result=='') $result=$this->sqlres;
		if(!$result) return false;
		else{
			$tmpvar='Jieqi'.ucfirst($this->basename);
			$myrow = $this->db->fetchArray($result);
			if(!$myrow) return false;
			else{
				if(!isset($dbrowobj)){
					$dbrowobj = new $tmpvar();
				}
				foreach($myrow as $k=>$v){
					if(isset($this->tableFieldid[$k])) $dbrowobj->setVar($this->tableFieldid[$k], $v, true, false);
					else $dbrowobj->setVar($k, $v, true, false);
				}
				return $dbrowobj;
			}
		}
	}

	//获取下一个查询结果(数据库行)
	function getRow($result=''){
		if($result=='') $result=$this->sqlres;
		if(!$result) return false;
		else{
			$myrow = $this->db->fetchArray($result);
			if(!$myrow) return false;
			else return $myrow;
		}
	}


	//返回行数
	function getCount($criteria = NULL){
		$sql = 'SELECT COUNT(*) FROM '.jieqi_dbprefix($this->dbname, $this->fullname);
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		$result = $this->db->query($sql, 0, 0, true);
		if (!$result) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	//批量更新
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
				if(isset($this->tableFields[$k]['name'])) $k = $this->tableFields[$k]['name'];
				if(is_numeric($v)){
					$sql.=$k.'='.$v;
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
?>