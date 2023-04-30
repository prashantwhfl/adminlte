<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController; 
use App\Models\Blocks;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator;
use Illuminate\Http\Request;
/**
* BlocksController Controller
*
* Add your methods in the class below
*
*/
class BlocksController extends BaseController {

	public $model		=	'Blocks';
	public $sectionName	=	'Blocks';
	public $sectionNameSingular	=	'Block';
	
	public function __construct(Request $request) {
		parent::__construct();
		$this->request = $request;
		View::share('modelName',$this->model);
		View::share('sectionName',$this->sectionName);
		View::share('sectionNameSingular',$this->sectionNameSingular);
	}
	 
	/**
	* Function for display all blocks
	*
	* @param null
	*
	* @return view page. 
	*/
	public function index(Request $request){ 
		$DB					=	Blocks::query();
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
						$DB->where("blocks.name",'like','%'.$fieldValue.'%');
					}
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$DB->select("blocks.*");
		$sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'created_at';
	    $order  = ($request->input('order')) ? $request->input('order')   : 'DESC';
		$results = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends($request->all())->render();
		//dd($results);
		return  View::make("admin.$this->model.index",compact('results','searchVariable','sortBy','order','query_string'));
	}// end index()

	
	/**
	* Function for add new block
	*
	* @param null
	*
	* @return view page. 
	*/
	public function add(){
		$languages					=	DB::select("CALL GetAcitveLanguages(1)");
        if(!empty($languages)){
			foreach($languages as &$language){
				$language->image	=	LANGUAGE_IMAGE_URL.$language->image;
			}
		}
		$default_language			=	Config::get('default_language');
		$language_code 				=   $default_language['language_code'];

		return  View::make("admin.$this->model.add",compact('languages' ,'language_code'));
	}// end add()
	
/**
* Function for save new block
*
* @param null
*
* @return redirect page. 
*/
	function save(Request $request){
		//$request->replace($this->arrayStripTags($request->all()));
		$thisData					=	$request->all();
		$default_language			=	Config::get('default_language');
		$language_code 				=   $default_language['language_code'];
		$dafaultLanguageArray		=	$thisData['data'][$language_code];
		$validator = Validator::make(
			array(
				'image' 	=> $request->file("image"),
				// 'page_name' 	=> $request->input("page_name"),
				'name' 			=> $dafaultLanguageArray['name'],
				'description' 			=> $dafaultLanguageArray['description'],
			),
			array(
				// 'page_name' => 'required',
				'name' => 'required:unique:blocks',
				'image'	=> 'nullable:mimes:'.IMAGE_EXTENSION,
			)
		);
		
		if ($validator->fails()) {	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$blockOrder	=	DB::table("blocks")->count();
			$blockOrder	=	$blockOrder+1;
			
			DB::beginTransaction();
			$obj = new Block;
			$obj->page_name   		= $dafaultLanguageArray['name'];
			$obj->name   			= $dafaultLanguageArray['name'];
			$obj->page_name_slug   	= str_replace(" ","-",$dafaultLanguageArray['name']);
			$obj->slug   			= str_replace(" ","-",$dafaultLanguageArray['name']);
			$obj->description   	= $dafaultLanguageArray['description'];
			$obj->block_order   	= $blockOrder;
			if($request->hasFile('image')){
				$extension 	=	 $request->file('image')->getClientOriginalExtension();
				$fileName	=	time().'-image.'.$extension;
				
				$folderName     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath			=	BLOCK_IMAGE_ROOT_PATH.$folderName;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if($request->file('image')->move($folderPath, $fileName)){
					$obj->image	=	$folderName.$fileName;
				}
			}
			$objSave				= $obj->save();
			$last_id	=	$obj->id;
			if(!$objSave) {
				DB::rollback();
				Session::flash('error', trans("Something went wrong.")); 
				return Redirect::route($this->model.".index");
			}
			foreach ($thisData['data'] as $language_id => $value) {
				$BlockDescription				=  new BlockDescription();
				$BlockDescription->language_id	=	$language_id;
				$BlockDescription->parent_id	=	$last_id;
				$BlockDescription->name			=	$value['name'];		
				$BlockDescription->description	=	$value['description'];		
				$BlockDescription->save();
			}
			DB::commit();
			Session::flash('success',trans($this->sectionNameSingular." has been added successfully"));
			return Redirect::route($this->model.".index");
		}
	}//end save()
	
	/**
	* Function for update status
	*
	* @param $modelId as id of block 
	* @param $status as status of block 
	*
	* @return redirect page. 
	*/	
	public function changeStatus($modelId = 0, $status = 0){ 
		if($status == 0){
			$statusMessage	=	trans($this->sectionNameSingular." has been deactivated successfully");
		}else{
			$statusMessage	=	trans($this->sectionNameSingular." has been activated successfully");
		}
		$this->_update_all_status('blocks',$modelId,$status);
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	}// end changeStatus()
	
	/**
	* Function for display page for edit block
	*
	* @param $modelId id  of block
	*
	* @return view page. 
	*/
	public function edit($modelId = 0){
		
		$model				=	Blocks::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->model.".index",$modelId);
		}
		return  View::make("admin.$this->model.edit",compact('model'));
	} // end edit()
	
	
	/**
	* Function for update course module 
	*
	* @param $modelId as id of course module 
	*
	* @return redirect page. 
	*/
	function update($modelId = 0,Request $request){
		$model				=	Blocks::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->model.".index",$modelId);
		}
		
		///$request->replace($this->arrayStripTags($request->all()));
		$thisData					=	$request->all();
		$validator 					=	Validator::make(
			$request->all(),
			array(
				'section_name'			    => 'required',
				'title'					    => 'required',
				'image'      			    => 'mimes:'.IMAGE_EXTENSION,
				
			),
			array(
				"section_name.required"	     =>	trans("The section name field is required."),
				"title.required"			=>	trans("The title field is required."),
				"image.mimes"			    =>	trans("The image must be a file of type: 'jpeg, jpg, png, gif, bmp.'."),
			)
		);
		
		
		if ($validator->fails()) {	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj = $model;
			$obj->section_name      = $thisData['section_name'];
			$obj->title   			= $thisData['title'];
			$obj->subtitle   			= $thisData['subtitle'];
			$obj->video_url   			= $thisData['video_url'];
			$obj->link   			= $thisData['link'];
			$obj->description   	= $thisData['description'];
			if($request->hasFile('image')){
				$extension 	=	 $request->file('image')->getClientOriginalExtension();
				$fileName	=	time().'-'.date('Y-m-d').'-image.'.$extension;
				
				$folderPath			=	SECTION_IMAGE_URL_IMAGE_ROOT_PATH;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if($request->file('image')->move($folderPath, $fileName)){
					$obj->image	=	$fileName;
				}
			}
		//	dd($obj);
			$objSave				= $obj->save();
			$last_id	=	$obj->id;
			if(!$objSave) {
				DB::rollback();
				Session::flash('error', trans("Something went wrong.")); 
				return Redirect::route($this->model.".index");
			}
			
			DB::commit();
			Session::flash('success',trans($this->sectionNameSingular." has been updated successfully"));
			return Redirect::route($this->model.".index");
		}
	}// end update()
	
	
}// end BlocksController
