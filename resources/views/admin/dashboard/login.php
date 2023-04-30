<?php
/*
* This file handles the login functionality for Webserive.
*/

if(!empty($data)) {
	global $wpdb;
 $data = get_object_vars($data);
	if((isset($data['username']) && !empty($data['username'])) && (isset($data['password']) && !empty($data['password'])))
	{
		 $username = $data['username'];
		 $pass = $data['password'];
		 $ar = wp_authenticate_username_password("", $username, $pass);
		 
		 $class_name = get_class ($ar);
		 if ( $class_name == "WP_User" ) { 
		  $user_id = $ar->data->ID;
		  
		  $arr = array(
		   "userID" => $user_id,
		   "email"  => $ar->data->user_email,
		   "username"   => $ar->data->display_name,
		  );
			$coupleInfo							=	array();
			$user = get_userdata($user_id);
			if($user){
				$coupleInfo 					= 	array();
				$coupleInfo['name'] 			= 	$user->user_firstname.' & '.$user->registrant_2_first_name;
				
				$wedding_date 					= 	$user->wedding_date;
				$old_date_timestamp 			= 	strtotime($wedding_date);
				$coupleInfo['wedding_date'] 	= 	date('F j, Y', $old_date_timestamp);
				
				$coupleInfo['charity'] 						=	get_user_meta($user_id,"charity",true);
				$coupleInfo['your_first_name'] 				=	get_user_meta($user_id,"first_name",true);
				$coupleInfo['your_last_name'] 				=	get_user_meta($user_id,"last_name",true);
				$coupleInfo['partner_first_name'] 			=	get_user_meta($user_id,"registrant_2_first_name",true);
				$coupleInfo['partner_last_name'] 			=	get_user_meta($user_id,"registrant_2_last_name",true);
				$coupleInfo['address'] 						=	get_user_meta($user_id,"shipping_address_1",true);
				$coupleInfo['city'] 						=	get_user_meta($user_id,"shipping_city",true);
				$coupleInfo['zipcode'] 						=	get_user_meta($user_id,"shipping_postcode",true);
				$coupleInfo['state'] 						=	get_user_meta($user_id,"shipping_state",true);
				$coupleInfo['your_url']						=	site_url().'/registries/'.$user->user_login;
				
				$greeting_text 					= 	$user->greeting_text;
				$charity_choose 				= 	get_user_meta($user_id,"charity_choose",true);
				if(!$greeting_text){
					$greeting_text 				= 	'Your greeting will be here';
				}
				if(!$charity_choose){
					$charity_choose				= 	'';
				}
				$coupleInfo['charity_choose'] 	= 	$charity_choose;
				$coupleInfo['greeting_text'] 	=	$greeting_text;
				$profile_image 					= 	$user->photo_1_text;
				if($profile_image){
					$coupleInfo['couple_image'] = 	$user->photo_1_text;
				}else{
					$coupleInfo['couple_image'] = 	$user->photo_1;
				}
			}
			
			$response_data					=	array_merge($arr,$coupleInfo);
		  $ar = json_encode(array ("response" => "success", "message" => "User logged in!", "data" => $response_data));
		  echo base64_encode($ar);
		  exit();
		} else {
			$spassword 				= 	md5($data['password']);
			$semail	 				= 	$data['username'];
			
			$wp_users 				= 	$wpdb->prefix . 'users';
			$userInfo 				= 	$wpdb->get_row("SELECT * FROM $wp_users WHERE active = '1' AND second_email = '$semail' AND second_pass='$spassword'",ARRAY_A);
			if(!empty($userInfo)) {
					$user_id = $userInfo['ID'];
		  
					$coupleInfo							=	array();
					$user = get_userdata($user_id);
					$coupleInfo 					= 	array();
					$coupleInfo['name'] 			= 	$user->user_firstname.' & '.$user->registrant_2_first_name;
					$coupleInfo['userID'] 			= 	$user_id;
					$coupleInfo['email'] 			= 	$user->user_email;
					$coupleInfo['username'] 		= 	$user->user_login;
					
					$wedding_date 					= 	$user->wedding_date;
					$old_date_timestamp 			= 	strtotime($wedding_date);
					$coupleInfo['wedding_date'] 	= 	date('F j, Y', $old_date_timestamp);
					
					$coupleInfo['charity'] 						=	get_user_meta($user_id,"charity",true);
					$coupleInfo['your_first_name'] 					=	get_user_meta($user_id,"first_name",true);
					$coupleInfo['your_last_name'] 					=	get_user_meta($user_id,"last_name",true);
					$coupleInfo['partner_first_name'] 			=	get_user_meta($user_id,"registrant_2_first_name",true);
					$coupleInfo['partner_last_name'] 			=	get_user_meta($user_id,"registrant_2_last_name",true);
					$coupleInfo['address'] 						=	get_user_meta($user_id,"shipping_address_1",true);
					$coupleInfo['city'] 						=	get_user_meta($user_id,"shipping_city",true);
					$coupleInfo['zipcode'] 						=	get_user_meta($user_id,"shipping_postcode",true);
					$coupleInfo['state'] 						=	get_user_meta($user_id,"shipping_state",true);
					$coupleInfo['your_url']						=	site_url().'/registries/'.$user->user_login;
					
					$greeting_text 					= 	$user->greeting_text;
					$charity_choose 				= 	get_user_meta($user_id,"charity_choose",true);
					if(!$greeting_text){
						$greeting_text 				= 	'Your greeting will be here';
					}
					if(!$charity_choose){
						$charity_choose				= 	'';
					}
					$coupleInfo['charity_choose'] 	= 	$charity_choose;
					$coupleInfo['greeting_text'] 	=	$greeting_text;
					$profile_image 					= 	$user->photo_1_text;
					if($profile_image){
						$coupleInfo['couple_image'] = 	$user->photo_1_text;
					}else{
						$coupleInfo['couple_image'] = 	$user->photo_1;
					}
					
					$response_data					=	$coupleInfo;
					$ar = json_encode(array ("response" => "success", "message" => "User logged in!", "data" => $response_data));
					echo base64_encode($ar);
			}else {
				$ar = json_encode(array ("response" => "error", "message" => "Email or Password is Wrong"));
				echo base64_encode($ar);
				exit();
			}
		}
	}
	else 
	{
		$ar = json_encode(array ("response" => "error", "message" => "Require Fields Missing"));
		echo base64_encode($ar);
		exit();
	}
} 
else 
{
	$ar = json_encode(array ("response" => "error", "message" => "Require Field Missing"));
	echo base64_encode($ar);
	exit();
}