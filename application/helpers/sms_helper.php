<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('send_sms'))
{
	function send_sms($sms_setting, $mobile_no, $message)
	{	
		if($sms_setting != null)
		{
			$api_url = $sms_setting->api_url;
			$sender = $sms_setting->sender;
			$route = $sms_setting->route;
			$auth_key = $sms_setting->auth_key;
			$country = $sms_setting->country;
			$unicode = $sms_setting->unicode;

			if(($api_url != "") && ($sender != "") && ($route != "") && ($auth_key != "") && ($country != "") && ($unicode != "")){

				$url = $api_url."?sender=".$sender."&route=".$route."&mobiles=".$mobile_no."&authkey=".$auth_key."&country=".$country."&unicode=".$unicode."&message=".$message;

				$curl  =  curl_init();

				curl_setopt_array($curl, array(
					  CURLOPT_URL => $url,
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 30,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "GET",
					  CURLOPT_SSL_VERIFYHOST => 0,
					  CURLOPT_SSL_VERIFYPEER => 0,
					));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				  echo $err;
				} else {
				  return $response;
				}
			}
		}
	}
}
