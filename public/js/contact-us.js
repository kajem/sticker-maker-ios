$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function save(){
    $('#contact-us .alert').remove();
    $('#contact-us .text-danger').addClass('d-none');
    $('#contact-us input[type="text"], #contact-us textarea').css('border', '1px solid #ced4da');
    var error = 0;
    var name = $.trim($('input[name="name"]').val());
    if(name == ''){
        error = 1;
        $('input[name="name"]').css('border', '1px solid red').focus();
        $('input[name="name"]').parent().find('.text-danger').removeClass('d-none');
    }

    var from_email = $.trim($('input[name="from_email"]').val());
    if(from_email == ''){
        error = 1;
        $('input[name="from_email"]').css('border', '1px solid red').focus();
        $('input[name="from_email"]').parent().find('.text-danger').removeClass('d-none').text('Email is required!');
    }
    else if(!isEmail(from_email)){
        error = 1;
        $('input[name="from_email"]').css('border', '1px solid red').focus();
        $('input[name="from_email"]').parent().find('.text-danger').removeClass('d-none').text('Please enter a valid email.');
    }

    var subject = $.trim($('input[name="subject"]').val());
    if(subject == ''){
        error = 1;
        $('input[name="subject"]').css('border', '1px solid red').focus();
        $('input[name="subject"]').parent().find('.text-danger').removeClass('d-none');
    }

    var message = $.trim($('textarea[name="message"]').val());
    if(message == ''){
        error = 1;
        $('textarea[name="message"]').css('border', '1px solid red').focus();
        $('textarea[name="message"]').parent().find('.text-danger').removeClass('d-none');
    }

    if(!error){
        $('#contact-us input[type="button"]').val('Please wait...');
        $('#contact-us input[type="button"]').attr('disabled', 'true');

        $.ajax({
            type:'POST',
            url:'/contact/send-mail',
            data:{name: name, from_email: from_email, subject: subject, message: message},
            dataType:"json",
            success:function(data){
                if(data.status == 200){
                    $('#contact-us input[type="text"], #contact-us textarea').val('');
                    $('#contact-us input, #contact-us textarea').css('border', '1px solid #ced4da');
                    $('#contact-us .text-danger').addClass('d-none');

                    $('#contact-us input[type="button"]').val('Submit');
                    $('#contact-us input[type="button"]').removeAttr('disabled');

                    $('#contact-form').before('<div class="alert alert-success alert-block">\n' +
                        // '                    <button type="button" class="close" data-dismiss="alert">×</button>\n' +
                        '                    <strong>'+data.message+'</strong>\n' +
                        '                </div>');
                }else{
                    $('#contact-form').before('<div class="alert alert-danger alert-block">\n' +
                        // '                    <button type="button" class="close" data-dismiss="alert">×</button>\n' +
                        '                    <strong>'+data.message+'</strong>\n' +
                        '                </div>');
                }
            }

        });
    }
}

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}
