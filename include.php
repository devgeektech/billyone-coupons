<?php add_action('wp_ajax_genratecoupoun', 'genrated_rand_codes');
add_action('wp_ajax_nopriv_genratecoupoun', 'genrated_rand_codes');

function genrated_rand_codes(){

	$coupon = array();
	
	$coupon_count = (int)$_POST['coupon_count'];	
	$expiry_date = '';
	
	if(isset($_POST['add_expiry_date']) && trim($_POST['add_expiry_date']) !=""){

		$expiry_date = date('Y-m-d', strtotime(trim($_POST['add_expiry_date'])));

	}



	if(!empty($coupon_count)){
		for ($i = 1; $i <= $coupon_count; $i++) { 
			$coupon[$i] = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 4); 
			global $wpdb;     
            $date = date('Y-m-d H:i:s');     
			$query=$wpdb->insert('wp_easy_couppons', array(
			  
			  'coupons' => $coupon[$i],
			  'expire_date' =>$expiry_date
		  ));  
		  
		  $wpdb->query($query);  
			
			$coupon['status'] = true;
		}
		
	} 
	
	print(json_encode($coupon));
  	die;
}