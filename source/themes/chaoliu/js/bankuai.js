// JavaScript Document


$(document).ready(function(){
    
    /*$('#rmzptj_fl_first').css('border-bottom','2px solid #ef5f00');*/
    $('.rmzptj_fenlei ul').css('display','none');
    $('#rmzptj_fenlei_first ul').css('display','block');
    /*$('#rmzptj_fl_first').css('color','#ef5f00');*/
    /*$('.rmzptj_fl').hover(function(){
        $('.rmzptj_fenlei ul').css('display','none');
        $(this).next().css('display','block');
        $('.rmzptj_fl').css('border-bottom','none');
        $(this).css('border-bottom','2px solid #ef5f00');
        $('.rmzptj_fl').css('color','#999999');
        $(this).css('color','#ef5f00');
        },function(){
        });*/
    $('.phb_con').css('display','none');
    $('.paihangbang_first .phb_con').css('display','block');
    $('.phb_title').hover(function(){
        $('.phb_con').css('display','none');
        $(this).next().css('display','block');
        },function(){
        });    
})


    


    