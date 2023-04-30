<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\SoftwarePartner;
use Illuminate\Http\Request;
use App, Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator;

/**
*  LinkssController Controller
*
* Add your methods in the class below
*
*/
class SoftwarePartnerController extends BaseController {

	public $model		=	'SoftwarePartner';
	public $sectionName	=	'SoftwarePartner';
	public $headingName	=	'Software Partner';
	public $sectionNameSingular	=	'Software Partner';
	public $sectionNamePlural	='Software Partners';
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
	  	$DB					=	SoftwarePartner::query();
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
						$DB->where("software_partner.name",'like','%'.$fieldValue.'%');
					}
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$model 					= 	$DB->select("software_partner.*")->where('is_deleted',0);
		$sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'created_at';
	    $order  = ($request->input('order')) ? $request->input('order')   : 'DESC';
		$results = $model->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends($request->all())->render();   
	//	dd($results);
		return  View::make("admin.$this->sectionName.index",compact('results','searchVariable','sortBy','order','query_string'));
	}// end index()
	
	
	
	/**
	* Function for add new Review
	*
	* @param null
	*
	* @return view page. 
	*/
	public function add(){
		return  View::make("admin.$this->sectionName.add");
	}// end add()
	
	
	
	/**
	* Function for save Review module 
	*
	* @param $modelId as id of Link module 
	*
	* @return redirect page. 
	*/
	function save(Request $request){
		
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
	   // dd($thisData);

		$validator 					=	Validator::make(
			$request->all(),
			array(
				'name'				        => 'required',
				'type'				        => 'required',
				'image'      			    => 'required|mimes:'.IMAGE_EXTENSION,
				
			),
			array(
				"name.required"			    =>	trans("The name field is required."),
				"type.required"			    =>	trans("The type field is required."),
				"image.required"			    =>	trans("The image is required."),
				"image.mimes"			    =>	trans("The image must be a file of type: 'jpeg, jpg, png, gif, bmp.'."),
			)
		);
	
		if ($validator->fails()) {	
		
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					=    new SoftwarePartner;
			$obj->name		 		= 	$thisData['name'];
			$obj->type		 		= 	$thisData['type'];
			if($request->hasFile('image')){
				$extension 	=	 $request->file('image')->getClientOriginalExtension();
				$fileName	=	time().'-reviewwe.'.$extension;
				
				$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath			=	SOFTWARE_PARTNER_IMAGE_URL_IMAGE_ROOT_PATH.$folderName;
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
		$model				=	SoftwarePartner::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		
		return  View::make("admin.$this->sectionName.edit",array('model' => $model));
	} // end edit()
	
	
	/**
	* Function for update Review module 
	*
	* @param $modelId as id of Review module 
	*
	* @return redirect page. 
	*/
	function update($modelId = 0,Request $request){
		$model							=	SoftwarePartner::find($modelId);
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
				'image'      			    => 'mimes:'.IMAGE_EXTENSION,
				
			),
			array(
				"name.required"			    =>	trans("The name field is required."),
				"image.mimes"			    =>	trans("The image must be a file of type: 'jpeg, jpg, png, gif, bmp.'."),
			)
		);
	
		if ($validator->fails()) {	
		
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					=   $model;
			$obj->name		 		= 	$thisData['name'];
			$obj->type		 		= 	$thisData['type'];
			if($request->hasFile('image')){
				$extension 	=	 $request->file('image')->getClientOriginalExtension();
				$fileName	=	time().'-reviewwe.'.$extension;
				
				$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath			=	SOFTWARE_PARTNER_IMAGE_URL_IMAGE_ROOT_PATH.$folderName;
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
	public function delete($Id = 0){
		$reviewDetails	=	SoftwarePartner::find($Id);
		//dd($reviewDetails);
		if(empty($reviewDetails)) {
			return Redirect::route($this->model.".index");
		}
		if($Id){		
			SoftwarePartner::where('id',$Id)->update(array('is_deleted'=>1));
			Session::flash('flash_notice',trans($this->sectionNameSingular." has been removed successfully")); 
			return Redirect::route($this->sectionName.".index");
		}
		return Redirect::back();
	}// end delete()

	
}// end Class