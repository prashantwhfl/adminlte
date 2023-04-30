<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Model\Admin;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use App\Model\SubAdminRestaurant;
use Illuminate\Http\Request;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator,App;

/**
* CustomersController Controller
*
* Add your methods in the class below
*
*/
class AdminUsersController extends BaseController {

	public $model		=	'Admin';
	public $sectionName	=	'Users';
	public $sectionNameSingular	=	'User';
	
	public function __construct(Request $request) {
		parent::__construct();
		View::share('modelName',$this->model);
		View::share('sectionName',$this->sectionName);
		View::share('sectionNameSingular',$this->sectionNameSingular);
		$this->request = $request;
	}
	 
	/**
	* Function for display all Users 
	*
	* @param null
	*
	* @return view page. 
	*/
	public function index(Request $request){  
		$DB1				=	Admin::whereNotIn('user_role',[SUPER_ADMIN_ROLE_ID,USER_ADMIN_ROLE_ID,USER_RESTAURENT_ROLE_ID,KRUNCH_EMPLOYEE_ID])->leftJoin('user_roles','admins.user_role','=','user_roles.role_value')->select('admins.*','user_roles.role_name')
								->where("admins.is_deleted",0)->where('user_roles.is_active',1)->where('user_roles.is_deleted',0);
					
		$searchVariable		=	array(); 
		$inputGet			=	$request->all();
		if ($request->all()) {
			$searchData			=	$request->all();
			unset($searchData['display']);
			unset($searchData['_token']);

			if(isset($searchData['order'])){
				unset($searchData['order']);
			}
			if(isset($searchData['sortBy'])){
				unset($searchData['sortBy']);
			}
			if(isset($searchData['page'])){
				unset($searchData['page']);
			}
			if((!empty($searchData['date_from'])) && (!empty($searchData['date_to']))){
				$dateS = $searchData['date_from'];
				$dateE = $searchData['date_to'];
				$DB1->whereBetween('admins.created_at', [$dateS." 00:00:00", $dateE." 23:59:59"]);
			}elseif(!empty($searchData['date_from'])){
				$dateS = $searchData['date_from'];
				$DB1->where('admins.created_at','>=' ,[$dateS." 00:00:00"]); 
			}elseif(!empty($searchData['date_to'])){
				$dateE = $searchData['date_to'];
				$DB1->whereDate('admins.created_at','<=' ,$dateE);
			}
			foreach($searchData as $fieldName => $fieldValue){
				if($fieldValue != ""){
					//check user role
					if($fieldName == "name"){
						$DB1->where("admins.name",'like','%'.$fieldValue.'%');
					}
					if($fieldName == "phone_number"){
						$DB1->where("admins.phone_number",'like','%'.$fieldValue.'%');
					}
					if($fieldName == "email"){
						$DB1->where("admins.email",'like','%'.$fieldValue.'%');
					}
					if($fieldName == "restaurant_unique_id"){
						$DB1->where($fieldName,$fieldValue);
					}
					if($fieldName == "is_active"){
						$DB1->where("admins.is_active",$fieldValue);
					}
					if($fieldName == "user_role"){
						$DB1->where("admins.user_role",$fieldValue);
					}
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$sortBy 	= 	($request->input('sortBy')) ? $request->input('sortBy') : 'admins.id';
	    $order  	= 	($request->input('order')) ? $request->input('order')   : 'DESC';
		$records_per_page	=	($request->input('per_page')) ? $request->input('per_page') : Config::get("Reading.records_per_page");
		$results = $DB1->orderBy($sortBy, $order)->paginate($records_per_page);
		$complete_string		=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends($inputGet)->render();
		$restaurantDetails		=	DB::table("admins")
									->where("is_active",1)
									->where("is_deleted",0)
									->where("user_role",USER_RESTAURENT_ROLE_ID)
									->pluck("restaurant_name","restaurant_unique_id")
									->toArray();
		$userRoles              =   DB::table('user_roles')->where('is_deleted',0)->where('is_active',1)->pluck('role_name','role_value')->toArray();
		
		return  View::make("admin.$this->sectionName.index",compact('results','searchVariable','sortBy','order','query_string','restaurantDetails','userRoles'));
	}// end index()

	/**
	* Function for add new customer
	*
	* @param null
	*
	* @return view page. 
	*/
	public function add(){
		$userRoles              =   DB::table('user_roles')->where('is_deleted',0)->where('is_active',1)->pluck('role_name','role_value')->toArray();
		return  View::make("admin.$this->sectionName.add",compact('userRoles'));
	}// end add()
	
	/**
	* Function for save new customer
	*
	* @param null
	*
	* @return redirect page. 
	*/
	function save(Request $request){
		
		$this->request->replace($this->arrayStripTags($request->all()));
		$formData						=	$request->all();
		if(!empty($formData)){
			$validator 					=	Validator::make(
				$request->all(),
				array(
					'user_type'					=> 'required',
					'first_name'				=> 'required',
					'last_name'					=> 'required',
					'email' 					=> 'required|email|unique:admins,email',
					'phone_number' 				=> 'required|numeric|digits:10',
					'password'					=> 'required|min:8',
					'confirm_password'  		=> 'required|min:8|same:password',
				),
				array(
					"user_type.required"		=>	trans("The type field is required."),
					"first_name.required"		=>	trans("The first name field is required."),
					"last_name.required"		=>	trans("The last name field is required."),
					"email.required"			=>	trans("The email field is required."),
					"email.email"				=>	trans("The email must be a valid email address."),
					"email.unique"				=>	trans("The email has already been taken."),
					"phone_number.required"		=>	trans("The phone number field is required."),
					"phone_number.numeric"		=>	trans("The phone number must be numeric."),
					"phone_number.digits"		=>	trans("The phone number must be 10 digits."),
					"password.required"			=>	trans("The password field is required."),
					"password.min"				=>	trans("The password must be atleast 8 characters."),
					"confirm_password.required"	=>	trans("The confirm password field is required."),
					"confirm_password.same"		=>	trans("The confirm password not matched with password."),
					"confirm_password.min"		=>	trans("The confirm password must be atleast 8 characters."),
				)
			);
			if ($validator->fails()){
				return Redirect::back()->withErrors($validator)->withInput();
			}else{ 
				$password 					= 	$request->input('password');
				if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('#[\W]#', $password)) {
					$correctPassword		=	Hash::make($password);
				}else{
					$errors 				=	$validator->messages();
					$errors->add('password', trans("Password must have be a combination of numeric, alphabet and special characters."));
					return Redirect::back()->withErrors($errors)->withInput();
				}
				DB::beginTransaction();
				$validateString			=  	md5(time().$request->input('email'));
				$firstName				=	$request->input('first_name');
				$lastName				=	$request->input('last_name');
				$fullName				=	$firstName.' '.$lastName;
				$obj 					=  	new Admin;
				$obj->user_role			=	$request->input('user_type');
				$obj->restaurant_unique_id	=  	$this->getRestaurantUniqueId();
				$obj->name				=	$fullName;
				$obj->first_name		=	$firstName;
				$obj->last_name			=	$lastName;
				$obj->email				=	$request->input('email');
				$obj->phone_number		=	$request->input('phone_number');
				$obj->password			=	Hash::make($request->input('password'));
				$obj->validate_string	=	$validateString;
				$obj->is_verified		=	1;
				$obj->save();
				$userId					=	$obj->id;
				if(!$userId){
					DB::rollback();
					Session::flash('error', trans("Something went wrong.")); 
					return Redirect::back()->withInput();
				}

				/* GENERATE ACTIVITY LOGS SCRIPT START HERE */
				$log_type				= 	$obj->user_role.'_created';
				$performedOn			= 	$obj;
				$causedBy				= 	Auth::guard("admin")->user()->id;
				$customProperty			= 	$obj;
				$logMessage				= 	'New user has been created by {CAUSER_NAME}';
				$this->storeActivityLogs($log_type,$performedOn,$causedBy,$customProperty,$logMessage);
				/* GENERATE ACTIVITY LOGS SCRIPT END HERE */

				$lang					=	!empty(Config::get('Reading.language')) ? Config::get('Reading.language') : App::getLocale();
				//mail email and password to new registered user
				$settingsEmail 			=	Config::get('Site.email');
				$full_name				= 	$obj->name; 
				$email					= 	$obj->email;
				$route_url      		= 	route("user.Verify",$validateString);
				$emailActions			= 	EmailAction::where('action','=','account_verification')->get()->toArray();
				$emailTempl 			= 	new EmailTemplate();
				$emailTemplates 		= 	$emailTempl->get_template_data('account_verification',$lang); 		
				$cons 					= 	explode(',',$emailActions[0]['options']);
				$constants 				= 	array();
				foreach($cons as $key => $val){
					$constants[] 		= 	'{'.$val.'}';
				}
				$subject 				= 	$emailTemplates[0]->subject;
				$rep_Array 				= 	array($full_name,$route_url,$route_url); 
				$messageBody			= 	str_replace($constants, $rep_Array, $emailTemplates[0]->body);
				$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
				DB::commit();
				Session::flash('success',trans("User has been added successfully"));
				return Redirect::route($this->sectionName.".index");
			}
		}
	}//end save()
	
	/**
	* Function for update status
	*
	* @param $modelId as id of customer 
	* @param $status as status of customer 
	*
	* @return redirect page. 
	*/	
	public function changeStatus($modelId = 0, $status = 0){ 
		$model					=	Admin::findorFail($modelId);
		if(empty($model)) {
			return Redirect::back();
		}
		if($status == 0){
			$statusMessage	=	trans("User has been deactivated successfully");
		}else{
			$statusMessage	=	trans("User has been activated successfully");
		} 
		$this->_update_all_status('admins',$modelId,$status);

		/* GENERATE ACTIVITY LOGS SCRIPT START HERE */
		$log_type				= 	'status_changed';
		$performedOn			= 	$model;
		$causedBy				= 	Auth::guard("admin")->user()->id;
		$customProperty			= 	['old'=>$model,'attribute'=>Admin::findorFail($modelId)];
		$logMessage				= 	'{USER_NAME} status has been changed by {CAUSER_NAME}';
		$this->storeActivityLogs($log_type,$performedOn,$causedBy,$customProperty,$logMessage);
		/* GENERATE ACTIVITY LOGS SCRIPT END HERE */

		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	}// end changeStatus()
	
	/**
	* Function for display page for edit customer
	*
	* @param $modelId id  of customer
	*
	* @return view page. 
	*/
	public function edit($modelId = 0,Request $request){
		$model					=	Admin::findorFail($modelId);
		if(empty($model)) {
			return Redirect::back();
		}
		$userRoles              =   DB::table('user_roles')->where('is_deleted',0)->where('is_active',1)->pluck('role_name','role_value')->toArray();
		return  View::make("admin.$this->sectionName.edit",compact('model','userRoles'));
	} // end edit()
	
	
	/**
	* Function for update customer 
	*
	* @param $modelId as id of customer 
	*
	* @return redirect page. 
	*/
	function update($modelId,Request $request){
		$model					=	Admin::findorFail($modelId);
		if(empty($model)) {
			return Redirect::back();
		}
		$this->request->replace($this->arrayStripTags($request->all()));
		$formData						=	$request->all();
		if(!empty($formData)){
			$validator 					=	Validator::make(
				$request->all(),
				array(
					'user_type'					=> 'required',
					'first_name'				=> 'required',
					'last_name'					=> 'required',
					'email' 					=> 'required|email|unique:admins,email,'.$modelId,
					'phone_number' 				=> 'required|numeric|digits:10',
					'password'					=> 'nullable|min:8',
					'confirm_password'  		=> 'nullable|min:8|same:password',
				),
				array(
					"user_type.required"		=>	trans("The type field is required."),
					"first_name.required"		=>	trans("The first name field is required."),
					"last_name.required"		=>	trans("The last name field is required."),
					"email.required"			=>	trans("The email field is required."),
					"email.email"				=>	trans("The email must be a valid email address."),
					"email.unique"				=>	trans("The email has already been taken."),
					"phone_number.required"		=>	trans("The phone number field is required."),
					"phone_number.numeric"		=>	trans("The phone number must be numeric."),
					"phone_number.digits"		=>	trans("The phone number must be 10 digits."),
					"password.min"				=>	trans("The password must be atleast 8 characters."),
					"confirm_password.same"		=>	trans("The confirm password not matched with password."),
					"confirm_password.min"		=>	trans("The confirm password must be atleast 8 characters."),
				)
			);
			if ($validator->fails()){
				return Redirect::back()->withErrors($validator)->withInput();
			}else{ 
				if(!empty($request->input('password'))){
					$password 					= 	$request->input('password');
					if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('#[\W]#', $password)) {
						$correctPassword		=	Hash::make($password);
					}else{
						$errors 				=	$validator->messages();
						$errors->add('password', trans("Password must have be a combination of numeric, alphabet and special characters."));
						return Redirect::back()->withErrors($errors)->withInput();
					}
				}
				DB::beginTransaction();
				$firstName				=	$request->input('first_name');
				$lastName				=	$request->input('last_name');
				$fullName				=	$firstName.' '.$lastName;
				$obj 					=  	$model;
				$obj->user_role			=	$request->input('user_type');
				$obj->name				=	$fullName;
				$obj->first_name		=	$firstName;
				$obj->last_name			=	$lastName;
				$obj->email				=	$request->input('email');
				$obj->phone_number		=	$request->input('phone_number');
				if(!empty($request->input('password'))){
					$obj->password	 						=  Hash::make($request->input('password'));
				}
				$obj->save();
				$userId					=	$obj->id;
				if(!$userId){
					DB::rollback();
					Session::flash('error', trans("Something went wrong.")); 
					return Redirect::back()->withInput();
				}

				/* GENERATE ACTIVITY LOGS SCRIPT START HERE */
				$log_type				= 	'profile_updated';
				$performedOn			= 	$model;
				$causedBy				= 	Auth::guard("admin")->user()->id;
				$customProperty			= 	['old'=>$model,'attribute'=>$obj];
				$logMessage				= 	'{USER_NAME} profile has been updated by {CAUSER_NAME}';
				$this->storeActivityLogs($log_type,$performedOn,$causedBy,$customProperty,$logMessage);
				/* GENERATE ACTIVITY LOGS SCRIPT END HERE */
				DB::commit();

				Session::flash('success',trans("User has been updated successfully"));
				return Redirect::route($this->sectionName.".index");
			}
		}
	}// end update()
	 
	/**
	* Function for update Currency  status
	*
	* @param $modelId as id of Currency 
	* @param $modelStatus as status of Currency 
	*
	* @return redirect page. 
	*/	
	public function delete($userId = 0){
	
		$userDetails	=	Admin::find($userId); 
		if(empty($userDetails)) {
			return Redirect::route($this->sectionName.".index");
		}
		if($userId){
			$email 			=	'delete_'.$userId.'_'.$userDetails->email;		
			$phone_number 	=	'delete_'.$userId.'_'.$userDetails->phone_number;		
			Admin::where('id',$userId)->update(array('is_deleted'=>1,'email'=>$email,'phone_number'=>$phone_number,'deleted_at'=>date("Y-m-d H:i:s")));

			/* GENERATE ACTIVITY LOGS SCRIPT START HERE */
			$log_type				= 	$userDetails->user_role.'_deleted';
			$performedOn			= 	$userDetails;
			$causedBy				= 	Auth::guard("admin")->user()->id;
			$customProperty			= 	['old'=>$userDetails,'attribute'=>Admin::find($userId)];
			$logMessage				= 	'{USER_NAME} has been deleted by {CAUSER_NAME}';
			$this->storeActivityLogs($log_type,$performedOn,$causedBy,$customProperty,$logMessage);
			/* GENERATE ACTIVITY LOGS SCRIPT END HERE */

			Session::flash('flash_notice',trans("User has been removed successfully")); 
		}
		return Redirect::back();
	}// end delete()

	public function view($modelId = 0){
		$model	=	Admin::where('id',$modelId)->first();
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}

		$restaurantSubAdmins	=	DB::table("sub_admin_restaurants")
									->where("sub_admin_restaurants.user_id",$model->id)
									->leftJoin("admins","admins.restaurant_unique_id","sub_admin_restaurants.restaurant_id")
									->where("admins.is_active",1)
									->where("admins.is_deleted",0)
									->select("admins.name","admins.id","admins.email","sub_admin_restaurants.created_at","admins.phone_number","admins.restaurant_name","admins.restaurant_unique_id")
									->get()
									->toArray(); 
		return  View::make("admin.$this->sectionName.view",compact('model','restaurantSubAdmins'));
	} // end view()
	
