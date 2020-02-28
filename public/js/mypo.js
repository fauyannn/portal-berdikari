$(document).ready(function(){
    var data = $('table#temp tbody').html();
    $('table#table_dashboard tbody').html(data);
    
    var total_rows = $('#total_rows').text();
    $('.box-body').find('span.pull-right').text(total_rows);
})