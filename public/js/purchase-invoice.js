$(document).ready(function(){

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


    if ( $( "select#supplier_invoice_number").length ) {
        $('select#supplier_invoice_number').attr('id','my_supplier_invoice_number');
        var _url = '/admin/purchase_invoices/purchaseinvoice/1';
        $('#my_supplier_invoice_number').select2({
            placeholder: {
                id: '-1',
                text: '** Please select a Purchase Invoice'
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
        

        var _val = $('input[name="supplier_invoice_number"]').val();
        var data = {
            id: _val,
            text: _val
        };    
        var newOption = new Option(data.text, data.id, false, false);
        $('#my_supplier_invoice_number').append(newOption).trigger('change');

        $('select#purchase_order_number').attr('id','my_purchase_order_number');
        var _url = '/admin/purchase_invoices/purchaseorder/1';
        $('#my_purchase_order_number').select2({
            placeholder: {
                id: '-1',
                text: '** Please select a Purchase Order Number'
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
        

        var _val = $('input[name="purchase_order_number"]').val();
        var data = {
            id: _val,
            text: _val
        };    
        var newOption = new Option(data.text, data.id, false, false);
        $('#my_purchase_order_number').append(newOption).trigger('change');


        
        $('#my_supplier_invoice_number').on('change',function(){
            var $this = $(this);
            var val = $this.val();
            console.log(val);
            var _url = '/admin/purchase_invoices/purchaseinvoicedetail/1?idx='+val;
            var data = [];
            $.get(_url, function(res){
                data = res.data;
                console.log(data);
                $('input#supplier_date').val(data.posting_date)
                // insert items
                $('table#table-items tbody').html('') //clear data childs
                $.each(data.items,function(k,v){
                    $('input#itemsitem_code').val(v.item_code)
                    $('input#itemsitem_name').val(v.item_name)
                    $('input#itemsqty').val(v.qty)
                    $('input#itemsuom').val(v.uom)
                    $('input#itemsrate').val(v.rate)
                    $('input#itemsamount').val(v.amount)
                    $('input#btn-add-table-items').click()
                })

            });

        })

    }
});

