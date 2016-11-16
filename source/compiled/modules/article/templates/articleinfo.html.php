<?php
echo '<link href="/themes/chaoliu/css/qiandaoye.css" rel="stylesheet" />
<link href="/themes/chaoliu/css/menu.css" rel="stylesheet" />

<script type="text/javascript" src="/themes/chaoliu/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/themes/chaoliu/js/qiandaoye.js"></script>
<script type="text/javascript" src="/themes/chaoliu/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="/themes/chaoliu/js/guide-tab.js"></script>

<script type="text/javascript">var cmsUrl = "'.$this->_tpl_vars['jieqi_siteurl'].'";</script>
<script src="/themes/chaoliu/js/commu.js" type="text/javascript" language="javascript"></script>

<script type="text/javascript">
var c, _ = Function;
window.onload=function()
{ 
var num = $(\'.dongtai_list_l\').length;
if(num > 5){
  with (o = document.getElementById("dongtai_list")) { innerHTML += innerHTML; onmouseover = _("c=1"); onmouseout = _("c=0"); } 
  (F = _("if(#%46||!c)#++,#%=o.scrollHeight>>1;setTimeout(F,#%46?10:3000);".replace(/#/g, "o.scrollTop")))(); 
  }
}
</script> 
</head>

<body>

<SCRIPT LANGUAGE="JavaScript"> 
var c=0
function votes(obj) { 
var limit = document.getElementById("num").innerHTML;
obj.checked?c++:c--; 
if(c>limit)obj.checked=false,c--,alert("最多选择 "+limit+" 项，请重新选择"); 


} 
</script>
<script type="text/javascript" src="/themes/chaoliu/js/floatwin.js"></script>
<style>
        *{
            margin:0;
            padding:0;
        }
        ul,ol {list-style:none;
        }
        .clear {
            clear:both;
        }
        body {
            font-family:"微软雅黑", "宋体";
            font-size:12px;
            color:#666666;
            height:1500px;
        }
        .black_overlay{ 
            display: none; 
            position: fixed; 
            top: 0%; 
            left: 0%; 
            width: 100%; 
            height: 100%; 
            background-color: black; 
            z-index:1001; 
            -moz-opacity: 0.6; 
            opacity:.60; 
            filter: alpha(opacity=60); 
        } 
        .white_content { 
            display: none; 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            width: 588px; 
            height: 388px; 
            margin-left:-325px;
            margin-top:-225px;
            padding:25px;
            border: 6px solid orange; 
            background-color:#fff; 
            z-index:1002; 
            overflow: auto; 
        } 
        .white_content2 { 
            display: none; 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            width: 588px; 
            height: 288px; 
            margin-left:-325px;
            margin-top:-175px;
            padding:25px;
            border: 6px solid orange; 
            background-color:#fff; 
            z-index:1002; 
            overflow: auto; 
        } 
        .white_content4 { 
            display: none; 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            width: 588px; 
            height: 288px; 
            margin-left:-325px;
            margin-top:-175px;
            padding:25px;
            border: 6px solid orange; 
            background-color:#fff; 
            z-index:1002; 
            overflow: auto; 
        } 
        .white_content3 { 
            display: none; 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            width: 588px; 
            height: 288px; 
            margin-left:-325px;
            margin-top:-175px;
            padding:25px;
            border: 6px solid orange; 
            background-color:#fff; 
            z-index:1002; 
            overflow: auto; 
        } 
        .white_content_con {
            position:relative;
            width: 588px; 
            height: 288px;
        }
        .caozuo_title {
            height:31px;
            border-bottom:2px solid orange;
        }
        .caozuo_title li {
            float:left;
        }

        .caozuo_con {

        }
        .close {
            position:absolute;
            right:-10px;
            top:-10px;
        }
        .zanmei {
            width: 523px; 
            height:70px;
            line-height:40px;
            font-size:20px;
            background:url(/themes/chaoliu/images/tanchu_03.gif) repeat-x 0px 50px;
            font-weight:bold;
            color:#F93;
            padding-left:65px;
        }
        .chengse {
            color:orange;
            font-weight:bold;
            padding-left:3px;
            padding-right:3px;
        }
        .fudaipinglun {
            margin: 5px 0px 5px 5px;
            width: 335px;
            height: 80px;
            color: #999999;
            padding: 5px;
            font-size:12px;
        }
        .queren_btn {
            width:129px;
            height:30px;
            border:none;
            float:left;
            cursor:pointer;
            margin-top:15px;
        }
        #querendashang{
            background:url(/themes/chaoliu/images/querendashang_07.gif) no-repeat left top;
            float:right;
        }
        #querentuijian {
            background:url(/themes/chaoliu/images/querentuijian.gif) no-repeat left top;
        }
        #querentoupiao {
            background:url(/themes/chaoliu/images/querentoupiao.gif) no-repeat left top;
        }
        #querenzengsong {
            background:url(/themes/chaoliu/images/querenzengsong.gif) no-repeat left top;
        }
        .queren_btn:hover {
            position:relative;
            left:1px;
            top:1px;
        }
        .pingluntishi {
            width:300px;
            float:left;
            text-align:left;
            padding-top:15px;
        }
        .tips {
            margin: 15px 0px 15px 0px;
            background: url(/themes/chaoliu/images/tips_11.gif) left center no-repeat;
            padding-left: 25px;
        }
        .tui {
            position:absolute;
            left:375px;
            top:95px;
        }
        .xiancao {
            position:absolute;
            left:400px;
            top:88px;
        }
        .shang {
            position:absolute;
            left:385px;
            top:100px;
        }
        .piao {
            position:absolute;
            left:340px;
            top:115px;
        }
        .plus {
            position:absolute;
            left:0px;
            top:-5px;
        }
		.caozuo_con{text-align:left;}
		.maincon_r li{width:200px;padding-left:10px;}
		#info_bt span {
