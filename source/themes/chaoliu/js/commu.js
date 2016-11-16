function ajax_praise(aid){
	aid = parseInt(aid);
	if(isNaN(aid) || aid == 0) alert('请指定文档ID！');
	var x = new Ajax('XML');
	x.get(cmsUrl + 'tools/praise.php?inajax=1&aid=' + aid, function(s){
		if(s != 'succeed'){
			alert(s);
		}else{
			//$('cms_praises').innerHTML = parseInt($('cms_praises').innerHTML) + 1;
			var a = $("#cms_praises").text();
			var num=parseInt(a)+1;
			
			$("#cms_praises").text(num);
			
		} 
	});
}
function ajax_debase(aid){
	aid = parseInt(aid);
	if(isNaN(aid) || aid == 0) alert('请指定文档ID！');
	var x = new Ajax('XML');
	x.get(cmsUrl + 'tools/debase.php?inajax=1&aid=' + aid, function(s){
		if(s != 'succeed'){
			alert(s);
		}else{
			$('cms_debases').innerHTML = parseInt($('cms_debases').innerHTML) + 1;
		} 
	});
}
function ajax_report(aid){
	aid = parseInt(aid);
	if(isNaN(aid) || aid == 0) alert('请指定文档ID！');
	var x = new Ajax('XML');
	x.get(cmsUrl + 'tools/report.php?inajax=1&aid=' + aid, function(s){
		if(s != 'succeed'){
			alert(s);
		}else{
			$('cms_reports').innerHTML = parseInt($('cms_reports').innerHTML) + 1;
		} 
	});
}
function ajax_favorite(aid){
	aid = parseInt(aid);
	if(isNaN(aid) || aid == 0) alert('请指定文档ID！');
	var x = new Ajax('XML');
	x.get(cmsUrl + 'tools/favorite.php?inajax=1&aid=' + aid, function(s){
		if(s != 'succeed'){
			alert(s);
		}else{
			alert('您已成功收藏！');
		}
	});
}
function ajax_subscribe(aid){
	aid = parseInt(aid);
	if(isNaN(aid) || aid == 0) alert('请指定文档ID！');
	var x = new Ajax('XML');
	x.get(cmsUrl + 'tools/subscribe.php?inajax=1&aid=' + aid, function(s){
		if(s != 'succeed'){
			alert(s);
		}else alert('您已成功订阅此文！');
	});
}

function ajax_get_stat(aids, callback){
	var k, m = ajax_get_stat;
	aids && m.aids.push(aids);
	if(callback && m.aids.length){
		var x = new Ajax('XML');
		x.get(cmsUrl + 'tools/ajax.php?action=stat&aids=' + m.aids.join(','), function(s){
			eval('m.data=s=' + s);
			for(k in s){
				m.faid = k;
				break;
			}
			callback(s);
		});
	}
}
ajax_get_stat.aids = [];
ajax_get_stat.data = {};
function ajax_get_score(aids, callback){
	var k, m = ajax_get_stat;
	aids && m.aids.push(aids);
	if(callback && m.aids.length){
		var x = new Ajax('XML');
		x.get(cmsUrl + 'tools/ajax.php?action=score&aids=' + m.aids.join(','), function(s){
			eval('m.data=s=' + s);
			for(k in s){
				m.faid = k;
				break;
			}
			callback(s);
		});
	}
}

function get_aids_info(atr, dom){
	atr = atr || 'aid_info';
	dom = dom || document.body;
	for(var i = 0; i < dom.childNodes.length; i++){
		if(dom.childNodes[i].nodeType != 1)continue;
		var aid = dom.childNodes[i].getAttribute(atr);
		aid && ajax_get_stat.aids.push(aid);
		dom.childNodes[i].childNodes.length && get_aids_info(atr, dom.childNodes[i]);
	}
}

function set_aids_info(atr, dom){
	atr = atr || 'set_info';
	dom = dom || document.body;
	for(var i = 0; i < dom.childNodes.length; i++){
		if(dom.childNodes[i].nodeType != 1)continue;
		var info = dom.childNodes[i].getAttribute(atr);
		if(info && (info = /^(?:(\d+):)?(\w+)$/.exec(info))){
			info = ajax_get_stat.data[info[1] || ajax_get_stat.faid][info[2]];
			info && (dom.childNodes[i].innerHTML = info);
		}else{
			dom.childNodes[i].childNodes.length && set_aids_info(atr, dom.childNodes[i]);
		}
	}
}

function ajax_aids_info(get, set){
	var m = ajax_get_stat;
	get_aids_info(get);
	var x = new Ajax('XML');
	x.get(cmsUrl + 'tools/ajax.php?action=stat&aids=' + m.aids.join(','), function(s){
		eval('m.data=' + s);
		for(k in m.data){
			m.faid = k;
			break;
		}
		set_aids_info(set);
	});
}

function ajaxUscore(aid,score,id){
	aid = parseInt(aid);
	if(isNaN(aid) || aid == 0) alert('请指定文档ID！');
	var x = new Ajax('XML');
	x.get(cmsUrl + 'tools/score.php?inajax=1&aid=' + aid + "&score=" + score, function(s){
		if(s != 'succeed'){
			alert(s);
		}else{
			var a = $("#cms_score_"+score).text();
			var num=parseInt(a)+1;
			$("#cms_score_"+score).text(num);
		} 
	});
}


function ajaxComment(aid,cid,opt,id){
	aid = parseInt(aid);
	if(isNaN(aid) || aid == 0) alert('请指定文档ID！');
	var x = new Ajax('XML');
		x.get(cmsUrl + 'tools/comment.php?action=vote&inajax=1&aid='+aid+'&cid='+cid+'&option='+opt, function(s){
		
		if(s != 'succeed'){
			alert(s);
		}else{
			var bid = id;
			var a = $("#"+bid).text();
			var num=parseInt(a)+1;
			$("#"+bid).text(num);
		} 
		
	});
	
}


function ajaxCommentreplay(aid,cid,opt,id){
	aid = parseInt(aid);
	if(isNaN(aid) || aid == 0) alert('请指定文档ID！');
	var x = new Ajax('XML');
		x.get(cmsUrl + 'tools/comment.php?action=replay&inajax=1&aid='+aid+'&cid='+cid+'&option='+opt, function(s){
		
		if(s != 'succeed'){
			alert(s);
		}else{
			var bid = id;
			var a = $("#"+bid).text();
			var num=parseInt(a)+1;
			$("#"+bid).text(num);
		} 
		
	});
	
}

