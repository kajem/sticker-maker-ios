ClassicEditor
    .create( document.querySelector( '#description' ), {
        toolbar: {
            items: [
                'heading',
                '|',
                'bold',
                'italic',
                'underline',
                'strikethrough',
                'link',
                'bulletedList',
                'numberedList',
                '|',
                'indent',
                'outdent',
                '|',
                'blockQuote',
                'insertTable',
                'mediaEmbed',
                'undo',
                'redo',
                'htmlEmbed',
                'fontColor',
                'fontSize',
                'fontFamily',
                'highlight',
                'horizontalLine',
                'code',
                'alignment',
                'CKFinder'
            ]
        },
        language: 'en',
        // image: {
        //     toolbar: [
        //         'imageTextAlternative',
        //         'imageStyle:full',
        //         'imageStyle:side',
        //         'linkImage'
        //     ]
        // },
        image: {
                // Configure the available styles.
                styles: [
                    'alignLeft', 'alignCenter', 'alignRight'
                ],

                // Configure the available image resize options.
                resizeOptions: [
                    {
                        name: 'imageResize:original',
                        label: 'Original',
                        value: null
                    },
                    {
                        name: 'imageResize:50',
                        label: '50%',
                        value: '50'
                    },
                    {
                        name: 'imageResize:75',
                        label: '75%',
                        value: '75'
                    }
                ],

                // You need to configure the image toolbar, too, so it shows the new style
                // buttons as well as the resize buttons.
                toolbar: [
                    'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight',
                    '|',
                    'imageResize',
                    '|',
                    'imageTextAlternative'
                ]
            },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
            ]
        },
        licenseKey: '',

    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( error => {
        console.error( 'Oops, something went wrong!' );
        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
        console.warn( 'Build id: th0kdamdami7-vjqknlklo0c5' );
        console.error( error );
    } );

jQuery(document).ready(function (){
    // Datapicker
    $( "#published_date" ).datepicker({
        "dateFormat": "yy-mm-dd"
    });

    $('#title').on('focusout', function (){
       if( $.trim($('#slug').val()) == ''){
           $('#slug').val(convertToSlug($('#title').val()))
       }
    });

    var meta_title_max_length = 60;
    $('#meta_title').keyup(function() {
        var textlen = meta_title_max_length - $(this).val().length;
        $('#meta_title_char_count').text(' Characters left: ' + textlen);
    });

    var meta_description_max_length = 160;
    $('#meta_description').keyup(function() {
        var textlen = meta_description_max_length - $(this).val().length;
        $('#meta_description_char_count').text(' Characters left: ' + textlen);
    });

    var short_description_max_length = 500;
    $('#short_description').keyup(function() {
        var textlen = short_description_max_length - $(this).val().length;
        $('#short_description_char_count').text(' Characters left: ' + textlen);
    });
});

function convertToSlug(text)
{
    return text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}
