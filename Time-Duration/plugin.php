<?php
/**
 * @package
 */
/*
Plugin Name: Duration
Plugin URI: 
Description: This plugin is basically created by me for my own time practice 
Version: 1.0.0
Author: Shoaib
Author URI: http://websdeveloper.base.pk/
License: Free
*/



// it is needed to enque my js file along with this php plugin
add_action('wp_enqueue_scripts', 'add_time_duration_plugin_scripts');

function add_time_duration_plugin_scripts(){
    if(!is_admin()) {
        wp_enqueue_script('time_plugin', plugins_url( '/js/durationjs.js' , __FILE__ ), array('jquery'), true);
        wp_localize_script("time_plugin","time_plugin",array('ajaxurl' => admin_url('admin-ajax.php')));
    }
	
	 wp_enqueue_style( 'style', plugins_url( '/css/style.css', __FILE__ ) );
	
	
	
	
}

// this shortcode will include my small form like relative time in to posts 
add_shortcode('relative-time','relative_time_php_function');

// the function that actually display my shortcode of relative time
function relative_time_php_function(){
	?>
    
    <div class="Rtime">
    <span id="top">experience of</span>
    <table>
    <tr id="top">
    	<td id="year"></td>
        <td id="month"></td>
        <td id="day"></td>
        <td id="hour"></td>
        <td id="min"></td>
        <td id="sec"></td>
    </tr>
    <tr id="bottom">
    	<td>years</td>
        <td>months</td>
        <td>days</td>
        <td>hours</td>
        <td>min</td>
        <td>sec</td>
    </tr>
    </table>
    <span id="bottom">and counting ...</span>
    </div>
    
	
	<?php
}




// this action will take care of logged in users
add_action('wp_ajax_time_plugin', 'time_plugin_cb');
// this action will take care of non logged in users
add_action('wp_ajax_nopriv_time_plugin', 'time_plugin_cb');

// in our case this is the function that will be called by our ajax
function time_plugin_cb(){
			
			$phpStartYear = 2014;
			$phpStartMonth = 6;
			$phpStartDate = 5;
			
			$phpThisYear = date('Y');
			$phpThisMonth = date('m');
			$phpThisDate = date('d');
			
			if($phpThisYear >= $phpStartYear){
				
				$phpYear = $phpThisYear - $phpStartYear;
				
				if($phpThisMonth > $phpStartMonth){
					$phpMonth = $phpThisMonth - $phpStartMonth;}
				if($phpThisMonth < $phpStartMonth){
					$phpMonth = (12 - $phpStartMonth) + $phpThisMonth;}
				if($phpThisMonth == $phpStartMonth){
					$phpMonth = 0;}
				
				
				if($phpThisDate > $phpStartDate){
					$phpDate = $phpThisDate - $phpStartDate;}
				if($phpThisDate < $phpStartDate){
					$phpDate = (31 - $phpStartDate) + $phpThisDate;}
				if($phpThisDate == $phpStartDate){
					$phpDate = 0;}
				
			
			
				$return_data = array(
					'flag' => 1,
					'phpYear' => $phpYear,
					'phpMonth' => $phpMonth,
					'phpDate' => $phpDate
				
				);
				
			}
		else{	$return_data = array('flag' => 0,);	}
		
		echo json_encode($return_data);
		
    //echo "Yes! the ajax call was successful"; // this message will be displayed in our jacascript alert
    die(0);

}






?>