<?php
@set_time_limit(3600);
header('Content-type:text/html;charset=gb2312');
if($_GET['confirm'] != 1){
	echo '<font color="red">注意：</font><br>本程序用于更新模板和系统配置文件，适合从1.10至1.22的任意版本升级到1.30版。<br>升级前请备份好以下几个目录，并确认这些目录及子目录下内容可写。<br>/configs<br>/themes<br>/templates<br>/modules/article/templates<br>/modules/forum/templates<br><br><a href="'.basename($_SERVER['PHP_SELF']).'?confirm=1">点击这里正在更新模板和配置</a><br><br>';
	exit;
}

include_once '../../configs/define.php';

echo '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ';
echo '正在更新系统风格模板...<br>';
ob_flush();
flush();
$tmpary=array('/templates/index.html', '/templates/custom.html');
$themeary=array();
$themedir='../../themes';
$handle = @opendir($themedir);
while ($file = @readdir($handle)) {
	if($file != '.' && $file != '..' && is_dir('../../themes/'.$file) && is_file('../../themes/'.$file.'/theme.html')){
		$tmpary[]='/themes/'.$file.'/theme.html';
		$themeary[]='/themes/'.$file;
	}
}

//通用模板
$repfrom=array('<{$jieqi_loginfo}>', '$jieqi_indexflag', '<{$jieqi_logininfo}>', '<{$jieqi_imageurl}>');
$repto=array('执行时间：<{$jieqi_exetime}>秒', '$jieqi_indexpage', '<{if $jieqi_userid == 0}><{$jieqi_sitename}>欢迎您，您可以选择[<a href="<{$jieqi_url}>/login.php?jumpurl=<{$jieqi_thisurl|urlencode}>">登录</a>]或者[<a href="<{$jieqi_url}>/register.php">注册新用户</a>]！<{elseif $jieqi_newmessage == 0}><{$jieqi_sitename}>欢迎您，<{$jieqi_username}> [<a href="<{$jieqi_url}>/logout.php">退出登录</a>]<{else}><{$jieqi_sitename}>欢迎您，<{$jieqi_username}> [<a href="<{$jieqi_url}>/logout.php">退出登录</a>] [<a href="<{$jieqi_url}>/message.php?box=inbox"><span class="hottext">您有新消息</span></a>]<{/if}>', '<{$jieqi_themeurl}>');

//替换通用模板变量
foreach ($tmpary as $v){
	$filename='..'.$v;
	if(is_file($filename)){
		$content=jieqi_readfile($filename);
		$content1=str_replace($repfrom, $repto, $content);
		if($content != $content1){
			if(is_writable($filename)){
				jieqi_writefile($filename, $content1);
				echo '模板<a href="'.JIEQI_URL.$v.'">'.$v.'</a> <font color="blue">更新完成！</font><br>';
			}else{
				echo '模板<a href="'.JIEQI_URL.$v.'">'.$v.'</a> <font color="red">不可写，请检查文件读写权限！</font><br>';
			}
			ob_flush();
			flush();
		}
	}
}


//themes模板里面增加 msgerr.html
foreach($themeary as $v){
	$filename='..'.$v.'/msgwin.html';
	$makename='..'.$v.'/msgerr.html';
	$cssname='..'.$v.'/style.css';
	$jumpname='..'.$v.'/jumppage.html';
	if(is_file($filename)){
		$content=jieqi_readfile($filename);
		$content1=str_replace('<{$copyright}>', '版权所有&copy; <a href="<{$jieqi_url}>/"><{$jieqi_sitename}></a>', $content);
		if(strlen($content) != strlen($content1)){
			if(is_writable($filename)){
				jieqi_writefile($filename, $content1);
				echo '模板<a href="'.JIEQI_URL.$v.'/msgwin.html">'.$v.'/msgwin.html</a> <font color="blue">更新完成！</font><br>';
			}else{
				echo '模板<a href="'.JIEQI_URL.$v.'/msgwin.html">'.$v.'/msgwin.html</a> <font color="red">不可写，请检查文件读写权限！</font><br>';
			}
			ob_flush();
			flush();
		}
		if(!file_exists($makename)){
			$content1=str_replace(array('<{$title}>', '<{$content}>'), array('出现错误！', '错误原因：<{$errorinfo}><br /><{$debuginfo}><br />请 <a href="javascript:history.back(1)">返 回</a> 并修正'), $content1);
			if(is_writable(dirname($makename))){
				jieqi_writefile($makename, $content1);
				echo '模板<a href="'.JIEQI_URL.$v.'/msgerr.html">'.$v.'/msgerr.html</a> <font color="blue">创建完成！</font><br>';
			}else{
				echo '模板<a href="'.JIEQI_URL.$v.'/msgerr.html">'.$v.'/msgerr.html</a> <font color="red">创建失败，请检查目录 '.$v.' 是否可写！</font><br>';
			}
			ob_flush();
			flush();
		}
	}

	if(is_file($jumpname)){
		$content=jieqi_readfile($jumpname);
		if(strpos($content, '点击这里直接进入')===false){
			$content=str_replace('<{$content}>', '<br /><{$content}><br /><br />如不能自动跳转，<a href="<{$url}>">点击这里直接进入！</a><br /><br />', $content);
			if(is_writable($jumpname)){
				jieqi_writefile($jumpname, $content);
				echo '跳转页面模板 <a href="'.JIEQI_URL.$v.'/jumppage.html">'.$v.'/jumppage.html</a> <font color="blue">更新完成！</font><br>';
			}else{
				echo '跳转页面模板 <a href="'.JIEQI_URL.$v.'/jumppage.html">'.$v.'/jumppage.html</a> <font color="red">不可写，请检查文件读写权限！</font><br>';
			}
			ob_flush();
			flush();
		}

	}

	if(is_file($cssname)){
		$content=jieqi_readfile($cssname);
		$addstr='';
		$ulstr='';
		if(strpos($content, '.popbox')===false) $addstr.="\r\n.popbox{
	position:absolute;
	width:190px !important;
	height:110px !important;
	width:200px;
	height:120px;
	border: 1px solid #83b0e1;
	background: #F0F7FF;
	color: #FF0000;
	font-size: 12px;
	line-height:120%;
	padding: 3px;
	display:none;
	z-index:9999;
}";
		if(strpos($content, '.c_title')===false) $addstr.="\r\n.c_title{
  width: 100%;
  text-align: center;
  font-size: 16px;
  font-weight: bold;
  line-height: 200%;
}";
		if(strpos($content, '.c_head')===false) $addstr.="\r\n.c_head{
  line-height: 150%;
}";
		if(strpos($content, '.c_content')===false) $addstr.="\r\n.c_content{
  font-size: 14px;
  line-height: 150%;
}";
		if(strpos($content, '.c_foot')===false) $addstr.="\r\n.c_foot{
  line-height: 150%;
}";
		if(strpos($content, '.c_label')===false) $addstr.="\r\n.c_label{
	font-weight: bold;
}";
		if(strpos($content, '.c_value')===false) $addstr.="\r\n.c_value{
}";
		if(strpos($content, '.ultop')===false){
			$addstr.="\r\n.ultop li{list-style: circle inside; margin-left: 3px;}";
			if(!empty($ulstr)) $ulstr.=', ';
			$ulstr.='.ultop';
		}
		if(strpos($content, '.ulitem')===false){
			$addstr.="\r\n.ulitem li{list-style: circle inside; margin-left: 3px;}";
			if(!empty($ulstr)) $ulstr.=', ';
			$ulstr.='.ulitem';
		}

		if(strpos($content, '.ulrow')===false){
			$addstr.="\r\n.ulrow li{padding:3px;}";
			if(!empty($ulstr)) $ulstr.=', ';
			$ulstr.='.ulrow';
		}
		if(strpos($content, '.ulcenter')===false){
			$addstr.="\r\n.ulcenter li{text-align: center;}";
			if(!empty($ulstr)) $ulstr.=', ';
			$ulstr.='.ulcenter';
		}
		if(strpos($content, '.ulmul')===false){
			$addstr.="\r\n.ulmul{overflow: hidden;}";
			if(!empty($ulstr)) $ulstr.=', ';
			$ulstr.='.ulmul';
		}
		if(!empty($ulstr)) $addstr = "\r\n".$ulstr.'{margin: 0px; padding: 0px; list-style-type: none; text-align: left; clear: both;}'.$addstr;
		if(strpos($content, '.lm')===false) $addstr.="\r\n.lm{white-space:nowrap; text-overflow:ellipsis; -o-text-overflow:ellipsis; overflow: hidden;}";
		if(strpos($content, '.fl')===false) $addstr.="\r\n.fl{float:left;}";
		if(strpos($content, '.fr')===false) $addstr.="\r\n.fr{float:right;}";
		if(strpos($content, '.cl')===false) $addstr.="\r\n.cl{clear:left;}";
		if(strpos($content, '.cr')===false) $addstr.="\r\n.cr{clear:right;}";
		if(strpos($content, '.cb')===false) $addstr.="\r\n.cb{clear:both;}";
		if(strpos($content, '.tl')===false) $addstr.="\r\n.tl{text-align:left;}";
		if(strpos($content, '.tc')===false) $addstr.="\r\n.tc{text-align:center;}";
		if(strpos($content, '.tr')===false) $addstr.="\r\n.tr{text-align:right;}";
		if(strpos($content, '.more')===false) $addstr.="\r\n.more{text-align: right;}";

		if(!empty($addstr)){
			if(is_writable($cssname)){
				$content.=$addstr;
				jieqi_writefile($cssname, $content);
				echo '风格CSS <a href="'.JIEQI_URL.$v.'/style.css">'.$v.'/style.css</a> <font color="blue">更新完成！</font><br>';
			}else{
				echo '风格CSS <a href="'.JIEQI_URL.$v.'/style.css">'.$v.'/style.css</a> <font color="red">不可写，请检查文件读写权限！</font><br>';
			}
			ob_flush();
			flush();
		}
	}
}

