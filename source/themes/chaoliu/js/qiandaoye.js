// JavaScript Document


$(document).ready(function(){
	$('.xinxi_content').css('display','none');
	$('.zuopinjianjie .xinxi_content').css('display','block');
	$('.zuopinjianjie').css('border-top','#ab1430 1px solid');	
	$('.zuopinjianjie').css('background-color','#ededed');
	$('.xinxi_title').hover(function(){
		$('.xinxi_content').css('display','none');
		$(this).next().css('display','block');
		$('.zuopinjianjie,.zuopinxinxi,.zuozhexinxi').css('border-top','#dbdee1 1px solid');	
		$('.zuopinjianjie,.zuopinxinxi,.zuozhexinxi').css('background','none');
		$(this).parent().css('border-top','#ab1430 1px solid');
		$(this).parent().css('background-color','#ededed');
		},function(){
		});	
	
})


	