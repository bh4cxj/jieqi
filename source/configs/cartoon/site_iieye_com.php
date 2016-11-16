<?php
//***************************************************************************
//基本配置
//***************************************************************************
$jieqiCollect['pagecharset'] = ''; //默认编码
$jieqiCollect['referer']=0; //是否发送referer
$jieqiCollect['proxy_host']=''; //代理服务器设置
$jieqiCollect['proxy_port']='';
$jieqiCollect['proxy_user']='';
$jieqiCollect['proxy_pass']='';
$jieqiCollect['loginexpire']=3600; //超时时间
$jieqiCollect['cookiefile']='iieye_com'; //是否使用cookie,是的话设置cookie文件标准, 如果设置成 abc 那实际cookie文件是 cookie_abc.php
$jieqiCollect['urlsite']='http://www.iieye.com'; //网址
$jieqiCollect['sitename']='精明眼';  //站名

//***************************************************************************
//漫画信息采集
//***************************************************************************
$jieqiCollect['urlcartoon']='http://www.iieye.com/comic/<{cartoonid}>/index.html'; //漫画信息页面
//***************************************************************************
//漫画标题
$jieqiCollect['cartoontitle']=array('left'=>'<span id="spTitle">', 'right'=>'</span>', 'middle'=>'!!!!'); 
//作者
$jieqiCollect['author']=array('left'=>'<a target=_blank href=\'/author/goauthor.aspx?author=~\'>', 'right'=>'</a>', 'middle'=>'!!!!');
//关键字
$jieqiCollect['keyword']='';
//简介
//$jieqiCollect['intro']=array('left'=>'<TD vAlign="top"><span id="labMemo" style="color:#404040;">', 'right'=>'</span></TD>!<TD width="7" align="right"><IMG src="/img/1point.gif"></TD>', 'middle'=>'****');
//类型
$jieqiCollect['sort']=array('left'=>'<li>!所属类型：', 'right'=>'<li>', 'middle'=>'!!!!');
//封面图片
$jieqiCollect['cartoonimage']=array('left'=>'<input name="hdID" id="hdID" type="hidden" value="$" /><input name="hdDefaultImg" id="hdDefaultImg" type="hidden" value="', 'right'=>'" />', 'middle'=>'~~~~');
//过滤的封面
$jieqiCollect['filterimage']='';
//全文标记
$jieqiCollect['fullcartoon']='';
//漫画类型对应id
$jieqiCollect['sortid']=array('武侠格斗'=>1, '浪漫爱情'=>2, '耽美/BL'=>3, '侦探推理'=>4, '经典喜剧'=>5, '魔幻冒险'=>6, '另类恐怖'=>7, '体育竞技'=>8, '战争灾难'=>9,  'default'=>10);

//***************************************************************************
//漫画目录采集
//***************************************************************************
$jieqiCollect['urlindex']='http://www.iieye.com/xml/comicVol/<{cartoonid}>.xml'; //漫画目录页面
$jieqiCollect['indexcharset']='utf8';
//***************************************************************************
//分卷名称
$jieqiCollect['volume']=array('left'=>'<voldata id="~" cTitle="', 'right'=>'" cUserID="~">', 'middle'=>'~~~~');
//分卷序号
$jieqiCollect['volumeid']=array('left'=>'<voldata id="', 'right'=>'" cTitle="~" cUserID="~">', 'middle'=>'$$$$');

//***************************************************************************
//取得分卷图片数
//***************************************************************************

//$jieqiCollect['urlvolumeimages']=array('http://www.iieye.com/xml/comicVol/<{cartoonid}>.xml', 'http://www.iieye.com/comic/<{volumeid}>/1/index.html'); //分卷图片数
$jieqiCollect['urlvolumeimages']=array('http://www.iieye.com/comic/viewcomic.aspx?&ID=<{volumeid}>', 'http://www.iieye.com/comic/<{volumeid}>/1/index.html'); //分卷图片数
//$jieqiCollect['urlvolumeimages']=array('http://www.iieye.com/comic/<{volumeid}>/<{pageid}>/index.html', 'http://www.iieye.com/ct/xmlgetViewTxt.aspx?pi=1'); //分卷图片数

//http://ct.iieye.com/UserCt/comic/15818/104075/eye0001-15544.JPG|http://ct.iieye.com/UserCt/comic/15818/104075/eye0002-20541.JPG|194|NANA[日文版] — Vol_15|开放漫画
$jieqiCollect['volumeimagenum']=array('left'=>'<input name="hdDataCount" id="hdDataCount" type="hidden" value="', 'right'=>'" />', 'middle'=>'$$$$'); //解析图片数

$jieqiCollect['vimagematchall']='0'; //匹配多个，然后统计图片数

//***************************************************************************
//取得分卷图片地址
//***************************************************************************

$jieqiCollect['urlpageimage']='http://www.iieye.com/comic/<{volumeid}>/<{pageid}>/index.html'; //某页图片地址

$jieqiCollect['pageimageurl']=array('left'=>'<input name="hdUrl" id="hdUrl" type="hidden" value="', 'right'=>'" />', 'middle'=>'~~~~'); //解析图片数(如果不用采集就留空)
$jieqiCollect['pageimagename']=array('left'=>'<input name="hdCuImgName" id="hdCuImgName" type="hidden" value="', 'right'=>'" />', 'middle'=>'~~~~'); //解析图片数(如果不用采集就留空)

//***************************************************************************
//漫画简介及评论
//***************************************************************************
$jieqiCollect['urlcartoonxml']='http://www.iieye.com/xml/comicMemo/<{cartoonid}>.xml'; //漫画简介评论页面
$jieqiCollect['introcharset']='utf8';
//***************************************************************************
//简介
$jieqiCollect['intro']=array('left'=>'<cTxt><![CDATA[', 'right'=>']]></cTxt>', 'middle'=>'****');
?>