echo '<hr>正在更新内容网页模板...<br>';
ob_flush();
flush();

//系统模板
$i=0;
$tmpchange[$i]['tmpfile']='templates/buyegold.html';
$tmpchange[$i]['repfrom']=array('<{$url_ipay}>', '<{$url_xpay}>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/ipay.php', '<{$jieqi_url}>/xpay.php');

$i++;
$tmpchange[$i]['tmpfile']='templates/checkuser.html';
$tmpchange[$i]['repfrom']=array('<{$url_checkuser}>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/checkuser.php');

$i++;
$tmpchange[$i]['tmpfile']=array('templates/statusframe.html', 'templates/loginframe.html', 'templates/custom.html', 'themes/'.JIEQI_THEME_SET.'/theme.html');
$tmpchange[$i]['repfrom']=array('<{$jieqi_css}>');
$tmpchange[$i]['repto']=array('<link rel="stylesheet" type="text/css" media="all" href="<{$jieqi_url}>/themes/<{$jieqi_theme}>/style.css" />');

$i++;
$tmpchange[$i]['tmpfile']='templates/loginframe.html';
$tmpchange[$i]['repfrom']=array('<{$url_login}>', '<{$url_register}>', '<{$url_getpass}>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/loginframe.php', '<{$jieqi_url}>/register.php', '<{$jieqi_url}>/getpass.php');

$i++;
$tmpchange[$i]['tmpfile']='templates/statusframe.html';
$tmpchange[$i]['repfrom']=array('<{$url_bookcase}>', '<{$url_message}>', '<{$url_userdetail}>', '<{$url_logout}>', '<{$username}>', '<{$usergroup}>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/modules/article/bookcase.php', '<{$jieqi_url}>/message.php?box=inbox', '<{$jieqi_url}>/userdetail.php', '<{$jieqi_url}>/loginframe.php?action=logout', '<{$jieqi_username}>', '<{$jieqi_usergroup}>');

$i++;
$tmpchange[$i]['tmpfile']=array('templates/inbox.html', 'templates/outbox.html');
$tmpchange[$i]['repfrom']=array('<{$listtitle}>', '<{$messagerows[i].username}></td>', '<{$messagerows[i].title}></td>', '<{$messagerows[i].isread}>');
$tmpchange[$i]['repto']=array('<{$boxname}>（收/发件箱共允许消息数：<{$limitedmessage}>，现有消息数：<{$usedmessage}>）', '<{if $messagerows[i].userid > 0}><a href="<{$jieqi_url}>/userinfo.php?id=<{$messagerows[i].userid}>" target="_blank"><{$messagerows[i].username}></a><{else}><span class="hottext">网站管理员</span><{/if}></td>', '<a href="<{$jieqi_url}>/messagedetail.php?id=<{$messagerows[i].messageid}>"><{$messagerows[i].title}></a></td>', '<{if $messagerows[i].isread == 0}><span class="hottext">未读</a><{else}>已读<{/if}>');

$i++;
$tmpchange[$i]['tmpfile']='templates/messagedetail.html';
$tmpchange[$i]['repfrom']=array('><{$title}>', '<{$fromuser}>', '<{$touser}>', '<{$useraction}>');
$tmpchange[$i]['repto']=array('>标题：<{$title}>', '<{if $fromid > 0}><a href="<{$jieqi_url}>/userinfo.php?id=<{$fromid}>" target="_blank"><{$fromname}></a><{else}><span class="hottext">网站管理员</span><{/if}>', '<{if $toid > 0}><a href="<{$jieqi_url}>/userinfo.php?id=<{$toid}>" target="_blank"><{$toname}></a><{else}><span class="hottext">网站管理员</span><{/if}>', '<{if $url_reply != ""}><a href="<{$jieqi_url}>/newmessage.php?reid=<{$messageid}>&tosys=<{$fromsys}>">回复消息</a>&nbsp;&nbsp;&nbsp;&nbsp;<{/if}><a href="<{$jieqi_url}>/newmessage.php?fwid=<{$messageid}>">转发消息</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:if(confirm(\'确实要该消息么？\')) document.location=\'<{$jieqi_url}>/message.php?box=<{$box}>&delid=<{$messageid}>\'">删除消息</a>');

