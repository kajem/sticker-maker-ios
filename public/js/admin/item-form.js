$(document).ready(function (){
    checkIsAnimatedField();
    $('#is_animated, #thumb, #stickers').on('change', function (){
        checkIsAnimatedField();
    })

    $('form').on('submit', function (){
        if($("#stickers").get(0).files.length > 0)
        {
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
