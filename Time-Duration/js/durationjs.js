// JavaScript Document
jQuery(document).ready(function(e) {
	
	$j = jQuery.noConflict();
    
	
	
	jQuery.ajax({
			url: time_plugin.ajaxurl,
			type: "GET",
			dataType:"JSON",
			data:{
				action: 'time_plugin'
				
			},
			dataType:"html",
			success: function (response) {
				//alert(response);
				var objData = jQuery.parseJSON(response);
				
				var Cdate = { 	cyear: objData.phpYear,
								cmonth: objData.phpMonth,
								cdate: objData.phpDate    };
				
				$j('.Rtime #year').text(Cdate.cyear)
				$j('.Rtime #month').text(Cdate.cmonth);
				$j('.Rtime #day').text(Cdate.cdate);
				var flag = objData.flag;
				
				if(flag == 1){
					window.setInterval(function(){
					  displayExp()}, 
					  1000);
				}
			}
		});
		
	  
	  function displayExp(){
		  var chour = new Date().getHours();
		  var cminute = new Date().getMinutes();
		  var csecond = new Date().getSeconds();
		  
		  $j('.Rtime #hour').text(chour);
		  $j('.Rtime #min').text(cminute);
		  $j('.Rtime #sec').text(csecond);
	  }
	  
	  
	 
	
	
});