$i++;
$tmpchange[$i]['tmpfile']='templates/myfriends.html';
$tmpchange[$i]['repfrom']=array('<{$friendstitle}>', '<{$friendsrows[i].url_yourname}>');
$tmpchange[$i]['repto']=array('我的好友（最多好友数：<{$maxfriends}>，现有好友数：<{$nowfriends}>）', '<{$friendsrows[i].yourname|urlencode}>');

$i++;
$tmpchange[$i]['tmpfile']='templates/topuser.html';
$tmpchange[$i]['repfrom']=array('<{$userrows[i].username}>');
$tmpchange[$i]['repto']=array('<a href="<{$jieqi_url}>/userinfo.php?id=<{$userrows[i].uid}>" target="_blank"><{$userrows[i].uname}></a>');

$i++;
$tmpchange[$i]['tmpfile']='templates/userdetail.html';
$tmpchange[$i]['repfrom']=array('<{$email}></td>', '<{$msn}></td>', '<{$url}></td>');
$tmpchange[$i]['repto']=array('<a href="mailto:<{$email}>"><{$email}></a></td>', '<a href="mailto:<{$msn}>"><{$msn}></a></td>', '<a href="<{$url}>" target="_blank"><{$url}></a></td>');

$i++;
$tmpchange[$i]['tmpfile']='templates/userinfo.html';
$tmpchange[$i]['repfrom']=array('<{$email}></td>', '<{$msn}></td>', '<{$url}></td>', '<{$url_action}>');
$tmpchange[$i]['repto']=array('<{if $viewemail == 1}><a href="mailto:<{$email}>"><{$email}></a><{else}>保密<{/if}></td>', '<a href="mailto:<{$msn}>"><{$msn}></a></td>', '<a href="<{$url}>" target="_blank"><{$url}></a></td>', '<a href="<{$jieqi_url}>/newmessage.php?receiver=<{$uname|urlencode}>">发送站内短消息</a> | <a href="<{$jieqi_url}>/addfriends.php?id=<{$uid}>" target="_blank">加为好友</a>');

//-----------------------------------------------------------------------------------
//根目录下区块
$i++;
$tmpchange[$i]['tmpfile']='templates/blocks/block_grouplist.html';
$tmpchange[$i]['repfrom']=array('<{$grouprows[i].url}>', '<{$grouprows[i].name}>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/userlist.php?group=<{$grouprows[i].groupid}>', '<{$grouprows[i].groupname}>');

$i++;
$tmpchange[$i]['tmpfile']='templates/blocks/block_login.html';
$tmpchange[$i]['repfrom']=array('<{$login_url}>', '<{$reg_url}>', '<{$pass_url}>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/login.php', '<{$jieqi_url}>/register.php', '<{$jieqi_url}>/getpass.php');

$i++;
$tmpchange[$i]['tmpfile']='templates/blocks/block_searchuser.html';
$tmpchange[$i]['repfrom']=array('<{$url_usersearch}>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/userinfo.php');

$i++;
$tmpchange[$i]['tmpfile']='templates/blocks/block_userbox.html';
$tmpchange[$i]['repfrom']=array('<{$egold_name}>');
$tmpchange[$i]['repto']=array('<{$egoldname}>');

$i++;
$tmpchange[$i]['tmpfile']=array('templates/blocks/block_userexp.html', 'templates/blocks/block_userlist.html', 'templates/blocks/block_userscore.html', 'templates/blocks/block_usernew.html');
$tmpchange[$i]['repfrom']=array('<{$userrows[i].username}>', 'sort=experience', 'sort=regdate', 'sort=score');
$tmpchange[$i]['repto']=array('<{$userrows[i].uname}>', 'sort=<{$sort}>', 'sort=<{$sort}>', 'sort=<{$sort}>');

$i++;
$tmpchange[$i]['tmpfile']='templates/blocks/block_userstatus.html';
$tmpchange[$i]['repfrom']=array('<{$username}>', '<{$usergroup}>', '<{$url_bookcase}>', '<{$url_message}>', '<{$url_logout}>', '<{$url_userdetail}>');
$tmpchange[$i]['repto']=array('<{$jieqi_username}>', '<{$jieqi_usergroup}>', '<{$jieqi_url}>/modules/article/bookcase.php', '<{$jieqi_url}>/message.php?box=inbox', '<{$jieqi_url}>/logout.php', '<{$jieqi_url}>/userdetail.php');

//-----------------------------------------------------------------------------------
//根目录下管理

$i++;
$tmpchange[$i]['tmpfile']='templates/admin/groups.html';
$tmpchange[$i]['repfrom']=array('<{$groups[i].id}>', '<{$groups[i].url}>');
$tmpchange[$i]['repto']=array('<{$groups[i].groupid}>', '<a href="<{$jieqi_url}>/admin/groups.php?id=<{$groups[i].groupid}>&action=edit">编辑</a><{if $groups[i].grouptype == 0}>  <a href="javascript:if(confirm(\'确实要删除该用户组么？\')) document.location=\'<{$jieqi_url}>/admin/groups.php?id=<{$groups[i].groupid}>&action=delete\';">删除</a><{/if}>');

$i++;
$tmpchange[$i]['tmpfile']=array('templates/admin/index.html', 'templates/admin/main.html');
$tmpchange[$i]['repfrom']=array('<{$jieqi_cssjs}>');
$tmpchange[$i]['repto']=array('<link rel="stylesheet" type="text/css" media="all" href="<{$jieqi_url}>/templates/admin/style.css" />');

$i++;
$tmpchange[$i]['tmpfile']='templates/admin/login.html';
$tmpchange[$i]['repfrom']=array('<{$login_url}>', '<{$index_url}>', '<{$pass_url}>');
$tmpchange[$i]['repto']=array('<{$url_login}>', '<{$jieqi_url}>/index.php', '<{$jieqi_url}>/getpass.php');

