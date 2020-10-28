$(document).ready(function (){
    checkIsAnimatedField();
    $('#is_animated, #thumb, #stickers').on('change', function (){
        checkIsAnimatedField();
    })

    $('form').on('submit', function (){
        if($("#stickers").get(0).files.length > 5)
        {
            $('input#stickers').parent().find('.text-danger').remove();
            $('input#stickers').after('<span class="text-danger">You can upload maximum 28 stickers.</span>');
            return false;
        }else if($("#stickers").get(0).files.length > 0)
        {
            $('input#stickers').parent().find('.text-danger').remove();
            $('#submit-alert').removeClass('d-none');
        }
    })
})

function checkIsAnimatedField(){
    if(($("#thumb").get(0).files.length > 0 ||
        $("#stickers").get(0).files.length > 0) &&
        $('#is_animated option:selected').val() == 0)
    {
            $('#compress_with_pngquant').prop( "checked", true ).parent().parent().parent().removeClass('d-none');
    }else{
        $('#compress_with_pngquant').prop( "checked", false ).parent().parent().parent().addClass('d-none');
    }
}
