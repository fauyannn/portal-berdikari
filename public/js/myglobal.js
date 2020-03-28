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
}

function labelStatus(status){
  var data = [];
  data['draft'] = '<span class="label label-danger">'+status+'</span>';
  data['submited'] = '<span class="label label-primary">'+status+'</span>';
  data['open'] = '<span class="label label-warning">'+status+'</span>';
  data['closed'] = '<span class="label label-success">'+status+'</span>';
  return data[status];
}

$.urlParam = function(name){
  var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
  if (results==null) {
     return null;
  }
  return decodeURI(results[1]) || 0;
}