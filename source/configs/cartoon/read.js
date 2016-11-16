//图片根目录
var imageurl=siteurl+'/files/cartoon/page/'+Math.floor(cartoonid / 1000)+'/'+cartoonid+'/'+volumeid+'/';

//图片状态位置
var Obj='';
var imgstatus=0;
var pX;
var pY;
var mycartoonid;
var volumeid;
if(remotepicurl != '')
{
	imageurl='http://'+remotepicurl+'/'+remotepicpath+'/'+Math.floor(cartoonid / 1000)+'/'+cartoonid+'/'+volumeid+'/';
}

//默认页码
var page=getParameter('p');
page = parseInt(page);
imagenum = parseInt(imagenum);
if(page == null || isNaN(page) || page <= 0 || page > imagenum) page=1;


//显示默认图片

function pageload(){
	var readdiv=document.getElementById('readdiv');
	readdiv.innerHTML='正在载入图片，请稍候...';
	if(getreadcookie('imgW') != null)
	{
		readdiv.innerHTML='<img id="readimage" src="'+imageurl+page+'.pic" border="0" onmousedown="imgdown(this)" onmousemove="imgmove()" onmouseup="imgup()" style="position:relative;" width="'+getreadcookie('imgW')+'" height="'+getreadcookie('imgH')+'" onload="fimg()" onmouseout="imgup()">';
	}
	else
	{
		readdiv.innerHTML='<img id="readimage" src="'+imageurl+page+'.pic" border="0" onmousedown="imgdown(this)" onmousemove="imgmove()" onmouseup="imgup()" style="position:relative;" onload="fimg()" onmouseout="imgup()">';
	}
	var loadinginfo=document.getElementById('loadinginfo');
	npage=page+1;
	MM_preloadImages(imageurl+npage+'.pic');
}

function fimg()
{
	cartoonpic = document.getElementById("readimage");
	if(getreadcookie('selfW')!=null)
	{
		selfW=getreadcookie('selfW');
		selfH=getreadcookie('selfH');
	}
	else
	{
		picW = cartoonpic.width;
		picH = cartoonpic.height;
		selfW = picW;
		selfH = picH;
	}
}

function gimg()
{
	cartoonpic = document.getElementById("readimage");
	selfW2 = cartoonpic.width;
	selfH2 = cartoonpic.height;
}

//预载入图片
function MM_preloadImages() { //v3.0
var d=document; if(d.images){ 
if(!d.MM_p) d.MM_p=new Array();
var i,j=d.MM_p.length,a=MM_preloadImages.arguments;
for(i=0; i<a.length; i++)
if (a[i].indexOf("#")!=0){ 
d.MM_p[j]=new Image; 
d.MM_p[j++].src=a[i];
}
}
}



//显示VIP图片
function vippageload(){
	var readdiv=document.getElementById('readdiv');
	readdiv.innerHTML='正在载入图片，请稍候...';
	
	if(getreadcookie('imgW') != null)
	{
		
		readdiv.innerHTML='<img id="readimage" src="'+siteurl+'/modules/cartoon/vipimg.php?aid='+cartoonid+'&vid='+volumeid+'&p='+page+'" border="0" onmousedown="imgdown(this)" width="'+getreadcookie('imgW')+'" height="'+getreadcookie('imgH')+'"   onmousemove="imgmove()" onmouseup="imgup()" onmouseout="imgup()">';
	}
	else
	{
		readdiv.innerHTML='<img id="readimage" src="'+siteurl+'/modules/cartoon/vipimg.php?aid='+cartoonid+'&vid='+volumeid+'&p='+page+'" border="0" onmousedown="imgdown(this)"   onmousemove="imgmove()" onmouseup="imgup()" onmouseout="imgup()">';
	}
	
	
	readdiv.innerHTML='<img id="readimage" src="'+siteurl+'/modules/cartoon/vipimg.php?aid='+cartoonid+'&vid='+volumeid+'&p='+page+'" border="0" onmousedown="imgdown(this)"   onmousemove="imgmove()" onmouseup="imgup()" onmouseout="imgup()">';
	
	var loadinginfo=document.getElementById('loadinginfo');
	
	cartoonpic = document.getElementById("readimage");
	picW = cartoonpic.width;
	picH = cartoonpic.height;
	selfW = picW;
	selfH = picH;	
		
	npage=page+1;
	MM_preloadImages(siteurl+'/modules/cartoon/vipimg.php?aid='+cartoonid+'&vid='+volumeid+'&p='+npage);
}

//移动图片

//图片上按下鼠标
function imgdown(Object){
	Obj=Object.id;
	document.all(Obj).setCapture();
	pX=event.clientX-document.all(Obj).style.pixelLeft;
	pY=event.clientY-document.all(Obj).style.pixelTop;
}

function imgmove(){
	if(Obj!='')
	{
	document.all(Obj).style.left=event.clientX-pX;
	document.all(Obj).style.top=event.clientY-pY;
	}
}

//图片上释放鼠标
function imgup(){
	if(Obj!='')
	{
		document.all(Obj).releaseCapture();
		Obj='';
	}
}

function zoom()
{
	gimg();
	if (selfH2 > 0 && selfW2 > 0)
		{
			selfH2 = selfH2 * 130 / 100;
			selfW2 = selfW2*130/100;
			cartoonpic.height = selfH2;
			cartoonpic.width = selfW2;
		}
		saveimg();
}

