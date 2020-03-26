$(document).ready(function(){

    $('input#company').attr('id','input_company');
    $('input#input_company').closest('div.form-group').hide();
    if( $('select#company').length){
        $('select#company').attr('id','my_company');
        var _url = '/admin/delivery_notes/supplier/1?e=cfcd208495d565ef66e7dff9f98764da';
        $('#my_company').select2({
            placeholder: {
                id: '-1',
                text: '** Please select a Company'
            },
            // disabled: true,
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: _url,
                delay: 250,
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    // console.log(data.items)
                    return {
                        results: data
                    };
                },
                cache: true
            },escapeMarkup: function (markup) {
                return markup;
            },
        });
        // if($('input[name="my_company"]').val()){
        //     $("#my_supplier").select2({disabled: true});
        // }        

        var _val = $('input[name="company"]').val();
        var data = {
            id: _val,
            text: _val
        };    
        var newOption = new Option(data.text, data.id, false, false);
        $('#my_company').append(newOption).trigger('change');
    }
});