var _po = '';
var db_items = [];
$(document).ready(function(){
    hideField();

    var qrsize = getCookie('qrsize');
    if (!qrsize) {
        setCookie('qrsize','200',9999);
    }
    qrsize = getCookie('qrsize');
    $(document).on('keyup','input#qrsize',function(){
        setCookie('qrsize',$(this).val(),9999);
    })

    $('#delivery_date').attr('readonly',false);
    $('textarea[name="qr_code"]').closest('.form-group').hide();
    $('input[name="supplier"]').closest('.form-group').hide();
    var img_qr = $('textarea[name="qr_code"]').val();
    
    var printQRSize = '<div class="input-group printHide" style="width:200px;">'+
            '<input type="number" class="form-control" aria-label="Size" id="qrsize" name="qrsize" value="'+qrsize+'">'+
            // '<span class="input-group-addon">px</span>'+
            '<span class="input-group-btn">'+
                '<button class="btn btn-default" onclick="printDiv(\'printableArea\')" type="button">Print QR Code</button>'+
            '</span>'+        
        '</div>';
    if(!img_qr && !$('table#table-detail').length){
        printQRSize = '';    
    }
    
    // var fsize = '<input type="number" style="width:50px;" id="qrsize" name="qrsize" value="'+qrsize+'" />px';
    var html_qr = '<div id="printableArea" class="qr_tag pull-right col-sm-2" style="position:absolute;right:30px;"><img src="'+img_qr+'" class="qr_code" style="width:200px;"/>'+printQRSize+'</div>';
    // var btn_print_qr = '<center><a class="btn btn-default" onclick="printDiv(\'printableArea\')">Print QR Code</a></center>';
    // var btn_print_qr = '<center>'+printQRSize+'</center>';
    // $('textarea[name="qr_code"]').parent().append(html_qr);
    $('#parent-form-area').before(html_qr);
    // $('div.qr_tag').append(btn_print_qr);
    if($('table#table-detail').length){
        $('table#table-detail tr:eq(0)').find('td:eq(1)').hide();
        $('table#table-detail tr:eq(1)').hide();
        $('table#table-detail tr:eq(5)').find('td:first').text(' ');

        var img_qr = $('table#table-detail tr:eq(0)').find('td:eq(1)').text();
        var html_qr = '<div id="printableArea" class="qr_tag pull-left col-sm-2" style="position:absolutes;right:15px;"><img src="'+img_qr+'" class="qr_code" style="width:200px;"/>'+printQRSize+'</div>';
        $('table#table-detail tr:eq(0)').find('td:eq(1)').show().html(html_qr);
        // $('table#table-detail tr:eq(0) div.qr_tag').append(btn_print_qr);
        $('div.qr_tag:eq(0) img').attr('src',img_qr);
        $('div.qr_tag:eq(0)').hide();
    }
    
    // $('#btn_add_new_data').hide();
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


    if ( $( "select#supplier").length ) {
        $('select#supplier').attr('id','my_supplier');
        var _url = '/admin/delivery_notes/supplier/1?e=cfcd208495d565ef66e7dff9f98764da';
        $('#my_supplier').select2({
            placeholder: {
                id: '-1',
                text: '** Please select a Supplier'
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
        if($('input[name="supplier"]').val()){
            $("#my_supplier").select2({disabled: true});
        }        

        var _val = $('input[name="supplier"]').val();
        var data = {
            id: _val,
            text: _val
        };    
        var newOption = new Option(data.text, data.id, false, false);
        $('#my_supplier').append(newOption).trigger('change');


        $('#my_supplier').on('change',function(){
            var $this = $(this);
            var supplier = $this.val();
            var delivery_date = $('input[name="delivery_date"]').val();
            $('input[name="supplier"]').val(supplier);
            // console.log(supplier+' '+delivery_date);
            getPO(supplier, delivery_date, false)

        })
        

    }

    $('select#purchase_order').on('change',function(){
        var $this = $(this);
        var delivery_date = $('input[name="delivery_date"]').val();
        var supplier = $('input[name="supplier"]').val();
        _po = $this.val();
        // console.log(_po);
        getItemByPO(supplier,delivery_date,_po)
    })

    if($('input[name="supplier"]').length && $('input[name="delivery_date"]').length){
        var supplier = $('input[name="supplier"]').val();
        var delivery_date = $('input[name="delivery_date"]').val();
        // console.log(supplier+' '+delivery_date);
       
        var auto_input = false;
        // if($('input[value="Save & Add More"]').length){
        //     auto_input = true;
        // }
        getPO(supplier, delivery_date, auto_input);

        var items = $('form').data('items');
        if(items){
            autoInputItem(items);
        }
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
            $('#panel-form-items').find('#itemspurchase_order').val(_po);
            $('#panel-form-items').find('#itemsitem_code').val(item_code);
            $('#panel-form-items').find('#itemsitem_name').val(item_name);
            $('#panel-form-items').find('#itemsqty').val(qty);
            $('#panel-form-items').find('#itemsuom').val(uom);
            $('#panel-form-items').find('#itemsrate').val(rate);
            $('#panel-form-items').find('#itemsamount').val(amount);
            $('#panel-form-items').find('#itemsbatch_no').val('-');
            $('#panel-form-items').find('#itemsserial_no').val('-');
            addToTableitems();
            setDBItems();
            hideField();
        } else {
            $('table#table-items')
                .find('tr#'+(_po+'__'+item_code).trim().replace(/[_\W]+/g, "-"))
                .remove();
        }
        
    })
});

function autoInputItem(items){
    $.each(items, function(k,v){
        var rate = parseInt(v.rate);
        var amount = rate * v.qty;
        $('#panel-form-items').find('#itemspurchase_order').val(v.purchase_order);
        $('#panel-form-items').find('#itemsitem_code').val(v.item_code);
        $('#panel-form-items').find('#itemsitem_name').val(v.item_name);
        $('#panel-form-items').find('#itemsqty').val(v.qty);
        $('#panel-form-items').find('#itemsuom').val(v.stock_uom);
        $('#panel-form-items').find('#itemsbatch_no').val('-');
        $('#panel-form-items').find('#itemsserial_no').val('-');
        addToTableitems();
    });
}
function getItemByPO(supplier,delivery_date,po){
    // var param = supplier+'__'+delivery_date+'__'+po;
    var _url = '/admin/purchase_orders/show/'+po;
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
                            '<th class="hidden">Rate</th>'+
                            '<th class="hidden">Amount</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody><tr><td colspan="7"><center>loading...</center></td></tr>'+
                    
                    '</tbody>'+
                '</table></div>';

    
    if(!$('#table-items-po').length){
        $('#form-group-purchase_order').append(_table);
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
                '<span class="td-label">'+v.qty+'</span>'+
            '</td>'+
            '<td class="uom">'+
                '<span class="td-label">'+v.stock_uom+'</span>'+
            '</td>'+
            '<td class="rate hidden">'+
                '<span class="td-label">'+_rate+'</span>'+
            '</td>'+
            '<td class="amount hidden">'+
                '<span class="td-label">'+_amount+'</span>'+
            '</td>'+
        '</tr>';
        });
        // console.log(_tr)
        $('body').find('#table-items-po').find('tbody').html(_tr);
    });
}


