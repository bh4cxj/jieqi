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
$jieqiCollect['cookiefile']='waau_com'; //是否使用cookie,是的话设置cookie文件标准, 如果设置成 abc 那实际cookie文件是 cookie_abc.php
$jieqiCollect['urlsite']='www.waau.com'; //网址
$jieqiCollect['sitename']='哇噢动漫';  //站名

//***************************************************************************
//漫画信息采集
//***************************************************************************
$jieqiCollect['urlcartoon']='http://www.waau.com/comiclist/<{cartoonid}>/index.htm'; //漫画信息页面<{cartoonid}>
//***************************************************************************
//漫画标题
$jieqiCollect['cartoontitle']=array('left'=>'<P>《', 'right'=>'》~', 'middle'=>'!!!!'); 
//作者
$jieqiCollect['author']=array('left'=>'*作者：', 'right'=>'- 点击：*', 'middle'=>'!!!!');
//关键字
$jieqiCollect['keyword']='';
//简介
$jieqiCollect['intro']=array('left'=>'<div  class=mhjj id=\'ComicInfo\'>', 'right'=>'</div>	  	  </div>		</div>', 'middle'=>'****');
//类型
$jieqiCollect['sort']=array('left'=>'<li>!所属类型：', 'right'=>'<li>', 'middle'=>'!!!!');
//封面图片
$jieqiCollect['cartoonimage']=array('left'=>'class=\'sp1\'><img src=\'', 'right'=>'\' width=\'150\' height=\'200\'', 'middle'=>'~~~~');
//过滤的封面
$jieqiCollect['filterimage']='';
//全文标记
$jieqiCollect['fullcartoon']='';
//漫画类型对应id
$jieqiCollect['sortid']=array('武侠格斗'=>1, '浪漫爱情'=>2, '耽美/BL'=>3, '侦探推理'=>4, '经典喜剧'=>5, '魔幻冒险'=>6, '另类恐怖'=>7, '体育竞技'=>8, '战争灾难'=>9,  'default'=>10);

//***************************************************************************
//漫画目录采集
//***************************************************************************
$jieqiCollect['urlindex']='http://www.waau.com/comiclist/<{cartoonid}>/index.htm'; //漫画目录页面
//$jieqiCollect['indexcharset']='utf8';
//***************************************************************************
//分卷名称
$jieqiCollect['volume']=array('left'=>'<a href=\'/comiclist/~\'>', 'right'=>'</a></dd>', 'middle'=>'~~~~');
//分卷序号
$jieqiCollect['volumeid']=array('left'=>'<a href=\'/comiclist/$/', 'right'=>'/1.htm\'>', 'middle'=>'$$$$');

$jieqiCollect['reverse'] = 1; //对方网站卷是从大到小排列的，我们采集的时候需要从小到大采集
//***************************************************************************
//取得分卷图片数
//***************************************************************************

//$jieqiCollect['urlvolumeimages']=array('http://www.iieye.com/xml/comicVol/<{cartoonid}>.xml', 'http://www.iieye.com/comic/<{volumeid}>/1/index.html'); //分卷图片数
$jieqiCollect['urlvolumeimages']=array('http://www.waau.com/comiclist/<{cartoonid}>/<{volumeid}>/1.htm'); //分卷图片数
//http://www.waau.com/comiclist/1937/11340/1.htm

//http://ct.iieye.com/UserCt/comic/15818/104075/eye0001-15544.JPG|http://ct.iieye.com/UserCt/comic/15818/104075/eye0002-20541.JPG|194|NANA[日文版] — Vol_15|开放漫画
$jieqiCollect['volumeimagenum']=array('left'=>'当前：1 | 共：', 'right'=>' | 上一页&nbsp;', 'middle'=>'$$$$'); //解析图片数

$jieqiCollect['vimagematchall']='0'; //匹配多个，然后统计图片数

//***************************************************************************
//取得分卷图片地址
//***************************************************************************

$jieqiCollect['urlpageimage']='http://www.waau.com/comiclist/<{cartoonid}>/<{volumeid}>/<{pageid}>.htm'; //某页图片地址

$jieqiCollect['pageimageurl']=array('left'=>'<img src=\'', 'right'=>'\' name=ComicPic border=\'0\' id=ComicPic />', 'middle'=>'~~~~'); //解析图片地址
$jieqiCollect['pageimagename']=array('left'=>'<input name="hdCuImgName" id="hdCuImgName" type="hidden" value="', 'right'=>'" />', 'middle'=>'~~~~'); //解析图片数(如果不用采集就留空)

//***************************************************************************
//漫画简介及评论
//***************************************************************************
//$jieqiCollect['urlcartoonxml']='http://www.waau.com/comiclist/<{cartoonid}/index.htm'; //漫画简介评论页面
//$jieqiCollect['introcharset']='utf8';
//***************************************************************************
//简介
//$jieqiCollect['intro']=array('left'=>'class=mhjj id=\'ComicInfo\'>', 'right'=>'</div>	  	  </div>', 'middle'=>'****');
?>