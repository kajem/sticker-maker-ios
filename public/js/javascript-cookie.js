jQuery(document).ready(function (){
    setTimeout(function(){
        $('#cookie-policy').removeClass('d-none');
    }, 1000);

    $('#accept-cookie, a').on('click', function (){
        if(!$(this).hasClass('cookie-policy-info')){
            setCookie('cookie_accepted', 1, 3650);
            $('#cookie-policy').remove();
        }
    });
})
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
