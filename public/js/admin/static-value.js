let sweetalert = function(icon, title, text){
    Swal.fire({
        icon: icon,
        title: title,
        text: text
    })
}

let edit = function(id){
    $('#static-value-'+id+' td.label, #static-value-'+id+' td.value').addClass('p-1');
    $('#static-value-'+id).find('span').removeClass('d-block').addClass('d-none');
    $('#static-value-'+id).find('i.fa-edit').removeClass('d-inline-block').addClass('d-none');
    $('#static-value-'+id).find('i.fa-window-close').removeClass('d-none').addClass('d-inline-block');
    $('#static-value-'+id).find('i.fa-save').removeClass('d-none').addClass('d-inline-block');
    $('#static-value-'+id).find('input[name="label"]').removeClass('d-none').addClass('d-block');
    $('#static-value-'+id).find('input[name="value"]').removeClass('d-none').addClass('d-block');

    $('#static-value-'+id).find('input[name="label"]').val( $('#static-value-'+id+' .label span').text() );
    $('#static-value-'+id).find('input[name="value"]').val( $('#static-value-'+id+' .value span').text() );
}

let cancel = function(id){
    $('#static-value-'+id+' td.label, #static-value-'+id+' td.value').removeClass('p-1');
    $('#static-value-'+id).find('span').removeClass('d-none').addClass('d-block');
    $('#static-value-'+id).find('i.fa-edit').removeClass('d-none').addClass('d-inline-block');
    $('#static-value-'+id).find('i.fa-window-close').removeClass('d-inline-block').addClass('d-none');
    $('#static-value-'+id).find('i.fa-save').removeClass('d-inline-block').addClass('d-none');
    $('#static-value-'+id).find('input[name="label"]').removeClass('d-block').addClass('d-none');
    $('#static-value-'+id).find('input[name="value"]').removeClass('d-block').addClass('d-none');

    $('#static-value-'+id).find('input[name="label"]').css('border', '1px solid #ced4da');
    $('#static-value-'+id).find('input[name="value"]').css('border', '1px solid #ced4da');
}

let save = function(id){
    var label = $('#static-value-'+id).find('input[name="label"]').val();
    var value = $('#static-value-'+id).find('input[name="value"]').val();
    if(label == ''){
        $('#static-value-'+id).find('input[name="label"]').css('border', '1px solid red').focus();
        return false;
    }
    if(value == ''){
        $('#static-value-'+id).find('input[name="value"]').css('border', '1px solid red');
        return false;
    }

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#static-value-'+id+' input[name="_token"]').val()},
        type:'POST',
        url:'/static-value/save',
        data:{id:id, label:label, value:value},
        success:function(data){
            if(data.status == 200){
                $('#static-value-' + id + ' .label span').text(label);
                $('#static-value-' + id + ' .value span').text(value);
                sweetalert('success', 'Success!', data.message);  
            }else{
                sweetalert('error', 'Oops...', data.message);  
            }
            cancel(id);
        }

     });
}