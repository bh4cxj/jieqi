// JavaScript Document


$(document).ready(function(){
    //$('.gonggao').kxbdSuperMarquee({
//                isEqual:false,
//                distance:25,
//                time:5,
//                //btnGo:{up:'#goU',down:'#goD'},
//                direction:'up'
//            });
    $('.main_tab_con').css('display','none');
    $('.remenxiaoshuo .main_tab_con').css('display','block');
    $('.remenxiaoshuo .main_tab_title').css('background','url(/template/newchaoliu/images/newimages/main_tab_line_35.gif) no-repeat left bottom');
    $('.main_tab_title').hover(function(){
        $('.main_tab_title').css('background','none');
        $(this).css('background','url(/template/newchaoliu/images/newimages/main_tab_line_35.gif) no-repeat left bottom');
        $('.main_tab_con').css('display','none');
        $(this).next().css('display','block');
        },function(){
        });
    $('.qiangtui ul').css('display','none');
    $('.nanpintui ul').css('display','block');
    $('.nanpintui,.nvpintui').css('border-bottom','none');    
    $('.nvpintui').css('border-bottom','1px solid #d3d3d3');
    $('.nanpintui,.nvpintui').css('background','none');
    $('.nvpintui').css('background','url(/template/newchaoliu/images/newimages/qiangtui_tab_bg_29.jpg) no-repeat left top');
    $('.qiangtui p').hover(function(){
        $('.qiangtui ul').css('display','none');
        $(this).next().css('display','block');
        },function(){
        });    
    $('.nvpintui p').hover(function(){
        $('.nanpintui,.nvpintui').css('border-bottom','none');    
        $('.nanpintui').css('border-bottom','1px solid #d3d3d3');
        $('.nanpintui,.nvpintui').css('background','none');
        $('.nanpintui').css('background','url(/template/newchaoliu/images/newimages/qiangtui_tab_bg_29.jpg) no-repeat left top');
        },function(){
        });    
    $('.nanpintui p').hover(function(){
        $('.nanpintui,.nvpintui').css('border-bottom','none');    
        $('.nvpintui').css('border-bottom','1px solid #d3d3d3');
        $('.nanpintui,.nvpintui').css('background','none');
        $('.nvpintui').css('background','url(/template/newchaoliu/images/newimages/qiangtui_tab_bg_29.jpg) no-repeat left top');
        },function(){
        });    
})


    