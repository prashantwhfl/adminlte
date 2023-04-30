<?php
namespace App\Http\Controllers;

use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,Str,App,DateTime,Route,PDF;
use Stripe;
use Twilio\Rest\Client;

/**
* Base Controller
*
* Add your methods in the class below
*
* This is the base controller called everytime on every request
*/
class BaseController extends Controller {
	
	protected $user;
	
	public function __construct() {

		$this->session_data 	= 	"";

		/* $this->middleware(function ($request, $next){
			$this->session_data		= 		Session::get('default_restaurant');
		
			return $next($request);
		}); */
	}
	// end function __construct()
	
		public  function arrayStripTags($array){
		$result =array();
		foreach ($array as $key => $value) {
			// Don't allow tags on key either, maybe useful for dynamic forms.
			$key = strip_tags($key,ALLOWED_TAGS_XSS);
			// If the value is an array, we will just recurse back into the
			// function to keep stripping the tags out of the array,
			// otherwise we will set the stripped value.
			if (is_array($value)) {
				$result[$key] = $this->arrayStripTags($value);
			} else {
				// I am using strip_tags(), you may use htmlentities(),
				// also I am doing trim() here, you may remove it, if you wish.
				$result[$key] = trim(strip_tags($value,ALLOWED_TAGS_XSS));
			}
		}
		return $result;
	}

	
}// end BaseController class
