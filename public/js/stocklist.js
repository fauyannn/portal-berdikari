$(document).ready(function(){
    $('#add_new').on('click', function(){
        let count = $('#stockListTableBody').find('tr').length;
        $('#stockListTableBody').append($('#formRow').html().replace(/__PLACEHOLDER__/g,count.toString()))
    });

    $('form').on('change', '.selectItem', function(){
        let name = '';
        let uom  = '';

        let item = items.find(item => item.name == $(this).val());
        if(item){
            name = item.item_name
            uom = item.stock_uom
        }
        $(this).closest('tr').find('.itemName').html(name);
        $(this).next().val(name);
    });
});