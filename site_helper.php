<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('ta_session'))
{
    function ta_session()
    {
        if (!isset($_SESSION['telephent_user'])){
            redirect(base_url()."login/");
        }
    }
}
if(!function_exists('ta_logout'))
{
    function ta_logout()
    {
        unset($_SESSION['telephent_user']);
        ta_session();
    }
}

if (!function_exists('base_path')){
	function base_path(){
		if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	}
}
if (!function_exists('rootURL')){
	function rootURL($path=NULL){

		if ($path!=NULL){
			echo base_url($path);
		}else{
			echo base_url();
		}
	}
}


if (!function_exists('redirectAdmin')){
    function redirectAdmin($url){
        redirect(base_url()."admin/".$url);
    }
}
if (!function_exists('redirectFront')){
    function redirectFront($url){
        redirect(base_url().$url."/");
    }
}

if (!function_exists('checkSession')){
	function checkSession($redirect){
		if (isset($_SESSION['telephent_user'])){
			if ($redirect){
				redirect(base_url('admin/dashboard/'));
			}else{
				return TRUE;
			}
		}else{
			if ($redirect){
				redirect(base_url('login'));
			}else{
				return FALSE;
			}
		}
	}
}

if (!function_exists('checkRobot')){
	function checkRobot($token){
		if ($token == API_TOKEN_KEY){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}


if (!function_exists('seo')){
	function seo($title, $description, $h1 = NULL){
		return array(
			'title' => $title,
			'description' => $description,
			'h1' => $h1
		);
	}
}

if (!function_exists('create_url_slug')){
	function create_url_slug($text)
	{
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, '-');

		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}
		return $text;
	}
}

if (!function_exists('get_client_ip')){
	// Function to get the client IP address
	function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
}

if (!function_exists('whatsapp_chat_url')){
	function whatsapp_chat_url($message="Hello"){
		return "https://api.whatsapp.com/send?phone=966581310230&text={$message}";
	}
}




?>
