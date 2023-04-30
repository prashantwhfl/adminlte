<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use Illuminate\Http\Request;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Mail,mongoDate,Redirect,Response,Session,URL,View,Validator;
use Illuminate\Database\Eloquent\Model;
/**
* AdminLogin Controller
*
* Add your methods in the class below
*
* This file will render views\admin\login
*/
class AdminLoginController extends BaseController {
		
	public function __construct(Request $request){
		$this->request = $request;
	}
		
	/**
	* Function for display admin  login page
	*
	* @param null
	*
	* @return view page. 
	*/
	public function login(Request $request){
		
		if(!empty(Auth::guard('admin')->user())){
			return Redirect::route('dashboard');
		}
		if($request->isMethod('post')){
			$this->request->replace($this->arrayStripTags($request->all()));
			$formData	=	$request->all();
			if(!empty($formData)){
				$validator = Validator::make(
					$request->all(),
					array(
						'password'				=> 'required',
						'email' 			=> 'required|email',
					)
				);
				if ($validator->fails()){
					 return Redirect::back()->withErrors($validator)->withInput();
				}else{
					$userdata = array(
						'email' 		=> $request->input('email'),
						'password' 		=> $request->input('password'),
					);
					
					if (auth()->guard('admin')->attempt($userdata)){
						if(auth()->guard('admin')->user()->user_role == SUPER_ADMIN_ROLE_ID){
							
							Session::flash('flash_notice', 'You are now logged in!');
							return redirect()->to('adminpnlx/dashboard')->with('message','You are now logged in!');
						}else{
						
							auth()->guard('admin')->logout();
							Session::flash('error', 'Email or Password is incorrect.');
							return Redirect::back()->withInput();
						} 
					}else{
						Session::flash('error', 'Email or Password is incorrect.');
						return Redirect::back() ->withInput();
					}
				}
			}
		}else{
			return View::make('admin.login.index');
		}
   }// end index()

	/**
	* Function is used to display forget password page
	*
	* @param null
	*
	* @return view page. 
	*/	
	public function forgetPassword(Request $request){
		if(!empty(Auth::guard('admin')->user())){
			return Redirect::route('dashboard');
		}
		return View::make('admin.login.forget_password');
	}// end forgetPassword()

	/**
	* Function is used to send email for forgot password process
	*
	* @param null
	*
	* @return url. 
	*/		
	public function sendPassword(Request $request){
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData				=	$request->all();
		$messages = array(
			'email.required' 		=> trans('The email field is required.'),
			'email.email' 			=> trans('The email must be a valid email address.'),
		);
		$validator = Validator::make(
			$request->all(),
			array(
				'email' 			=> 'required|email',
			),$messages
		);
		if ($validator->fails()){		
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$email		=	$request->input('email');   
			$userDetail	=	Admin::where('email',$email)->where("user_role",SUPER_ADMIN_ROLE_ID)->first();
			if(!empty($userDetail)){
				if($userDetail->is_active == 1 ){
					$forgot_password_validate_string	= 	md5($userDetail->email.time().time());
					Admin::where('email',$email)->update(array('forgot_password_validate_string'=>$forgot_password_validate_string));
					
					$settingsEmail 		=  Config::get('Site.email');
					$email 				=  $userDetail->email;
					$username			=  $userDetail->username;
					$full_name			=  $userDetail->full_name;  
					$route_url      	=  URL::to('adminpnlx/reset_password/'.$forgot_password_validate_string);
					$varify_link   		=   $route_url;
					
					$emailActions		=	EmailAction::where('action','=','forgot_password')->get()->toArray();
					$emailTemplates		=	EmailTemplate::where('action','=','forgot_password')->get(array('name','subject','action','body'))->toArray();
					$cons = explode(',',$emailActions[0]['options']);
					$constants = array();
					
					foreach($cons as $key=>$val){
						$constants[] = '{'.$val.'}';
					}
					$subject 			=  $emailTemplates[0]['subject'];
					$rep_Array 			= array($email,$varify_link,$route_url); 
					$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
					
					$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
					Session::flash('flash_notice', trans('An email has been sent to your inbox. To reset your password please follow the steps mentioned in the email.')); 
					return Redirect::to('/adminpnlx');						
				}else{
					return Redirect::to('/adminpnlx/forget_password')->with('error', trans('Your account has been temporarily disabled. Please contact administrator to unlock.'));
				}	
			}else{
				return Redirect::to('/adminpnlx')->with('error', trans('Your email is not registered with '.config::get("Site.title")."."));
			}		
		}
	}// sendPassword()

