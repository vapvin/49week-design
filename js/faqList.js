$(document).ready(function(){
    textchange = false;
    $('.faq_data_btn01,.faq_label_btn01').click(function(){
        if(textchange){
        textchange = false;
        $('.faq_data_btn01').text('▼');
        }else{
        textchange = true;
        $('.faq_data_btn01').text('▲');
        }
        $('.faq_data_on01').toggle('500',function(){
        // $(this).css('display','block');
        });
    })

    $('.faq_data_btn02,.faq_label_btn02').click(function(){
        if(textchange){
        textchange = false;
        $('.faq_data_btn02').text('▼');
        }else{
        textchange = true;
        $('.faq_data_btn02').text('▲');
        }
        $('.faq_data_on02').toggle('500',function(){
        // $(this).css('display','block');
        });
    })

    $('.faq_data_btn03,.faq_label_btn03').click(function(){
        if(textchange){
        textchange = false;
        $('.faq_data_btn03').text('▼');
        }else{
        textchange = true;
        $('.faq_data_btn03').text('▲');
        }
        $('.faq_data_on03').toggle('500',function(){
        // $(this).css('display','block');
        });
    })

    $('.faq_data_btn04,.faq_label_btn04').click(function(){
        if(textchange){
        textchange = false;
        $('.faq_data_btn04').text('▼');
        }else{
        textchange = true;
        $('.faq_data_btn04').text('▲');
        }
        $('.faq_data_on04').toggle('500',function(){
        // $(this).css('display','block');
        });
    })
});