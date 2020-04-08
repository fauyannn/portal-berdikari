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
        let qrReader = new QrReader('#qrScanModal');
        qrReader.initQr().then( qr => {
            try {
                json = JSON.parse(qr)
            }
            catch {
                alert("QR not valid.");
            }
            if(json.doctype != 'Stock Entry') {
                alert("QR not valid.");
            }
            $.post('/admin/stocklist/processqr', json, function (result) {
                if(result == 'success') {
                    alert('Scan QR success')
                    window.location.href = window.location.href
                } else {
                    alert('There is an error in the scan process')
                }
            })
        })
    })
});