function mini()
{
	gimg();
	if (selfH2 > 0 && selfW2 > 0)
		{
			if (selfH2 > 100 || selfW2 > 100)
			{
				selfH2 = selfH2 * 70 / 100;
				selfW2 = selfW2 * 70 / 100;
				cartoonpic.height = selfH2;
				cartoonpic.width = selfW2;
			}
		}
	saveimg();
}

function self()
{
	document.all('readimage').style.left=0;
	document.all('readimage').style.top=0;
	cartoonpic.height = selfH;
	cartoonpic.width = selfW;
	saveimg();
}

function wellsize()
{
	document.all('readimage').style.left=0;
	document.all('readimage').style.top=0;

	var oBody = document.body;

	var tH = oBody.clientHeight;
	var tW;
	if (tH > 700)
	{
		tW = 900;
	}
	else
	{
		tW = 900;
	}
	//var tH =	500;
	//var tW = 800;
	//document.write("here:"+tH);

	if (selfH > tH)	
	{
		cartoonpic.height = tH;
		cartoonpic.width = selfW*tH/selfH;
	}
	else if (selfW > tW)
	{
		cartoonpic.width = tW;
		cartoonpic.height= selfH*tW/selfW; 
	}
	saveimg();
}

function saveimg()//记录图片大小到cookie
{
	cartoonpic = document.getElementById("readimage");
	selfW2 = cartoonpic.width;
	selfH2 = cartoonpic.height;
	setreadcookie('imgW',selfW2);
	setreadcookie('imgH',selfH2);
	if(getreadcookie('selfW')==null)
	{
		setreadcookie('selfW',selfW);
		setreadcookie('selfH',selfH);
	}
}



//显示翻页和跳转
function showpagelink(){
	document.write('<input type="button" class="button" value="放大图片" onClick="javascript:zoom()"> ');
	
	document.write('<input type="button" class="button" value="缩小图片" onClick="javascript:mini()"> ');
	
	document.write('<input type="button" class="button" value="还原图片" onClick="javascript:self()"> ');
	
	document.write('<input type="button" class="button" value="最适大小" onClick="javascript:wellsize()"> ');
	
	document.write('<input name="prevpage" type="button" class="button" value="上一页" onClick="javascript:prevpage()"> ');

	document.write('<select name="jumppage" class="select" onChange="javascript:jumppage(this.value)">');
	for (i=1;i<=imagenum;i++){
		document.write('<option value="'+ i +'"');
		if(i==page) document.write(' selected');
		document.write('>第'+ i +'页</option>');
	}
	document.write('</select>');

	document.write(' <input name="nextpage" type="button" class="button" value="下一页" onClick="javascript:nextpage()">');
}

//页面跳转
function jumppage(v){
	if(v > 0 && v <= imagenum){
		page = v;
		showimg(v);
	}
}

//取地址栏参数
function getParameter(varName){
	var query = location.search;
	if (query != null || query != "")
	{
		query = query.replace(/^\?+/, "");
		var qArray = query.split("&");
		var len = qArray.length;
		if (len > 0)
		{
			for (var i=0; i<len; i++)
			{
				var sArray = qArray[i].split("=", 2);
				if (sArray[0] && sArray[1] && sArray[0] == varName)
				{
					return unescape(sArray[1]);
				}
			}
		}
	}
	return null;
}

//显示图片
function showimg(v){
	url = location.search;
	vv=getParameter("p");
	if(vv!=null){
		search='p='+vv;
		replace='p='+v;
		var regex = new RegExp(search, "g");
		url=url.replace(regex, replace);
	}else{
		url=url+'&p='+v;
	}
	for(i=0; i<document.getElementsByName('jumppage').length; i++) document.getElementsByName('jumppage')[i].value=v;
	window.location=url;
}

//上一页
function prevpage(){
	page--;
	if (page < 1){
		alert('已经到第一页了');
		page = 1;
	}else{
		showimg(page);
	}
}

//下一页
function nextpage(){
	page++;
	if (page > imagenum ){
		alert('已经到最后一页了');
		page = imagenum;
	}else{
		showimg(page);
	}
}

//按键锁定
function keydown(){
	if(event.ctrlKey == true || event.shiftKey == true)	return false;
}

//键盘事件
function keyup(){
	if (event.keyCode == 188){
		prevpage();
	}
	if (event.keyCode == 190 || window.event.keyCode == 32){
		nextpage();
	}
	if(event.keyCode == 187 && document.images['readimage'].width < 2000){
		document.images['readimage'].width = document.images['readimage'].width * 1.2
	}	
	if( event.keyCode == 189 && document.images['readimage'].width > 200){
		document.images['readimage'].width = document.images['readimage'].width / 1.2
	}
}

//设置cookie
function setreadcookie(cname, cvalue) {
	var expdate = new Date((new Date()).getTime() + (24 * 60 * 60 * 1000 * 1));
	document.cookie = cname + "=" + escape(cvalue) + ";expires="+expdate.toGMTString() +";";
}

//读取cookie
function getreadcookie(cname)	{
	var aCookie = document.cookie.split("; ");
	for (var i=0; i < aCookie.length; i++) {
		var aCrumb = aCookie[i].split("=");
		if (cname == aCrumb[0]) 
		return unescape(aCrumb[1]);
	}
	return null;
}

//加入书签
function addbookmark(){
	var url=siteurl+'/modules/cartoon/addbookcase.php?bid='+cartoonid+'&vid='+volumeid+'&page='+page;
	window.open(url);
}