	/**
	* Function for assign subadmin modal box
	*
	* @param null
	*
	* @return view page. 
	*/
	public function assignRestaurantModal(Request $request){
		$user_id		=	!empty($request->input('user_id')) ? $request->input('user_id') : 0;
		$model			=	Admin::where('id',$user_id)
							->whereIn("user_role",[CHAIN_SUPERVISOR,CORPORATE_CHEF_ID])
							->where("is_deleted",0)
							->first();	
		if(!empty($model)) {
			$assignedRestaurant	=	DB::table("sub_admin_restaurants")
									->where("sub_admin_restaurants.user_id",$user_id)
									->leftJoin("admins","admins.restaurant_unique_id","sub_admin_restaurants.restaurant_id")
									->where("admins.is_active",1)
									->where("admins.is_deleted",0)
									->pluck("admins.restaurant_unique_id","admins.restaurant_unique_id")
									->toArray(); 
				
			$restaurantDetails		=	DB::table("admins")
										->where("is_active",1)
										->where("is_deleted",0)
										->where("user_role",USER_RESTAURENT_ROLE_ID)
										->pluck("restaurant_name","restaurant_unique_id")
										->toArray();
			return View::make("admin.$this->sectionName.assign_restaurant_modal",compact('assignedRestaurant','restaurantDetails','user_id'));
		}  
	}

