$(document).ready(function(){

    $('table#table_dashboard tbody tr').each(function(k,_this){
        console.log(_this)
        var $this = $(_this);
        var val = $this.find('td:eq(1)').text();
        console.log(val)
        var url_detail = $this.find('a.btn-detail').attr('href');
                         $this.find('a.btn-detail').attr('href',url_detail+'&idx='+val);

        var url_edit = $this.find('a.btn-edit').attr('href');
                       $this.find('a.btn-edit').attr('href',url_edit+'&idx='+val);

    })


    if ( $( "select#purchase_order").length ) {
        $('select#purchase_order').attr('id','my_purchase_order');
        var _url = '/admin/delivery_notes/deliverynote/1?e=cfcd208495d565ef66e7dff9f98764da';
        $('#my_purchase_order').select2({
            placeholder: {
                id: '-1',
                text: '** Please select a Delivery Note'
            },
            allowClear: true,
            minimumInputLength: 2,
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
        

        var _val = $('input[name="purchase_order"]').val();

        var data = {
            id: _val,
            text: _val
        };    
        var newOption = new Option(data.text, data.id, false, false);
        $('#my_purchase_order').append(newOption).trigger('change');
    }
});
