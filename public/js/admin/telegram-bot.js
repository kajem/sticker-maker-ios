$(document).ready(function (){
    $('form#telegram-pack button').on('click', function (e){
        e.preventDefault();
        var images = [];

        $('form#telegram-pack img').each(function (){
            images.push($(this).attr('data-src'));
        });
        console.log(images);
    });
})

// $.ajax({
//     type:'POST',
//     url:'/contact/send-mail',
//     data:{ name: name, from_email: from_email, subject: subject, message: message},
//     success:function(data){
//
//     }
// });
