function $id(d){return typeof d == 'string' ? document.getElementById(d) : d}
function $ce(tag){return document.createElement(tag)}
function isUndefined(v){return typeof v == 'undefined'}

top.__08CMS_TOP_INFO__ || (top.__08CMS_TOP_INFO__ = {'_INFOS_' : {}});
var _08CMS_ = top.__08CMS_TOP_INFO__, undefined;
_08CMS_.top || (_08CMS_.top = top);
_08CMS_.set = function(key, val){if(!this._INFOS_[key] || this._INFOS_[key].window === window)this._INFOS_[key] = {'window' : window, 'value' : val};return this._INFOS_[key].value};
_08CMS_.get = function(key){return this._INFOS_[key] ?  this._INFOS_[key].value : undefined};
_08CMS_.set('window', window);
$WE = _08CMS_.set('$id', $id);
if(!$WE.elements){
	$WE.index = 999;
	$WE.elements = {};
}
var _ua = function(){
	var E={ie:0,opera:0,gecko:0,webkit:0,mobile:null,air:0,caja:0},F=navigator.userAgent,D;
	if((/KHTML/).test(F)){E.webkit=1}
	D=F.match(/AppleWebKit\/([^\s]*)/);
	if(D&&D[1]){
		E.webkit=parseFloat(D[1]);
		if(/ Mobile\//.test(F)){E.mobile="Apple"}else{
			D=F.match(/NokiaN[^\/]*/);
			if(D){E.mobile=D[0]}
		}
		D=F.match(/AdobeAIR\/([^\s]*)/);
		if(D){E.air=D[0]}
	}
	if(!E.webkit){
		D=F.match(/Opera[\s\/]([^\s]*)/);
		if(D&&D[1]){
			E.opera=parseFloat(D[1]);
			D=F.match(/Opera Mini[^;]*/);
			if(D){E.mobile=D[0]}
		}else{
			D=F.match(/MSIE\s([^;]*)/);
			if(D&&D[1]){E.ie=parseFloat(D[1])}else{
				D=F.match(/Gecko\/([^\s]*)/);
				if(D){
					E.gecko=1;
					D=F.match(/rv:([^\s\)]*)/);
					if(D&&D[1]){E.gecko=parseFloat(D[1])}
				}
			}
		}
	}
	D=F.match(/Caja\/([^\s]*)/);
	if(D&&D[1]){E.caja=parseFloat(D[1])}
	return E
}(),jsmenu={'active':[],'timer':[],'iframe':[]},cssloaded=[],ajaxdebug,Ajaxs=[],AjaxStacks=[0,0,0,0,0,0,0,0,0,0],attackevasive=window.attackevasive ? 0 : attackevasive,ajaxpostHandle=0,loadCount=0,floatwinhandle=[],floatscripthandle=[],floattabs=[],InFloat='',floatwinopened=0,floatzIndex=999,floatOut=0;

function in_array(needle, haystack){
	for(var i in haystack)if(haystack[i] == needle)return true;
	return false;
}
function doane(e){
	e = e || window.event;
	if(!e)return;
	try{
		e.stopPropagation();
		e.preventDefault();
	}catch(x){
		e.returnValue=false;
		e.cancelBubble=true;
	}
}
function loadcss(cssname) {
	if(!cssloaded[cssname]) {
		css=$ce('link');
		css.type='text/css';
		css.rel='stylesheet';
		css.href=IXDIR+cssname+'.css';
		var headNode=document.getElementsByTagName("head")[0];
		headNode.appendChild(css);
		cssloaded[cssname]=1;
	}
}
function showloading(display,waiting) {
	var l=window.loadingElement,display=display ? display : 'block',waiting=waiting ? waiting : lang('please wait window loading ...'),d=document,w=d.documentElement.scrollLeft||d.body.scrollLeft,h=d.documentElement.scrollTop||d.body.scrollTop;
	if(!l){
		l=window.loadingElement=$ce('div');l.id='testMenu';
		d.body.appendChild(l);
	}
	with(l.style){
		position='absolute';
		whiteSpace='normal';
		border='1px solid #ccc';
		cursor='default';
		backgroundColor='white';
		fontSize='18px';
		color='red';
		lineHeight='150%';
		padding='5px 10px 3px 5px';
		top=h+20+'px';
		left=w+20+'px';
		zIndex=9999;
	}
	l.innerHTML='<img src="' + IXDIR+'loading.gif">'+waiting;
	if(display!='none')loadCount++;else if(loadCount>0) loadCount--;
	if(display!='none'||loadCount==0)l.style.display=display;
}

