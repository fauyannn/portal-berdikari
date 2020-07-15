var _po = '';
var db_items = [];
var get_item_from= '';
$(document).ready(function(){
    // var s = $.urlParam('status'); // name

    $('#form-group-get_item_from input').on('change',function(){
        var $this = $(this);
        var val = $this.val();
        get_item_from = val;
        $('div#form-group-purchase_order_number').hide();
        if(val == 'poe' || val == 'dnp'){
            var supplier = $('[name="supplier"]').val();
            getPO(supplier);
            $('div#form-group-purchase_order_number').slideDown(300);
        }
    })


    if($('[name="_file_invoice"]').length){
        $('input[value="Submit"]').removeClass('hide');
    }
    $('form div.child-form-area').parent().css('display','none');
    // $('a[onclick="editRowitems(this)"], a[onclick="deleteRowitems(this)"]').parent().html('-');

    var btnSave = '<a href="#panel-form-items" class="btn btn-success btn-xs btn-save"><i class="fa fa-check"></i></a>';
    $('body').on('click','a[onclick="editRowitems(this)"]',function(){
        // $('form div.child-form-area').parent().css('display','none');
        var $this = $(this);
        $this.parents('tr').find('textarea').show();
        $this.parents('tr').find('td:last a.btn-warning').hide().before(btnSave);

        // $('div.child-form-area').parent().css('display','block');
        $this.parents('tr').find('input[name="items-qty[]"]').parent().find('span').hide();
        $this.parents('tr').find('input[name="items-qty[]"]').attr('type','text').css('width','60px');
        
        // console.log()
        $this.parents('tr').find('input').show();   
        
        changeFormat($this);     
        setTimeout(function(){
            $('form div.child-form-area').parent().css('display','none');
        },5)
    })

    function changeFormat($this){
        $parent = $this.parents('tr');
        var qty = $parent.find('td.qty').html();
            qty = qty.split('<');
        if(qty.length == 2){
            var newHtml = '<span class="td-label" style="display: none;">'+qty[0]+'</span>';
                newHtml += '<'+qty[1];
            $parent.find('td.qty').html(newHtml);
        }       
        
        // console.log(qty);
    }

    $(document).on('click','a.btn-save',function(){
        // $('input[type="submit"]').click();
        var $this = $(this);
        $this.parents('tr').find('input,textarea').hide();
        $(this).parents('tr').find('span').show();
        $(this).parents('tr').find('td:last a.btn-warning').show();
        $(this).parents('tr').find('td:last a.btn-save').hide();
    })

    $('body').on('keyup','input[name="items-qty[]"]',function(){
        var $this = $(this);
        $this.parents('td').find('span').text($this.val());
        var qty = $this.val();
        $this.val(qty);
        var rate = $this.parents('tr').find('td.rate input').val();
        var amount = parseInt(qty) * parseInt(rate);

        $this.parents('tr').find('td.amount span').text(amount);
        $this.parents('tr').find('td.amount input').val(amount);
        

    })


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
        $('form #form-group-file_invoice').find('a.btn-delete').remove();
        $('#form-group-get_item_from').hide();
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
    
    //rate masih bisa berubah2 ketika status belum closed
    if(_status != 'closed' && _id){
        refreshItem();
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


    function getPO(supplier){

        var _url = '/admin/purchase_invoices/podn/'+supplier+'?item=2';
        if(get_item_from == 'poe'){
            _url = '/admin/delivery_notes/porder/'+supplier+'?item=1';
        } 
        
        var data = [];
    
        // if($('select#purchase_order_number').find('option[value="0"]').length < 1){
            var d = {
                id: 0,
                text: '*** Select a Purchase Order Number'
            };
            var newOption = new Option(d.text, d.id, false, false);
            $('select#purchase_order_number').html(newOption).trigger('change');
        // }
            
        $.get(_url, function(res){
            data = res.data;
            console.log(data);
            $.each(data, function(k,v){
                var d = {
                    id: v.name,
                    text: v.name
                };    
                var newOption = new Option(d.text, d.id, false, false);
                $('select#purchase_order_number').append(newOption).trigger('change');            
    
            });
            
            
        }).done(function(){
            setDBItems();
        });
    }
    
    
    function setDBItems(){
        var i=0;
        $('table#table-items tbody tr').each(function(k,elm){
            var po = $(elm).find('td.purchase_order_number input').val();
            var item_code = $(elm).find('td.item_code input').val();
            var _id = (po+'__'+item_code).trim().replace(/[_\W]+/g, "-");
            db_items[i] = _id;
    
            $(elm).attr('id',_id);
    
            // console.log(elm);
            i++;
        });
        // console.log(db_items)
    }

    $('select#purchase_order_number').on('change',function(){
        var $this = $(this);
        var supplier = $('input[name="supplier"]').val();
        _po = $this.val();
        // console.log(_po);
        getItemByPO(supplier,_po)
    })
    function getItemByPO(supplier,po){
        // var param = supplier+'__'+delivery_date+'__'+po;
        var _url = '/admin/purchase_invoices/items/'+po;
        if(get_item_from == 'poe'){
            _url = '/admin/purchase_orders/show/'+po;
        }
        
        var data = [];
        
        $('#table-items-po').show();
        if(po == 0){
            $('#table-items-po').hide();
            return false;
        }
        
        var _table = '<div class="clearfix"></div><div class="col-sm-2"></div><div class="col-sm-9"><table id="table-items-po" class="table table-striped table-bordered">'+
                        '<thead>'+
                            '<tr>'+
                                '<th></th>'+
                                '<th>Item Code</th>'+
                                '<th>Item Name</th>'+
                                '<th>QTY</th>'+
                                '<th>UOM</th>'+
                                '<th>Rate</th>'+
                                '<th>Amount</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody><tr><td colspan="7"><center>loading...</center></td></tr>'+
                        
                        '</tbody>'+
                    '</table></div>';
    
        
        if(!$('#table-items-po').length){
            $('#form-group-purchase_order_number').append(_table);
        }
        
        $.get(_url, function(res){
            data = res;
            // console.log(data);
            var _tr = '';
            $.each(data, function(k,v){
                var _id = (_po+'__'+v.item_code).trim().replace(/[_\W]+/g, "-");
                var _checked = ($.inArray(_id, db_items) != -1) ? 'checked' : '';
                var _rate = parseInt(v.rate);
                var _amount = _rate * v.qty;
                _tr += '<tr>'+
                '<td class="pilih">'+
                    '<input type="checkbox" class="pilih" id="'+v.item_code+'" '+_checked+'></input>'+
                '</td>'+
                '<td class="item_code">'+
                    '<span class="td-label">'+v.item_code+'</span>'+                                  
                '</td>'+
                '<td class="item_name">'+
                    '<span class="td-label">'+v.item_name+'</span>'+                                     
                '</td>'+
                '<td class="qty">'+
                    '<span class="td-label pull-right">'+v.qty+'</span>'+
                '</td>'+
                '<td class="uom">'+
                    '<span class="td-label">'+(v.stock_uom ? v.stock_uom : v.uom)+'</span>'+
                '</td>'+
                '<td class="rate">'+
                    '<span class="td-label pull-right">'+_rate+'</span>'+
                '</td>'+
                '<td class="amount">'+
                    '<span class="td-label pull-right">'+_amount+'</span>'+
                '</td>'+
            '</tr>';
            });
            // console.log(_tr)
            $('body').find('#table-items-po').find('tbody').html(_tr);
        });
    }    

    $(document).on('click','input.pilih',function(){
        var $this = $(this);
        var item_code   = $this.closest('tr').find('td.item_code').text();
        var item_name   = $this.closest('tr').find('td.item_name').text();
        var qty         = $this.closest('tr').find('td.qty').text();
        var uom         = $this.closest('tr').find('td.uom').text();
        var rate        = $this.closest('tr').find('td.rate').text();
        var amount      = $this.closest('tr').find('td.amount').text();
        // console.log(_po)
        if($this.is(':checked')) {
            $('#panel-form-items').find('#itemspurchase_order_number').val(_po);
            $('#panel-form-items').find('#itemsitem_code').val(item_code);
            $('#panel-form-items').find('#itemsitem_name').val(item_name);
            $('#panel-form-items').find('#itemsqty').val(qty);
            $('#panel-form-items').find('#itemsuom').val(uom);
            $('#panel-form-items').find('#itemsrate').val(rate);
            $('#panel-form-items').find('#itemsamount').val(amount);
            // $('#panel-form-items').find('#itemsbatch_no').val('-');
            // $('#panel-form-items').find('#itemsserial_no').val('-');
            addToTableitems();
            setDBItems();

            // $('a[onclick="editRowitems(this)"], a[onclick="deleteRowitems(this)"]').parent().html('-');
        } else {
            $('table#table-items')
                .find('tr#'+(_po+'__'+item_code).trim().replace(/[_\W]+/g, "-"))
                .remove();
        }
        
    });

    // if ( $( "select#supplier_invoice_number").length ) {
    //     $('select#supplier_invoice_number').attr('id','my_supplier_invoice_number');
    //     var _url = '/admin/purchase_invoices/purchaseinvoice/1';
    //     $('#my_supplier_invoice_number').select2({
    //         placeholder: {
    //             id: '-1',
    //             text: '** Please select a Purchase Invoice'
    //         },
    //         allowClear: true,
    //         minimumInputLength: 1,
    //         ajax: {
    //             url: _url,
    //             delay: 250,
    //             dataType: 'json',
    //             data: function (params) {
    //                 return {
    //                     q: $.trim(params.term)
    //                 };
    //             },
    //             processResults: function (data) {
    //                 // console.log(data.items)
    //                 return {
    //                     results: data
    //                 };
    //             },
    //             cache: true
    //         },escapeMarkup: function (markup) {
    //             return markup;
    //         },
    //     });
        

    //     var _val = $('input[name="supplier_invoice_number"]').val();
    //     var data = {
    //         id: _val,
    //         text: _val
    //     };    
    //     var newOption = new Option(data.text, data.id, false, false);
    //     $('#my_supplier_invoice_number').append(newOption).trigger('change');

        
        
    //     $('#my_supplier_invoice_number').on('change',function(){
    //         var $this = $(this);
    //         var val = $this.val();
    //         console.log(val);
    //         var _url = '/admin/purchase_invoices/purchaseinvoicedetail/1?idx='+val;
    //         var data = [];
    //         $.get(_url, function(res){
    //             data = res.data;
    //             // console.log(data);
    //             $('input#supplier_date').val(data.posting_date)
    //             $('input#supplier').val(data.supplier)
    //             // insert items
    //             $('table#table-items tbody').html('') //clear data childs
    //             $.each(data.items,function(k,v){
    //                 $('input#itemsitem_code').val(v.item_code)
    //                 $('input#itemsitem_name').val(v.item_name)
    //                 $('input#itemsqty').val(v.qty)
    //                 $('input#itemsuom').val(v.uom)
    //                 $('input#itemsrate').val(v.rate)
    //                 $('input#itemsamount').val(v.amount)
    //                 $('input#btn-add-table-items').click()
    //             })

    //         });

    //     })

    // }

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

function refreshItem(){
    console.log('refresh item');
    var _id = $('input[name="id"]').val();
    var po = [];

    po['po']        = [];
    po['item_code'] = [];
    $('table#table-items tbody tr').each(function(k,v){

        var val_po          = $(v).find('input[name="items-purchase_order_number[]"]').val();
        var val_item_code   = $(v).find('input[name="items-item_code[]"]').val();

        po['po'][k]             = val_po;
        po['item_code'][k]      = val_item_code;
    })

    $.ajax({
        type: "GET",
        url: "/admin/purchase_invoices/refreshitem/"+_id,
        dataType: "json",
        data:  { 
            po : po['po'].join('|||'),
            item_code : po['item_code'].join('|||'),
         },
        success: function(res){
            console.log(res)
            $('table#table-items tbody tr').each(function(k,v){
                var val_po   = $(v).find('input[name="items-purchase_order_number[]"]').val();
                var val_ic   = $(v).find('input[name="items-item_code[]"]').val();

                if(res[val_po]){
                    var qty         = res[val_po][val_ic]['qty'];
                    var rate        = res[val_po][val_ic]['rate'];
                    var amount      = res[val_po][val_ic]['amount'];
                    var uom         = res[val_po][val_ic]['stock_uom'];
                    console.log(rate)
                    $(v).find('td.qty').find('span').text(qty);
                    $(v).find('td.rate').find('span').text(rate);
                    $(v).find('td.amount').find('span').text(amount);
                    $(v).find('td.uom').find('span').text(uom);

                    $(v).find('td.qty').find('input').val(qty);
                    $(v).find('td.rate').find('input').val(rate);
                    $(v).find('td.amount').find('input').val(amount);
                    $(v).find('td.uom').find('input').val(uom);
                }
            })
        }
    });
    // $('input[name="items-purchase_order_number[]"]').each(function(k,v){
    //     po[k] = $(v).val();
    // })
    // $('input[name="items-item_code[]"]').each(function(k,v){
    //     item_code[k] = $(v).val();
    // })

}

