$(document).ready(function(){
		
		$('.fontsize').click(function(){
			if($('#fontsize_list').css('display')=='none'){
				$('.btn_list').css('display','none')
				$('#fontsize_list').css('display','block')
			}else{
				$('.btn_list').css('display','none')	
			}
			})
		$('.changewidth').click(function(){
			if($('#width_list').css('display')=='none'){
				$('.btn_list').css('display','none')
				$('#width_list').css('display','block')
			}else{
				$('.btn_list').css('display','none')	
			}
			})	
		$('.daynight').click(function(){
			if($('#daynight_list').css('display')=='none'){
				$('.btn_list').css('display','none')
				$('#daynight_list').css('display','block')
			}else{
				$('.btn_list').css('display','none')	
			}
			})	
		$('.mulu').click(function(){
			if($('#mulu_list').css('display')=='none'){
				$('.btn_list').css('display','none')
				$('#mulu_list').css('display','block')
			}else{
				$('.btn_list').css('display','none')	
			}
			})
		$('.collect').click(function(){
			$('.btn_list').css('display','none')	
			
			})
		$('.dashang').click(function(){
			$('.btn_list').css('display','none')	
			
			})
		
		/*修改字体js*/
		$('.font_mr a').click(function(){
			
			$('.chapter_con p').css('font','14px/2 "微软雅黑","宋体", sans-serif')
				var family='"微软雅黑","宋体",sans-serif'
				document.cookie="font-family="+family; 
				document.cookie="font-size=14px"; 
				document.cookie="width=60%"; 	
			})
		$('.font_st a').click(function(){
			$('.chapter_con p').css('font-family','"宋体", sans-serif')
			var family='"宋体",sans-serif'
			document.cookie="font-family="+family; 
				
			})
		$('.font_ht a').click(function(){
			
			$('.chapter_con p').css('font-family','"黑体","宋体", sans-serif')
				var family='"黑体","宋体",sans-serif'
				document.cookie="font-family="+family; 
			})
		$('.font_yh a').click(function(){
			$('.chapter_con p').css('font-family','"微软雅黑","宋体", sans-serif')
				var family='"微软雅黑","宋体",sans-serif'
				document.cookie="font-family="+family; 
				
			})
		$('.font_kt a').click(function(){
			$('.chapter_con p').css('font-family','"楷体","宋体", sans-serif')
				var family='"楷体","宋体", sans-serif'
				document.cookie="font-family="+family; 
			})
		
		/*修改字大小js*/	
		$('.font_16 a').click(function(){
			$('.chapter_con p').css('font-size','16px')
			$('.chapter_con p').css('line-height','32px')
				document.cookie="font-size=16px"; 	
			})
		
		$('.font_18 a').click(function(){
			$('.chapter_con p').css('font-size','18px')
			$('.chapter_con p').css('line-height','36px')
					document.cookie="font-size=18px"; 
			})
		$('.font_24 a').click(function(){
			$('.chapter_con p').css('font-size','24px')
			$('.chapter_con p').css('line-height','48px')
					document.cookie="font-size=24px"; 
			})
		$('.font_32 a').click(function(){
			$('.chapter_con p').css('font-size','32px')
			$('.chapter_con p').css('line-height','64px')
			document.cookie="font-size=32px"; 
			})
		
		/*修改宽度js*/
		$('.width_820 a').click(function(){
			$('#wrap').css('width','60%')
			$('.side_btn').css('left','80%')
			document.cookie="width=60%"; 	
			})
		$('.width_1080 a').click(function(){
			$('#wrap').css('width','70%')
			$('.side_btn').css('left','85%')
			document.cookie="width=70%"; 	
					
			})
		$('.width_1240 a').click(function(){
			$('#wrap').css('width','75%')
			$('.side_btn').css('left','87.5%')
			document.cookie="width=75%"; 	
					
			})
		$('.width_1400 a').click(function(){
			$('#wrap').css('width','80%')
			$('.side_btn').css('left','90%')
			document.cookie="width=80%"; 	
					
			})
		$('.width_1720 a').click(function(){
			$('#wrap').css('width','85%')
			$('.side_btn').css('left','92.5%')
			document.cookie="width=85%"; 	
					
			})
		
		/*修改夜间模式js*/
		$('.day a').click(function(){
			$('body').css('background-image','url(/images/wood.jpg)')
			$('#wrap').css('background-color','#f1f1f1')
			$('.chapter_con p').css('color','#333')
			$('h1.chapter_title').css('color','#495163')
			document.cookie="background-image=url(/images/wood.jpg)"; 
			document.cookie="background-color=#f1f1f1"; 
			document.cookie="color1=#333"; 
			document.cookie="color2=#495163"; 
			})
		$('.night a').click(function(){
			$('body').css('background-image','url(/images/bg.jpg)')
			$('#wrap').css('background-color','#0a0a0a')
			$('.chapter_con p').css('color','#999')
			$('h1.chapter_title').css('color','#999')
			document.cookie="background-image=url(/images/bg.jpg)"; 
			document.cookie="background-color=#0a0a0a"; 
			document.cookie="color1=#999"; 
			document.cookie="color2=#999"; 					
			})
	})
	
	function clearthis(o){
        if(o.contains(event.toElement ) == false) {
		$('.btn_list').css('display','none');		
		}	
	}
	function clear_all(){
		$('.btn_list').css('display','none');	
	}
	function fontsize_list(){
	$('#fontsize_list').css('display','block')

	}
	function width_list(){
	$('#width_list').css('display','block')
	}
	function daynight_list(){
	$('#daynight_list').css('display','block')
	}
	function mulu_list(){
	$('#mulu_list').css('display','block')
	}
document.onkeydown=nextpage;
function nextpage(event)
{
	//上一页链接
	var prevpage = document.getElementById("s1").value;
	//下一页链接
	var nextpage = document.getElementById("x1").value;
   event = event ? event : (window.event ? window.event : null);
   if (event.keyCode==39)
   {
     location=nextpage
   }
  if (event.keyCode==37)
  {
   location=prevpage;
  }

}

