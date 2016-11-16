<?php
$GLOBALS['jieqiTset']['jieqi_blocks_module'] = 'system';
echo '
<link href="/css/nt.css" rel="stylesheet" type="text/css" />
<!--正文部份-->
	<div class="homecon">
		<div class="homedh">
			<div class="hometit1">
				<ul>
					<li class="homesy"><a href="/userdetail.php" class="size14">用户中心</a></li>
					<li class="homesy2"><a target="_blank" href="/modules/article/applywriter.php"
						class="size14">申请作者</a></li>
				</ul>
			</div>
			<div class="cl">
			</div>
			<div>
			</div>
		</div>
		<div class="homedown">
			<!--会员左边-->
			<div class="homeDL">
	<div class="photo">
		<div class="photo_pic">
			<div>
				<a href="touxiang.aspx">
					<img style="width: 80px; height: 80px; border: 1px solid #ccc;" id="imagesrc" src="'.jieqi_geturl('system','avatar',$this->_tpl_vars['uid'],'l',$this->_tpl_vars['avatar']).'" /></a>
			</div>
			<div class="photo_name">'.$this->_tpl_vars['uname'].'
			</div>
		</div>
	</div>
	<div class="homeleft_dh">
		<ul>
			<li class="myaccount"><a href="/userdetail.php" title="账户">账户</a></li>
			<li class="myaccount" style="background-position: 0px -705px;"><a href="/setavatar.php"
				title="头像">头像</a></li>
			<li class="mybookcase"><a href="/modules/article/bookcase.php" title="书架">书架</a></li>
			<li class="mymsg"><a href="/message.php?box=inbox" title="消息">消息</a></li>
			<li class="myfootmark"><a href="/ptopics.php?uid=self" title="会客">会客</a></li>
			<li class="myhelp"><a target="_blank" href="/modules/forum/" title="交流">交流</a></li>
			<li class="zuxiao"><a href="/logout.php" title="注销">注销</a></li>
		</ul>
	</div>
</div>

			<!--会员左边结束-->
			<div class="homeDR">
            <div class="homezhdh">
					<ul>
						<li><a href="/userdetail.php">个人中心</a></li><li><a href="/passedit.php">修改密码</a></li><li><a href="/useredit.php">修改资料</a></li>
					</ul>
				</div>
				<div class="homeDRcon">
					<div class="myinformation">
						<ul>
							<li>用户昵称：<span style="color: red">'.$this->_tpl_vars['uname'].'</span></li>
							<li class="marlet10">用户等级：'.$this->_tpl_vars['group'].'</li>
							<li>用户积分：'.$this->_tpl_vars['score'].'</li>
							<li class="marlet10">头衔：'.$this->_tpl_vars['honor'].'</li>
							<li>性别：'.$this->_tpl_vars['sex'].'</li>
							<li class="marlet10">QQ：'.$this->_tpl_vars['qq'].'</li>
							<li>贡献值：'.$this->_tpl_vars['credit'].'</li>
							<li class="marlet10">经验值：'.$this->_tpl_vars['experience'].'</li>
							<li>最多好友数：'.$this->_tpl_vars['system_maxfriends'].'</li>
							<li class="marlet10">最大消息数：'.$this->_tpl_vars['system_maxmessages'].'</li>
							<li>最大收藏量：'.$this->_tpl_vars['article_maxbookmarks'].'</li>
							<li class="marlet10">每日推荐次数：'.$this->_tpl_vars['article_dayvotes'].'</li>
							<li>注册日期：'.$this->_tpl_vars['regdate'].'</li>
							<li class="marlet10">邮箱：<a href="mailto:'.$this->_tpl_vars['email'].'">'.$this->_tpl_vars['email'].'</a></li>
							<li>VIP类型：';
if($this->_tpl_vars['isvip'] <= 0){
echo '非vip会员';
}else{
echo 'VIP会员';
}
echo '</li>
                            <li class="marlet10">'.$this->_tpl_vars['egoldname'].'：';
if($this->_tpl_vars['jieqi_silverusage']==1){
echo $this->_tpl_vars['emoney'];
}else{
echo $this->_tpl_vars['egold'];
}
echo '</li>
							<li>鸡蛋数：'.$this->_tpl_vars['egg'].'</li>
							<li>鲜花数：'.$this->_tpl_vars['flower'].'</li>

						</ul>
						<div class="cl">
						</div>
					</div>
				</div>
			</div>
			<div class="cl">
			</div>
		</div>
	</div>
';
?>