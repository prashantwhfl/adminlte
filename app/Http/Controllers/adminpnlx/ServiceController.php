<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\Service;
// use App\Model\CategoryDescription;
// use App\Model\EmailTemplate;
// use App\Model\EmailAction;
use Illuminate\Http\Request;
use App, Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator;

/**
*  ServicesController Controller
*
* Add your methods in the class below
*
*/
class ServiceController extends BaseController {

	public $model		=	'Service';
	public $sectionName	=	'Service';
	public $headingName	=	'Service';
	public $sectionNameSingular	=	'Service';
	public $sectionNamePlural	='Sub Service';
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
	* Function for display all Service 
	*
	* @param null
	*
	* @return view page. 
	*/
	public function index(Request $request){
		$DB					=	Service::where("is_deleted",0);
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
						$DB->where("services.name",'like','%'.$fieldValue.'%');
					}
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$model 					= 	$DB->select("services.*");
		$sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'created_at';
	    $order  = ($request->input('order')) ? $request->input('order')   : 'DESC';
		$results = $model->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends($request->all())->render();   
		return  View::make("admin.$this->sectionName.index",compact('results','searchVariable','sortBy','order','query_string'));
	} // end index()

	/**
	* Function for add Service
	*
	* @param null
	*
	* @return view page. 
	*/
	public function add(){
		return  View::make("admin.$this->sectionName.add");
	}// end add()
	
	/**
	* Function for save Service
	*
	* @param null
	*
	* @return redirect page. 
	*/
	function save(Request $request){
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
		$validator = Validator::make(
			array(
				'name' 			    		=> $thisData['name'],
				'mini_description' 			=> $thisData['mini_description'],
				'full_description' 			=> $thisData['full_description'],
			),
			array(
				'name' => 'required',
				'mini_description' => 'required',
				'full_description' => 'required',
			),
			array (
				"name.required"							=>	trans("Please enter name of service."),
				"mini_description.required"				=>	trans("Please enter Mini Description."),
				"full_description.required"				=>	trans("Please enter full Description."),
			)
		);
		
		if ($validator->fails()){	
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			$extension 			=	$request->file('image')->getClientOriginalExtension();
			$fileName			=	time().'-image.'.$extension;
			$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
			$folderPath			=	WEBSITE_ADMIN_SERVICE_IMG_ROOT_PATH.$folderName;
			if(!File::exists($folderPath)) {
				File::makeDirectory($folderPath, $mode = 0777,true);
			}
			if($request->file('image')->move($folderPath, $fileName)){
				$main_image	= $folderName.$fileName;
			}

			$extension 			=	 $request->file('select_image')->getClientOriginalExtension();
			$fileName			=	time().'-hover-image.'.$extension;
			$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
			$folderPath			=	WEBSITE_ADMIN_SERVICE_IMG_ROOT_PATH.$folderName;
			if(!File::exists($folderPath)) {
				File::makeDirectory($folderPath, $mode = 0777,true);
			}
			if($request->file('select_image')->move($folderPath, $fileName)){
				$hover_image	= $folderName.$fileName;
			}

			DB::beginTransaction();
			$obj 					    =   new Service;
			$obj->image		 			= 	$main_image;
			$obj->image_hover		 	= 	$hover_image;
			$obj->name		 			= 	$thisData['name'];
			$obj->mini_desription		= 	$thisData['mini_description'];
			$obj->description		 	= 	$thisData['full_description'];
			$obj->sub_service_status	= 	($thisData['sub_cat_status'] == 'on') ? 1:0;
			$obj->status		 		= 	1;
			$obj->is_deleted		 	= 	0;
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
	}//end save()
	
	public function edit($modelId = 0){
		$model				=	Service::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		
		return  View::make("admin.$this->sectionName.edit",array('model' => $model));
	} // end edit()
	
	
	/**
	* Function for update Service module 
	*
	* @param $modelId as id of Service module 
	*
	* @return redirect page. 
	*/
	function update($modelId = 0,Request $request){
		$model							=	Service::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
		$validator = Validator::make(
			array(
				'name' 			    		=> $thisData['name'],
				'mini_description' 			=> $thisData['mini_description'],
				'full_description' 			=> $thisData['full_description'],
			),
			array(
				'name' => 'required',
				'mini_description' => 'required',
				'full_description' => 'required',
			),
			array (
				"name.required"							=>	trans("Please enter name of service."),
				"mini_description.required"				=>	trans("Please enter Mini Description."),
				"full_description.required"				=>	trans("Please enter full Description."),
			)
		);
		
		if ($validator->fails()) {	
			return Redirect::back()->withErrors($validator)->withInput();
		}else{

			if($request->hasFile('image')){ 
				$exploded_sting = explode("service", $model->image);
				if (!empty($exploded_sting[1])) {
					if(File::exists(WEBSITE_ADMIN_SERVICE_IMG_ROOT_PATH.$exploded_sting[1])) {
						File::delete(WEBSITE_ADMIN_SERVICE_IMG_ROOT_PATH.$exploded_sting[1]);	
					}
				}
				$extension 		=	$request->file('image')->getClientOriginalExtension();
				$fileName		=	time().'-image.'.$extension;
				$folderName     = 	strtoupper(date('M'). date('Y'))."/";
				$folderPath		=	WEBSITE_ADMIN_SERVICE_IMG_ROOT_PATH.$folderName;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if($request->file('image')->move($folderPath, $fileName)){
					$main_image =	$folderName.$fileName;
				}
			}

			if($request->hasFile('select_image')){ 
				if(File::exists(WEBSITE_ADMIN_SERVICE_IMG_ROOT_PATH.$model->image_hover)) {
					File::delete(WEBSITE_ADMIN_SERVICE_IMG_ROOT_PATH.$model->image_hover);	
				}
				$extension 		=	$request->file('select_image')->getClientOriginalExtension();
				$fileName		=	time().'-hover-image.'.$extension;
				$folderName     = 	strtoupper(date('M'). date('Y'))."/";
				$folderPath		=	WEBSITE_ADMIN_SERVICE_IMG_ROOT_PATH.$folderName;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if($request->file('select_image')->move($folderPath, $fileName)){
					$hover_image =	$folderName.$fileName;
				}
			}

			$obj 						=  Service::find($modelId);
			if (!empty($main_image)) {
				$obj->image		 			= 	$main_image;
			}
			if (!empty($hover_image)) {
				$obj->image_hover		 	= 	$hover_image;
			}
			$obj->name		 			= 	$thisData['name'];
			$obj->mini_desription		= 	$thisData['mini_description'];
			$obj->description		 	= 	$thisData['full_description'];
			$obj->sub_service_status	= 	($thisData['sub_cat_status'] == 'on') ? 1:0;
			$obj->status		 		= 	1;
			$obj->is_deleted		 	= 	0;
			$objSave					=   $obj->save();
			Session::flash('success',trans($this->sectionNameSingular." has been updated successfully"));
			return Redirect::route($this->sectionName.".index");
		}
	}// end update()

	/**
	* Function for update status
	*
	* @param $modelId as id of Service 
	* @param $status as status of Service 
	*
	* @return redirect page. 
	*/	
	public function changeStatus($id){ 
		$status = Service::where('id',$id)->value('status');
		if($status == '1'){
			Service::where('id',$id)->update(['status' =>'0']);
			$statusMessage	=	trans($this->sectionNameSingular." has been deactivated successfully");
			Session::flash('flash_notice', $statusMessage); 
			return Redirect::back();
		}else{
			Service::where('id',$id)->update(['status' => '1']);
			$statusMessage	=	trans($this->sectionNameSingular." has been activated successfully");
			Session::flash('flash_notice', $statusMessage); 
			return Redirect::back();	
		}
	}// end changeStatus()

	/**
	* Function for delete Service
	*
	* @param $modelId as id of Service 
	* @param $modelStatus as status of Service 
	*
	* @return redirect page. 
	*/	
	public function delete($Id = 0){
		$catDetails	=	Service::find($Id); 
		if(empty($catDetails)) {
			return Redirect::route($this->sectionName.".index");
		}
		if($Id){		
			Service::where('id',$Id)->update(array('is_deleted'=>1));
			Session::flash('flash_notice',trans($this->sectionNameSingular." has been removed successfully")); 
		}
		return Redirect::back();
	}// end delete()
	

	/**
	* Function for display all Sub Service of modelId
	*
	* @param null
	*
	* @return view page. 
	*/
	public function subCatListing($modelId, Request $request){
		$cat 					=   Category::find($modelId); 
		$DB						= 	DB::table("categories")
									->where('categories.is_deleted',0)
									->where('categories.parent_id',$cat->id)
									->leftJoin("category_descriptions","category_descriptions.parent_id","categories.id")
									->where("language_id",1)
									->select("categories.*","categories.title","category_descriptions.description");						
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
						$DB->where("categories.title",'like','%'.$fieldValue.'%');
					}
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		// $model 					= 	$DB->get(); echo '<pre>'; print_r($model); die;
		$sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'created_at';
	    $order  = ($request->input('order')) ? $request->input('order')   : 'DESC';
		$results = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends($request->all())->render();   	
		return  View::make("admin.$this->sectionName.subCatList",compact('results','searchVariable','sortBy','order','query_string','cat'));
	}// end index()
		/**
	* Function for add Sub Category
	*
	* @param null
	*
	* @return view page. 
	*/
	public function addSubCat($modelId=0, Request $request){ 
		$model 						=	Category::where("id",$modelId)
										->where("is_deleted",0)->first();
										
		$languages					=	DB::select("CALL GetAcitveLanguages(1)");
        if(!empty($languages)){
			foreach($languages as &$language){
				$language->image	=	LANGUAGE_IMAGE_URL.$language->image;
			}
		}
		$default_language			=	Config::get('default_language');
		$language_code 				=   $default_language['language_code'];

		return  View::make("admin.$this->sectionName.addSubCat",compact('languages' ,'language_code','model'));
	}// end add()
	
	/**
	* Function for save Sub Category
	*
	* @param null
	*
	* @return redirect page. 
	*/
	function storeaddSubCat($modelId=0,Request $request){
		$model							=	Category::find($modelId)->where("is_deleted",0); 
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
		$default_language				=	Config::get('default_language');
		$language_code 					=   $default_language['language_code'];
		$dafaultLanguageArray			=	$thisData['data'][$language_code];
		$validator = Validator::make(
			array(
				'title' 			    => $dafaultLanguageArray['title'],
				'description' 			=> $dafaultLanguageArray['description'],
			),
			array(
				'title' => 'required:unique:categories',
			)
		);
		
		if ($validator->fails()){	
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					    =   new Category;
			$obj->title		 			= 	$dafaultLanguageArray['title'];
			$obj->parent_id				=	$modelId;
			$objSave					=   $obj->save();
			$last_id					=	$obj->id;
			if(!$objSave) {
				DB::rollback();
				Session::flash('error', trans("Something went wrong.")); 
				return Redirect::route($this->model.".index");
			}
			foreach ($thisData['data'] as $language_id => $value) {
				$desc					=  new CategoryDescription();
				$desc->language_id		=	$language_id;
				$desc->parent_id		=	$last_id;
				$desc->title			=	$value['title'];		
				$desc->description		=	$value['description'];	
				//echo '<pre>'; print_r($desc); die;	
				$desc->save();
			}
			DB::commit();
			Session::flash('success',trans($this->sectionNamePlural." has been added successfully"));
			return Redirect::route($this->sectionName.".subCatList",$modelId);
		}
	}//end save()

	public function SubCatEdit($modelId = 0,$id,Request $request){
		$catUser				=	Category::find($modelId); 
		if(empty($catUser)) {
			return Redirect::back();
		}
		$model					=	Category::where('id',$id)
									->where("parent_id",$modelId)
									->where("is_deleted",0)
									->first();
		if(empty($model)) {
			return Redirect::back();
		}					
		$CatDescription	=	CategoryDescription::where('parent_id', '=',  $id)->get();
		$multiLanguage		 	=	array();
		if(!empty($CatDescription)){
			foreach($CatDescription as $description) {
				$multiLanguage[$description->language_id]['title']		=	$description->title;				
				$multiLanguage[$description->language_id]['description']=	$description->description;	
			}
		}
		  $languages			=	DB::select("CALL GetAcitveLanguages(1)");
        if(!empty($languages)){
			foreach($languages as &$language){
				$language->image	=	LANGUAGE_IMAGE_URL.$language->image;
			}
		}
		$default_language	=	Config::get('default_language');
		$language_code 		=   $default_language['language_code'];

		return  View::make("admin.$this->sectionName.SubCatEdit",array('languages' => $languages,'language_code' => $language_code,'modelId' => $modelId,'model' => $model,'multiLanguage' => $multiLanguage));
	} // end edit()
	
	
	/**
	* Function for update Category module 
	*
	* @param $modelId as id of Category module 
	*
	* @return redirect page. 
	*/
	function updateSubCat($modelId,$id,Request $request){
		$catUser							=	Category::find($modelId);
		if(empty($catUser)) {
			return Redirect::back();
		}
		$model					=	Category::where('id',$id)
									->where("parent_id",$modelId)
									->where("is_deleted",0)
									->first();
		if(empty($model)) {
			return Redirect::back();
		}	
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
		$default_language				=	Config::get('default_language');
		$language_code 					=   $default_language['language_code'];
		$dafaultLanguageArray			=	$thisData['data'][$language_code];
		$validator = Validator::make(
			array(
				'title' 				=> $dafaultLanguageArray['title'],
				'description' 			=> $dafaultLanguageArray['description'],
			),
			array(
				'title'					 => "required",
				'description' 			 => 'required',
			)
		);
		
		if ($validator->fails()) {	
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					=   $model;
			$obj->title		 		= 	$dafaultLanguageArray['title'];
			$objSave				=   $obj->save();
			$last_id				=	$obj->id;
			if(!$objSave) {
				DB::rollback();
				Session::flash('error', trans("Something went wrong.")); 
				return Redirect::route($this->sectionName.".index");
			}
			CategoryDescription::where("parent_id",$last_id)->delete();
			foreach ($thisData['data'] as $language_id => $value) {
				$desc					=  new CategoryDescription();
				$desc->language_id		=	$language_id;
				$desc->parent_id		=	$last_id;
				$desc->title			=	$value['title'];		
				$desc->description		=	$value['description'];
				$desc->save();
			}
			DB::commit();
			Session::flash('success',trans($this->sectionNamePlural." has been updated successfully"));
			return Redirect::route($this->sectionName.".subCatList",$modelId);
		}
	}// end update()

	/**
	* Function for update status
	*
	* @param $modelId as id of Category 
	* @param $status as status of Category 
	*
	* @return redirect page. 
	*/	
	public function changeSubCatStatus($modelId = 0,$id, $status = 0){ 
		$catUser							=	Category::find($modelId);
		if(empty($catUser)) {
			return Redirect::back();
		}
		$model								=	Category::where('id',$id)
												->where("parent_id",$modelId)
												->first();
		if(empty($model)) {
			return Redirect::back();
		}	
		if($status == 0){
			$statusMessage	=	trans($this->sectionNamePlural." has been deactivated successfully");
		}else{
			$statusMessage	=	trans($this->sectionNamePlural." has been activated successfully");
		}
		$this->_update_all_status('categories',$id,$status);
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	}// end changeStatus()

	/**
	* Function for delete Category
	*
	* @param $modelId as id of Category 
	* @param $modelStatus as status of Category 
	*
	* @return redirect page. 
	*/	
	public function SubCatDelete($modelId,$id = 0){
		$catUser							=	Category::find($modelId);
		if(empty($catUser)) {
			return Redirect::back();
		}
		$model								=	Category::where('id',$id)
												->where("parent_id",$modelId)
												->where("is_deleted",0)
												->first();
		if(empty($model)) {
			return Redirect::back();
		}	
		else{		
			Category::where('id',$id)->update(array('is_deleted'=>1));
			Session::flash('flash_notice',trans($this->sectionNamePlural." has been removed successfully")); 
		}
		return Redirect::back();
	}// end delete()
	
}// end Class