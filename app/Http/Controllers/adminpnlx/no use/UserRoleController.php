<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
// use App\Model\Admin;
use App\Model\UserRole;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use Illuminate\Http\Request;
use App, Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator;

/**
*  UserRoleController Controller
*
* Add your methods in the class below
*
*/
class UserRoleController extends BaseController {

	public $model		=	'UserRole';
	public $sectionName	=	'UserRole';
	public $headingName	=	'User Role';
	public $sectionNameSingular	=	'User Role';
	public $sectionNamePlural	='User Roles';
	public function __construct(Request $request) {
		parent::__construct();
		View::share('modelName',$this->model);
		View::share('sectionName',$this->sectionName);
		View::share('headingName',$this->headingName);
		View::share('sectionNameSingular',$this->sectionNameSingular);
		View::share('sectionNamePlural',$this->sectionNamePlural);
		$this->request = $request;
	}
	 
	/**
	* Function for display all User Roles
	*
	* @param null
	*
	* @return view page. 
	*/
	public function index(Request $request){
		$DB					=	UserRole::where("is_deleted",0);
		$searchVariable		=	array(); 
		$inputGet			=	$request->all();
		if (($request->all())) {
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
			foreach($searchData as $fieldName => $fieldValue){
				if($fieldValue != ""){
					if($fieldName == "user_role"){
						$DB->where("user_roles.role_name",'like','%'.$fieldValue.'%');
					}
					if($fieldName == "is_active"){
						$DB->where("user_roles.is_active",$fieldValue);
					}
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		// $model 					= 	$DB->paginate(0)
		$sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'created_at';
	    $order  = ($request->input('order')) ? $request->input('order')   : 'DESC';
		$results = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends($request->all())->render();   	
		return  View::make("admin.$this->sectionName.index",compact('results','searchVariable','sortBy','order','query_string'));
	}// end index()
// 	/**
// 	* Function for add User Role
// 	*
// 	* @param null
// 	*
// 	* @return view page. 
// 	*/
	public function add(){

		
		return  View::make("admin.$this->sectionName.add");
	}// end add()
	
// /**
// * Function for save User Role
// *
// * @param null
// *
// * @return redirect page. 
// */
	function save(Request $request){
		$this->request->replace($this->arrayStripTags($request->all()));
		$formData						=	$request->all();
		if(!empty($formData)){
			$validator = Validator::make(
				$formData,
				array(
					'user_role' => 'required'
				)
			);
			
			if ($validator->fails()){	
				return Redirect::back()->withErrors($validator)->withInput();
			}else{
				$roleValue=str_replace(' ','_',$request->user_role);
				$roleValue= strtolower($roleValue);
				$roleValue=preg_replace("/[^a-zA-Z_]/", "", $roleValue);
				$count=UserRole::whereIn('role_value',[$roleValue])->count();
				if($count>0){
					$errors 			=	$validator->messages();
					$errors->add('user_role', trans("User role already exists"));
					return Redirect::back()->withErrors($errors)->withInput();	
				}else{

					
					DB::beginTransaction();
					$obj 					    =   new UserRole;
					$obj->role_name		 		= 	$request->user_role;
					$obj->role_value		 	= 	$roleValue;
					$objSave					=   $obj->save();
					if(!$objSave) {
						DB::rollback();
						Session::flash('error', trans("Something went wrong.")); 
						return Redirect::route($this->model.".index");
					}
					DB::commit();
					Session::flash('success',trans($this->sectionNameSingular." has been added successfully"));
					return Redirect::route($this->sectionName.".index");
				}
			}
	    }
	}//end save()
	
	public function edit($modelId = 0){
		$model					=	UserRole::findorFail($modelId);
		if(empty($model)) {
			return Redirect::back();
		}
		return  View::make("admin.$this->sectionName.edit",compact('model'));
	} // end edit()
	
	
// 	/**
// 	* Function for update user role module
// 	*
// 	* @param $modelId as id of User Role module 
// 	*
// 	* @return redirect page. 
// 	*/
	function update($modelId = 0,Request $request){
		$model					=	UserRole::findorFail($modelId);
		if(empty($model)) {
			return Redirect::back();
		}
		$this->request->replace($this->arrayStripTags($request->all()));
		$formData						=	$request->all();
		if(!empty($formData)){
			$validator = Validator::make(
				$formData,
				array(
					'user_role' => 'required'
				)
			);
			if ($validator->fails()){
				return Redirect::back()->withErrors($validator)->withInput();
			}else{ 
				$roleValue=str_replace(' ','_',$request->user_role);
				$roleValue= strtolower($roleValue);
				$roleValue=preg_replace("/[^a-zA-Z_]/", "", $roleValue);
				$count=UserRole::whereIn('role_value',[$roleValue])->where('id','!=',$modelId)->count();
				if($count>0){
					$errors 			=	$validator->messages();
					$errors->add('user_role', trans("User role already exists"));
					return Redirect::back()->withErrors($errors)->withInput();	
				}
				else{
					DB::beginTransaction();
					$obj 					    =   UserRole::find($modelId);
					$obj->role_name		 		= 	$request->user_role;
					$obj->role_value		 	= 	$roleValue;
					$objSave					=   $obj->save();
					if(!$objSave) {
						DB::rollback();
						Session::flash('error', trans("Something went wrong.")); 
						return Redirect::route($this->model.".index");
					}

					/* GENERATE ACTIVITY LOGS SCRIPT START HERE */
					$log_type				= 	'user_role_updated';
					$performedOn			= 	$model;
					$causedBy				= 	Auth::guard("admin")->user()->id;
					$customProperty			= 	['old'=>$model,'attribute'=>$obj];
					$logMessage				= 	'{USER_NAME} user_role has been updated by {CAUSER_NAME}';
					$this->storeActivityLogs($log_type,$performedOn,$causedBy,$customProperty,$logMessage);
					/* GENERATE ACTIVITY LOGS SCRIPT END HERE */
					DB::commit();

					Session::flash('success',trans("User Role has been updated successfully"));
					return Redirect::route($this->sectionName.".index");
				}
			}
		}
	}// end update()

/**
	* Function for update status
	*
	* @param $modelId as id of User Role 
	* @param $status as status of User Role 
	*
	* @return redirect page. 
	*/	
	public function changeStatus($modelId = 0, $status = 0){ 
		if($status == 0){
			$statusMessage	=	trans($this->sectionNameSingular." has been deactivated successfully");
		}else{
			$statusMessage	=	trans($this->sectionNameSingular." has been activated successfully");
		}
		$this->_update_all_status('user_roles',$modelId,$status);
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	}// end changeStatus()

	/**
	* Function for delete User Role
	*
	* @param $modelId as id of User Role
	* @param $modelStatus as status of UserRole 
	*
	* @return redirect page. 
	*/	
	public function delete($Id = 0){
		$roleDetails	=	UserRole::find($Id); 
		if(empty($roleDetails)) {
			return Redirect::route($this->sectionName.".index");
		}
		if($Id){		
			UserRole::where('id',$Id)->update(array('is_deleted'=>1));
			Session::flash('flash_notice',trans($this->sectionNameSingular." has been removed successfully")); 
		}
		return Redirect::back();
	}// end delete()
	

	
}// end Class