function getPO(supplier, delivery_date, _auto){
    var _url = '/admin/delivery_notes/porder/'+supplier+'__'+delivery_date+'?item=1';
    var data = [];

    // if($('select#purchase_order').find('option[value="0"]').length < 1){
        var d = {
            id: 0,
            text: '*** Select a Purchase Orders'
        };
        var newOption = new Option(d.text, d.id, false, false);
        $('select#purchase_order').html(newOption).trigger('change');
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
            $('select#purchase_order').append(newOption).trigger('change');            

        });
        
        
    }).done(function(){
        setDBItems();
    });
}


function setDBItems(){
    var i=0;
    $('table#table-items tbody tr').each(function(k,elm){
        var po = $(elm).find('td.purchase_order input').val();
        var item_code = $(elm).find('td.item_code input').val();
        var _id = (po+'__'+item_code).trim().replace(/[_\W]+/g, "-");
        db_items[i] = _id;

        $(elm).attr('id',_id);

        // console.log(elm);
        i++;
    });
    // console.log(db_items)
}

function hideField(){
    $(document).find('#itemsrate').closest('.form-group').hide();
    $(document).find('#itemsamount').closest('.form-group').hide();
    $('table#table-items').find('thead th:eq(7)').hide();
    $('table#table-items').find('thead th:eq(8)').hide();
    $('table#table-items').find('tbody td.rate').hide();
    $('table#table-items').find('tbody td.amount').hide();
}

function printDiv(divName) {
    var divToPrint=document.getElementById(divName);

    var newWin=window.open('','Print-Window');
    var cssPrint = '<style>a, .printHide{display:none;} img{width:'+getCookie('qrsize')+'px !important;}</style>';
    newWin.document.open();
  
    newWin.document.write('<html>'+cssPrint+'<body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  
    newWin.document.close();
  
    setTimeout(function(){newWin.close();},10);
}