$i++;
$tmpchange[$i]['tmpfile']='templates/admin/paylog.html';
$tmpchange[$i]['repfrom']=array('<{$url_search}>', '<{$payrows[i].buyname}></td>', '<{$payrows[i].action}>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/admin/paylog.php', '<a href="<{$jieqi_url}>/userinfo.php?id=<{$payrows[i].buyid}>" target="_blank"><{$payrows[i].buyname}></a></td>', '<{if $payrows[i].payflag == 0}><a href="javascript:if(confirm(\'确实要手工确认该订单么？\')) document.location=\'<{$jieqi_url}>/admin/paylog.php?action=confirm&id=<{$payrows[i].payid}>\';">手工处理</a> | <a href="javascript:if(confirm(\'确实要删除么？\')) document.location=\'<{$jieqi_url}>/admin/paylog.php?action=del&id=<{$payrows[i].payid}>\';">删除</a><{/if}>');

$i++;
$tmpchange[$i]['tmpfile']='templates/admin/userlog.html';
$tmpchange[$i]['repfrom']=array('<{$url_search}>', '<{$logrows[i].fromname}></td>', '<{$logrows[i].toname}></td>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/admin/userlog.php', '<a href="<{$jieqi_url}>/userinfo.php?id=<{$logrows[i].fromid}>" target="_blank"><{$logrows[i].fromname}></a></td>', '<a href="<{$jieqi_url}>/userinfo.php?id=<{$logrows[i].toid}>" target="_blank"><{$logrows[i].toname}></a></td>');

$i++;
$tmpchange[$i]['tmpfile']='templates/admin/users.html';
$tmpchange[$i]['repfrom']=array('<{$url_query}>', '<{$userrows[i].username}></td>', '<{$userrows[i].email}></td>', '<{$userrows[i].action}>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/admin/users.php', '<a href="<{$jieqi_url}>/userinfo.php?id=<{$userrows[i].userid}>" target="_blank"><{$userrows[i].username}></a></td>', '<a href="mailto:<{$userrows[i].email}>"><{$userrows[i].email}></a></td>', '<a href="<{$jieqi_url}>/admin/usermanage.php?id=<{$userrows[i].userid}>">管理</a>');

