<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\Banners;
use Illuminate\Http\Request;
use App, Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator;

/**
*  LinkssController Controller
*
* Add your methods in the class below
*
*/
class BannersController extends BaseController {

	public $model		=	'Banners';
	public $sectionName	=	'Banners';
	public $headingName	=	'Banners';
	public $sectionNameSingular	=	'Banner';
	public $sectionNamePlural	='Banners';
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
	  	$DB					=	Banners::query();
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
					if($fieldName == "title"){
						$DB->where("banners.title",'like','%'.$fieldValue.'%');
					}
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$model 					= 	$DB->select("banners.*");
		$sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'created_at';
	    $order  = ($request->input('order')) ? $request->input('order')   : 'DESC';
		$results = $model->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends($request->all())->render();   
		//dd($results);
		return  View::make("admin.$this->sectionName.index",compact('results','searchVariable','sortBy','order','query_string'));
	}// end index()


	public function edit($modelId = 0){
		$model				=	Banners::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
	//	dd($model);
		return  View::make("admin.$this->sectionName.edit",array('model' => $model));
	} // end edit()
	
	
	/**
	* Function for update Link module 
	*
	* @param $modelId as id of Link module 
	*
	* @return redirect page. 
	*/
	function update($modelId = 0,Request $request){
		$model							=	Banners::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
	    //dd($thisData);

		$validator 					=	Validator::make(
			$request->all(),
			array(
				'title'			   => "required",
				'subtitle' 			    => 'required',
				'link' 	   => 'required',
			),
			array(
				"title.required"		=>	trans("The title field is required."),
				"subtitle.required"		=>	trans("The subtitle field is required."),
				"link.required"  =>	trans("The link field is required."),
				
			)
		);
				// 'description' 	   => 'required',
				// 'image'      			    => 'mimes:'.IMAGE_EXTENSION,
				// "description.required"  =>	trans("The description field is required."),
				// "image.mimes"			=>	trans("The image must be a file of type: 'jpeg, jpg, png, gif, bmp.'.")
	
		if ($validator->fails()) {	
		//s	dd($validator);
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					=   $model;
			$obj->title		 		= 	$thisData['title'];
			$obj->subtitle		 		= 	$thisData['subtitle'];
			//$obj->description		 		= 	$thisData['description'];
			$obj->link		 		= 	$thisData['link'];
			// if($request->hasFile('image')){
			// 	$extension 	=	 $request->file('image')->getClientOriginalExtension();
			// 	$fileName	=	 date('Y-m-d').'-'.time().'-banner.'.$extension;
				
			// 	//$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
			// 	$folderPath			=	BANNER_IMAGE_URL_IMAGE_ROOT_PATH;
			// 	if(!File::exists($folderPath)) {
			// 		File::makeDirectory($folderPath, $mode = 0777,true);
			// 	}
			// 	if($request->file('image')->move($folderPath, $fileName)){
			// 		$obj->image	=	$fileName;
			// 	}
			// }
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

	
}// end Class