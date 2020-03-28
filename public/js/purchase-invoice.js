$(document).ready(function(){
    // var s = $.urlParam('status'); // name

    var status = $('input[name="status"]').val();
        status = status ? status : $.urlParam('status');
        status = labelStatus(status);
    $('.panel-heading:first').append(status);

    $('#due_date').attr('readonly',false);
    var _id = $("input[name=\"id\"]").val()
    var _status = $('input[name="status"]').attr('value');
    if(_status != 'draft' && _id){
        $('form input[name="submit"]').attr('disabled',true).hide();
        $('form #table-items a').attr('disabled',true).remove();
        $('form input').attr('readonly',true);
        $('form div.child-form-area').parent().css('display','none');
        $('form #form-group-file_invoice').find('a.btn-delete').remove();
    }
    if(_status == 'submited'){
        $('input[name="generate_invoice"]').show();
    }
    if(_status == 'open'){
        $('input[name="close_invoice"]').show();
    }
    if(_status == 'closed'){
        $('input[name="reopen"]').show();
    }

    
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
        var status = $this.find('td:eq(4)').text();
        // console.log(val)
        var url_detail = $this.find('a.btn-detail').attr('href');
                         $this.find('a.btn-detail').attr('href',url_detail+'&idx='+val+'&status='+status);

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
                // console.log(data);
                $('input#supplier_date').val(data.posting_date)
                $('input#supplier').val(data.supplier)
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

    // $('input[value="Submit"]').on('click',function(e){
    //     var $this = $(this);
    //     console.log($this.closest('form').attr('id'))
    //     $this.closest('form').submit();
        

    //     swal({
    //         title: "Permanently submit data?",
    //         text: "Send document to PT. Berdikari Metal Engineering.",
    //         type: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         confirmButtonClass: "btn-danger",
    //         confirmButtonText: "Yes, Submit it!",
    //         cancelButtonText: "No, cancel please!",
    //         closeOnConfirm: true,
    //         closeOnCancel: true
    //       },
    //       function(isConfirm) {
    //         if (isConfirm) {
    //             console.log($this)
    //             $this.closest('form').submit();
    //             e.preventDefault();
    //             // swal("Deleted!", "Your imaginary file has been deleted.", "success");
    //         } else {
    //             e.preventDefault();
    //             // swal("Cancelled", "Your imaginary file is safe :)", "error");
    //         }
    //       });
    //     //   e.preventDefault();
    // })


    $('input[name="generate_invoice"]').on('click',function(){
        var $this = $(this);
        // $this.closest('form').submit();
        swal({
            title: "Generate this invoice?",
            text: "Generate invoice to ERP and change status to open.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, generate it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
            },
            function(isConfirm) {
            if (isConfirm) {
                console.log($this.data('url'));
                window.location.href = $this.data('url');
                
                // swal("Deleted!", "Your imaginary file has been deleted.", "success");
            } else {
                // swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        });
    });

    $('input[name="close_invoice"]').on('click',function(){
        var $this = $(this);
        // $this.closest('form').submit();
        swal({
            title: "Close this invoice?",
            text: "Change status invoice to closed.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, close it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
            },
            function(isConfirm) {
            if (isConfirm) {
                // console.log($this.data('url'));
                window.location.href = $this.data('url');
                
                // swal("Deleted!", "Your imaginary file has been deleted.", "success");
            } else {
                // swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        });
    });

    $('input[name="reopen"]').on('click',function(){
        var $this = $(this);
        // $this.closest('form').submit();
        swal({
            title: "Reopen this invoice?",
            text: "Change status invoice to open.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, re-open it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
            },
            function(isConfirm) {
            if (isConfirm) {
                // console.log($this.data('url'));
                window.location.href = $this.data('url');                
                // swal("Deleted!", "Your imaginary file has been deleted.", "success");
            } else {
                // swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        });
    });
});