//-----------------------------------------------------------------------------------
//文章模块
$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/applywriter.html';
$tmpchange[$i]['repfrom']=array('<{$url_agree}>', '<{$url_refuse}>');
$tmpchange[$i]['repto']=array('<{$jieqi_url}>/modules/article/applywriter.php?agree=1', '<{$jieqi_url}>/index.php');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/articleinfo.html';
$tmpchange[$i]['repfrom']=array('<{$url_fulltext}>', '<{$size}>', '<{$author}></td>', '<{$agent}></td>', '<{$ebookrows[i].ebookname}></td>', '<{$ebookrows[i].urlbuy}>', '<{$reviewrows[i].content}></td>');
$tmpchange[$i]['repto']=array('<{$url_fullpage}>', '<{$size_c}>字', '<{if $authorid > 0}><a href="<{$article_dynamic_url}>/userinfo.php?id=<{$authorid}>" target="_blank"><{$author}></a><{else}><{$author}><{/if}></td>', '<{if $agentid > 0}><a href="<{$article_dynamic_url}>/userinfo.php?id=<{$agentid}>" target="_blank"><{$agent}></a><{else}><{$agent}><{/if}></td>', '<a href="<{$article_dynamic_url}>/modules/ebookshop/ebookinfo.php?id=<{$ebookrows[i].ebookid}>" target="_blank"><{$ebookrows[i].ebookname}><{if $ebookrows[i].ebookchapter != ""}>（<{$ebookrows[i].ebookchapter}>）<{/if}></a></td>', '<a href="<{$ebookrows[i].url_buy}>" target="_blank">订阅</a>', '□ <{if $reviewrows[i].topflag == 1}><span class="hottext">[顶]</span><{/if}><{if $reviewrows[i].goodflag == 1}><span class="hottext">[精]</span><{/if}><{$reviewrows[i].content}><br />---- <{$reviewrows[i].postdate}> <{$reviewrows[i].username}> 发表<hr /></td>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/authorpage.html';
$tmpchange[$i]['repfrom']=array('<{$authorname}>', '<{$articlerows[i].articlename}></td>', '<{$articlerows[i].lastchapter}></td>', '<{$noterows[i].post}>', '<{$noterows[i].reply}>');
$tmpchange[$i]['repto']=array('<a href="<{$article_dynamic_url}>/userinfo.php?id=<{$authorid}>" target="_blank"><{$author}></a>', '<a href="<{$articlerows[i].url_articleinfo}>"><{$articlerows[i].articlename}></a></td>', '<a href="<{$articlerows[i].url_lastchapter}>" target="_blank"><{$articlerows[i].lastchapter}></a></td>', '<{if $noterows[i].posterid == 0}>游客：<{else}><a href="<{$article_dynamic_url}>/userinfo.php?id=<{$noterows[i].posterid}>" target="_blank"><{$noterows[i].postername}></a>：<{/if}><{$noterows[i].postdate}><br />&nbsp;&nbsp;&nbsp;&nbsp;<{$noterows[i].notetext}>', '<{if $noterows[i].replytext != ""}>回复：<br />&nbsp;&nbsp;&nbsp;&nbsp;<span class="hottext"><{$noterows[i].replytext}></span><{/if}>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/bookcase.html';
$tmpchange[$i]['repfrom']=array('<{$bookcasetitle}>', '<{$bookcaserows[i].indexurl}>', '<{$bookcaserows[i].lastchapterurl}>', '<{$bookcaserows[i].articlemarkurl}>', '<{$bookcaserows[i].articleurl}>', '<{$bookcaserows[i].delurl}>', '<{$bookcaserows[i].operate}>');
$tmpchange[$i]['repto']=array('您的书架可收藏 <{$maxbookcase}> 本，已收藏 <{$nowbookcase}> 本', '<{$bookcaserows[i].url_index}>', '<{$bookcaserows[i].url_lastchapter}>', '<{$bookcaserows[i].url_articlemark}>', '<{$bookcaserows[i].url_articleinfo}>', '<{$bookcaserows[i].url_delete}>', '<a href="javascript:if(confirm(\'确实要将本书移除书架么？\')) document.location=\'<{$bookcaserows[i].url_delete}>\';">移除</a>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/draft.html';
$tmpchange[$i]['repfrom']=array('<{$checkall}>', '<{$draftrows[i].articlename}></td>', '<{$draftrows[i].chaptername}>', '<{$draftrows[i].operate}>');
$tmpchange[$i]['repto']=array('操作', '<a href="<{$article_static_url}>/modules/article/articlemanage.php?id=<{$draftrows[i].articleid}>"><{$draftrows[i].articlename}></a></td>', '<a href="<{$article_static_url}>/modules/article/draftedit.php?id=<{$draftrows[i].draftid}>"><{$draftrows[i].draftname}></a>', '<a href="<{$article_static_url}>/modules/article/draftedit.php?id=<{$draftrows[i].draftid}>">编辑</a> <a href="javascript:if(confirm(\'确实要删除该章节么？\')) document.location=\'<{$draftrows[i].url_delete}>\';">删除</a> <{if $draftrows[i].articleid == 0}>&nbsp;&nbsp;&nbsp;&nbsp;<{else}><a href="<{$article_static_url}>/modules/article/newchapter.php?aid=<{$draftrows[i].articleid}>&draftid=<{$draftrows[i].draftid}>">发表</a><{/if}>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/articlelist.html';
$tmpchange[$i]['repfrom']=array('<{$articlerows[i].size}>', '<{$articlerows[i].articleinfourl}>', '<{$articlerows[i].lastchapterurl}>');
$tmpchange[$i]['repto']=array('<{$articlerows[i].size_k}>K', '<{$articlerows[i].url_articleinfo}>', '<{$articlerows[i].url_lastchapter}>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/masterpage.html';
$tmpchange[$i]['repfrom']=array('<{$articlerows[i].size}>', '<{$jieqi_url}>/modules/article/articleinfo.php?id=<{$articlerows[i].articleid}>', '<{$articlerows[i].lastchapterurl}>', '<{$articlerows[i].operate}>');
$tmpchange[$i]['repto']=array('<{$articlerows[i].size_k}>K', '<{$articlerows[i].url_articleinfo}>', '<{$articlerows[i].url_lastchapter}>', '<a href="<{$article_static_url}>/modules/article/articlemanage.php?id=<{$articlerows[i].articleid}>" target="_blank">管理</a>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/notebook.html';
$tmpchange[$i]['repfrom']=array('<{$note_title}>', '<{$noterows[i].post}>', '<{$noterows[i].reply}>', '<{$noterows[i].manage}>');
$tmpchange[$i]['repto']=array('<a href="<{$article_dynamic_url}>/userinfo.php?id=<{$authorid}>" target="_blank"><{$authorname}></a> 会客室', '<{if $noterows[i].posterid == 0}>游客：<{else}><a href="<{$article_dynamic_url}>/userinfo.php?id=<{$noterows[i].posterid}>" target="_blank"><{$noterows[i].postername}></a>：<{/if}><{$noterows[i].postdate}><br />&nbsp;&nbsp;&nbsp;&nbsp;<{$noterows[i].notetext}>', '<{if $noterows[i].replytext != ""}>回复：<br />&nbsp;&nbsp;&nbsp;&nbsp;<span class="hottext"><{$noterows[i].replytext}></span><{/if}>', '<{if $noterows[i].ismaster == 1}>[<a href="javascript:if(confirm(\'确实要删除该留言么？\')) document.location=\'<{$article_dynamic_url}>/modules/article/notebook.php?id=<{$id}>&nid=<{$noterows[i].noteid}>&action=delete\';">删 除</a>]&nbsp;&nbsp;[<a href="<{$article_dynamic_url}>/modules/article/notereply.php?id=<{$id}>&nid=<{$noterows[i].noteid}>">回 复</a>]<{/if}>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/review.html';
$tmpchange[$i]['repfrom']=array('<{$reviewtitle}>', '"><{$reviewrows[i].content}>', '<{$reviewrows[i].info}>', '<{$url_review}>');
$tmpchange[$i]['repto']=array('<a href="<{$url_articleinfo}>">《<{$articlename}>》</a><{if $type == "good"}>&nbsp;&nbsp;&nbsp;&nbsp;[<a href="<{$article_dynamic_url}>/modules/article/review.php?aid=<{$articleid}>&type=all">全部书评</a>] &nbsp;&nbsp; [精华书评]<{else}>&nbsp;&nbsp;&nbsp;&nbsp;[全部书评] &nbsp;&nbsp; [<a href="<{$article_dynamic_url}>/modules/article/review.php?aid=<{$articleid}>&type=good">精华书评</a>]<{/if}>', '">□ <{if $reviewrows[i].topflag == 1}><span class="hottext">[顶]</span><{/if}><{if $reviewrows[i].goodflag == 1}><span class="hottext">[精]</span><{/if}><{$reviewrows[i].content}>', '<{$reviewrows[i].username}><br /><{$reviewrows[i].postdate}><{if $ismaster == 1}><br /><{if $reviewrows[i].topflag == 0}>[<a href="<{$reviewrows[i].url_top}>">置顶</a>]<{else}>[<a href="<{$reviewrows[i].url_untop}>">置后</a>]<{/if}> <{if $reviewrows[i].goodflag == 0}>[<a href="<{$reviewrows[i].url_good}>">加精</a>]<{else}>[<a href="<{$reviewrows[i].url_normal}>">去精</a>]<{/if}> [<a href="javascript:if(confirm(\'确实要删除该书评么？\')) document.location=\'<{$reviewrows[i].url_delete}>\';">删除</a>]<{/if}>', '<{$article_dynamic_url}>/modules/article/review.php?aid=<{$articleid}>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/reviewlist.html';
$tmpchange[$i]['repfrom']=array('<{$url_review}>', '<{$reviewtitle}>', '"><{$reviewrows[i].content}>', '<{$reviewrows[i].info}>');
$tmpchange[$i]['repto']=array('<{$article_dynamic_url}>/modules/article/reviewlist.php', '[<a href="<{$article_dynamic_url}>/modules/article/reviewlist.php?type=all">全部书评</a>] &nbsp;&nbsp; [<a href="<{$article_dynamic_url}>/modules/article/reviewlist.php?type=good">精华书评</a>]', '">□ <{if $reviewrows[i].topflag == 1}><span class="hottext">[顶]</span><{/if}><{if $reviewrows[i].goodflag == 1}><span class="hottext">[精]</span><{/if}><{$reviewrows[i].content}>', '《<a href="<{$reviewrows[i].url_articleinfo}>" target="_blank"><{$reviewrows[i].articlename}></a>》<br /><{$reviewrows[i].username}><br /><{$reviewrows[i].postdate}>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/searchresult.html';
$tmpchange[$i]['repfrom']=array('<{$articlerows[i].size}>', '<{$articlerows[i].lastchapterurl}>', '<{$jieqi_url}>/modules/article/articleinfo.php?id=<{$articlerows[i].articleid}>');
$tmpchange[$i]['repto']=array('<{$articlerows[i].size_k}>K', '<{$articlerows[i].url_lastchapter}>', '<{$articlerows[i].url_articleinfo}>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/toplist.html';
$tmpchange[$i]['repfrom']=array('<{$articlerows[i].size}>', '<{$articlerows[i].articleinfourl}>', '<{$articlerows[i].lastchapterurl}>');
$tmpchange[$i]['repto']=array('<{$articlerows[i].size_k}>K', '<{$articlerows[i].url_articleinfo}>', '<{$articlerows[i].url_lastchapter}>');

$i++;
$tmpchange[$i]['tmpfile']=array('modules/article/templates/index.html', 'modules/article/templates/style.html','modules/article/templates/fulltext.html');
$tmpchange[$i]['repfrom']=array('href="<{$dynamic_url}>"');
$tmpchange[$i]['repto']=array('href="<{$dynamic_url}>/"');

//-----------------------------------------------------------------------------------
//文章区块
$i++;
$tmpchange[$i]['tmpfile']=array('modules/article/templates/blocks/block_allauthorvisit.html', 'modules/article/templates/blocks/block_allvisit.html', 'modules/article/templates/blocks/block_allvote.html', 'modules/article/templates/blocks/block_dayauthorvisit.html', 'modules/article/templates/blocks/block_dayvisit.html', 'modules/article/templates/blocks/block_dayvote.html', 'modules/article/templates/blocks/block_goodnum.html', 'modules/article/templates/blocks/block_mouthauthorvisit.html', 'modules/article/templates/blocks/block_mouthvisit.html', 'modules/article/templates/blocks/block_mouthvote.html', 'modules/article/templates/blocks/block_postdate.html', 'modules/article/templates/blocks/block_size.html', 'modules/article/templates/blocks/block_toptime.html', 'modules/article/templates/blocks/block_weekauthorvisit.html', 'modules/article/templates/blocks/block_weekvisit.html', 'modules/article/templates/blocks/block_weekvote.html', 'modules/article/templates/blocks/block_articlelist.html');
$tmpchange[$i]['repfrom']=array('toprows', '<{$articlerows[i].url}>', '<{$articlerows[i].name', '<{$articlerows[i].num}>', '<{$newrows[i].article', '<{$newrows[i].articleurl}>', '<{$newrows[i].chapterurl}>', '$newrows[i].authorurl', '$newrows[i].chapter', '$newrows');
$tmpchange[$i]['repto']=array('articlerows', '<{$articlerows[i].url_articleinfo}>', '<{$articlerows[i].articlename', '<{$articlerows[i].visitnum}>', '<{$newrows[i].articlename', '<{$newrows[i].url_articleinfo}>', '<{$newrows[i].url_lastchapter}>', '$newrows[i].url_authorinfo', '$newrows[i].lastchapter', '$articlerows');

$i++;
$tmpchange[$i]['tmpfile']=array('modules/article/templates/blocks/block_lastupdate.html', 'modules/article/templates/blocks/block_masterupdate.html', 'modules/article/templates/blocks/block_authorupdate.html');
$tmpchange[$i]['repfrom']=array('$newrows');
$tmpchange[$i]['repto']=array('$articlerows');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/blocks/block_newreview.html';
$tmpchange[$i]['repfrom']=array('<{$newrows[i].title}>', '<{$newrows[i].posttime}>', '<{$newrows[i].article}>');
$tmpchange[$i]['repto']=array('<a href="<{$article_dynamic_url}>/modules/article/review.php?aid=<{$newrows[i].articleid}>" target="_blank"><{$newrows[i].reviewtitle}></a>', '<{$newrows[i].postdate}>', '<a href="<{$newrows[i].url_articleinfo}>" target="_blank"><{$newrows[i].articlename}></a>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/blocks/block_transarticles.html';
$tmpchange[$i]['repfrom']=array('$articles', '<a href="<{$articlerows[i].url}>"><{$articlerows[i].caption');
$tmpchange[$i]['repto']=array('$articlerows', '<a href="<{$article_static_url}>/modules/article/articlemanage.php?id=<{$articlerows[i].articleid}>"><{$articlerows[i].articlename');

$i++;
$tmpchange[$i]['tmpfile']=array('modules/article/templates/blocks/block_lastupdate.html','modules/article/templates/blocks/block_authorupdate.html','modules/article/templates/blocks/block_masterupdate.html');
$tmpchange[$i]['repfrom']=array('<{$newrows[i].articleurl}>', '<{$newrows[i].article}>', '<{$newrows[i].article|', '<{$newrows[i].chapterurl}>', '<{$newrows[i].chapter', '$newrows[i].authorurl');
$tmpchange[$i]['repto']=array('<{$newrows[i].url_articleinfo}>', '<{$newrows[i].articlename}>', '<{$newrows[i].articlename|', '<{$newrows[i].url_lastchapter}>', '<{$newrows[i].lastchapter', '$newrows[i].url_authorinfo');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/blocks/block_sort.html';
$tmpchange[$i]['repfrom']=array('<{$sortrows[i].sorturl}>');
$tmpchange[$i]['repto']=array('<{$sortrows[i].url_sort}>');

//-----------------------------------------------------------------------------------
//文章管理

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/admin/articlelist.html';
$tmpchange[$i]['repfrom']=array('<{$articlerows[i].size}>', 'lastchapterurl', '<{$jieqi_url}>/modules/article/articleinfo.php?id=<{$articlerows[i].articleid}>', '<{$articlerows[i].operate}>');
$tmpchange[$i]['repto']=array('<{$articlerows[i].size_k}>K', 'url_lastchapter', '<{$articlerows[i].url_articleinfo}>', '<{if $articlerows[i].display == 0}><a href="<{$article_dynamic_url}>/modules/article/admin/article.php?action=hide&id=<{$articlerows[i].articleid}>">隐藏</a> <a href="<{$article_dynamic_url}>/modules/article/admin/setgood.php?id=<{$articlerows[i].articleid}>" target="_blank">推荐</a><{else}><a href="<{$article_dynamic_url}>/modules/article/admin/article.php?action=confirm&id=<{$articlerows[i].articleid}>">审核</a> 推荐<{/if}> <a href="<{$article_static_url}>/modules/article/articlemanage.php?id=<{$articlerows[i].articleid}>" target="_blank">管理</a> <a href="javascript:if(confirm(\'确实要删除该文章么？\')) document.location=\'<{$article_dynamic_url}>/modules/article/admin/article.php?action=del&id=<{$articlerows[i].articleid}>\'">删除</a>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/admin/chapterlist.html';
$tmpchange[$i]['repfrom']=array('<{$articlerows[i].size}>', 'chapterurl', '<{$jieqi_url}>/modules/article/articleinfo.php?id=<{$articlerows[i].articleid}>', '操作');
$tmpchange[$i]['repto']=array('<{$articlerows[i].size_k}>K', 'url_lastchapter', '<{$articlerows[i].url_articleinfo}>', '类型');

$i++;
$tmpchange[$i]['tmpfile']=array('modules/article/templates/admin/checkupdate.html', 'modules/article/templates/admin/makefake.html');
$tmpchange[$i]['repfrom']=array('<{$new_url}>');
$tmpchange[$i]['repto']=array('<{$article_static_url}>');

$i++;
$tmpchange[$i]['tmpfile']='modules/article/templates/admin/review.html';
$tmpchange[$i]['repfrom']=array('<{$url_review}>', '<{$reviewtitle}>', '"><{$reviewrows[i].content}>', '<{$reviewrows[i].info}>');
$tmpchange[$i]['repto']=array('<{$article_dynamic_url}>/modules/article/admin/review.php', '[<a href="<{$article_dynamic_url}>/modules/article/admin/review.php?type=all">全部书评</a>] &nbsp;&nbsp; [<a href="<{$article_dynamic_url}>/modules/article/admin/review.php?type=good">精华书评</a>]', '"><{$reviewrows[i].checkbox}> <{if $reviewrows[i].topflag == 1}><span class="hottext">[顶]</span><{/if}><{if $reviewrows[i].goodflag == 1}><span class="hottext">[精]</span><{/if}><{$reviewrows[i].content}>', '《<a href="<{$reviewrows[i].url_articleinfo}>" target="_blank"><{$reviewrows[i].articlename}></a>》<br /><a href="<{$jieqi_url}>/userinfo.php?id=<{$reviewrows[i].userid}>" target="_blank"><{$reviewrows[i].username}></a><br /><{$reviewrows[i].postdate}><br /><{if $reviewrows[i].topflag == 0}>[<a href="<{$reviewrows[i].url_top}>">置顶</a>]<{else}>[<a href="<{$reviewrows[i].url_untop}>">置后</a>]<{/if}> <{if $reviewrows[i].goodflag == 0}>[<a href="<{$reviewrows[i].url_good}>">加精</a>]<{else}>[<a href="<{$reviewrows[i].url_normal}>">去精</a>]<{/if}> [<a href="javascript:if(confirm(\'确实要删除该书评么？\')) document.location=\'<{$reviewrows[i].url_delete}>\';">删除</a>]');

//替换模板
foreach($tmpchange as $v){
	$tmpfiles=array();
	if(is_array($v['tmpfile'])) $tmpfiles=$v['tmpfile'];
	else $tmpfiles[0]=$v['tmpfile'];
	foreach($tmpfiles as $f){
		$filename='../../'.$f;
		if(is_file($filename)){
			$content=jieqi_readfile($filename);
			$fromlen=strlen($content);
			$content=str_replace($v['repfrom'], $v['repto'], $content);
			if($fromlen != strlen($content)){
				if(is_writable($filename)){
					jieqi_writefile($filename, $content);
					echo '模板<a href="'.$filename.'">'.$f.'</a> <font color="blue">更新完成！</font><br>';
				}else{
					echo '模板<a href="'.$filename.'">'.$f.'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
				}
				ob_flush();
				flush();
			}
		}
	}
}

//文章信息页面 ajax 应用
/*
$fname='../../modules/article/templates/articleinfo.html';
$data=jieqi_readfile($fname);
if(strpos($data, 'ajax.js')==false){
	if(is_writable($fname)){
		$data=str_replace(array('<a href="<{$url_bookcase}>" target="_blank">', '<a href="<{$url_uservote}>" target="_blank">'), array('<a href="javascript:geturl(\'<{$url_bookcase}>\');">', '<a href="javascript:geturl(\'<{$url_uservote}>\');">'), $data);

		$data='<div id="popbox" class="popbox" onclick="hidepop();" onblur="hidepop();"></div>
<script type="text/javascript" src="<{$jieqi_url}>/scripts/ajax.js"></script>
<script type="text/javascript">
var ajax = new jieqi_ajax();
function whenCompleted(){
	if (ajax.responseStatus){
		$("popbox").innerHTML=ajax.response;
		showpop();
	}
}

function showpop(){
	$("popbox").style.top=document.body.clientHeight - 120;
	$("popbox").style.left=document.body.clientWidth - 200;
	$("popbox").style.display="block";
	$("popbox").focus();
}

function hidepop(){
	$("popbox").style.display="none";
}

function geturl(url){
	ajax.requestFile = url;
	ajax.method = "GET";
	ajax.onCompletion = whenCompleted;
	ajax.runAJAX();
}
</script>
'.$data;
		jieqi_writefile($fname, $data);
		echo '文章信息页面模板 <a href="'.$fname.'">/modules/article/templates/articleinfo.html</a>  <font color="blue">更新完成！</font><br>';

	}else{
		echo '文章信息页面模板 <a href="'.$fname.'">/modules/article/templates/articleinfo.html</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
	}
	ob_flush();
	flush();
}
*/


echo '<hr>正在更新模板边界标记...<br>';
ob_flush();
flush();
prep_dirhtml('../../templates');
prep_dirhtml('../../themes');
if(is_dir('../../modules')){
	$handle = @opendir('../../modules');
	while ($file = @readdir($handle)) {
		if($file != '.' && $file != '..' && is_dir('../../modules/'.$file.'/templates')) prep_dirhtml('../../modules/'.$file.'/templates');
	}
}

function prep_dirhtml($dirname){
	if(is_dir($dirname)){
		$handle = @opendir($dirname);
		while ($file = @readdir($handle)) {
			if($file != '.' && $file != '..'){
				if (is_dir($dirname.'/'.$file)){
					prep_dirhtml($dirname.'/'.$file);
				}else{
					if(substr($file,-5)=='.html'){
						$fname=$dirname.'/'.$file;
						$data=jieqi_readfile($fname);
						if(strpos($data, '<{') !== false || strpos($data, 'mouth') !== false){
							if(is_writable($fname)){
						 		$data=str_replace(array('<{', '}>', 'mouth'), array('{?', '?}', 'month'), $data);
						 		jieqi_writefile($fname, $data);
						 		echo '模板 <a href="'.$fname.'">'.substr($fname,2).'</a>  <font color="blue">更新完成！</font><br>';
						 		
							}else{
								echo '模板 <a href="'.$fname.'">'.substr($fname,2).'</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
							}
							ob_flush();
							flush();
						}
					}
				}
			}
		}
		@closedir($handle);
	}
}

echo '<hr>正在更新配置文件...<br>';
ob_flush();
flush();


//修改菜单
include_once('../../configs/adminmenu.php');

$fname='../../configs/adminmenu.php';
$data=jieqi_readfile($fname);

$repstr='';
if(strpos($data, 'online.php')===false) $repstr.= '$jieqiAdminmenu[\'system\'][] = array(\'layer\' => \'0\', \'caption\' => \'在线用户管理\', \'command\'=>JIEQI_URL.\'/admin/online.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'right.php')===false) $repstr.= '$jieqiAdminmenu[\'system\'][] = array(\'layer\' => \'0\', \'caption\' => \'权利设置\', \'command\'=>JIEQI_URL.\'/admin/right.php?mod=system\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'honors.php')===false) $repstr.= '$jieqiAdminmenu[\'system\'][] = array(\'layer\' => \'0\', \'caption\' => \'头衔管理\', \'command\'=>JIEQI_URL.\'/admin/honors.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'indexc.php')!==false) $data=str_replace('indexc.php?refresh=1', 'indexs.php?refresh=1&template=index.html', $data);
elseif(strpos($data, 'indexs.php')===false) $repstr.= '$jieqiAdminmenu[\'system\'][] = array(\'layer\' => \'0\', \'caption\' => \'生成静态首页\', \'command\'=>JIEQI_URL.\'/indexs.php?refresh=1\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'dboptimize.php?option=optimize')===false) $repstr.= '$jieqiAdminmenu[\'database\'][] = array(\'layer\' => \'0\', \'caption\' => \'数据库优化\', \'command\'=>JIEQI_URL.\'/admin/dboptimize.php?option=optimize\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'dboptimize.php?option=repair')===false) $repstr.= '$jieqiAdminmenu[\'database\'][] = array(\'layer\' => \'0\', \'caption\' => \'数据库修复\', \'command\'=>JIEQI_URL.\'/admin/dboptimize.php?option=repair\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'dbquery.php')===false) $repstr.= '$jieqiAdminmenu[\'database\'][] = array(\'layer\' => \'0\', \'caption\' => \'数据库升级\', \'command\'=>JIEQI_URL.\'/admin/dbquery.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'cleancache.php?target=blockcache')===false) $repstr.= '$jieqiAdminmenu[\'tools\'][] = array(\'layer\' => \'0\', \'caption\' => \'清理区块缓存\', \'command\'=>JIEQI_URL.\'/admin/cleancache.php?target=blockcache\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'cleancache.php?target=cache')===false) $repstr.= '$jieqiAdminmenu[\'tools\'][] = array(\'layer\' => \'0\', \'caption\' => \'清理网页缓存\', \'command\'=>JIEQI_URL.\'/admin/cleancache.php?target=cache\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'cleancache.php?target=compiled')===false) $repstr.= '$jieqiAdminmenu[\'tools\'][] = array(\'layer\' => \'0\', \'caption\' => \'清理程序编译缓存\', \'command\'=>JIEQI_URL.\'/admin/cleancache.php?target=compiled\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'sysinfo.php')===false) $repstr.= '$jieqiAdminmenu[\'tools\'][] = array(\'layer\' => \'0\', \'caption\' => \'系统信息及优化建议\', \'command\'=>JIEQI_URL.\'/admin/sysinfo.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(!empty($repstr)){
	if(is_writable($fname)){
		$data=str_replace('?>', $repstr.'?>', $data);
		jieqi_writefile($fname, $data);
		echo '系统后台菜单配置文件 <a href="'.$fname.'">/configs/adminmenu.php</a>  <font color="blue">更新完成！</font><br>';

	}else{
		echo '系统后台菜单配置文件 <a href="'.$fname.'">/configs/adminmenu.php</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
	}
	ob_flush();
	flush();
}

//****************************************
//文章模块菜单
$fname='../../configs/article/adminmenu.php';
$data=jieqi_readfile($fname);
$data=str_replace(array('modules/article/batchcollect.php', 'modules/article/collect.php'), array('modules/article/admin/batchcollect.php', 'modules/article/admin/collect.php'), $data);

$repstr='';

if(strpos($data, 'searchcache.php')==false) $repstr.= '$jieqiAdminmenu[\'article\'][] = array(\'layer\' => \'0\', \'caption\' => \'搜索关键字\', \'command\'=>JIEQI_URL.\'/modules/article/admin/searchcache.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'batchreplace.php')==false) $repstr.= '$jieqiAdminmenu[\'article\'][] = array(\'layer\' => \'0\', \'caption\' => \'批量替换\', \'command\'=>JIEQI_URL.\'/modules/article/admin/batchreplace.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'collect.php')==false) $repstr.= '$jieqiAdminmenu[\'article\'][] = array(\'layer\' => \'0\', \'caption\' => \'单篇采集\', \'command\'=>JIEQI_URL.\'/modules/article/admin/collect.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'batchcollect.php')==false) $repstr.= '$jieqiAdminmenu[\'article\'][] = array(\'layer\' => \'0\', \'caption\' => \'批量采集\', \'command\'=>JIEQI_URL.\'/modules/article/admin/batchcollect.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'collectset.php')==false) $repstr.= '$jieqiAdminmenu[\'article\'][] = array(\'layer\' => \'0\', \'caption\' => \'采集配置\', \'command\'=>JIEQI_URL.\'/modules/article/admin/collectset.php\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";

if(strpos($data, 'right.php')==false) $repstr.= '$jieqiAdminmenu[\'article\'][] = array(\'layer\' => \'0\', \'caption\' => \'权利设置\', \'command\'=>JIEQI_URL.\'/admin/right.php?mod=article\', \'power\' => JIEQI_GROUP_GUEST, \'target\' => JIEQI_TARGET_SELF, \'publish\' => \'1\');'."\r\n";


if(!empty($repstr)){
	if(is_writable($fname)){
		$data=str_replace('?>', $repstr.'?>', $data);
		jieqi_writefile($fname, $data);
		echo '小说后台菜单配置文件 <a href="'.$fname.'">/configs/article/adminmenu.php</a>  <font color="blue">更新完成！</font><br>';

	}else{
		echo '小说后台菜单配置文件 <a href="'.$fname.'">/configs/article/adminmenu.php</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
	}
	ob_flush();
	flush();
}

//修改css
$fname='../../configs/article/page.css';
$data=jieqi_readfile($fname);
$addstr='';
if(strpos($data, 'divimage')==false) $addstr.= '.divimage{
    text-align: center;
}'."\r\n";
if(strpos($data, 'imagecontent')==false) $addstr.= '.imagecontent{
}'."\r\n";
if($addstr != ''){
	if(is_writable($fname)){
		$data.=$addstr;
		jieqi_writefile($fname, $data);
		echo '阅读页CSS <a href="'.$fname.'">/configs/article/page.css</a>  <font color="blue">更新完成！</font><br>';

	}else{
		echo '阅读页CSS <a href="'.$fname.'">/configs/article/page.css</a>  <font color="red">不可写，请检查文件读写权限！</font><br>';
	}
	ob_flush();
	flush();
}

echo '<br><hr><br><font color="blue">程序执行完成，您可以关闭本窗口！</font>';



// 读文件
function jieqi_readfile($file_name){
	if (function_exists("file_get_contents")) {
		return file_get_contents($file_name);
	}else{
		$filenum = @fopen($file_name, "rb");
		@flock($filenum, LOCK_SH);
		$file_data = @fread($filenum, @filesize($file_name));
		@flock($filenum, LOCK_UN);
		@fclose($filenum);
		return $file_data;
	}
}

//写文件
function jieqi_writefile($file_name, &$data, $method = "wb"){
	$filenum = @fopen($file_name, $method);
	if(!$filenum) return false;
	@flock($filenum, LOCK_EX);
	$ret = @fwrite($filenum, $data);
	@flock($filenum, LOCK_UN);
	@fclose($filenum);
	@chmod($file_name, 0777);
	return $ret;
}
?>