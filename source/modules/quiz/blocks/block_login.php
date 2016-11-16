<?php
class BlockSystemLogin extends JieqiBlock
{
	var $isuse;
	var $template;
	var $username;

	function BlockSystemLogin()
	{
		$this->JieqiBlock($vars);
		include_once('./include/usertype.php');
		$usertype = usertype::getInstance('usertype');
		$this->isuse=$usertype -> isuser();
		$this->username=$usertype -> get('username');
	}

	function setContent()
	{
		global $jieqiModules;
		if(!is_object($jieqiTpl)) $jieqiTpl =& JieqiTpl::getInstance();
		if($this->isuse==1)
		{
			$jieqiTpl->assign('username',$this->username);
			$this->template	='block_login2.html';
		}
		else
		{
			global $linkurl;
			$url_login=JIEQI_URL.'/login.php?jumpurl='.urlencode($linkurl);
			$jieqiTpl->assign('url_login',$url_login);
			$jieqiTpl->assign('username',$this->username);
			$this->template	='block_login.html';
		}
		$this->blockvars['tlpfile'] = $jieqiModules['quiz']['path'].'/templates/blocks/'.$this->template;
		$jieqiTpl->setCaching(0);//²»»º´æ
	}
}
?>