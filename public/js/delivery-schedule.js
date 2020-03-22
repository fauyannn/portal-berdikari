$(document).ready(function(){
    var data = $('table#temp tbody').html();
    $('table#table_dashboard tbody').html(data);
    
    var total_rows = $('#total_rows').text();
    $('.box-body').find('span.pull-right').text(total_rows);

    var loadmore = $('#loadmore').html();
    $('.box-body').find('form#form-table').append(loadmore);


    var _limit = $('a#url-loadmore').data('limit');
    var trows = _limit;
    var total_data = $('a#url-loadmore').data('totaldata');
    var start = 0;
    var $this;
    $('a#url-loadmore').on('click',function(){
        $this = $(this);
        start = parseInt(start+_limit);
        var _url = $this.data('href')+'&start='+start;
        console.log(_url)
        var datalist = '';
        console.log(trows +' -- '+total_data);
        if(trows >= total_data){
            return false;
        }
        $this.text('loading...');
        $.get(_url, function(res){
            // console.log(res.message);
            var modul_url = res.message.modul_url;
            total_data = res.message.total_data;
            $.each(res.message.data,function(k,v){
                var id = v.supplier+'__'+v.delivery_date;
                var _url_cdn = modul_url+'/../delivery_notes/add?supplier='+v.supplier+'&delivery_date='+v.delivery_date;
                var _url = modul_url+'/show/'+id;
                          
                // datalist += "<tr>"+
                //         "<td>"+v.type+"</td>"+
                //         "<td>"+(v.sales_order ? v.sales_order : v.purchase_order)+"</td>"+
                //         "<td>"+v.item_code+"</td>"+
                //         "<td>"+v.item_name+"</td>"+
                //         "<td class='pull-right'>"+formatMoney(v.qty,0,',','.')+"</td>"+
                //         "<td>"+v.delivery_date+"</td>"+
                //         "<td><a class='btn btn-xs btn-primary btn-detail' title='Detail Data' href='"+_url+"'><i class='fa fa-eye'></i></a></td>"+
                //         "</tr>";
                datalist += "<tr>"+
                        "<td>"+v.supplier+"</td>"+
                        "<td>"+v.delivery_date+"</td>"+
                        "<td><a class='btn btn-xs btn-success btn-detail' title='Detail Data' href='"+_url_cdn+"'><i class='fa fa-xxx'></i> Create Delivery Note</a> "+
                        "<a class='btn btn-xs btn-primary btn-detail' title='Detail Data' href='"+_url+"'><i class='fa fa-eye'></i></a></td>"+
                        "</tr>";
            })
            $('table#table_dashboard tbody').append(datalist);
            // console.log(datalist);
            trows +=  parseInt(res.message.data.length);
            var total_rows = 'Total rows : '+trows+' of '+total_data;
            $('.box-body').find('span.pull-right').text(total_rows);
            $this.text('load more');
            if(trows>=total_data){
                $('a#url-loadmore').hide();
            }
        })
    })
});