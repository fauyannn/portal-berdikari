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
            console.log(res.message);
            var modul_url = res.message.modul_url;
            total_data = res.message.total_data;
            $.each(res.message.data,function(k,v){
                var id = v.name;
                var _url = modul_url+'/show/'+id;
                          
                datalist += "<tr>"+
                        "<td>"+v.name+"</td>"+
                        "<td>"+v.transaction_date+"</td>"+
                        "<td class='right'>Rp "+formatMoney(v.grand_total,0,',','.')+"</td>"+
                        "<td><a class='btn btn-xs btn-primary btn-detail' title='Detail Data' href='"+_url+"'><i class='fa fa-eye'></i></a></td>"+
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
})

function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
      decimalCount = Math.abs(decimalCount);
      decimalCount = isNaN(decimalCount) ? 2 : decimalCount;
  
      const negativeSign = amount < 0 ? "-" : "";
  
      let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
      let j = (i.length > 3) ? i.length % 3 : 0;
  
      return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
      console.log(e)
    }
  };