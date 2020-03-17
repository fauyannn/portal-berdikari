$(document).ready(function(){
    $('div.child-form-area').parent().css('display','none');
    $(document).on('click','a[onclick="editRowitems(this)"]',function(){
        $('div.child-form-area').parent().css('display','block');
    })
    $(document).on('click','input#btn-add-table-items',function(){
        $('div.child-form-area').parent().css('display','none');
    })
    
    $('table#table_dashboard tbody tr').each(function(k,_this){
        // console.log(_this)
        var $this = $(_this);
        var val = $this.find('td:eq(1)').text();
        // console.log(val)
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


        $('#my_purchase_order').on('change',function(){
            var $this = $(this);
            var val = $this.val();
            console.log(val);
            var _url = '/admin/delivery_notes/deliverynotedetail/1?idx='+val;
            var data = [];
            $.get(_url, function(res){
                data = res.data;
                console.log(data);
                $('input#supplier').val(data.customer)
                // insert items
                $('table#table-items tbody').html('') //clear data childs
                $.each(data.items,function(k,v){
                    $('input#itemsitem_code').val(v.item_code)
                    $('input#itemsitem_name').val(v.item_name)
                    $('input#itemsqty').val(v.actual_qty)
                    $('input#itemsuom').val(v.uom)
                    $('input#itemsrate').val(v.rate)
                    $('input#itemsamount').val(v.amount)
                    $('input#btn-add-table-items').click()
                })

            });

        })

    }
});

