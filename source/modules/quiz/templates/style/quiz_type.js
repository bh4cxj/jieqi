function quizchange()
{
	Ajax.Request("quiz_stype.php?aaa="+Math.random()+"&key="+$('quiztype').value,{onLoading:show_loading, onComplete:show_complete});
}
function show_loading()
{
	$("select").innerHTML='<select class="select" name="select" ><option value="0" selected="selected">载入中</option></select>';
}
function show_complete()
{
	$("select").innerHTML=this.response;
}



function novalue(i){
	var ary=new Array();
	ary=i.split('|')
	for(var i=0;i<ary.length;i++){
		if(document.getElementById(ary[i]).value=='')
			{
				alert('请将资料填写完整');
				return false;
			}
	}
}

function add(i){
	if($("addcontent"+i).getValue()!=''){$("addcontent"+i).show();return;}
	if(isNaN(i))
		{
		var title='补充问题';
		}
		else
		{
		var title='补充回答';
		}
	$("addcontent"+i).innerHTML='<form name="form1" id="form1" action="answer.php?action=add" onsubmit="return novalue(\'contents\')"  method="post"><table width="70%" align="center" cellspacing="1" class="grid"><caption>'+title+'</caption>     <tr><td>补充内容:</td><td><textarea class="textarea" name="contents" id="contents" rows="6" cols="60"></textarea></td></tr>	  <tr><td>&nbsp;</td><td><input type="submit" class="button" name="submit" id="submit" value="提 交" /></td></tr></table><input name="quizids" type="hidden" value="'+document.getElementById('quizid').value+'" /><input name="answerid" type="hidden" value="'+i+'" /></form>';
	$("addcontent"+i).show();
}