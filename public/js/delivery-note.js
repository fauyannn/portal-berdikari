
var _po = '';
var db_items = [];
$(document).ready(function(){
    $('table#table-detail tr:eq(2)').remove();
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
        // var rate        = $this.closest('tr').find('td.rate').text();
        // var amount      = $this.closest('tr').find('td.amount').text();
        // console.log(_po)
        if($this.is(':checked')) {
            $('#panel-form-items').find('#itemspurchase_order').val(_po);
            $('#panel-form-items').find('#itemsitem_code').val(item_code);
            $('#panel-form-items').find('#itemsitem_name').val(item_name);
            $('#panel-form-items').find('#itemsqty').val(qty);
            $('#panel-form-items').find('#itemsuom').val(uom);
            // $('#panel-form-items').find('#itemsrate').val(rate);
            // $('#panel-form-items').find('#itemsamount').val(amount);
            addToTableitems();
            setDBItems();
        } else {
            $('table#table-items')
                .find('tr#'+(_po+'__'+item_code).trim().replace(/[_\W]+/g, "-"))
                .remove();
        }
        
    })
});

function autoInputItem(items){
    $.each(items, function(k,v){
        var rate = parseInt(v.last_purchase_rate);
        var amount = rate * v.qty;
        $('#panel-form-items').find('#itemspurchase_order').val(v.purchase_order);
        $('#panel-form-items').find('#itemsitem_code').val(v.item_code);
        $('#panel-form-items').find('#itemsitem_name').val(v.item_name);
        $('#panel-form-items').find('#itemsqty').val(v.qty);
        $('#panel-form-items').find('#itemsuom').val(v.stock_uom);
        // $('#panel-form-items').find('#itemsrate').val(rate);
        // $('#panel-form-items').find('#itemsamount').val(amount);
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
    
    var _table = '<div class="col-sm-2"></div><div class="col-sm-9"><table id="table-items-po" class="table table-striped table-bordered">'+
                    '<thead>'+
                        '<tr>'+
                            '<th></th>'+
                            '<th>Item Code</th>'+
                            '<th>Item Name</th>'+
                            '<th>QTY</th>'+
                            '<th>UOM</th>'+
                            // '<th>Rate</th>'+
                            // '<th>Amount</th>'+
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
            var _checked = ($.inArray(_po+'__'+v.item_code, db_items) != -1) ? 'checked' : '';
            var _rate = parseInt(v.last_purchase_rate);
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
            // '<td class="rate">'+
            //     '<span class="td-label">'+_rate+'</span>'+
            // '</td>'+
            // '<td class="amount">'+
            //     '<span class="td-label">'+_amount+'</span>'+
            // '</td>'+
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