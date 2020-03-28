$(document).ready(function(){
   $('table#table_dashboard tbody tr').each(function(k,val){
        var $this = $(val);
        var target = $this.find('td:eq(4)')
        var status = target.text();
        status = labelStatus(status);
        target.html(status)
        // console.log(status);
   });
   
});



