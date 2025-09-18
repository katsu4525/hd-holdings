//BURGERクリック時の処理
$(function() {
    //バーガーラインの生成
    $('#burger').append('<span></span><span></span><span></span>');
    
    //バーガークリック時の処理
    $('#burger').on('click', function(){
        $(this).toggleClass('active');
        
        if($(this).hasClass('active')){
            $('#leftNav').addClass('visible');
        } else{
            $('#leftNav').removeClass('visible');
        }
    });
});

