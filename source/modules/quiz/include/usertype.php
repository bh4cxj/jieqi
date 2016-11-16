<?php
class usertype extends JieqiObject
{
	var  $userid;
	var  $username;
	var  $groupid;
	var  $score;

	function usertype()
	{
		session_start();
		$this -> userid = $_SESSION['jieqiUserId'];
		$this -> username = $_SESSION['jieqiUserUname'];
		$this -> groupid = $_SESSION['jieqiUserGroup'];
	}

	function isuser()
	{
		if($this->userid=='')
		{
			return false;
		}
		else
		{
			jieqi_includedb();
			$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
			$sql='select score from jieqi_system_users where uid ='.$this->userid;
			$res=$query->getObject($query->execute($sql));
			$this -> score = $res -> getVar('score');
			return true;
		}
	}

	function get($name)
	{
		return $this->$name;
	}

	function editscore($num)
	{
		if($num > $this -> score){return false;}
		$sql = 'update '.jieqi_dbprefix('system_users').' set score=score-'.$num.' where uid = '.jieqi_dbslashes($this->userid);
		jieqi_includedb();
		$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
		$res=$query->execute($sql);
		return true;
	}

}
?>