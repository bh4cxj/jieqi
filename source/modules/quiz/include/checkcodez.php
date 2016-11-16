<?php
class checkcodez extends JieqiObject
{
		var $checkcodes;
		function checkcodez()
		{
			session_start();
			$this -> checkcodes = $_SESSION['jieqiCheckCode'];
		}
		function istrue($session)
		{
			if($session == $this->checkcodes)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
}
?>