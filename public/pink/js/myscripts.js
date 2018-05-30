
/**
 * Created by Настя on 09.03.2018.
 */
jQuery(document).ready(function($){

   $('.commentlist li').each(function(i){

       $(this).find('div.commentNumber').text('#' + (i + 1));

   });

    $('#commentform').on('click','#submit',function(e){

        e.preventDefault();
        
        var comParent = $(this);
        
        $('.wrap_result').css('color','green').
        text('Сохранение комментария').
        fadeIn(500,function(){

            var data = $('#commentform').serializeArray();
          //  alert(data);

            $.ajax({

                url:$('#commentform').attr('action'),
                data:data,
                headers:{'X-CSRF-TOKEN':$('meta[name ="csrf-token"]').attr('content')},
                type:'Post',
                dataType:'JSON',
                success:function(html){
                    if(html.error){
                        $('.wrap_result').css('color','red').append('<br /><strong>Ошибка:</strong>'+ html.error.join('<br />'));
                        $('.wrap_result').delay(2000).fadeOut(500);
                    }else if(html.success){
                        $('.wrap_result').
                        append('<br/></strong>Сохраненно!</strong>')
                            .delay(2000)
                            .fadeOut(500,function(){

                                if(html.data.parent_id > 0){
                                    comParent.parents('div#respond').prev().after('<ul class ="children">'+html.comment +'</ul>');
                                }else{
                                    ///Если есть коментариц но это не подкомент
                                    if($.contains('#comments','ol.commentlist')){
                                            $('#commentlist').append('div#respond').append(html.comment);
                                    }else{
                                        ////если нету вообще коментариев
                                        $('#respond').before('<ol class="commentlist group">'+html.comment + '</ol>')
                                    }
                                }

                                $('#cancel-comment-reply-link').click();

                            })
                    }

                },
                error:function(html){
                    $('.wrap_result').css('color','red').append('<br /><strong>Ошибка:</strong>'+ html.error('<br />'));
                    $('.wrap_result').delay(2000).fadeOut(500,function(){
                        $('#cancel-comment-reply-link').click();
                    })
                }


            });

        });

    });


});