<?php
/**
 * Plugin Name: WooCommerce Fast-order
 * Plugin URI: 
 * Description:  this plugin enables us to place orders instantly from product pages. require woocommerce to work
 * Version: 1.2
 * Author: Shoaib 
 * Author URI: 
 * License: 
 * Copyright 2015
 */
 
function enqueue_scripts()
{
	/*wp_enqueue_script(
		'jquery-ui-min',
		'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
		'',
		'',
		false
	);
		
	wp_enqueue_style(
		 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css' ,
		 '',
		 '',
		 false);
			
	wp_enqueue_script(
		'jquery-min',
		'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js',
		'',
		'',
		false
	);
  
	*/
	
	
	?>
	
	<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
     <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script> -->
    <?php 
    

}


/*add_action( 'admin_enqueue_scripts', 'enqueue_scripts' );*/






add_action('wp_enqueue_scripts', 'add_fast_order_plugin_scripts');

function add_fast_order_plugin_scripts(){
    if(!is_admin()) {
        wp_enqueue_script('fast_order_plugin', plugins_url( '/js/place_order_ajax.js' , __FILE__ ), array('jquery'), 1.0, true);
        wp_localize_script("fast_order_plugin","fast_order_plugin",array('ajaxurl' => admin_url('admin-ajax.php')));
    }
	wp_enqueue_style( 'fast-order-styles', plugins_url('/css/fast-order-styles.css', __FILE__ ) );
}



// this action will take care of logged in users
add_action('wp_ajax_fast_order_plugin', 'fast_order_plugin_cb');
// this action will take care of non logged in users
add_action('wp_ajax_nopriv_fast_order_plugin', 'fast_order_plugin_cb');


function front_place_order_form(){
		
		global $post;	
		global $woocommerce;
		global $product;
		global $wp_query;
		global $checkout;
		global $order_id;
		
	?>
    <div id="wrapper_place_order_form">
     <h3>FAST ORDER</h3>
     
	 <form id="place-order-form" method="post" enctype="multipart/form-data">
            <label>Cash on delivery</label>
            <input type="text" name="fullname" id="fullname" placeholder="Full Name" required="required" />
            <input type="text" name="address" id="address" placeholder="Address" required="required" />
            <input type="tel" name="telephone" id="telephone" placeholder="Phone" required="required" />
            <input type="email" name="email" id="email" placeholder="Email" required="required" />
            <input type="hidden" name="pid" id="pid" value="<?php echo $post->ID ?>" />
            <button id="place-order-button">Place Order</button><img id="loading" src="http://poshaak.pk/wp-content/uploads/2015/08/ajax-loader.gif" style="display:none" />
        </form>
     
     <div id="message"></div>
    </div> 
	<?php
	
	}


add_shortcode('place-order-form','front_place_order_form');

// in our case the data returned is the same for logged in or not logged in users. This is our ajax call handler
function fast_order_plugin_cb(){
		
		
		global $post;
		global $woocommerce;
		global $product;
		global $wp_query;

		parse_str($_POST['formdata'], $formdata );
		$fullname = $formdata['fullname'];
		$address = $formdata['address'];
		$phone = $formdata['telephone'];
		$email = $formdata['email'];
		$pid = $formdata['pid'];
		$qty = $formdata['qty'];
		$flag = 0;
		$report = '<br/>';
		
		
		if( $is_alpha_space = ctype_alpha(str_replace(' ', '', $fullname))
				&& $address != NULL
				 && is_numeric($phone)
				  && $pid != NULL 
				   && !$qty<=0
				   	&& filter_var($email, FILTER_VALIDATE_EMAIL)  ){
			
			$flag = 1;
			
			}
		else{
			
			if(!$is_alpha_space = ctype_alpha(str_replace(' ', '', $fullname))){
				$report .= "Full Name field has an error: " . $fullname ."<br/>";}
			if(!$address != NULL){
				$report .= "Address field has an error: ". $address ."<br/>";}
			if(!is_numeric($phone)){
				$report .= "Phone field has an error: ". $phone ."<br/>";}
			if(!$pid != NULL){
				$report .= "Product id is not correct: ". $pid ."<br/>";}
			if($qty<=0){
				$report .= "Quantity field has an error: ". $qty ."<br/>";}
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$report .= "Email field has an error: ". $email ."<br/>";}
			
			
			
			
			$flag = 0;
			
			
			}
		
		
		if($flag == 1){
		
		
		
		$address = array(
            'first_name' => $fullname,
            'email'      => $email,
            'phone'      => '+92-' . $phone . '',
            'address_1'  => $address,
            'country'    => 'PK'
        );

        $order = wc_create_order();
        $order->add_product( get_product( $pid  ), $qty ); //(get_product with id and next is for quantity)
        $order->set_address( $address, 'billing' );
        $order->set_address( $address, 'shipping' );
        $order->calculate_totals();
		
		
		$messages = "Thank you " . $fullname . " <br/>Your order has been placed. <br/>You will receive a call from our representative shortly";
		
		
		
		}
		else	
		{
		$messages = "There is an error in the form <br/> Please correct it" . $report;
		
			
			}
		
		
		
		$return_data = array(
			
			'flag' => $flag,
			'messages' => $messages
			
		);
		
		
		echo json_encode($return_data);
		
		
		
		
		
    //echo "Yes! the ajax call was successful"; // this message will be displayed in our jacascript alert
    die(0);
}





 
 
?>