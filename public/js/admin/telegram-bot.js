var ajax_request_count = 0;
var progress_bar_parcentage = 0;
var item_id = 0;
var telegram_name = '';
$(document).ready(function (){
    $('form#telegram-pack button').on('click', function (e){
        e.preventDefault();
        var images = [];
        ajax_request_count = 0;
        progress_bar_parcentage = 0;

        $('form#telegram-pack img').each(function (){
            images.push($(this).attr('data-src'));
        });
        //console.log(images);
        if(images.length > 0){
            item_id = $('form#telegram-pack input[name="id"]').val();
            telegram_name = $('input[name="telegram_name"]').val();
            progress_bar_parcentage = 100/images.length;
            $('.progress').removeClass('d-none');

            createNewStickerSet(images);
        }
    });
})

function createNewStickerSet(images){
    var is_first_request = 0;
    var is_last_request = 0;
    if(ajax_request_count == 0){
        is_first_request = 1;
    }
    if(ajax_request_count == images.length - 1){
        is_last_request = 1;
    }

    var data = {
        id: item_id,
        telegram_name: telegram_name,
        is_first_request: is_first_request,
        is_last_request: is_last_request,
        png_sticker: images[ajax_request_count]
    };

    $.ajax({
        type:'POST',
        url:'/telegram/create-new-sticker-set',
        data: data,
        success:function(data){
            if(data.status !== 200 || data.data.ok === false){
                $('.progress').addClass('d-none');
                $('.message').removeClass('d-none');
                var message = data.data.description !== undefined ? data.data.description : data.message;
                $('.message .alert').removeClass('alert-success').addClass('alert-danger').find('.text-msg').text(message);
                return false;
            }

            $('#sticker-'+ajax_request_count).append('<span class="position-absolute text-primary" style="top: 0; right: 3px;"><i class="fas fa-check-circle"></i></span>');
            ajax_request_count ++;

            //set the percent to progress bar
            var progress_percent = progress_bar_parcentage*ajax_request_count;
            $('.progress .progress-bar').attr('aria-valuenow', progress_percent).css({width: progress_percent+'%'}).text(Math.ceil(progress_percent) + '%');

            if(ajax_request_count < images.length){
                createNewStickerSet(images);
            }
            if(is_last_request == 1){
                $('.progress').addClass('d-none');
                $('.message').removeClass('d-none');
                $('.message .alert').removeClass('alert-danger').addClass('alert-success').find('.text-msg').text(data.message);
            }
        }
    });
}


