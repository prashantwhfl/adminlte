<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\SubServices;
use App\Models\Service;
use Illuminate\Http\Request;
use App, Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator;

/**
*  LinkssController Controller
*
* Add your methods in the class below
*
*/
class SubServicesController extends BaseController {

	public $model		=	'SubServices';
	public $sectionName	=	'SubServices';
	public $headingName	=	'Sub Services';
	public $sectionNameSingular	=	'Sub Service';
	public $sectionNamePlural	='Sub Services';
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
	* Function for display all Links
	*
	* @param null
	*
	* @return view page. 
	*/
	public function index(Request $request){
	  	$DB					=	SubServices::query();
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
					if($fieldName == "name"){
						$DB->where("sub_services.name",'like','%'.$fieldValue.'%');
					}
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$model 					= 	$DB->leftjoin("services" ,"sub_services.service_id", "=", "services.id")->select("sub_services.*", "services.name as sub_service_name")->where('sub_services.is_deleted',0);
		$sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'created_at';
	    $order  = ($request->input('order')) ? $request->input('order')   : 'DESC';
		$results = $model->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends($request->all())->render();   
		
		return  View::make("admin.$this->sectionName.index",compact('results','searchVariable','sortBy','order','query_string'));
	}// end index()
	
	/**
	* Function for add new Sub Service
	*
	* @param null
	*
	* @return view page. 
	*/
	public function add(){
		
		$services_list = Service::where('sub_service_status',1)->where('status',1)->where('is_deleted',0)->pluck('name', 'id');
		return  View::make("admin.$this->sectionName.add",compact('services_list'));
	}// end add()
	
	/**
	* Function for save Sub Services module 
	*
	* @param $modelId as id of Link module 
	*
	* @return redirect page. 
	*/
	function save(Request $request){
		
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
	   
		$validator 					=	Validator::make(
			$request->all(),
			array(
				'name'				        => 'required',
				'service'					=> 'required',
				'description' 				=> 'required',
				'image'      			    => 'required|mimes:'.IMAGE_EXTENSION,
				
			),
			array(
				"name.required"			    =>	trans("The name field is required."),
				"service.required"			=>	trans("The service field is required."),
				"description.required"		 	=>	trans("The description is required."),
				"image.required"			    =>	trans("The image is required."),
				"image.mimes"			    =>	trans("The image must be a file of type: 'jpeg, jpg, png, gif, bmp.'."),
			)
		);
	
		if ($validator->fails()) {	
		//s	dd($validator);
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					=    new SubServices;
			$obj->name		 		= 	$thisData['name'];
			$obj->service_id	    = 	$thisData['service'];
			$obj->description		= 	$thisData['description'];
			if($request->hasFile('image')){
				$extension 	=	 $request->file('image')->getClientOriginalExtension();
				$fileName	=	time().'-sub_service.'.$extension;
				
				$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath			=	SUB_SERVICES_IMAGE_URL_IMAGE_ROOT_PATH.$folderName;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if($request->file('image')->move($folderPath, $fileName)){
					$obj->image	=	$folderName.$fileName;
				}
			}
			$objSave				=   $obj->save();
			$last_id				=	$obj->id;
			if(!$objSave) {
				DB::rollback();
				Session::flash('error', trans("Something went wrong.")); 
				return Redirect::route($this->sectionName.".index");
			}
			DB::commit();
			Session::flash('success',trans($this->sectionNameSingular." has been added successfully"));
			return Redirect::route($this->sectionName.".index");
		}
	}// end update()

	
	
	public function edit($modelId = 0){
		$model				=	SubServices::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		$services_list = Service::where('sub_service_status',1)->where('status',1)->where('is_deleted',0)->pluck('name', 'id');
		return  View::make("admin.$this->sectionName.edit",compact('model','services_list'));
	} // end edit()
	
	
	/**
	* Function for update Review module 
	*
	* @param $modelId as id of Review module 
	*
	* @return redirect page. 
	*/
	function update($modelId = 0,Request $request){
		$model							=	SubServices::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
	    //dd($thisData);

		$validator 					=	Validator::make(
			$request->all(),
			array(
				'name'				        => 'required',
				'service'					=> 'required',
				'description' 				=> 'required',
				'image'      			    => 'mimes:'.IMAGE_EXTENSION,
				
			),
			array(
				"name.required"			    =>	trans("The name field is required."),
				"service.required"			=>	trans("The service field is required."),
				"rating.required"		    =>	trans("The rating is required."),
				"description.required"		 	=>	trans("The description is required."),
				"image.mimes"			    =>	trans("The image must be a file of type: 'jpeg, jpg, png, gif, bmp.'."),
			)
		);
	
		if ($validator->fails()) {	
		//s	dd($validator);
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					=   $model;
			$obj->name		 		= 	$thisData['name'];
			$obj->service_id	    = 	$thisData['service'];
			$obj->description		= 	$thisData['description'];
			
			//dd($obj);
			if($request->hasFile('image')){
				$extension 	=	 $request->file('image')->getClientOriginalExtension();
				$fileName	=	time().'-sub_service.'.$extension;
				
				$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath			=	SUB_SERVICES_IMAGE_URL_IMAGE_ROOT_PATH.$folderName;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if($request->file('image')->move($folderPath, $fileName)){
					$obj->image	=	$folderName.$fileName;
				}
			}
			$objSave				=   $obj->save();
			$last_id				=	$obj->id;
			if(!$objSave) {
				DB::rollback();
				Session::flash('error', trans("Something went wrong.")); 
				return Redirect::route($this->sectionName.".index");
			}
			DB::commit();
			Session::flash('success',trans($this->sectionNameSingular." has been updated successfully"));
			return Redirect::route($this->sectionName.".index");
		}
	}// end update()
	
	/**
	* Function for Delete Review  
	*
	* @param $modelId as id of Review 
	* @param $modelStatus as status of Review 
	*
	* @return redirect page. 
	*/	
	public function delete($subCatId = 0){
		$reviewDetails	=	SubServices::find($subCatId);
		//dd($reviewDetails);
		if(empty($reviewDetails)) {
			return Redirect::route($this->model.".index");
		}
		if($subCatId){		
			SubServices::where('id',$subCatId)->update(array('is_deleted'=>1));
			Session::flash('flash_notice',trans($this->sectionNameSingular." has been removed successfully")); 
			return Redirect::route($this->sectionName.".index");
		}
		return Redirect::back();
	}// end delete()
	
	
	public function changeStatus($modelId = 0, $status = 0){ 
		$subServicesId							=	SubServices::find($modelId);
		if(empty($subServicesId)) {
			return Redirect::back();
		}
	
		if($status == 0){
			SubServices::where('id',$modelId)->update(array('status'=>1));
			$statusMessage	=	trans($this->sectionNamePlural." has been deactivated successfully");
		}else{
			SubServices::where('id',$modelId)->update(array('status'=>0));
			$statusMessage	=	trans($this->sectionNamePlural." has been activated successfully");
		}
	
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	}// end changeStatus()

	
}// end Class