<?php namespace psm\utils;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class csrf {
protected function __construct() {}
// CSRF - Prevent cross-site request forgery
//
// modified and classified by lorenzop of PoiXson
// originally from http://halls-of-valhalla.org
//
// To use:
//   1. Include this file
//   2. Add getTokenURL() to URLs with GET parameters
//   3. Add getTokenForm() to forms
//   4. When validating GET and POST data, execute ValidateToken().



const SESSION_KEY = 'csrf_token';



// get token
public static function getToken() {
	if(!self::isEnabled()) return '';
	\psm\utils\vars::session_init();
	if(!isset($_SESSION[self::SESSION_KEY]) || empty($_SESSION[self::SESSION_KEY]))
		$_SESSION[self::SESSION_KEY] = self::GenerateToken();
	return $_SESSION[self::SESSION_KEY];
}
// generate new token
protected static function GenerateToken() {
	if(!self::isEnabled()) return '';
	return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand(0, 0xffff),
		mt_rand(0, 0xffff),
		mt_rand(0, 0xffff),
		mt_rand(0, 0x0fff) | 0x4000,
		mt_rand(0, 0x3fff) | 0x8000,
		mt_rand(0, 0xffff),
		mt_rand(0, 0xffff),
		mt_rand(0, 0xffff)
	);
}



public static function isEnabled() {
//TODO:
//	return \psm\config()->csrf();
//	return \SettingsClass::getBoolean('CSRF Protection');
	return TRUE;
}

// token for url
public static function getTokenURL() {
	return '&amp;'.self::SESSION_KEY.'='.self::getToken();
}
// token for form
public static function getTokenForm() {
	return '<input type="hidden" name="'.self::SESSION_KEY.'" value="'.self::getToken().'" />';
}



// validate token
public static function ValidateToken() {
	if(!self::isEnabled()) return;
	if(!self::isValidToken()) {
		echo 'Invalid CSRF Token!<br /><a href="./">Back to WebAuctionPlus website</a>';
		\psm\utils::ForwardTo('./', 2);
		exit;
	}
}
protected static function isValidToken() {
	if(!self::isEnabled()) return TRUE;
	$url_token = '';
	if(isset($_POST[self::SESSION_KEY])) $url_token = $_POST[self::SESSION_KEY];
	else
	if(isset( $_GET[self::SESSION_KEY])) $url_token =  $_GET[self::SESSION_KEY];
	if(empty($url_token)) return FALSE;
	return (self::getToken() === $url_token);
}



}
?>