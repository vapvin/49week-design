$(document).ready(function(){
    textchange = false;
    $('.footer_data_btn01').click(function(){
        if(textchange){
        textchange = false;
        $('.footer_data_btn01').text('설정 ▼');
        }else{
        textchange = true;
        $('.footer_data_btn01').text('설정 ▲');
        
        }
        $('.footer_data_on01').toggle('500',function(){
        // $(this).css('display','block');
        });
    })
    $('.footer_data_btn02').click(function(){
        if(textchange){
        textchange = false;
        $('.footer_data_btn02').text('설정 ▼');
        }else{
        textchange = true;
        $('.footer_data_btn02').text('설정 ▲');
        }
        $('.footer_data_on02').toggle('500',function(){
        // $(this).css('display','block');
        });
    })
});