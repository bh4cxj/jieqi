<?php
define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'blocks');
include_once(JIEQI_ROOT_PATH.'/header.php');
$conn=mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS) or die('链接失败');  
mysql_select_db(JIEQI_DB_NAME, $conn); 
@mysql_query("SET names gbk");
if(!$_COOKIE[jieqiUserInfo]){
	echo "<script language='javascript' type='text/javascript'>
 window.location.href='/login.php';
 </script>";
}

$configarray = mysql_query("SELECT * FROM `jieqi_51muban_config`");
while($row = mysql_fetch_array($configarray,1)){
	$config[] = $row;
}

$str = str_replace(',','&',"$_COOKIE[jieqiUserInfo]");//获取用户资料
parse_str($str,$user);//字符串分割成数组
$uid = $user[jieqiUserId];$groupid = $user['jieqiUserGroup'];
$user=mysql_query("SELECT * FROM `jieqi_system_users` WHERE `uid` ='$uid'");
while($row = mysql_fetch_array($user,1)){
	$userarray = $row;
}
$nums = intval($_GET['nums']);
$content = addslashes($_GET['content']);
//echo '经验值'.$userarray[experience].'<br />';
//echo '现有积分'.$userarray[score].'<br />';
//echo '虚拟货币'.$userarray[egold].'<br />';
//echo '贡献值'.$userarray[credit].'<br />';
if($userarray[experience] < $nums){
	echo 1;
}