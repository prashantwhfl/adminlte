<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\Blogs;
use Illuminate\Http\Request;
use App, Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator;

/**
*  LinkssController Controller
*
* Add your methods in the class below
*
*/
class BlogsController extends BaseController {

	public $model		=	'Blogs';
	public $sectionName	=	'Blogs';
	public $headingName	=	'Blogs';
	public $sectionNameSingular	=	'Blogs';
	public $sectionNamePlural	='Blog';
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
	  	$DB					=	Blogs::query();
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
						$DB->where("blogs.title",'like','%'.$fieldValue.'%');
					}
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$model 					= 	$DB->select("blogs.*")->where('is_deleted',0);
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
				'title'				        => 'required',
				'author'					=> 'required',
				'description' 				=> 'required',
				'image'      			    => 'required|mimes:'.IMAGE_EXTENSION,
				
			),
			array(
				"title.required"			    =>	trans("The title field is required."),
				"author.required"			=>	trans("The author field is required."),
				"description.required"		 	=>	trans("The description is required."),
				"image.required"		 	=>	trans("The image is required."),
				"image.mimes"			    =>	trans("The image must be a file of type: 'jpeg, jpg, png, gif, bmp.'."),
			)
		);
	
		if ($validator->fails()) {	
		//s	dd($validator);
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					=    new Blogs;
			$obj->title		 		= 	$thisData['title'];
			$obj->author		    = 	$thisData['author'];
			$obj->description		 	= 	$thisData['description'];
			if($request->hasFile('image')){
				$extension 	=	 $request->file('image')->getClientOriginalExtension();
				$fileName	=	time().'-blog.'.$extension;
				
				$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath			=	BLOG_IMAGE_URL_IMAGE_ROOT_PATH.$folderName;
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
		$model				=	Blogs::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		//dd($model);
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
		$model							=	Blogs::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
	    //dd($thisData);

		$validator 					=	Validator::make(
			$request->all(),
			array(
			    'title'				        => 'required',
				'author'					=> 'required',
				'description' 				=> 'required',
				'image'      			    => 'mimes:'.IMAGE_EXTENSION,
				
			),
			array(
				"title.required"			    =>	trans("The title field is required."),
				"author.required"			=>	trans("The author field is required."),
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
			$obj->title		 		= 	$thisData['title'];
			$obj->author		    = 	$thisData['author'];
			$obj->description		 	= 	$thisData['description'];
			//dd($obj);
			if($request->hasFile('image')){
				$extension 	=	 $request->file('image')->getClientOriginalExtension();
				$fileName	=	time().'-blog.'.$extension;
				
				$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath			=	BLOG_IMAGE_URL_IMAGE_ROOT_PATH.$folderName;
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
	public function delete($blogId = 0){
		$blogDetails	=	Blogs::find($blogId);
		//dd($reviewDetails);
		if(empty($blogDetails)) {
			return Redirect::route($this->model.".index");
		}
		if($blogId){		
			Blogs::where('id',$blogId)->update(array('is_deleted'=>1));
			Session::flash('flash_notice',trans($this->sectionNameSingular." has been removed successfully")); 
			return Redirect::route($this->sectionName.".index");
		}
		return Redirect::back();
	}// end delete()

	
}// end Class