color: black;
float: left;
width: 56px;
height: 20px;
padding-top: 60px;
margin-left: 7px;
text-align: center;
cursor: pointer;
}
#bt_flower {
background: url(/images/flower.png) -1px 0 no-repeat;
}
#bt_egg {
background: url(/images/egg.png) -1px 0 no-repeat;
}
#dongtai li{margin-left:none;}
    </style> 
<script>
function checkadd(){
        var str = $("#pcontent").val();  
        var str_temp = str.replace(/[\\u4e00-\\u9fa5]/g, \'aa\');//将汉字替换为aa  
        if(str_temp.length < 10 ){
            alert("评论内容过少，不能少于10字符");
            $("#pcontent").focus();
            return false;
        }
         if(str_temp.length > 1000 ){
            alert("评论内容过多，不能超过1000字符");
            $("#pcontent").focus();
            return false;
        }
         if ($("#ptitle").val() == \'\'){
            alert("请填写标题");
            $("#ptitle").focus();
            return false;
    }
     if ($("#pcontent").val() == \'\'){
        alert("请填写评论内容");
        $("#pcontent").focus();
        return false;
    }
        return true;
    
    
}
</script>
<div id="main">
    <div class="main_inner">
        <a href="" >
        <img src="/themes/chaoliu/images/ad.jpg" class="box"/>
        </a>
        <div id="maincon" class="box">
            <div id="maincon_l" >
                <div class="book_info">
                    <div class="book_info_top">
                          ';
if($this->_tpl_vars['fullflag'] == '连载中'){
echo '
						  <img src="/themes/chaoliu/images/lianzai_03.gif"  class="book_xingzhi"/>
						  ';
}else{
echo '
						<img src="/themes/chaoliu/images/wanjie_03.gif"  class="book_xingzhi"/>
						  ';
}
echo '
                        <div style="height:30px; line-height:30px;padding-bottom:15px;float:left;">
                            <span >当前位置&gt;&gt;</span>  <a href="/">首页</a> > '.$this->_tpl_vars['sort'].' > '.$this->_tpl_vars['articlename'].'</div>
							<div style="float:right;margin-right:100px;margin-top:10px;">本书书号:'.$this->_tpl_vars['articleid'].'</div>
							<div class="clear"></div>
                        <div class="book_info_top_l">
                            <img src="'.$this->_tpl_vars['url_simage'].'"  width="199" height="265" title="">                            <ul class="gezhongpiao">
                                <li><a href = "'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/tip.php?id='.$this->_tpl_vars['articleid'].'"  class="woyaodashang"></a></li>
                                <li><a href = "'.$this->_tpl_vars['url_uservote'].'"  class="toutuijianpiao"></a></li>
								<div id="info_bt">
        <a href="'.$this->_tpl_vars['article_static_url'].'/othervote.php?id='.$this->_tpl_vars['articleid'].'&type=flower"><span id="bt_flower" title="投鲜花">('.$this->_tpl_vars['flower'].')</span></a>
        <a href="'.$this->_tpl_vars['article_static_url'].'/othervote.php?id='.$this->_tpl_vars['articleid'].'&type=egg"><span id="bt_egg" title="扔鸡蛋">('.$this->_tpl_vars['egg'].')</span></a>
        </div>
        <div style=""><a href="'.$this->_tpl_vars['article_static_url'].'/cuigeng.php?id='.$this->_tpl_vars['articleid'].'" style="border:0;"><img src="/images/cuigeng.jpg" /></a></div>

                                 <input type="hidden" id="zuozhe" value="'.$this->_tpl_vars['authorid'].'"/>
                                 <input type="hidden" id="author" value="'.$this->_tpl_vars['author'].'"/>
                                  <input type="hidden" id="subject" value="'.$this->_tpl_vars['articlename'].'"/>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class="book_info_top_r">
                            <div class="book_title">
                                <h3 class="book_name"><a >'.$this->_tpl_vars['articlename'].'</a></h3>
                                <p class="author">作者：'.$this->_tpl_vars['author'].'</p>
                                <div class="clear"></div>
                            </div>
                            <ul class="gezhongxinxi">
                                <li class="zuopinjianjie">
                                    <p class="xinxi_title">作品简介</p>
                                    <div class="xinxi_content">
                                        <p class="jianjieneirong">'.$this->_tpl_vars['intro'].'</p>
                                        <p class="tixing">本站提醒：本故事纯属虚构，如有雷同，纯属巧合。切勿模仿！</p>
                                        <p class="biaoqian" style="display:none">标签：'.$this->_tpl_vars['keywords'].'</p>
                                    </div>
                                </li>
                                <li class="zuopinxinxi">
                                    <p class="xinxi_title">作品信息</p>
                                    <ul class="xinxi_content" style="display:none">
                                        <li>作品分类：<span class="red">'.$this->_tpl_vars['sort'].'</span></li>
                                        <li>授权状态：<span class="red">'.$this->_tpl_vars['permission'].'</span></li>
									    <li>首发状态：<span class="red">'.$this->_tpl_vars['firstflag'].'</span></li>
                                        <li>总字数：<span class="red">'.$this->_tpl_vars['size_c'].'字</span></li>
                                        <li>总点击：<span class="red">'.$this->_tpl_vars['allvisit'].'</span></li>
                                        <li>月点击：<span class="red">'.$this->_tpl_vars['monthvisit'].'</span></li>
                                        <li>周点击：<span class="red">'.$this->_tpl_vars['weekvisit'].'</span></li>
                                        <li>总收藏：<span class="red">'.$this->_tpl_vars['goodnum'].'</span></li>
                                        <li>总推荐数：<span class="red">'.$this->_tpl_vars['allvote'].'</span></li>
                                        <li>本月推荐：<span class="red">'.$this->_tpl_vars['monthvote'].'</span></li>
                                        <li>本周推荐：<span class="red">'.$this->_tpl_vars['weekvote'].'</span></li>
                                        <li>最后更新：<span class="red">'.$this->_tpl_vars['lastupdate'].'</span></li>
                                        <div class="clear"></div>
                                    </ul>
                                </li>

                                <li class="zuozhexinxi">
                                    <p class="xinxi_title">作者信息</p>
                                    <dl class="xinxi_content" style="display:none">
                                        <dt>作者的话:</dt>
                                        <dd>'.$this->_tpl_vars['notice'].'</dd>
                                    </dl>
                                </li>
                                <li class="update_time">创建时间  '.$this->_tpl_vars['postdate'].'</li>
                            </ul>
                            <ul class="gezhonganniu">
                                <li class="dianjiyuedu"><a href="/modules/article/reader.php?aid='.$this->_tpl_vars['articleid'].'&cid='.$this->_tpl_vars['firstzhangjieId'].'" target="_blank"></a></li>
                                 <li class="xiaoshuomulu"><a href="'.$this->_tpl_vars['url_read'].'" target="_blank"></a></li>
                                <li class="shoucangbenshu"><a href="'.$this->_tpl_vars['url_bookcase'].'"></a></li>
                                <!--<li class="xiazaiyuedu"><a href="#"></a></li>-->
                                <div class="clear"></div>
                            </ul>
                        </div>
                    </div>
                    <div class="shidu">
                        <h4 class="shidu_title"><a>《'.$this->_tpl_vars['articlename'].'》最新章节试读</a></h4>
						';
if (empty($this->_tpl_vars['indexrows'])) $this->_tpl_vars['indexrows'] = array();
elseif (!is_array($this->_tpl_vars['indexrows'])) $this->_tpl_vars['indexrows'] = (array)$this->_tpl_vars['indexrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['indexrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['indexrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['indexrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['indexrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['indexrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
							<dl class="new_zhangjie';
if($this->_tpl_vars['i']['order']==1){
echo '1';
}else{
echo '2';
}
echo '">	<dt><span class="dt_l"><a href=\'/files/article/html/'.subdirectory($this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['articleid']).'/'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['articleid'].'/'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['chapterid'].'.html\' target=\'_blank\'>'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['chaptername'].'</a><span class="zishu">('.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['size'].'字)</span></span><span class="dt_r">'.date('m-d H:i',$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['lastupdate']).'</span>		<div class="clear"></div>	 </dt>
							<dd>
							';
if($this->_tpl_vars['i']['order']==1){
echo '
						<iframe src="/modules/article/contentnew.php?articleid='.$this->_tpl_vars['articleid'].'&lastchapterid='.$this->_tpl_vars['lastchapterid'].'" allowtransparency frameborder="0" marginheight="0" marginwidth="0" scrolling="no" width="528" height="75" style="margin-left:10px;margin-top:8px;"></iframe>
							';
}
echo '
							</dd>  </dl>
						';
}
echo '

                    </div>
                    
                    <div class="pinglunqu">
                        <div class="quanbupinglun">
                            <h4 class="shupingqu_title"><a>《'.$this->_tpl_vars['articlename'].'》书评区</a></h4>
                            <div class="pinglunkuang">
                                <form name="frmreview" method="post" action="'.$this->_tpl_vars['url_review'].'" onsubmit="return checkadd()" target="_blank">
                                <p class="biaoti_title">标题：</p><input name="ptitle" type="text" class="biaoti" id="title" value="" maxlength="30"/>
                                <p class="duihuakuang_title">内容：</p><textarea class="duihuakuang" name="pcontent"  id="content" title="评论内容"></textarea>
								<p><script language="javascript">loadJs("'.$this->_tpl_vars['jieqi_url'].'/scripts/ubbeditor_'.$this->_tpl_vars['jieqi_charset'].'.js", function(){UBBEditor.Create("pcontent");});</script></p>
                                <div class="clear"></div>
                                <div class="submit">
                                     <input type="hidden" name="action" id="action" value="newpost" />
                                     <input type="submit" name="newcommu" value="提交" class="tijiao" />
                          <img src="'.$this->_tpl_vars['jieqi_url'].'/checkcode.php" alt="点击更换验证码" style="vertical-align:middle;cursor:pointer;" onClick="this.src=\''.$this->_tpl_vars['jieqi_url'].'/checkcode.php?rand=\'+Math.random();" />
                                        <p class="yanzhengma" >验证码：<input type="text" name="checkcode" size="7" maxlength="4" id="regcode" tabindex="2" value="" class="input"></p>
                                        <div class="clear"></div>
                                </div>
                                </form>
                            </div>
                            
                            <div class="quanbupinglun_title">	<p class="qbpl_l"><a href="'.$this->_tpl_vars['url_allreview'].'">查看所有评论</a></p>	
							<div class="clear"></div></div>
<ul class="comment">

';
if (empty($this->_tpl_vars['reviewrows'])) $this->_tpl_vars['reviewrows'] = array();
elseif (!is_array($this->_tpl_vars['reviewrows'])) $this->_tpl_vars['reviewrows'] = (array)$this->_tpl_vars['reviewrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['reviewrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['reviewrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['reviewrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['reviewrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['reviewrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
<li>
<div class="avatar">
<img src="'.jieqi_geturl('system','avatar',$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posterid'],'s').'" width="48" height="48" />
</div>
<div class="comment_r">
<div class="pinglun_f1">
<p class="commenter">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['poster'].'</p>
<p class="floor"></p>
<div class="clear"></div>
</div>
<p class="pinglun_f2">';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['istop'] == 1){
echo '<span class="hottext">[顶]</span>';
}
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['isgood'] == 1){
echo '<span class="hottext">[精]</span>';
}
echo '<a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/reviewshow.php?rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'" target="_blank">'.str_replace('<br />',' ',$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['title']).'</a></p>
<div class="pinglun_f3">
<p class="date">'.date('m-d H:i',$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['replytime']).'</p>
<p class="support">回复('.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['replies'].')/查看('.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['views'].')</p>
<div class="clear"></div>
</div></div>
<div class="clear"></div>
</li>
';
}
echo '
</ul>
                        </div>
                        
                    </div>
                </div>
                
                
               
            </div>
            <div class="maincon_r" >
                <div class="duzhedongtai">
                    <div class="duzhe_title" >
                    读者动态
                    </div>
						';
if($this->_tpl_vars['tiprows'] != ""){
echo '
						';
if (empty($this->_tpl_vars['tiprows'])) $this->_tpl_vars['tiprows'] = array();
elseif (!is_array($this->_tpl_vars['tiprows'])) $this->_tpl_vars['tiprows'] = (array)$this->_tpl_vars['tiprows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['tiprows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['tiprows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['tiprows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['tiprows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['tiprows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
						<div style="text-align: left;padding: 5px 30px;"><span class="fr">打赏<b>'.$this->_tpl_vars['tiprows'][$this->_tpl_vars['i']['key']]['shu'].'</b>个'.$this->_tpl_vars['egoldname'].'</span>'.$this->_tpl_vars['tiprows'][$this->_tpl_vars['i']['key']]['name'].'</div>
						';
}
echo '
						';
}else{
echo '
						<div style="padding:10px;"><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/tip.php?id='.$this->_tpl_vars['articleid'].'">我来做第一个打赏的粉丝</a></div>
						';
}
echo '
                </div>
                
                <div class="zuozhetuijian">
                    <div class="zztj_title">作者所有作品</div>
                    <ul class="zztj_list">
					';
if (empty($this->_tpl_vars['authother'])) $this->_tpl_vars['authother'] = array();
elseif (!is_array($this->_tpl_vars['authother'])) $this->_tpl_vars['authother'] = (array)$this->_tpl_vars['authother'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['authother']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['authother']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['authother']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['authother']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['authother']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
							<li><a href="/modules/article/articleinfo.php?id='.$this->_tpl_vars['authother'][$this->_tpl_vars['i']['key']]['articleid'].'">'.$this->_tpl_vars['authother'][$this->_tpl_vars['i']['key']]['articlename'].'</a></li>
					';
}
echo '
                        
                    </ul>
                </div>
                
                <div class="tongleizuopin">
                    <div class="tlzp_title">您可能还喜欢</div>
                    <ul class="tlzp_list">
                        '.jieqi_get_block(array('bid'=>'89', 'module'=>'article', 'filename'=>'block_articlelist', 'classname'=>'BlockArticleArticlelist', 'side'=>'0', 'title'=>'', 'vars'=>'allvisit,5,0,0,0,0', 'template'=>'xinxi_side.html', 'contenttype'=>'4', 'custom'=>'0', 'publish'=>'3', 'hasvars'=>'3'), 1).'
                    </ul>
                </div>
                
            <div class="clear"></div>
        </div>
    </div>
</div>

    </div>
</div>

    </body> 
</html>';
?>