	/**
	* Function for save new customer
	*
	* @param null
	*
	* @return redirect page. 
	*/
	function assignRestaurant(Request $request){
		$this->request->replace($this->arrayStripTags($request->all()));
		$formData						=	$request->all();
		if(!empty($formData)){
			$validator 					=	Validator::make(
				$request->all(),
				array(
					'restaurant_id'		=> 'required',
				),
				array(
					"restaurant_id.required"	=>	trans("The restaurant field is required."),
				)
			);
			if ($validator->fails()){
				$response		=	array(
										'success' 		=> 	0,
										'errors' 		=> 	$validator->errors()
									);
				return Response::json($response); 
				die;
			}else{ 
				if(empty($request->input('user_id'))){
					$response	=	array(
						'success'	=> 	2,
						'message'	=> 	trans("Something went wrong. Please try again")
					);
					return Response::json($response); 
					die; 
				}
				$checkIfrestaurantExist	=	DB::table("admins")
											->where("id",$request->input('user_id'))
											->where("is_deleted",0)
											->where("is_active",1)
											->first();
				if(empty($checkIfrestaurantExist)){
					$response	=	array(
						'success'	=> 	2,
						'message'	=> 	trans("Something went wrong. Please try again")
					);
					return Response::json($response); 
					die; 
				}
				if(!empty($request->input('restaurant_id'))){
					DB::table("sub_admin_restaurants")
					->where("user_id",$request->input('user_id'))
					->delete();
					foreach($request->input('restaurant_id') as $key => $restval){
						$obj1 					=  	new SubAdminRestaurant;
						$obj1->user_id			=  	$request->input('user_id');
						$obj1->restaurant_id	=  	$restval;
						$obj1->assigned_by		=	Auth::guard('admin')->user()->id;
						$obj1->save();
						
						/* GENERATE ACTIVITY LOGS SCRIPT START HERE */
						$log_type				= 	'restaurant_assigned';
						$performedOn			= 	$obj1;
						$causedBy				= 	Auth::guard("admin")->user()->id;
						$customProperty			= 	[$obj1];
						$logMessage				= 	'{RESTAURANT_NAME} has been assigned to {USER_NAME} by {CAUSER_NAME}';
						$this->storeActivityLogs($log_type,$performedOn,$causedBy,$customProperty,$logMessage);
						/* GENERATE ACTIVITY LOGS SCRIPT END HERE */
					}

				}
				$response	=	array(
					'success'	=> 	1,
					'message'	=> 	trans("Location assigned successfully")
				);
				return Response::json($response); 
				die; 
			}
		}
	}//end save()
	
}// end Class