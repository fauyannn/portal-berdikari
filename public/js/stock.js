$(document).ready(function(){
    if ( $( "select#item_code").length ) {
        $('select#item_code').attr('id','my_item_code');
        var _url = '/admin/stock/itembdk/1';
        $('#my_item_code').select2({
            placeholder: {
                id: '-1',
                text: '** Please select a Item'
            },
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
        
        var _val = $('input[name="item_code"]').val();
        var data = {
            id: _val,
            text: _val
        };    
        var newOption = new Option(data.text, data.id, false, false);
        $('#my_item_code').append(newOption).trigger('change');

        $('#my_item_code').on('change',function(){
            var $this = $(this);
            var val = $this.val();
            var _item = $('select#my_item_code').find('option[value="'+val+'"]').text();
            var _item = _item.split(' | ');
            $('input#item_name').val(_item[1]);

        })
    }
})