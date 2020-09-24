<section id="contact-us">
    <div class="container pb-5">
        <div class="row">
            <div class="col-sm-12 pt-5 pb-4">
                <h2 class="title text-center text-white">Contact Us</h2>
            </div>
        </div>
        @include('common.notification')
        <form id="contact-form" action="{{url('contact/send-mail#contact-us')}}" method="post">
            {{ csrf_field() }} {{ method_field('POST') }}
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <input class="form-control" type="text" value="{{!empty(old('name')) ? old('name') : ''}}" name="name" placeholder="Name" required>
{{--                        <span class="text-danger d-none">Name is required!</span>--}}
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif

                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" type="email" name="from_email" value="{{!empty(old('from_email')) ? old('from_email') : ''}}" placeholder="Email" required>
{{--                        <span class="text-danger d-none">Email is required!</span>--}}
                        @if ($errors->has('from_email'))
                            <span class="text-danger">{{ $errors->first('from_email') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <input class="form-control" type="text" name="subject" value="{{!empty(old('subject')) ? old('subject') : ''}}" placeholder="Subject" required>
{{--                        <span class="text-danger d-none">Subject is required!</span>--}}
                        @if ($errors->has('subject'))
                             <span class="text-danger">{{ $errors->first('subject') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <textarea rows="5" class="form-control" name="message" value="{{!empty(old('message')) ? old('message') : ''}}" placeholder="Message" required></textarea>
{{--                        <span class="text-danger d-none">Message is required!</span>--}}
                        @if ($errors->has('message'))
                            <span class="text-danger">{{ $errors->first('message') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6 text-right">
                        <input type="submit" value="Submit" class="btn btn-primary pl-5 pr-5 pt-2 pb-2">
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
{{--<script src="{{asset('js/contact-us.js?2414dduussseswwsrrbf')}}"></script>--}}
{{--<script type="text/javascript">--}}
{{--    function save(){--}}
{{--    $('#contact-us .alert').remove();--}}
{{--    $('#contact-us .text-danger').addClass('d-none');--}}
{{--    $('#contact-us input[type="text"], #contact-us textarea').css('border', '1px solid #ced4da');--}}
{{--    var error = 0;--}}
{{--    var name = $.trim($('input[name="name"]').val());--}}
{{--    if(name == ''){--}}
{{--    error = 1;--}}
{{--    $('input[name="name"]').css('border', '1px solid red').focus();--}}
{{--    $('input[name="name"]').parent().find('.text-danger').removeClass('d-none');--}}
{{--    }--}}

{{--    var from_email = $.trim($('input[name="from_email"]').val());--}}
{{--    if(from_email == ''){--}}
{{--    error = 1;--}}
{{--    $('input[name="from_email"]').css('border', '1px solid red').focus();--}}
{{--    $('input[name="from_email"]').parent().find('.text-danger').removeClass('d-none').text('Email is required!');--}}
{{--    }--}}
{{--    else if(!isEmail(from_email)){--}}
{{--    error = 1;--}}
{{--    $('input[name="from_email"]').css('border', '1px solid red').focus();--}}
{{--    $('input[name="from_email"]').parent().find('.text-danger').removeClass('d-none').text('Please enter a valid email.');--}}
{{--    }--}}

{{--    var subject = $.trim($('input[name="subject"]').val());--}}
{{--    if(subject == ''){--}}
{{--    error = 1;--}}
{{--    $('input[name="subject"]').css('border', '1px solid red').focus();--}}
{{--    $('input[name="subject"]').parent().find('.text-danger').removeClass('d-none');--}}
{{--    }--}}

{{--    var message = $.trim($('textarea[name="message"]').val());--}}
{{--    if(message == ''){--}}
{{--    error = 1;--}}
{{--    $('textarea[name="message"]').css('border', '1px solid red').focus();--}}
{{--    $('textarea[name="message"]').parent().find('.text-danger').removeClass('d-none');--}}
{{--    }--}}

{{--    if(!error){--}}
{{--    $('#contact-us input[type="button"]').val('Please wait...');--}}
{{--    $('#contact-us input[type="button"]').attr('disabled', 'true');--}}

{{--    $.ajax({--}}
{{--    type:'POST',--}}
{{--    url: '/contact/send-mail',--}}
{{--    data:{_token: '{{csrf_token()}}', name: name, from_email: from_email, subject: subject, message: message},--}}
{{--    success:function(data){--}}
{{--    if(data.status == 200){--}}
{{--    $('#contact-us input[type="text"], #contact-us textarea').val('');--}}
{{--    $('#contact-us input, #contact-us textarea').css('border', '1px solid #ced4da');--}}
{{--    $('#contact-us .text-danger').addClass('d-none');--}}

{{--    $('#contact-us input[type="button"]').val('Submit');--}}
{{--    $('#contact-us input[type="button"]').removeAttr('disabled');--}}

{{--    $('#contact-form').before('<div class="alert alert-success alert-block">\n' +--}}
{{--        '                    <button type="button" class="close" data-dismiss="alert">×</button>\n' +--}}
{{--        '                    <strong>'+data.message+'</strong>\n' +--}}
{{--        '                </div>');--}}
{{--    }else{--}}
{{--    $('#contact-form').before('<div class="alert alert-danger alert-block">\n' +--}}
{{--        '                    <button type="button" class="close" data-dismiss="alert">×</button>\n' +--}}
{{--        '                    <strong>'+data.message+'</strong>\n' +--}}
{{--        '                </div>');--}}
{{--    }--}}
{{--    }--}}

{{--    });--}}
{{--    }--}}
{{--    }--}}

{{--    function isEmail(email) {--}}
{{--    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;--}}
{{--    return regex.test(email);--}}
{{--    }--}}
{{--</script>--}}