//FloatWin
function floatwin(action,dom,w,h,nx,parent,allow,win) {
	win = win || window;
	var l,t,s,reset,floatonly=!floatonly ? 0 : 1,actione=action.indexOf('_'),script=dom&&dom.href?dom.href:dom,forward,handlekey,layerid,scriptfile,url,
		isE=dom&&dom.nodeType==1&&dom.tagName,
		div,divmask,append_parent=$id('append_parent'),de = document.documentElement, db = document.body,
		cw=de.clientWidth ? de.clientWidth : db.clientWidth,
		ch=de.clientHeight ? de.clientHeight : db.clientHeight,
		ct=de.scrollTop ? de.scrollTop : db.scrollTop,
		cl=de.scrollLeft ? de.scrollLeft : db.scrollLeft;
	append_parent || (append_parent = document.body);
	w = parseInt(w||window.floatwidth,10);
	h = parseInt(h||window.floatheight,10);
	l = (cw - w) / 2 + cl;
	t = (ch - h) / 2 + ct;
	if(actione<0)actione=['open',action];else actione=[action.substr(0,actione),action.substr(actione+1)];
	if(!actione[1])actione[1]=parent||'';
	action=actione[0];handlekey=scriptfile=actione[1];layerid='floatwin_' + handlekey;
	if(script&&script.replace)script = script.replace(/([?&])(?:infloat|inajax)=\w+&?|#.*$/gi,'$1');
	if(empty(window.allowfloatwin) && !allow){
		isE || win.location.assign(script);
		return true;
	}
	if(_ua.ie&&event)doane(event);
	switch(action){
	case'update':
		if(!floatscripthandle[scriptfile]||$id(floatscripthandle[scriptfile][1]).style.display=='none')break;
		if(!script)script=floatscripthandle[scriptfile][0];
		reset=1;
	case'open':
		loadcss('float');
		floatwinhandle[handlekey + '_0']=layerid;
		if(floatscripthandle[scriptfile])forward = floatscripthandle[scriptfile][2];
		if(script && script !=-1) {
			if(reset||(floatscripthandle[scriptfile] && floatscripthandle[scriptfile][0] !=script)){
				reset=floatscripthandle[scriptfile][1];
				if(!parent)parent=$id(reset).parent;
				nx&&(nx=parseInt($id(reset).style.zIndex));
				append_parent.removeChild($id(reset));
				append_parent.removeChild($id(reset + '_mask'));
				if(_ua.ie)append_parent.removeChild($id(reset + '_bgfm'));
			}else{
				nx=0;
			}
			floatscripthandle[scriptfile]=[script,layerid,forward];
		}
		window.floatminwidth || (window.floatminwidth = 5);
		window.floatminheight || (window.floatminheight = 5);
		floatwinhandle[handlekey + '_1'] = l = l < floatminwidth ? floatminwidth : l;
		floatwinhandle[handlekey + '_2'] = t = t < floatminheight ? floatminheight : t;
		if(!nx){
			nx=floatzIndex+99;
			floatzIndex+=3;
		}
		div = $id(layerid);
		if(!div){
			floattabs[layerid]=new Array();
			div=$ce('div');
			div.className='floatwin';
			div.id=layerid;
			with(div.style){
				width=w + 'px';
				height=h + 'px';
				left= l + 'px';
				top= t + 'px';
				position='absolute';
				zIndex=nx;
				display='';
			}
			div.onkeydown=floatwin_keyhandle;
			append_parent.appendChild(div);
			divmask=$ce('div');
			divmask.className='floatwinmask';
			divmask.id=layerid + '_mask';
			with(divmask.style){
				width=(w + 14) + 'px';
				height=(h + 14) + 'px';
				left=(l - 6) + 'px';
				top=(t - 6) + 'px';
				position='absolute';
				zIndex=nx-1;
				filter='progid:DXImageTransform.Microsoft.Alpha(opacity=90,finishOpacity=100,style=0)';
				opacity=0.9;
			}
			append_parent.appendChild(divmask);
			if(script && script !=-1) {
				url = script.replace(/([?&])infloat=[^&]*&?/g,'$1').replace(/([?&])handlekey=[^&]*&?/g,'$1');
				s	= url.match(/[?&]forward=([^&]+)/);
				if(!forward){
					forward = s ? s[1] : win.location ? encodeURIComponent(win.location.href.replace(/#.*$/,'')) : '';
					floatscripthandle[scriptfile][2] = forward;
				}
				url += s ? '' : ((/[?&]/.test(url.charAt(url.length - 1)) ? '' : url.indexOf('?') < 0 ? '?' : '&') + 'forward=' + forward);
				url = url.replace(/"/g,'&quot;');
				showloading();
				div.innerHTML='<div><h3 class="float_ctrl"><div id="' + handlekey + '_title"><a href="javascript:" onclick="floatwin(\'update_' + handlekey + '\',\'' + url.replace(/'/g,"\\'") + '&infloat=1&handlekey=' + handlekey + '\')">['+lang('refresh the float window')+']</a> <a href="'+url+'" target="_blank">['+lang('at new window open the page')+']</a></div><span><a href="javascript:;" class="float_close" onclick="floatwin(\'close_' + handlekey + '\',0,0,0,0,0,1);return false">&nbsp</a></span></h3></div><div id="' + handlekey + '_content" style="height:'+(h-30)+'px;"><iframe src="' + url + '&infloat=1&handlekey=' + handlekey + '" id="' + handlekey + '_iframe" name="' + handlekey + '_iframe" onload="showloading(\'none\');this.contentWindow.floatwinName=\'' + handlekey + '\'" width="100%" height="100%" frameborder="0"></iframe></div>';
			} else if(script==-1) {
				div.innerHTML='<div><h3 class="float_ctrl"><div id="' + handlekey + '_title"></div><span><a href="javascript:;" class="float_close" onclick="floatwin(\'close_' + handlekey + '\',0,0,0,0,0,1);return false">&nbsp</a></span></h3></div><div id="' + handlekey + '_content"></div>';
			}
			if(_ua.ie){
				var bg=$ce('<iframe></iframe>');
				bg.id=layerid + '_bgfm';
				with(bg.style){
					width=(w + 14) + 'px';
					height=(h + 14) + 'px';
					left=(l - 6) + 'px';
					top=(t - 6) + 'px';
					position='absolute';
					zIndex=nx-2;
					filter='progid:DXImageTransform.Microsoft.Alpha(opacity=0,finishOpacity=100,style=0)';
					opacity=0;
				}
				append_parent.appendChild(bg);
			}
		}else{
			with(div.style){
				width=w + 'px';
				height=h + 'px';
				left= l + 'px';
				top= t + 'px';
				zIndex=nx;
				display='';
			}
			with($id(layerid + '_mask').style){
				width=(w + 14) + 'px';
				height=(h + 14) + 'px';
				top=(t - 6) + 'px';
				left=(l - 6) + 'px';
				zIndex=nx-1;
				display='';
			}
			if(_ua.ie){
				with($id(layerid + '_bgfm').style){
					width=(w + 14) + 'px';
					height=(h + 14) + 'px';
					top=(t - 6) + 'px';
					left=(l - 6) + 'px';
					zIndex=nx-2;
					display='';
				}
			}
		}
//		floatwins[floatwinopened++]=handlekey;
		if(parent){
			div.parent=parent
		}else if(action=='open'&&isE){
			while(dom=dom.parentNode)if(dom.id&&dom.id.substr(0,9)=='floatwin_'){div.parent=dom.id.substr(9);break}
		}
		if(!div.parent)div.parent=win;
		break;
	case'close':
		showloading('none');
		clearDelay(handlekey);
		if(floatwinhandle[handlekey + '_0']){
			$id(layerid + '_mask').style.display='none';
			$id(layerid).style.display='none';
			if(_ua.ie)$id(layerid + '_bgfm').style.display='none';
			script&&floatscripthandle[scriptfile]&&(floatscripthandle[scriptfile][0]=script);
		}
		break;
	case'updateparent':
		parent=$id(layerid).parent;
		if(typeof parent == 'string')floatwin('update_'+parent,script,w,h,1);else setTimeout(function(){(parent||win).location.reload()},1250);
		break;
	case'closeparent':
		parent=$id(layerid).parent;
		if(typeof parent == 'string')floatwin('close_'+parent,script,0,0,0,0,1);else{
			parent && (win = parent);
			if(win._08cms_forward){
				url = win._08cms_forward;
			}else if(url = location.href.match(/[?&]forward=([^&]+)/)){
				url = decodeURIComponent(url[1]);
			}else{
				url = document.referrer;
			}
			setTimeout(function(){if(url)win.location.assign(url);else win.location.reload()},1250);
		}
		break;
	case'updateup2':
		parent=$id(layerid).parent;
		if(typeof parent == 'string')floatwin('updateparent_'+parent,script,w,h,1);else (parent||win).location.reload();
		break;
	case'size':
		w=cw > 800 ? cw * 0.9 : 800;
		h=ch > 600 ? ch * 0.9 : 600;
	case'center':
		if(floatwinhandle[handlekey + '_0']){
		if(!floatwinhandle[handlekey + '_3']) {
			floatwinhandle[handlekey + '_3']=$id(layerid).style.left;
			floatwinhandle[handlekey + '_4']=$id(layerid).style.top;
			floatwinhandle[handlekey + '_5']=$id(layerid).style.width;
			floatwinhandle[handlekey + '_6']=$id(layerid).style.height;
			with($id(layerid).style){
				left=floatwinhandle[handlekey + '_1']=l + 'px';
				top=floatwinhandle[handlekey + '_2']=t + 'px';
				width=w + 'px';
				height=h + 'px';
			}
		} else {
			with($id(layerid).style){
				left=floatwinhandle[handlekey + '_1']=floatwinhandle[handlekey + '_3'];
				top=floatwinhandle[handlekey + '_2']=floatwinhandle[handlekey + '_4'];
				width=floatwinhandle[handlekey + '_5'];
				height=floatwinhandle[handlekey + '_6'];
			}
			floatwinhandle[handlekey + '_3']='';
		}
		s=$id(layerid).style;
		with($id(layerid + '_mask').style){
			width=(parseInt(s.width) + 14) + 'px';
			height=(parseInt(s.height) + 14) + 'px';
			left=(parseInt(s.left) - 6) + 'px';
			top=(parseInt(s.top) - 6) + 'px';
		}
		}break;
	}
	return false;
}
function setDelay(code, delay){
	var fwin = _08CMS_.get('window');
	if(typeof code == 'string'){
		if(code.match(/floatwin\s*\(\s*['"][^_]+_['"]/) && window != fwin && !window.floatwinName){
			delay = delay > 50 ? delay - 50 : 20;
			return setTimeout(function(){setDelay(code, delay)}, 50);
		}
		code = code.replace(/(floatwin\s*\(\s*)('|")([^_]+_)['"]/, '$1$2$3' + (window.floatwinName || '') + '$2');
	}
	window['floatwinTimer_' + window.floatwinName] = fwin.setTimeout(code, delay);
}
function clearDelay(win){
	_08CMS_.get('window').clearTimeout(window['floatwinTimer_' + win]);
}
_08CMS_.set('floatwin', floatwin);
_08CMS_.set('showloading', showloading);
if(_08CMS_.get('window') !== window){
	floatwin = function(o,u,w,h,x,p,a){
		return _08CMS_.get('floatwin')(o,u,w,h,x,p||window.floatwinName,a,window);
	};
	showloading = _08CMS_.get('showloading');
}
function floatwin_keyhandle(e){
	e=_ua.ie ? event : e;
	if(e.keyCode==9){
		doane(e);
		var obj=_ua.ie ? e.srcElement : e.target;
		var srcobj=obj;
		j=0;
		if(!(obj=obj.form))return;
		obj.id=obj.id ? obj.id : 'floatbox_' + Math.random();
		if(!floattabs[obj.id]){
			floattabs[obj.id]=new Array();
			var alls=obj.elements;
			for(i=0;i < alls.length;i++)floattabs[obj.id][j++]=alls[i];
		}
		if(floattabs[obj.id].length > 0){
			for(i=0;i < floattabs[obj.id].length;i++)
				if(srcobj==floattabs[obj.id][i]){
					j=e.shiftKey ? i - 1 : i + 1;break;
				}
			if(j < 0)j=floattabs[obj.id].length - 1;
			if(j > floattabs[obj.id].length - 1)j=0;
			do{
				focusok=1;
				try{floattabs[obj.id][j].focus()} catch(e){focusok=0}
				if(!focusok){
					j=e.shiftKey ? j - 1 : j + 1;
					if(j < 0)j=floattabs[obj.id].length - 1;
					if(j > floattabs[obj.id].length - 1)j=0;
				}
			} while(!focusok);
		}
	}
}

function ajaxform(form, width, height, forward){
	if(empty(window.allowfloatwin))return true;
	if(form.target == form.ajax)return false;
	showloading();
	var fel, wid, fid = 'ajax_' + (new Date).getTime(), pel = $id('append_parent') || document.body, url = form.action;
	if(_ua.ie){
		fel = $ce("<iframe name='" + fid + "' id='" + fid + "'></iframe>");
	}else{
		fel = $ce("iframe");
		fel.name = fel.id = fid;
	}
/**/	fel.style.display='none';/*
	with(fel.style){
		width='500px'
		height='400px'
	}//*/
	pel.appendChild(fel);
	listen(fel, 'load', function(){
		var data,a=fel.contentWindow.location.href,c,e,m,w,h,p,s,v,x;
		if(a.substr(a.length - form.action.length) != form.action)return;
		showloading('none');
		form.target = '_self';
		form.action = url;
		try{
			data = fel.contentWindow.document;
			data = _ua.ie ? data.XMLDocument.text : data.documentElement.firstChild.nodeValue;
			pel.removeChild(fel);
		}catch(e){
			pel.removeChild(fel);
			return alert(lang('data format error !'));
		}
		w = width || 520; h = height || 230;
		floatwin('open_' + fid, -1, w, h, 0, wid);
		$WE('floatwin_' + fid).innerHTML = '<div><h3 class="float_ctrl"><div></div><span><a href="javascript:;" class="float_close" onclick="floatwin(\'close_'+fid+'\',0,0,0,0,0,1);">&nbsp</a></span></h3></div><div id="' + fid + '_content" style="width:'+w+'px;height:'+(h-30)+'px;overflow:auto"><div style="width:96%;margin:'+(_ua.ie?5:7)+'px auto 0px auto">'+data+'</div></div>';
		e = /<script([^\>]*?)>([^\x00]*?)<\/script>/gi;/*
		p = document.createElement('div');
		p.innerHTML = data;
		document.body.appendChild(p);/**/
		p = $id('append_parent') || document.body;
		window._08cms_forward = forward;//*
		while(m = e.exec(data)){
			s = $ce("script");
			if(m[1]){
				a = /(\w+)\s*=\s*(\w+|"[^"\\]*(?:\\.[^"\\]*)*"|'[^'\\]*(?:\\.[^'\\]*)*')/gi;
				while(v = a.exec(m[1])){
					v[1] = v[1].toLowerCase();
					v[2] = v[2].charAt(0) == '"' || v[2].charAt(0) == "'" ?  v[2].substr(1, v[2].length - 2) : v[2];
					if(v[1] == 'charset'){
						c = v[2];
					}else{
						c = 0;
						if(v[1] == 'src')x = 1;
						s[v[1]] = v[2];
					}
				}
			}
			s.charset = c ? c : (_ua.gecko ? document.characterSet : document.charset);
			if(!x)s.text=m[2];
			p.appendChild(s);
			p.removeChild(s);
		}/**/
	});
	form.target = form.ajax = fid;
	wid = window.floatwinName;
	if(!wid){
		wid = form.parentNode;
		while(wid = wid.parentNode)if(wid.id&&wid.id.substr(0,9)=='floatwin_'){wid = wid.id.substr(9);break}
	}
	fid = 'new_' + (wid || 'commonwin');
	wid || (win = window);
	form.action = (url.replace(/[?&]infloat\b[^&]*$|([?&])infloat\b[^&]*&/g,'$1')) + (url.indexOf('?') < 0 ? '?' : '&') + 'inajax=1&infloat=1&handlekey=' + fid;
	return true;
}

function initCtrl(ctrlobj,click,duration,timeout,layer) {
	if(ctrlobj && !ctrlobj.initialized) {
		ctrlobj.initialized=true;
		ctrlobj.unselectable=true;

		ctrlobj.outfunc=typeof ctrlobj.onmouseout=='function' ? ctrlobj.onmouseout : null;
		ctrlobj.onmouseout=function() {
			if(this.outfunc) this.outfunc();
			if(duration < 3 && !jsmenu['timer'][ctrlobj.id]) jsmenu['timer'][ctrlobj.id]=setTimeout('hideMenu(' + layer + ')',timeout);
		}

		ctrlobj.overfunc=typeof ctrlobj.onmouseover=='function' ? ctrlobj.onmouseover : null;
		ctrlobj.onmouseover=function(e) {
			doane(e);
			if(this.overfunc) this.overfunc();
			if(click) {
				clearTimeout(jsmenu['timer'][this.id]);
				jsmenu['timer'][this.id]=null;
			} else {
				for(var id in jsmenu['timer']) {
					if(jsmenu['timer'][id]) {
						clearTimeout(jsmenu['timer'][id]);
						jsmenu['timer'][id]=null;
					}
				}
			}
		}
	}
}

function initMenu(ctrlid,menuobj,duration,timeout,layer,drag) {
	if(menuobj && !menuobj.initialized) {
		menuobj.initialized=true;
		menuobj.ctrlkey=ctrlid;
		menuobj.onclick=ebygum;
		menuobj.style.position='absolute';
		if(duration < 3) {
			if(duration > 1) {
				menuobj.onmouseover=function() {
					clearTimeout(jsmenu['timer'][ctrlid]);
					jsmenu['timer'][ctrlid]=null;
				}
			}
			if(duration !=1) {
				menuobj.onmouseout=function() {
					jsmenu['timer'][ctrlid]=setTimeout('hideMenu(' + layer + ')',timeout);
				}
			}
		}
		menuobj.style.zIndex=9999 + layer;
		if(drag) {
			menuobj.onmousedown=function(event) {try{menudrag(menuobj,event,1);}catch(e){}};
			menuobj.onmousemove=function(event) {try{menudrag(menuobj,event,2);}catch(e){}};
			menuobj.onmouseup=function(event) {try{menudrag(menuobj,event,3);}catch(e){}};
		}
	}
}

var menudragstart=new Array();
function menudrag(menuobj,e,op) {
	if(op==1) {
		if(in_array(_ua.ie ? event.srcElement.tagName : e.target.tagName,['TEXTAREA','INPUT','BUTTON','SELECT'])) {
			return;
		}
		menudragstart=_ua.ie ? [event.clientX,event.clientY] : [e.clientX,e.clientY];
		menudragstart[2]=parseInt(menuobj.style.left);
		menudragstart[3]=parseInt(menuobj.style.top);
		doane(e);
	} else if(op==2 && menudragstart[0]) {
		var menudragnow=_ua.ie ? [event.clientX,event.clientY] : [e.clientX,e.clientY];
		menuobj.style.left=(menudragstart[2] + menudragnow[0] - menudragstart[0]) + 'px';
		menuobj.style.top=(menudragstart[3] + menudragnow[1] - menudragstart[1]) + 'px';
		doane(e);
	} else if(op==3) {
		menudragstart=[];
		doane(e);
	}
}

function showInfo(ctrlid,url,w,h){
	var div=$id(ctrlid + '_menu');
	if(!w)w=480;if(!h)h=320;
	if(!div){
		var ifr,cnt=$ce('div');
		div=$ce('div');
		div.id=ctrlid + '_menu';
		div.className='infoBox';
		with(div.style){
			position='absolute';
			width=w+'px';
			height=h+'px';
			background='#FFF'
		}
		($id('append_parent') || document.body).appendChild(div);
		div.appendChild(cnt);
		if(_ua.ie&&_ua.ie<7){
			ifr=$ce('<iframe></iframe>');
			with(ifr.style){
				position='absolute';
				top=left='0px';
				width=w+'px';
				height=h+'px';
				zIndex=98;
				filter='progid:DXImageTransform.Microsoft.Alpha(opacity=0,finishOpacity=100,style=0)';
			}
			div.appendChild(ifr);
		}
		with(cnt.style){
			position='relative';
			ifr&&(width=w+'px');
			height=h+'px';
			zIndex=99;
		}
		cnt.innerHTML=lang('please wait loading window ...');
		showloading();
		var xml=Ajax();
		xml.get(url+'&inajax=1',function(s){
			showloading('none');
			cnt.innerHTML='<div style="margin:5px;">'+s+'</div>';
			cnt.style.height='';
			div.style.height=cnt.offsetHeight+'px';
			ifr&&(ifr.style.height=cnt.offsetHeight+'px');
			showMenu(ctrlid,0,0,2,250,0,ctrlid,h,0,1);
		});
	}
	showMenu(ctrlid,0,0,2,250,0,ctrlid,h,0,1);
	return false;
}

function showMenu(ctrlid,click,offset,duration,timeout,layer,showid,maxh,drag,info) {
	var ctrlobj=$id(ctrlid);
	if(!ctrlobj) return;
	if(isUndefined(click)) click=false;
	if(isUndefined(offset)) offset=0;
	if(isUndefined(duration)) duration=2;
	if(isUndefined(timeout)) timeout=250;
	if(isUndefined(layer)) layer=0;
	if(isUndefined(showid)) showid=ctrlid;
	var showobj=$id(showid);
	var menuobj=$id(showid + '_menu');
	if(!showobj|| !menuobj) return;
	if(isUndefined(maxh)) maxh=400;
	if(isUndefined(drag)) drag=false;

	if(click && jsmenu['active'][layer]==menuobj) {
		hideMenu(layer);
		return;
	} else {
		hideMenu(layer);
	}

	var len=jsmenu['timer'].length;
	if(len > 0) {
		for(var i=0; i<len; i++) {
			if(jsmenu['timer'][i]) clearTimeout(jsmenu['timer'][i]);
		}
	}

	initCtrl(ctrlobj,click,duration,timeout,layer);
	ctrlobjclassName=ctrlobj.className;
	ctrlobj.className +=' hover';
	initMenu(ctrlid,menuobj,duration,timeout,layer,drag);

	menuobj.style.display='';
	if(!_ua.opera) {
		menuobj.style.clip='rect(auto,auto,auto,auto)';
	}


	if(maxh && menuobj.scrollHeight > maxh) {
		menuobj.style.height=maxh + 'px';
		if(info&&_ua.ie)menuobj.firstChild.style.width = menuobj.offsetWidth-18+'px';
		if(_ua.opera) {
			menuobj.style.overflow='auto';
		} else {
			menuobj.style.overflowY='auto';
		}
	}
	setMenuPosition(showid,offset);

	if(!duration) {
		setTimeout('hideMenu(' + layer + ')',timeout);
	}

	jsmenu['active'][layer]=menuobj;
}

function setMenuPosition(showid,offset) {
	var id='floatwin_',l=id.length,s=$id(showid),p=m=$id(showid + '_menu'),f=floatwinhandle
		de = document.documentElement, db = document.body;
	if(isUndefined(offset))offset=0;
	if(s){
		s.pos=fetchOffset(s);
		s.X=s.pos['left'];
		s.Y=s.pos['top'];
		if(!InFloat)while(p=p.parentNode)if(p.id&&p.id.substr(0,l)==id){InFloat=p.id;break}
		if($id(InFloat) !=null) {
			var InFloate=InFloat.substr(l);
			if(!f[InFloate + '_1']) {
				floatwinnojspos=fetchOffset($id('floatwinnojs'));
				f[InFloate + '_1']=floatwinnojspos['left'];
				f[InFloate + '_2']=floatwinnojspos['top'];
			}
			p=m;while(p=p.parentNode)
			if(/_content$/.test(p.id)){
				s.X-=p.scrollLeft;
				s.Y-=p.scrollTop;
			}
			s.X=s.X - $id(InFloat).scrollLeft - parseInt(f[InFloate + '_1']);
			s.Y=s.Y - $id(InFloat).scrollTop - parseInt(f[InFloate + '_2']);
			InFloat='';
		}
		s.w=s.offsetWidth;
		s.h=s.offsetHeight;
		m.w=m.offsetWidth;
		m.h=m.offsetHeight;
		if(offset < 3) {
			m.style.left=(s.X + m.w > db.clientWidth) && (s.X + s.w - m.w >=0) ? s.X + s.w - m.w + 'px' : s.X + 'px';
			m.style.top=offset==1 ? s.Y + 'px' : (offset==2 || ((s.Y + s.h + m.h > de.scrollTop + de.clientHeight) && (s.Y - m.h >=0)) ? (s.Y - m.h) + 'px' : s.Y + s.h + 'px');
		} else if(offset==3) {
			m.style.left=(db.clientWidth - m.clientWidth) / 2 + db.scrollLeft + 'px';
			m.style.top=(db.clientHeight - m.clientHeight) / 2 + db.scrollTop + 'px';
		}

		if(m.style.clip && !_ua.opera) {
			m.style.clip='rect(auto,auto,auto,auto)';
		}
	}
}

function hideMenu(layer) {
	if(isUndefined(layer)) layer=0;
	if(jsmenu['active'][layer]) {
		try {
			$id(jsmenu['active'][layer].ctrlkey).className=ctrlobjclassName;
		} catch(e) {}
		clearTimeout(jsmenu['timer'][jsmenu['active'][layer].ctrlkey]);
		jsmenu['active'][layer].style.display='none';
		if(_ua.ie && _ua.ie < 7 && jsmenu['iframe'][layer]) {
			jsmenu['iframe'][layer].style.display='none';
		}
		jsmenu['active'][layer]=null;
	}
}

function fetchOffset(obj) {
	var left_offset=obj.offsetLeft;
	var top_offset=obj.offsetTop;
	while((obj=obj.offsetParent) !=null) {
		left_offset +=obj.offsetLeft;
		top_offset +=obj.offsetTop;
	}
	return { 'left' : left_offset,'top' : top_offset };
}

function ebygum(eventobj) {
	if(!eventobj || _ua.ie) {
		window.event.cancelBubble=true;
		return window.event;
	} else {
		if(eventobj.target.type=='submit') {
			eventobj.target.form.submit();
		}
		eventobj.stopPropagation();
		return eventobj;
	}
}

function ajaxinnerhtml(showid,s) {
	if(showid.tagName !='TBODY') {
		var id=showid.id.substr(showid.id.indexOf('_')+1),url=floatscripthandle[id][0],u=location.href,zs=floatwidth/10;
		url=fullurl(url);
		s=s.replace(/<(\/?)js(.*?)>/gi,function(a,b,c){return'<'+b+'script'+c+'>'});
		showid.innerHTML='<div><h3 class="float_ctrl"><div><a href="javascript:;" onclick="floatwin(\'update_'+id+'\')">['+lang('refresh the float window')+']</a> <a href="'+url+'" target="_blank">['+lang('at new window open the page')+']</a></div><span><a href="javascript:;" class="float_close" onclick="floatwin(\'close_'+id+'\',0,0,0,0,0,1);">&nbsp</a></span></h3></div><div id="' + id + '_content" style="width:'+showid.offsetWidth+'px;height:'+(showid.offsetHeight-50)+'px;overflow:auto"><div style="width:96%;margin:0px auto">'+s+'</div></div>';
		evalscript(s);
	} else {
		while(showid.firstChild) {
			showid.firstChild.parentNode.removeChild(showid.firstChild);
		}
		var div1=$ce('DIV');
		div1.id=showid.id+'_div';
		div1.innerHTML='<table><tbody id="'+showid.id+'_tbody">'+s+'</tbody></table>';
		$id('append_parent').appendChild(div1);
		var trs=div1.getElementsByTagName('TR');
		var l=trs.length;
		for(var i=0; i<l; i++) {
			showid.appendChild(trs[0]);
		}
		var inputs=div1.getElementsByTagName('INPUT');
		var l=inputs.length;
		for(var i=0; i<l; i++) {
			showid.appendChild(inputs[0]);
		}
		div1.parentNode.removeChild(div1);
	}
}

function Ajax(recvType,waitId) {

	for(var stackId=0; stackId < AjaxStacks.length && AjaxStacks[stackId] !=0; stackId++);
	AjaxStacks[stackId]=1;

	var aj=new Object();

	aj.loading='Loading...';//public
	aj.recvType=recvType ? recvType : 'XML';//public
	aj.waitId=waitId ? $id(waitId) : null;//public

	aj.resultHandle=null;//private
	aj.sendString='';//private
	aj.targetUrl='';//private
	aj.stackId=0;
	aj.stackId=stackId;

	aj.setLoading=function(loading) {
		if(typeof loading !=='undefined' && loading !==null) aj.loading=loading;
	}

	aj.setRecvType=function(recvtype) {
		aj.recvType=recvtype;
	}

	aj.setWaitId=function(waitid) {
		aj.waitId=typeof waitid=='object' ? waitid : $id(waitid);
	}

	aj.createXMLHttpRequest=function() {
		var request=false;
		if(window.XMLHttpRequest) {
			request=new XMLHttpRequest();
			if(request.overrideMimeType) {
				request.overrideMimeType('text/xml');
			}
		} else if(window.ActiveXObject) {
			var versions=['Microsoft.XMLHTTP','MSXML.XMLHTTP','Microsoft.XMLHTTP','Msxml2.XMLHTTP.7.0','Msxml2.XMLHTTP.6.0','Msxml2.XMLHTTP.5.0','Msxml2.XMLHTTP.4.0','MSXML2.XMLHTTP.3.0','MSXML2.XMLHTTP'];
			for(var i=0; i<versions.length; i++) {
				try {
					request=new ActiveXObject(versions[i]);
					if(request) {
						return request;
					}
				} catch(e) {}
			}
		}
		return request;
	}

	aj.XMLHttpRequest=aj.createXMLHttpRequest();
	aj.showLoading=function() {
		if(aj.waitId && (aj.XMLHttpRequest.readyState !=4 || aj.XMLHttpRequest.status !=200)) {
			aj.waitId.style.display='';
			aj.waitId.innerHTML='<span><img src="' + IXDIR+'images/loading.gif"> ' + aj.loading + '</span>';
		}
	}

	aj.processHandle=function() {
		if(aj.XMLHttpRequest.readyState==4 && aj.XMLHttpRequest.status==200) {
			for(k in Ajaxs) {
				if(Ajaxs[k]==aj.targetUrl) {
					Ajaxs[k]=null;
				}
			}
			if(aj.waitId) {
				aj.waitId.style.display='none';
			}
			if(aj.recvType=='HTML') {
				aj.resultHandle(aj.XMLHttpRequest.responseText,aj);
			} else if(aj.recvType=='XML') {
				if(aj.XMLHttpRequest.responseXML.lastChild) {
					aj.resultHandle(aj.XMLHttpRequest.responseXML.lastChild.firstChild.nodeValue,aj);
				} else {
					alert(lang('ajaxxmlerr'));
					if(ajaxdebug) {
						var error=mb_cutstr(aj.XMLHttpRequest.responseText.replace(/\r?\n/g,'\\n').replace(/"/g,'\\\"'),200);
						aj.resultHandle('<root>ajaxerror<script type="text/javascript" reload="1">alert(\'Ajax Error: \\n' + error + '\');</script></root>',aj);
					}
				}
			}
			AjaxStacks[aj.stackId]=0;
		}
	}

	aj.get=function(targetUrl,resultHandle) {
		return js_callback(targetUrl, resultHandle);
		setTimeout(function(){aj.showLoading()},250);
		if(in_array(targetUrl,Ajaxs)) {
			return false;
		} else {
			Ajaxs.push(targetUrl);
		}
		aj.targetUrl=targetUrl;
		aj.XMLHttpRequest.onreadystatechange=aj.processHandle;
		aj.resultHandle=resultHandle;
		var delay=attackevasive & 1 ? (aj.stackId + 1) * 1001 : 100;
		if(window.XMLHttpRequest) {
			setTimeout(function(){
			aj.XMLHttpRequest.open('GET',aj.targetUrl);
			aj.XMLHttpRequest.send(null);},delay);
		} else {
			setTimeout(function(){
			aj.XMLHttpRequest.open("GET",targetUrl,true);
			aj.XMLHttpRequest.send();},delay);
		}

	};
	aj.post=function(targetUrl,sendString,resultHandle) {
		setTimeout(function(){aj.showLoading()},250);
		if(in_array(targetUrl,Ajaxs)) {
			return false;
		} else {
			Ajaxs.push(targetUrl);
		}
		aj.targetUrl=targetUrl;
		aj.sendString=sendString;
		aj.XMLHttpRequest.onreadystatechange=aj.processHandle;
		aj.resultHandle=resultHandle;
		aj.XMLHttpRequest.open('POST',targetUrl);
		aj.XMLHttpRequest.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		aj.XMLHttpRequest.send(aj.sendString);
	};
	aj.form=function(url,fields,callbak){
		setTimeout(function(){aj.showLoading()},250);
		var P = $id('append_parent'),E = function(t){return document.createElement(t)},A = function(e, p){(p || P).appendChild(e)}, D = function(e, p){(p || P).removeChild(e)};
		P || (P = document.body);
		var form = E('form'), fid = 'f_' + (new Date).getTime(), e, k;
		form.style.display = 'none';
		form.action = url;
		form.target = fid;
		form.method = 'post';
		for(k in fields){
			e = E('input');
			e.type = 'hidden';
			e.name = k;
			e.value = fields[k];
			A(e, form);
		}
		e = E(_ua.ie ? '<iframe id="' + fid + '" name="' + fid + '" style="display:none"></iframe>' : 'iframe');
		e.id = e.name = fid;
		e.style.display = 'none';
		listen(e, 'load', function(){
			var d, s;
			try{
				e || (e = $id(fid));
				if(e.contentWindow.location == 'about:blank')return;
				d=e.contentWindow.document;
				s=_ua.ie ? d.XMLDocument.text : d.documentElement.firstChild.nodeValue;
			}catch(e){
				return alert(lang('ajaxxmlerr'));
			}
			D(e);
			D(form);
			callbak && callbak(s);
		});
		A(e);
		A(form);
		form.submit();
	}
	return aj;
}
function js_callback(url,/*[callback]*/ cb){
	var s, id, h = document.getElementsByTagName('HEAD')[0];
	js_callback.stack = js_callback.stack || {};
	if(typeof cb == 'string' && cb in js_callback.stack){
		cb = js_callback.stack[cb];
		cb.callback(url);
		setTimeout(function(){h.removeChild(cb.script);delete js_callback.stack[cb]}, 200);
	}else{
		id = random_symbol();
		s = document.createElement('script');
		js_callback.stack[id] = {script : s, callback : cb};
		s.type = 'text/javascript';
		s.src = url + (url.indexOf('?') == -1 ? '?' : '&') + 'callback='+id;
		setTimeout(function(){h.appendChild(s)}, 20);
	}
}
function random_symbol(){
	var str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz$_', len = str.length,
		data = parseInt(Math.random().toString().slice(2)), symbol = '$_', tmp;
	while(data){
		tmp = data % len;
		symbol += str.charAt(tmp);
		data = (data - tmp) / len;
	}
	return symbol;
}

var DEBUG = /[#&]debug=js\b/i.test(top.location.hash);
if(DEBUG){
	window.onerror = function(e){
		alert(e.description || e);return true;
	}
}
function debug(str){
	if(DEBUG)alert(str);
}