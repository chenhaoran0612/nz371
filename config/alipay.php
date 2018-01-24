<?php

/**
 * alipay Config
 */

return [
	'data' => array(
		'partner' => '2088821715221038', 
		'key' => 'hxonc22a8os90vgvjfk7uic7o7x5w9jp' , 
		'notify_url' => 'http://www.kirin2.com/alipay/notify_url', 
		'return_url' => 'http://www.kirin2.com/alipay/state' ,
		'sign_type' =>  strtoupper('MD5'), 
		'input_charset' => strtolower('utf-8') ,
		'cacert' => getcwd().'\\cacert.pem',
		'transport' => 'http' ,
		'service' => 'create_forex_trade' ,
		// 'product_code' => 'NEW_OVERSEAS_SELLER',
	)
];
