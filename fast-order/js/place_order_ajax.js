// JavaScript Document

var $j = jQuery.noConflict();
$j(document).ready(function(e) {

	
	/*$j('.single_add_to_cart_button').css('display', 'none');*/
	$j('#place-order-form #place-order-button').on('click touchstart tap',function(e){
		
		e.preventDefault();
		$j('#wrapper_place_order_form #loading').show();
		
		
		
		var form_data = $j('#wrapper_place_order_form #place-order-form').serialize();
		var qty = $j('input.qty').val();
			
		
		form_data += '&qty='+qty;
		
		
		jQuery.ajax({
			url: fast_order_plugin.ajaxurl,
			type: "POST",
			dataType:"JSON",
			data:{
				action: 'fast_order_plugin', formdata: form_data
				
			},
			dataType:"html",
			success: function (response) {
				alert(response);
				var objData = jQuery.parseJSON(response);
				
				var msg = objData.messages;
				var flag = objData.flag;
					
					if(flag == 1){
						
						$j('#wrapper_place_order_form #message').html(msg);
						$j('#wrapper_place_order_form #loading').hide();
						//$('#wrapper_place_order_form').find("input").val("");
						//alert('thank you for your order');
						// window.location = "http://trendsetters.pk/thank-you/";
						// $j(location).attr('href',"http://trendsetters.pk/thank-you/");  
					}
					
					if(flag == 0){
						$j('#wrapper_place_order_form #message').html(msg);
						$j('#wrapper_place_order_form #loading').hide();
						  
					}
						
						
						
			}
		});
		
		
		
		
		
		
		
			
		})
	
	
	
});