	/**
	* Function is used for reset password
	*
	* @param $validate_string as validator string
	*
	* @return view page. 
	*/		
	public function resetPassword($validate_string=null,Request $request){
		$this->request->replace($this->arrayStripTags($request->all()));
		if($validate_string!="" && $validate_string!=null){
			$userDetail	=	Admin::where('is_active','1')->where("user_role",SUPER_ADMIN_ROLE_ID)->where('forgot_password_validate_string',$validate_string)->first();
			
			if(!empty($userDetail)){
				return View::make('admin.login.reset_password' ,compact('validate_string'));
			}else{
				return Redirect::to('/adminpnlx')
						->with('error', trans('Sorry, you are using wrong link.'));
			}
			
		}else{
			return Redirect::to('/adminpnlx')->with('error', trans('Sorry, you are using wrong link.'));
		}
	}// end resetPassword()
	
	/**
	* Function is used for save reset password
	*
	* @param $validate_string as validator string
	*
	* @return view page. 
	*/
	public function resetPasswordSave($validate_string=null,Request $request){
		$thisData				=	$request->all();; 
		$this->request->replace($this->arrayStripTags($thisData));
		$newPassword		=	$request->input('new_password');
		$validate_string	=	$request->input('validate_string');
		$messages = array(
			'new_password.required' 				=> trans('The new password field is required.'),
			'new_password_confirmation.required' 	=> trans('The confirm password field is required.'),
			'new_password.confirmed' 				=> trans('The confirm password must be match to new password.'),
			'new_password.min' 						=> trans('The password must be at least 8 characters.'),
			'new_password_confirmation.min' 		=> trans('The confirm password must be at least 8 characters.'),
			"new_password.custom_password"			=>	"Password must have combination of numeric, alphabet and special characters.",
		);
		
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
		$validator = Validator::make(
			$request->all(),
			array(
				'new_password'			=> 'required|min:8|custom_password',
				'new_password_confirmation' => 'required|same:new_password', 

			),$messages
		);
		if ($validator->fails()){	
			return Redirect::to('adminpnlx/reset_password/'.$validate_string)
				->withErrors($validator)->withInput();
		}else{
			$userInfo = Admin::where('forgot_password_validate_string',$validate_string)->first();
			Admin::where('forgot_password_validate_string',$validate_string)
				->update(array(
						'password'							=>	Hash::make($newPassword),
						'forgot_password_validate_string'	=>	''
				));
			$settingsEmail 		= Config::get('Site.email');			
			$action				= "reset_password";
			
			$emailActions		=	EmailAction::where('action','=','reset_password')->get()->toArray();
			$emailTemplates		=	EmailTemplate::where('action','=','reset_password')->get(array('name','subject','action','body'))->toArray();
			$cons 				= 	explode(',',$emailActions[0]['options']);
			$constants 			= 	array();
			foreach($cons as $key=>$val){
				$constants[] = '{'.$val.'}';
			}
			
			$subject 			=  $emailTemplates[0]['subject'];
			$rep_Array 			= array($userInfo->name); 
			$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
									 
			$this->sendMail($userInfo->email,$userInfo->full_name,$subject,$messageBody,$settingsEmail);
			Session::flash('flash_notice', trans('Thank you for resetting your password. Please login to access your account.')); 
			
			return Redirect::to('/adminpnlx');	
		}
	}// end resetPasswordSave()

	/**
	* Function for logout admin users
	*
	* @param null
	*
	* @return rerirect page. 
	*/ 
	public function logout(Request $request){
		
	   if(auth()->guard('admin')->user()->user_role == 'user_admin') {
		 $user = Admin::find(auth()->guard('admin')->user()->id);
	  	 $user->login_user_group_id = 0;
	  	 $user->first_login_popup = 	0;
	  	 $user->save();
	  	 Session::forget('group');
		}
	  	auth()->guard('admin')->logout();
		Session::flash('flash_notice', 'You are now logged out!');
		return Redirect::to('/adminpnlx')->with('message', 'You are now logged out!');
	}//endLogout()
}// end AdminLoginController