$(document).ready(function(){
  $('#selectBom').on('change', function () {
    let value = $(this).val();
    if(value !== ''){
      $.get('/admin/stocklist/bom_detail/'+value, function (data) {
        showMaterials(data)
      })
    }

  });

  $('#processButton').on('click', function() {
    $.post('/admin/stocklist/submit_process', $('form').serialize(), function(result) {
      if(result == 'invalid'){
        alert('Validation error, please check the amount')
      } else {
        alert('Process is done')
        window.location.href= "/admin/stocklist"
      }
    })
  })
});

function showMaterials(data) {
  let body = $('tbody');
  body.empty();
  for (let material of data){
    let row = $('<tr></tr>');
    let name = `<td> ${material.item_name}</td>`;
    row.append(name);

    let batchColumn = $('<td></td>');
    row.append(batchColumn);

    let select = $(`<select name="material[${material.item_code}]"><option data-amount=""></option></select>`);
    batchColumn.append(select)
    for (let batch of material.batches){
      let option = `<option value="${batch.id}" data-amount="${batch.amount}">${batch.batch_no}</option>`;
      select.append(option);
    }


    row.append('<td class="amount"></td>');
    select.on('change', function () {
      let amount = $(this).find(':selected').data('amount');
      $(this).parent().parent().find('.amount').html(amount);
    });



    let materialColumn = $(`<td>${material.qty}</td>`);
    row.append(materialColumn);

    let uomColumn = $(`<td>${material.uom}</td>`);
    row.append(uomColumn);
    body.append(row);
  }
}