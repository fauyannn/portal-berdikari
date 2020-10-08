$(document).ready(function(){
    $('.add_new').on('click', function(){
        count++;
        let placeholder = $(this).closest('.panelRoot').find('.formRow').html().replace(/__PLACEHOLDER__/g,count);
        $body = $(this).prev().find('.stockListTableBody')
        $body.append(placeholder);
        $body.find('select').addClass('selectItem').select2({
            'width': '100%',
            'allowClear' : true,
            'placeholder' : 'Select Item'
          }
        );
    });

    $('.selectItem').select2({
        'width': '100%',
        'allowClear' : true,
        'placeholder' : 'Select Item'
      }
    );

    $('form').on('change', '.selectItem', function(){
        let name = '';
        let uom  = '';

        let item = items.find(item => item.name == $(this).val());
        if(item){
            name = item.item_name
            uom = item.stock_uom
        }
        $(this).closest('tr').find('.itemName').html(name);
        $(this).parent().find('.hiddenName').val(name);
    });

    $('#scanQr').on('click', function () {
        let url = '/admin/stocklist/processqr';
        let companyName = $('#company').val();
        if(companyName) {
            url += '/company/'+companyName;
        }
        let qrReader = new QrReader('#qrScanModal',url, 'Stock Entry');
        qrReader.initQr();
    })
});